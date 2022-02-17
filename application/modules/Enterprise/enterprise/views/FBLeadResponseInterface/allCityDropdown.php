<?php 
	$html = '<select id="shikshaCity_'.$old_value.'" class="form-control"><option value="-1">Select City</option>';
	foreach ($all_cities as $key => $value) {
		$html .= '<option value='.$key.' >'.$value.'</option>';
	}

	$html .= "</select>";
	echo $html;
?>

