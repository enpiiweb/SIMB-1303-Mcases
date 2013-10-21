<table>
<tr><th>Username</th><th>E-mail</th><th>Website</th><th>Approve</th><th>Delete</th></tr>
{foreach from=$phpbbusers item=user}
<tr>
<td>{$user.username}</td>
<td>{$user.user_email}</td>
<td>{$user.website}</td>
<td><a href="phpbbusers.php?action=approve&id={$user.user_id}">Approve</a></td>
<td><a href="phpbbusers.php?action=delete&id={$user.user_id}">Delete</a></td>
</tr>
{/foreach}
</table>
