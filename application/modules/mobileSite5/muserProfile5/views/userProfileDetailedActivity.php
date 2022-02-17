
	<?php
		//$iter = 0;
		
		foreach ($activities as $activity) {
			$activity['entityType'] = $entityType;
			$activity['iter'] = $iter;
			$viewTupleData .= $this->load->view($detailTuple,$activity,true);
			$iter++;
		}
		echo $viewTupleData;
	 ?>
	<?php if(!empty($activities)){ ?>
		<input type="hidden" id="statType" value="<?php echo $typeOfStat; ?>"/>
	<?php } ?>