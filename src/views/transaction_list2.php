<?php 
	function write_subtotal($date, $amount) {
		echo('<tr style="border-top: 1px solid #000000"><td></td><td colspan="3"</td><td><i>&euro; ' . sprintf('%.2f', $amount / 100.0) . '</i><br />&nbsp;</td></tr>');
	}
?>

<style>
	div#week_overview {
		float:left; 
		width: 75%;
	}

	span#week_number {
		font-size: larger; 
		font-weight: bold; 
		padding-bottom: 5px; 
		display: block;	
	}
	
	span#week_dates {
		font-size: small; 
		display: block; 
		padding-bottom: 10px;
	}
</style>

<div id="week_overview">
	<span id="week_number">
		Week <?=date('W', $options['timespan'][0])?>
	</span>
	
	<span id="week_dates">
		<?=date('F j, Y', $options['timespan'][0])?>
		to
		<?=date('F j, Y', $options['timespan'][1])?>
	</span>
	
	<table class="table-hover">
		<thead>
		<tr>
			<th>Date</th>
			<th>Category</th>
			<th>Subcategory</th>
			<th>Description</th>
			<th>Amount</th>
		</tr>		
		</thead>

		<tbody>		
<?php
	$subtotal = 0;
	$timestamp = '';
	while($row = $options['transactions']->fetch())
	{
		if($timestamp == '') $timestamp = $row['timestamp'];
		
		if($timestamp <> $row['timestamp']) {
			write_subtotal($timestamp, $subtotal);
			$timestamp = $row['timestamp'];
			$subtotal = 0;
		}
?>
			<tr>
				<td><?=$row['timestamp']?></td>
				<td><?=$row['category']?></td>
				<td><?=$row['subcategory']?></td>
				<td><?=$row['description']?></td>
				<td>&euro; <?=sprintf('%.02f', $row['amount'] / 100.0)?></td>				
			</tr>	
<?php
		$subtotal += $row['amount'];
	}
	
	write_subtotal($timestamp, $subtotal);
?>		
		</tbody>
	<tfoot>
		<tr>		
			<td colspan="5">
			<a href="<?php $viewManager->renderLink('transaction', 'add'); ?>">Add new transactions</a>
			</td>
		</tr>
	</tfoot>
		
	</table>


</div>

<div style="float: right">

<table class="table-hover">
<?php
	$week = 0;

	while($row = $options['overview']->fetch()) {	
		if($week <> $row['week']) {
?>
			<tr><td colspan="2">
			<b>Week <?=$row['week']?></b>
			</td></tr>
<?php
			$week = $row['week'];
		}
?>
		<tr>
			<td><?=$row['category']?></td>
			<td>&euro; <?=sprintf('%.2f', $row['total'] / 100.0)?></td>
		</tr>
<?php
	}
?>
</table>

</div>