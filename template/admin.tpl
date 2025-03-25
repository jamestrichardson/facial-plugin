<div class = "titlePage">
  <h2>{'Facial Recognition'|@translate}</h2>
</div>
{if $train != 0}
<!-- todo: validate server side that we should be doing this -->
<form action='{$FACIAL_PATH}-train-update' method='POST'>
<input type="hidden" name="userid" value="{$train_user_id}" />
<fieldset>
  <legend>{'Configuration for: '|@translate}{$train_user_name}</legend>
  <div align="left">{'Which album contains the photos of '|@translate}{$train_user_name}{' that we want to evaluate?'|@translate}</div>
  <div align="left">{'Please note that currently this table does not map out a good heiracle album structure.'|@translate}</div>
  <table width="30%">
    <tr>
      <td>
        {if not empty($train_albums)}
        <select name="facial_train" id="facial_train" style="width: 300px" multiple>
        {foreach from=$train_albums item=theAlbum}
        {strip}
          <option value="{$theAlbum.id}">{$theAlbum.name}</option>
        {/strip}
        {/foreach}
        </select>
        {/if}
      </td>
    </tr>
    <tr>
      <td align="right"><input type="submit" /></td>
    </tr>
  </table>
</fieldset>
</form>
{/if}
<!-- Create the form for creating and editing the people we know about to train the recognition -->
<form action='{$FACIAL_PATH}-{if $edit == 0}create{else}update{/if}' method='POST'>
  <fieldset>
    <legend>{'Known Individuals'|@translate}</legend>
    <div align="left">{'This is where we are going to list the people we know and provide an interface on how to identify them'|@translate}</div>
    <table width="80%">
      <tr>
        <th>{'ID'|@translate}</th>
        <th>{'Persons Name'|@translate}</th>
        <th>{'Training Album'|@translate}</th>
        <th>{'Actions'|@translate}</th>
      </tr>
      <!-- Loop over all peoples -->
      {if not empty($Peoples)}
      {foreach from=$Peoples item=Person}
      {strip}
        <tr class="{cycle values="row1,row2"}"> <!-- This gives nicely colored table rows -->
          <td>{$Person.id}</td>
          <td>{$Person.person_name}</td>
          <td>{$Person.train_album_name}</td>
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
