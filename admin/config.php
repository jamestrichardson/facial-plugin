<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $conf;

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

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/config.tpl'));
