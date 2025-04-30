<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

/**
 * This function is used to get the list of subjects from the Compreface API.
 *
 * @return array|int
 */

function facial_compreface_listsubjects()
{
  global $logger, $conf;
  $comprefaceConfig = safe_unserialize($conf['facial']);
  $url = $comprefaceConfig['compreface_protocol'] . "://" . $comprefaceConfig['compreface_host'] . ":" . $comprefaceConfig['compreface_port'] . "/api/v1/recognition/subjects/";

  $httpHeaders = array(
    "x-api-key: " . $comprefaceConfig['compreface_recog_api_key'],
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  $response = curl_exec($ch);
  if(curl_errno($ch)) {
    $logger->error('Compreface API error: ' . curl_error($ch));
    $error_msg = curl_errno($ch) . " - " . curl_error($ch);
    curl_close($ch);
    return 0;
  }
  curl_close($ch);
  $logger->info('Compreface API response: ' . $response);

  return json_decode($response, true);
}

/**
 *
 * This function sends a request to the Compreface API to detect faces in an image.
 *
 * @param string $imgPath
 * @return array|int
 */
function facial_compreface_detect($imgPath)
{
  global $conf, $logger;

  $comprefaceConfig = safe_unserialize($conf['facial']);

  $httpHeaders = array(
    "Content-Type: multipart/form-data",
    "x-api-key: " . $comprefaceConfig['compreface_api_key'],
  );


  $ch = curl_init();

  // TODO: Add testing to make sure the mimeType of what we are sending
  // actually is supported by the detection software. We don't want to send
  // a .PDF file if the detection software only supports .jpg files.

  $options = array(
    CURLOPT_URL => $comprefaceConfig['compreface_api_url'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $httpHeaders,
    CURLOPT_FAILONERROR => true,
    CURLOPT_POSTFIELDS => [
      "file" => new CURLFile($imgPath),
    ],
  );

  $logger->info('Making request to Compreface API URL: ' . $comprefaceConfig['compreface_api_url']);
  curl_setopt_array($ch, $options);
  $response = curl_exec($ch);
  if(curl_errno($ch)) {
    $logger->error('Compreface API error: ' . curl_error($ch));
    $error_msg = curl_errno($ch) . " - " . curl_error($ch);
  }
  curl_close($ch);

  $logger->info('Compreface API response: ' . $response);
  $response = json_decode($response, true);
  if(!isset($response['result'])) {
    // error happened because nothing was returned?
    return 0;
  }
  else
    return $response['result'];
}
