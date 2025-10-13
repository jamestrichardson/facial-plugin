<?php

defined('FACIAL_PATH') or die('Hacking attempt!');

/**
 * Generates a thumbnail of a face region from an image.
 *
 * @param string $imagePath Path to the original image.
 * @param int $x_min Left boundary of the face.
 * @param int $y_min Top boundary of the face.
 * @param int $x_max Right boundary of the face.
 * @param int $y_max Bottom boundary of the face.
 * @param int $thumbnailSize Size of the thumbnail (width/height).
 * @return string|false Base64 encoded thumbnail image data or false on failure.
 */
function facial_generate_face_thumbnail($imagePath, $x_min, $y_min, $x_max, $y_max, $thumbnailSize = 64)
{
  if (!file_exists($imagePath)) {
    return false;
  }

  // Get image info and create resource
  $imageInfo = getimagesize($imagePath);
  if ($imageInfo === false) {
    return false;
  }
  $mimeType = $imageInfo['mime'];

  switch ($mimeType) {
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($imagePath);
      break;
    case 'image/png':
      $sourceImage = imagecreatefrompng($imagePath);
      break;
    case 'image/gif':
      $sourceImage = imagecreatefromgif($imagePath);
      break;
    default:
      return false;
  }

  if (!$sourceImage) {
    return false;
  }

  // Calculate face dimensions
  $faceWidth = $x_max - $x_min;
  $faceHeight = $y_max - $y_min;

  // Create thumbnail canvas
  $thumbnail = imagecreatetruecolor($thumbnailSize, $thumbnailSize);

  // Copy and resize face region to thumbnail
  imagecopyresampled(
    $thumbnail, $sourceImage,
    0, 0, $x_min, $y_min,
    $thumbnailSize, $thumbnailSize, $faceWidth, $faceHeight
  );

  // Generate base64 encoded image
  ob_start();
  imagejpeg($thumbnail, null, 80);
  $thumbnailData = ob_get_contents();
  ob_end_clean();

  // Clean up resources
  imagedestroy($sourceImage);
  imagedestroy($thumbnail);

  return 'data:image/jpeg;base64,' . base64_encode($thumbnailData);
}

/**
 * Adds the facial command center block to the picture page template.
 *
 * Checks for video images and assigns the number of faces detected to the template.
 *
 * @return void
 */
function facial_command_center()
{
  global $page, $template, $conf, $picture;

  if (isset($picture['current']['is_gvideo']) and $picture['current']['is_gvideo'])
  {
    return;
  }

  $numFaces = facial_detect_and_store_faces($page['image_id']);

  // Query face metadata for this image
  global $prefixeTable;
  $table = $prefixeTable . 'facial_faces';
  $metaQuery = 'SELECT face_num, probability, x_min, y_min, x_max, y_max FROM ' . $table . ' WHERE image_id = ' . intval($page['image_id']) . ' ORDER BY face_num ASC';
  $metaResult = pwg_query($metaQuery);
  $faceMetadata = array();

  // Get image path for thumbnail generation
  $imageQuery = 'SELECT path FROM ' . IMAGES_TABLE . ' WHERE id = ' . intval($page['image_id']) . ' LIMIT 1;';
  $imageResult = pwg_query($imageQuery);
  $imagePath = null;
  if ($imageRow = pwg_db_fetch_assoc($imageResult)) {
    $imagePath = $imageRow['path'];
  }

  while ($row = pwg_db_fetch_assoc($metaResult)) {
    // Generate thumbnail for this face
    if ($imagePath && file_exists($imagePath)) {
      $thumbnail = facial_generate_face_thumbnail(
        $imagePath,
        $row['x_min'], $row['y_min'],
        $row['x_max'], $row['y_max']
      );
      $row['thumbnail'] = $thumbnail;
    }

    // Call facial_recognize_face with base64 thumbnail
    if (!empty($thumbnail) && function_exists('facial_recognize_face')) {
      $recognition = facial_recognize_face($thumbnail);
      if (is_array($recognition)) {
        // Subjects (array of matches)
        $row['recognized_subject'] = isset($recognition['subjects'][0]['subject']) ? $recognition['subjects'][0]['subject'] : null;
        $row['recognized_similarity'] = isset($recognition['subjects'][0]['similarity']) ? $recognition['subjects'][0]['similarity'] : null;
        // Age
        if (isset($recognition['age'])) {
          $row['recognized_age'] = isset($recognition['age']['result']) ? $recognition['age']['result'] : null;
          $row['recognized_age_low'] = isset($recognition['age']['low']) ? $recognition['age']['low'] : null;
          $row['recognized_age_high'] = isset($recognition['age']['high']) ? $recognition['age']['high'] : null;
          $row['recognized_age_probability'] = isset($recognition['age']['probability']) ? $recognition['age']['probability'] : null;
        } else {
          $row['recognized_age'] = $row['recognized_age_low'] = $row['recognized_age_high'] = $row['recognized_age_probability'] = null;
        }
        // Gender
        if (isset($recognition['gender'])) {
          $row['recognized_gender'] = isset($recognition['gender']['value']) ? $recognition['gender']['value'] : null;
          $row['recognized_gender_probability'] = isset($recognition['gender']['probability']) ? $recognition['gender']['probability'] : null;
        } else {
          $row['recognized_gender'] = $row['recognized_gender_probability'] = null;
        }
      } else {
        $row['recognized_subject'] = null;
        $row['recognized_similarity'] = null;
        $row['recognized_age'] = $row['recognized_age_low'] = $row['recognized_age_high'] = $row['recognized_age_probability'] = null;
        $row['recognized_gender'] = $row['recognized_gender_probability'] = null;
      }
    } else {
      $row['recognized_subject'] = null;
      $row['recognized_similarity'] = null;
      $row['recognized_age'] = $row['recognized_age_low'] = $row['recognized_age_high'] = $row['recognized_age_probability'] = null;
      $row['recognized_gender'] = $row['recognized_gender_probability'] = null;
    }

    $faceMetadata[] = $row;
  }

  $template->set_prefilter('picture', 'facial_add_cc_prefilter');
  $template->assign('NUM_FACES', $numFaces);
  if (count($faceMetadata) > 0) {
    // Get subjects for dropdown
    if (function_exists('facial_get_subjects')) {
      $subjects = facial_get_subjects();
    } else {
      $subjects = array();
    }
    $template->assign('FACIAL_FACE_METADATA', $faceMetadata);
    $template->assign('FACIAL_SUBJECTS', $subjects);
  }
}

/**
 * Smarty prefilter callback to inject the facial command center HTML block.
 *
 * @param string $content The template content to modify.
 * @return string Modified template content with the command center block injected.
 */
function facial_add_cc_prefilter($content)
{
  global $conf;

  $facial_tpl = '<div align="center" id="facialCommandCenter">
    <form method="post" action="" id="facial-metadata-form">
    <table border="1" class="imageInfoTable" width="80%">
      <tr width="100%">
        <th colspan="3">Facial Recognition</th>
      </tr>
      <tr>
        <td>Faces Detected:</td>
        <td colspan="2">{$NUM_FACES}</td>
      </tr>
      {if isset($FACIAL_FACE_METADATA) && $FACIAL_FACE_METADATA|@count > 0}
        {foreach from=$FACIAL_FACE_METADATA item=face}
          <tr>
            <td>Face #{$face.face_num}</td>
            <td>
              <table border="0" width="100%">
                <tr>
                  <th></th>
                  <th>Probability</th>
                </tr>
                <tr>
                  <td rowspan="3" width="70px" align="center">
                    {if isset($face.thumbnail)}
                      <img src="{$face.thumbnail}" width="64" height="64" style="border: 1px solid #ccc;" alt="Face #{$face.face_num}" title="Face #{$face.face_num}"/>
                      <input type="hidden" name="thumbnail_{$face.face_num}" value="{$face.thumbnail}" />
                    {else}
                      <div style="width:64px;height:64px;border:1px solid #ccc;background:#f0f0f0;display:flex;align-items:center;justify-content:center;font-size:10px;">No Image</div>
                    {/if}
                  </td>
                  <td align="left">{$face.probability} this is actually a face.</td>
                </tr>
                <tr>
                  <td align="left" colspan="2">
                    Name: {$face.recognized_subject}<br />
                    Probability: {math equation="x*100" x=$face.recognized_similarity format="%.1f"}% <br />
                    Gender: {$face.recognized_gender} ({math equation="x*100" x=$face.recognized_gender_probability format="%.1f"}%) <br />
                    Age Low: {$face.recognized_age_low} <br />
                    Age High: {$face.recognized_age_high} <br />
                    Age Probability: {math equation="x*100" x=$face.recognized_age_probability format="%.1f"}% <br />
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <select name="subject_{$face.face_num}" style="width:110px;">
                <option value="">Assign subject</option>
                {foreach from=$FACIAL_SUBJECTS item=subject}
                  <option value="{$subject}"{if isset($face.subject) && $face.subject == $subject} selected{/if}>{$subject}</option>
                {/foreach}
              </select>
            </td>
          </tr>
        {/foreach}
      {/if}
    </table>
    <div style="margin-top:20px;">
      <button type="submit" name="save_face_metadata" value="1">Save Face Metadata</button>
    </div>
    </form>
  </div>';
  $search = '{$ELEMENT_CONTENT}';
  $replace = '{$ELEMENT_CONTENT}'.$facial_tpl;


  return str_replace($search, $replace, $content);
}
