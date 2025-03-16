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

if(isset($_GET['tab'])) {
  echo "<!-- tab is set to: " . $_GET['tab'] . " -->";
}

// Add the admin.tpl template
$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'
  )
);
