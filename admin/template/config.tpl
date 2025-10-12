{combine_css path=$FACIAL_PATH|@cat:"admin/template/style.css"}

{footer_script}
jQuery(".showInfo").tipTip({
  delay: 0,
  fadeIn: 200,
  fadeOut: 200,
  maxWidth: '300px',
  defaultPosition: 'bottom'
});
{/footer_script}

<div class="titrePage">
	<h2>Facial</h2>
</div>

<form method="post" action="" class="properties">
<fieldset>
  <legend>{'Facial Configuration'|translate}</legend>
  <div align="center">
  <table width="100%">
  <tr>
    <td align="right" nowrap>CompreFace Host Details:</td>
    <td nowrap>
    <select name="compreface_protocol" id="compreface_protocol">
      <option value="http" {if $facial.compreface_protocol == 'http'}selected{/if}>http</option>
      <option value="https" {if $facial.compreface_protocol == 'https'}selected{/if}>https</option>
    </select>
    &nbsp;://&nbsp;
    <input type="text" name="compreface_host" id="compreface_host" value="{$facial.compreface_host}" size=50 />
    &nbsp;:&nbsp;
    <input type="text" name="compreface_port" id="compreface_port" value="{$facial.compreface_port}" size=5 />
    </td>
  </tr>
  <tr><td colspan="2" align="center"><hr width="75%" /></td></tr>
  <tr><td colspan="2" align="left"><b>Detection API Configuration</b></td></tr>
  <tr>
    <td width="25%" align="right" nowrap>{'API URL'|translate}:</td>
    <td width="75%" align="left" nowrap><input type="text" name="compreface_api_url" value="{$facial.compreface_api_url}" size=100 /></td>
  </tr>
  <tr>
    <td width="25%" align="right">{'API Key'|translate}:</td>
    <td width="75%" align="left"><input type="text" name="compreface_api_key" value="{$facial.compreface_api_key}" size="200"></td>
  </tr>
  <tr><td colspan="2" align="center"><hr width="75%" /></td></tr>
  <tr><td colspan="2" align="left"><b>Recognition API Configuration</b></td></tr>
  <tr>
    <td width="25%" align="right" nowrap>{'API URL'|translate}:</td>
    <td width="75%" align="left" nowrap><input type="text" name="compreface_recog_api_url" value="{$facial.compreface_recog_api_url}" size=100 /></td>
  </tr>
  <tr>
    <td width="25%" align="right">{'API Key'|translate}:</td>
    <td width="75%" align="left"><input type="text" name="compreface_recog_api_key" value="{$facial.compreface_recog_api_key}" size="200"></td>
  </tr>
</table>
<p class="formButtons"><input type="submit" name="save_config" value="{'Save Settings'|translate}"></p>
</fieldset>
</form>

<div align="center"><hr width="75%" /></div>
<fieldset>
  <legend>{'Known Subjects'|translate}</legend>
  {foreach $subjects as $subject}
    Name: {$subject}<br />
  {/foreach}
</fieldset>
