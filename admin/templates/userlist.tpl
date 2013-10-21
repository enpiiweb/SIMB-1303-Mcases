<table>
<tr><td colspan="4"><a href="user.php?action=new">New User</a></td></tr>
<tr><th>email</th><th>login</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$userlist_users item=user}
<tr>
<td>{$user.email}</td>
<td>{$user.login}</td>
<td><a href="user.php?action=edit&id={$user.id}">Edit</a></td>
<td>{if ($user.usergroup != -1) }<a href="user.php?action=confirmdelete&id={$user.id}">Delete</a>{else}Cannot delete{/if}</td>
<td><a href="user.php?action=resetpasswordform&id={$user.id}">reset Password</a></td>
</tr>
{/foreach}
</table>




