<table>
<tr><th>Name</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$catlist_cats item=cat}
<tr>
<td>{$cat.name}</td>
<td><a href="catadmin.php?action=edit&id={$cat.id}">Edit</a></td>
<td><a href="catadmin.php?action=confirmdelete&id={$cat.id}">Delete</a></td>
</tr>
{/foreach}
</table>
<a href="catadmin.php?action=add">Add New</a>