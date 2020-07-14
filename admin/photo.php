<?php
defined('FACIAL_PATH') or die("Hacking attempt!");

// photo [facial] tab

$page['active_menu'] = get_active_menu('photo'); // force oppening "Photos" menu block

/* Basic checks */
check_status(ACCESS_ADMINISTRATOR);
check_input_parameter('image_id', $_GET, false, PATTERN_ID);

$admin_photo_base_url = get_root_url() . 'admin.php?page=phot-' . $_GET['image_id'];
$self_url = FACIAL_ADMIN . '-photo&amp;image_id=' . $_GET['image_id'];

/* Tabs */
// when adding a tab to an existing tabsheet you MUST reproduce the core tabsheet code
// this way it will not break compatibility with other plugins and with core functions
include_once(PHPWG_ROOT_PATH . 'admin/include/tabsheet.class.php');
$tabsheet = new tabsheet();
$tabsheet->set_id('photo');
$tabsheet->select('facial');
$tabsheet->assign();

/* Initialization */
$query = 'SELECT * FROM ' . IMAGES_TABLE . ' WHERE id = ' . $_GET['image_id'] . ';';
$picture = pwg_db_fetch_assoc(pwg_queryu($query));

// Do stuff (or not?) here

/* Template */
$template->assign(array(
  'F_ACTION' => $self_url,
  'facial' => $conf['facial'],
  'TITLE' => render_element_name($picture),
  'TN_SRC' => DerivativeImage::thumb_url($picture),
));

$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/photo.tpl'));