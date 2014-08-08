
<table>
<?php
	$period = -1;
	$total = 0;
	
	foreach($options['transactions'] as $transaction) {
		
		if($period != $transaction['period']) {
			if($period != -1) {
				echo('<tr><td><b>Total</b></td><td style="text-align: right"><b>' . sprintf('%.02f', $total / 100) . '</b></td></tr>');
				echo('<tr><td><b>Total (per day)</b></td><td style="text-align: right"><b>' . sprintf('%.02f', $total / 100 / 30) . '</b></td></tr>');
				echo('<tr><td colspan="2"><br/></td></tr>');
				$total = 0;
			}
			
			$period = $transaction['period'];
			echo('<tr><th colspan="2">Period: ' . $period . '</th></tr>');
		}
		
		$total = $total + $transaction['total'];		
?>
	<tr>		
		<td><?php echo($transaction['category']); ?></td>
		<td style="text-align: right"><?php echo(sprintf('%.02f', $transaction['total'] / 100)); ?></td>
	</tr>
<?php		
	}
	echo('<tr><td><b>Total</b></td><td style="text-align: right"><b>' . sprintf('%.02f', $total / 100) . '</b></td></tr>');	
	echo('<tr><td><b>Total (per day)</b></td><td style="text-align: right"><b>' . sprintf('%.02f', $total / 100 / 30) . '</b></td></tr>');
?>
</table>