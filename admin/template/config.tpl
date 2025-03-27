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
  <legend>{'Common configuration'|translate}</legend>

  <ul>
    <li>
      <label>
        <input type="text" name="compreface_api_url" value="{$facial.compreface_api_url}" />
      </label>
    </li>
    <li class="option1">
      <label>
        <input type="text" name="compreface_api_key" value="{$facial.compreface_api_key}" size="4">
      </label>
    </li>
  </ul>
</fieldset>

<p class="formButtons"><input type="submit" name="save_config" value="{'Save Settings'|translate}"></p>

</form>
