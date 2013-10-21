{literal}<style type="text/css">
<!--
.style2 {color: #999999}
-->
</style>{/literal}
<span class="style2">Confirm Order</span><br><br>
<table>
	<tr>
		<td>
			<strong>Quantity:</strong><br>
		{foreach from=$order.summary item=i}
			{$i.quantity}x {$i.book}<br>
		{/foreach}
		</td>
	</tr>
	<tr><td><strong>Total:</strong> AUD${$order.total}</td></tr>
</table>
Pay by credit card using secure eWay.
<form method="post" action="https://www.eway.com.au/gateway_cvn/payment.asp">
	<input type="hidden" name="ewayCustomerID" value="{$ewayCustomerID}" />
	<input type="hidden" name="ewayTotalAmount" value="{$ewayTotalAmount}" />
	<input type="hidden" name="ewayCustomerFirstName" value="{$cust_firstname}" />
	<input type="hidden" name="ewayCustomerLastName" value="{$cust_lastname}" />
	<input type="hidden" name="ewayCustomerEmail" value="{$cust_email}" />
	<input type="hidden" name="ewayCustomerAddress" value="{$cust_address}" />
	<input type="hidden" name="ewayCustomerPostcode" value="{$cust_postcode}" />
	<input type="hidden" name="ewayCustomerInvoiceDescription" value="{$invc_descript}" />
	<input type="hidden" name="ewayCustomerInvoiceRef" value="{$cust_invoiceref}" />
	<input type="hidden" name="eWAYoption1" value="{$order.total_quantity}" />
	<input type="hidden" name="ewayURL" value="{$return_url}" />
	<input type="hidden" name="ewaySiteTitle" value="{$site_title}" />
	<input type="submit" value="Process Transaction" name="submit" /><p></p>
</form>