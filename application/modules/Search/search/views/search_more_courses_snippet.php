<?php
$categoryList = $categorylist;//variable set in controller
$totalCourses = count($documents);
$courseContainerHeaderClass = "course-title-open";
$courseContainerStyle = "display:block;";
if($courseContainerOpen == false){
	$courseContainerHeaderClass = "course-title";
	$courseContainerStyle = "display:none;";
}
if($headingType == "category"){
	$styleStr = $courseContainerStyle;
	if($headingType == "category"){
		$styleStr .= "padding:10px 0px 10px 10px;";
	}
	$courseContainerStyle = $styleStr;
}
?>

	<?php
	$headerContent = "More Courses";
	if($headingType == "category"){
		$categoryName = "Others";
		if(isset($categoryList[$categoryId]) && !empty($categoryList[$categoryId])){
			$categoryName = $categoryList[$categoryId]['name'];
		}
		$headerContent = "Courses in ". $categoryName . " - <a href='javascript:void(0);'>" . getPlural($totalCourses, 'course') . "</a>";
	} else if($headingType == "more"){
		$headerContent = $totalCourses . " " . getPlural($totalCourses, 'course', false) . " similar to &ldquo;" . $course_title . "&rdquo;";
	}
	if(count($documents) > 0){
	?>
		<div class="course-detail-main" style="float:left;width:100%;">
			<?php
			if($headingType == "more"){
				?>
				<div id="course-details-<?php echo $instituteId . "-" . $categoryId;?>-head" class="more-courses-header">
				<?php
			} else {
				?>
				<div id="course-details-<?php echo $instituteId . "-" . $categoryId;?>-head" style="cursor:pointer;padding-left:30px;" onclick="changeShowMoreCoursesClass('course-details-<?php echo $instituteId . "-" . $categoryId;?>');" class="<?php echo $courseContainerHeaderClass;?>">
				<?php
			}?><?=trim($headerContent);?>
				</div>
			<div id="course-details-<?php echo $instituteId . "-" . $categoryId;?>" style="<?php echo $courseContainerStyle;?>">
				<?php
				$displayRowCount = 0;
				foreach($documents as $course){
					$displayRowCount++;
					if($_REQUEST['debug'] == "general"){
						echo "DS: " . $course->getDocumentScore();
						echo "<br/>CO: ". $course->getOrder();
						echo "<br/>CVC: ". $course->getViewCount();
						echo "<br/>IVC: ". $course->getInstitute()->getCumulativeViewCount()->getCount();
						echo "<br/>PS: ". $course->isPaid();
					}
					if($headOfficeDetails){
						$location = $course->getHeadOfficeLocation();
						$instituteLocationId = 0;
						if($location != false){
							$instituteLocationId = $location->getLocationId();
						}
					}
					$liStyle = "";
					if($displayRowCount == count($documents)){
						$liStyle = "border:none;";
					}
				?>
				<ol>
					<li style="<?php echo $liStyle;?>">
						<p>
							<?php
							$additionalURLParams = "?city=".$course->getLocation()->getCity()->getId()."&locality=".$course->getLocation()->getLocality()->getId().$queryParamsForTracking;
							$course->setAdditionalURLParams($additionalURLParams);
							?>
							<a href="<?php echo $course->getURL();?>" onclick="trackSearchQuery(event, '<?php echo $rowCount;?>', '<?php echo $course->getURL(); ?>', '<?php echo $course->getId(); ?>', 'more-courses', '<?php echo $result_type;?>', '<?php echo $pageId;?>');" ><?php echo ($course->getCourseTitle()); ?></a>
							-
							<?php echo trim($course->getDuration()->getDisplayValue()) ? trim($course->getDuration()->getDisplayValue()) : "";?>
							<?php echo ( $course->getDuration()->getDisplayValue() && $course->getCourseType() ) ? ", " . $course->getCourseType() : ( trim($course->getCourseType()) ? trim($course->getCourseType()) : "" ); ?>
							<?php echo ( $course->getCourseLevel() && ($course->getCourseType() || $course->getDuration()->getDisplayValue())) ? ", ". $course->getCourseLevel() : ( $course->getCourseLevel() ? $course->getCourseLevel() : ""); 
							?>
						</p>
						<p class="fee-structure">
							<?php
							if($course->getFees($instituteLocationId)->getValue()) { ?>
								Fees: <span><?php echo $course->getFees($instituteLocationId);?></span> 
							<?php
							} else {
							?>	<!--Fees: <span>Not Available</span>-->
							<?php
							}
							$exams = $course->getEligibilityExams();
							if(count($exams) > 0 && $course->getFees($instituteLocationId)->getValue()){
								echo '&nbsp;|&nbsp;';
							}
							if(count($exams) > 0){ ?>
								Eligibility:
								<span>
									<?php
										$examAcronyms = array();
										foreach($exams as $exam) {
											$examAcronyms[] = $exam->getAcronym();
										}
										echo implode(', ',$examAcronyms); ?>
								</span>
								<?php
							}
							?>
						</p>
						<p>
							<?php
								$approvalsAndAffiliations = array();
								$approvals = $course->getApprovals();
								foreach($approvals as $approval) {
									$outString = "";
									if(in_array($approval,array('aicte','ugc','dec'))){
										$outString = "<span onmouseover=\"catPageToolTip('".$approval."','',this,0,-5);\" onmouseout=\"hidetip();\">".langStr('approval_'.$approval)."</span>";
									}else{
										$outString = langStr('approval_'.$approval);
									}
									$approvalsAndAffiliations[] = $outString;
								}
								$affiliations = $course->getAffiliations();
								foreach($affiliations as $affiliation) {
									$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);  
								}
								echo implode(', ',$approvalsAndAffiliations);
							?>
						
							<?php
							if(count($salientFeatures = $course->getSalientFeatures(4))){
							?>
							<div class="facilities" style="margin-bottom:0 !important">
								<?php
									$featuresTemp = array();
									foreach($salientFeatures as $sf) {
										array_push($featuresTemp, str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue())) );
									}
									?>
									<span>
										<?php
										echo implode('<label style="color:#D0D0D0;"> | </label> ',$featuresTemp);
										?>
									</span>
									<?php
								?>
							</div>
							<?php
							}
							?>
							
							<?php
							if($course->getOAFLastDate() != "" && $course->getOAFFees() != ""){
							?>
                            <div class="feeStructure">
								<label style="color:#ED7117;">Last Date: </label>
								<span style="color:#ED7117;"><?php echo $course->getOAFLastDate();?></span>
								<b> | </b>
								<label>Form Fees: </label>
								<span style="color:#000;"><?php echo $course->getOAFFees();?></span>
							</div>
							<?php
							}
							?>
						</p>
						<div class="spacer10 clearFix"></div>
						<div style="position:relative;">
						<?php
							$applyNowButtonStyle = "";
							$applyNowOverlayStyle = "left:-10px;";
							$applyNowPointerStyle = "margin-left:32px";
							if($course->isPaid() || ($course->getOAFLastDate() != "" && $course->getOAFFees() != "") ){
								?>
								<!--<p>-->
									<?php
									$tempCityId = $course->getLocation()->getCity()->getId();
									if($course->isPaid() && !empty($tempCityId)){
										$applyNowButtonStyle = "margin-left:10px;";
										$applyNowOverlayStyle = "left:-7px;";
										$applyNowPointerStyle = "margin-left:205px";
										?>
										<input type="hidden" id = "course<?php echo $course->getId();?>BrochureURL" value="<?php echo $brochureURL[$course->getId()]; ?>">
										<span class="orangeButtonStyle" type="button" value="Download E-brochure" title="Download E-brochure" onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;} changeDropDownSelectedIndex('applynow<?php echo $course->getInstitute()->getId();?>', <?php echo $course->getId();?>); return multipleCourseApplyForCategoryPage(<?php echo $course->getInstitute()->getId()?>,'MAIN_SEARCH_PAGE',this);">Download E-brochure</span>
										<?php
									}
									
									if($course->getOAFLastDate() != "" && $course->getOAFFees() != ""){
										$courseOFExternalURL = $course->getOAFExternalURL();
										if(!empty($courseOFExternalURL)){
											//$externalURL = 'http://www.shiksha.com/Online/OnlineForms/showPage/'.base64_encode($courseOFExternalURL);
											//external online form call changed - as external forms will have seo landing page.
											?>
											<span class="gray-button" style='<?php echo $applyNowButtonStyle;?>' onmouseover="show('of_details_<?php echo $course->getId();?>');" onmouseout="hide('of_details_<?php echo $course->getId();?>');" onClick="trackSearchQuery(event, '<?php echo $count;?>', 'http://www.shiksha.com/Online/OnlineForms/showOnlineForms/<?php echo $course->getId();?>', '<?php echo $course->getId(); ?>', 'apply-online', '<?php echo $result_type;?>', '<?php echo $pageId;?>');">Apply online</span>
											<?php
										} else {
											?>
											<span class="gray-button" style='<?php echo $applyNowButtonStyle;?>' onmouseover="show('of_details_<?php echo $course->getId();?>');" onmouseout="hide('of_details_<?php echo $course->getId();?>');" onClick="trackSearchQuery(event, '<?php echo $count;?>', 'http://www.shiksha.com/Online/OnlineForms/showOnlineForms/<?php echo $course->getId();?>', '<?php echo $course->getId(); ?>', 'apply-online', '<?php echo $result_type;?>', '<?php echo $pageId;?>');">Apply online</span>
										<?php
										}
									}
									?>
								<!--</p>-->
								<?php
							}
							
							if($course->getOAFLastDate() != "" && $course->getOAFFees() != ""){
								?>
								<div style="display:none;<?php echo $applyNowOverlayStyle;?>" class="applylayerWrap" id="of_details_<?php echo $course->getId();?>">
									<span class="applylayerPointer" style='<?php echo $applyNowPointerStyle;?>'></span>
									<div class="applylayerContent">
										<ul>
											<li>
												<label>Min Qualification:</label>
												<span><?php echo $course->getOAFMinQualification();?></span>
											</li>
											<li>
												<label>Form Fees:</label>
												<span><?php echo $course->getOAFFees();?></span>
											</li>
											<li>
												<label>Exams Accepted:</label>
												<span><?php echo $course->getOAFExams();?></span>
											</li>
											<li><div class="lastDateNotify">Last Date to Apply: <span><?php echo $course->getOAFLastDate();?></span></div></li>
										</ul>
										<div class="clearFix"></div>
									</div>
									<div class="clearFix"></div>
								</div>
								<?php
							}
							?>
						</div>
					</li>
				</ol>
				<?php
				if($displayRowCount != count($documents) && $headingType == "category"){
				?>
					<div class="spacer15 clearFix"></div>
					<div style="height:1px;overflow:hidden;background:#EEE;"></div>
					<div class="spacer10 clearFix"></div>
				<?php
				}
				?>
				<?php
				}
				?>
			</div>
		</div>
	<?php
	}
	?>
