<form method="post" action="user.php?action=save" />
<input type="hidden" name="id" value="{$userform_user->id}" />
<table border="1">
<tr><td>Email</td>	<td><input type="text" name="email"		value="{$userform_user->email}" />{if isset($userform_errors.email)}<div class="alert">{$userform_errors.email}</div>{/if}</td></tr>
<tr><td>Login</td>	<td><input type="text" name="login"		value="{$userform_user->login}" />{if isset($userform_errors.login)}<div class="alert">{$userform_errors.login}</div>{/if}</td></tr>
<tr><td>submit</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>



