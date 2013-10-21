<h2>Welcome</h2><p></p>

<form method="post" action="password.php?action=save" />

<table border="1">

 

<tr><td>Old Password</td><td><input type="password" name="oldpassword" />{if isset($password_errors.oldpassword) }{$password_errors.oldpassword}{/if}</td></tr>

<tr><td>New password</td><td><input type="password" name="password" />{if isset($password_errors.password) }{$password_errors.password}{/if}</td></tr>

<tr><td>submit</td>       <td><input type="submit" name="submit" value="Submit Now" /></td></tr>

</table>

</form>
