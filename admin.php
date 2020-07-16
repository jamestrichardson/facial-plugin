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
$train_albums = array();


load_language('plugin.lang', FACIAL_PATH);
check_status(ACCESS_ADMINISTRATOR);

if(isset($_GET['tab'])) {
  echo "<!-- tab is set to: " . $_GET['tab'] . " -->";
  switch($_GET['tab']) {
    case 'edit':
    case 'train':
      $train = 1;
      if(!isset($_GET['id'])) {
        array_push($page['errors'], l10n('Error looking up user for training purposes.'));
      }
      else {
        $train_user_id = $_GET['id'];
    
        $query = sprintf('SELECT * FROM %s WHERE `id` = %d;', FACIAL_TBL_PEOPLE, $train_user_id);
        $result = pwg_query($query);
        $row = pwg_db_fetch_assoc($result);

        $train_user_id = $row['id'];
        $train_user_name = $row['person_name'];

        // TODO: This is pretty insecure. How do we make sure they only have access to the albums they are supposed to?
        $query = sprintf('SELECT * FROM piwigo_categories WHERE `status` like "public"');
        $result = pwg_query($query);
        while($row = pwg_db_fetch_assoc($result)) {
          echo "<!-- found: " . $row['id'] . " and " . $row['name'] . " -->";
          $template->append('train_albums', array('id' => $row['id'], 'name' => $row['name']));
        }
      }
    break;
    case 'train-update':
      //echo "<!-- update train tab branch: " . $_GET['tab'] . " -->";
      $userid = pwg_db_real_escape_string($_REQUEST['userid']);
      $facial_train_album = pwg_db_real_escape_string($_REQUEST['facial_train']);

      $query = sprintf('UPDATE %s SET `train_album`=%d WHERE `id`=%d;', FACIAL_TBL_PEOPLE, $facial_train_album, $userid);
      pwg_query($query);
      break;
    default:
      echo "<!-- default train tab branch: " . $_GET['tab'] . " -->";
  }
}
  
-
// Add the admin.tpl template
$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'
  )
);

// get the people we know about
// TODO: don't hard code this table name (make it a constent or a config or something)
//$query = 'SELECT * FROM piwigo_facial_people WHERE id <> -1 ORDER BY id ASC;';
$query = 'SELECT p.id, p.person_name, p.train_album, c.name AS train_album_name FROM ' . FACIAL_TBL_PEOPLE . ' as p LEFT JOIN piwigo_categories AS c ON p.train_album = c.id WHERE p.id <> -1 ORDER BY p.id ASC;';
$result = pwg_query($query);
while($row = pwg_db_fetch_assoc($result)) {
  $template->append('Peoples', array('id'  => $row['id'], 'person_name' => $row['person_name'], 'training_album' => $row['train_album'], 'train_album_name' => $row['train_album_name']));
}

// assign the path for URL forming
$template->assign('FACIAL_PATH', FACIAL_ADMIN);

// Assign all the variable we contructed
$template->assign('train', $train);
$template->assign('train_user_id', $train_user_id);
$template->assign('train_user_name', $train_user_name);

// this actually puts the data from the template into the screen
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
