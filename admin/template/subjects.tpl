{combine_css path=$FACIAL_PATH|@cat:"admin/template/style.css"}

<div class="titrePage">
  <h2>{'CompreFace Subjects'|translate}</h2>
</div>

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
        <form method="post" action="" style="display:contents;">
        <tr>
          <input type="hidden" name="form_type" value="subject_action" />
          <td><input type="checkbox" name="subject_select[]" value="{$subject}" /></td>
          <td>{$subject}<input type="hidden" name="subject_name" value="{$subject}" /></td>
          <td>
            <button name="delete" type="submit" class="delete-btn" data-subject="{$subject}">{'Delete'|translate}</button>
          </td>
        </form>
        </tr>
      {/foreach}
    </tbody>
  </table>
  <form method="post" action="" style="margin-top:20px;">
      <input type="hidden" name="form_type" value="add_subject" />
      <label for="new_subject">{'Add New Subject'|translate}:</label>
      <input type="text" name="new_subject" id="new_subject" placeholder="Enter new subject name" style="width:200px;" />
      <button type="submit" name="add_subject" value="1">{'Add'|translate}</button>
    </form>

{footer_script}
jQuery(".showInfo").tipTip({
  delay: 0,
  fadeIn: 200,
  fadeOut: 200,
  maxWidth: '300px',
  defaultPosition: 'bottom'
});


{/footer_script}
