<script>
	function deleteTransaction(id) {
		var response = confirm("Do you really want to delete " +
			"transaction " + id + "?");
	
		if(response == true) {
			var form = document.getElementById('delete-form-' + id);
			form.submit();
		}
	}
</script>

<h2>Transactions</h2>

<p>
<?php 
	$page = $options['navigation']['page'];
	$prev_page = $page - 1;
	$next_page = $page + 1;
	$last_page = $options['navigation']['pages'];

	if($page > 1) { ?>
<a href="<?php $viewManager->renderLink('transaction', 'list', array('page' => $prev_page)); ?>"/>Previous page</a> |
	<?php } ?>
Page <?php echo($options['navigation']['page']); ?> of <?php echo($options['navigation']['pages']); ?>
<?php 
	if($page < $last_page) {
?>
 | <a href="<?php $viewManager->renderLink('transaction', 'list', array('page' => $next_page)); ?>"/>Next page</a>
<?php } ?>
</p>


<table class="table-hover">
	<thead>
		<tr>
			<th>Date</th>
			<th>User</th>
			<th>Category</th>
			<th>Description</th>
			<th>Amount</th>
			<th></th>
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
			<td align="right">&euro; <?php echo(sprintf('%.02f', $transaction->getAmount()/100.0)); ?></td>
			<td><a href="<?php $viewManager->renderLink('transaction', 'edit', array('id' => $transaction->getId())); ?>">Edit</a></td>
			<td>
				<form id="delete-form-<?php echo($transaction->getId()); ?>" method="post" action="<?php $viewManager->renderLink('transaction', 'delete'); ?>">
				<input type="hidden" name="transaction_id" value="<?php echo($transaction->getId()); ?>" /> 
				<a href="javascript: deleteTransaction(<?php echo($transaction->getId()); ?>);">Delete</a>
				</form>
			</td>
		</tr>
	<?php } ?>
	</tbody>

	<tfoot>
		<tr>		
			<td colspan="6">
			<a href="<?php $viewManager->renderLink('transaction', 'add'); ?>">Add new transactions</a>
			</td>
		</tr>
	</tfoot>
</table>

