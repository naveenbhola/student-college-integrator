<?php ?>
<div id ="instTupleSec" class="univ-tab-details clearwidth">
    <h2 style="font-size:18px; margin-bottom:5px;">Recommended Colleges</h2>
    <div>	
		<ul class="tuple-cont" id = "alsoViewedRecoTuples">
		    <?php
			$action = "";
			$widget = "";
			$sourceForDownload = '';
			
			$trackingPageKeyId = 524;
			$shortlistTrackingPageKeyId = 526;
			$rmcRecoTrackingPageKeyId = 522 ;
			
			$action = "rmcSuccessPageRecommendation";
			$widget = "rmcSuccessPageRecommendation";
			$widgetForDownload = 'rmcSuccessPageRecommendation';
			$sourceForDownload = 'rmcSuccessPageReco';

			$algo = 'also_viewed';
			foreach($courseData as $courseId=>$courseObj){
			    $isCourseShotlistedByUser = in_array($courseId, $userShortlistedCourseIds['courseIds']) ? true : false;
		    ?>
		    <li class = "overLayAlsoViewedTuple clearwidth" >
			<div id ="<?=($widget)?>_tupleId_<?=$courseId?>" class="tuple-box <?=($widget)?>_tupleId_<?=$courseId?>">
			    <div class="tuple-image flLt">
				<a target="_blank" href="<?=$courseObj['courseURL']?>" onclick="processActivityTrack('<?=$courseId?>','<?=$requestedCourseId?>','<?=$courseObj['institute_id']?>','<?=$action.'Viewed'?>','<?=$widget?>','<?=$algo?>')"><img src="<?=$courseObj['universityImageURL']?>" alt="<?=$courseObj['universityName'].", ".$courseObj['universityLocation'];?>" title="<?=$courseObj['universityName']?>" align="center" /></a>
			    </div>
			    <div class="tuple-detail" >
				<div class="tuple-title">
				    <p><a target="_blank" href="<?=($courseObj['universityURL'])?>" onclick="processActivityTrack('<?=$courseId?>','<?=$requestedCourseId?>','<?=$courseObj['institute_id']?>','<?=$action.'Viewed'?>','<?=$widget?>','<?=$algo?>')"><?=htmlentities($courseObj['universityName'])?></a><span class="font-11">, <?=$courseObj['universityLocation']?></span></p>
				</div>
				<div class="course-touple clearwidth">
				    <p><a target="_blank" href="<?=$courseObj['courseURL']?>" class="tuple-sub-title" onclick="processActivityTrack('<?=$courseId?>','<?=$requestedCourseId?>','<?=$courseObj['institute_id']?>','<?=$action.'Viewed'?>','<?=$widget?>','<?=$algo?>')"><?=htmlentities($courseObj['courseName']);?></a></p>
				    <div class="clearwidth">
					<div class="uni-course-details flLt">
					    <div class="detail-col flLt" style="width:130px;">
						<strong>1st Year Total Fees</strong>
						<p><?=str_replace("Lac","Lakh",$courseObj['courseFees'])?></p>
					    </div>
					    <div class="detail-col flLt" style="width:80px;">
						<strong>Eligibility</strong>
						<p <?=(strpos($courseObj['courseExam'],'N/A')!=FALSE)?"onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":''?>>
							<?php if(strpos($courseObj['courseExam'],'N/A')!=FALSE){
								$comboString = $courseObj['courseExam'];
								$piece = strtok($comboString,':');	//This will take the first half of the exam string out (exam name)
								$this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$piece));
							}
							?>
							<?=str_replace("N/A","Accepted",$courseObj['courseExam'])?>
						</p>
					    </div>
					    <div class="detail-col flLt" style="width:125px">
						<?php if($courseObj['universityType'] == 'public'){?>
							<p><span class="tick-mark">&#10004;</span>Public university</p>
						<?php }else{?>
							<p class="non-available"><span class="cross-mark">&times;</span>Public university</p>
						<?php }?>
						<?php if($courseObj['universityScholarship']){?>
							<p><span class="tick-mark">&#10004;</span>Scholarship</p>
						<?php }else{?>
							<p class="non-available"><span class="cross-mark">&times;</span>Scholarship</p>
						<?php }?>
						<?php if($courseObj['universityHasCampus']){?>
							<p><span class="tick-mark">&#10004;</span>Accomodation</p>
						<?php }else{?>
							<p class="non-available"><span class="cross-mark">&times;</span>Accomodation</p>
						<?php }?>
					    </div>
					</div>
					<div class="reco-btn-col" style="">
					    <?php
					    $courseData = array($courseId => array('desiredCourse' => $courseObj['desiredCourse'],
										   'paid' => $courseObj['paid'],
										   'name' => $courseObj['courseName'],
										   'subcategory' => $courseObj['subcategory']
										   ));
					    $brochureDataObj = array(
						       'sourcePage'       		=> $sourceForDownload,
						       'courseId'         		=> $courseId,
						       'courseName'       		=> $courseObj['courseName'],
						       'universityId'     		=> $courseObj['universityId'],
						       'universityName'   		=> $courseObj['universityName'],
						       'trackingPageKeyId' 		=> $trackingPageKeyId,
						       'shortlistTrackingPageKeyId' => $shortlistTrackingPageKeyId,
						       'widget'					=> $widgetForDownload,
						       'courseData'				=> base64_encode(json_encode($courseData))
						   );
					    ?>
					    <a href="Javascript:void(0);" class="button-style" style="" onclick = "studyAbroadTrackEventByGA('ABROAD_ALSO_VIEWED_RECO_OVERLAY', 'DownloadBrochure'); loadStudyAbroadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');processActivityTrack('<?=$courseId?>','<?=$requestedCourseId?>','<?=$courseObj['institute_id']?>','<?=$action.'ReqEBrochure'?>','<?=$widget?>','<?=$algo?>');"><strong>Download Brochure</strong></a>
						<?php if($courseObj['showRmcButton'] == 1){ ?>
							<?php
								$brochureDataObj['pageTitle'] 			= $pageTitle;
								$brochureDataObj['userRmcCourses'] 		= $userRmcCourses;
								$brochureDataObj['trackingPageKeyId'] 	= $rmcRecoTrackingPageKeyId;
								echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
							?>
						<?php } ?>
						<div class="compare-box flLt customInputs" style="margin-top:6px;">
							<?php
								$checkedStatus = '';
								if(in_array($courseId,$userComparedCourses)){
									$checkedStatus = 'checked="checked"';
								}
							?>
							<input type="checkbox" name="compare<?=$courseId?>" id="compareRMCSuccess<?=$courseId?>" class="compareCheckbox<?=$courseId?>" <?=$checkedStatus?>>
							<label class="compareCheckboxLabel<?=$courseId?>" onclick="toggleCompare('<?=$courseId?>',553);">
								<span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
							</label>
						</div>
					</div>
				    </div>
				</div>
				    <i title="Click to ShortList" class="cate-sprite <?= $isCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" uniqueattr="ABROAD_CAT_PAGE/shortlistCourse"  onclick ="addRemoveFromShortlistedCourse('<?=$courseId?>','<?=($widget)?>','rmcSuccessPageRecommendation',<?php echo $shortlistTrackingPageKeyId; ?>);"></i>
			    </div>
			</div>
		    </li>
		    <?php }?>
		</ul>
    </div>
</div>

<script>
	function toggleCompare(courseId,source){
		addRemoveFromCompare(courseId,source);
	}
</script>
