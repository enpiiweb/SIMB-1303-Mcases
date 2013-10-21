Search form

<form action="search.php?action=search" method="post">
<table border="1">
<tr><td>Title</td><td><input type="text" name="search_title" /></td></tr>
<tr><td>Court</td><td><input type="text" name="search_court" /></td></tr>
<tr><td>Judge</td><td><input type="text" name="search_judge" /></td></tr>
<tr><td>Keywords</td><td><input type="text" name="search_keywords" /></td></tr>
<tr><td>Category</td><td>
	<select name="search_cat">
	<option value="" >Any</option>
	{foreach from=$categories item=cat}
	<option value="{$cat.id}">{$cat.name}</option>
	{/foreach}
	</select>
</td></td>
<tr><td>content</td><td><input type="text" name="search_content" /></td></tr>
<tr><td>Start Year</td><td>{html_options name=search_startyear options=$searchform_years}</td></tr>
<tr><td>End Year</td><td>{html_options name=search_endyear options=$searchform_years}</td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="submit" /></td></tr>
</table>
</form>
	