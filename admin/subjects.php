<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $conf;

function facial_get_subjects()
{
  $subjects = array();

  $facialConfig = safe_unserialize($conf['facial']);
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $facialConfig['compreface_api_url'] . '/api/v1/recognition/subjects/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
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

// $conf['facial'] = array(
//   'compreface_api_url' => 'foo',
//   'compreface_api_key' => 'bar',
// );

// +-----------------------------------------------------------------------+
// | Configuration tab                                                     |
// +-----------------------------------------------------------------------+

// save config
if (isset($_POST['save_config']))
{
  $conf['facial'] = array(
    'compreface_api_url' => isset($_POST['compreface_api_url']) ? $_POST['compreface_api_url'] : "default__url",
    'compreface_api_key' => isset($_POST['compreface_api_key']) ? $_POST['compreface_api_key'] : "enter_key_here",
    );

  conf_update_param('facial', $conf['facial']);
  $page['infos'][] = l10n('Information data registered in database');
}

// send config to template
$template->assign(array(
  'facial' => safe_unserialize($conf['facial'])
));

$template->assign('subjects', facial_get_subjects());

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/subjects.tpl'));
