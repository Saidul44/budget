
<script>
	function show_add_row() {		
		row = document.getElementById('add-row');
		row.style.visibility = "visible";

		row = document.getElementById('add-row-link');
		row.style.visibility = "collapse";		
	}	
</script>

<h2>Categories</h2>

<table class="table-hover">
	<tr><th>Category</th><th>Parent category</th><th></th><th></th</tr>
	
<?php

	foreach($options['categories'] as $category) {
		$parentName = $category->getParent()->getName();
		if($parentName == '') $parentName = '(none)';
?>
	<tr>
		<td><?php echo($category->getName()); ?></td>
		<td><?php echo($parentName); ?></td>
		<td><a href="<?php $viewManager->renderLink('category', 'edit', array('id' => $category->getId())); ?>">Edit</a></td>
		<td>
			<form id="delete-form-<?php echo($category->getId()); ?>" method="post" action="<?php $viewManager->renderLink('category', 'delete'); ?>">
			<input type="hidden" name="category_id" value="<?php echo($category->getId()); ?>" /> 
			<a href="javascript: document.getElementById('delete-form-<?php echo($category->getId()); ?>').submit()">Delete</a>
			</form>
		</td>
	</tr>
<?php
	}
?>
	
	<tr>
		<td colspan="4">
			<a href="<?php echo($viewManager->linkTo('category', 'new')); ?>">Add new category</a>
		</td>
	</tr>
</table>
