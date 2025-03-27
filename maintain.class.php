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
    // I (mistic100) chosed to handle install and update in the same method
    // you are free to do otherwize
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
    // delete local folder
    // use a recursive function if you plan to have nested directories
    foreach (scandir($this->dir) as $file)
    {
      if ($file == '.' or $file == '..') continue;
      unlink($this->dir.$file);
    }
    rmdir($this->dir);
  }
}
