
<?php
 foreach ($activities as $activity) {
				$activity['entityType'] = $entityType;
				$activity['iter'] = $iter;
				$viewTupleData .= $this->load->view($detailTuple,$activity,true);
				$iter++;
			}
		echo $viewTupleData;
			?>

	<?php if(!empty($activities)){ ?>
		<div class='activiy-data no_show' id='nothing_show' style="display:none"><p class="n-show">There is no activity to display.</p></div>
		<input type="hidden" id="statType" value="<?php echo $typeOfStat; ?>"/>
	<?php } ?>