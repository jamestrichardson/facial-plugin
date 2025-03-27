{combine_script id="jquery.facialBatchManagerUnit" load="footer" path="{$FACIAL_PATH}/js/batch_manager_unit.js"}

{* This div works with Method 1 (see batch_manager_unit.js) *}
<div class="facials_input">
  <strong>{'facial API Method'|translate}</strong>
  <input type="number" class="facial" name="Facial" min="0" max="1" value="{if isset($element.facial)}{$element.facial}{else}0{/if}">
  <label>Enter 0 or 1</label>
</div>

{* This div works with Method 2 (see batch_manager_unit.js)*}
<div class="facials_input">
  <strong>{'Facial pwg.images.setInfo Hook'|translate}</strong>
  <input type="number" class="facial2" name="Facial" min="0" max="1" value="{if isset($element.facial)}{$element.facial}{else}0{/if}">
  <label>Enter 0 or 1</label>
</div>

{html_style}
  .facials_input {
    flex: 0 0 calc(100% - 20px);
    margin: 0px 10px;
  }

  .facials_input strong {
    display: block;
    margin-bottom: 10px;
}

  .facials_input input {
      flex-direction:column;
      border: 1px solid #D3D3D3;
      background-color: #FFFFFF !important;
      border-radius: 2px;
      padding: 0 7px;
  }
{/html_style}