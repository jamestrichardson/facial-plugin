<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

function facial_get_subjects()
{
  global $conf;

  $subjects = array();

  $facialConfig = safe_unserialize($conf['facial']);
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $facialConfig['compreface_api_url'] . '/api/v1/recognition/subjects/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-api-key: " . $facialConfig['compreface_api_key']
    ],
  ]);
  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response, true);
  if(isset($data['result']) && is_array($data['result'])) {
    foreach($data['result'] as $collection) {
      if(isset($collection['name'])) {
        $subjects[] = $collection['name'];
      }
    }
  }

  return $subjects;
}

global $conf;
// $conf['facial'] = array(
//   'compreface_api_url' => 'foo',
//   'compreface_api_key' => 'bar',
// );

$dbg_conf = safe_unserialize($conf['facial']);
$debug_url = isset($dbg_conf['compreface_api_url']) ? $dbg_conf['compreface_api_url'] : 'not set';
$template->assign('debug_url', $debug_url . '/api/v1/recognition/subjects/');


$template->assign('subjects', serialize(facial_get_subjects()));

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/subjects.tpl'));
