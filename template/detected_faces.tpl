{combine_script id='jquery.jcrop' load='footer' require='jquery' path='themes/default/js/plugins/jquery.Jcrop.min.js'}
<div id="detected-faces" border=1>
  <div id="face1">
  <table>
    <tr>
      <td>
          <div class="container">
            <img src="data:image/jpg;base64,{$IMG_B64}" width="100px" alt="" />';`
          </div>
      </td>
      <td>
      <form method="post" action="" class="properties">
      <input type="hidden" name="image_id" value="{$IMGID}" />
      <fieldset>
        <legend>{'Who am I?'|translate}</legend>
          <select name="tag_option" id="tag_option">
            {foreach $tags as $tag}
            <option value="{$tag.id}">{$tag.name}</option>
            {/foreach}
          </select>
         </td>
        </tr>
        </table>
        </div>
        </form>
        </fieldset>
    </tr>
    <tr>
      <td colspan="2">
        {$DEBUG_FACIAL}
      </td>
    </tr>
  </table>
  </div>
  <br />
  <br />
  <br />
</div>
