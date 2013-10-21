<h2>Welcome</h2><p></p>
<form method="post" action="catadmin.php?action=save" />
{if $catform_cat->id }<input type="hidden" name="id" value="{$catform_cat->id}" />HIDDEN FIELD{/if}
<table border="1">
<tr><td>Name</td>		<td><input type="text" name="name" value="{$catform_cat->name}" /></td></tr>
<tr><td>submit</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>
