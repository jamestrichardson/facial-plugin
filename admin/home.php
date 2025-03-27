<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

// +-----------------------------------------------------------------------+
// | Home tab                                                              |
// +-----------------------------------------------------------------------+

// send variables to template
$template->assign(array(
  'facial' => $conf['facial'],
  'INTRO_CONTENT' => load_language('intro.html', FACIAL_PATH, array('return'=>true)),
  ));

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/home.tpl'));
