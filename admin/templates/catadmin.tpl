<h2>Admin Panel - Years</h2>
<table>
<tr><th>Year</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$catlist_cats item=cat}
<tr>
<td><a href="downloadadmin.php?cat={$cat.id}">{$cat.name}</a></td>
<td><a href="catadmin.php?action=edit&id={$cat.id}">Edit</a></td>
<td><a href="catadmin.php?action=delete&id={$cat.id}" onClick="return confirm('Are you sure you want to delete {$cat.name}?  This will also permanently delete ALL the articles and downloads associated with the year.');">Delete</a></td>
</tr>
{/foreach}
</table>

{if $current_cat.id}
<form method="get" action="catadmin.php" />
	<h2>Edit Year</h2>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="{$current_cat.id}" />
	<table border="1">
		<tr>
			<td>Year</td>
			<td><input type="text" name="name" value="{$current_cat.name}" /></td>
		</tr>
		<tr>
			<td>submit</td>
			<td><input type="submit" name="submit" value="Update" /></td>
		</tr>
	</table>
</form>
{else}
<form method="get" action="catadmin.php" />
	<h2>Add Year</h2>
	<input type="hidden" name="action" value="add" />
	<table border="1">
		<tr>
			<td>Year</td>
			<td><input type="text" name="name" /></td>
		</tr>
		<tr>
			<td>submit</td>
			<td><input type="submit" name="submit" value="Add" /></td>
		</tr>
	</table>
</form>
{/if}