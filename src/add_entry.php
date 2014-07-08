
<!-- We have a category savings -->
<!-- Warm colors -> big expenses like mybudget360 -->

<style>
	body {
		font-family: tahoma, sans-serif;
	}
</style>

Who: <select><option>Ivar</option></select>
<br />

<table>
<tr><th>Description</th><th>Amount</th><th>Category</th><th>Subcategory</th></tr>
<?php for($i = 0; $i < 10; $i++) { ?>
<tr>

<td><input type="text" /></td>
<td>&euro; <input type="text" size="6" /></td>
<td>
	<select>
		<option>Transportation</option>
	</select>
</td>

<td><select><option>Flights</option></select></td>
</tr>
<?php } ?>
</table>

<input type="submit" value="Add" />

