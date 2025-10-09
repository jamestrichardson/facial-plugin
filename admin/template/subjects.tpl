{combine_css path=$FACIAL_PATH|@cat:"admin/template/style.css"}


<div class="titrePage">
  <h2>{'CompreFace Subjects'|translate}</h2>
</div>

<table class="table table-striped" style="width: 400px; margin-top: 20px;">
  <thead>
    <tr>
      <th>{'Subjects'|translate}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{$subjects}</td>
    </tr>
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
