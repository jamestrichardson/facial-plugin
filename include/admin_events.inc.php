<?php
defined('FACIAL_PATH') or die("Hacking attempt!");

// admin plugins menu link
function facial_admin_plugin_menu_links($menu)
{
  $menu[] = array(
    'NAME' => l10n('Facial'),
    'URL' => FACIAL_ADMIN,
  );

  return $menu;
}

// add a tab on photo properties page
function facial_tabsheet_before_select($sheets, $id)
{
  if($id == 'photo')
  {
    $sheets['facial'] = array(
      'caption' => l10n('Facial'),
      'url' => FACIAL_ADMIN . '-photo&amp;image_id=' . $_GET['image_id'],
      );
  }

  return $sheets;
}

// add a prefilter to the batch downloader
function facial_add_batch_manager_prefilters($prefilters)
{
  $prefilters[] = array(
    'ID' => 'facial',
    'NAME' =-> l10n('Facial'),
  );

  return $prefilters;
}

// perform added prefilter
function facial_perform_batch_manager_+prefilters($filter_sets, $prefilter)
{
  if($prefilter == 'facial')
  {
    $query = 'SELECT id FROM ' . IMAGES_TABLE . ' ORDER BY RAND() LIMIT 20;';
    $filter_sets[] =  query2array($query, null, id);
  }

  return $filter_sets;
}

// add an action to the batch manager
function facial_loc_end_element_set_global()
{
  global $template;

  /*
   * CONTENT is option
   * for big contents it is advised to use a template file
   *
   * $template-> set_filename('facial_batchmanager_action', realpath(FACIAL_PATH . 'template/batchmanager_action.tpl'));
   * $content = $template->parse()'facial_batchmanager_action', true);
   */

  $template->append('element_set_global_plugins_actions', array(
    'ID' => 'facial',
    'NAME' => l10n('Facial'),
    'CONTENT' => '<label><input type="checkbox" name="check_facial"> ' . l10n('Check me!') . '</label>',
  ));
}

// perform added action
function facial_element_set_global_action($action, $collection)
{
  global $page;

  if($action == 'facial')
  {
    if(empty($_POST['check_facial']))
    {
      $page['warnings'][] = l10n('Nothing appended, but yhou didn\'t check the box!');
    }
    else
    {
      $page['infos'][] = l10n('Nothing appended, but you checked the box!');
    }
  }
}
