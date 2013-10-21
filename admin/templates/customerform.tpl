
id int unsigned not null primary key auto_increment,
status int(3),*
user int unsigned,
fname varchar (127),
lname varchar (127),
address varchar (127),
suburb varchar (127),
state varchar (127),
country varchar (127),
pcode varchar (127),
phone varchar (127),
mobile varchar (127),
fax varchar (127),
password varchar(15),
readterms int(1) unsigned*


<h2>Welcome</h2><p></p>
<form method="post" action="customer.php?action=save" />
{if $customerform_customer->id }<input type="hidden" name="id" value="{$customerform_customer->id}" />{/if}
<table border="1">

<tr><td>First Name</td>	<td><input type="text" name="fname"		value="{$customerform_customer->fname}" />{if isset($customerform_errors.fname)}<div class="alert">{$customerform_errors.fname}</div>{/if}</td></tr>
<tr><td>Last Name</td>	<td><input type="text" name="lname" 	value="{$customerform_customer->lname}" />{if isset($customerform_errors.lname)}<div class="alert">{$customerform_errors.lname}</div>{/if}</td></tr>
<tr><td>Address</td>	<td><input type="text" name="address"	value="{$customerform_customer->address}" />{if isset($customerform_errors.address)}<div class="alert">{$customerform_errors.address}</div>{/if}</td></tr>
<tr><td>Suburb</td>	<td><input type="text" name="suburb"	value="{$customerform_customer->suburb}" />{if isset($customerform_errors.suburb)}<div class="alert">{$customerform_errors.suburb}</div>{/if}</td></tr>
<tr><td>State</td>	<td><input type="text" name="state"		value="{$customerform_customer->state}" />{if isset($customerform_errors.state)}<div class="alert">{$customerform_errors.state}</div>{/if}</td></tr>
<tr><td>Country</td>	<td><input type="text" name="country"	value="{$customerform_customer->country}" />{if isset($customerform_errors.country)}<div class="alert">{$customerform_errors.country}</div>{/if}</td></tr>
<tr><td>pcode</td>	<td><input type="text" name="pcode"		value="{$customerform_customer->pcode}" size="5" maxlength="5"/>{if isset($customerform_errors.pcode)}<div class="alert">{$customerform_errors.pcode}</div>{/if}</td></tr>
<tr><td>phone</td>	<td><input type="text" name="phone"		value="{$customerform_customer->phone}" />{if isset($customerform_errors.phone)}<div class="alert">{$customerform_errors.phone}</div>{/if}</td></tr>
<tr><td>mobile</td>	<td><input type="text" name="mobile"	value="{$customerform_customer->mobile}" />{if isset($customerform_errors.mobile)}<div class="alert">{$customerform_errors.mobile}</div>{/if}</td></tr>
<tr><td>fax</td>		<td><input type="text" name="fax"		value="{$customerform_customer->fax}" />{if isset($customerform_errors.fax)}<div class="alert">{$customerform_errors.fax}</div>{/if}</td></tr>
<tr><td>E-mail</td>	<td><input type="text" name="email"		value="{$customerform_customer->email}" />{if isset($customerform_errors.email)}<div class="alert">{$customerform_errors.email}</div>{/if}</td></tr>
<tr><td>password</td>	<td><input type="password" name="password" />{if isset($customerform_errors.password)}<div class="alert">{$customerform_errors.password}</div>{/if}</td></tr>
<tr><td>confirmpassword</td><td><input type="password" name="confirmpassword" />{if isset($customerform_errors.confirmpassword)}<div class="alert">{$customerform_errors.confirmpassword}</div>{/if}</td></tr>



<tr><td>submit</td>	<td><input type="submit" name="submit" value="Submit Now" /></td></tr>
</table>
</form>



