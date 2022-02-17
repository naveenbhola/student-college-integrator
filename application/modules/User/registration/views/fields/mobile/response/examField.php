<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative" >
	<div class="reg-form signup-fld invalid tDis" layerFor="exams" regfieldid="exams" id="exams_block_<?php echo $regFormId; ?>" mandatory="0" caption="Choose Exams" type="layer" hasSearch='1' caption="Choose Exams" type="layer" hasSearch='1' layerTitle="Exams" layerHeading="Exams taken" sub-label="Select one or more">
		<div class="ngPlaceholder">Exams taken <span class="optl">(Optional)</span></div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please enter exams.</div>
		</div>
	</div>

	<div class="crse-layer layerHtml ih" id="examField_<?php echo $regFormId; ?>">

		<?php if (count($examList) > 7) {?>
		<div class="cr-srDiv">
			<div class="srch-bx cSearchX">
				<i class="src-icn"></i>
				<input type="text" placeholder="Search Exam" class="cSearch"/>
				<i class="crss-icn ih">&times;</i>
			</div>
		</div>
		<?php }?>
		<div class="lyr-sblst ctmScroll">
			<div class="unmppedSpec">

				<?php foreach ($examList as $examId => $examName) {?>
					<input type="checkbox" class="unmp cLevel exams_<?php echo $regFormId; ?>" classHolder="exams_<?php echo $regFormId; ?>" id="exams_<?php echo $examId; ?>" value="<?php echo $examName; ?>" <?php if (!empty($userDetails['exams']) && in_array($examName, $userDetails['exams'])) {echo 'checked';}?> name="exams[]" label="<?php echo $examName; ?>" match="exams_<?php echo $examId; ?>" norest = 'yes'>
				<?php }?>

			</div>
		</div>

		<div class="ih">
		</div>

	</div>
</div>