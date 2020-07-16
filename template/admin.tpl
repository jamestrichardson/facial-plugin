<div class = "titlePage">
  <h2>{'Facial Recognition'|@translate}</h2>
</div>
{if $train != 0}
<<fieldset>
  <legend>{'Train Photos for: '|@translate}{$train_user_name}</legend>
  <div align="left">{'What do we need to do to train photos for the user'|@translate}</div>
  <table width="80%">
    <tr>
      <td>foo</td>
      <td>foo</td>
      <td>foo</td>
      <td>foo</td>
    </tr>
  </table>
{/if}
<!-- Create the form for creating and editing the people we know about to train the recognition -->
<form action='{$FACIAL_PATH}-{if $edit == 0}create{else}update{/if}' method='POST'>
  <fieldset>
    <legend>{'Known Individuals'|@translate}</legend>
    <div align="left">{'This is where we are going to list the people we know and provide an interface on how to identify them'|@translate}</div>
    <table width="80%">
      <tr>
        <th>{'ID'|@translate}</th>
        <td>{'Persons Name'|@translate}</th>
        <th>{'Actions'|@translate}</th>
      </tr>
      <!-- Loop over all peoples -->
      {if not empty($Peoples)}
      {foreach from=$Peoples item=Person}
      {strip}
        <tr class="{cycle values="row1,row2"}"> <!-- This gives nicely colored table rows -->
          <td>{$Person.id}</td>
          <td>{$Person.person_name}</td>
          <td><a href="{$FACIAL_PATH}-edit&id={$Person.id}">Edit</a>&nbsp;<a href="{$FACIAL_PATH}-train&id={$Person.id}">Train</a>&nbsp;<a href="delete-url">Delete&nbsp;</td>
        </tr>
      {/strip}
      {/foreach}
      {/if}
    </table>
  </fieldset>
</form>

<!-- Create a form for adding new people -->
<form action='{$FACIAL_PATH}-add' method='POST'>
  <fieldset>
    <legend>{'Add a new person'|@translate}</legend>
    <table>
      <tr>
        <td>New Persons Name:</td>
        <td><input type=text size="100" /></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" /></td>
      </tr>
    </table>
  </fieldset>
</form>
