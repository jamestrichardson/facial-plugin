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

function facial_batch_global()
{
  global $template, $logger;

  $logger->debug('Adding facial options to batch manager');

  load_language('plugin.lang', FACIAL_PATH);

  // Assign the template for batch management
  $template->set_filename('FACIAL_batch_global', dirname(__FILE__).'/template/batch_global.tpl');

  $FacialOptions = array();
  $template->assign('FacialOptions', $FacialOptions);


  // Add info on the "choose action" dropdown in the batch manager
  $template->append('element_set_global_plugins_actions', array(
    'ID' => 'facial', // ID of the batch manager action
    'NAME' => l10n('Recognize Faces'), // Description of the batch manager action
    'CONTENT' => ''
  ));
}

// Process the submit action
function facial_batch_global_submit($action, $collection)
{
  global $logger;
  $logger->debug('facial_batch_global_submit called with action: '.$action);

  if ($action == 'facial')
  {
    // Process facial recognition on the selected images
    foreach ($collection as $image_id)
    {
      /*
        For each image in the collection,
        I want to first send the image to CompreFace
        facial recognition service. It will return an
        array of recognized faces. Then i want to 'tag'
        the image with who is in the image.

        If a face isn't recognized, I want to add an
        unknown subject to CompreFace and tag the image with
        'unknown-face' tag.

        I consider a face with a similarity score of less than .95 as
        an unknown face.
      */
      $faces = facial_recognize_faces_by_image_id($image_id);
      if (is_array($faces)) {
        foreach ($faces as $face)
        {
          //$logger->debug('Face detected with subjects: '.var_export($face['subjects'], true));
          if (count($face['subjects']) > 0 && $face['subjects'][0]['similarity'] < 0.95)
          {
            // unknown face, it has a subject, but under the threshold of similarity that we know about
            $logger->debug("Unknown face: " . count($face['subjects']) . " subjects, similarity: " . $face['subjects'][0]['similarity']);
            $logger->debug('Unknown face detected, adding unknown-face tag');
            facial_add_tag_to_image('unknown-face', $image_id);

            // If it's an unknown face, I'd like to generate a UUID and add it to the CompreFace system
            // What will need to happen though is we'll need a system to "combine" known faces together
          }
          elseif (count($face['subjects']) > 0 && $face['subjects'][0]['similarity'] >= 0.95)
          {
            // recognized face
            $subject_name = $face['subjects'][0]['subject'];
            $logger->debug("Recognized face: $subject_name, adding tag to image");
            facial_add_tag_to_image($subject_name, $image_id);
          }
        }
      }
    }
  }
  return;
}
