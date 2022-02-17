<?php
    //_p($courseData);die();
    if(!empty($courseData)){
	$viewPortHeight = 'height : 400px';
	switch(count($courseData)){
	    case 1 : $viewPortHeight = 'height : 172px';
			break;
	    case 2 : $viewPortHeight = 'height : 340px';
			break;
	    default : $viewPortHeight = 'height : 400px';
			break;
	}
?>

<div id ="instTupleSec" class="univ-tab-details clearwidth">
    <h2>Students who showed interest in this institute also looked at</h2>
    <div id = "alsoViewedReco" class="scrollbar1 clearwidth">
	
	<div class="scrollbar" style="/*visibility:hidden; */left: 10px;">
	    <div class="track">
		<div class="thumb"></div>
	    </div>
	</div>
	<div class="viewport" style="<?=$viewPortHeight?>;">
	    <div class="overview" style="width:100%;">    
		<ul class="tuple-cont" id = "alsoViewedRecoTuples">
		    <?php
			$action = "";
			$widget = "";
			$sourceForDownload = '';

			switch ($sourcePage) {
				case 'response_abroad_rmcSuccessPageRecommendation':
					$trackingPageKeyId = 542;
					$consultantTrackingPageKeyId = 545;
					$shortlistTrackingPageKeyId = 543;
					$rmcRecoTrackingPageKeyId = 544;
					break;
				case 'response_abroad_course_belly_link':
				case 'response_abroad_alsoViewed':
				case 'response_abroad_course_download_form_bottom':
				case 'response_abroad_course_applicationProcessTab_request_callback':
					$trackingPageKeyId = 37;
					$consultantTrackingPageKeyId = 381;	
					$shortlistTrackingPageKeyId = 423;
					$rmcRecoTrackingPageKeyId = 438;
					break;

				case 'response_abroad_category_page':
					$trackingPageKeyId = 15;
					$consultantTrackingPageKeyId = 370;	
					$shortlistTrackingPageKeyId = 424;
					$rmcRecoTrackingPageKeyId = 437;
					break;

				case 'response_abroad_department_email_popout':
				case 'response_abroad_department_download_form_bottom':
					$trackingPageKeyId = 46;
					$consultantTrackingPageKeyId = 399;
					$shortlistTrackingPageKeyId = 426;
					$rmcRecoTrackingPageKeyId = 435;	
					break;

				case 'response_abroad_university_email_popout':
				case 'response_abroad_university_download_form_bottom':
					$trackingPageKeyId = 3;
					$consultantTrackingPageKeyId = 390;
					$shortlistTrackingPageKeyId = 425;
					$rmcRecoTrackingPageKeyId = 436;
					break;

				case 'response_abroad_savedCoursesTab_RMCTab':
					$trackingPageKeyId = 393;
					$consultantTrackingPageKeyId = 404;
					$shortlistTrackingPageKeyId = 422;
					$rmcRecoTrackingPageKeyId = 441;
					break;

				case 'response_abroad_savedCoursesTab_shortlistTab':
					$trackingPageKeyId = 416;
					$consultantTrackingPageKeyId = 419;
					$shortlistTrackingPageKeyId = 421;
					$rmcRecoTrackingPageKeyId = 442;
					break;

				case 'response_abroad_savedCoursesPage_RMCTab':
					$trackingPageKeyId = 22;
					$consultantTrackingPageKeyId = 402;
					$shortlistTrackingPageKeyId = 414;
					$rmcRecoTrackingPageKeyId = 443;
					break;

				case 'response_abroad_savedCoursesPage_shortlistTab':
					$trackingPageKeyId = 410;
					$consultantTrackingPageKeyId = 408;
					$shortlistTrackingPageKeyId = 413;
					$rmcRecoTrackingPageKeyId = 444;
					break;

				case 'response_abroad_universityRankingPage':
					$trackingPageKeyId = 27;
					$consultantTrackingPageKeyId = 400;
					$shortlistTrackingPageKeyId = '';
					$rmcRecoTrackingPageKeyId = 439;
					break;

				case 'response_abroad_courseRankingPage':
					$trackingPageKeyId = 26;
					$consultantTrackingPageKeyId = 382;
					$shortlistTrackingPageKeyId = '';
					$rmcRecoTrackingPageKeyId = 440;
					break;

				case 'response_abroad_search':
					$trackingPageKeyId = 34;	
					$consultantTrackingPageKeyId = 385;
					$shortlistTrackingPageKeyId = 427;
					$rmcRecoTrackingPageKeyId = 434;
					break;

				
			}

			if($sourcePage == 'response_abroad_search'){
			    $action = "CP_Reco_";
			    $widget = "CP_";
			    $widgetForDownload = 'search_overlay_also_viewed';
			    $sourceForDownload = 'abroadSearch';
			}elseif(in_array($sourcePage, array("response_abroad_category_page","response_abroad_category_page_shortList_tab","response_abroad_overlay_also_viewed_CP"))){
			    $action = "CP_Reco_";
			    $widget = "CP_";
			    $widgetForDownload = 'overlay_also_viewed_CP';
			    $sourceForDownload = 'category';
			}elseif(in_array($sourcePage, array("response_abroad_shortlistPage","response_abroad_overlay_also_viewed_shortlist_page"))){
			    $action = "Shortlist_Page_Reco_";
			    $widget = "Shortlist_Page_";
			    $widgetForDownload = 'overlay_also_viewed_shortlist_page';
			    $sourceForDownload = 'shortlist_page';
			}elseif(in_array($sourcePage, array("response_abroad_course_email_popout",
							    "response_abroad_department_email_popout",
							    "response_abroad_university_email_popout",
							    "response_abroad_course_belly_link",
							    "response_abroad_university_belly_link",
							    "response_abroad_course_download_form_bottom",
							    "response_abroad_department_download_form_bottom",
							    "response_abroad_university_download_form_bottom",
							    "response_abroad_similarInstitutes",
							    "response_abroad_alsoViewed",
							    "response_abroad_overlay_also_viewed_LP"))){
			    $action = "LP_Reco_";
			    $widget = "LP_";
			    $widgetForDownload = 'overlay_also_viewed_LP';
			    $sourceForDownload = 'recommendation'; //course
			}elseif(in_array($sourcePage, array("response_abroad_universityRankingPage","response_abroad_courseRankingPage","response_abroad_overlay_also_viewed_RP"))){
			    $action = "RP_Reco_";
			    $widget = "RP_";
			    $widgetForDownload = 'overlay_also_viewed_RP';
			    if($sourcePage == "response_abroad_universityRankingPage"){
				$sourceForDownload = 'university_rankingpage_abroad';
			    }elseif($sourcePage == "response_abroad_courseRankingPage"){
				$sourceForDownload = 'course_rankingpage_abroad';
			    }else{
				$sourceForDownload = 'recommendation'; // default it would always be course recommendations
			    }
			}elseif(in_array( $sourcePage ,array("response_abroad_savedCoursesPage_RMCTab", "response_abroad_overlay_also_viewed_savedCoursesPage_RMCTab"))){
				
				$action = "SavedCoursesPage_Reco_";
			    $widget = "SavedCoursesPage_";
			    $widgetForDownload = 'overlay_also_viewed_savedCoursesPage_RMCTab';
			    $sourceForDownload = 'recommendation'; 
			}elseif (in_array($sourcePage, array("response_abroad_savedCoursesTab_shortlistTab","response_abroad_overlay_also_viewed_savedCoursesTab_shortlistTab"))) {
				$action = "savedCoursesTab_Reco_";
			    $widget = "savedCoursesTab_";
			    $widgetForDownload = 'overlay_also_viewed_savedCoursesTab_shortlistTab';
			    $sourceForDownload = 'recommendation'; 
			}elseif (in_array($sourcePage, array("response_abroad_savedCoursesTab_RMCTab","response_abroad_overlay_also_viewed_savedCoursesTab_RMCTab"))) {
				$action = "savedCoursesTab_Reco_";
			    $widget = "savedCoursesTab_";
			    $widgetForDownload = 'overlay_also_viewed_savedCoursesTab_RMCTab';
			    $sourceForDownload = 'recommendation'; 
			}elseif(in_array( $sourcePage ,array("response_abroad_savedCoursesPage_shortlistTab", "response_abroad_overlay_also_viewed_savedCoursesPage_shortlistTab"))){
				
				$action = "SavedCoursesPage_Reco_";
			    $widget = "SavedCoursesPage_";
			    $widgetForDownload = 'overlay_also_viewed_savedCoursesPage_shortlistTab';
			    $sourceForDownload = 'recommendation'; 
			}else if($sourcePage == 'response_abroad_rmcSuccessPageRecommendation')
			{
				$action = "rmcSuccessPageRecommendation";
				$widget = "rmcSuccessPageRecommendation";
				$widgetForDownload = 'rmcSuccessPageRecommendation';
				$sourceForDownload = 'rmcSuccessPageReco';
			}
			else{
			    $action = "misc_";
			    $widget = "misc_";
			    $widgetForDownload = 'misc';
			    $sourceForDownload = 'misc'; //course
			}
			$widget .= 'Reco_popupLayer_SA';
			$algo = 'also_viewed';
			foreach($courseData as $courseId=>$courseObj){
			    $isCourseShotlistedByUser = in_array($courseId, $userShortlistedCourseIds['courseIds']) ? true : false;
		    ?>
		    <li class = "overLayAlsoViewedTuple" >
			<div id ="<?=($sourcePage == 'response_abroad_rmcSuccessPageRecommendation'?'rmcSuccessPageRecommendation':'categoryPageListing')?>_tupleId_<?=$courseId?>" class="tuple-box categoryPageListing_tupleId_<?=$courseId?>">
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
						       'sourcePage'       => $sourceForDownload,
						       'courseId'         => $courseId,
						       'courseName'       => $courseObj['courseName'],
						       'universityId'     => $courseObj['universityId'],
						       'universityName'   => $courseObj['universityName'],
						       'trackingPageKeyId' => $trackingPageKeyId,
						       'consultantTrackingPageKeyId' => $consultantTrackingPageKeyId,
						       'shortlistTrackingPageKeyId' => $shortlistTrackingPageKeyId,
						       'rmcRecoTrackingPageKeyId' => $rmcRecoTrackingPageKeyId,
						       'widget'		=> $widgetForDownload,
						       'courseData'	=> base64_encode(json_encode($courseData))
						   );
					    ?>
					    <a href="Javascript:void(0);" class="button-style" style="" onclick = "studyAbroadTrackEventByGA('ABROAD_ALSO_VIEWED_RECO_OVERLAY', 'DownloadBrochure'); loadStudyAbroadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');processActivityTrack('<?=$courseId?>','<?=$requestedCourseId?>','<?=$courseObj['institute_id']?>','<?=$action.'ReqEBrochure'?>','<?=$widget?>','<?=$algo?>');"><strong>Download Brochure</strong></a>
						<?php if($courseObj['showRmcButton'] == 1){ ?>
							<?php
								$brochureDataObj['pageTitle'] = $pageTitle;
								$brochureDataObj['userRmcCourses'] = $userRmcCourses;
								$brochureDataObj['trackingPageKeyId'] = $rmcRecoTrackingPageKeyId;
								echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
							?>
						<?php } ?>
					</div>
				    </div>
				</div>
				<?php if(strpos($widget,'CP_') === 0 || strpos($widget,'Shortlist_Page_Reco') === 0 || strpos($widget,'SavedCoursesPage_') === 0 || strpos($widget, 'savedCoursesTab_')===0) {?>
				    <i title="Click to ShortList" class="cate-sprite <?= $isCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" uniqueattr="ABROAD_CAT_PAGE/shortlistCourse"  onclick ="addRemoveFromShortlistedTab('<?=$courseId?>','categoryPageListing',this,'overlay',<?php echo $shortlistTrackingPageKeyId; ?>);"></i>
				<?php }elseif(strpos($widget,'RP_') === 0){?>
					<i title="Click to ShortList"></i>
				<?php }else{?>
				    <i title="Click to ShortList" class="cate-sprite <?= $isCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" uniqueattr="ABROAD_CAT_PAGE/shortlistCourse"  onclick ="addRemoveFromShortlistedCourse('<?=$courseId?>','<?=($sourcePage == 'response_abroad_rmcSuccessPageRecommendation'?'rmcSuccessPageRecommendation':'categoryPageListing')?>','<?=($sourcePage == 'response_abroad_rmcSuccessPageRecommendation'?'rmcSuccessPageRecommendation':'overlay')?>',<?php echo $shortlistTrackingPageKeyId; ?>);"></i>
				<?php }?>
				
			    </div>
			</div>
		    </li>
		    <?php }?>
		</ul>
	    </div>
	</div>
    </div>
</div>
<script>
    $j('#alsoViewedReco').tinyscrollbar();
</script>
<style>
	.reco-btn-col .btn-rate-change .tooltip-info {width:220px !important;left:-30px !important;top:-100px !important;}
	.reco-btn-col .btn-rate-change .tooltip-info .verified-up-pointer {background-position: -239px -31px; height: 9px; left: 66px; position: absolute; top: 91px !important;width: 9px;}
	
	@media all and (-ms-high-contrast:none){
	.reco-btn-col .btn-rate-change .tooltip-info {top:-86px !important;}
	.reco-btn-col .btn-rate-change .tooltip-info .verified-up-pointer {top:75px !important;}
    }
</style>


<?php }?>
