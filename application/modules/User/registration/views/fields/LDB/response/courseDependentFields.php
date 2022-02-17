<!-- Exam Field -->
<?php if(!empty($examList)){ ?>
	<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative" >
		<div class="reg-form signup-fld invalid tDisCnditnl" layerFor="exams" regfieldid="exams" id="exams_block_<?php echo $regFormId; ?>" mandatory="<?php if($isSpecMand == 'no'){ echo '0'; }else{ echo '1'; } ?>" caption="Choose Exams" type="layer" hasSearch='1' regformid='<?php echo $regFormId; ?>'>
			<div class="ngPlaceholder">Exams taken/planning to take <span class="optl">(Optional)</span></div>
			<div class="multiinput"></div>
			<div class="input-helper">
				<div class="up-arrow"></div>
				<div class="helper-text">Please Enter Exams.</div>
			</div>
		</div>

		<div class="crse-layer response-form layerHtml ih">
			<?php if(count($examList) > 7){ ?>
				<div class="cr-srDiv">
					<div class="srch-bx cSearchX">
						<i class="src-icn"></i>
						<input type="text" placeholder="Search Exam" class="cSearch"/>
						<i class="crss-icn ih">&times;</i>
					</div>
				</div>
			<?php } ?>
			<p class="deflt-placeholder-label">Select one or more</p> 
			<div class="lyr-sblst ctmScroll">
				<!-- <div class="scrollbar1">
					<div class="scrollbar thickSB" style="height: 145px;">
						<div class="track" style="height: 145px;">
								<div class="thumb" style="top: 0px; height: 2.57344px;"></div>
						</div>
					</div>
					<div class="viewport" style="height:145px;overflow:hidden;">-->
						<div id="examsField_<?php echo $regFormId; ?>"> 
							<ul>
								<div class="stdAlnLbl">
								</div>
								<div class="nav-cont">
									<ul class="lyr-sblst2 lyr-Nopd">
										<?php foreach ($examList as $examId => $examName) { ?>
											<li>
												<div class="Customcheckbox nav-checkBx ptb8">
													<input type="checkbox" class="cLevel exams_<?php echo $regFormId; ?>" classHolder="exams_<?php echo $regFormId; ?>" id="exams_<?php echo $examId; ?>" value="<?php echo $examName; ?>" <?php if(!empty($userDetails['exams']) && in_array($examName, $userDetails['exams'])){ echo 'checked';} ?> name="exams[]">
													<label for="exams_<?php echo $examId; ?>"><?php echo $examName; ?></label>
												</div>
											</li>
											<?php } ?>
											<li class="nsf"><span> No Results Found</span></li>
										</ul>
									</div>
							</ul>

						</div>
<!-- 					</div>
				
 -->			</div>
							<a href="javascript:void(0);" class="reg-btn btn-onlayr closeLayer">Done</a>
 
 </div>
			<div class="ih">
			</div>

		</div>
	</div>
<?php } ?>

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

<?php if(!empty($fields['baseCourses'])){ 
	$this->load->view('registration/fields/LDB/baseCourse');
} ?>

<input type="hidden" name="listing_type" value="course" id="listing_type_<?php echo $regFormId; ?>">
