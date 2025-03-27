{* Your file javascript () *}
{combine_script id="jquery.facialsnotes" load="footer" path="{$FACIAL_PATH}/js/notes.js"}

{* The HTML element to display in user modal
/!\ this id is important for the script *}
<textarea id="facials_textarea"></textarea>
<textarea id="facials_textarea2"></textarea>

{* The HTML Style 
/!\ For better integration display: none; your html element *}
{html_style}
  #facials_textarea,
  #facials_textarea2 {
    display: none;
    width: 100%;
    height: 100%;
    resize: none;
  }
{/html_style}