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
  <legend>{'Facial Plugin Settings'|translate}</legend>

  <ul>
    <li>
      <label for="compreface_api_url">
        {'Compreface API URL'|translate}
        <span class="showInfo" title="Enter the full URL to your Compreface API endpoint. Example: https://your-compreface-instance/api/v1">&#9432;</span>
      </label><br>
      <input type="text" id="compreface_api_url" name="compreface_api_url" value="{$facial.compreface_api_url}" placeholder="https://your-compreface-instance/api/v1" style="width: 350px;" />
      <small class="help">{'Required. The endpoint for your Compreface server.'|translate}</small>
    </li>
    <li class="option1">
      <label for="compreface_api_key">
        {'Compreface API Key'|translate}
        <span class="showInfo" title="Paste your Compreface API key here. You can find this in your Compreface dashboard.">&#9432;</span>
      </label><br>
      <input type="text" id="compreface_api_key" name="compreface_api_key" value="{$facial.compreface_api_key}" placeholder="API key" style="width: 350px;" />
      <small class="help">{'Required. Your Compreface API key.'|translate}</small>
    </li>
  </ul>
</fieldset>

<p class="formButtons">
  <input type="submit" name="save_config" value="{'Save Settings'|translate}" class="button-primary">
</p>

<div id="config-feedback" style="margin-top:10px;"></div>

</form>
