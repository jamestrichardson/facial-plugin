<?php
/**
 * This is the main administration page, if you have only one admin page you can put
 * directly its code here or using the tabsheet system link below
 */

// Chech whether we are indeed included by Piwigo.
defined('FACIAL_PATH') or die('Hacking attempt!');

global $template;

// TODO: turn this into a class
$train = 0;
$train_user_id = -1;
$train_user_name = "";

load_language('plugin.lang', FACIAL_PATH);
check_status(ACCESS_ADMINISTRATOR);

if(isset($_GET['tab']) && $_GET['tab'] == 'add') {
  echo "<!-- ADD TAB -->";
}
if(isset($_GET['tab']) && $_GET['tab'] == 'edit') {
  echo "<!-- EDIT TAB -->";
}
if(isset($_GET['tab']) && $_GET['tab'] == 'train') {
  echo "<!-- train tab -->";
  $train = 1;
  if(!isset($_GET['id'])) {
    array_push($page['errors'], l10n('Error looking up user for training purpose.'));
  }
  else {
    $train_user_id = $_GET['id'];
    
    $query = sprintf('SELECT * FROM %s WHERE `id` = %d;', FACIAL_TBL_PEOPLE, $train_user_id);
    $result = pwg_query($query);
    $row = pwg_db_fetch_assoc($result);

    $train_user_id = $row['id'];
    $train_user_name = $row['person_name'];


  }
}

// Add the admin.tpl template
// echo "<!-- loading template: " . dirname(__FILE__) . '/template/admin.tpl -->';
$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'
  )
);

// get the people we know about
// TODO: don't hard code this table name (make it a constent or a config or something)
$query = 'SELECT * FROM piwigo_facial_people WHERE id <> -1 ORDER BY id ASC;';
$result = pwg_query($query);
while($row = pwg_db_fetch_assoc($result)) {
  $template->append('Peoples', array('id'  => $row['id'], 'person_name' => $row['person_name']));
}

//$template->assign('id', 0);
//$template->assign('person_name', '');

// assign the path for URL forming
$template->assign('FACIAL_PATH', FACIAL_ADMIN);




// Assign all the variable we contructed
$template->assign('train', $train);
$template->assign('train_user_id', $train_user_id);
$template->assign('train_user_name', $train_user_name);

// this actually puts the data from the template into the screen
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
