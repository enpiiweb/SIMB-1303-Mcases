<table>
<tr><th>title</th><th>Case ID</th><th>Court</th><th>Judge(s)</th><th>Date</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$articlelist_articles item=article}
<tr>
<td>{$article.title}</td>
<td>{$article.caseid}/{$article.courtid}</td>
<td>{$article.court}</td>
<td>{$article.judge}</td>
<td>{$article.date|date_format:"%d/%m/%y"}</td>
<td><a href="articleadmin.php?action=edit&id={$article.id}">Edit</a></td>
<td><a href="articleadmin.php?action=confirmdelete&id={$article.id}">Delete</a></td>
</tr>
{/foreach}
</table>
<a href="articleadmin.php?action=add">Add New</a>




