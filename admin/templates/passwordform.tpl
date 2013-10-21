restting password for {$passwordform_user->login}<br>
<form method="post" action="user.php?action=resetpasswordsave" />
<input type="hidden" name="id" value="{$passwordform_user->id}" />
<table border="1">
<tr><td>Password</td>	<td><input type="text" name="password" />{if isset($userpassword_errors.password)}<div class="alert">{$userpassword_errors.password}</div>{/if}</td></tr>
<tr><td>&nbsp;</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>



