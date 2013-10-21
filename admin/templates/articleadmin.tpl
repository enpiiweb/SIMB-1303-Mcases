<script language="JavaScript" type="text/javascript" src="{$smartyglobal.relroot}CMS/js/html2xhtml.js"></script>
<script language="JavaScript" type="text/javascript" src="{$smartyglobal.relroot}CMS/js/richtext.js"></script>

<h2><a href="catadmin.php">Admin Panel</a> - {if $current_download}{$current_download.title}{else}All Cases{/if}</h2>
{if $error}
<div class="alert">
	There was an error while trying to save the information.<br>
	Please <a href="#edit">click here</a> or scroll to the bottom to correct the error.
</div>&nbsp;<br>
{else}
<div><a href="articleadmin.php?download={$current_article.download}#edit">Add Case</a></div>&nbsp;<br>
{/if}
<div>
{foreach from=$alpha item=al}{if $al==$current_al}<b>{$al}</b> {else}<a href="?alpha={$al}">{$al}</a> {/if}{/foreach}
<br>
Showing {math equation="x*y+1" x=$page y=$max} to {if $count>$max*($page+1)}{math equation="x*(y+1)" x=$max y=$page}{else}{$count}{/if} of {$count} Entries<br>
{section loop=$count start=1 step=$max name=counter}{if $page==($smarty.section.counter.iteration-1)}<b>{$smarty.section.counter.iteration}</b> {else}<a href="?alpha={$current_al}&page={$smarty.section.counter.iteration}">{$smarty.section.counter.iteration}</a> {/if}{/section}
</div>
<table>
	<tr>
		<th>Title</th>
		<th>Case ID</th>
		<th>Court</th>
		<th>Judge(s)</th>
		<th>Date</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
{foreach from=$articlelist_articles item=article}
	<tr>
		<td>{$article.title}</td>
		<td>{$article.caseid}/{$article.courtid}</td>
		<td>{$article.court}</td>
		<td>{$article.judge}</td>
		<td>{$article.day}/{$article.month}/{$article.year}</td>
		<td><a href="articleadmin.php?action=edit&id={$article.id}&download={$article.download}#edit">Edit</a></td>
		<td><a href="articleadmin.php?action=delete&id={$article.id}&download={$article.download}" onClick="return confirm('Are you sure you want to permanently delete this case?');">Delete</a></td>
	</tr>
{/foreach}
</table>
<div>
{foreach from=$alpha item=al}{if $al==$current_al}<b>{$al}</b> {else}<a href="?alpha={$al}">{$al}</a> {/if}{/foreach}
<br>
Showing {math equation="x*y+1" x=$page y=$max} to {if $count>$max*($page+1)}{math equation="x*(y+1)" x=$max y=$page}{else}{$count}{/if} of {$count} Entries<br>
{section loop=$count start=1 step=$max name=counter2}{if $page==($smarty.section.counter2.iteration-1)}<b>{$smarty.section.counter2.iteration}</b> {else}<a href="?alpha={$current_al}&page={$smarty.section.counter2.iteration}">{$smarty.section.counter2.iteration}</a> {/if}{/section}

</div>
{if $current_article.id }
<h2><a name="edit">Update Case</h2>
<form method="post" name="articleform" action="articleadmin.php?action=save" onSubmit="return submitForm();">
	<input type="hidden" name="id" value="{$current_article.id}" />
{else}
<h2><a name="edit">Add Case</h2>
<form method="post" name="articleform" action="articleadmin.php?action=add" onSubmit="return submitForm();">
{/if}
	<table border="1">
		<tr>
			<td>Title</td>
			<td><input type="text" name="title" value="{$current_article.title}" />{if isset($error.title)}<div class="alert">{$error.title}</div>{/if}</td>
		</tr>
		<tr>
			<td>Case Number</td>
			<td><input type="text" name="caseid" value="{$current_article.caseid}" /> / <input type="text" name="courtid" value="{$current_article.courtid}" /></td>
		</tr>
		<tr>
			<td>Court</td>
			<td><input type="text" name="court" value="{$current_article.court}" /></td>
		</tr>
		<tr>
			<td>Restricted</td>
			<td><input type="checkbox" name="restricted" value="1" {if $current_article.restricted == 1}CHECKED{/if} /></td>
		</tr>
		<tr>
			<td>Judge</td>
			<td><input type="text" name="judge" value="{$current_article.judge}" /></td>
		</tr>
		<tr>
			<td>Date</td>
			<td>
				Day {html_options name=day options=$articleform_days selected=$current_article.day}
				Months {html_options name=month options=$articleform_months selected=$current_article.month}
				Year {html_options name=year options=$articleform_years selected=$current_article.year}
			</td>
		</tr>
		<tr>
			<td>Keywords</td>
			<td><textarea name="keywords" cols="40" rows="5">{$current_article.keywords}</textarea></td>
		</tr>
		<tr>
			<td>Content</td>
			<td>
				<script language="JavaScript" type="text/javascript">
					{literal}<!--
					function submitForm() {
						//make sure hidden and iframe values are in sync before submitting form
						//to sync only 1 rte, use updateRTE(rte)
						//to sync all rtes, use updateRTEs
					
						var safari = false;
						var detect = navigator.userAgent.toLowerCase();
						if (detect.indexOf('safari') + 1)
						{
								safari = true;
						}
					
						if(!safari)
							updateRTE('content');
						//updateRTEs();
					
						//change the following line to true to submit form
						return true;
					}
					var safari = false;
					var detect = navigator.userAgent.toLowerCase();
					if (detect.indexOf('safari') + 1)
					{
							safari = true;
					}
					{/literal}
					//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
					if(!safari)
							initRTE("{$smartyglobal.relroot}CMS/images/", "{$smartyglobal.relroot}CMS/js/", "{$smartyglobal.relroot}CMS/rte.css", true);
					//-->
				</script>
				<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

				<script language="JavaScript" type="text/javascript">
					<!--
					//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
					if(safari)
							document.write('<textarea name="content" cols="40" rows="15">{$current_article.RTEcontent}</textarea>');
					else
							writeRichText('content', '{$current_article.RTEcontent}', 420, 300, true, false);
					//-->
				</script>
			</td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				<select name="cat">
					{foreach from=$articleform_cats item=cat}
					<option value="{$cat.id}" {if $cat.id == $current_article.cat}selected{/if} >{$cat.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>Download</td>
			<td>
				<select name="download">
				{foreach from=$articleform_downloads item=download}
				<option value="{$download.id}" {if $download.id == $current_article.download}selected{/if} >{$download.date|date_format:"%e-%m-%Y"} - {$download.title}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>Submit</td>
			<td><input type="submit" name="submit" value="Submit" /></td>
		</tr>
	</table>
</form>