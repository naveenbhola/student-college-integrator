<li class="clearwidth">
	<div class="tuple-box">
		<div class="tuple-image flLt">
			<?php
			 $imgUrl = $course['sa_course_university_photo']; 
			 if(empty($course['sa_course_university_photo'])){$imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg"; }
			?>
			<a href="<?php echo $course['sa_course_seo_url'];?>"  onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');" ><img src="<?php echo $imgUrl;?>" alt="<?php echo $course['sa_course_seo_url'];?>" align="center" width="172" height="115"/></a>
		</div>
		<div class="tuple-detail">
			<div class="tuple-title">
				<a href="<?php echo ($course['sa_course_univ_seo_url'])?>" onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');"><?php  echo $course['sa_course_uni_name']?></a><span class="font-11">, <?php echo $course['sa_course_city_name']?>, <?php echo $course['sa_course_country_name']?></span>
			 </div>
			 <div class="course-touple clearwidth">
				<p class="tuple-sub-title"><a href="<?php echo $course['sa_course_seo_url']?>" onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');"><?php echo $course['sa_course_name']?></a></p>
				<div class="clearwidth">
					<div class="uni-course-details flLt">
                                                <?php  if(!empty($course['sa_course_fees_value'])){ ?>
						<div class="detail-col flLt" style="width: 170px;">
							<strong>1st Year Total Fees</strong>
							<p> <?php echo str_replace("Lac","Lakh",$fees) ?></p>
                                                </div> <?php } ?>
                                    <div class="detail-col flLt" style="width: 130px;">
                                        <strong>Eligibility</strong>
					<?php	$examCount = count($course['sa_course_eligibility_exams']);
						for($counter=0; $counter < 2; $counter++){
                                                    if(empty($course['sa_course_eligibility_exams'][$counter])){
                                                            continue;
                                                    }
                                                    $score = explode(',', $course['sa_course_eligibility_exams'][$counter]);
					?>
					<p <?php if($score[1] == "N/A"){echo "onmouseover='showAcceptedMessage(this);' onmouseout='hideAcceptedMessage(this);'";} ?> style="position:relative;width:117px !important;">
								<?php if($score[1] == "N/A"){ ?>
									<?php $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$score[0])); ?>
								<?php } ?>
								<?php echo htmlentities($score[0])?>: <?php echo ($score[1]=="N/A")?"Accepted":$score[1]?>
							
					</p>
					<?php }?>
					<?php	if($examCount>=3){?>
						<a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
						<div class="extra-exam-div" style="display:none">
							<?php	
								for($counter=2; $counter < $examCount; $counter++){
                                                                    $score = explode(',', $course['sa_course_eligibility_exams'][$counter]);
							?>
							<p <?php if($score[1] == "N/A"){echo "onmouseover='showAcceptedMessage(this);' onmouseout='hideAcceptedMessage(this);'";} ?>  style="position:relative;width:117px !important;">
								<?php if($score[1] == "N/A"){ ?>
									<?php $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$score[0])); ?>
								<?php } ?>
								<?php echo htmlentities($score[0])?>: <?php echo ($score[1]=="N/A")?"Accepted":$score[1]?>
							
							</p>
							<?php }?>
						</div>
					<?php }?>
                                    </div>
						<div class="detail-col flLt" style="width:132px">
							<p><span class="tick-mark"><?php echo $publicclass?>;</span>Public university</p>
							<p><span class="tick-mark"><?php echo $scholarshipclass?>;</span>Scholarship</p>
							<p><span class="tick-mark"><?php echo $accomodationclass?>;</span>Accommodation</p>
						</div>
						<i class="cate-sprite detail-pointer"></i>
					</div>
                     <div class="btn-col">
						<?php
							$brochureDataObj = array(
							   'sourcePage'       => 'abroadSearch',
							   'courseId'         => $course['sa_course_id'],
							   'courseName'       => $course['sa_course_name'],
							   'universityId'     => $course['sa_course_uni_id'],
							   'universityName'   => $course['sa_course_uni_name'],
							   'widget'		=> 'search',
							   'userStartTimePrefWithExamsTaken'    => $userPref,
							   'trackingPageKeyId' => 33,
							   'consultantTrackingPageKeyId' => 386
							   );
						?>
                        <a href="Javascript:void(0);" class="button-style" onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');" onclick = "loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><i class="common-sprite dwnld-icon"></i><strong>Download E-Brochure</strong></a>
					</div>
					<div class="compare-box flLt customInputs" style="margin-top:6px;">
						<?php
							$checkedStatus = '';
							if(in_array($course['sa_course_id'],$userComparedCourses)){
								$checkedStatus = 'checked="checked"';
							}
						?>
						<input type="checkbox" name="compare<?=$course['sa_course_id']?>" id="compareSearch<?=$course['sa_course_id']?>" class="compareCheckbox<?=$course['sa_course_id']?>" <?=$checkedStatus?>>
						<label class="compareCheckboxLabel<?=$course['sa_course_id']?>" onclick="toggleCompare('<?=$course['sa_course_id']?>',552);">
							<span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
						</label>
					</div>
				</div>
				<!--
				<div class="compare-box flRt customInputs">
					<input type="checkbox" name="compare2" id="compare2">
					<label for="compare2">
						<span class="common-sprite"></span><p>Compare</p>
					</label>
				</div>
				-->
                                
                                
                                
                                <?php if($count > 0){ ?>
				<div class="flLt" style="width:83%">                                        
                                       <a href="javascript:void(0)" onclick="showHideSimilarCourse(this)" class="smlr-course-btn"><i class="cate-sprite plus-icon"></i><?php ?><?php echo $count; ?> similar <?php $courseLabel = ($count > 1) ? 'courses':'course';  echo $courseLabel; ?></a>
				</div>
                                <?php } ?>
			 </div>
		</div>
	</div>
	
    
         	<!-- start: for similar course -->
            <div class="similarCourseTuple"  style="display:none">
             <?php $flagTuple = 0;
                for($i=0;$i <count($similarCourses); $i++){
                        $courseTuple = $similarCourses[$i];
                        $subFees = $courseTuple['subfees'];
                        $courseDetails = $courseTuple['course'];
                        $subPublicClass = $courseTuple['publicclass'];
                        $subScholarshipClass = $courseTuple['scholarshipclass'];
                        $accomodationClass = $courseTuple['accomodationclass'];
             ?>
		<div class="tuple-detail">
			<div class="course-touple clearwidth">
				<p class="tuple-sub-title"><a href="<?php echo $courseDetails['sa_course_seo_url']?>" onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');"><?php echo $courseDetails['sa_course_name']?></a></p>
				<div class="clearwidth">
					<div class="uni-course-details flLt">
						<div class="detail-col flLt" style="width: 170px;">
							<p><strong>1st Year Total Fees</strong></p>
							<p><?php echo str_replace("Lac","Lakh",$subFees)?></p>
						</div>
                                    <div class="detail-col flLt" style="width:130px;">
                                        <strong>Eligibility</strong>
					<?php	$examCount = count($courseDetails['sa_course_eligibility_exams']);
						for($counter=0; $counter < 2; $counter++){
                                                        if(empty($courseDetails['sa_course_eligibility_exams'][$counter])){
                                                                continue;
                                                        }
                                                    $score = explode(',', $courseDetails['sa_course_eligibility_exams'][$counter]);
					?>
					<p <?php if($score[1] == "N/A"){echo "onmouseover='showAcceptedMessage(this);' onmouseout='hideAcceptedMessage(this);'";} ?> style="position:relative">
								<?php if($score[1] == "N/A"){ ?>
									<?php $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$score[0])); ?>
								<?php } ?>
								<?php echo htmlentities($score[0])?>: <?php echo ($score[1]=="N/A")?"Accepted":$score[1]?>
					</p>
					<?php }?>
					<?php	if($examCount>=3){?>
						<a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
						<div class="extra-exam-div" style="display: none">
							<?php	
								for($counter=2; $counter < $examCount; $counter++){
                                                                    $score = explode(',', $courseDetails['sa_course_eligibility_exams'][$counter]);
							?>
							<p <?php if($score[1] == "N/A"){echo "onmouseover='showAcceptedMessage(this);' onmouseout='hideAcceptedMessage(this);'";} ?> style="position:relative">
								<?php if($score[1] == "N/A"){ ?>
									<?php $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$score[0])); ?>
								<?php } ?>
								<?php echo htmlentities($score[0])?>: <?php echo ($score[1]=="N/A")?"Accepted":$score[1]?>
							</p>
							<?php }?>
						</div>
					<?php }?>
                                    </div>
						<div class="detail-col flLt" style="width:132px">
							<p><span class="tick-mark"><?php echo $subPublicClass?>;</span>Public university</p>
							<p><span class="tick-mark"><?php echo $subScholarshipClass?>;</span>Scholarship</p>
							<p><span class="tick-mark"><?php echo $accomodationClass?>;</span>Accommodation</p>
						</div>
						<i class="cate-sprite detail-pointer"></i>
				</div>
                                <div class="btn-col">
					<?php
					$brochureDataObj = array(
						   'sourcePage'       => 'abroadSearch',
						   'courseId'         => $courseDetails['sa_course_id'],
						   'courseName'       => $courseDetails['sa_course_name'],
						   'universityId'     => $courseDetails['sa_course_uni_id'],
						   'universityName'   => $courseDetails['sa_course_uni_name'],
						   'widget'		=> 'search',
                           'userStartTimePrefWithExamsTaken'    => $userPref,
                           'trackingPageKeyId' => 33
					       );
					?>				
                                    <a href="Javascript:void(0);" class="button-style" onmousedown = "searchTracking(<?php echo $tuplePostion?>,'course');" onclick = "loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><i class="common-sprite dwnld-icon"></i><strong>Download E-Brochure</strong></a>
                                </div>
					<div class="compare-box flLt customInputs" style="margin-top:6px;">
						<?php
							$checkedStatus = '';
							if(in_array($courseDetails['sa_course_id'],$userComparedCourses)){
								$checkedStatus = 'checked="checked"';
							}
						?>
						<input type="checkbox" name="compare<?=$courseDetails['sa_course_id']?>" id="compareSearch<?=$courseDetails['sa_course_id']?>" class="compareCheckbox<?=$courseDetails['sa_course_id']?>" <?=$checkedStatus?>>
						<label class="compareCheckboxLabel<?=$courseDetails['sa_course_id']?>" onclick="toggleCompare('<?=$courseDetails['sa_course_id']?>',552);">
							<span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
						</label>
					</div>
				</div>
			 </div>
                </div> <?php } ?>
	</div>
	<!-- end: for similar course -->
</li>

<script>
	function showExamDiv(obj){
		$j(obj).closest('.detail-col').find('.extra-exam-div').slideDown();
		$j(obj).hide();
	}
	
	function toggleCompare(courseId,source){
		addRemoveFromCompare(courseId,source);
	}
</script>
<!-- END : SEARCH COURSE TUPLE LISTING-->
