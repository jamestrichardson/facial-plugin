<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

/**
 * add a tab on photo properties page
 */
function facial_tabsheet_before_select($sheets, $id)
{
  if ($id == 'photo')
  {
    $sheets['facial'] = array(
      'caption' => l10n('facial'),
      'url' => FACIAL_PATH.'-photo&amp;image_id='.$_GET['image_id'],
      );
  }

  return $sheets;
}

/**
 * add a prefilter to the Batch Downloader
 */
function facial_add_batch_manager_prefilters($prefilters)
{
  $prefilters[] = array(
    'ID' => 'facial',
    'NAME' => l10n('facial'),
    );

  return $prefilters;
}

/**
 * perform added prefilter
 */
function facial_perform_batch_manager_prefilters($filter_sets, $prefilter)
{
  if ($prefilter == 'facial')
  {
    $query = '
SELECT id
  FROM '.IMAGES_TABLE.'
  ORDER BY RAND()
  LIMIT 20
;';
    $filter_sets[] = query2array($query, null, 'id');
  }

  return $filter_sets;
}

/**
 * add an action to the Batch Manager
 */
function facial_loc_end_element_set_global()
{
  global $template;

  /*
    CONTENT is optional
    for big contents it is advised to use a template file

    $template->set_filename('facial_batchmanager_action', realpath(facial_PATH.'template/batchmanager_action.tpl'));
    $content = $template->parse('facial_batchmanager_action', true);
   */
  $template->append('element_set_global_plugins_actions', array(
    'ID' => 'facial',
    'NAME' => l10n('facial'),
    'CONTENT' => '<label><input type="checkbox" name="check_facial"> '.l10n('Check me!').'</label>',
    ));
}

/**
 * perform added action
 */
function facial_element_set_global_action($action, $collection)
{
  global $page;

  if ($action == 'facial')
  {
    if (empty($_POST['check_facial']))
    {
      $page['warnings'][] = l10n('Nothing appened, but you didn\'t check the box!');
    }
    else
    {
      $page['infos'][] = l10n('Nothing appened, but you checked the box!');
    }
  }
}

/**
 * add template for a tab in users modal
 */
function facial_add_tab_users_modal()
{
  global $page, $template;

  if ('user_list' === $page['page'])
  {
    $template->set_filename('facial_notes', realpath(FACIAL_PATH.'template/notes.tpl'));
    $template->assign(array(
      'FACIAL_PATH' => FACIAL_PATH,
    ));
    $template->parse('facial_notes');
  }
}

/**
 * add a prefilter on batch manager unit
 *
 * PLUGINS_BATCH_MANAGER_UNIT_ELEMENT_SUBTEMPLATE is the hook for your HTML injection in the batch manager unit mode page
 *
 * If your data is located within the piwigo_images table in the database it will be loaded by default with the template and doesn't need to be pre-assigned here
 * You can directly use it by calling $element.[dataName] in your template
 */
function facial_loc_end_element_set_unit()
{
    global $template, $page;

    $template->assign(array(
        'FACIAL_PATH' => FACIAL_PATH,
    ));
    $template->append('PLUGINS_BATCH_MANAGER_UNIT_ELEMENT_SUBTEMPLATE', 'plugins/facial/template/batch_manager_unit.tpl');
}
