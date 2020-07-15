<?php
/*
  Plugin Name: facial
  Version: auto
  Description: This is a proof of concept to do some facial recognition
  Plugin URI: auto
  Author: teknofile
  Author URI: https://teknofile.org
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
define('FACIAL_TABLE',    $prefixeTable . 'facial');
define('FACIAL_ADMIN',    get_root_url() . 'admin.php?page=plugin-' . FACIAL_ID);
define('FACIAL_PUBLIC',   get_absolute_root_url() . make_index_url(array('section' => 'facial')) . '/');
define('FACIAL_DIR',      PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'facial/');

// +-----------------------------------------------------------------------+
// | Add event handlers                                                    |
// +-----------------------------------------------------------------------+

// init the plugin
add_event_handler('init', 'facial_init');

// This is the common way to define event functions:
//    Create a new function for each event you want to handle

if(defined('IN_ADMIN')) {
    // file containing all admin  handlers functions

    $admin_file = FACIAL_PATH . '/include/admin_events.inc.php';

    // admin plugins menu link
    add_event_handler('get_admin_plugin_menu_links', 'facial_admin_plugin_menu_links', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);

    // new tab on photo page
    add_event_handler('tabsheet_before_select', 'facial_tabsheet_before_select', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);

    // new prefiler in Batch Manager
    add_event_handler('get_batch_manager_prefilters', 'facial_add_batch_manager_prefilters', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);
    add_event_handler('perform_batch_manager_prefilters', 'facial_perform_batch_manager_prefilters', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);

    // new action in Batch Manager
    add_event_handler('loc_end_element_set_global', 'facial_loc_end_element_set_global', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);
    add_event_handler('elemnt_set_global_action', 'facial_element_set_global_action', EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);
}
else
{
  // file containing all public handlers functions
  $public_file = FACIAL_PATH . 'include/public_events.inc.php';

  // add a public section
  add_event_handler('loc_end_section_init', 'facial_loc_end_section_init', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
  add_event_handler('loc_end_index', 'facial_loc_end_page', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);

  //add button on album and photos pages
  add_event_handler('loc_end_index', 'facial_add_button', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
  add_event_handler('loc_end_picture', 'facial_add_button', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);

  //prefilter on photo page
  add_event_handler('loc_end_picture', 'facial_loc_end_picture', EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
}

// file containing API function
$ws_file = FACIAL_PATH . 'include/ws_functions.inc.php';

// add API function
add_event_handler('ws_add_methods', 'facial-ws_add_methods', EVENT_HANDLER_PRIORITY_NEUTRAL, $ws_file);

/*
 * Event functions can also be wrapped in a class
*/

$menu_file = FACIAL_PATH . 'include/menu_events.class.php';

//add item to existing menu (EVENT_HANDLER_PRIORITY_NEUTRAL+10 is for compatibilitiy with Advanced Menu Manager plugin)
add_event_handler('blockmanager_apply', array('FacialMenu', 'blockmanager_apply1'), EVENT_HANDLER_PRIORITY_NEUTRAL+10, $menu_file);

// add a new menu block (the declaration must be done every time, in order to be able to manage the menu block in "Menus screen and Advanced Menu Manager)
add_event_handler('blockmanager_register_block', array('FacialMenu', 'blockmanager_apply2'), EVENT_HANDLER_PRIORITY_NEUTRAL, $menu_file);

// NOTE: blockmanager_apply1() and blockmanager_apply2() can (must) be merged

/*** 
 * plugin_initialization
 *  - check for updates
 *  - unserialize configuration
 *  - load language
 */

function facial_init()
{
  global $conf;

  // load plugin lang file
  load_language('plugin.lang', FACIAL_PATH);

  // prepare plugin configuration
  $conf['facial'] = safe_unserialize($conf['facial']);
  
}
