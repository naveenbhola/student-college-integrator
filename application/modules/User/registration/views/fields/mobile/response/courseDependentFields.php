<!-- Exam Field -->
<?php if(!empty($examList)){ 
	$this->load->view('registration/fields/mobile/response/examField');
 } ?>

<?php if(!empty($mappedHierarchies) && 0){ ?>
	<div class="ih mappedHierarchies">
		<?php
			$unMapppedSpec = $mappedHierarchies['0'];
			unset($mappedHierarchies['0']);
		?>
		<?php foreach($mappedHierarchies as $subStreamId=>$specializations){ ?> 
				<div class="subStrmGrp">
					<input type="checkbox" id="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" class="pLevel sSS ih" name="subStream[]" value="<?php echo $subStreamId; ?>"  checked="checked">
					<div class="child">
						<?php if(!empty($specializations)){ ?>

							<?php foreach($specializations as $key=>$spec){ ?>

								<input type="checkbox" id="spec_<?php echo $spec.'_'.$subStreamId.'_'.$regFormId; ?>_uss" class="cLevel sSS_<?php echo $regFormId; ?> ih" classHolder="sSS_<?php echo $regFormId; ?>" name="specializations[]" value="<?php echo $spec; ?>" checked="checked">
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>

		<?php if(!empty($unMapppedSpec)){ ?>
			<div class="unmppedSpec">
				<?php 
				foreach ($unMapppedSpec as $key => $specId) { ?>
					<input type="checkbox" id="spec_<?php echo $specId.'_'.$regFormId; ?>" class="unmp cLevel sSS_<?php echo $regFormId; ?> ih" name="specializations[]" value="<?php echo $specId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" checked="checked">
				<?php } ?>
			</div>
		<?php } ?>	
	</div>
<?php } ?>

<?php if(!empty($mappedBaseCourse)){ ?>
	<div class="ih mappedBaseCourse">
		<input type="checkbox" class="cLevel course_<?php echo $regFormId; ?>" id="baseCourse_<?php echo $mappedBaseCourse; ?>" value="<?php echo $mappedBaseCourse; ?>" name="baseCourses[]" checked="checked">
	</div>
<?php } ?>

<input type="hidden" name="listing_type" value="course" id="listing_type_<?php echo $regFormId; ?>">
