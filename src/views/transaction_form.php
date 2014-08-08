<?php 
	function field_subcategory($options, $i, $value)
	{
		echo("<select id=\"subcategory_id_$i\" name=\"subcategory_id_$i\">");
		
		foreach($options['subcategories'] as $subcategory) {
			$id = $subcategory->getId();

			echo("<option value=\"$id\"");
			if($id == $value)
				echo( "selected");
			echo(">");

			$parent = $subcategory->getCategory();
			echo($subcategory->getName()); 
			if($parent->getName() != $subcategory->getName())
				echo(' (' . $parent->getName() . ')');
			echo("</option>");
		}
		
		echo("</select>");
	}

	function field_date($i, $value)
	{
		$value = htmlentities($value);
		echo("<input name=\"timestamp_$i\" type=\"text\" size=\"10\" value=\"$value\" />");
	}
	
	function field_descr($i, $value)
	{
		$value = htmlentities($value);
		echo("<input id=\"description_$i\" name=\"description_$i\" type=\"text\" size=\"20\" value=\"$value\" />");
	}
	
	function field_amount($i, $value)
	{
		$value = sprintf('%.02f', $value / 100);
		$value = htmlentities($value);
		echo("<input id=\"amount_$i\" name=\"amount_$i\" type=\"text\" size=\"6\" value=\"$value\" />");
	}
?>

<script src="<?php $viewManager->renderResourceLink('js/transaction_form.js'); ?>"></script>

<script>
	function clear(index) {
		// Clear fields
		document.getElementById('description_' + index).value = "";
		document.getElementById('amount_' + index).value = "0.00";

		// Move rows that are still in use up

		// Remove empty rows at the end, leave one

	}
</script>

<h2>Add transactions</h2>

<form method="post">
<input type="hidden" name="action" value="add" />
<table class="table-hover">
	<thead>
		<tr>
			<th>Date</th>
			<th>Category</th>
			<th>Description</th>
			<th>Amount</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$i = 0;
		foreach($options['transactions'] as $transaction) {
	?>
		<tr>
			<td><?php field_date($i, $transaction->getTimestamp()); ?></td>
			<td><?php field_subcategory($options, $i, $transaction->getCategory()->getId()); ?></td>
			<td><?php field_descr($i, $transaction->getDescription()); ?></td>
			<td>&euro; <?php field_amount($i, $transaction->getAmount()); ?></td>
			<td>
				<a href="javascript: clear(<?php echo($i); ?>);">Clear</a>
			</td>
		</tr>
	<?php 
			$i++;
		}

		$j = $i + 1;		
		if($j < 10) $j = 10;
		
		$date = date('Y-m-d');
		
		for(; $i < $j; $i++)
		{
	?>
	
		<tr>
			<td><?php field_date($i, $date); ?></td>
			<td><?php field_subcategory($options, $i, 0); ?></td>
			<td><?php field_descr($i, ""); ?></td>
			<td>&euro; <?php field_amount($i, "0.00"); ?></td>
			<td><a href="javascript: clear(<?php echo($i); ?>);">Clear</a></td>
		</tr>
	<?php 
		}
	?>
	</tbody>
	
	<tfoot>
		<tr>
			<td colspan="6"><input type="submit" value="Add transactions" /></td>
		</tr>
	</tfoot>
</table>

</form>

<script>
	var i = 0;
	var element = document.getElementById('amount_' + i);

	while(element) {
		element.onclick = function() {
			this.select();
		};

		element.onkeydown = function(event) {
			return is_key_allowed_for_amount(event);			
		}
		
		element.onchange = function() {
			this.value = normalize_amount(this.value);
		};
		
		i++;
		element = document.getElementById('amount_' + i);
	}
	
//document.getElementById('thing').onfocus = function(){
//    this.select();
//};​
</script>
