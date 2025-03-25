<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

/**
 *  Makes a curl call to a compreface server to determine if a face is in the image
 *
 * @param mixed $imgID
 * @return int
 */
function facial_DetectFace(int $imgID)
{
  $query = '
    SELECT
      path
    FROM '.IMAGES_TABLE.'
    WHERE id = '.$imgID.'
  ;';
  $row = pwg_db_fetch_assoc(pwg_query($query));
  if ($row == null) {
    return false;
  }

  $url = ""
  $apiKey = ""
  $filePath = $row['path'];

  if(!file_exists($filePath)) {
    return false;
  }

  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: multipart/form-data",
        "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => [
        "file" => new CURLFile($filePath)
    ]
]);
  $output = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);
  $output = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $array = json_decode($output, true);

  return count($array["result"]);

}

// add a prefilter on phooto page
function facial_loc_end_picture()
{
  global $template;
  $template->set_prefilter('picture', 'facial_picture_prefilter');
}

function facial_picture_prefilter($content)
{
  // TODO: Most of this should probably be moved up into `facial_loc_end_picture`
  $url = ""
  $apiKey = ""
  $filePath = "./upload/2025/03/14/20250314220708-726da3b1.jpg";

  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: multipart/form-data",
        "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => [
        "file" => new CURLFile($filePath)
    ]
]);
  $output = curl_exec($ch);

  global $picture, $template;

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $array = json_decode($output, true);

  $search = '{if $display_info.author and isset($INFO_AUTHOR)}';
  $replace = '<div id="Facial" class="imageInfo">
  <dt>{\'Facial Information\'|@translate}</dt>
  <dd><hr /></dd>
  <dd>Faces Detected:&nbsp;'. count($array["result"]) . '</dd>
  <dd>Image ID:&nbsp;'. $picture['current']['id'] . '</dd>
  <dd><hr /></dd>
  <dd style="color:blue;">{\'Piwigo rocks!!\'|@translate}</dd>
</div>';

  return str_replace($search, $replace.$search, $content);

}
