<?php

/**
 * @file
 * This file will reidrect folks who are trying to navigate to the plugin folder.
 * They shouldn't be doing that.
 */
$url = '../';
header( 'Request-URI: '.$url );
header( 'Content-Location: '.$url );
header( 'Location: '.$url );
exit();
?>