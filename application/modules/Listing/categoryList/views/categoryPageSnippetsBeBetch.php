<ul>
<?php
		$count = 0;
		//$localityArray = array();
		foreach($institutes as $institute) {
			$count++;
			$selectedCourseId = "";
			if(count(instituteIdsOfCoursesToRotate) && in_array($institute->getId(), $instituteIdsOfCoursesToRotate)){
				//$coursesArray = $institute->getCourses();
				//$course = $coursesArray[array_rand($coursesArray)];
				$course = $institute->getFlagshipCourse();
				$selectedCourseId = $course->getid();
			} else {
				$course = $institute->getFlagshipCourse();
			}
			
					
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
                        
                        $courseListForBrochure = array();
                        foreach ($courses as $key => $value) {
                            $courseListForBrochure[$value->getId()] = $value->getName();
                            //echo $value->getId().'-'.$value->getName().'<br>';
                        }
                        
			$similarCourses = $courses;
			unset($similarCourses[0]);
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
			$track_url = ""; 
		        if($recommendationPage) {
				$onClickAction = "processActivityTrack(".$appliedCourse.", ".$course->getId().", ".$appliedInstitute.", 'LP_Reco_Viewed', 'LP_Reco_ShowRecoLayer', 'NULL', '".$course->getURL()."', event);";
				$recomd_course_id = "";
				foreach($applied_data as $temp_id) {
					$recomd_course_id = $temp_id;
				}
                        	$track_url = 'onclick="trackEventByCategory('.'\'ShowRecoPage_Reco\''.','.'\'Course_'.$recomd_course_id.'\','.'\'replacesomethinghere\''.')"'; 
			}		
?>
		<li id="instituteDetail<?php echo $institute->getId(); ?>">
			<div class="instituteListsDetails">
			<?php if(!$recommendationPage) { ?>	
				<div class="checkCol">
					<?php
						if($institute->isSticky() || $institute->isMain()){
							echo '<span class="checkIcon"></span>';
						}
					?>	
				</div>
			<?php } ?>	
				<div class="collegeDetailCol">
					<?php if($alsoOnShiksha){?>
					<script>
					var track_tocken = universal_page_type+'_'+universal_page_type_id;
					</script>
					<div class="college-title"><a onclick="trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.$count.'_'.'Insti'.'_'.$institute->getId();?>');" href="<?php echo $course->getURL();?>" title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span></div>
					<?php }else{
                                        $temp_track_url = "";				   
                                        if($track_url) {
						$temp_track_url = $track_url;
						$temp_track_url = str_replace(
									    "replacesomethinghere",
									    "Bottom_".$count."_institute_".$institute->getId(),
                                                                            $temp_track_url
                                                                  );
						$temp_track_url = substr_replace($temp_track_url, $onClickAction.' ', 9, 0);
					}
                                        ?>
					<!--<h3><a <?php echo $temp_track_url;?> href=<?=(($course->getOrder()==1)?'"'.$institute->getURL().'"':'"'.$course->getURL().'" rel="nofollow"')?> title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?>, </a> <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span></h3>-->
					<h3><a <?php echo $temp_track_url;?> href="<?=$course->getURL();?>" title="<?php echo html_escape($institute->getName()); ?>"><?php echo html_escape($institute->getName()); ?><?=(count($courseLocations)==1?', ':'')?></a>
					<?php if(count($courseLocations)==1){ ?>
					<span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span>
					<?php } ?>
					</h3>
					<?php } ?>
					<?php $newCourse = clone $course; echo Modules::run('listing/ListingPageWidgets/seeAllBranches',$newCourse,$courseLocations,"yes"); ?>
					<em><?php echo trim($institute->getUsp())?'"'.$institute->getUsp().'"':''; ?></em>
					<div class="collegeDetailsWrapper">
					<div class="collegeDetailsWrapper-left">
						<div class="collegePic">
							<?php
								if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){
									echo '<a href="'.$course->getURL().'"><img onclick="'.$onClickAction.'" src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="118" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
								}else{
									echo '<a href="'.$course->getURL().'"><img onclick="'.$onClickAction.'" src="/public/images/avatar.gif" alt="'.html_escape($institute->getName()).'" title="'.html_escape($institute->getName()).'"/></a>';
								}
							?>

						 							
						
						        
						</div>
												        <?php
								if($validateuser != 'false') {
									if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin'  || $validateuser[0]['usergroup'] == 'saCMS'){
										if(is_object($course)){
												if($course->isPaid()){
													echo '<div style="margin-top: 4px; float: left;"><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label></div>';
												}else{
													echo '<div style="margin-top: 4px;  float: left;"><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label></div>';	
												}
										}
									}
								}
						        ?>
						    <?php

		    						if($course->isPaid() || $brochureURL[$course->getId()] != ''){
									//keep brochure url as a hidden parameter, to later check for its type(PDF/IMAGE) in js at the time of start download
                                	echo '<input type = "hidden" id = "course'.$course->getId().'BrochureURL" value="'.$brochureURL[$course->getId()].'">';
									if($recommendationPage && !$alsoOnShiksha) {
									if(!$sourcePage) {
									$sourcePage = 'CATEGORY_RECOMMENDATION_PAGE';
									}
							?>        
						<div class="apply_confirmation" id="apply_confirmation<?php echo $institute->getId(); ?>"
						<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "style='display:block;float: left;line-height: 11px;'"; ?> >
							E-brochure successfully mailed
						<input type='hidden' id="apply_status<?php echo $institute->getId(); ?>" value='<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "1"; else echo "0"; ?>' />
						</div>
						
						<a href='javascript:void(0);' style='width:88%;'  onClick = "doAjaxApplyListings('<?php echo $institute->getId(); ?>','<?php echo $course->getId(); ?>','<?php echo $displayLocation->getCity()->getId(); ?>','<?php echo $displayLocation->getLocality()->getId();?>','<?php echo $responsecity ?>','<?php echo $sourcePage; ?>','<?php echo $appliedCourse; ?>','<?php echo $appliedInstitute; ?>')" class="orangeButtonStyle<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "_disabled"; ?> mr15 dwnld-brochure" id="apply_button<?php echo $institute->getId(); ?>" type="button" title="Download E-Brochure" ><span style="width:68%; margin:0 auto; display:block;">Download E-Brochure</span></a>
						<?php } elseif(!$recommendationPage) { ?>	
						<div id="applyCategoryPageConfirmation<?php echo $institute->getId(); ?>" class="recom-aply-row" style="float: left;<?php if(in_array($course->getId(),$recommendationsApplied)) echo "display:block;"; ?>" >
								<i class="thnx-icon" style="margin: 0; float: left"></i>
								<p style="margin:0 0 0 23px;float: none; color: inherit;line-height: 11px;">E-brochure successfully mailed.</p>
						</div>
						<a href='javascript:void(0);' style='width:88%;' id="categoryPageApplyButton<?php echo $institute->getId(); ?>" class="<?php if(in_array($course->getId(),$recommendationsApplied)) { echo 'orangeButtonStyle_disabled dwnld-brochure'; } else { echo 'orangeButtonStyle dwnld-brochure'; } ?> " <?php if(in_array($course->getId(),$recommendationsApplied)) { echo 'style="cursor:default;"'; } ?> uniqueattr="CategoryPage/reqEBrocher<?=$request->isNaukrilearningPage()?'/nl':''?>" title="Download E-Brochure" <?php if(!in_array($course->getId(),$recommendationsApplied)) { ?> onClick = "if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return multipleCourseApplyForCategoryPage(<?php echo $institute->getId()?>,'CategoryPageApplyRegisterButton',this, <?php echo $course->getId(); ?>,'<?=base64_encode(serialize($courseListForBrochure))?>');" <?php } ?>><span style="width:68%; margin:0 auto; display:block;">Download E-Brochure</span></a>
						<?php }
                        }?>     
						
						</div>
						<?php   
						       $data['institute'] = $institute;
						     
						       $data['displayLocation'] = $displayLocation;
						       $data['course'] = $course;
						       $data['similarCourseCount'] = count($similarCourses); 
						       $this->load->view('categoryList/categoryPageSnippetsBeBetchFlagshipCourseDetail',$data);
						       if(count($similarCourses) > 0) {
							   echo "<div class='spacer10 clearFix'></div>";
                               echo "<div id = 'similarCourses_".$institute->getId()."' style = 'display :none'>";
                               $data['count'] = 1;
							   foreach($similarCourses as $similarCourse)
							   {
							   	$data['course'] = $similarCourse;

							   	$this->load->view('categoryList/categoryPageSnippetsBeBetchSimilarCourseDetail',$data);
							   	$data['count']++;
							   }
							   echo "</div>";
							  }
					    ?>
					</div>
				</div>
			</div>
		<?php
			if($course->isPaid() || $brochureURL[$course->getId()] != ''){
		?>
		<!-- Hidden Div for Apply Now -->
		<div id="institute<?=$institute->getId()?>name" style="display:none"><?=html_escape($institute->getName())?>, <?=$displayLocation->getCity()->getName();?></div>
		<select id="applynow<?php echo $institute->getId()?>" style="display:none">
				<?php
					foreach($courses as $applyCourse){
						$applyCourse->setCurrentLocations($request);
						$courseLocations = $applyCourse->getCurrentLocations();
						//$localityArray[$applyCourse->getId()] = getLocationsCityWise($courseLocations);
				?>
						<option title="<?php echo html_escape($applyCourse->getName()); ?>" value="<?php echo $applyCourse->getId(); ?>" <?php echo ($selectedCourseId == $applyCourse->getId()) ? 'selected="selected"' : ''; ?>><?php echo html_escape($applyCourse->getName()); ?></option>
				<?php
					}
				?>
		</select>
		<div style="display:none">
			<?php
				foreach($courses as $applyCourse){
			?>
				<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$course->getId()?>list[]"  value= "<?=$applyCourse->getId()?>" />
			<?php	
				}
			?>
		</div>
		<?php
			}
		?>
		
		<?php if($recommendationPage && !$alsoOnShiksha): ?>
			<input type='hidden' id='params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
			<input type='hidden' id='reco_params<?php echo $institute->getId(); ?>' value='<?php echo html_escape(getParametersForApply($validateuser,$course,$responsecity,$responselocality)); ?>' />
		<?php endif; ?>
		<!-- Hidden Div for Apply Now -->
		
		<div id="recommendation_inline<?php echo $institute->getId();?>" style="display:none; float: left; width: 100%; margin-top:20px;"></div>
		</li>	
		
		
		<?php
		if($count == 8 && !$recommendationPage){
		?>
			<li style="text-align:center;">
				<?php
				global $criteriaArray;
				$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'EIGTH','shikshaCriteria' => $criteriaArray);
				$this->load->view('common/banner',$bannerProperties);
				?>
			</li>
		<?php	
		}
		if($count == 4 && !$recommendationPage){
		?>
			<li style="text-align:center;">
				<?php
				global $criteriaArray;
				$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'FOURTH','shikshaCriteria' => $criteriaArray);
				$this->load->view('common/banner',$bannerProperties);
				?>
			</li>
		<?php	
		}
	}
	//_p($coursesToCompare);
?>
</ul>
<?php if(!$recommendationPage) { ?>
<script>
compareDiv = 1;
</script>
<?php } ?>
<script>
	localityArray = <?=json_encode($localityArray)?>;
</script>
