<?php
/**
 * PHPCS Bootstrap file
 * Defines constants needed to prevent "Hacking attempt!" errors
 *
 * This file is loaded before PHPCS scans the codebase
 */

// Define constants that are normally set by Piwigo
if (!defined('FACIAL_PATH')) {
    define('FACIAL_PATH', __DIR__ . '/');
}

if (!defined('PHPWG_ROOT_PATH')) {
    define('PHPWG_ROOT_PATH', dirname(__DIR__, 3) . '/');
}

// Define other common Piwigo constants
if (!defined('IMAGES_TABLE')) {
    define('IMAGES_TABLE', 'piwigo_images');
}

if (!defined('TAGS_TABLE')) {
    define('TAGS_TABLE', 'piwigo_tags');
}

if (!defined('IMAGE_TAG_TABLE')) {
    define('IMAGE_TAG_TABLE', 'piwigo_image_tag');
}

// Mock global variables
global $conf, $logger, $prefixeTable;
$conf = [];
$prefixeTable = 'piwigo_';
