
<script>
	function show_add_row() {		
		row = document.getElementById('add-row');
		row.style.visibility = "visible";

		row = document.getElementById('add-row-link');
		row.style.visibility = "collapse";		
	}	
</script>

<h2>Categories</h2>

<?php
	echo('<table class="table-hover">');
	echo('<tr><th>Category</th><th>Parent category</th><th></th></tr>');
	foreach($options['categories'] as $category) {
		$parentName = $category->getParent()->getName();

		if($parentName == '') $parentName = '(none)';
		echo('<tr>');
		echo('<td>' . $category->getName() . '</td>');
		echo('<td>' . $parentName . '</td>');
		echo('<td><a href="">Edit</a></td>');
		echo('</tr>');
	} 
	
	echo('<tr id="add-row-link"><td colspan="3"><a href="javascript:show_add_row()">Add new category</a></td></tr>');
	
	echo('<tr id="add-row" class="add"><td><input type="text" size="20" /></td><td>');
	echo('<select>');
	echo('<option>(none)</option>');
	foreach($options['categories'] as $category) {
		echo('<option>' . $category->getName() . '</option>');
	}
	
	echo('</select>');	
	echo('</td><td><input type="submit" value="Add" /></tr>');
	echo('</table>');
?>