<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $conf;

// +-----------------------------------------------------------------------+
// | Configuration tab                                                     |
// +-----------------------------------------------------------------------+

// save config
if (isset($_POST['save_config']))
{
  $conf['facial'] = array(
    'compreface_api_url' => isset($_POST['compreface_api_url']) ? $_POST['compreface_api_url'] : "default__url",
    'compreface_api_key' => isset($_POST['compreface_api_key']) ? $_POST['compreface_api_key'] : "enter_key_here",
    'facial_cf_host' => isset($_POST['facial_cf_host']) ? $_POST['facial_cf_host'] : "localhost",
    'facial_cf_port' => isset($_POST['facial_cf_port']) ? $_POST['facial_cf_port'] : "8000",
    'facial_cf_ssl' => isset($_POST['facial_cf_ssl']) ? (bool)$_POST['facial_cf_ssl'] : false,
    'facial_cf_api_recoginition_key' => isset($_POST['facial_cf_api_recoginition_key']) ? $_POST['facial_cf_api_recoginition_key'] : "enter_key_here",
    'facial_cf_api_detection_key' => isset($_POST['facial_cf_api_detection_key']) ? $_POST['facial_cf_api_detection_key'] : "enter_key_here",
    'facial_cf_api_verification_key' => isset($_POST['facial_cf_api_verification_key']) ? $_POST['facial_cf_api_verification_key'] : "enter_key_here",
    'facial_plugin_debug' => isset($_POST['facial_plugin_debug']) ? (bool)$_POST['facial_plugin_debug'] : false,
    'facial_cf_detection_limit' => isset($_POST['facial_cf_detection_limit']) ? (int)$_POST['facial_cf_detection_limit'] : 0, // max number of faces to detect in an image
    'facial_cf_detection_prob_threshold' => isset($_POST['facial_cf_detection_prob_threshold']) ? (float)$_POST['facial_cf_detection_prob_threshold'] : 0.0, // min probability to consider a face detection valid
    'facial_cf_detection_face_plugins' => isset($_POST['facial_cf_detection_face_plugins']) ? $_POST['facial_cf_detection_face_plugins'] : '' // comma separated list of face plugins to use for detection
    );

  conf_update_param('facial', $conf['facial']);
  $page['infos'][] = l10n('Information data registered in database');
}

// send config to template
$template->assign(array(
  'facial' => safe_unserialize($conf['facial'])
  ));

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/config.tpl'));
