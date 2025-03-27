<?php

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');


class facial_maintain extends PluginMaintain
{
  private $default_conf = array(
    'compreface_api_url' => '',
    'compreface_api_key' => ''
  );

  private $table;
  private $dir;

  function __construct($plugin_id)
  {
    parent::__construct($plugin_id); // always call parent constructor

    global $prefixTable;

    // Class members can't be declared with computed values so initialization is done here
    $this->table = $prefixTable . 'facial';
    $this->dir = PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'facial/';
  }

   /**
   * Add an error message about the imageRotate plugin not being installed.
   *
   * @param string[] $errors The error array to add to.
   */
  protected function addFacialError(&$errors)
  {
    load_language('plugin.lang', __DIR__ . '/');
    $msg = sprintf(l10n('To install this plugin, you need to install the facial plugin first.'));
    if(is_array($errors)) {
      array_push($errors, $msg);
    }
    else {
      $errors = array($msg);
    }
  }

  /**
   * Plugin installation
   * 
   * Perform here all needed setup for the plugin installation such as creating the default config,
   * add database tables, add fields to existing, create local folders, etc.
   */
  function install($plugin_version, &$errors=array())
  {
    // Perform here all needed steps for the plugin installation such as:
    // * Creation of default configurations
    // * Add database tables
    // * Add fields to existing tables
    // * Create local folders

    global $conf;

    // add config params
    if(empty($conf['facial']))
    {
      // conf_update_param will serialize and escape array before database insertion
      // the third parameter indicates to update the $conf['facial] global variable as well
      //
      // The goal here is to set some sane defaults if the config is empty
      $this->default_conf['compreface_api_url'] = "enter your compreface api url here";
      $this->default_conf['compreface_api_key'] = 'enter your compreface api key here';

      // TODO: We should encrypt the api key before storing it to the DB.
      conf_update_param('facial', $this->default_conf, true);
    }
    else 
    {
      $oldConfig = safe_unserialize($conf['facial']);
      conf_update_param('facial', $oldConfig, true);
    }
  }

  /**
   * Plugin activation
   *
   * This function is triggered after installation, by manual activation or after a plugin update
   * for this last case you must manage updates tasks of your plugin in this function
   */
  function activate($plugin_version, &$errors=array())
  {
    global $pwg_loaded_plugins;
    $facial_active = false;

    if(array_key_exists(key: 'facial', array: $pwg_loaded_plugins)) {
      $facial_active = $pwg_loaded_plugins['facial']['state'] == "active";
    }

    if(!$this->facial_installed || !$facial_active) {
      $this->addFacialImageError(errors: &$errors);
    }
  }

  /**
   * Plugin deactivation
   *
   * Triggered before uninstallation or by manual deactivation
   */
  function deactivate()
  {
  }

  /**
   * Plugin (auto)update
   *
   * This function is called when Piwigo detects that the registered version of
   * the plugin is older than the version exposed in main.inc.php
   * Thus it's called after a plugin update from admin panel or a manual update by FTP
   */
  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }


  /**
   * Plugin uninstallation
   *
   * Perform here all cleaning tasks when the plugin is removed
   * you should revert all changes made in 'install'
   */
  function uninstall()
  {
    // delete configuration
    conf_delete_param('facial');

    // Delete Local Folder
    foreach (scandir(directory: $this->dir) as $file) {
      if($file == '.' or $file == '..') continue;
      unlink(filename: $this->dir.$file);
    }

    rmdir(directory: $this->dir);
  }
}
