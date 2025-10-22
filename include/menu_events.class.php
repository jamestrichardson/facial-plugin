<?php

defined('FACIAL_PATH') or die('Hacking attempt!');

/*
 * There is two ways to use class methods as event handlers:
 *
 * >  add_event_handler('blockmanager_apply', array('SkeletonMenu', 'blockmanager_apply'));
 *      in this case the method 'blockmanager_apply' must be a static method of the class 'SkeletonMenu'
 *
 * >  $myObj = new SkeletonMenu();
 * >  add_event_handler('blockmanager_apply', array(&$myObj, 'blockmanager_apply'));
 *      in this case the method 'blockmanager_apply' must be a public method of the object '$myObj'
 */
