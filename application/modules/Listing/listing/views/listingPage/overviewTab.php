<?php 
	$this->load->view('listing/listingPage/listingHead',array('tab' => 'overview', 'js' => array('caCoursePage','discussion','ana_common','CAValidations','user'), 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount,'instituteRep'=>$instituteRep));
	$courses = $institute->getCourses();
	$headerImages = array();
	foreach($institute->getHeaderImages() as $image){
		$tempImage = array();
		$tempImage['url'] =  $image->getFullURL();
		$headerImages[] = $tempImage;
	}
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_OVERVIEW_PAGE',1);
	
	if($pageType == 'course') {
		
		$coursepage_sub_cat_array = $googleRemarketingParams['subcategoryId'];
		foreach ($coursepage_sub_cat_array as $coursepage_subcat) {
			if(checkIfCourseTabRequired($coursepage_subcat)){
				$course_page_required_category = $coursepage_subcat;
				break;
			}
		}
	}
	
	if(strpos($mediaTabUrl,"?") === FALSE) {
			$cpgs_append  = '?cpgs='.$course_page_required_category;
		} else if(strpos($mediaTabUrl,"?") !==FALSE) {                     
				$cpgs_append  = '&cpgs='.$course_page_required_category;                   
	}	
	$mediaTabUrl = $mediaTabUrl.$cpgs_append;
?>
<?php 

$js_enabled = 1 ;
//$browser = get_browser(null, true);
//if(!empty($browser)){
//$js_enabled = $browser['javascript'];
//}

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = $.noConflict();
var courseIdForTracking = <?php echo $course->getId(); ?>;
var instituteIdForTracking = <?php echo $institute->getId(); ?>;
</script>

<div id="page-contents">
	<div id="listing-left-col">
		<div id="child-left">
			<?php    $videos = $institute->getVideos();
                        $photos = $institute->getPhotos();
                        ?>

			<?php
  				if($pageType=='institute'){
					$photoText = 'Photos of ';
				}else{
					$photoText = 'Images of ';
				}
				if(count($headerImages) > 0 && $headerImages[0]['url']){
			?>
			<div class="section-cont">
				<div class="big-pic">
			<?php if(count($photos) > 0 || count($videos) > 0){ ?><a href="<?php echo $mediaTabUrl;?>"><?php } ?>
				<img id="mainImage" width="289" height="201"  src="<?=$headerImages[0]['url']?>" alt="<?php echo $photoText.html_escape($institute->getName());?>&nbsp;<?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ")?><?=$currentLocation->getCity()->getName().', 01'?>" /></div>
			<?php if(count($photos) > 0 || count($videos) > 0){ ?></a><?php } ?>
				<div class="thumbnails">
					<?php
					if($headerImages[1]){
						if(count($photos) > 0 || count($videos) > 0){
							echo '<b class="mb-1" href="#">
                                                 <a href="'.$mediaTabUrl.'">
                                                 <img id="headerthumb1" width="119" height="100" src="'.$headerImages[1]['url'].'" alt="'.$photoText.html_escape($institute->getName()).' '.(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ").$currentLocation->getCity()->getName().', 02'.'"/>
                                                 </a>
                                                 </b>';
						}else{
							echo '<b class="mb-1" href="#">
                                                 <img id="headerthumb1" width="119" height="100" src="'.$headerImages[1]['url'].'" alt="'.$photoText.html_escape($institute->getName()).' '.(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ").$currentLocation->getCity()->getName().', 02'.'"/>
                                                 </b>';
						}	
					}
					?>
					<div class="spacer1 clearFix"></div>
					<?php
					if($headerImages[2]){
						if(count($photos) > 0 || count($videos) > 0){
							 echo '<b href="#">
                                                <a href="'.$mediaTabUrl.'">
                                                <img id="headerthumb2" width="119" height="100" src="'.$headerImages[2]['url'].'" alt="'.$photoText.html_escape($institute->getName()).' '.(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ").$currentLocation->getCity()->getName().', 03'.'"/>
                                                </a>
                                                </b>';
	
						}else{
							 echo '<b href="#">
                                                <img id="headerthumb2" width="119" height="100" src="'.$headerImages[2]['url'].'" alt="'.$photoText.html_escape($institute->getName()).' '.(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ").$currentLocation->getCity()->getName().', 03'.'"/>
                                                </b>';
			
						}
					}
					?>
				</div>
	                <p class="view-all-pic">
        		<?php if(count($photos) > 0){ ?><a href="<?=$mediaTabUrl?>">View all <?=count($photos)?> photos</a><?php } ?>
		        <?php if(count($videos) > 0 && count($photos) > 0){ ?> | <?php } ?>
		        <?php if(count($videos) > 0){ ?><a href="<?=$mediaTabUrl?>">View all <?=count($videos)?> videos</a><?php } ?>
			</p>

			</div>
			<?php
			}
			?>
			<div class="section-cont">
				<h4 class="section-title"><?=html_escape($course->getName())?> <br />
				<span>
					<?php
						echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; 
						echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():"");
						echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():"");
					?>
				</span></h4>
				<p class="sub-title">
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
                                         $form_sub_date = $course->getDateOfFormSubmission($currentLocation->getLocationId());
                                         $result_decl_date = $course->getDateOfResultDeclaration($currentLocation->getLocationId());
                                         $course_com_date = $course->getDateOfCourseComencement($currentLocation->getLocationId());
                                         if((!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00') || (!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00') || (!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00')):?>
                                         <li><p><label>Important Dates:</label>
                                        <?php
                                                     $dates_array = array();
                                                     if(!empty($form_sub_date) && $form_sub_date!='0000-00-00 00:00:00') {
                                                     	$dates_array[] = "Form Submission: ".date("d-m-y",strtotime($form_sub_date));
                                                     } 
                                                     if(!empty($result_decl_date) && $result_decl_date!='0000-00-00 00:00:00') {
							$dates_array[] = "Declaration of Results: ".date("d-m-y",strtotime($result_decl_date)); 
                                                     }
                                                     if(!empty($course_com_date) && $course_com_date!='0000-00-00 00:00:00') {
						     	$dates_array[] = "Course Commencement: ".date("d-m-y",strtotime($course_com_date));	
                                                     }
                                                     echo implode(' <span>|</span> ',$dates_array);
                                        
                                        ?> 
                                        </p></li>
                                        <?php endif;?>
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
				<div class="spacer5 clearFix"></div>
				<?php if($courseComplete->getDescriptionAttributes()){ ?>
				<strong>
					<a href="#" onclick="$j('body,html').animate({scrollTop:$j('#courseDesc').offset().top - 70},500);">View Course Details</a>
				</strong>
				<?php } ?>
				<div class="spacer10 clearFix"></div>
				<?php if($course->isPaid()){ ?>
				<div class="finalHeading_course<?=$course->getId()?>" style="margin-bottom:5px;display:none;font-weight:bold;font-size:14px;">
						<img border="0" src="/public/images/cn_chk.gif"> E-Brochure successfully mailed
				</div>
				<div class="clearFix"></div>
				<span class="flLt">
				<button class="orange-button course<?=$course->getId()?>" style="font-size:12px !important; margin-top:3px; border-radius:10px !important " onclick="makeResponse(<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>',<?=$course->getId()?>,'<?=base64_encode(html_escape($course->getName()))?>','showListingPageRecommendationLayer','LP_ ReqEBrochure_Top','NULL');" title="Request Free E-Brochure for <?=html_escape($course->getName())?>">Request Free E-Brochure <span class="btn-arrow"></span></button>
				</span>
				<span class="flRt" id="onlineFormButton"></span>
				<?php } ?>
			</div>
			<?php
				if(count($courses) > 1){
			?>
			<div class="section-cont">
				<div class="section-sub-title">Other Courses offered</div>
				<ul class="bullet-items">
					<?php
					$i = 0;
					foreach($courses as $c){
						if($c->getId() != $course->getId()){
							$i++;
							echo '<li><a href="'.$c->getUrl().'">'.html_escape($c->getName()).'</a></li>';
							if($i > 1){
								break;
							}
						}
					}
					?>
				</ul>
				<div class="spacer5 clearFix"></div>
				<?php
					if(count($courses) > 3){
				?>
				<strong><a href="<?=$courseTabUrl?>">View all courses</a></strong>
				<?php
					}
				?>
				<div class="spacer10 clearFix"></div>
			</div>
			<?php
				}
			?>
			<div class="section-cont" id="askWidget">&nbsp;</div>
		</div>
		
		<div id="child-right">
				<?php
					echo Modules::run('listing/ListingPageWidgets/placementCompanies', $course->getRecruitingCompanies());
					echo Modules::run('listing/ListingPageWidgets/salaryStatastics', $course->getSalary());
				?>
			
			<div id="alumniWidget">
				<?php echo Modules::run('listing/ListingPageWidgets/alumniSpeak',$institute->getId()); ?>
			</div>
			<?php 	//echo Modules::run('listing/ListingPageWidgets/mediaWidget',$institute->getPhotos(),$institute->getVideos(), $mediaTabUrl); ?>
			<?php
				if($instituteComplete->getJoinReason()->getDetails()){
			?>
			<div class="section-cont user-content course-details">
				<h4 class="section-cont-title">Why Join <?=html_escape($institute->getName())?></h4>
				<div class="big-img-cont">
					<?php
						if($instituteComplete->getJoinReason()->getPhotoUrl()){
					?>
						<img src="<?=$instituteComplete->getJoinReason()->getPhotoUrl()?>" alt="<?php echo $photoText.html_escape($institute->getName());?>&nbsp;<?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":", ")?><?=$currentLocation->getCity()->getName()?>" />
					<?php
						}
					?>
				</div>
					<?php
						if($instituteComplete->getJoinReason()->getDetails()){
							$summary = new tidy();
							$summary->parseString($instituteComplete->getJoinReason()->getDetails(),array('show-body-only'=>true),'utf8');
							$summary->cleanRepair();
					?>
					<div style="display: block; width: 255px; word-wrap: break-word;"><?=$summary?></div>
					<?php
						if($paid){
					?>
							<div class="spacer5 clearFix"></div><strong><a href="#" onclick="hideFloatingRegistration(); $j('body,html').animate({scrollTop:$j('#bottomWidget').offset().top - 70},500); return false;">Apply Now</a>&nbsp;&nbsp;&nbsp;&nbsp;</strong>
					
				<?php
						}
						}
				?>
			</div>
			<?php
				}
			?>
			
		</div>

                 <?php if($pageType == 'institute')
                                echo Modules::run('CA/CADiscussions/getAllCoursesTuplesForInstitute',$institute->getId(),$currentLocation->getLocationId());
                        else {
                                echo Modules::run('CA/CADiscussions/getCourseTuple',$course->getId(),$institute->getId(),'overview', base64_encode($campusRepTabUrl) );
                        		echo Modules::run('CA/CADiscussions/getCourseOverviewQnA',$course->getId(),$institute->getId(),$js_enabled,0);
				?>
			         <script>
			         var courseClientId = '<?=$course->getClientId();?>';
			         loadBadges();
			         </script>
				<?php
                        }
                 ?>


		<?php
			$this->load->view('listing/listingPage/overviewTabContent_horizontal');
		 ?>
		 <div class="desc-details-wrap">
		 <?php echo Modules::run('listing/ListingPageWidgets/seeAllBranches',$course); ?>
		 </div>
		<div class="desc-details-wrap">
			
			<div class="contact-detail-cont shadow-box">
				<?php
					if($currentLocation->getCountry()->getId() > 2)
					{
						$studyAbroad = 1;
					}
					else
					{
						$studyAbroad = 0;
					}
					//$lastUpdatedDate = $institute->getLastUpdatedDate();
					$lastUpdatedDate = ($pageType == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
					//$date6monthsBack = date("Y-m-d H:i:s",strtotime('-6 months'));
					$shikshaDataLastUpdated = date("Y-m-d H:i:s",strtotime(SHIKSHA_DATA_LAST_UPDATED));
					if ($lastUpdatedDate > $shikshaDataLastUpdated && $studyAbroad == 0)
					{
						echo Modules::run('listing/ListingPageWidgets/contactDetailsBottom',$institute, $course, $currentLocation);
					}
					else
						echo Modules::run('listing/ListingPageWidgets/contactDetails',$institute, $course,$currentLocation);
				?>
				<p>
					<span>
						<?php echo Modules::run('listing/ListingPageWidgets/seeAllBranches',$institute,FALSE,"onlylink"); ?>
					</span>
				</p>
			</div>
			
		</div>
		
		<div class="desc-details-wrap" id="bottomWidget">
			
		</div>
		
		<div class="section-cont" id="bottomAlsoOnShiksha">

		</div>
		
		<div class="section-cont" id="alsoOnShiksha">
		</div>
		
		<div class="section-cont" id="similarOnShiksha">
		</div>		
		<?php
		if(!empty($URLWidgetData['categoryPageURLs'])) {
		?>
		<div class="choice-list clear-width">
			<h2><?php echo $URLWidgetData['URLWidgetHeading']; ?></h2>
			<?php
			$viewNum = 0;
			$categoryPageURLs = $URLWidgetData['categoryPageURLs'];
			foreach($categoryPageURLs as $view => $links) {
				$viewNum++;
				$URLs = $links['URL'];
				$text = $links['text'];
				$tracking = $links['tracking'];
				$count = count($URLs) > 2 ? 2 : count($URLs);
				
				if($count > 0) {
					echo '<div class="flLt choice-box">';
					echo '<strong>'.$view.'</strong>';
					echo '<ul class="choice-course">';
					
					for($index = 0; $index < $count; $index++) {
						echo '<li><a href="'.$URLs[$index].'" onclick="processActivityTrack('.$course->getId().', 0, '.$institute->getId().', \'LP_CatPageLinks_Viewed\', \'LP_CatPageLinks\', \''.$tracking[$index].'\', \''.$URLs[$index].'\', event);">'.$text[$index].'</a></li>';
					}
					
					echo '</ul>';
					echo '</div>';
					
					if($viewNum % 2 == 0) {
						echo '<div class="clearFix"></div>';
					}
				}
			}
			?>
		</div>
		<?php
		}
		?>
		
		</div>
	
	<div id="listing-right-col">
                <?php
		$updatedTime =array();
		$lastUpdatedDate = ($pageType == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
		$updatedTime = explode(" ",$lastUpdatedDate);
		$updatedDate = explode("-",$updatedTime[0]);
		$updatedDateReadableFormat = array($updatedDate[2],$updatedDate[1],$updatedDate[0]);
		$updatedDate = implode("/",$updatedDateReadableFormat);
		if(trim($updatedDate,"/")!=""){ ?> <div id = "last_updated"> <?php echo "This information  was last updated on ".$updatedDate;?></div> <?php } 			?>
		<div id="rightWidget">
		</div>
		<div class="section-cont" id="rightAlsoOnShiksha">
		</div>
		<div class="section-cont" id="shikshaAnalytics" style="padding-bottom:0px;">
		</div>
		<?php
		if(!empty($rankingWidgetHTML)){
		?>
		 <div class="spacer20 clearFix"></div>
		<div id="rankingPageWidget" style="padding-bottom:10px;">
			<?php echo $rankingWidgetHTML; ?>
		</div>
		<?php
		}
		?>
		
		<div class="section-cont">
			<?php
				$bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
				$this->load->view('common/banner',$bannerProperties);
			?>
        </div>
		<?php /*  if($currentLocation->getCountry()->getId() != 2) { ?>
			<div class="section-cont">
					<?php $this->load->view('listing/widgets/ets_campaign');?>
			</div>
        <?php } */ ?>
        <div class="section-cont-title">Like us on Facebook</div>
        <div class="shadow-box">
        <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="265" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
        </div>

	</div>
</div>
<div class="clearFix"></div>
<?php
	$this->load->view('listing/listingPage/listingFoot');
	$shikshaDataLastUpdated = date("Y-m-d H:i:s",strtotime(SHIKSHA_DATA_LAST_UPDATED));
	if ($lastUpdatedDate > $shikshaDataLastUpdated && $studyAbroad == 0) { ?>
		<script>
			showHideSendContactDetailsButton('<?=$typeId?>', '<?=$pageType?>');
		</script>
	<?php }
?>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<div id="floatingRegister">
</div>
<script>
	rotateImages();
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("liteaccordion.jquery"); ?>" type="text/javascript"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>"></script>
<!--[if lt IE 9]>
	<script>
		document.createElement('figure');
		document.createElement('figcaption');           
	</script>
<![endif]-->
<script>
    var globalInstitute_id = '<?php echo $institute->getId();?>';
    var institute_name_cum_location = base64_encode('<?php echo html_escape($institute->getName()).','.html_escape($currentLocation->getCity()->getName()).','.html_escape($currentLocation->getState()->getName());?>');	
    var floatingRegistrationSource = 'LISTING_FLOATINGWIDGETREGISTRATION';
	(function($j) {
		//$j('#askWidget').load('/listing/Listing/getDataForAnAWidget/<?=$institute->getId()?>/institute/overview');
        	//$j('#askWidget').load('/CafeBuzz/ListingPageAnA/getDataForAnAWidget/<?=$institute->getId()?>');
		//$j('#alumniWidget').load('/listing/ListingPageWidgets/alumniSpeak/<?=$institute->getId()?>');
		$j('#shikshaAnalytics').load('/listing/ListingPageWidgets/shikshaAnalytics/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$pageType?>/'+ "?rand=" + (Math.random()*99999));
		//$j('#floatingRegister').load('/FloatingRegistration/FloatingRegistration/index/true/680/listing-right-col/<?=$institute->getId()?>/<?=($paid)?'true':'false'?>');
		//$j('#courseDesc').liteAccordion({theme : 'basic', rounded : true, containerWidth : 680,headerWidth: 30});
		//$j('#instituteDesc').liteAccordion({theme : 'basic', rounded : true, containerWidth : 680,headerWidth: 30});
		//if(!(getCookie('<?php echo "applied_".$course->getId()?>') == 1 && isUserLoggedIn)){
			//$j('#alsoOnShiksha').load('/listing/ListingPage/alsoOnShiksha/<?=$course->getId()?>');
			//$j('#similarOnShiksha').load('/listing/ListingPage/similarOnShiksha/<?=$course->getId()?><?php if(is_array($breadCrumb) && is_array($breadCrumb[0]) && $breadCrumb[0]['id']) { echo '/'.$breadCrumb[0]['id']; }; ?>');
		//}
		
		new Ajax.Request('/listing/ListingPage/alsoOnShiksha/<?=$course->getId()?>', { method:'post', parameters: '', onSuccess:function (request){
			alsoViewedResponse = eval('('+request.responseText+')');
			$j('#alsoOnShiksha').html(alsoViewedResponse.recommendationHTML);
			
			alsoViewedRecommendedInstitutes = alsoViewedResponse.recommendedInstitutes.join(',');
			
			//Commented out GA Tracking
			
			if (alsoViewedResponse.recommendedInstitutes.length) {
				//pageTracker._setCustomVar(1, "GATrackingVariable", 'LP_Reco_Load_AlsoViewed', 1);
				//pageTracker._trackPageview();
                                pushCustomVariable('LP_Reco_Load_AlsoViewed');
			}

			new Ajax.Request('/listing/ListingPage/similarOnShiksha/<?=$course->getId()?>/<?php echo intval($_REQUEST['city']); ?>/'+alsoViewedRecommendedInstitutes+'<?php if(is_array($breadCrumb) && is_array($breadCrumb[0]) && $breadCrumb[0]['id']) { echo '/'.$breadCrumb[0]['id']; }; ?>', { method:'post', parameters: '', onSuccess:function (srequest){
				similarResponse = eval('('+srequest.responseText+')');
				$j('#similarOnShiksha').html(similarResponse.recommendationHTML);
				
				//Commented out GA Tracking
				
				if (similarResponse.recommendedInstitutes.length) {
					//pageTracker._setCustomVar(1, "GATrackingVariable", 'LP_Reco_Load_SimilarInsti', 1);
					//pageTracker._trackPageview();
					pushCustomVariable('LP_Reco_Load_SimilarInsti');
				}
			}});
		}});
		
		/*Beacon*/
		var img = document.getElementById('beacon_img');
		var randNum = Math.floor(Math.random()*Math.pow(10,16));
		img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$typeId?>+<?=$pageType?>';
		disableAllCourseButtons(<?=$course->getId()?>,<?=$institute->getId()?>);
	})($j);  
</script>
<script>

var jsForWidget = new Array();
<?php if(($instituteRep['instituteRep']!='true' && $instituteRep['totalReps']<1) ||( $displayName=='' && $instituteRep['instituteRep']=='true')){?>
addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/index/true/680/listing-right-col/<?=$institute->getId()?>/<?=($paid)?'true':'false'?>/0/<?=$pageType?>/<?=$course->getId()?>/campus','floatingRegister',jsForWidget);
<?php }else{?>
addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/course/true/680/listing-right-col/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$currentLocation->getLocationId()?>/<?=($paid)?'true':'false'?>','floatingRegister',jsForWidget);
<?php } ?>
</script>
<script>
(function($) {
	var types = new Array("instituteDesc","courseDesc");
	var allPanels = new Array();
	var allPanelsTop  = new Array();
	var track = true;
	var name;
	$.each(types,function(index,type){
		allPanels[type] = $('.'+type+' > div').hide();
		allPanelsTop[type] = $('.'+type+' > h3');
		$('.'+type+' > div').each(function(index){
			if($(this).height() > 200){
				$(this).height(200);
				$(this).css('overflow-y','auto');
			}
		});
		$('.'+type+' > h3').click(function() {
			var currentClass = $(this).children('span').attr("class");
			if(currentClass.indexOf("opened-arrow") >= 0){
				return false;
			}
			allPanels[type].slideUp('fast');
			$(this).next().slideDown('fast',function(){
				if($(this).height() > 200){
					$(this).height(200);
					$(this).css('overflow-y','auto');
				}
				if(noScroll < 1){
					$('body,html').animate({scrollTop:$(this).prev().offset().top - 70},'fast');
				}else{
					noScroll--;
				}
			});
			
			allPanelsTop[type].children('span').removeClass('opened-arrow').addClass('closed-arrow');
			
			var trackClassBefore = $(this).children('span').attr("class");
			$(this).children('span').removeClass('closed-arrow').addClass('opened-arrow');
			var trackClassAfter = $(this).children('span').attr("class");
			
			if (trackClassBefore == 'sprite-bg closed-arrow' && trackClassAfter == 'sprite-bg opened-arrow' && track == true)
			{
				name = $(this).children('strong').attr("title");
				if(typeof pageTracker != "undefined")
				{
					pageTracker._trackEvent("LDP_WIKI", name, name);
				}
			}
			allPanelsTop[type].css('cursor','pointer');
			$(this).css('cursor','default');
			
			track = true;
			return false;
		});
		track = false;
		$('.'+type+' > h3:first').trigger('click');
	});

	noScroll = 2;
	
})($j);
</script>
