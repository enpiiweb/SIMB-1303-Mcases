<table>
<tr><th>title</th><th>Case ID</th><th>Court</th><th>Judge(s)</th><th>Date</th><th>Keywords</th><th>File</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$downloadlist_download item=download}
<tr>
<td>{$download.title}</td>
<td>{$download.date|date_format:"%d/%m/%y"}</td>
<td>{$download.file}</td>
<td><a href="downloadadmin.php?action=edit&id={$download.id}">Edit</a></td>
<td><a href="downloadadmin.php?action=confirmdelete&id={$download.id}">Delete</a></td>
</tr>
{/foreach}
</table>
<a href="downloadadmin.php?action=add">Add New</a>




