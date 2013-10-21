<?php /* Smarty version 2.6.6, created on 2012-11-20 10:37:07
         compiled from password.tpl */ ?>
<h2>Welcome</h2><p></p>

<form method="post" action="password.php?action=save" />

<table border="1">

 

<tr><td>Old Password</td><td><input type="password" name="oldpassword" /><?php if (isset ( $this->_tpl_vars['password_errors']['oldpassword'] )):  echo $this->_tpl_vars['password_errors']['oldpassword'];  endif; ?></td></tr>

<tr><td>New password</td><td><input type="password" name="password" /><?php if (isset ( $this->_tpl_vars['password_errors']['password'] )):  echo $this->_tpl_vars['password_errors']['password'];  endif; ?></td></tr>

<tr><td>submit</td>       <td><input type="submit" name="submit" value="Submit Now" /></td></tr>

</table>

</form>