<div id="intakeYearDiv" style="display: none">
	<div class="dv-pad">
		<div class="dv-ht">
	<?php foreach ($allIntakeYears as $key => $date) { ?>
		<p>-<span><?php echo date('M Y', strtotime($date)); ?></span></p>
	<?php } ?>
		</div>
	</div>
</div>