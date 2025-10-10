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
    <li>
      <label for="facial_cf_host">
        {'Compreface Host'|translate}
        <span class="showInfo" title="Host for Compreface service (e.g., localhost)">&#9432;</span>
      </label><br>
      <input type="text" id="facial_cf_host" name="facial_cf_host" value="{$facial.facial_cf_host}" placeholder="localhost" style="width: 350px;" />
    </li>
    <li>
      <label for="facial_cf_port">
        {'Compreface Port'|translate}
        <span class="showInfo" title="Port for Compreface service (e.g., 8000)">&#9432;</span>
      </label><br>
      <input type="number" id="facial_cf_port" name="facial_cf_port" value="{$facial.facial_cf_port}" placeholder="8000" style="width: 120px;" />
    </li>
    <li>
      <label for="facial_cf_ssl">
        {'Use SSL'|translate}
        <span class="showInfo" title="Enable SSL for Compreface connection.">&#9432;</span>
      </label>
      <input type="checkbox" id="facial_cf_ssl" name="facial_cf_ssl" value="1" {if $facial.facial_cf_ssl}checked{/if} />
    </li>
    <li>
      <label for="facial_cf_api_recoginition_key">
        {'Recognition API Key'|translate}
        <span class="showInfo" title="API key for recognition endpoint.">&#9432;</span>
      </label><br>
      <input type="text" id="facial_cf_api_recoginition_key" name="facial_cf_api_recoginition_key" value="{$facial.facial_cf_api_recoginition_key}" placeholder="Recognition API key" style="width: 350px;" />
    </li>
    <li>
      <label for="facial_cf_api_detection_key">
        {'Detection API Key'|translate}
        <span class="showInfo" title="API key for detection endpoint.">&#9432;</span>
      </label><br>
      <input type="text" id="facial_cf_api_detection_key" name="facial_cf_api_detection_key" value="{$facial.facial_cf_api_detection_key}" placeholder="Detection API key" style="width: 350px;" />
    </li>
    <li>
      <label for="facial_cf_api_verification_key">
        {'Verification API Key'|translate}
        <span class="showInfo" title="API key for verification endpoint.">&#9432;</span>
      </label><br>
      <input type="text" id="facial_cf_api_verification_key" name="facial_cf_api_verification_key" value="{$facial.facial_cf_api_verification_key}" placeholder="Verification API key" style="width: 350px;" />
    </li>
    <li>
      <label for="facial_plugin_debug">
        {'Enable Debug Mode'|translate}
        <span class="showInfo" title="Show debug information for troubleshooting.">&#9432;</span>
      </label>
      <input type="checkbox" id="facial_plugin_debug" name="facial_plugin_debug" value="1" {if $facial.facial_plugin_debug}checked{/if} />
    </li>
    <li>
      <label for="facial_cf_detection_limit">
        {'Detection Limit'|translate}
        <span class="showInfo" title="Maximum number of faces to detect per image.">&#9432;</span>
      </label><br>
      <input type="number" id="facial_cf_detection_limit" name="facial_cf_detection_limit" value="{$facial.facial_cf_detection_limit}" min="0" style="width: 120px;" />
    </li>
    <li>
      <label for="facial_cf_detection_prob_threshold">
        {'Detection Probability Threshold'|translate}
        <span class="showInfo" title="Minimum probability for a face detection to be considered valid.">&#9432;</span>
      </label><br>
      <input type="number" step="0.01" id="facial_cf_detection_prob_threshold" name="facial_cf_detection_prob_threshold" value="{$facial.facial_cf_detection_prob_threshold}" min="0" max="1" style="width: 120px;" />
    </li>
    <li>
      <label for="facial_cf_detection_face_plugins">
        {'Detection Face Plugins'|translate}
        <span class="showInfo" title="Comma separated list of face plugins to use for detection.">&#9432;</span>
      </label><br>
      <input type="text" id="facial_cf_detection_face_plugins" name="facial_cf_detection_face_plugins" value="{$facial.facial_cf_detection_face_plugins}" placeholder="plugin1,plugin2" style="width: 350px;" />
    </li>
  </ul>
</fieldset>

<p class="formButtons">
  <input type="submit" name="save_config" value="{'Save Settings'|translate}" class="button-primary">
</p>

<div id="config-feedback" style="margin-top:10px;"></div>

</form>
