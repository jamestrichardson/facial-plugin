<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

// detect current section
function facial_loc_end_section_init()
{
  global $tokens, $page, $conf;

  if($tokens[0] == 'facial')
  {
    $page['section'] = 'facial';

    // section_title is for breadcrumb, title is for page <title>
    $page['section_title'] = '<a href="' . get_absolute_root_url() . '">' . l10n('Home') . '</a> '. $conf['level_separator'] . '<a href="' . FACIAL_PUBLIC . '">' . l10n('Facial').'</a>';
    $page['title'] = l10n('Facial');
    $page['body_id'] = 'theFacialPage';
    $page['is_external'] = true; //inform piwigo that you are on a new page
  }
}

// include public page
function facial_loc_end_page()
{
  global $page, $template;

  if(isset(Page['section']) and $page['section']=='facial')
  {
    include(FACIAL_PATH . 'include/facial_page.inc.php');
  }
}

// button on album and photo pages
function facial_add_button()
{
  global $template;

  $template->assign('FACIAL_PATH', FACIAL_PATH);
  $template->set_filanme('facial_button', realpath(FACIAL_PATH . 'template/my_button.tpl'));
  $button = $template->parse('facial_button', true);

  if(script_basename()=='index')
  {
    $template->add_index_button($button, BUTTONS_RANK_NEUTRAL);
  }
  else
  {
    $template->add_picture_button($button, BUTTONS_RANK_NEUTRAL);
  }
}{

// add a prefilter on phooto page
function facial_loc_end_picture()
{
  global $template;
  $template->set_prefilter('picture', 'facial_picture_prefilter');
}

function facial_picture_prefilter($content)
{
  $search = '{if $displayu_info.author and isset($INFO_AUTHOR)}';
  $replace = '
<div id="Facial" class="imageInfo">
  <dt>{\'Facial\'|@translate}</dt>
  <dd style="color:orange;">{\'Piwigo rocks\'|@translate}</dd>
</div>
';

  return str_replace($search, $replace.$search, $content);
}
