<?php
	$this->load->view('listing/listingPage/listingHead',array('tab' => 'courses', 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount));
	$courses = $institute->getCourses();
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_COURSE_PAGE',1);
?>
<script>
var courses = new Array();
var courseIdForTracking = 0;
var instituteIdForTracking = <?php echo $institute->getId(); ?>;
</script>
<div id="page-contents">
	<div id="listing-left-col">
		<div class="section-cont mbot-0 accordion">
		<?php
			$id='id="first"';
			
			foreach($courses as $course){
		?>		
		
			<h5 class="desc-title-box pointer-cursor" <?=$id?>>
				<span class="sprite-bg closed-arrow"></span>
				<strong><?=html_escape($course->getName())?></strong>
			</h5>
			
			<div class="course-details" style="display:none">
				<h6 class="sub-title bld">
					<?php
						echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; 
						echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():"");
						echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():"");
					?>
				</h6>
				<p class="scholl-desc">
					<?php
						$approvalsAndAffiliations = array();
						$approvals = $course->getApprovals();
						foreach($approvals as $approval) {
							$approvalsAndAffiliations[] = langStr('approval_'.$approval);
						}
						$affiliations = $course->getAffiliations();
						foreach($affiliations as $affiliation) {
							$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
						}
						echo implode(', ',$approvalsAndAffiliations);
					?>
				</p>
				<div class="spacer10 clearFix"></div>
				<ul class="bullet-items">
					
					<?php
							if($accredited = $course->getAccredited()){
					?>
							<li>
								<p>
								<label>Accreditation: </label>
								<?=html_escape($accredited)?>
								</p>
							</li>
					<?php
							}
					?>
					
					<?php
							if($course->getFees($currentLocation->getLocationId())->getValue($currentLocation->getLocationId())){ ?>
							<li>
								<p>
								<label>Fees: </label> <?=$course->getFees($currentLocation->getLocationId())?>
								</p>
							</li>
					<?php
							}
					?>
					<?php
							if($course->getTotalSeats() || $course->getManagementSeats() || $course->getGeneralSeats() || $course->getReservedSeats()){
					?>
							<li>
								<p>
								<label>Seats: </label>
								<?php
									$seatsArray = array();
									if($course->getTotalSeats()){
										$seatsArray[] = "Total - ".$course->getTotalSeats();
									}
									if($course->getGeneralSeats()){
										$seatsArray[] = "General - ".$course->getGeneralSeats();
									}
									if($course->getManagementSeats()){
										$seatsArray[] = "Management - ".$course->getManagementSeats();
									}
									if($course->getReservedSeats()){
										$seatsArray[] = "Reserved - ".$course->getReservedSeats();
									}
									echo implode('<span> | </span> ',$seatsArray);
								?>
								</p>
							</li>
					<?php
							}
					?>
					
					
					<?php
						$exams = $course->getEligibilityExams();
						if(count($exams) > 0 || $course->getOtherEligibilityCriteria()){ ?>
							<li><p>
						<?php
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
								$tempExam = $exam->getAcronym();
								if($exam->getMarks()){
									$tempExam .= " - ".$exam->getMarks()." ".titleCase(str_replace("_"," ",$exam->getMarksType()));
								}
								if($exam->getPracticeTestsOffered()) {
									$tempExam = $exam->getAcronym()."(".$exam->getPracticeTestsOffered().")";
								}
								$examAcronyms[] = $tempExam;
							}
							if($course->getOtherEligibilityCriteria()){
								$examAcronyms[] = html_escape($course->getOtherEligibilityCriteria());
							}
							echo implode(' <span>|</span> ',$examAcronyms);
							?>
							</p></li>
					<?php } ?>
					<?php
						if(count($salientFeatures = $course->getSalientFeatures()) || count($classTimings = $course->getClassTimings())){
						?>
						<li><p><label>Salient Features:</label>
						<?php
							$salientArr = array();
							foreach($salientFeatures as $sf){
								$salientArr[] = langStr('feature_'.$sf->getName().'_'.$sf->getValue());
							}
							foreach($classTimings as $sf){
								$salientArr[] = langStr($sf);
							}
							echo implode(' <span>|</span> ',$salientArr);
						?>
						</p>
						</li>
						<?php
						}
					?>
				</ul>
				<div class="spacer10 clearFix"></div>
				<a class="see-all-link bld" href="<?=$course->getURL()?>">View Course Details <span class="sprite-bg"></span></a>
				<div class="spacer15 clearFix"></div>
				<?php if($course->isPaid()){ ?>
				<span>
					<h3 class="finalHeading_course<?=$course->getId()?>" style="margin-bottom:5px;display:none">
						<img border="0" src="/public/images/cn_chk.gif"> E-Brochure successfully mailed
					</h3>
				<button class="orange-button course<?=$course->getId()?>" onclick="makeResponse(<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>',<?=$course->getId()?>,'<?=base64_encode(html_escape($course->getName()))?>','showListingPageRecommendationLayer','LP_ ReqEBrochure_CourseTab','NULL');"  title="Request Free E-Brochure for <?=html_escape($course->getName())?>">Request Free E-Brochure <span class="btn-arrow"></span></button>
				</span>
				<script>
					courses.push(<?=$course->getId()?>);
				</script>
				<?php } ?>
			</div>
		<?php
				$id = "";
			}
		?>
		</div>
	</div>
	
	<div id="listing-right-col">
		<div id="rightWidget">
		</div>
	</div>
</div>
<div class="clearFix"></div>
<?php
	$this->load->view('listing/listingPage/listingFoot');
?>
<script>
(function($) {
	var allPanels = $('.accordion > div').hide();
	var allPanelsTop = $('.accordion > h5');
	$('.accordion > h5').click(function() {
		var currentClass = $(this).children('span').attr("class");
		if(currentClass.indexOf("opened-arrow") >= 0){
			return false;
		}
		allPanels.slideUp();
		$(this).next().slideDown();
		allPanelsTop.children('span').removeClass('opened-arrow').addClass('closed-arrow');
		$(this).children('span').removeClass('closed-arrow').addClass('opened-arrow');
		return false;
	});
	$('#first').trigger('click');
	$.each(courses,function(index,ele){
		disableAllCourseButtons(ele);
		});
})($j);
</script>
