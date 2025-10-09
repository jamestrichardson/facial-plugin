<?php
/**
 * This is the main administration page, if you have only one admin page you can put
 * directly its code here or using the tabsheet system link below
 */

// Chech whether we are indeed included by Piwigo.
defined('FACIAL_PATH') or die('Hacking attempt!');

global $template, $page, $conf;

include_once(PHPWG_ROOT_PATH . 'admin/include/tabsheet.class.php');

load_language('plugin.lang', FACIAL_PATH);
check_status(ACCESS_ADMINISTRATOR);


// Get the current tab
$page ['tab'] = isset($_GET['tab']) ? $_GET['tab'] : $page['tab'] = 'config';

// plugin tabsheet is not present on photo page
// tabsheet
$tabsheet = new tabsheet();
$tabsheet->set_id('facial');
// TODO: Get rid of this home/welcome page if it isn't needed?
$tabsheet->add('home', l10n('Welcome'), FACIAL_ADMIN . '-home');
$tabsheet->add('config', l10n('Configuration'), FACIAL_ADMIN . '-config');
$tabsheet->add('subjects', l10n('Subjects'), FACIAL_ADMIN . '-subjects');
$tabsheet->select($page['tab']);
$tabsheet->assign();

// include page
include(FACIAL_PATH . 'admin/' . $page['tab'] . '.php');

// template vars
$template->assign(array(
  'FACIAL_PATH' => FACIAL_PATH, // used for images, scripts, .... access
  'FACIAL_ABS_PATH' => realpath(FACIAL_PATH), // used for template inclusion (smarty templates needs real paths)
  'FACIAL_ADMIN' => FACIAL_ADMIN . 'foo',
  'PWG_TOKEN' => get_pwg_token()
));

$template->set_filename('plugin_admin_content', realpath(FACIAL_PATH) . 'template/admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'facial_content');
