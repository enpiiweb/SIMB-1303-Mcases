{literal}<style type="text/css">
<!--
.style2 {color: #999999}
-->
</style>{/literal}
<p><strong><span class="style2">Step 1: Submit details</span> | Step 2: </strong> <strong>Make
payment to complete your subscription</strong></p>
<p>Pay AUD$250 subscription for 12 months by credit card using secure eWay:<br>
  <br>
</p>
<form method="post" action="https://www.eway.com.au/gateway_cvn/payment.asp">
  	<input type="hidden" name="ewayCustomerID" 
  	value="{$ewayCustomerID}" />
	<input type="hidden" name="ewayTotalAmount" 
	value="{$ewayTotalAmount}" />
	<input type="hidden" name="ewayCustomerFirstName" 
	value="{$cust_firstname}" />
	<input type="hidden" name="ewayCustomerLastName" 
	value="{$cust_lastname}" />
	<input type="hidden" name="ewayCustomerEmail" 
	value="{$cust_email}" />
	<input type="hidden" name="ewayCustomerAddress" 
	value="{$cust_address}" />
	<input type="hidden" name="ewayCustomerPostcode" 
	value="{$cust_postcode}" />
	<input type="hidden" name="ewayCustomerInvoiceDescription" 
	value="{$invc_descript}" />
	<input type="hidden" name="ewayCustomerInvoiceRef" 
	value="{$cust_invoiceref}" />
	<input type="hidden" name="eWAYoption1" 
	value="{$cust_invoiceref}" />
	<input type="hidden" name="ewayURL" value="{$return_url}" />
	<input type="hidden" name="ewaySiteTitle" 
	value="{$site_title}" />
	
	

	
	<input type="submit" value="Process Transaction" 
	name="submit" /><p></p>
</form>