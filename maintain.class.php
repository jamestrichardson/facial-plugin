<?php

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');


class facial_maintain extends PluginMaintain
{
  private $default_conf = array(
    'compreface_api_url' => '',
    'compreface_api_key' => '',
    'facial_cf_host' => '',
    'facial_cf_port' => '',
    'facial_cf_ssl' => false,
    'facial_cf_api_recoginition_key' => '',
    'facial_cf_api_detection_key' => '',
    'facial_cf_api_verification_key' => '',
    'facial_plugin_debug' => false,
    'facial_cf_detection_limit' => 0, // max number of faces to detect in an image
    'facial_cf_detection_prob_threshold' => 0.0, // min probability to consider a face detection valid
    'facial_cf_detection_face_plugins' => '' // comma separated list of face plugins to use for detection
  );

  private $table;
  private $table_faces; // table for storing face data of each image
  private $dir;

  function __construct($plugin_id)
  {
    parent::__construct($plugin_id); // always call parent constructor

    global $prefixeTable;

    // Class members can't be declared with computed values so initialization is done here
    $this->table = $prefixeTable . 'facial';
    $this->table_faces = $prefixeTable . 'facial_faces';
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
      $this->default_conf['compreface_api_url'] = "enter your compreface api url here";
      $this->default_conf['compreface_api_key'] = 'enter your compreface api key here';
      $this->default_conf['facial_cf_host'] = 'localhost';
      $this->default_conf['facial_cf_port'] = '8000';
      $this->default_conf['facial_cf_ssl'] = false;
      $this->default_conf['facial_cf_api_recoginition_key'] = 'enter your compreface recognition api key here';
      $this->default_conf['facial_cf_api_detection_key'] = 'enter your compreface detection api key here';
      $this->default_conf['facial_cf_api_verification_key'] = 'enter your compreface verification api key here';
      $this->default_conf['facial_plugin_debug'] = false;
      $this->default_conf['facial_cf_detection_limit'] = 5; // max number of faces to detect in an image
      $this->default_conf['facial_cf_detection_prob_threshold'] = 0.7; // min probability to consider a face detection valid
      $this->default_conf['facial_cf_detection_face_plugins'] = ''; // comma separated

      // TODO: We should encrypt the api key before storing it to the DB.
      conf_update_param('facial', $this->default_conf, true);
    }
    else
    {
      $oldConfig = safe_unserialize($conf['facial']);
      conf_update_param('facial', $oldConfig, true);
    }

    // Create facial metadata table
    $query = 'CREATE TABLE IF NOT EXISTS ' . $this->table_faces . ' (
      id INT(11) NOT NULL AUTO_INCREMENT,
      image_id INT(11) NOT NULL,
      face_num INT(11) NOT NULL,
      probability DOUBLE NOT NULL,
      x_min INT(11) NOT NULL,
      y_min INT(11) NOT NULL,
      x_max INT(11) NOT NULL,
      y_max INT(11) NOT NULL,
      PRIMARY KEY (id),
      KEY image_id (image_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    pwg_query($query);
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

    // if(!$this->facial_installed || !$facial_active) {
    //   $this->addFacialImageError($errors);
    // }
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
