{html_style}
#theMainImageContainer {
  position: relative;
  display: inline-block;
}
#theMainImage {
  display: block;
}
#show-image-id {
  position: absolute;
  left: {$BOX_XMIN}%;
  top: {$BOX_YMIN}%;
  width: {$BOX_XMAX}%;
  bottom: {$BOX_YMAX}%;
  background-color: #cc0000;
  border-radius: 5px;
  border: 1px solid #000;
  color: white;
  font-size: 14px;
  z-index: 1000;
  opacity: 0.8;
  cursor: pointer;
  display: flex;
  align-items: center;
}

/* tooltip */
#show-image-id::after {
  content: "{$IMG_FLOAT_TEXT}";
  position: absolute;
  bottom: 100%;
  right: 0;
  background-color: #333;
  color: #fff;
  padding: 5px;
  border-radius: 3px;
  font-size: 12px;
  white-space: nowrap;
  visibility: hidden;
  opacity: 0;
  transition: opacity 0.3s;
}
#show-image-id:hover::after {
  visibility: visible;
  opacity: 1;
}
{/html_style}

{footer_script require='jquery'}
jQuery(document).ready(function(){
  var $img = jQuery("#theMainImage");
  var $container = jQuery('<div id="theMainImageContainer"></div>');
  $img.wrap($container);
  var $idDiv = jQuery('<div id="show-image-id">{$SHOW_IMAGE_FACIALBOX}</div>');
  $img.parent().append($idDiv);

});
{/footer_script}

<!--
{$DUMP}
-->
