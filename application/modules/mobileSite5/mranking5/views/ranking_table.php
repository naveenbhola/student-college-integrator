<?php

global $shiksha_site_current_url;
global $shiksha_site_current_refferal ;
$result_type = "main";
if(!empty($resultType)){
	$result_type = $resultType;
}

$examFilters = $filters['exam'];
$specializationFilters = $filters['specialization'];
$totalSpecializationFilters = count($specializationFilters);
$defaultSpecializationSelected = true;

$totalExamFilters = count($examFilters); 
$defaultExamSelected = false;
foreach($examFilters as $filter){
	if($filter->isSelected() == true){
		$defaultExamSelected = true;
	}
}

$showSpecializationFilters = true;
if($totalSpecializationFilters <= 1 && $defaultSpecializationSelected){
	$showSpecializationFilters = false;
}

$rankingPageData = $rankingPage->getRankingPageData();
$showFeesColoumn = false;
$showExamFilters = false;
foreach($rankingPageData as $pageRow){
	if(count($pageRow->getExams()) > 0){
		$showExamFilters = true;
	}
	if($pageRow->getfeesValueForSort() > 0){
		$showFeesColoumn = true;
	}
	if($showExamFilters && $showFeesColoumn){
		break;
	}
}
$showExamColoumn = $showExamFilters;
$enableSortFunctionality = false;

$sortKey 		=  "rank";
$sortKeyValue 	=  "";
$sortOrder 		=  "desc";
if(!empty($sorter) && count($rankingPageData) > 1){
	$enableSortFunctionality = true;
	$sortKey 		=  $sorter->getKey();
	$sortKeyValue 	=  $sorter->getKeyValue();
	$sortOrder 		=  $sorter->getOrder();
}

$examId = "";
if($result_type == "main"){
	$examId = $rankingPageRequest->getExamId();
} else if($result_type == "exam"){
	if(!empty($main_request_object)){
		$examId = $main_request_object->getExamId();	
	} else {
		$examId = $rankingPageRequest->getExamId();
	}
} 

if(empty($rankingPageData) && $result_type == "main" && $isAjax!='true') { ?>
	<section class="content-wrap2 clearfix" >			
		<article class="req-bro-box clearfix" style="cursor: pointer;" >
			<div class="confirmation-msg">
				Sorry, no results were found matching your selection. Please alter your refinement options above.
			</div>
		</article>
	</section>
	<?php
	$catPageWidgetCount = 1;
	$data = array();
	$data['count'] = 1;
	$data['catPageWidgetCounter'] = $catPageWidgetCount;
	$this->load->view('/mranking5/rankingFindBestCollegeWidget', $data);
} else {
	/*if(isset($_COOKIE['MOB_A_C'])) {
		$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
	}
	*/
	if(isset($_COOKIE['applied_courses'])){
		$appliedCourseArr = json_decode(base64_decode($_COOKIE['applied_courses']));	
	}
	$count = 1;
	$catPageWidgetCount = 0;
	foreach($rankingPageData as $pageData){
		
		if($count > 10  && $isAjax !='true') {
			break;
		}
		if($count < 11  && $isAjax == 'true') {
			$count++;
			continue;
		}
		
		if($count == 5 && ($rankingPageOf['Engineering']==1)) {
			$bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MOBILE');
			$this->load->view('common/banner',$bannerProperties1);
		}


		?>
		<section class="content-wrap2 clearfix">
			<article class="ranking-box">
				<div class="details" style="cursor: pointer;">
				    <a href="<?php echo $pageData->getCourseURL(); ?>">
                                    <div class="flLt" style="width:72%">
                                        <h4>
                                            <p>
                                                <?php echo $pageData->getInstituteName();?>,
                                                <?php
                                                $instituteCityName = $pageData->getCityName();
                                                if(!empty($instituteCityName)){
                                                ?><span><?php echo $instituteCityName;?></span>
                                            </p>
                                        
                                        </h4>
					<?php
					} else { ?>
					</h4>
					<?php
					}
					//$id = $pageData->getId();
					$examList = $pageData->getExams();
					/*usort($examList, 'sortExamsInUI');
					$validExams = array();
					if(!empty($examId)){
						foreach($examList as $exam){
							if($exam['id'] == $examId){
								$validExams[] = $exam;
								break;
							}
						}
					}
					if(!empty($validExams)){
						$examList = $validExams;
					}*/
					$exams = array();
					foreach($examList as $exam){
						if(!empty($exam['marks'])){
							$exams[] = $exam['name'] . "(".$exam['marks'].")";
						} else {
							$exams[] = $exam['name'];
						}
					}
					if(!empty($exams)){
					?>
					<div class="cutoff-item">
						<label>Cutoff:</label>
						<p><?php echo implode(", ", $exams);?></p>
					</div>
					<?php
					} 
					if(isset($reviewRatingData[$pageData->getCourseId()]) && $reviewRatingData[$pageData->getCourseId()]>0) {?> 
							<div style="margin:6px 0; float: left">
							<div class="flLt avg-rating-title">Alumni Rating: </div>
							<div onmouseover="showReviewsToolTip(this);" onmouseout="hideReviewsToolTip();" class="ranking-bg"><strong><?php if(strpos($reviewRatingData[$pageData->getCourseId()],'.')){echo $reviewRatingData[$pageData->getCourseId()];}else {echo $reviewRatingData[$pageData->getCourseId()].'.0'; }?></strong><sub>/5</sub></div>
							</div>
							<?php } ?>
                                        </div>
                                        <!----shortlist-course---->
                                        <div class="flRt">
                                            <?php
                                            $data['courseId'] = $pageData->getCourseId();
                                            $data['pageType'] = 'mobileRankingPage';
											if( isset($tracking_keyid) ){ // If the tracking key has been set in the previous view, pass it ahead
												$data['tracking_keyid'] = $tracking_keyid_shortilst;
											}
                                            $this->load->view('/mcommon5/mobileShortlistStar',$data);
                                            ?>
                                        </div>

					<div class="clearfix"></div>

                                        <!----end shortlist-course---->
					<?php if(!empty($rankingPageSources)) {
					?>
						<ul class="rank-position">
							<?php
							$sourcesRank = $pageData->getSourceRank();
							foreach($rankingPageSources as $sourceObject){
								$sourceId = $sourceObject->getId();
								$overallParamId = $sourceObject->getOverallParamId();
								$sourceName = $sourceObject->getName();
								$overallRank = '';
								if(array_key_exists($sourceId, $sourcesRank)){
									if(array_key_exists($overallParamId, $sourcesRank[$sourceId])){
										$overallRank = $sourcesRank[$sourceId][$overallParamId]['rank'];
									}
								}
								if($overallRank == '') {
									$overallRank = 'NA';
								}
								$className = "";
								if($main_source_id == $sourceId){
									$className = "comp-active";
								}
								?>
								<li>
									<span class="rank-num <?php echo $className;?>"><?php echo $overallRank;?></span>
									<p><?php echo $sourceName;?></p>
								</li>
								<?php
							}
							?>
						</ul>
					<?php
					}
					?>

                                    </a>

				</div>
				<div class="rank-footer">
                                    <!-- Request E-Brochure begins -->
                                    <?php
                                            $pageName = 'RANKING_PAGE';
                                            $from_where = 'MOBILE5_RANKING_PAGE';
                                    ?>

                                    
                                        <?php if(in_array($pageData->getCourseId(),$appliedCourseArr)){?>
	                                        <a href="javascript:void(0);" id="request_e_brochure<?=$pageData->getCourseId();?>" class="disabled" style="border-right:1px solid #e1e1e1;width:51%;">
	                                            <i class="sprite compare-bro-icn"></i>
	                                            <span>BROCHURE MAILED</span>
	                                        </a>
                                        <?php }else{ ?>
	                                        <a href="javascript:void(0);" id="request_e_brochure<?=$pageData->getCourseId();?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" style="border-right:1px solid #e1e1e1;width:51%;" onClick="trackReqEbrochureClick('<?php echo $pageData->getCourseId();?>'); window.L_tracking_keyid ='<?=MOBILE_NL_RNKINGPGE_TUPLE_DEB?>'; mobileRankingDownloadBrochure('<?php echo $pageData->getCourseId();?>','<?=$tracking_keyid?>',this);">
	                                            <i class="sprite compare-bro-icn"></i>
	                                            <span>REQUEST BROCHURE</span>
	                                        </a>
                                        <?php } ?>

                                    <!-- Request E-Brochure Ends -->
                                    
                                    <?php
                                            $data['instituteId'] = $pageData->getInstituteId();
                                            $data['courseId']    = $pageData->getCourseId();
                                            $data['isPaid']      = $pageData->isCoursePaid();
                                            $data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
                                            $this->load->view('/mranking5/mobileRankingCompare',$data);
                                    ?>
				</div>
                                <!--- Removing this thankYou msg in shiksha2.0
                                <div id= "thanksMsg<?=$pageData->getCourseId()?>" class="thnx-msg" <?php if(!in_array($pageData->getCourseId(),$appliedCourseArr)){?>style="display:none;"<?php } else { ?> style="margin:10px !important" <?php } ?>>
                                            <i class="icon-tick"></i>
                                            <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
                                </div>
                                -->
				<div class="clearfix"></div>
			</article>
		</section>
		<div id="recomendations_<?php echo $pageData->getCourseId();?>" style="display:none;background:#fff;"></div>
         <?php
				if($count == 8 && $isAjax !='true'){
					if($rankingPageOf['FullTimeMba']==1){
						?>
		                <div id="mbaToolsWidget" data-enhance="false">
		                	<div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
		                    <?php
		                        echo $collegeReviewWidget;  
		                    ?>
		                </div>
		                <?php
					}
				}
			?>		
		<?php
		if($count % 10 == 0 && $count != 10 || count($rankingPageData) == $count){
			$catPageWidgetCount++;
			$data = array();
			$data['count'] = $count;
			$data['catPageWidgetCounter'] = $catPageWidgetCount;
			$this->load->view('/mranking5/rankingFindBestCollegeWidget', $data);
		}
		
		if($count == 4 && $rankingPageOf['FullTimeMba'] == 1 && IIM_CALL_INTERLINKING_FLAG == 'true' && $isAjax != 'true')
		{?>
			<section class="content-wrap2 clearfix">
			<?php
					$fromPage = 'rankingPage';
					echo Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget',$fromPage);
			?>
			</section>
	<?php }
		$count++;
	}
}
?>
<div class="avgRating-tooltip" style="display: none;width: 286px;">
<i class="common-sprite avg-tip-pointer" style="left:102px"></i>
<p>Rating is based on actual reviews of students who studied at this college</p>
</div>
<script>
compareDiv = 1;
function showReviewsToolTip(thisObj) {
	
	$j('.avgRating-tooltip').css(
		
		{'top':$j(thisObj).offset().top+27+'px',
		'left': $j(thisObj).offset().left-95+'px'}
		);
		$j('.avgRating-tooltip').show(); 
}

function hideReviewsToolTip() {
	$j('.avgRating-tooltip').hide();
	}

</script>
<script>
function trackReqEbrochureClick(courseId){
try {
    _gaq.push(['_trackEvent', 'HTML5_Ranking_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}
</script>
