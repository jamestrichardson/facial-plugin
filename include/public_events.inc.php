<?php

defined('FACIAL_PATH') or die('Hacking attempt!');

// add a prefilter on phooto page
function facial_loc_begin_picture()
{
	global $template;
	$template->set_prefilter('picture', 'facial_add_to_pic_info');
}

function facial_add_to_pic_info($content)
{
	$search = '#class="imageInfoTable">#';

	$replacement = 'class="imageInfoTable">
	<div id="Facial Name" class="imageInfo">
		<dt>{\'Facial\'|@translate}</dt>
		<dd>
{if $FACES}
			Faces #: {$NUM_FACES}<br/>
      Info: {$FACIAL_DATA}
{/if}
    </dd>
	</div>';

	return preg_replace($search, $replacement, $content, 1);
}

function facial_add_image_vars_to_template()
{
  global $page, $template, $conf, $prefixeTable, $logger;

  if(empty($page['image_id'])) {
    return;
  }

  $imageInfo = array();

  $query = 'SELECT * FROM ' . IMAGES_TABLE . ' WHERE id=' . $page['image_id'] . ' LIMIT 1;';
  $row = pwg_db_fetch_assoc(pwg_query($query));
  $imageInfo['image_file'] = $row['file'];
//  $imageInfo['image_path'] = DerivativeImage::url(IMG_LARGE, $row);

  $params = ImageStdParams::get_by_type(IMG_MEDIUM);
  $derivative = new DerivativeImage($params, new SrcImage($row));
  $imageInfo['image_path'] = $derivative->get_path(IMG_MEDIUM);

  $facialConfig = safe_unserialize($conf['facial']);
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $facialConfig['compreface_api_url'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: multipart/form-data",
        "x-api-key: " . $facialConfig['compreface_api_key']
    ],
    CURLOPT_POSTFIELDS => [
        "file" => new CURLFile($imageInfo['image_path'])
    ]
  ]);
  $output = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  }
  curl_close($ch);

  $facialOutput = json_decode($output, true);
  $facialData = '';
  $numFaces = 0;

  if(isset($facialOutput['code'])) {
    // TODO: handle errors this doesn't work
    if($facialOutput['code'] == '28') {
      $facialData = "Err 28: No faces detected";
    }
    else {
      $facialData = $facialOutput['code'] . "Unknown Error";
    }
    $facialData = $facialOutput['code'];
  }


  if(isset($facialOutput['result'])) {
    $numFaces = count($facialOutput['result']);

    // Test to see if we need an insert or an update
    $query = 'SELECT IMAGE_ID FROM ' . $prefixeTable . 'facial WHERE IMAGE_ID = ' . $page['image_id'];
    $result = query2array($query);
    if(count($result) > 0) {
      /*
        If we are here, we already have records in the database for this image. Let's not process it again.
        We should move this check to encompass the entire facial detection process and not even call the API if we already have done so
      */
      $facialData = "Already processed this image";
    }
    else
    {
      for($i = 0; $i < count($facialOutput['result']); $i++) {
        $query = ('INSERT INTO ' . $prefixeTable . 'facial (IMAGE_ID, TAG_ID, PROBABILITY, BOX_XMIN, BOX_YMIN, BOX_XMAX, BOX_YMAX ) VALUES (
          ' . $page['image_id'] . ',
          ' . $i . ',
          ' . $facialOutput['result'][$i]['box']['probability'] . ',
          ' . $facialOutput['result'][$i]['box']['x_min'] . ',
          ' . $facialOutput['result'][$i]['box']['y_min'] . ',
          ' . $facialOutput['result'][$i]['box']['x_max'] . ',
          ' . $facialOutput['result'][$i]['box']['y_max'] . '
        )');
        pwg_query($query);

        if(isset($facialOutput['result'][$i]['age'])) {
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET AGE_PROB = ' . $facialOutput['result'][$i]['age']['probability'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET AGE_HIGH = ' . $facialOutput['result'][$i]['age']['high'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET AGE_LOW = ' . $facialOutput['result'][$i]['age']['low'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
        }

        if(isset($facialOutput['result'][$i]['gender'])) {
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET GENDER_PROB = ' . $facialOutput['result'][$i]['gender']['probability'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET GENDER = \'' . $facialOutput['result'][$i]['gender']['value'] . '\' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
        }

        if(isset($facialOutput['result'][$i]['pose'])) {
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET POSE_PITCH = ' . $facialOutput['result'][$i]['pose']['pitch'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET POSE_ROLL = ' . $facialOutput['result'][$i]['pose']['roll'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET POSE_YAW = ' . $facialOutput['result'][$i]['pose']['yaw'] . ' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
        }

        if(isset($facialOutput['result'][$i]['landmarks'])) {
          pwg_query('UPDATE ' . $prefixeTable . 'facial SET landmarks = \'' . serialize($facialOutput['result'][$i]['landmarks']) . '\' WHERE IMAGE_ID = ' . $page['image_id'] . ' AND TAG_ID = ' . $i);
        }
      }
    }
  }

  // I want to test my new reusable compreface api callign function, so we're gonna detect faces again to set some debug code
  $Faces = facial_compreface_detect($imageInfo['image_path']);
  if(is_array($Faces)) {
    $logger->info("-- Returned " . count($Faces) . " faces from Compreface API");
    $logger->info(json_encode($Faces));
  }

  $template->assign(
    array	(
      'FACES' => "1",
      'NUM_FACES' => is_array($Faces) ? count($Faces) : 0,
      'FACIAL_DATA' => json_encode($template) //$facialData
    )
  );
}

function show_detected_faces()
{
  global $template, $conf, $page;

  if(isset($page['image_id'])) {
    $template->set_filenames(array('detected_faces' => realpath(dirname(__FILE__) . '/../template/detected_faces.tpl')));

    $query = 'SELECT * FROM ' . IMAGES_TABLE . ' WHERE id=' . $page['image_id'];
    $row = pwg_db_fetch_assoc(pwg_query($query));

    $query = 'SELECT BOX_XMIN, BOX_XMAX, BOX_YMIN, BOX_YMAX from piwigo_facial WHERE IMAGE_ID = ' . $page['image_id'] . ' LIMIT 1;';
    $row2 = pwg_db_fetch_assoc(pwg_query($query));

    $x = $row2['BOX_XMIN'];
    $y = $row2['BOX_YMIN'];
    $width = $row2['BOX_XMAX'] - $x;
    $height = $row2['BOX_YMAX'] - $y;


    $newImage = new Imagick(DerivativeImage::url(IMG_MEDIUM, $row));
    $newImage->cropImage($width, $height, $x, $y);

    $tag_query = "select id, name from " . TAGS_TABLE . " ORDER BY id";
    $template->assign('IMG_B64', base64_encode($newImage->getImageBlob()));
    $template->assign('DEBUG_FACIAL', "xmin: " . $x . " xmax: " . $row2['BOX_XMAX'] . " ymin: " . $y . " ymax: " . $row2['BOX_YMAX']);

    $template->assign('tags', get_taglist($tag_query));
    $template->assign('IMGID', $page['image_id']);


    $template->assign('ORIG_SRC', $row['path']);
    $template->pparse('detected_faces');
  }
  return;
}
