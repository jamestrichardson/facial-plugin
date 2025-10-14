<?php
/**
 * PHPUnit Bootstrap file for Facial Plugin tests
 */

// Prevent direct access
if (!defined('PHPUNIT_TESTING')) {
    define('PHPUNIT_TESTING', true);
}

// Define constants that would normally be defined by Piwigo
if (!defined('PHPWG_ROOT_PATH')) {
    define('PHPWG_ROOT_PATH', dirname(__DIR__) . '/../../../');
}

if (!defined('FACIAL_PATH')) {
    define('FACIAL_PATH', dirname(__DIR__) . '/');
}

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
global $prefixeTable, $conf, $logger;
$prefixeTable = 'piwigo_';
$conf = array();
$logger = null;

// Mock Piwigo functions that are used by the plugin
if (!function_exists('pwg_query')) {
    function pwg_query($query) {
        // Mock implementation for testing
        return true;
    }
}

if (!function_exists('pwg_db_fetch_assoc')) {
    function pwg_db_fetch_assoc($result) {
        // Mock implementation for testing
        return array('path' => '/test/image.jpg');
    }
}

if (!function_exists('pwg_db_fetch_array')) {
    function pwg_db_fetch_array($result) {
        // Mock implementation for testing
        return array(0);
    }
}

if (!function_exists('query2array')) {
    function query2array($query, $key = null, $value = null) {
        // Mock implementation for testing
        return array();
    }
}

if (!function_exists('safe_unserialize')) {
    function safe_unserialize($data) {
        if (is_string($data)) {
            return unserialize($data);
        }
        return $data;
    }
}

if (!function_exists('pwg_db_real_escape_string')) {
    function pwg_db_real_escape_string($string) {
        return addslashes($string);
    }
}

if (!function_exists('create_tag')) {
    function create_tag($tag_name) {
        // Mock implementation - simulate existing tag
        if ($tag_name === 'existing-tag') {
            return array('error' => 'Tag already exists');
        }
        // Simulate new tag creation
        return array('id' => 123);
    }
}

if (!function_exists('set_tags')) {
    function set_tags($tag_ids, $image_id) {
        // Mock implementation for testing
        return true;
    }
}

// Include the functions file
require_once dirname(__DIR__) . '/include/functions.inc.php';
