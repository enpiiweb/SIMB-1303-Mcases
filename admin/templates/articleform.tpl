<script language="JavaScript" type="text/javascript" src="{$smartyglobal.relroot}CMS/js/html2xhtml.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$smartyglobal.relroot}CMS/js/richtext.js"></script>
<h2>Welcome</h2><p></p>
<form method="post" name="articleform" action="articleadmin.php?action=save" onSubmit="return submitForm();">
{if $articleform_article->id }<input type="hidden" name="id" value="{$articleform_article->id}" />{/if}
<table border="1">
<tr><td>Title</td>	<td><input type="text" name="title" value="{$articleform_article->title}" />{if isset($articleform_errors.title)}<div class="alert">{$articleform_errors.title}</div>{/if}</td></tr>
<tr><td>Case Number</td><td><input type="text" name="caseid" value="{$articleform_article->caseid}" /> / <input type="text" name="courtid" value="{$articleform_article->courtid}" /> </td></tr>
<tr><td>court</td>	<td><input type="text" name="court" value="{$articleform_article->court}" /></td></tr>
<tr><td>restricted</td>	<td><input type="checkbox" name="restricted" {if $articleform_article->restricted == 1}CHECKED{/if} /></td></tr>
<tr><td>judge</td>	<td><input type="text" name="judge" value="{$articleform_article->judge}" /></td></tr>
<tr><td>date</td>		<td>
				Day {html_options name=day options=$articleform_days selected=$articleform_article->date|date_format:"%e"}
				Months {html_options name=month options=$articleform_months selected=$articleform_article->date|date_format:"%m"}
				Year {html_options name=year options=$articleform_years selected=$articleform_article->date|date_format:"%Y"}
				</td></tr>
<tr><td>Keywords</td>	<td><textarea name="keywords" cols="40" rows="5">{$articleform_article->keywords}</textarea></td></tr>
<tr><td>content</td>	<td>
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
    document.write('<textarea name="content" cols="40" rows="15">{$articleform_article->RTEContent()}</textarea>');
else
    writeRichText('content', '{$articleform_article->RTEContent()}', 420, 300, true, false);
//-->
</script>



</td></tr>
<tr><td>cat</td>		<td>
				<select name="cat">
					{foreach from=$articleform_cats item=cat}
					<option value="{$cat.id}" {if $cat.id == $articleform_article->cat}selected{/if} >{$cat.name}</option>
					{/foreach}
				</select>
				</td></tr>
<tr><td>Download</td>	<td>
				<select name="download">
				{foreach from=$articleform_downloads item=download}
				<option value="{$download.id}" {if $download.id == $articleform_article->download}selected{/if} >{$download.date|date_format:"%e-%m-%Y"} - {$download.title}</option>
				{/foreach}
				</select>

				</td></tr>
<tr><td>submit</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>



