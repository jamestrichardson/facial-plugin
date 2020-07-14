<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

// home tab

$template->assign(
  'facial' => $conf['facial'],
  'INTRO_CONTENT' => load_language('intro.html', FACIAL_PATH, array('return'=>true)),
));

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/tempalte/home.tpl'));