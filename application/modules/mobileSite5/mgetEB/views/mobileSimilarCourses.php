<?php 
	$shiksha_site_current_url = SHIKSHA_HOME."/getEB/showSearchHome";
	
?>

<?php if(isset($_COOKIE['MOB_A_C'])){
	$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
?>
<!-- Display Institute List -->
<script> var pageType='';</script>
<?php 
	$courses_list;
	$count = 0;
	$listings = array();
	
	foreach($institutes as $institute) {
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
			<?php if($count==1){ ?>
			<h2 class="ques-title">
			    <p>
			     Institutes similar to <?=htmlspecialchars_decode(base64_decode($_COOKIE['getEBInstituteName']))?>:
			   </p>
			</h2>
			<?php } ?>

		<article class="req-bro-box clearfix" style="cursor: pointer;" >

				<div class="details"  onclick="setCookie('currentCourse','<?php echo $course->getId();?>','','');window.location='<?php echo $institute->getURL(); ?>';">
					<h4 title="<?php echo html_escape($institute->getName()).' , '  ?><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?>"><?php echo html_escape($institute->getName()); ?>,
						<span><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?>
						 
						 <!-- Display Country name in case of Study Abroad -->
						 <?php if(isset($isStudyAbroad) && $isStudyAbroad == "true"){
							echo ", ".$institute->getMainLocation()->getCountry()->getName();
						 }?>
						 </span>
					</h4>
				
					<ul>
						<li><?php echo html_escape($course->getName()); ?></li>

						<?php
						$parameterShown = false;
						$exams = $course->getEligibilityExams();
						$cutoff = '';
						foreach($exams as $exam) {
							$examName = $exam->getAcronym();
							$marks = $exam->getMarks();
							if($marks != 0) {
								$cutoff .= $examName.' ('.$marks.'), ';
							}
						}
						$cutoff = substr($cutoff, 0, -2);
		
		
						if(count($exams) > 0){
							$parameterShown = true;
							if($institute->getInstituteType() == "Test_Preparatory_Institute"){
							?>
								<li><p style="margin: 0px;"><label>Exams Prepared for: </label><?php
							}else{
							?>
								<li><p style="margin: 0px;"><label>Eligibility: </label><?php
							}
							echo $cutoff ?> </p></li>
						<?php } ?>

						
                                                <?php if($course->getFees()->getValue() && !$parameterShown){
							$parameterShown = true; ?>
                                                        <li><p style="margin: 0px;"><label>Fees:</label> <?=$course->getFees()?></p></li>
                                                <?php } ?>


                                                <?php if($course->getRanking()->__toString() && !$parameterShown){
							$parameterShown = true; ?>
                                                        <li><p style="margin: 0px;"><label>Ranking:</label> <?=$course->getRanking()?></p></li>
                                                <?php } ?>

                                                <?php if($course->getDuration()->__toString() && !$parameterShown){
							$parameterShown = true; ?>
                                                        <li><p style="margin: 0px;"><label>Duration:</label> <?=$course->getDuration()?></p></li>
                                                <?php } ?>

                                                <?php if($course->getCourseType() && !$parameterShown){
							$parameterShown = true; ?>
                                                        <li><p style="margin: 0px;"><label>Mode:</label> <?=$course->getCourseType()?></p></li>
                                                <?php } ?>

					</ul>
				</div>
				<div id= "thanksMsg<?php echo $course->getId();?>" class="thnx-msg" <?php if(!in_array($course->getId(),$appliedCourseArr)){?>style="display:none"<?php } ?>>
			                <i class="icon-tick"></i>
			                <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			        </div>


		<?php
			$addReqInfoVars = array();
			foreach($courses as $c){
				if($c->isPaid()=="TRUE" || true){
					$arr['isMultiLocation'.$institute->getId()] = $c->isCourseMultilocation();
					foreach($c->getLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							if($arr['isMultiLocation'.$institute->getId()]=='false'){
								$arr['rebLocallityId'.$institute->getId()] = $course_location->getLocality()->getId();
								$arr['rebCityId'.$institute->getId()] = $course_location->getCity()->getId();
							}else{
								$arr['rebLocallityId'.$institute->getId()] = '';
								$arr['rebCityId'.$institute->getId()] = '';
							}
					}		
				}
			}
			$addReqInfoVars=serialize($addReqInfoVars);
			$addReqInfoVars=base64_encode($addReqInfoVars);
		?>

			<?php if($course->isPaid()=="TRUE" || true){?>
			    <p>
				<?php if(in_array($course->getId(),$appliedCourseArr)){?>
				<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
				<?php }else{ ?>
				<a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>" onClick="trackReqEbrochureClick('<?php echo $course->getId();?>');validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>');"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
				<?php } ?>
			    </p>
			    <!--<div class="shortlist"><i class="icon-heart" aria-hidden="true"></i><span>Shortlist</span></div>-->
			<!--<input class="brochure-btn orange-button" type="submit" value="Request E-brochure" />-->
			<?php 
				$pageName = 'SEARCH_PAGE';
                                $from_where = 'MOBILE5_GETEB_HOMEPAGE';
			?>
			<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId();?>">
				<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
				<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
				<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
				<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
				<input type="hidden" name="list" value="<?php echo $course->getId(); ?>" />
				<input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
				<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
				<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
			</form>
			<?php }?>

		    </article>
				    
		</section>

<?php } ?>
<!-- End Display Institute List -->

<script>
function trackReqEbrochureClick(courseId){
try{
	_gaq.push(['_trackEvent', 'HTML5_GET_EB_Similar_Institute_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}

</script>

