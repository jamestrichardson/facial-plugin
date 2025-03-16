<?php
/**
 * This is the main administration page, if you have only one admin page you can put
 * directly its code here or using the tabsheet system link below
 */

// Chech whether we are indeed included by Piwigo.
defined('FACIAL_PATH') or die('Hacking attempt!');

global $template;


load_language('plugin.lang', FACIAL_PATH);
check_status(ACCESS_ADMINISTRATOR);


// save config
if (isset($_POST['save_config']))
{
  $conf['facial'] = array(
    'rotate_hd' => boolval($_POST['rotate_hd'])
    );

  conf_update_param('facial', $conf['facial']);
  $page['infos'][] = l10n('Information data registered in database');
}

// send config to template
$template->assign(array(
  'facial' => $conf['facial']
  ));

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'template/admin_config.tpl'));

// template vars
$template->assign(array(
  'FACIAL_PATH'=> FACIAL_PATH, // used for images, scripts, ... access
  'FACIAL_ABS_PATH'=> realpath(FACIAL_PATH), // used for template inclusion (Smarty needs a real path)
  'FACIAL_ADMIN' => FACIAL_ADMIN,
  ));

// send page content
$template->assign_var_from_handle('ADMIN_CONTENT', 'facial_content');
