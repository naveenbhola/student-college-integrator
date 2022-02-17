<div class="progress-sec">
	<!--complete-prgs-->
	<label>Form Progress</label>
	<div class="progress-bar flRt">
		<?php
		$className = "";
		if($percentage_completion == "100"){
			$className = "complete-prgs";
		}
		?>
		<div class="selected-percent <?php echo $className;?>" style="width:<?php echo $percentage_completion;?>%;"></div>
		<div class="prcnt-messg"><?php echo $percentage_completion;?>% completed</div>
	</div>
</div>