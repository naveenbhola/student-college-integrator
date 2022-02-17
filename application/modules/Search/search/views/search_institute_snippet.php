<?php
$courseKeys = array_keys($institute);
$courseId = $courseKeys[0];
$remainingCourseKeys = array_slice($courseKeys, 1);

$courseDocument = $institute[$courseId];
/*find course brochure url for course*/
$brochureUrl = $brochureURL[$courseId];
$instituteEntity = $courseDocument->getInstitute();

$flagInsttRedirect = false;
if( ( $solr_institute_data['params_picked_by_qer'] && 
      sizeof($solr_institute_data['params_picked_by_qer']) == 1 &&
      in_array('institute_id', $solr_institute_data['params_picked_by_qer'])
    ) || 
    ( $solr_institute_data['single_result'] == 1 && 
      $solr_institute_data['single_result_type'] == 'institute')
 )
{
		$flagInsttRedirect = true;
		$listingBuilder = new ListingBuilder;
		$institute_id = $instituteEntity->getId();
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$instituteObject = $instituteRepository->find($institute_id);
}

$paramsPickedByQer = getLocationPrioritySet($solr_institute_data['qer_params_value']);
if(!isset($hideCompareBlock)){
	$hideCompareBlock = false;
}

$containerWidth = "550px";
$collegeDescWith = "415px";
if($hideCompareBlock){
	$containerWidth = "100%";
	$collegeDetailColoumn = "100%";
	//$collegePicWidth = "20%";
	$collegeDescWith = "82%";
}

$courseLocations = array();
$displayCourseName = "";
$pageId = calculatePageId($general['rows_count']['institute_rows'], $solr_institute_data['start']);
// If user has put any of the three cityid, localityid or zoneid than show all branches of that city only

$recommendationPage = false; // Don't show any recommendation related stuff
$showCompareCheckBox = false; //Don't show compare box

$localityIdUrlParam = $_REQUEST['locality_id'];
$zoneIdUrlParam = $_REQUEST['zone_id'];
$cityIdUrlParam = $_REQUEST['city_id'];

if(	array_key_exists('course_city_id', $paramsPickedByQer) ||
    array_key_exists('course_zone_id', $paramsPickedByQer) ||
	array_key_exists('course_locality_id', $paramsPickedByQer) ||
	!empty($localityIdUrlParam) ||
	!empty($zoneIdUrlParam) ||
	!empty($cityIdUrlParam) 
  ){
	if(array_key_exists('course_locality_id', $paramsPickedByQer) || !empty($localityIdUrlParam)){
		$courseLocations = array($courseDocument);
	} else if(array_key_exists('course_zone_id', $paramsPickedByQer) || !empty($zoneIdUrlParam) ){
		$selectedDocumentZoneId = $courseDocument->getLocation()->getZone()->getId();
		array_push($courseLocations, $courseDocument->getLocation());
		foreach($courseDocument->getOtherLocations() as $location){
			if($location->getZone()->getId() == $selectedDocumentZoneId){
				array_push($courseLocations, $location);
			}
		}
	} else if(array_key_exists('course_city_id', $paramsPickedByQer) || !empty($cityIdUrlParam)){
		$selectedDocumentCityId = $courseDocument->getLocation()->getCity()->getId();
		array_push($courseLocations, $courseDocument->getLocation());
		foreach($courseDocument->getOtherLocations() as $location){
			if($location->getCity()->getId() == $selectedDocumentCityId){
				array_push($courseLocations, $location);
			}
		}
	}
} else {
	array_push($courseLocations, $courseDocument->getLocation());
	foreach($courseDocument->getOtherLocations() as $location){
		array_push($courseLocations, $location);
	}
}

$singleSearchCase = false;
if($solr_institute_data['single_result'] == 1){
	$singleSearchCase = true;
}

$liStyle = "";
$result_type = "normal";
if($sponsored){
	$liStyle = "background:#fffbe0;padding-top: 4px !important; margin-top:0px !important;";
	$result_type = "sponsored";
}
if($count == 1 && !$sponsored){
	$liStyle = "padding-top: 4px !important; margin-top:0px !important;";
}

?>
<!--<div id="result_container_<?php echo $courseDocument->getInstitute()->getId();?>" style="float:left;width:100%;">-->
	<li style="<?php echo $liStyle;?>" id="li_container_<?php echo $courseDocument->getInstitute()->getId();?>">
		<?php
		if($_REQUEST['debug'] == "general"){
			echo "DS: " . $courseDocument->getDocumentScore();
			echo "<br/>CO: ". $courseDocument->getOrder();
			echo "<br/>CVC: ". $courseDocument->getViewCount();
			echo "<br/>IVC: ". $courseDocument->getInstitute()->getCumulativeViewCount()->getCount();
			echo "<br/>PS: ". $courseDocument->isPaid();
		}
		if($sponsored){
		?>
			<p style="text-align:right; display:block; color:#808080; font-size:11px">Sponsored Result</p>
		<?php
		}
		?>
		<div class="instituteListsDetails" style="width:<?php echo $containerWidth; ?>;padding-bottom: 7px;">
			<div class="collegeDetailCol" style="width:<?php echo $collegeDetailColoumn; ?>;">
				<h4>
					<!-- institute name url -->
					<?php
						if(isset($searchKeyword) && isset($pageNo) && isset($rowNo) && isset($uid)){
							$queryParamsForTracking = "&query=".urlencode($searchKeyword)."&page=".$pageNo."&row=".$rowNo."&uid=".$uid;
						}else{
							$queryParamsForTracking = '';
						}

					$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId().$queryParamsForTracking;
					$courseDocument->setAdditionalURLParams($additionalURLParams);
					if($flagInsttRedirect && !empty($instituteObject)) {
						$instituteObject->setAdditionalURLParams($additionalURLParams);
						$url = $instituteObject->getUrl();
					} else {
						$url = $courseDocument->getURL();
					}
					?>
					<a href="<?php echo $url ?>" onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $url; ?>', '<?php echo $courseDocument->getInstitute()->getId(); ?>', 'institute-title', '<?php echo $result_type;?>', '<?php echo $pageId;?>');"><?php echo htmlspecialchars_decode($courseDocument->getInstitute()->getName()); ?><?=(count($courseLocations)==1?', ':'')?></a>
					<!--<span><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?></span>-->
					
					<?php if(count($courseLocations)==1){ ?>
					<span><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?></span>
					<?php } ?>
					<span>
						<a class="see-all-link">
							<?php echo Modules::run('listing/ListingPageWidgets/seeAllBranches', $courseDocument, $courseLocations, "yes", "search"); ?>
						</a>
					</span>
				</h4>
				<em><?php echo trim($courseDocument->getInstitute()->getUsp())?'"'.$courseDocument->getInstitute()->getUsp().'"':''; ?></em>
				<div class="collegeDetailsWrapper">
					<div class="collegePic">
						<?php
							if($courseDocument->getInstitute()->getMainHeaderImage() && $courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL()){
								echo '<img src="'.$courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL().'" width="118"/>';
							}else{
								echo '<img src="/public/images/avatar.gif" />';
							}
						?>
						<?php
						        if($validateuser != 'false') {
								if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
										if(is_object($courseDocument)){
												if($courseDocument->isPaid()){
												    echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label></div>';
												}else{
												    echo '<div style="margin-top: 4px;"><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label></div>';	
												}
										}
								}
						        }
						?>
					</div>
					<div class="collegeDescription" style="width:<?php echo $collegeDescWith;?>; min-height: 120px;">
						<?php if($courseDocument->getInstitute()->getAIMARating()) { ?>
							<div class="aimaRating"  style="cursor:default;" onmouseover="catPageToolTip('aima','',this,30,-5);" onmouseout="hidetip();">
								<span>AIMA Rating:</span>
								<span class="ratingBox"><?php echo $courseDocument->getInstitute()->getAIMARating();?>&nbsp;<img align="absmiddle" src="/public/images/question-icons2.gif"/></span>
							</div>
						<?php
						} ?>
						<?php if($courseDocument->getInstitute()->getAlumniRating()) { ?>
							<div class="alumniRating" style="cursor:default;float:left;margin-bottom:5px;" onmouseover="catPageToolTip('alumni','',this,20,-5);" onmouseout="hidetip();">
								<div style="vertical-align:top;width: 87px;float:left;padding-top: 1px;">Alumni Rating:</div>
								<div style="float:left;width:130px;vertical-align:top;">
									<?php
									$i = 1;
									while($i <= $courseDocument->getInstitute()->getAlumniRating()){
									?>
										<img border="0" src="/public/images/gold_star_big.gif" style="float:left;margin-right:1px;">
									<?php
										$i++;
									}
									?>
									<div class="rateNum" style="float:left;padding-top:4px;">&nbsp;<?php echo $courseDocument->getInstitute()->getAlumniRating();?>/5&nbsp;<img align="absmiddle" src="/public/images/question-icons2.gif"/></div>
								</div>
								
							</div>
						<?php
						} ?>
							<div class="clearFix"></div>
							<!-- college name url -->
							<?php
							$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId().$queryParamsForTracking;
							$courseDocument->setAdditionalURLParams($additionalURLParams);
							?>
							<h5><a href="<?php echo $courseDocument->getURL(); ?>"  onclick="trackSearchQuery(event, '<?php echo $count;?>', '<?php echo $courseDocument->getURL(); ?>', '<?php echo $courseDocument->getId(); ?>', 'course-title', '<?php echo $result_type;?>', '<?php echo $pageId;?>');"><?php echo ($courseDocument->getCourseTitle()); ?></a>
								<span>
								- <?php echo $courseDocument->getDuration()->getDisplayValue() ? $courseDocument->getDuration()->getDisplayValue() : ""; ?>
								  <?php echo ( $courseDocument->getDuration()->getDisplayValue() && $courseDocument->getCourseType() ) ? ", " . $courseDocument->getCourseType() : ( $courseDocument->getCourseType() ? $courseDocument->getCourseType() : "" ); ?>
								  <?php echo ( $courseDocument->getCourseLevel() && ($courseDocument->getCourseType() || $courseDocument->getDuration()->getDisplayValue())) ? ", ". $courseDocument->getCourseLevel() : ( $courseDocument->getCourseLevel() ? $courseDocument->getCourseLevel() : ""); 
								if($showSimilarCourses == true && count($remainingCourseKeys) > 0){
									if(count($remainingCourseKeys) == 1){
									?>
										<span style="font-weight:normal;"><label style="color:#717171;"> | </label><a href="javascript:void(0);" style="cursor:pointer;" class="see-all-link" onclick="applyBorder('show_more_courses_<?php echo $courseDocument->getInstitute()->getId()?>');"><?php echo count($remainingCourseKeys);?> similar course<span class="sprite-bg" style="margin-left:3px;"></span></a></span>
									<?php
									} else { ?>
										<span style="font-weight:normal;"><label style="color:#717171;"> | </label><a href="javascript:void(0);" style="cursor:pointer" class="see-all-link" onclick="applyBorder('show_more_courses_<?php echo $courseDocument->getInstitute()->getId()?>');"><?php echo count($remainingCourseKeys);?> similar courses<span class="sprite-bg" style="margin-left:3px;"></span></a></span>
									<?php
									}
								}
								?>
								</span>
							</h5>
							
							<div class="feeStructure">
								<?php
								$exams = $courseDocument->getEligibilityExams();
								if($courseDocument->getFees()->getValue()) { ?>
									<label>Fees: </label> <span><?php echo $courseDocument->getFees();?></span> 
								<?php
								} else {
								?>
									<!--<label>Fees: </label> <span>Not Available</span>-->
								<?php
								}
								if(count($exams) > 0 && $courseDocument->getFees()->getValue()){
									echo '<b>|</b>';
								}
								if(count($exams) > 0){ ?>
									<label>Eligibility: </label>
									<span>
										<?php
										$examAcronyms = array();
										foreach($exams as $exam) {
											$examAcronyms[] = $exam->getAcronym();
										}
										echo implode(', ',$examAcronyms); ?>
									</span>
								<?php
								} ?>
							</div>
						
							<div style="margin-top:3px;">
								<?php
								$approvalsAndAffiliations = array();
								$approvals = $courseDocument->getApprovals();
								foreach($approvals as $approval) {
									$outString = "";
									if(in_array($approval,array('aicte','ugc','dec'))){
										$outString = "<span style='cursor:default;' onmouseover=\"catPageToolTip('".$approval."','',this,0,-5);\" onmouseout=\"hidetip();\">".str_ireplace(' ','&nbsp;',langStr('approval_'.$approval)).'&nbsp;<img align="absmiddle" src="/public/images/question-icons2.gif"/>'."</span>";
									}else{
										$outString = "<span>".langStr('approval_'.$approval)."</span>";
									}
									$approvalsAndAffiliations[] = $outString;
								}
								$affiliations = $courseDocument->getAffiliations();
								foreach($affiliations as $affiliation) {
									$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);  
								}
								echo implode(', ',$approvalsAndAffiliations);
								?>
							</div>
						
							<?php
							if(count($salientFeatures = $courseDocument->getSalientFeatures(4))){
							?>
							<ul class="facilities" style="margin-bottom:0 !important">
								<?php
									foreach($salientFeatures as $sf) {
								?>
										<li>
											<span></span>
											<strong>
												<?php echo str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue())); ?>
											</strong>
										</li>
								<?php
									}
								?>
							</ul>
							<?php
							}
							?>
							
							<?php
							if($courseDocument->getOAFLastDate() != "" && $courseDocument->getOAFFees() != ""){
							?>
                            <div class="feeStructure">
								<label style="color:#ED7117;">Last Date: </label>
								<span style="color:#ED7117;"><?php echo $courseDocument->getOAFLastDate();?></span>
								<b> | </b>
								<label>Form Fees: </label>
								<span style="color:#000;"><?php echo $courseDocument->getOAFFees();?></span>
							</div>
							<?php
							}
							?>
                <select id="applynow<?php echo $courseDocument->getInstitute()->getId();?>" style="display:none;">
			<?php
				$courseDocuments = array();
				if($singleResultCase){
					$courseDocuments = $allCourseDocuments;
				} else {
					for($counter = 0; $counter < count($courseKeys); $counter++){
						$courseDocuments[$courseKeys[$counter]] = $institute[$courseKeys[$counter]];
					}	
				}
				global $studyAbroadIds;
				global $instituteWithMultipleCourseLocations;
				$totalPaidCourses = 0;
				$totalMultiLocationCourses = 0;
				$paidCoursesEnc = array();
				$dropDownEntries = array();
                                $courseListForBrochure = array();
				foreach($courseDocuments as $applyCourse) {
					// either course is paid listing OR course is free & has a brochure
					if($applyCourse->isPaid() || (!$applyCourse->isPaid() && $brochureURL[$applyCourse->getId()] != "")){
						if(!in_array($applyCourse->getId(), $paidCoursesEnc)){
							$totalPaidCourses++;
							array_push($paidCoursesEnc, $applyCourse->getId());
						}
						if($applyCourse->isStudyAbroadCourse() == true){ //Its a study abroad course
							array_push($studyAbroadIds, $applyCourse->getId());
						}
						$otherLocations = $applyCourse->getOtherLocations();
						if(!empty($otherLocations)){
							$totalMultiLocationCourses++;
						}
						$otherLocations[$applyCourse->getLocation()->getLocationId()] = $applyCourse->getLocation();
						$documentCourseLocation = $otherLocations;
						global $localityArray;
						$localityArray[$applyCourse->getId()] = getLocationsCityWise($documentCourseLocation);
						if(!in_array($applyCourse->getId(), $dropDownEntries)){
							array_push($dropDownEntries, $applyCourse->getId());
                                                        $courseListForBrochure[$applyCourse->getId()] = $applyCourse->getName();
						?>
							<option title="<?php echo $applyCourse->getCourseTitle(); ?>" value="<?php echo $applyCourse->getId(); ?>">
								<?php echo $applyCourse->getCourseTitle(); ?>
							</option>
						<?php
						}
					}
				}
				
				if($totalMultiLocationCourses > 0 || $totalPaidCourses > 1){ //This institute has more than one course to display
					if(!in_array($courseDocument->getInstitute()->getId(), $instituteWithMultipleCourseLocations)){
						array_push($instituteWithMultipleCourseLocations, $courseDocument->getInstitute()->getId());
					}
				}
				?>
		</select>
							<div class="spacer10 clearFix"></div>
							<div style="position:relative;float:left;">
								<?php
								$applyNowButtonStyle = "";
								$applyNowOverlayStyle = "";
								$applyNowPointerStyle = "";
								$tempCityId = $courseDocument->getLocation()->getCity()->getId();
								// either course is paid listing OR course is free & has a brochure
								if(($courseDocument->isPaid() && !empty($tempCityId)) || (!$courseDocument->isPaid() && $brochureUrl != "")){
									$applyNowButtonStyle = "margin-left:10px;";
									//keep brochure url as a hidden parameter, to later check for its type(PDF/IMAGE) in js at the time of start download
									echo '<input type = "hidden" id = "course'.$courseDocument->getId().'BrochureURL" value="'.$brochureUrl.'">';
				
									?>
									<span class="orangeButtonStyle flLt" style="margin-right:10px;" uniqueattr="SearchPage/reqEBrocher" type="button" value="Request E-brochure" title="Download E-Brochure" onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;} changeDropDownSelectedIndex('applynow<?php echo $courseDocument->getInstitute()->getId();?>', <?php echo $courseDocument->getId();?>); return multipleCourseApplyForCategoryPage(<?php echo $courseDocument->getInstitute()->getId()?>,'MAIN_SEARCH_PAGE',this, <?php echo $courseDocument->getId(); ?>,'<?=base64_encode(serialize($courseListForBrochure))?>');">Download E-Brochure</span>
									<?php
								} else {
									$applyNowOverlayStyle = "left:-130px;";
									$applyNowPointerStyle = "margin-left:152px;";
								}
								if($courseDocument->getOAFLastDate() != "" && $courseDocument->getOAFFees() != ""){
									$courseOFExternalURL = $courseDocument->getOAFExternalURL();
									if(!empty($courseOFExternalURL)){
										//$externalURL = 'http://www.shiksha.com/Online/OnlineForms/showPage/'.base64_encode($courseOFExternalURL);
										//$externalURL = '/Online/OnlineFormConversionTracking/send/'.$courseDocument->getId();
										//external online form call changed- as external forms will have seo landing page. 
										?>
										<span class="gray-button flLt" onmouseout="hide('of_details_<?php echo $courseDocument->getId();?>');" onmouseover="show('of_details_<?php echo $courseDocument->getId();?>');" onclick="trackSearchQuery(event, '<?php echo $count;?>', 'http://www.shiksha.com/Online/OnlineForms/showOnlineForms/<?php echo $courseDocument->getId();?>', '<?php echo $courseDocument->getId(); ?>', 'apply-online', '<?php echo $result_type;?>', '<?php echo $pageId;?>');">Apply online</span>
									<?php
									} else {
										?>
										<span class="gray-button flLt" onmouseout="hide('of_details_<?php echo $courseDocument->getId();?>');" onmouseover="show('of_details_<?php echo $courseDocument->getId();?>');" onclick="trackSearchQuery(event, '<?php echo $count;?>', 'http://www.shiksha.com/Online/OnlineForms/showOnlineForms/<?php echo $courseDocument->getId();?>', '<?php echo $courseDocument->getId(); ?>', 'apply-online', '<?php echo $result_type;?>', '<?php echo $pageId;?>');">Apply online</span>
									<?php
									}
								}
								//Only show compare checkbox if search type is institute
								if($courseDocument->isPaid()){
									if($search_type == "institute" && !$singleSearchCase && $showCompareCheckBox){
									?>
										<span  uniqueattr="SearchPage/addCompare" class="flRt">
											<input
												onclick="updateCompareText('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>'); updateCompareList('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');"
												type="checkbox"
												style="vertical-align:middle"
												name="compare"
												id="compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>"
												value="<?php echo $courseDocument->getInstitute()->getId().'::'.' '.'::'.($courseDocument->getInstitute()->getMainHeaderImage() ? $courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(($courseDocument->getInstitute()->getName())).', '.$courseDocument->getLocation()->getCity()->getName().'::'.$courseDocument->getId();?>"/>
											<a  href="javascript:void(0);" onclick="toggleCompareCheckbox('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');updateCompareList('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');return false;" id="compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>lable" style="vertical-align:middle">Compare</a>
										</span>
									<?php
									}
								}
								if($courseDocument->getOAFLastDate() != "" && $courseDocument->getOAFFees() != ""){
								?>
								<div style='display:none;<?php echo $applyNowOverlayStyle;?>' class="applylayerWrap" id="of_details_<?php echo $courseDocument->getId();?>">
									<span class="applylayerPointer" style='<?php echo $applyNowPointerStyle;?>'></span>
									<div class="applylayerContent">
										<ul>
											<li>
												<label>Min Qualification:</label>
												<span><?php echo $courseDocument->getOAFMinQualification();?></span>
											</li>
											<li>
												<label>Form Fees:</label>
												<span><?php echo $courseDocument->getOAFFees();?></span>
											</li>
											<li>
												<label>Exams Accepted:</label>
												<span><?php echo $courseDocument->getOAFExams();?></span>
											</li>
											<li><div class="lastDateNotify">Last Date to Apply: <span><?php echo $courseDocument->getOAFLastDate();?></span></div></li>
										</ul>
										<div class="clearFix"></div>
									</div>
									<div class="clearFix"></div>
								</div>
								<?php
								}
								?>
							</div>
					</div><!-- collegeDescription ends -->
				</div><!-- collegeDetailsWrapper ends -->
			</div><!-- collegeDetailCol ends -->
		</div> <!-- instituteListsDetails ends -->
		<?php
		if($courseDocument->isPaid()){
			if($recommendationPage) {
				?>
				<div class="compareInstBox" style="background-color:#fff; border:none;">
					<div class="apply_confirmation" id="apply_confirmation<?php echo $courseDocument->getInstitute()->getId(); ?>"
						<?php if(in_array($courseDocument->getInstitute()->getId(), $recommendationsApplied)) echo "style='display:none;'"; ?> >
							E-brochure successfully mailed
						<input type='hidden' id="apply_status<?php echo $courseDocument->getInstitute()->getId(); ?>" value='<?php if(in_array($courseDocument->getInstitute()->getId(), $recommendationsApplied)) echo "1"; else echo "0"; ?>' />
					</div>
					<input onClick = "doAjaxApply('<?php echo $courseDocument->getInstitute()->getId(); ?>','<?php echo $courseDocument->getInstitute()->getId(); ?>')" class="orangeButtonStyle<?php if(in_array($courseDocument->getInstitute()->getId(), $recommendationsApplied)) echo "_disabled"; ?> mr15" id="apply_button<?php echo $courseDocument->getInstitute()->getId(); ?>" type="button" value="Request E-brochure" title="Request E-brochure" />
				</div>
		<?php } else {
				?>
				<div class="compareInstBox" style="position:relative;display:none;">
					<?php
					//Only show compare checkbox if search type is institute
					if($searchType == "institute"){
					?>
						<p  uniqueattr="SearchPage/addCompare" style="position:absolute;bottom:0px;">
							<input
								onclick="updateCompareText('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>'); updateCompareList('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');"
								type="checkbox"
								name="compare"
								id="compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>"
								value="<?php echo $courseDocument->getInstitute()->getId().'::'.' '.'::'.($courseDocument->getInstitute()->getMainHeaderImage() ? $courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(($courseDocument->getInstitute()->getName())).', '.$courseDocument->getLocation()->getCity()->getName().'::'.$courseDocument->getId();?>"/>
							
							<a  href="#" onclick="toggleCompareCheckbox('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');updateCompareList('compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>');return false;" id="compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>lable">Compare</a>
						</p>
					<?php
					}
					?>
					<!--
					<input class="orangeButtonStyle" uniqueattr="CategoryPage/reqEBrocher" type="button" value="Request E-brochure" title="Request E-brochure" onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return multipleCourseApplyForCategoryPage(<?php echo $courseDocument->getInstitute()->getId()?>,'HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO',this);"/>
					-->
				</div>
		<?php
			}
			?>
			<div style="display:none;">
				<?php
					foreach($courseDocuments as $applyCourse) {
						if($applyCourse->isPaid()){
						?>
						<input type="hidden"
							   name="compare<?php echo $courseDocument->getInstitute()->getId();?>-<?php echo $courseDocument->getId();?>list[]"
							   value= "<?php echo $applyCourse->getId(); ?>" />
						<?php
						}
					}
				?>
			</div>
		<?php
		}
		?>
		<div id="institute<?php echo trim($courseDocument->getInstitute()->getId());?>name" style="display:none;">
		<?php
				$tempHeadOffice = $courseDocument->getHeadOfficeLocation();
				if(!empty($tempHeadOffice)){
						echo strip_tags(trim( $courseDocument->getInstitute()->getName() .", " . trim($courseDocument->getHeadOfficeLocation()->getCity()->getName()) ) );
				}
				
		?>
		</div>
		
		<?php
		if($recommendationPage) { ?>
			<input type='hidden' id='params<?php echo $courseDocument->getInstitute()->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser, $courseDocument)); ?>' />
		<?php	
		}
		?>
		
		<?php
		if($showSimilarCourses == true) {
		?>
			<!--<div class="course-detail-section" id="show_more_courses_<?php echo $courseDocument->getInstitute()->getId();?>" style="display:none;cursor:pointer;" onclick="toggleDiv('show_more_courses_<?php echo $courseDocument->getInstitute()->getId()?>');">-->
			<div class="course-detail-section" id="show_more_courses_<?php echo $courseDocument->getInstitute()->getId();?>" style="display:none;">
					<?php
					$courseDocuments = array();
					for($counter = 0; $counter < count($remainingCourseKeys); $counter++){
						$courseDocuments[$remainingCourseKeys[$counter]] = $institute[$remainingCourseKeys[$counter]];
					}
					$courseContainerOpen = true;
					$viewData = array(
									'course_title'			=> $courseDocument->getCourseTitle(),
									'documents'   			=> $courseDocuments,
									'categoryId' 			=> 0,
									'headOfficeDetails'	 	=> true,
									'courseContainerOpen'	=> $courseContainerOpen,
									'instituteId'			=> $courseDocument->getInstitute()->getId(),
									'headingType'		 	=> "more",
									'result_type'			=> $result_type,
									'pageId'				=> $pageId,
									'rowCount' 				=> $count,
									'queryParamsForTracking'=> $queryParamsForTracking
									);
					$this->load->view('search/search_more_courses_snippet', $viewData);
					?>
			</div>
		<?php
		}
	?>
	
	</li>
	
<!--</div>-->
