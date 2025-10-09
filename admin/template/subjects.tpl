{combine_css path=$FACIAL_PATH|@cat:"admin/template/style.css"}


<div class="titrePage">
  <h2>{'CompreFace Subjects'|translate}</h2>
</div>

<hr />
URL: {$debug_url}
<hr />

<table class="table table-striped" style="width: 600px; margin-top: 20px;">
  <thead>
    <tr>
      <th style="width:40px;"></th>
      <th>{'Subject Name'|translate}</th>
      <th>{'Actions'|translate}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$subjects item=subject}
      <tr>
        <td><input type="checkbox" name="subject_select[]" value="{$subject}" /></td>
        <td>{$subject}</td>
        <td>
          <button type="button" class="rename-btn" data-subject="{$subject}">{'Rename'|translate}</button>
          <button type="button" class="delete-btn" data-subject="{$subject}">{'Delete'|translate}</button>
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
{footer_script}
jQuery(".showInfo").tipTip({
  delay: 0,
  fadeIn: 200,
  fadeOut: 200,
  maxWidth: '300px',
  defaultPosition: 'bottom'
});
{/footer_script}
