<?php 
	global $shiksha_site_current_url;global $shiksha_site_current_refferal ;

?>

<?php if(isset($_COOKIE['MOB_A_C'])){
	$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
?>

	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>

<!-- Display Institute List -->
<script> var pageType='';</script>
<?php 
	$courses_list;
	$count = 0;
	$i=0;
	$listings = array();
	
	foreach($institutes as $institute) {
		$i++; 
		$course = $institute->getFlagshipCourse();

                global $appliedFilters;
                if($request){
                      $appliedFilters = $request->getAppliedFilters();
                      $course->setCurrentLocations($request);
                }
                $displayLocation = $course->getCurrentMainLocation();
                $courseLocations = $course->getCurrentLocations();

                if(!$courseLocations || count($courseLocations) == 0){
                         $courseLocations = $course->getLocations();
                }
                if(!$displayLocation){
                         $displayLocation = $course->getMainLocation();
                }

		$courses = $institute->getCourses();
		$count++;
		$locations = $institute->getLocations();

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
                           $institute->setAdditionalURLParams($additionalURLParams);
                      }
                }

?>


		<section class="content-wrap2 <?php if($count==1 && $ajaxRequest!='true'){ echo "";} ?> clearfix" >

		<article id="shortlistedCourse_<?php echo $course->getId();?>" class="req-bro-box shortlist-box" style="cursor: pointer;" >
				<div class="details" >
				
				<div  class="comp-detail-item" onclick="setCookie('currentCourse','<?php echo $course->getId();?>','','');window.location='<?php echo $course->getURL(); ?>';">
					<h3 title="<?php echo html_escape($institute->getName()).', '  ?><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?>"><?php echo html_escape($institute->getName()); ?>,
						<span><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?>
						 
						 <!-- Display Country name in case of Study Abroad -->
						 <?php if(isset($isStudyAbroad) && $isStudyAbroad == "true"){
							echo ", ".$institute->getMainLocation()->getCountry()->getName();
						 }?>
						 </span>
					</h3>
				
					<ul>
						<li><?php echo html_escape($course->getName()); ?></li>

                                                <?php   $affiliations = $course->getAffiliations();
                                                        foreach($affiliations as $affiliation) {
                                                                $Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
                                                        }
                                                        if($Affiliations[0]){
                                                                echo "<li><i class='icon-medal'></i><p style='padding-top:1px'>";
								echo "<label>Affiliation: </label>";
                                                                echo $Affiliations[0];
                                                                echo "</p></li>";
                                                        }
                                                        unset($Affiliations);
                                                ?>


                                                <?php if($course->getFees()->getValue()){ ?>
                                                        <li><i class="icon-rupee"></i><p><label>Fees:</label> <?=$course->getFees()?></p></li>
                                                <?php } ?>


						<?php
						$exams = $course->getEligibilityExams();
						if(count($exams) > 0){
							if($institute->getInstituteType() == "Test_Preparatory_Institute"){
							?>
								<li><i class="icon-eligible"></i><p><label>Exams Prepared for: </label><?php
							}else{
							?>
								<li><i class="icon-eligible"></i><p><label>Eligibility: </label><?php
							}
							$examAcronyms = array();
							foreach($exams as $exam) {
								if($exam->getMarks() > 0){
                                                                          $examAcronyms[] = $exam->getAcronym() . "(" . $exam->getMarks() . ")";
                                                                 } else {
                                                                          $examAcronyms[] = $exam->getAcronym();
                                                                }
							}
							echo implode(', ',$examAcronyms); ?> </p></li>
						<?php } ?>
						
					</ul>
				</div>
					
		   			<div class="side-col" onClick="removeShortistedInstitutes('<?php echo $course->getId();?>')" ><strong>&times;</strong><br>Remove</a></div>	
				</div>
				
				<div id= "thanksMsg<?php echo $course->getId();?>" class="thnx-msg" <?php if(!in_array($course->getId(),$appliedCourseArr)){?>style="display:none"<?php } ?>>
			                <i class="icon-tick"></i>
			                <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			        </div>

				<?php
					$institute_id = $course->getInstId();
					$courseList =  Modules::run('mranking5/RankingMain/getCourses',$institute_id);
					if($courseList){
						$institute = reset($instituteRepository->findWithCourses(array($institute_id => $courseList)));
						$courses = $institute->getCourses();
					}

					$addReqInfoVars = array();
					foreach($courses as $c){
						$arr['isMultiLocation'.$institute_id] = $c->isCourseMultilocation();
						foreach($c->getLocations() as $course_location){
								$locality_name = $course_location->getLocality()->getName();
								if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
								$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($course->getInstituteName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
								if($arr['isMultiLocation'.$institute_id]=='false'){
									$arr['rebLocallityId'.$institute_id] = $course_location->getLocality()->getId();
									$arr['rebCityId'.$institute_id] = $course_location->getCity()->getId();
								}else{
									$arr['rebLocallityId'.$institute_id] = '';
									$arr['rebCityId'.$institute_id] = '';
								}
						}
					}
					$addReqInfoVars=serialize($addReqInfoVars);
					$addReqInfoVars=base64_encode($addReqInfoVars);
					$pageName = 'SHORTLIST_PAGE';
					$from_where = 'MOBILE5_SHORTLIST_PAGE';
				?>
				
                                
				<p>
				    <?php if(in_array($course->getId(),$appliedCourseArr)){?>
				    <a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
				    <?php }else{ ?>
				    <a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>" onClick="trackReqEbrochureClick('<?php echo $course->getId();?>');validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>');setCookie('hide_recommendation','yes',30);"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
				    <?php } ?>
				    
				    <!--Add-to-compare--->
		    
				    <?php
				    $data['instituteId'] = $institute->getId();
				    $data['courseId']    = $course->getId();
				    $data['isPaid'] = $course->isPaid();
				    $data['shortlistPageName'] = 'mobileshortlistPage';
				    $data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
				    $this->load->view('/mcommon5/mobileAddCompare',$data);
				    ?>
				    
				    <!--end-->
				</p>

				<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId()?>">
					<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
					<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" />
					<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
					<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
					<input type="hidden" name="list" value="<?php echo $course->getId(); ?>" />
					<input type="hidden" name="institute_id" value="<?php echo $institute_id; ?>" />
					<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
					<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
					<?php if (isset($tracking_keyid) && $tracking_keyid != '') {?><input type="hidden" name="tracking_keyid" value="<?php echo $tracking_keyid;?>" /><?php } ?>
				</form>
				<!-- Request Brochure ends -->


		    </article>
				    
		</section>
	
<?php } ?>
<!-- End Display Institute List -->

<script>
function trackReqEbrochureClick(courseId){
try{
	_gaq.push(['_trackEvent', 'HTML5_Shortlist_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}

</script>

