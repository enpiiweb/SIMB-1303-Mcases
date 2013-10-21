<table>
<tr><th>First Name</th><th>Surname</th><th>Edit</th><th>Delete</th></tr>
{foreach from=$customerlist_customer item=customer}
<tr>
<td>{$customer.fname}</td>
<td>{$customer.lname}</td>
<td><a href="customer.php?action=edit&id={$customer.id}">Edit</a></td>
<td><a href="customer.php?action=confirmdelete&id={$customer.id}">Delete</a></td>
</tr>
{/foreach}
</table>
<a href="customer.php?action=add">Add New</a>




