<?php
global $appliedFilters;
$filterSelected = false;
if(!empty($appliedFilters)){
	foreach($appliedFilters as $values){
		if(count($values) > 0){
			$filterSelected = true;
			break;
		}
	}
}
?>
<div class="selection-box">
<strong>Your Selection:</strong>
	<div class="selection-details" id="usf_cont">
		<?php
		if(!$filterSelected){
			echo "<span>No filter selected</span>";
		}
		?>		   
	</div>
</div>