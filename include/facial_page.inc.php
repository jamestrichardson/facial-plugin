<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $page, $template, $conf, $user, $tokens, $pwg_loaded_plugins;


# DO SOME STUFF HERE... or not !


$template->assign(array(
  // this is useful when having big blocks of text which must be translated
  // prefer separated HTML files over big lang.php files
  'INTRO_CONTENT' => load_language('intro.html', FACIAL_PATH, array('return'=>true)),
  'FACIAL_PATH' => FACIAL_PATH,
  'FACIAL_ABS_PATH' => realpath(FACIAL_PATH).'/',
  ));

$template->set_filename('facial_page', realpath(FACIAL_PATH . 'template/facial_page.tpl'));
$template->assign_var_from_handle('CONTENT', 'facial_page');
