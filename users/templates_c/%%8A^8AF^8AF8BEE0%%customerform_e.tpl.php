<?php /* Smarty version 2.6.6, created on 2012-11-20 10:36:56
         compiled from customerform_e.tpl */ ?>
<form method="post" action="user.php?action=save" />

<?php if ($this->_tpl_vars['customerform_customer']->id): ?><input type="hidden" name="id" value="<?php echo $this->_tpl_vars['customerform_customer']->id; ?>
" /><?php endif; ?>

<table border="0" cellpadding="2" cellspacing="2">



<tr><td><div align="right">First Name </div></td>	<td><input type="text" name="fname"		value="<?php echo $this->_tpl_vars['customerform_customer']->fname; ?>
" />

  *<?php if (isset ( $this->_tpl_vars['customerform_errors']['fname'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['fname']; ?>
</div><?php endif; ?></td></tr>

<tr><td><div align="right">Last Name </div></td>	<td><input type="text" name="lname" 	value="<?php echo $this->_tpl_vars['customerform_customer']->lname; ?>
" />

  *<?php if (isset ( $this->_tpl_vars['customerform_errors']['lname'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['lname']; ?>
</div><?php endif; ?></td></tr>

<tr><td><div align="right">Address </div></td>	<td><input type="text" name="address"	value="<?php echo $this->_tpl_vars['customerform_customer']->address; ?>
" />

  *<?php if (isset ( $this->_tpl_vars['customerform_errors']['address'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['address']; ?>
</div><?php endif; ?></td></tr>

<tr><td><div align="right">Suburb </div></td>	<td><input type="text" name="suburb"	value="<?php echo $this->_tpl_vars['customerform_customer']->suburb; ?>
" />

  *<?php if (isset ( $this->_tpl_vars['customerform_errors']['suburb'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['suburb']; ?>
</div><?php endif; ?></td></tr>

<tr><td><div align="right">State </div></td>	<td><input type="text" name="state"		value="<?php echo $this->_tpl_vars['customerform_customer']->state; ?>
" />

  *<?php if (isset ( $this->_tpl_vars['customerform_errors']['state'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['state']; ?>
</div><?php endif; ?></td></tr>

<tr><td><div align="right">Country</div></td>	<td><input type="text" name="country"	value="<?php echo $this->_tpl_vars['customerform_customer']->country; ?>
" /><?php if (isset ( $this->_tpl_vars['customerform_errors']['country'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['country']; ?>
</div><?php endif; ?></td></tr>

<tr>

  <td><div align="right">Postcode</div></td>	

  <td><input type="text" name="pcode"		value="<?php echo $this->_tpl_vars['customerform_customer']->pcode; ?>
" size="5" maxlength="5"/>

    *

    <?php if (isset ( $this->_tpl_vars['customerform_errors']['pcode'] )): ?>

    <div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['pcode']; ?>
</div><?php endif; ?></td></tr>

<tr>

  <td><div align="right">Phone</div></td>	

  <td><input type="text" name="phone"		value="<?php echo $this->_tpl_vars['customerform_customer']->phone; ?>
" /><?php if (isset ( $this->_tpl_vars['customerform_errors']['phone'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['phone']; ?>
</div><?php endif; ?></td></tr>

<tr>

  <td><div align="right">Mobile</div></td>	

  <td><input type="text" name="mobile"	value="<?php echo $this->_tpl_vars['customerform_customer']->mobile; ?>
" /><?php if (isset ( $this->_tpl_vars['customerform_errors']['mobile'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['mobile']; ?>
</div><?php endif; ?></td></tr>

<tr>

  <td><div align="right">Fax</div></td>		

  <td><input type="text" name="fax"		value="<?php echo $this->_tpl_vars['customerform_customer']->fax; ?>
" /><?php if (isset ( $this->_tpl_vars['customerform_errors']['fax'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['fax']; ?>
</div><?php endif; ?></td></tr>

<tr>

  <td><div align="right">Email </div></td>	

  <td><input type="text" name="email"		value="<?php echo $this->_tpl_vars['customerform_customer']->email; ?>
" />

    *<?php if (isset ( $this->_tpl_vars['customerform_errors']['email'] )): ?><div class="alert"><?php echo $this->_tpl_vars['customerform_errors']['email']; ?>
</div><?php endif; ?></td></tr>

<tr>




<tr><td>&nbsp;</td>	

<td><p>

  <input type="submit" name="submit" value="Update" />

</p>

  <p>* required  </p></td></tr>

</table>

</form>