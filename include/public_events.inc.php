<?php
defined('FACIAL_PATH') or die('Hacking attempt!');


// add a prefilter on phooto page
function facial_loc_end_picture()
{
  global $template;
  $template->set_prefilter('picture', 'facial_picture_prefilter');
}

function facial_picture_prefilter($content)
{
  $search = '{if $display_info.author and isset($INFO_AUTHOR)}';
  $replace = '
<div id="Facial" class="imageInfo">
  <dt>{\'Facial\'|@translate}</dt>
  <dd style="color:orange;">{\'Piwigo rocks\'|@translate}</dd>
</div>
';

  return str_replace($search, $replace.$search, $content);
}
