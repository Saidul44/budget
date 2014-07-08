<?php 
	// Load defaults
	if(array_key_exists('category', $options)) {
		$category = $options['category'];
		
		$name = $category->getName();
		$parent_id = $category->getParent()->getId();

		if($category->getId())
			$mode = 'edit';
		else
			$mode = 'add';
	} else {
		$name = "";
		$parent_id = $options['root']->getId();
		$mode = 'add';
	}
?>

<h2><?php if($mode == 'add') echo('Add'); else echo('Edit'); ?> category</h2>

<form method="POST">

Name:<br />
<input name="name" type="text" value="<?php echo($name); ?>" /><br />
<br />
Parent category:<br />

<select name="parent_id">
	<option value="<?php echo($options['root']->getId()); ?>">Root category</option>
<?php	
	foreach($options['categories'] as $category) {
		$id = $category->getId();

		echo("<option value=\"$id\"");
		if($id == $parent_id)
			echo( "selected");
		echo(">");

		$parent = $category->getParent();
		if($parent->getName() != '')
			echo($parent->getName() . ' / ');
		echo($category->getName());
	echo("</option>");
	}
?></select><br />
<br />
<input type="submit" value="<?php if($mode == 'add') echo('Add'); else echo('Update'); ?> category" />

</form>