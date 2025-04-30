<?php
/*
  Version: 0.0.1
  Plugin Name: facial
  Plugin URI: http://piwigo.org/ext/index.php?eid=1008
  Description: This is a proof of concept to do some facial recognition
  Author: teknofile
  Author URI: https://teknofile.org
  Has Settings: true
*/

/**
  * This is the main file of the plugin, called by Piwigo in "include/common.inc.php" line 137.
  * At this point of the code, Piwigo is not completelyu initialized, so nothing should be done directly
  * except define constants and event handlers (see http://piwigo.org/doc/doku.php?id=dev:plugins)
**/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if(basename(dirname(__FILE__)) != 'facial')
{
  add_event_handler('init', 'facial_error');
  function facial_error()
  {
    global $page;
    $page['errors'][] = 'Facial folder name is incorrect, uninstall the plugin and rename it to "facial"';
  }
  return;
}

// +-----------------------------------------------------------------------+
// | Define plugin constants                                               |
// +-----------------------------------------------------------------------+

global $prefixeTable;

define('FACIAL_ID',       basename(dirname(__FILE__)));
define('FACIAL_PATH',     PHPWG_PLUGINS_PATH . FACIAL_ID . '/');
define('FACIAL_ADMIN',    get_root_url() . 'admin.php?page=plugin-' . FACIAL_ID);
define('FACIAL_TABLE' ,   $prefixeTable . 'facial');
define('FACIAL_PUBLIC',   get_absolute_root_url() . make_index_url(array('section' => 'facial')) . '/');
define('FACIAL_DIR',      PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'facial/');

include_once(FACIAL_PATH . 'include/functions.inc.php');
include_once(FACIAL_PATH . 'include/functions_compreface.inc.php');


// +-----------------------------------------------------------------------+
// | Add event handlers                                                    |
// +-----------------------------------------------------------------------+
// init the plugin
add_event_handler('init', 'facial_init');

/***
 * plugin_initialization
 *  - check for updates
 *  - unserialize configuration
 *  - load language
 */


// This is the common way to define event fucntions: create a new function for each event you want to handle:
if(defined('IN_ADMIN'))
{
  $admin_file = FACIAL_PATH . 'include/admin_events.inc.php';
}
else
{
  // File containing all public handerl functions
  $public_file = FACIAL_PATH . 'include/public_events.inc.php';
  add_event_handler('loc_begin_picture', 'facial_loc_begin_picture', EVENT_HANDLER_PRIORITY_NEUTRAL + 5, $public_file);
  add_event_handler('loc_begin_picture', 'facial_add_image_vars_to_template', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
//  add_event_handler('loc_end_picture', 'show_image_facialboxes', EVENT_HANDLER_PRIORITY_NEUTRAL + 5, $public_file);
  add_event_handler('loc_begin_page_tail', 'show_detected_faces', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
}

function facial_init()
{
  global $conf;

  load_language('plugin.lang', FACIAL_PATH);

  // Prepare plugin configuration
  $conf['facial'] = safe_unserialize($conf['facial']);
}
