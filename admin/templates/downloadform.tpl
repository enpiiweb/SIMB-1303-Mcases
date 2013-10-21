<h2>Welcome</h2><p></p>
<form method="post" action="downloadadmin.php?action=save" enctype="multipart/form-data" />
<input type="hidden" name="oldcat" value="{$downloadform_download->cat}" />
{if $downloadform_download->id }<input type="hidden" name="id" value="{$downloadform_download->id}" />{/if}
<table border="1">
<tr><td>Title</td>	<td><input type="text" name="title" value="{$downloadform_download->title}" />{if isset($downloadform_errors.title)}<div class="alert">{$downloadform_errors.title}</div>{/if}</td></tr>
<tr><td>restricted</td>	<td><input type="checkbox" name="restricted" {if $downloadform_download->restricted == 1}CHECKED{/if} /></td></tr>
<tr><td>date</td>		<td>
				Day {html_options name=day options=$downloadform_days selected=$downloadform_download->date|date_format:"%e"}
				Month {html_options name=month options=$downloadform_months selected=$downloadform_download->date|date_format:"%m"}
				Year {html_options name=year options=$downloadform_years selected=$downloadform_download->date|date_format:"%Y"}
				</td></tr>
<tr><td>Description</td><td><textarea name="description" cols="20" rows="5">{$downloadform_download->description}</textarea></td></tr>
{if $downloadform_download->id }
<tr><td>x</td><td>
	<input type="radio" name="handelfile" value="keep" checked />Keep File<br>
	<input type="radio" name="handelfile" value="replace" />Repalce File<br>
</td></tr>
{else}
	<input type="hidden" name="handelfile" value="replace" />
{/if}
<tr><td>File</td><td> <input type="file" name="file" value="{$downloadform_download->file}" />{if isset($downloadform_errors.file)}<div class="alert">{$downloadform_errors.file}</div>{/if}</td>
<tr><td>cat</td>
<td>
<select name="cat">
	{foreach from=$downloadform_cats item=cat}
	<option value="{$cat.id}" {if $cat.id == $downloadform_download->cat}selected{/if} >{$cat.name}</option>
	{/foreach}
</select>
</tr>
<tr><td>submit</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>



