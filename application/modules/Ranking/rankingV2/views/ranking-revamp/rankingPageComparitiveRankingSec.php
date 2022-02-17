<?php
    $tracking_keyid			= DESKTOP_NL_RNKINGPGE_TUPLE_DEB;
	$rankingPageDetails		= $rankingPage->getRankingPageData();
	$disclaimer				= $rankingPage->getDisclaimer();
    $courseRankingMapping	= array();
	$tableColumnCount		= count($rankingPageSources) + 5;
?>
<div class="ranking-table" id="top-ranking-table">
	<!-- use class to make sticky : header-fixed -->
	<table cellpadding="0" cellspacing="0" class="ranking-table-row">
		<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageComaritiveRankingTableHeader'); ?>
		<?php
			if(empty($rankingPageDetails)){
				echo "<tr><td colspan='".$tableColumnCount."'><p><i style='color:#FD8103;'>Sorry, no results were found matching your selection. Please alter your refinement options above.</i></p></td></tr>";
			}
			$count = 0;
			echo "<tbody>";
			foreach($rankingPageDetails as $key=>$pageData){
				$count++;
				$courseId=$pageData->getCourseId();
				$courseShortlistedStatus = 0;
				if(in_array($courseId, $shortlistedCoursesOfUser)) {
					$courseShortlistedStatus = 1;
				}
				$displayData['isRankingPage'] = 1;
				$displayData['instituteName'] = $pageData->getInstituteName();
				$addCurrentSelectedClass = "";
				$actionLinksSelectedStyle = "";
		?>
				<tr class="ranking-data-row <?=$pageData->isCoursePaid() ? 'ranking-paid-tuple-bg' : 'ranking-row-color'?>">
					<td class="col-2">
						<?php if($rankingPage->isUgTemplate()=='Yes') { ?>
							<a class="ranking-inst-title" href="<?php echo $pageData->getInstituteURL();?>">
								<?php echo $pageData->getInstituteName();?>
							<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Institute'] ?></p></span>	
							</a>
							<span class="ranking-city-title"><?php echo ($pageData->getCityName() != '') ? ' | '.$pageData->getCityName() : '';?></span>
						<?php }else {?>
							<a class="ranking-inst-title" href="<?php echo $pageData->getInstituteURL();?>"><?php echo $pageData->getInstituteName();?>
								<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Institute'] ?></p></span>	
							</a>
						<?php } ?>
						<span class="college-clr"><a class="course-name" href="<?php echo $pageData->getCourseURL();?>"><?php echo $pageData->getCourseName()?>
						<span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Course'] ?></p></span>
						</a>
						</span>	
					</td>
					<?php
						$courseId = $pageData->getCourseId();
						$sourceRanks = $pageData->getSourceRank();
					?>
					<?php foreach($rankingPageSourcePriority as $sourceId){ ?>
						<?php	
							$overAllParamId = $rankingPageSources[$sourceId]->getOverallParamId();
							$rank = $sourceRanks[$sourceId] ? $sourceRanks[$sourceId][$overAllParamId]['rank'] : '';
						?>
						<td class="rest-cols">
							<?php if($rank){ ?>
								<span rank="<?php echo $rank;?>" class="<?php $class = ""; ($rankingPageMainSourceId == $sourceId) ? $class ="rank-numb-shorlisted": $class = "rank-numb"; echo $class;?>"><?php echo $rank;?></span></td>
							<?php } else{ ?>
								N/A
							<?php } ?>
						</td>
					<?php } ?>
					<?php
						$id 			= $pageData->getId();
						$exams 			= $pageData->getExams();
						ksort($exams);
						$examString 	= "";
						$examMoreString = "";
						if(count($exams) > 0){
							$mainExams = array_slice($exams, 0, 2);
							if(count($mainExams) > 1){
								$examString .= "<li>";
								$examString .= $mainExams[0]['name'];
								if(!empty($mainExams[0]['marks'])){
									$examString .= ": ". $mainExams[0]['marks'];
								}
								$examString .= "</li>";
								$examString .= "<li>";
								$examString .= $mainExams[1]['name'];
								if(!empty($mainExams[1]['marks'])){
									$examString .=  ": ". $mainExams[1]['marks'];
								}
								$examString .= "</li>";
							} else {
								$examString .= "<li>";
								$examString .= $mainExams[0]['name'];
								if(!empty($mainExams[0]['marks'])){
									$examString .= ": ". $mainExams[0]['marks'];
								}
								$examString .= "</li>";
							}
							$extraExams = array();
							if(count($exams) > 2){
								$extraExams = array_slice($exams, 2);
							}
							$counter = 0;
							foreach($extraExams as $exam){
								$examMoreString .= "<li>";
								$examMoreString .= $exam['name'];
								if(!empty($exam['marks'])){
									$examMoreString .= ": ". $exam['marks'];
								}
								$examMoreString .= "</li>";
							}
							$examString = trim($examString);
							$examString = trim($examString, ",");
							$examMoreString = trim($examMoreString);
							$examMoreString = trim($examMoreString, ",");
						}
					?>
					<?php if($rankingPage->isUgTemplate()=='No') { ?>
						<td class="rest-cols">
							<ul class="cutoff-list">
								<?php echo $examString; ?>
								<?php if($examMoreString){ ?>
									<li><a href="javascript:void(0);" more='<?php echo $examMoreString;?>' id="more_exams_handle_<?php echo $id;?>">+ view more</a></li>
								<?php } ?>
							</ul>
						</td>
					<?php } ?>
					<?php
						$institute_id = $pageData->getInstituteId();
						$course_id = $pageData->getCourseId();
						$isNaukriSalaryDataAvailable = false;
						$instituteNaukriSalaryData = $pageData->getNaukriSalaryData();
						if($instituteNaukriSalaryData) {
							$salary = number_format($instituteNaukriSalaryData['ctc50'], 1, '.', '')." L";
							$isNaukriSalaryDataAvailable = true;
						}else{
							$salaryValue 	= $pageData->getAverageSalary();
							$salaryUnit 	= $pageData->getAverageSalaryUnit();
							if($salaryUnit != 'INR' && !empty($salaryUnit)){
								$salaryValue = $currencyConvertService->convert($salaryValue, $salaryUnit);
							}
							$salary = $salaryValue ? formatAmountToNationalFormat($salaryValue, 1) : "N/A";
						}
						if($rankingPage->isUgTemplate()=='No') {
					?>
							<td class="rest-cols"><?php echo (($isNaukriSalaryDataAvailable) ? "<a href='javascript:void(0);' instId={$institute_id} courseId={$course_id}>".$salary."</a>" : $salary);?></td>
					<?php } ?>
					<?php
						$feesValue = $pageData->getfeesValueForSort();
						$feesUnit  = $pageData->getfeesCurrencyForSort();
						if($feesValue!=''){
							$feesValue = formatAmountToNationalFormat($feesValue, 1);
						}else{
							$feesValue = "N/A";
						}
					?>
					<td class="rest-cols"><?php echo $feesValue;?></td>
					<td class="rest-cols rest-cols-last">
							<?php $getInstituteImage = $pageData->getMainHeaderImage(); ?>
							<div class="customInputs align-center">
								<!-- compare tool -->
								<p>
									<?php 
										$checked = '';
										if(is_array($alreadyComparedCourses) && !empty($alreadyComparedCourses[$pageData->getCourseId()])){
											$checked = "checked='checked'";
										}
									?>
									<input data-trackId='509' data-courseId='<?php echo $pageData->getCourseId();?>' type="checkbox" name="compare" style="margin-left:0;" <?php echo $checked; ?> id="compare<?=$pageData->getCourseId();?>" class="compare<?=$pageData->getCourseId();?>" onchange = "rankingCompareClick(event,this);"/>
									<label for="compare<?=$pageData->getCourseId();?>">
										<span class="common-sprite text-align-checkbox"></span>
										<span>Compare</span>
									</label>
									<a style="display:none;" href="javascript:void(0);" id="compare<?=$pageData->getCourseId();?>lable" class="compare<?=$pageData->getCourseId();?>lable">Add to Compare</a>
								</p>
								<div style="display:none">
									<input type="hidden" name="compare<?=$pageData->getCourseId();?>list[]"  value="<?=$pageData->getCourseId();?>"/>
								</div>
								<!-- compare tool end -->
							</div>
					</td>
				</tr>
				<tr class="actionLinks extra-row"  <?=$actionLinksSelectedStyle ?>>
					<td style="padding:0 5px 10px 5px" width="100%" colspan="<?php echo $tableColumnCount;?>" class="<?=$pageData->isCoursePaid() ? 'ranking-paid-tuple-bg' : ''?>">
						<div class="flLt">
							<?php if(isset($reviewRatingData[$pageData->getCourseId()]) && $reviewRatingData[$pageData->getCourseId()]>0) {?> 
								<div style="margin-bottom:6px; float: left">
									<div class="flLt avg-rating-title">Alumni Rating: </div>
									<div class="ranking-bg"><?=$reviewRatingData[$pageData->getCourseId()]?><sub>/5</sub></div>
								</div>
							<?php }?>
							<div class="clearfix"></div>
								<?php
									$photosCount = $pageData->getPhotoCount() + $pageData->getVideoCount();
									if($photosCount > 0){ 
										$this->load->helper('image');
										if($pageData->getMainHeaderImage() && $pageData->getMainHeaderImage()->getUrl() && $pageData->isCoursePaid()){
								?>
											<a href="<?php echo $pageData->getCourseURL(); ?>#gallery">
												<?php $headerImageURL = getImageVariant($pageData->getMainHeaderImage()->getUrl(),1); ?>
												<img class='lazy' style="height:63px;width:76px;" data-original="<?php echo addingDomainNameToUrl(array('url'=>$headerImageURL,'domainName'=>MEDIA_SERVER)); ?>" src="<?php echo IMGURL_SECURE; ?>/public/images/avatar.gif" alt="<?php echo html_escape($pageData->getInstituteName()); ?>" title="<?php echo html_escape($pageData->getInstituteName()); ?>">
												<noscript>
													<img src="<?php echo getImageVariant($pageData->getMainHeaderImage(),1); ?>">
												</noscript>
											</a>
								<?php
										}else if($pageData->isCoursePaid()){
								?>
											<a href="<?php echo $pageData->getCourseURL(); ?>#gallery"><img style="height:63px;width:76px;" src="<?php echo IMGURL_SECURE; ?>/public/images/avatar.gif" alt="<?php echo html_escape($pageData->getInstituteName()); ?>" title="<?php echo html_escape($pageData->getInstituteName()); ?>"></a>
								<?php
										}
										if($pageData->isCoursePaid()){
								?>
										<a href="<?=$pageData->getCourseURL()?>#gallery" class="ranking-photo-link"><?=$photosCount?> Photo<?=($photosCount > 1)? 's' : ''?> available</a>
								<?php
										}
									}
								?>
							</div>
							<?php 
								$brochurePostitionStyle = '';
								if($rankingPage->isUgTemplate()=='Yes') {
									$brochurePostitionStyle = 'right:48px;';
								}
							?>

								<div class="flRt user-pref-btns" style="<?=$brochurePostitionStyle;?> position: relative;<?=$pageData->isCoursePaid() ? 'margin-top: 70px;' : ''?>">
									<?php if($coursesWithOnlineForm[$pageData->getCourseId()]) { ?>			
										<?php 
											if($coursesWithOnlineForm[$pageData->getCourseId()]['of_external_url'] != '') { 
										?>                                                              
												<a href="javascript:void(0);" class="alumini-btn" label='Apply_Online_External' url='<?php echo $coursesWithOnlineForm[$pageData->getCourseId()]['of_external_url']."?tracking_keyid=".$applyOnlinetrackingPageKeyId;?>'><i class="ranking-sprite ranking-applyNow-icon"></i>Apply Online</a>
										<?php 
											} else if($coursesWithOnlineForm[$pageData->getCourseId()]['seo_url'] != ''){ 
										?>
												<a href="javascript:void(0);" class="alumini-btn" label='Apply_Online' url='<?php echo $coursesWithOnlineForm[$pageData->getCourseId()]['of_seo_url']."?tracking_keyid=".$applyOnlinetrackingPageKeyId;?>'><i class="ranking-sprite ranking-applyNow-icon"></i>Apply Online</a>
										<?php 
											} 
										?>
									<?php } ?>
									<div id="institute<?=$pageData->getInstituteId()?>name" style="display:none">
										<?php echo html_escape($pageData->getInstituteName());?>
									</div>
									<select id="applynow<?php echo $pageData->getInstituteId();?>" style="display:none;">
										<option title="<?php echo trim($pageData->getCourseName());?>" value="<?php echo trim($pageData->getCourseId());?>">
											<?php echo $pageData->getCourseName();?>
										</option>
									</select>
									<a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>"  class="alumini-btn" hidefocus="hidefocus" trackId='<?php echo $tracking_keyid;?>' instId='<?php echo $pageData->getInstituteId();?>' courseId='<?php echo $pageData->getCourseId(); ?>' courseName ='<?php echo $pageData->getCourseName(); ?>'>
										<i class="ranking-sprite brochure-icon"></i>
										Get Brochure
									</a>
									<a style="padding:8px 12px 9px; margin-left:10px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;" href="javascript:void(0);" trackId='<?=DESKTOP_NL_RNKINGPGE_TUPLE_SHORTLIST?>' courseId='<?php echo $courseId;?>'  class="shrtlist-btn <?="shrt".$courseId?> <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' :'' ?> ">
										<i class="common-sprite shrtlist-star-icon"></i>
										<span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span>
									</a>
									<br/> 
									<a target="_blank" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="<?="shrt-view-link".$courseId?>" style="font-size:12px; margin:8px 0 0 0; display:<?=$courseShortlistedStatus == 1 ? 'block' : 'none' ?>; float:right">View my shortlist</a>
								</div>
						</div>
					</td>
				</tr>
				<?php
					if($count == 4 && $rankingPageOf['FullTimeMba']==1){
						$this->load->view('common/IIMCallPredictorBanner',array('productType'=>'rankingPage'));
					}
					if($count == 10 && $rankingPageOf['FullTimeMba']==1){
						$this->load->view('/RecentActivities/InlineWidget');
					}
					if($count == 6 && $rankingPageOf['FullTimeEngineering']==1 && false){
				?>
						<tr class="actionLinks">
							<td style="padding:5px 5px 10px 5px;" width="100%" colspan="7" class="">
								<p style='font-size:18px; margin:10px 0 12px;'>Engineering College Reviews</p>
								<?php
									$bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MIDDLE');
					 				$this->load->view('common/banner',$bannerProperties1);
					 			?>
 							</td>
						</tr>
 				<?php
					}
				?>
		<?php
			}	// End foreach
			echo "</tbody>";
		?>
	</table>
	<div class="rank-tooltip">
		Click to see colleges with same rank<span class="caret"></span>
	</div>
	<div class="disclaimer clear-width" id="comprativeTableEnd">
		<?php if($disclaimer) { ?>
			<strong class="disclaimer-heading">Disclaimer & source information:</strong> 
			<span class="disclaimer-text">
				<?php echo htmlentities(strip_tags($disclaimer));?>
			</span>
		<?php } ?>
	</div>
	<div class="avgRating-tooltip" style="display: none;width: 286px;">
		<i class="common-sprite avg-tip-pointer" style="left:102px"></i>
		<p>Rating is based on actual reviews of students who studied at this college</p>
	</div>
	<input type='hidden' id='current_page_url' value="<?php echo $current_page_url;?>" />
	<script>
		compareDiv = 1;
	</script>
</div>