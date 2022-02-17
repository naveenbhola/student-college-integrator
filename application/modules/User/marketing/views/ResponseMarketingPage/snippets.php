<?php if(!$institutes || count($institutes) == 0) { ?>
	<h1 style="color:#FD8103; font-size:13px;">Sorry, no results were found matching your selection.<br> Please alter your refinement options above.</h1>
<?php } else { ?>	
<ul>
<?php
	$count = 0;
	$localityArray = array();
	foreach($institutes as $institute) {
		$count++;
		$course = $institute->getFlagshipCourse();
		global $appliedFilters;
		if($request){
			$appliedFilters = $request->getAppliedFilters();
			$course->setCurrentLocations($request);
		}
		$displayLocation = $course->getCurrentMainLocation();
		$courseLocations = $course->getCurrentLocations();
		if($appliedFilters){
			foreach($courseLocations as $location){
				$localityId = $location->getLocality()?$location->getLocality()->getId():0;
				if(in_array($localityId,$appliedFilters['locality'])){
					$displayLocation = $location;
					break;
				}
				if(in_array($location->getCity()->getId(),$appliedFilters['city'])){
					$displayLocation = $location;
					break;
				}
			}
		}
		if(!$courseLocations || count($courseLocations) == 0){
			$courseLocations = $course->getLocations();
		}
		if(!$displayLocation){
			$displayLocation = $course->getMainLocation();
		}
		$courses = $institute->getCourses();
		$additionalURLParams = "";
		if($request){
			if(count($course->getLocations()) > 1){
				if($request->getCityId() > 1){
					$additionalURLParams = "?city=".$displayLocation->getCity()->getId();
					if($request->getLocalityId()){
						$additionalURLParams .= "&locality=".$request->getLocalityId();
					}
				}
				$course->setAdditionalURLParams($additionalURLParams);
			}
		}
?>
		<li id="listingSnippet<?php echo $institute->getId(); ?>">
			<h5>
				<a href="#" uniqueattr="ResponseMarketingPageInstituteDetailButton" onclick="showListingDetailOverview('<?php echo $course->getURL(); ?>','<?php echo $institute->getId(); ?>'); return false;"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span>
				<a href="#" class="close-items" uniqueattr="ResponseMarketingPageInstituteHideButton" title="Close" onclick="hideListingSnippet('<?php echo $institute->getId(); ?>'); return false;">&nbsp;</a>
			</h5>
			
			<div class="figure">
				<?php
					if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){
						echo '<img src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="118"/>';
					}else{
						echo '<img src="/public/images/avatar.gif" />';
					}
				?>
			</div>
			
			<div class="details">
			
				<?php if($institute->getAIMARating()) { ?>
					<p class="aima-rating" onmouseover="catPageToolTip('aima','',this,30,-10);" onmouseout="hidetip();">
						AIMA Rating:
						<span class="rating-box"><?=$institute->getAIMARating()?></span>
					</p>
				<?php } ?>
			
				<?php if($institute->getAlumniRating()) { ?>
					<p class="alum-rating">
						Alumni Rating:
						<span>
							<?php $i = 1; while($i <= $institute->getAlumniRating()){ ?><img src="/public/images/responseMarketingPage/star.gif" alt="" /><?php $i++; } ?>
						</span>
						<span class="rateNum">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
					</p>
				<?php } ?>
			
				
				<p>
				<a href="#" uniqueattr="ResponseMarketingPageCourseDetailButton" onclick="showListingDetailOverview('<?php echo $course->getURL(); ?>','<?php echo $institute->getId(); ?>'); return false;"><?php echo html_escape($course->getName()); ?></a>  -   <?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?><?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?><?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?></p>
				
				<p>
				<?php if($course->getFees()->getValue()){ ?>
					<label>Fees: </label> <?=$course->getFees()?>
				<?php }else{
				?>
					<label>Fees: </label> Not Available
				<?php
				}
				?>
				
				<?php
				$exams = $course->getEligibilityExams();
				if(count($exams) > 0){
					echo '<label>&nbsp;|&nbsp;</label>';
				}
				if(count($exams) > 0){ 
					if($institute->getInstituteType() == "Test_Preparatory_Institute"){
				?>
					<label>Exams Prepared for: </label>
				<?php
					}else{
				?>
					<label>Eligibility: </label>
				<?php
					}
					$examAcronyms = array();
					foreach($exams as $exam) {
						$examAcronyms[] = $exam->getAcronym();
					}
					echo implode(', ',$examAcronyms); ?>
				<?php } ?>
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
							$outString = "<span>".langStr('approval_'.$approval)."</span>";
						}
						$approvalsAndAffiliations[] = $outString;
					}
					
					$affiliations = $course->getAffiliations();
					foreach($affiliations as $affiliation) {
						$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);	
					}
	
					echo implode(', ',$approvalsAndAffiliations);
				?>
				</p>
				
				<?php
				if(count($salientFeatures = $course->getSalientFeatures(4))){
				?>
				
				<ul class="facilities">
				<?php
					foreach($salientFeatures as $sf){
				?>
						<li><span></span><strong><?=str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue()))?></strong></li>
				<?php
					}
				?>
				</ul>
				
				<?php
				}
				?>
			
				<div class="spacer10 clearFix"></div>
				<input type="button" class="orange-button flLt" value="Request E-brochure" uniqueattr="ResponseMarketingPageRequestEBrochureButton" onclick="loadResponseForm('<?php echo $institute->getId(); ?>','<?php echo $course->getId(); ?>')" />
				
				<div class="confirm-msg" id="brochureConfirmation<?php echo $institute->getId(); ?>" style="display:none;">E-brouchure successfully mailed.</div>
				<div class="clearFix"></div>
				<div id="responseForm<?php echo $institute->getId(); ?>"></div>
			</div>
			<div class="clearFix"></div>
		</li>
<?php		
	}
?>
</ul>

<?php if($canLoadMoreInstitutes) { ?>
<div id="pageResultHolder<?php echo $currentPageNumber+1; ?>">
<ul>
<li>
		<div style="float:left; padding-top:6px; margin-left: 10px; width:30px;">
			<img src='/public/images/loader_hpg.gif' id="pagination-loader" style="display:none;" />
		</div>
		<a href="#" uniqueattr="ResponseMarketingPageSeeMoreButton" title="See more institutes" class="more-items" style="float:left; margin-left: 210px;" onclick="displayMoreInstituteResults('<?php echo $currentPageNumber; ?>'); return false;">See more institutes</a>
		<div class="clearFix"></div>
</li>
</ul>
</div>
<?php } ?>
<?php } ?>	