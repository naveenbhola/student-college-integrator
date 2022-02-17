<ul class="tuple-cont shortListCont">
   <?php foreach($courseAndUnivObjs['courses'] as $courseId => $courseObj ){
	  $universityObject = $courseAndUnivObjs['universities'][$courseObj->getUniversityId()];
	  if(empty($universityObject))
	  {
		  continue;
	  }
	  
	  $isFirstCourseShotlistedByUser = TRUE;
	  $univPhotos = $universityObject->getPhotos();			
	  if(count($univPhotos)) {
		  $imgUrl = $univPhotos['0']->getThumbURL('172x115');
	  } else {
		  $imgUrl = SHIKSHA_HOME."/public/images/univDefault_172x115.jpg";
	  }
   ?>
	  <li class="clearwidth shortListedTuple" id ="shortListedListing_tupleId_<?php echo $courseObj->getId()?>">
		 <div class="tuple-box">
			<div class="flLt">
			<div class="tuple-image">
			   <a target="_blank" href="<?php echo $courseObj->getURL()?>"><img src="<?php echo $imgUrl?>" alt="<?php echo $universityObject->getName().", ".$universityObject->getLocation()->getCountry()->getName();?>" title="<?php echo $universityObject->getName()?>" align="center" width="172" height="115"/></a>
			   <div class ="tuple-shrtlist-image" >
			   <?php if($isFirstCourseShotlistedByUser) {?>
				  <i class="cate-sprite add-shrlst-icon"></i>
				  <p>Saved</p>
			   <?php }?>
			   </div>    
			</div>
			<?php 
			   $courseData = array( $courseObj->getId() => array(
						'desiredCourse' => ($courseObj->getDesiredCourseId()?$courseObj->getDesiredCourseId():$courseObj->getLDBCourseId()),
						'paid'		=> $courseObj->isPaid(),
						'name'		=> $courseObj->getName(),
						'subcategory'	=> $categoryData['subcategoryId']
						)
			   );
			   $brochureDataObj = array(
					  //'sourcePage'       => 'shortlist',
					  'courseId'         => $courseObj->getId(),
					  'courseName'       => $courseObj->getName(),
					  'universityId'     => $universityObject->getId(),
					  'universityName'   => $universityObject->getName(),
					  'destinationCountryId'	=> $universityObject->getLocation()->getCountry()->getId(),
					  'destinationCountryName'=> $universityObject->getLocation()->getCountry()->getName(),
					  'courseData'	      => base64_encode(json_encode($courseData))

				  );

			   if($catPageTitle == 'Saved Courses')
				 {
				 	$brochureDataObj['sourcePage'] = 'savedCoursesPage';
				 	 //$brochureDataObj['widget'] = 'savedCoursesPage_shortlistTab';
				 	$brochureDataObj['trackingPageKeyId'] = 411;	
				 }
				 else
				 {
				 	$brochureDataObj['sourcePage'] = 'category';
				 	// $brochureDataObj['widget'] = 'category_page_shortList_tab';
				 	$brochureDataObj['trackingPageKeyId'] = 417;	
				 }

			/*if($counsellorData[$universityObject->getId()] > 0){ ?>
			<!-- <div class="req-callbck">
			   <a href="Javascript:void(0)" onclick = "loadStudyAbroadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');" ><i class="cate-sprite req-callbck-icn"></i>Request a call back</a>
			</div> -->
			<?php }*/?>
			</div>
			<div class="shortlt-tp-detail" >
			   <div class="tuple-title shortlt-tp-title" style="margin-left:4px;">
				  <p><a target="_blank" href="<?php echo ($universityObject->getURL())?>"><?php echo htmlentities($universityObject->getName())?></a><span class="font-11">, <?php echo $universityObject->getLocation()->getCity()->getName()?>, <?php echo $universityObject->getLocation()->getCountry()->getName()?></span></p>
			   </div>
			   <div class="course-touple clearwidth">
				  <p class="tuple-sub-title" style="margin-left:4px;font-size:13px;"><a target="_blank" href="<?php echo $courseObj->getURL();?>"><?php echo htmlentities($courseObj->getName());?></a></p>
				  <div class="clearwidth">
					 <div class="uni-course-details clearwidth">
						<div class="detail-col flLt" style="width:125px;">
						<?php $fees = $courseObj->getTotalFees()->getValue();
							if($fees){
								$feesCurrency = $courseObj->getTotalFees()->getCurrency();
								$courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
								$courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
								$courseFees = str_replace("Lac","Lakh",$courseFees);
								?>
								 <strong>1st Year Total Fees</strong>
								 <p><?php echo $courseFees?></p>
							<?php }?>
										</div>
										<div class="detail-col flLt" style="width:102px;">
											<strong>Eligibility</strong>
							<?php	$examCount = 0;
							foreach($courseObj->getEligibilityExams() as $examObj){
							if($examObj->getId() == -1){continue;}
							if(++$examCount >= 3){continue;}
						?>
						   <p <?php if($examObj->getCutoff()=="N/A"){ echo " onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'"; } ?> style="position:relative;width:117px !important;">
							  <?php if($examObj->getCutoff()=="N/A"){ $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$examObj->getName())); } ?>
							  <?php echo htmlentities($examObj->getName())?>: <?php echo ($examObj->getCutoff()=="N/A")?"Accepted":$examObj->getCutoff()?>
						   </p>
						<?php }?>
						<?php	if($examCount>=3){?>
						   <a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
						   <div class="extra-exam-div" style="display: none">
						   <?php	$examCount = 0;
							  foreach($courseObj->getEligibilityExams() as $examObj){
							  if($examObj->getId() == -1){continue;}
							  if(++$examCount <= 2){continue;}
						   ?>
							  <p <?php if($examObj->getCutoff()=="N/A"){ echo " onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'"; } ?> style="position:relative;width:117px !important;">
								 <?php if($examObj->getCutoff()=="N/A"){ $this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>$examObj->getName())); } ?>
								 <?php echo htmlentities($examObj->getName())?>: <?php echo ($examObj->getCutoff()=="N/A")?"Accepted":$examObj->getCutoff()?>
							  </p>
						   <?php }?>
						  </div>
						<?php }?>
						</div>
						<div class="detail-col flLt" style="width:108px">
						   <?php if($universityObject->getTypeOfInstitute() == 'public'){?>
							   <p><span class="tick-mark">&#10004;</span>Public university</p>
						   <?php }else{?>
							   <p class="non-available"><span class="cross-mark">&times;</span>Public university</p>
						   <?php }?>
						   <?php if($courseObj->isOfferingScholarship()){?>
							   <p><span class="tick-mark">&#10004;</span>Scholarship</p>
						   <?php }else{?>
							   <p class="non-available"><span class="cross-mark">&times;</span>Scholarship</p>
						   <?php }?>
						   <?php if($universityObject->hasCampusAccommodation()){?>
							   <p><span class="tick-mark">&#10004;</span>Accomodation</p>
						   <?php }else{?>
							   <p class="non-available"><span class="cross-mark">&times;</span>Accomodation</p>
						   <?php }?>
						</div>
					 </div>
					 <div class="btn-col btn-brochure" style="<?php echo ($courseObj->getCourseApplicationDetail() > 0?'margin-top: 8px !important':'margin-top: 27px !important')?>">
					 <?php
					 $brochureWidgetTrackingName = ($thisPageBrochureInfo)?'shortlistPage':'category_page_shortList_tab';
					 //$brochureDataObj['sourcePage'] = 'category';
					 // $brochureDataObj['widget'] = $brochureWidgetTrackingName;
					 if($catPageTitle == 'Saved Courses')
					 {
					 	$brochureDataObj['sourcePage'] = 'savedCoursesPage';
					 	 $brochureDataObj['widget'] = 'savedCoursesPage_shortlistTab';
					 	$brochureDataObj['trackingPageKeyId'] = 409;	
					 	$brochureDataObj['consultantTrackingPageKeyId'] = 407;
					 }
					 else
					 {
					 	$brochureDataObj['sourcePage'] = 'category';
					 	 $brochureDataObj['widget'] = 'savedCoursesTab_shortlistTab';
					 	$brochureDataObj['trackingPageKeyId'] = 415;	
					 	$brochureDataObj['consultantTrackingPageKeyId'] = 418;
					 }

					 ?>
					 <a href="javascript:void(0)" class="button-style" onclick = "loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"></i><strong>Download Brochure</strong></a>
					 </div>
					 <?php
						if($brochureWidgetTrackingName == 'shortlistPage') // change the pagename for shortlist page (not for tab)
						{
						   $brochureDataObj['sourcePage'] = 'savedCoursesPage';
						   $brochureDataObj['pageTitle'] = "Saved Courses";
						}
						else{
						   $brochureDataObj['pageTitle'] = str_replace("in All Countries","Abroad",$catPageTitle);
						}
						// load rate my chance button
						if($courseObj->showRmcButton())
						{
						   $brochureDataObj['userRmcCourses'] = $userRmcCourses;
						   if($brochureDataObj['sourcePage'] == 'savedCoursesPage')
							{
								$brochureDataObj['trackingPageKeyId'] = 364;
							}
							else
							{
								$brochureDataObj['trackingPageKeyId'] = 362;	
							}
									
						   echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
						}
					 ?>
					<div class="compare-box flLt customInputs" style="margin-top:6px;">
						<?php
							$checkedStatus = '';
							if(in_array($courseObj->getId(),$userComparedCourses)){
								$checkedStatus = 'checked="checked"';
							}
							if($brochureDataObj['sourcePage'] == "category"){
								$trackingKeyForCompare = 548;
							}elseif($brochureDataObj['sourcePage'] == "savedCoursesPage"){
								$trackingKeyForCompare = 550;
							}
						?>
						<input type="checkbox" name="compare<?=$courseObj->getId()?>" id="compareShortlist<?=$courseObj->getId()?>" class="compareCheckbox<?=$courseObj->getId()?>" <?=$checkedStatus?>>
						<label class="compareCheckboxLabel<?=$courseObj->getId()?>" onclick="toggleCompare('<?=$courseObj->getId()?>',<?=$trackingKeyForCompare?>);">
							<span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
						</label>
					</div>
				  </div>

							
			   <?php $announcementObj = $universityObject->getAnnouncement();
				  if($announcementObj) {
					 $announcementText = $announcementObj->getAnnouncementText();
					 $announcementActionText = $announcementObj->getAnnouncementActionText();
					 $announcementStartDate = $announcementObj->getAnnouncementStartDate();
					 $announcementEndDate = $announcementObj->getAnnouncementEndDate();
					 $today = date("Y-m-d");
					 if($announcementText && $today >= $announcementStartDate && $today <= $announcementEndDate) {
							 ?>
							<div class="category-update-sec clearwidth">
								<strong>Announcement</strong>: <?php echo $announcementText?> <br/> <?php echo $announcementActionText?>
							</div>
			   <?php	}
				  } ?>
				   
				  <div class="flLt" style="width:83%">
				  <?php if(count($courseObj) > 1){?>
					 <a href="javascript:void(0)" onclick="showHideSimilarCourse(this)" class="smlr-course-btn"><i class="cate-sprite plus-icon"></i><?php echo count($courseObj)-1?> similar courses</a>
				  <?php } ?>
				  </div>
			   </div>
			   <?php 
			   if($brochureDataObj['sourcePage'] == 'category') { ?>
			   	<i class="cate-sprite <?php echo  $isFirstCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" onclick ="addRemoveFromShortlistedTab('<?php echo $courseObj->getId()?>','shortListedListing',this,'categoryPage',420);" title="Click to remove"></i>
				 <?php }elseif($brochureDataObj['sourcePage'] == 'savedCoursesPage') { ?>
				<i class="cate-sprite <?php echo  $isFirstCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" onclick ="addRemoveFromShortlistedTab('<?php echo $courseObj->getId()?>','shortListedListing',this,'savedCoursesPage',412);" title="Click to remove"></i>
				 <?php } ?>
			</div>
		 </div>
	  </li>

	  <?php 
	     $lastCourseId = $courseId; //after loop through course id of last tuple will be retained in this variable.
		}?>
</ul>
<?php
   $totalNoOfPages = ceil($courseAndUnivObjs['totalCount']/$noOfResultPerPage);
   if(!empty($userId) && 1 < $totalNoOfPages) {
?>
<a href="javascript:void(0)" class = "load-more-result clearwidth" onclick ="fetchShortlistedCourses('<?php echo $courseAndUnivObjs['rowIdOfLastTuple']?>','<?php echo ($catPageTitle)?>');$j(this).hide();"> Load more courses</a>
<?php } ?>

<script>
	function toggleCompare(courseId,source){
		addRemoveFromCompare(courseId,source);
	}
</script>
