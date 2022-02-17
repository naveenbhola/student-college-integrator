<?php
global $exam_weightage_array;
$widgetData=array();
$numAvailableCourseWidgets = 0;
$eligibilities = $courseExamDetails;
$eligibilitiesArr = array();
//$GLOBALS['ENGINEERING_EXAMS_REQUIRED_SCORES'];
foreach($eligibilities as $k=>$v)
{ 
    if($eligibilities[$k]->getMarksType()=='percentile' && !in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
    {
	array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'%tile'));
    }
    else if($eligibilities[$k]->getMarksType()=='total_marks' && in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
    {
	array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'Marks'));
    }
}
//sort by exam priority order(weightage) 
usort($eligibilitiesArr,function($a, $b)
{
    return ($a['Weightage'] < $b['Weightage']);
});
$eligibilities = $eligibilitiesArr;
if(count($eligibilities)>0)
{
    
    $widgetData['eligibility'] = array('completeEligilibilityData'=>$eligibilities,
				       'examRequiredHTML'=>'');
    $exampage = new ExamPageRequest;
    foreach($eligibilities as $k=>$eligibility)
    {
		$exampage->setExamName($eligibility['Acronym']);
		$url = $exampage->getUrl();
		if(!in_array($exampage->getExamName(), $liveExamPages)){
			$url = "";
		}
		$exampage->reset();

		$examAcronym = $eligibility['Acronym'];
		if(!empty($url['url'])){
			$examAcronym = "<a href='".$url['url']."'>".$examAcronym."</a>";
		}
		if($abroadExamData!=false && is_array($abroadExamData) && array_key_exists($examAcronym, $abroadExamData)){
			$examAcronym = "<a target='blank' href='".$abroadExamData[$examAcronym]['contentURL']."'>".$examAcronym."</a>";
		}
		$widgetData['eligibility']['examRequiredHTML'] .= "<big>".$examAcronym." ".
								  ($eligibility['Percentile']>0?$eligibility['Percentile']:"")."</big> ".
								 ($eligibility['Percentile']>0 && $eligibility['Percentile']!="No Exam Required" ?$eligibility['MarksUnit']:"");
		if($k!=(count($eligibilities)-1))
		{
		    $widgetData['eligibility']['examRequiredHTML'] .= " <span class='sep-color'>|</span> ";
		}
    }
    $numAvailableCourseWidgets++;
}
else
{
    $widgetData['eligibility'] = 0;
}
//course ranking
if(!NEW_RANKING_PAGE && $course->course_ranking['course_rank'] > 0) {
    $widgetData['courseRank']   = array('rankValue'=>$course->course_ranking['course_rank'],
                                        'rankSuffix'=>ordinal($course->course_ranking['course_rank']),
					'rankingPage'=>$course->course_ranking['ranking_page_link'],
					'ranking_page_text'=>$course->course_ranking['ranking_page_text'],
					'city_name'=>$course->course_ranking['city_name']);
    $numAvailableCourseWidgets++;
} else if (NEW_RANKING_PAGE) {
	if(!empty($course->course_ranking)) {
		$widgetData['courseRank'] = $course->course_ranking;
	} else {
		$widgetData['courseRank'] = 0;
	}
	$numAvailableCourseWidgets++;
} else {
	$widgetData['courseRank'] = 0;
}

//get all important dates
    $dateOfFormSubmission           = $course->getDateOfFormSubmission($currentLocation->getLocationId());
    $dateOfResultDeclaration        = $course->getDateOfResultDeclaration($currentLocation->getLocationId());
    $dateOfCourseComencement        = $course->getDateOfCourseComencement($currentLocation->getLocationId());
	$numUpcomingDatesAvailable = 0;
if($dateOfFormSubmission=="0000-00-00 00:00:00" && $dateOfResultDeclaration=="0000-00-00 00:00:00" && $dateOfCourseComencement=="0000-00-00 00:00:00")
{
    $widgetData['importantDates'] = 0;
}
else
{
	$flag = 0;
    //keep in an array
    $widgetData['importantDates']   = array();
    if($dateOfFormSubmission!="0000-00-00 00:00:00" && $dateOfFormSubmission!="undefined"){
	$widgetData['importantDates']['dateOfFormSubmission']   = array('type'=>'Form Submission Date','dateString'=>$dateOfFormSubmission,'date'=>date_format(date_create($dateOfFormSubmission),"d"),'month_year'=>date_format(date_create($dateOfFormSubmission),"M'y"));
	//if(strtotime($dateOfFormSubmission)>=strtotime(date("Y-m-d"))){
	    $flag++;
	    $numUpcomingDatesAvailable++;
	//}
    }
    if($dateOfResultDeclaration!="0000-00-00 00:00:00" && $dateOfResultDeclaration!="undefined"){
	$widgetData['importantDates']['dateOfResultDeclaration']= array('type'=>'Declaration of Result','dateString'=>$dateOfResultDeclaration,'date'=>date_format(date_create($dateOfResultDeclaration),"d"),'month_year'=>date_format(date_create($dateOfResultDeclaration),"M'y"));
	//if(strtotime($dateOfResultDeclaration)>=strtotime(date("Y-m-d"))){
	    $flag++;
	    $numUpcomingDatesAvailable++;
	//}
    }
    if($dateOfCourseComencement!="0000-00-00 00:00:00" && $dateOfCourseComencement!="undefined"){
	$widgetData['importantDates']['dateOfCourseComencement']= array('type'=>'Course Commencement','dateString'=>$dateOfCourseComencement,'date'=>date_format(date_create($dateOfCourseComencement),"d"),'month_year'=>date_format(date_create($dateOfCourseComencement),"M'y"));
	//if(strtotime($dateOfCourseComencement)>=strtotime(date("Y-m-d"))){
	    $flag++;
	    $numUpcomingDatesAvailable++;
	//}
    }
    if($flag>0){
	$numAvailableCourseWidgets++;
    }
}
//get fees details
$feesObject = $course->getFees($currentLocation->getLocationId());
if($feesObject->getValue()>0){
    $widgetData['annualFees']['value'] = (int)$feesObject->getValue();
    global $fees_types_array;
    $feestypes = $feesObject->getFeesTypes();
    foreach($feestypes as $k=>$feestype)
    { $feestypes[$k] = ucfirst($feestype);}
    $included = array_intersect($feestypes,$fees_types_array);
    $notIncluded = array_diff($fees_types_array,$feestypes);
    foreach($notIncluded as $k=>$feestype)
    { $notIncluded[$k] = rtrim($feestype,'s');}
    
    $widgetData['annualFees']['breakup'] = array('included'=>$included,'notIncluded'=>$notIncluded);
    $widgetData['annualFees']['currency'] = $feesObject->getCurrency();
	$widgetData['annualFees']['disclaimer'] = $feesObject->getFeeDisclaimer() == 1 ? FEES_DISCLAIMER_TEXT : '';
    $numAvailableCourseWidgets++;
}

//get affiliations
$closedViewData = $course->getAffiliations();
$affiliationDataArr= array();
$affiliationDataArr['completeAffiliationData'] = $closedViewData ;
$affiliationDataArr['indianForeignFlag'] = 0;
$affiliationDataArr['deemedAutonomousFlag'] = 0;
foreach($closedViewData as $affiliation)
{
    if(in_array($affiliation[0],array('indian','foreign')))
    {	//univ name
	if($affiliationDataArr['closedText']==""){
	    $affiliationDataArr['closedText'] = $affiliation[1];
	    $affiliationDataArr['indianForeignFlag']++;
	}
	//break;//breaking here because preference is to be given to indian/foreign univ
    }
    else if(in_array($affiliation[0],array('deemed','autonomous')))
    {	//mention deemed/autonomous univ
	if($affiliationDataArr['closedText']==""){
	    if($affiliation[0]=="autonomous"){
		$affiliationDataArr['closedText'] = ucfirst($affiliation[0])." Institute";
	    }
	    else{
		$affiliationDataArr['closedText'] = ucfirst($affiliation[0])." University";
	    }
	}
	$affiliationDataArr['deemedAutonomousFlag']++;
	//break;
    }
}
$affiliationDataArr['openText'] = $course->getApprovals();
$widgetData['affiliation']      = $affiliationDataArr;
if(count($closedViewData)>0){
    $numAvailableCourseWidgets++;
}
if(!empty($widgetData['affiliation']['openText'])){
	$numAvailableCourseWidgets++;
}
//get salary details
$widgetData['salary']           = $course->getSalary();
if($widgetData['salary']['avg']==0&&$widgetData['salary']['min']==0&&$widgetData['salary']['max']==0)
{
    $widgetData['salary']=0;
}
else
{
    $numAvailableCourseWidgets++;
}
//get duration details(value,unit,coursetype(full time etc))
$duration         = array('Unit'=>$course->getDuration()->getDurationUnit(),
			  'Value'=>$course->getDuration()->getDurationValue(),
			  'courseType'=>$course->getCourseType());
if($duration['Value']==1 && $duration['Unit']!="Year")
{
    $duration['Unit']=substr($duration['Unit'],0,strlen($duration['Unit'])-1);
}
if($duration['Value']>0)
{
    $widgetData['duration']= $duration;
    $numAvailableCourseWidgets++;
}

$widgetData['recruitingCompanies'] = $course->getRecruitingCompanies();
$allowedTags = '<td><dt><dd><ul><li><br><table><colgroup><tbody><tr><td><img><ol><strong><em><span><a><textarea><iframe><cite><code><blockquote><h><b><i><small><sup><sub><ins><del><mark><kbd><samp><var><pre><abbr><address><bdo><blockquote><q><dfn>';
?>
<?php if($numAvailableCourseWidgets>0){ ?>
<div class="animation-list insitute-criteria-box other-details-wrap clear-width">
    <h2 style="margin-bottom: 10px;">Important Information</h2>
    
    
    
	<ul id = "adm-criteria-id">
	
	<?php if($widgetData['eligibility']!=0){ ?>
	<!-- exam required -->
		<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg degree-icon"></i></p>
			<p class="title-txt2">Exam Required</p>
			<p class="label-sep">-</p>
			<div class="criteria-content exam-req">
			    <?php echo $widgetData['eligibility']['examRequiredHTML'];?>
			</div>
		    </div>
		</li>
	<!-- exam required : END-->	
	<?php } ?>
	
	<?php if($widgetData['courseRank'] > 0 && $course->getId() != 128317 ){
		?>
	<!-- rank-->
	    <li>
		<div class="details">
		<p class="criteria-icn-box"><i class="sprite-bg institute-icon"></i></p>
		
		<p class="title-txt2">Course Rank</p>
		<p class="label-sep">-</p>
		<div class="criteria-content">
			<?php if(!NEW_RANKING_PAGE) { ?>
				<?php echo "<big>".$widgetData['courseRank']['rankValue']."</big>".$widgetData['courseRank']['rankSuffix']; ?>
				<a class = "font-12" href="<?php echo $widgetData['courseRank']['rankingPage']; ?>">View Top <?php echo $widgetData['courseRank']['ranking_page_text']?> institutes in <?php echo $widgetData['courseRank']['city_name']; ?> &gt;</a>
			<?php } else { ?>
				<ul class="course-rank-list">
					<?php foreach($widgetData['courseRank'] as $courseRank) { ?>
						<li>
							<p class="round-rank" onclick="window.location = '<?php echo $courseRank['ranking_page_url']; ?>'" style="margin-bottom:5px;cursor: pointer;"><?php echo $courseRank['rank']; ?></p>
							<a href="<?php echo $courseRank['ranking_page_url']; ?>" style="font-size:12px; line-height:16px;"><?php echo $courseRank['source_name'] ?></a>
						</li>
					<?php } ?>
				</ul>
				<!--a href="#" class="rank-view-link">View all 100 in Delhi &gt;</a-->
			<?php } ?>
		</div>
		</div>
	    </li>
	<!-- rank END -->
	<?php } ?>
	
	<?php if($numUpcomingDatesAvailable > 0){  ?>
	<!-- Imp Dates -->
		<li>
		    <div class="details">
		    <p class="criteria-icn-box"><i class="sprite-bg rank-icon"></i></p>
		    <p class="title-txt2">Important Dates</p>
		    <p class="label-sep">-</p>
		<div class="criteria-content">
		<?php $k=0;
		foreach($widgetData['importantDates'] as  $impDate) { 
		   // if(strtotime($impDate['dateString'])>=strtotime(date("Y-m-d"))){ 
			echo "<big>".$impDate['date']." ".$impDate['month_year']."</big> ";
			echo $impDate['type'];
			if($k!=count($widgetData['importantDates'])-1)
			echo '<span class="pipe">|</span>';
			$k++;
		   // }
		}
		?>
		    </div>
		    </div>
		</li>
	<!-- Imp Dates END -->
	<?php } ?>
	
	<?php if($widgetData['annualFees']['value'] > 0){ ?>
	<!-- Fees -->
		<li>
		    <div class="details">
		    <p class="criteria-icn-box"><i class="sprite-bg rupee-icon"></i></p>
			<p class="title-txt2">Total Fees <br><span class="inr-txt">(<?php echo $widgetData['annualFees']['currency']; ?>)</span></p>
			<p class="label-sep">-</p>
			<div class="criteria-content" style="line-height: normal;">
		<?php
			  //if($widgetData['annualFees']['value'] >= 100000)
		      //{
				//$feevalue =round(($widgetData['annualFees']['value']/100000),1);
				//$feeunit  = ($widgetData['annualFees']['value'] == 100000)?"Lac":"Lacs";
		      //}
		      //else
		      //{
				$feevalue = moneyFormatIndia($widgetData['annualFees']['value']);
				$feeunit  = "";
		      //}
		    ?>
		<?php echo "<big>".$feevalue."</big> ".$feeunit; ?>
				<span class="fees-disclaimer-text"><?php echo $widgetData['annualFees']['disclaimer']; ?></span>
		<?php if(count($widgetData['annualFees']['breakup']['included'])>0){ ?>
		</br><small>
		    <span class="gray-label">(</span><?php if(count($widgetData['annualFees']['breakup']['included'])>0){ //included fees text ?><span class="gray-label">Included:</span>
			    <?php foreach($widgetData['annualFees']['breakup']['included'] as $k=>$includedItem){	 ?>
				<?php
				    $includedHTML .=  $space.$includedItem." Fees".",";
				    $space = " ";
				?>
			    <?php }
			    $space = "";
			    echo rtrim($includedHTML,","); ?>
		    <?php } ?>
   		    <?php if(count($widgetData['annualFees']['breakup']['notIncluded'])>0){ //included fees text ?>
		    <span class="pipe">|</span>
		    <span class="gray-label">Not Included:</span>
    			    <?php
			    $space = "";
			    foreach($widgetData['annualFees']['breakup']['notIncluded'] as $k=>$notIncludedItem){ ?>
				<?php
				    $notIncludedHTML .=  $space.$notIncludedItem." Fees".",";
				    $space = " ";
				?>
			    <?php }
			    echo rtrim($notIncludedHTML,",");?>
		    <?php } ?><span class="gray-label">)</span>
		</small>
		<?php } ?>
		</div>
		</div>
		</li>
	<!-- Fees END-->	
	<?php } ?>
        <?php
	    if ($course->isSalaryTypeExist())// check for Placement and salary
		{?>
		<!-- salary statistics -->
		<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg salary-icon"></i></p>
			<p class="title-txt2">Campus Placement <br/><span style="font-size:10px;font-weight:normal;font-style:italic;">(Provided by <?php echo $collegeOrInstituteRNR;?>)</span></p>
			<p class="label-sep">-</p>
		        <div class="criteria-content">
			<?php $this->load->view('listing/national/listingSalaryStatsWidget',array('salary'=>$widgetData['salary'])); ?>
			</div>
		    </div>
		</li>
		<!-- salary statistics END-->
	<?php }?>
        <?php
	    if ($widgetData['recruitingCompanies'][0]->getName()!="")
		{?>
		<!-- Recruiting companies -->
		<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg placement-icon"></i></p>
			<p class="title-txt2" style="line-height:19px;">Recruiting Companies</p>
			<p class="label-sep">-</p>
		       
		       <div class="criteria-content">
			<?php $this->load->view('listing/national/listingCourseRecruitingCompanies',array('recruitingCompanies'=>$widgetData['recruitingCompanies'])); ?>
			</div>
		    </div>
		</li>
		<!-- Recruiting companies END-->
	<?php }?>
        <!-- COLLEGE REVIEWS STARTS-->
        <?php
        $courseReviewsCount = $courseReviews[$course->getId()]['overallRecommendations'];
        $totalCourseReviewsCount = count($courseReviews[$course->getId()]['reviews']);
            if($totalCourseReviewsCount > 0) { ?>
		
		<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg college-review-icon"></i></p>
			<p class="title-txt2" style="line-height:19px;">College Reviews</p>
			<p class="label-sep">-</p>
		       
		       <div class="criteria-content ">
			Average Alumni Rating: 
                        <div class="ranking-bg" style="float:none; display:inline-block; line-height:15px;">
                            <?php echo $courseReviews[$course->getId()]['overallAverageRating'];?><sub>/<?php echo $courseReviews[$course->getId()]['reviews'][0]['ratingParamCount'];?></sub>
                        </div>
                        <?php if($courseReviewsCount >= 5) { ?>
                        <div class="recommended-title">
                            <i class="sprite-bg thumb-up-icon" style="top:-2px!important;"></i>
                            <?php echo $courseReviews[$course->getId()]['overallRecommendations'];?> Students Recommend This Course
                        </div>
                        <?php } ?>
			</div>
		    </div>
		</li>
        <!-- COLLEGE REVIEWS END-->
	<?php }?>
	<?php
		if(!empty($widgetData['affiliation']['openText'])){
			?>
			<li>
				<div class="details">
					<p class="criteria-icn-box"><i class="sprite-bg recognition-icon"></i></p>
					<p class="title-txt2">Recognition</p>
					<p class="label-sep">-</p>
					<div class="criteria-content" style="line-height: normal;">
						<?php 
						    $approvalData ="";
						    foreach($widgetData['affiliation']['openText'] as $approval){
								$approvalData .= strtoupper($approval)." Approved, ";
							}
							if($approvalData != "") {
								echo "<big>".rtrim(rtrim($approvalData),",")."</big>";
							}
						?>
					</div>
				</div>
			</li>
			<?php
		}
		if(!empty($widgetData['affiliation']['closedText'])){
			?>
			<li>
				<div class="details">
					<p class="criteria-icn-box"><i class="sprite-bg affiliation-icon"></i></p>
					<p class="title-txt2">Course Status</p>
					<p class="label-sep">-</p>
					<div class="criteria-content" style="line-height: normal;">
						<?php 
							$affiliationData="";
						    foreach($widgetData['affiliation']['completeAffiliationData'] as $k=>$univs){
								if($univs[1]!=""){
								    $affiliationData .= $univs[1];
							}
							if(!empty($affiliationData)){
								$affiliationData = "Affiliated to ".$affiliationData;
							}
							if(in_array($univs[0],array('deemed','autonomous'))){
							    if($univs[0] == 'deemed'){
									$affiliationData .= ucfirst($univs[0])." University";
							    }
							    else{
									$affiliationData .= ucfirst($univs[0])." Institute";
							    }
							}
							if($k!=(count($widgetData['affiliation']['completeAffiliationData'])-1))
								$affiliationData .= '<span class = "pipe">|</span>';
						    } 
						    if(!empty($affiliationData)){
								echo "<big>".$affiliationData."</big><br/>";	
							}
						?>
					</div>
				</div>
			</li>
			<?php
		}
	?>
	<?php if(count($widgetData['duration']['Value']) > 0 ){ ?>
	<!-- Duration -->
		<li>
		    
		    <div class="details">
		    <p class="criteria-icn-box"><i class="sprite-bg duration-icon"></i></p>
		    <p class="title-txt2">Duration</p>
		    <p class="label-sep">-</p>
		   
		   <div class="criteria-content">
		    <?php echo "<big>".$widgetData['duration']['Value']; ?> <?php echo $widgetData['duration']['Unit'].($widgetData['duration']['Value']>1&&$widgetData['duration']['Unit']=="Year"?"s":"")."</big>"; ?>
		    <?php echo $widgetData['duration']['courseType']; ?>
		    </div>
		</div>
		</li>
	<!-- Duration END-->
	<?php } ?>
	<?php if (! empty ( $wikisData ['Eligibility'] )) {
		$isEligibilityVisitOnTop = TRUE;
		$summary = new tidy();
		$summary->parseString (strip_tags($wikisData['Eligibility'],$allowedTags), array ('show-body-only' => true ), 'utf8' );
		$summary->cleanRepair();
		?>
	<!-- eligibility -->
		<li>
		    
		    <div class="details">
		    <p class="criteria-icn-box"><i class="sprite-bg done-icon"></i></p>
		    <p class="title-txt2">Eligibility</p>
		    <p class="label-sep">-</p>
		   
		   <div class="criteria-content tiny-content">
			<div class="bubble-box-details scrollbar2 scrollbar1" id="scrollbar_eligibility">
				<div class="scrollbar">
					<div class="track">
						<div class="thumb">
							<div class="end"></div>
						</div>
					</div>
				</div>
				<div class="viewport_h viewport" style="height: 120px; width: 490px;">
					<div class="overview_h overview">
						<?=$summary?>
					</div>
				</div>
				<div class="scrollbar_h">
					<div class="track_h">
						<div class="thumb_h">
							<div class="end_h"></div>
						</div>
					</div>
				</div>	
			</div>
		    </div>
		</div>
		</li>
	<!-- eligibility END-->
	<?php } ?>
	<?php  
	if ( !empty($wikisData['Admission Procedure'])) //admission procedure available 
		{
			$isAdmissionProcedureOnTop = TRUE;
			$summary = new tidy();
			$summary->parseString (strip_tags($wikisData['Admission Procedure'],$allowedTags), array ('show-body-only' => true ), 'utf8' );
			$summary->cleanRepair();?>
		<!-- admission procedure -->
		<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg procedure-icon"></i></p>
			<p class="title-txt2">Admission Procedure</p>
			<p class="label-sep">-</p>
		       
		       <div class="criteria-content tiny-content">
			    <div class="bubble-box-details scrollbar2 scrollbar1" id="scrollbar_admissionProcedure">
				    <div class="scrollbar">
					    <div class="track">
						    <div class="thumb">
							    <div class="end"></div>
						    </div>
					    </div>
				    </div>			
				    <div class="viewport viewport_h" style="height: 120px; width: 490px;">
					    <div class="overview_h overview">
						    <?=$summary?>
					    </div>
				    </div>
				    <div class="scrollbar_h">
					    <div class="track_h">
						    <div class="thumb_h">
							    <div class="end_h"></div>
						    </div>
					    </div>
				    </div>	
			    </div>
			</div>
		    </div>
		</li>	
		<!-- admission procedure END -->	
	<?php } ?>
	
	</ul>
</div>
<?php } ?> 
<!-- <script>
    var count =0;
    var numOfSlides = <?php echo $numAvailableCourseWidgets; ?>;
//$j(document).ready(function(){$j(".soft-scroller").tinyscrollbar();}); ..moved to courseFooter

function slideUsingBullet(bulletID)
{
    
    $j(".slideBulletLink").addClass('active');
    $j("#"+bulletID).removeClass('active');
    var slideWidth= 161;
    if (bulletID == 'slideBullet1')
    {	
	$j("#slide-next").css('cursor','pointer');
	$j("#slide-prev").css('cursor','default');
	$j("#slide-next i").addClass('next-active');
	$j("#slide-prev i").removeClass('prev-active')
        $j('#adm-criteria-id').animate({left:0+'px'},700);
        count = 0;
	if ($j(".widget-box").length>=4) {	    
	    var fourthMouseOver = $j(".widget-box").eq(3).attr('class').split(' ')[0];
	    $j("#mask-"+fourthMouseOver).css('left','-155px');
	}
    }
    else
    {	
	var shift = -slideWidth * (numOfSlides-4) + 3;	
	$j("#slide-prev").css('cursor','pointer');
	$j("#slide-next").css('cursor','default');
	$j("#slide-prev i").addClass('prev-active');
	$j("#slide-next i").removeClass('next-active');
	$j('#adm-criteria-id').animate({left:shift+'px'},700);
        count = -(numOfSlides-4);
	
	if ($j(".widget-box").length >=4) {	    
	    var fourthMouseOver = $j(".widget-box").eq(3).attr('class').split(' ')[0];
	    $j("#mask-"+fourthMouseOver).css('left','0px');
	    var lastMouseOver = $j(".widget-box").eq($j(".widget-box").length-1).attr('class').split(' ')[0];
	    $j("#mask-"+lastMouseOver).css('left','-156px');
	}

    }
    
}
function trackGAListingCourseWidget(div,uniqueAttribute) {
    if(typeof($j) == 'undefined') {
	return false;
    }
    pushCustomVariable(uniqueAttribute);
}

var lis;
function timedAutoHover(){
    if(typeof($j) == 'undefined') {
	return false;
    }
    //position shift for last in slide
    //$j(".mousehover-box").eq(3).css('left','-155px');
    if ($j(".widget-box").length>=4) {
	var fourthMouseOver = $j(".widget-box").eq(3).attr('class').split(' ')[0];
	$j("#mask-"+fourthMouseOver).css('left','-155px');
    }
    
    var lastMouseOver = $j(".widget-box").eq($j(".widget-box").length-1).attr('class').split(' ')[0];
    if ($j(".widget-box").length >=4) {
	$j("#mask-"+lastMouseOver).css('left','-155px');
    }
    window.clearInterval(intervalID);
}
var global_count=-1;
var animateFlag= false;
var intervalID = setInterval(timedAutoHover,2000);
</script>-->
