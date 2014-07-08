
function createTextInput(name, size)
{
	var input = document.createElement("input");
	input.setAttribute("name", name);
	input.setAttribute("size", size);
	input.setAttribute("type", "text");
	
	return input;
}

function createDateField(name)
{
	return createTextInput(name, 10);
}

function createTransactionLine(number)
{
	var tr, td;
	
	tr = document.createElement("tr");
	
	td = document.createElement("td");
	td.appendChild( createDateField("timestamp[]") );
	tr.appendChild(td);
	
	tr.appendChild(document.createElement("td"));
	
	td = document.createElement("td");
	td.appendChild( createCategoryField("category_id[]") );
	tr.appendChild(td);
	
	td = document.createElement("td");
	td.appendChild( createCategoryField("category_id[]") );
	tr.appendChild(td);
	
	
	
	return tr;	
}

/*<tr>
<td><?php field_date($i, $date); ?></td>
<td><?php field_person($options, $i, 0); ?></td>
<td><?php field_category($options, $i, 0); ?></td>
<td><?php field_descr($i, ""); ?></td>
<td>&euro; <?php field_amount($i, "0.00"); ?></td>
<td><a href="javascript: clear(<?php echo($i); ?>);">Clear</a></td>
</tr>*/


	function normalize_amount(amount) {
		var normalized = "";

		for(var i = 0; i < amount.length; i++) {
			if(amount[i] >= '0' && amount[i] <= '9')
				normalized += amount[i];
			if(amount[i] == '.' || amount[i] == ',')
				normalized += '.';
		}

		var value = parseFloat(normalized);
		
		return format_float(value, 2);
	}

	function is_key_allowed_for_amount(event) {
		var key = event.keyCode;

		// Number keys
		if(key >= 48 && key <= 57)
			return true;

		// Period
		if(key == 188 || key == 190)
			return true;

		// Backspace
		if(key == 8)
			return true;

		return false;
	}
	

	function format_float(number, decimals) {
		var str = Math.floor(number) + ".";
		var frac = Math.round((number - Math.floor(number)) * 100) + "";

		for(var i = frac.length; i < decimals; i++)
			str += "0";
		str += frac;
		return str;		
	}