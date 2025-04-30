<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $conf;

// +-----------------------------------------------------------------------+
// | Configuration tab                                                     |
// +-----------------------------------------------------------------------+

// save config
if (isset($_POST['save_config']))
{
  $conf['facial'] = array(
    'compreface_api_url' => isset($_POST['compreface_api_url']) ? $_POST['compreface_api_url'] : "default__url",
    'compreface_api_key' => isset($_POST['compreface_api_key']) ? $_POST['compreface_api_key'] : "enter_key_here",
    'compreface_recog_api_url' => isset($_POST['compreface_recog_api_url']) ? $_POST['compreface_recog_api_url'] : "default__url",
    'compreface_recog_api_key' => isset($_POST['compreface_recog_api_key']) ? $_POST['compreface_recog_api_key'] : "enter_key_here",
    'compreface_protocol' => isset($_POST['compreface_protocol']) ? $_POST['compreface_protocol'] : 'http',
    'compreface_port' => isset($_POST['compreface_port']) ? $_POST['compreface_port'] : 80,
    'compreface_host' => isset($_POST['compreface_host']) ? $_POST['compreface_host'] : 'localhost'
  );

  conf_update_param('facial', $conf['facial']);
  $page['infos'][] = l10n('Information data registered in database');
}

$subjects = facial_compreface_listsubjects();

/* We should be nicer about the cretion of tags. Right now we
just create a tag for each subject. This is not the best way to
do it. We should ask folks if we want, etc. */

if(is_array($subjects)){
  foreach($subjects['subjects'] as $subject){
    // we should check if it exists first /shrug
    create_tag($subject);
  }
}

// send config to template
$template->assign(array(
  'facial' => safe_unserialize($conf['facial']),
  'subjects' => $subjects['subjects'],
  ));

// $subjects = facial_compreface_listsubjects();
// $template->assign('subjects', $subjects);

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/config.tpl'));
