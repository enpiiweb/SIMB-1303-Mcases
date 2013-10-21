<form method="post" action="user.php?action=save" />

{if $customerform_customer->id }<input type="hidden" name="id" value="{$customerform_customer->id}" />{/if}

<table border="0" cellpadding="2" cellspacing="2">



<tr><td><div align="right">First Name </div></td>	<td><input type="text" name="fname"		value="{$customerform_customer->fname}" />

  *{if isset($customerform_errors.fname)}<div class="alert">{$customerform_errors.fname}</div>{/if}</td></tr>

<tr><td><div align="right">Last Name </div></td>	<td><input type="text" name="lname" 	value="{$customerform_customer->lname}" />

  *{if isset($customerform_errors.lname)}<div class="alert">{$customerform_errors.lname}</div>{/if}</td></tr>

<tr><td><div align="right">Address </div></td>	<td><input type="text" name="address"	value="{$customerform_customer->address}" />

  *{if isset($customerform_errors.address)}<div class="alert">{$customerform_errors.address}</div>{/if}</td></tr>

<tr><td><div align="right">Suburb </div></td>	<td><input type="text" name="suburb"	value="{$customerform_customer->suburb}" />

  *{if isset($customerform_errors.suburb)}<div class="alert">{$customerform_errors.suburb}</div>{/if}</td></tr>

<tr><td><div align="right">State </div></td>	<td><input type="text" name="state"		value="{$customerform_customer->state}" />

  *{if isset($customerform_errors.state)}<div class="alert">{$customerform_errors.state}</div>{/if}</td></tr>

<tr><td><div align="right">Country</div></td>	<td><input type="text" name="country"	value="{$customerform_customer->country}" />{if isset($customerform_errors.country)}<div class="alert">{$customerform_errors.country}</div>{/if}</td></tr>

<tr>

  <td><div align="right">Postcode</div></td>	

  <td><input type="text" name="pcode"		value="{$customerform_customer->pcode}" size="5" maxlength="5"/>

    *

    {if isset($customerform_errors.pcode)}

    <div class="alert">{$customerform_errors.pcode}</div>{/if}</td></tr>

<tr>

  <td><div align="right">Phone</div></td>	

  <td><input type="text" name="phone"		value="{$customerform_customer->phone}" />{if isset($customerform_errors.phone)}<div class="alert">{$customerform_errors.phone}</div>{/if}</td></tr>

<tr>

  <td><div align="right">Mobile</div></td>	

  <td><input type="text" name="mobile"	value="{$customerform_customer->mobile}" />{if isset($customerform_errors.mobile)}<div class="alert">{$customerform_errors.mobile}</div>{/if}</td></tr>

<tr>

  <td><div align="right">Fax</div></td>		

  <td><input type="text" name="fax"		value="{$customerform_customer->fax}" />{if isset($customerform_errors.fax)}<div class="alert">{$customerform_errors.fax}</div>{/if}</td></tr>

<tr>

  <td><div align="right">Email </div></td>	

  <td><input type="text" name="email"		value="{$customerform_customer->email}" />

    *{if isset($customerform_errors.email)}<div class="alert">{$customerform_errors.email}</div>{/if}</td></tr>

<tr>




<tr><td>&nbsp;</td>	

<td><p>

  <input type="submit" name="submit" value="Update" />

</p>

  <p>* required  </p></td></tr>

</table>

</form>
