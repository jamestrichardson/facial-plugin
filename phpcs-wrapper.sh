#!/bin/bash
# PHPCS wrapper script that defines constants before running PHPCS

# Define constants via environment that can be used
export FACIAL_PATH="/fake/path/"
export PHPWG_ROOT_PATH="/fake/path/"

# Create a temporary bootstrap file that PHPCS will use
cat > /tmp/phpcs-bootstrap-temp.php << 'EOF'
<?php
define('FACIAL_PATH', getenv('FACIAL_PATH') ?: '/fake/path/');
define('PHPWG_ROOT_PATH', getenv('PHPWG_ROOT_PATH') ?: '/fake/path/');
define('IMAGES_TABLE', 'piwigo_images');
define('TAGS_TABLE', 'piwigo_tags');
define('IMAGE_TAG_TABLE', 'piwigo_image_tag');
$GLOBALS['conf'] = [];
$GLOBALS['prefixeTable'] = 'piwigo_';
EOF

# Run PHPCS with the bootstrap
./vendor/bin/phpcs --bootstrap=/tmp/phpcs-bootstrap-temp.php "$@"

# Clean up
rm -f /tmp/phpcs-bootstrap-temp.php
