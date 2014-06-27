
<script>
	function show_add_row() {		
		row = document.getElementById('add-row');
		row.style.visibility = "visible";

		row = document.getElementById('add-row-link');
		row.style.visibility = "collapse";		
	}	
</script>

<h2>Transactions</h2>

<form method="post">
<input type="hidden" name="action" value="add" />
<table class="table-hover">
	<thead>
		<tr>
			<th>Date</th>
			<th>User</th>
			<th>Category</th>
			<th>Description</th>
			<th>Amount</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php 
		foreach($options['transactions'] as $transaction) {
	?>
		<tr>
			<td><?php echo($transaction->getTimestamp()); ?></td>
			<td><?php echo($transaction->getPerson()->getName());; ?></td>
			<td><?php echo($transaction->getCategory()->getName()); ?></td>
			<td><?php echo($transaction->getDescription()); ?></td>
			<td>&euro; <?php echo($transaction->getAmount()); ?></td>
			<td></td>
		</tr>
	<?php } ?>
	</tbody>

	<tfoot>

		<tr id="add-row-link">
			<td colspan="6">
			<a href="javascript:show_add_row();">Add new transactions</a>
			</td>
		</tr>
	
<?php 
	for($i = 0; $i < 1; $i++) {
?>

		<tr id="add-row" class="add">
			<td><input name="timestamp[]" type="text" value="2014-06-27" size="10"/></td>
			<td>
<select name="person_id[]">
<?php foreach($options['people'] as $person) {?>
	<option value="<?php echo($person->getId()); ?>"><?php echo($person->getName()); ?></option>
<?php }?>
</select>
			</td>
			
			<td>
<select name="category_id[]">
<?php foreach($options['categories'] as $category) {?>
	<option value="<?php echo($category->getId()); ?>"><?php
		$parent = $category->getParent();		
		if($parent->getName() != '')
			echo($parent->getName() . ' / ');
		echo($category->getName()); 
	?></option>
<?php }?>
</select>
			</td>
			
			<td><input name="description[]" type="text" /></td>
			<td>&euro; <input name="value[]" type="text" size="5" /></td>
			<td><input type="submit" value="Add" /></td>
<?php } ?>
	</tfoot>
</table>


</form>
