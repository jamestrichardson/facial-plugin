
## Misc Information
- ImageStdParams::get_defined_type_map() returns map of defined image sizes from the configuration settings page


## TODO's
- Make sure we're always working from a specific availabel image size available (like IMG_LARGE)
  - ```$query = 'SELECT * FROM ' . IMAGES_TABLE . ' WHERE id=' . $page['image_id'] . ' LIMIT 1;';
    $row = pwg_db_fetch_assoc(pwg_query($query));
    $URL = DerivativeImage::url(IMG_LARGE, $row);```
