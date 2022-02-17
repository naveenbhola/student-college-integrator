<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
if(isset($_COOKIE['MOB_A_C'])){

	$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);

}?>   
<?php

if (getTempUserData('confirmation_message')){?>
<section class="top-msg-row">
        <div class="thnx-msg">
            <i class="icon-tick"></i>
 	    <p><?php echo getTempUserData('confirmation_message'); ?></p>
        </div>
</section>
<?php } 
   deleteTempUserData('confirmation_message_ins_page');
   deleteTempUserData('confirmation_message');
?>

<script>

</script>
<div class="inst-detail-list" id="courseTabDesc" data-enhance="false" >


   <?php
   $this->load->builder('ListingBuilder','listing');
   $listingBuilder = new ListingBuilder;
   $courseRepository = $listingBuilder->getCourseRepository();
   $courses=$institute->getCourses();$i=0;$j=0;
      foreach($courses as $course){
	$courseObj = $courseRepository->find($course->getId());
      		if($i>0){
				$hideDivStyling = "display:none";
				$classIcon="icon-arrow-up";
			}
			else{
				$classIcon="icon-arrow-dwn";
			//	$openClass = "icon-arrow-dwn";
			}
	?>
     
		<dt id="level_<?php echo $courseObj->getCourseLevel()?>_catId_<?php echo $category['categoryIdList'][$courseObj->getId()].'_count_'.$i.'_';?>">
		  <a onClick="setAccordion('<?=$i?>');" href="javascript:void(0);">
		   		<h2><p><?=html_escape($courseObj->getName());?></p></h2>
		   		 <i id="desc<?=$i;?>" class="<?=$classIcon;?>"></i>
		   </a>
		</dt>            
	 
	 
	    <dd style="height:auto;<?=$hideDivStyling?>" id="2_level_<?php echo $courseObj->getCourseLevel()?>_catId_<?php echo $category['categoryIdList'][$courseObj->getId()].'_count_'.$i.'_';?>">
             
	     <div class="notify-details" >
		<div class="comp-detail-item">
		<div onclick="window.location.href='<?php echo $courseObj->getURL();?>'">
                <p>
                     <?php
			      echo $courseObj->getDuration()->getDisplayValue()?$courseObj->getDuration()->getDisplayValue():""; 
			      echo ($courseObj->getDuration()->getDisplayValue()&&$courseObj->getCourseType())?", ".$courseObj->getCourseType():($courseObj->getCourseType()?$courseObj->getCourseType():"");
			      echo ($courseObj->getCourseLevel()&&($courseObj->getCourseType()||$courseObj->getDuration()->getDisplayValue()))?", ".$courseObj->getCourseLevel():($courseObj->getCourseLevel()?$courseObj->getCourseLevel():"");
		     ?>
                </p>

                <em>
                     <?php
			      $approvalsAndAffiliations = array();
			      $approvals = $courseObj->getApprovals();
			      foreach($approvals as $approval) {
				      $approvalsAndAffiliations[] = langStr('approval_'.$approval);
			      }
			      $affiliations = $courseObj->getAffiliations();
			      foreach($affiliations as $affiliation) {
				      $approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
			      }
			      echo implode(', ',$approvalsAndAffiliations);
					?>
                </em>
                <ul class="bullet-item">
                    
                     <?php
			    if($accredited = $courseObj->getAccredited()){?>
                            <li>                   
                                               Accrediation:
                                               <span><?=html_escape($courseObj->getAccredited()); ?></span>
                           </li>
                    <?php
			    }
		     ?>
                     
                     <?php
			    if($courseObj->getFees($currentLocation->getLocationId())->getValue($currentLocation->getLocationId())){ ?>
			    <li>
					  Fees: <span> <?=$courseObj->getFees($currentLocation->getLocationId())?><span>
			    </li>
                     <?php
                            }
                     ?>
                     
                     <?php
			    if($courseObj->getTotalSeats() || $courseObj->getManagementSeats() || $courseObj->getGeneralSeats() || $courseObj->getReservedSeats()){?>
			    <li>
			       Seats: <span>
					     <?php
						     $seatsArray = array();
						     if($courseObj->getTotalSeats()){
							     $seatsArray[] = "Total - ".$courseObj->getTotalSeats();
						     }
						     if($courseObj->getGeneralSeats()){
							     $seatsArray[] = "General - ".$courseObj->getGeneralSeats();
						     }
						     if($courseObj->getManagementSeats()){
							     $seatsArray[] = "Management - ".$courseObj->getManagementSeats();
						     }
						     if($courseObj->getReservedSeats()){
							     $seatsArray[] = "Reserved - ".$courseObj->getReservedSeats();
						     }
						     echo implode('<i> | </i>',$seatsArray);
					     ?>
			            </span>
			    </li>
                     <?php
                                     }
                     ?>
					
                     <?php
			    $exams = $courseObj->getEligibilityExams();
			    if(count($exams) > 0 || $courseObj->getOtherEligibilityCriteria()){ ?>
			    <li>
                                   <?php
                                           if($institute->getInstituteType() == "Test_Preparatory_Institute"){
                                   ?>
                                                   Exams Prepared for: 
                                   <?php
                                           }else{
                                   ?>
                                                   Eligibility: 
                                   <?php
                                           }
							$examAcronyms = array();
							$exampage = new ExamPageRequest;
							foreach($exams as $exam) {
								$tempExam = $exam->getAcronym();
								$exampage->setExamName($tempExam);
								$url = $exampage->getUrl();
								if(!in_array($exampage->getExamName(), $liveExamPages)){
									$url = "";
								}
								$exampage->reset();
								if($exam->getMarks()){
									$tempExam .= " - ".$exam->getMarks()." ".titleCase(str_replace("_"," ",$exam->getMarksType()));
								}
								if($exam->getPracticeTestsOffered()) {
									$tempExam = $exam->getAcronym()."(".$exam->getPracticeTestsOffered().")";
								}
								if(!empty($url['url'])){
                                                                        $tempExam = "<a href='".$url['url']."'>".$exam->getAcronym()."</a>";
                                                                }
								$examAcronyms[] = $tempExam;
							}
							if($courseObj->getOtherEligibilityCriteria()){
								$examAcronyms[] = html_escape($courseObj->getOtherEligibilityCriteria());
							}
							?>
                            <span style="line-height: 22px;">
                            <?php
							echo implode(' <i>|</i> ',$examAcronyms);
							?>
							</span></li>
					<?php } ?>
                   
                    
                    <?php
                            if(count($salientFeatures = $courseObj->getSalientFeatures()) || count($classTimings = $courseObj->getClassTimings())){
                            ?>
                            <li>Salient Features:
                                   <span>
                                   <?php
                                           $salientArr = array();
                                           foreach($salientFeatures as $sf){
                                                   $salientArr[] = langStr('feature_'.$sf->getName().'_'.$sf->getValue());
                                           }
                                           foreach($classTimings as $sf){
                                                   $salientArr[] = langStr($sf);
                                           }
                                           echo implode(' <i>|</i> ',$salientArr);
                                   ?>
                                   </span>
                            </li>
                            <?php
                            }
             ?>              
                </ul>
		</div>

		</div>
	        <!----shortlist-course---->
		<?php
		$data['courseId'] = $courseObj->getId();
		$data['pageType'] = 'mobileCourseDetailPage';
		$data['tracking_keyid'] = $shortlistTrackingPageKeyId;
		$this->load->view('/mcommon5/mobileShortlistStar',$data);
		?>
		<!-----end-shortlist------>
				
		<div id= "thanksMsg<?php echo $courseObj->getId();?>" class="thnx-msg" <?php if(!in_array($courseObj->getId(),$appliedCourseArr)){?>style="display:none"<?php } ?>>
			                <i class="icon-tick"></i>
			                <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
	    </div>
	  <?php
			$addReqInfoVars = array();
			//foreach($courses as $c){
				if(checkEBrochureFunctionality($courseObj)){
					$arr['isMultiLocation'.$institute->getId()] = $courseObj->isCourseMultilocation();
                                        foreach($courseObj->getLocations() as $course_location){
                                                        $locality_name = $course_location->getLocality()->getName();
                                                        if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
                                                        $addReqInfoVars[$courseObj->getName().' | '.$course_location->getCity()->getName().$locality_name]=$courseObj->getId()."*".html_escape($institute->getName())."*".$courseObj->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							if($arr['isMultiLocation'.$institute->getId()]=='false'){
								$arr['rebLocallityId'.$institute->getId()] = $course_location->getLocality()->getId();
								$arr['rebCityId'.$institute->getId()] = $course_location->getCity()->getId();
							}else{
								$arr['rebLocallityId'.$institute->getId()] = '';
								$arr['rebCityId'.$institute->getId()] = '';
                                        }
					}		
				}
		//	}
					$addReqInfoVars=serialize($addReqInfoVars);
					$addReqInfoVars=base64_encode($addReqInfoVars);
		?>
<?php if(checkEBrochureFunctionality($course)){?>
                <p>
			<?php if(in_array($courseObj->getId(),$appliedCourseArr)){?>
				<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$courseObj->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
				<?php }else{ ?>
				<a class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$courseObj->getId();?>" onClick="trackReqEbrochureClick('<?=$courseObj->getId();?>');validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $courseObj->getId();?>','','<?php echo $coursedTrackingPageKeyId;?>'); setCookie('hide_recommendation','yes',30);"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
				<?php } ?>
				
			<!--Add-to-compare--->
			
			<?php
			$data['instituteId'] = $institute->getId();
			$data['courseId']    = $courseObj->getId();
			$data['isPaid'] = $courseObj->isPaid();
			$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
			$this->load->view('/mcommon5/mobileAddCompare',$data);
			?>
			
			<!--end-->
		</p>
           <form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$courseObj->getId();?>">
				<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
				<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
				<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
				<input type="hidden" name="selected_course" value = "<?php echo $courseObj->getId(); ?>" />
				<input type="hidden" name="list" value="<?php echo $courseObj->getId(); ?>" />
                                <input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
                                <input type="hidden" name="pageName" value="COURSE_DETAIL_PAGE_OTHER" />
				<input type="hidden" name="from_where" value="MOBILE5_COURSE_DETAIL_PAGE_OTHER" />
				<input type="hidden" name='tracking_keyid' id='tracking_keyid<?=$courseObj->getId();?>' value=''>
		</form>
	     <?php } ?>
        </div>
 </dd>

       <?php $j++; $i++;
       unset($courseObj);
       } ?>
</div>

<script>
var totalCount = <?=$j?>;
function setAccordion(indexVal){
	if(totalCount>1){
		//Hide all the other courses
		$("dd[id*='_count_']").hide();
	
		//Change the Class of all the other courses
		for(i=0;i<totalCount;i++)	{
			$('#desc'+i).attr('class', 'icon-arrow-up');		
		}
	
		//Display the clicked Course
		id="count_"+indexVal + '_';
		 $("dd[id*='"+id+"']").show();
		
		//Change the Class of the Clicked Course
		$('#desc'+indexVal).attr('class', 'icon-arrow-dwn');
		
		//Set the clicked CourMBA - Financial Management
		courseToBeInFocus = parseInt(indexVal)-1;	
		if($("dt[id*='count_"+courseToBeInFocus+"']")[0]){
			$("dt[id*='count_"+courseToBeInFocus+"']")[0].scrollIntoView();
		}
		else{
			$("dt[id*='count_"+parseInt(indexVal)+"']")[0].scrollIntoView();
		}
	}
}
function trackReqEbrochureClick(courseId){
		try{
		_gaq.push(['_trackEvent', 'HTML5_CourseListing_Request_Ebrochure', 'click', courseId]);
		}catch(e){}
}


</script>
	    
