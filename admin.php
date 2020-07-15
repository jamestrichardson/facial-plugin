<?php
/**
 * This is the main administration page, if you have only one admin page you can put
 * directly its code here or using the tabsheet system link below
 */

// Chech whether we are indeed included by Piwigo.
defined('FACIAL_PATH') or die('Hacking attempt!');

//load_language('plugin.lang', FACIAL_PATH);
//check_status(ACCESS_ADMINISTRATOR);

global $template;

// Add the admin.tpl template
// echo "<!-- loading template: " . dirname(__FILE__) . '/template/admin.tpl -->';
$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'
  )
);

// this actually puts the data from the template into the screen
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
