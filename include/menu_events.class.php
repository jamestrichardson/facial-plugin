<?php
defined('FACIAL_PATH') or die("Hacking attempt!");

/* 
 * There is two ways to use class methods as event handlers:
 *
 * > add_event_handler('blockmanager_apoply', array('FacialMenu', 'blackmanager_apply'));
 *  in this case the method 'blockmanager_apply' must be a static method of the class 'FacialMenu'
 *
 *  > $myObj = new FacialMenu();
 *  > add_event_handler('blockmanager_apply', array(&$myObj, 'blockmanager_apply'));
 *    In this case the method 'blockmanager_apply' must be a public method of the object '$myObj'
 *
*/

class FacialMenu
{
  // add link in existing menu
  static function blockmanager_apply1($menu_ref_arr)
  {
    $menu = &$menu_ref_arr[0];
    if(($block = $menu->get_block('mbMenu')) != null)
    {
      $block->data[] = array(
        'URL' => FACIAL_PUBLIC,
        'TITLE' => l10n('Facial'),
        'NAME' => l10n('Facial'),
      );
    }
  }

  // add a new menu block
  static function blockmoanager_register_blocks($menu_ref_arr)
  {
    $menu = &$menu_ref_arr[0];
    if($menu->get_id() == 'menubar')
    {
      // identifier, title, owner
      $menu->register_block(new RegistredBlock('mbFacial', l10n('Facial'), 'Facial'));
    }
  }

  // fill the added menu block
  static function blockmanager_apply2($menu_ref_arr)
  {
    $menu = &$menu_ref_arr[0];

    if(($block = $menu->get_block('mbFacial')) != null)
    {
      $block->set_title(l10n('Facial'));
      $block->data['link1'] = 
        array(
          'URL' => get_absolute_root_url(),
          'TITLE' => l10n('First link'),
          'NAME' => l10n('Link 1'),
          'REL' => 'rel="nofollow"',
        );

      $block->data['link2'] = 
        array(
          'URL' => FACIAL_PUBLIC,
          'TITLE' => l10n('Second link'),
          'NAME' => l10n('Link 2'),
        );

      $block->template = realpath(FACIAL_PATH . 'template/menubar_facial.tpl');
    }
  }
}
