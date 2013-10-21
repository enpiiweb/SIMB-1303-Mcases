<h2><a href="catadmin.php">Admin Panel</a> - {if $current_cat}{$current_cat.name}{else}All Years{/if}</h2>
{if $error}
<div class="alert">
	There was an error while trying to save the information.<br>
	Please <a href="#edit">click here</a> or scroll to the bottom to correct the error.
</div>&nbsp;<br>
{else}
<div><a href="#edit">Add Part</a></div>&nbsp;<br>
{/if}
<table cellpadding="2" cellspacing="0" border="1" rules="cols" frame="void">
<tr>
	<th>Date</th>
	<th>Title</th>
	<th>File</th>
	<th>Edit</th>
	<th>Delete</th>
</tr>
{foreach from=$downloadlist_download item=download}
<tr>
<td valign="top">{$download.date|date_format:"%d/%m/%y"}</td>
<td valign="top"><a href="articleadmin.php?download={$download.id}">{$download.title}</a></td>
<td valign="top">{$download.file}</td>
<td valign="top"><a href="downloadadmin.php?action=edit&id={$download.id}&cat={$download.cat}#edit">Edit</a></td>
<td valign="top"><a href="downloadadmin.php?action=delete&id={$download.id}&cat={$download.cat}" onClick="return confirm('Are you sure you want to permanently delete this part?  The associated file will also be deleted.');">Delete</a></td>
</tr>
{foreachelse}
<tr>
<td valign="top" colspan="5">No parts found for this year.</td>
</tr>
{/foreach}
</table>

{if $current_download.id}
<h2><a name="edit">Edit Part</h2>
{if isset($error.id)}<div class="alert">{$error.id}</div>{/if}
<form method="post" action="downloadadmin.php" enctype="multipart/form-data" />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="oldcat" value="{$current_download.cat}" />
	<input type="hidden" name="oldfile" value="{$current_download.file}" />
	<input type="hidden" name="id" value="{$current_download.id}" />
	<table border="1">
		<tr>
			<td>Title</td>
			<td><input type="text" name="title" value="{$current_download.title}" />{if isset($error.title)}<div class="alert">{$error.title}</div>{/if}</td>
		</tr>
		<tr>
			<td>Restricted</td>
			<td><input type="checkbox" name="restricted" value="1" {if $current_download.restricted == 1}CHECKED{/if} /></td>
		</tr>
		<tr>
			<td>Date</td>
			<td>
				Day {html_options name=day options=$downloadform_days selected=$current_download.date|date_format:"%e"}
				Month {html_options name=month options=$downloadform_months selected=$current_download.date|date_format:"%m"}
				Year {html_options name=year options=$downloadform_years selected=$current_download.date|date_format:"%Y"}
			</td>
		</tr>
		<tr>
			<td>Description</td>
			<td><textarea name="description" cols="20" rows="5">{$current_download.description}</textarea></td>
		</tr>
		<tr>
			<td>Current File</td>
			<td>{$current_download.file}</td>
		</tr>
		<tr>
			<td>New File</td>
			<td><input type="file" name="file" />{if isset($error.file)}<div class="alert">{$error.file}</div>{/if}<br>NOTE:  Uploading a new file will automatically delete the current file.<br>If this field is left blank, the current file will remain unaffected.</td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				<select name="cat">
				{foreach from=$downloadform_cats item=cat}
					<option value="{$cat.id}" {if $cat.id == $current_download.cat}selected{/if} >{$cat.name}</option>
				{/foreach}
				</select>
				{if isset($error.cat)}<div class="alert">{$error.cat}</div>{/if}
			</td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" value="Update" /></td>
		</tr>
	</table>
</form>
{else}
<h2><a name="edit">Add Part</h2>
{if isset($error.id)}<div class="alert">{$error.id}</div>{/if}
<form method="post" action="downloadadmin.php" enctype="multipart/form-data" />
	<input type="hidden" name="action" value="add" />
	<table border="1">
		<tr>
			<td>Title</td>
			<td><input type="text" name="title" value="{$current_download.title}" />{if isset($error.title)}<div class="alert">{$error.title}</div>{/if}</td>
		</tr>
		<tr>
			<td>Restricted</td>
			<td><input type="checkbox" name="restricted" value="1" {if $current_download.restricted == 1}CHECKED{/if} /></td>
		</tr>
		<tr>
			<td>Date</td>
			<td>
				Day {html_options name=day options=$downloadform_days selected=$current_download.date|date_format:"%e"}
				Month {html_options name=month options=$downloadform_months selected=$current_download.date|date_format:"%m"}
				Year {html_options name=year options=$downloadform_years selected=$current_download.date|date_format:"%Y"}
			</td>
		</tr>
		<tr>
			<td>Description</td>
			<td><textarea name="description" cols="20" rows="5">{$current_download.description}</textarea></td>
		</tr>
		<tr>
			<td>File</td>
			<td><input type="file" name="file" />{if isset($error.file)}<div class="alert">{$error.file}</div>{/if}</td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				<select name="cat">
				{foreach from=$downloadform_cats item=cat}
					<option value="{$cat.id}" {if $cat.id == $downloadform_download->cat}selected{/if} >{$cat.name}</option>
				{/foreach}
				</select>
				{if isset($error.cat)}<div class="alert">{$error.cat}</div>{/if}
			</td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" value="Add" /></td>
		</tr>
	</table>
</form>
{/if}