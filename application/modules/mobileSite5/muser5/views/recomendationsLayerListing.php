 <?php if(!empty($recommendations)):?>
 			<a id="reco_layer"></a>
        		<?php if(isset($_COOKIE['MOB_A_C'])){
						$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
					}
				?>
				<?php 
					global $shiksha_site_current_url;global $shiksha_site_current_refferal ;
				
				        //Check for Request E-Brochure from Also on Shiksha institutes. From here, the referral URL will be the current URL
				        if(strpos($shiksha_site_current_url,'alsoOnShiksha')!==false){
				                $shiksha_site_current_url = $shiksha_site_current_refferal;
				        }
				?>
				<?php $from_where = $pageType;
						$pageName = $pageType;
						
						if($pageName == 'CP_MOB_Reco_ReqEbrochure') {
							$courseClick = 'HTML5_RECOMMENDATION_CATEGORY_COURSE_CLICK';
							$brochureClick = 'HTML5_RECOMMENDATION_CATEGORY_BROCHURE_CLICK';
						}else if($pageName == 'LP_MOB_Reco_ReqEbrochure') {
							$courseClick = 'HTML5_RECOMMENDATION_LISTING_COURSE_CLICK';
							$brochureClick = 'HTML5_RECOMMENDATION_LISTING_BROCHURE_CLICK';
						}else if($pageName == 'SEARCH_MOB_Reco_ReqEbrochure') {
							$courseClick = 'HTML5_RECOMMENDATION_SEARCH_COURSE_CLICK';
							$brochureClick = 'HTML5_RECOMMENDATION_SEARCH_BROCHURE_CLICK';
						}else if($pageName = 'RANKING_MOB_Reco_ReqEbrochure') {
							$courseClick = 'HTML5_RECOMMENDATION_SEARCH_COURSE_CLICK';
							$brochureClick = 'HTML5_RECOMMENDATION_SEARCH_BROCHURE_CLICK';
						}
						
				?>
        		<?php for($i = 0 ; $i< count($recommendations) ; $i=$i+1):?>
	        
	        		<?php 
	        				$currntInstitute = $recommendations[$i];
	        				$curInstituteUrl = $currntInstitute->getURL();
							$curcourse = $currntInstitute->getFlagshipCourse();
							$curinstituteId = $currntInstitute->getId();
							$curcourseId = $curcourse->getId();
							$curinstituteFullName = $currntInstitute->getName();
							$curcourseFullName = $curcourse->getName();
							$curinstituteName = strlen($curinstituteFullName) > 40 ? substr($curinstituteFullName, 0, 40).'...' : $curinstituteFullName;
							$curcourseName = strlen($curcourseFullName) > 45 ? substr($curcourseFullName, 0, 45).'...' : $curcourseFullName;
							$curcourseURL = $curcourse->getURL();
							$curmainLocation = $curcourse->getMainLocation();
							$curmainCity = $curmainLocation->getCity();
							$curmainCityName = $curmainCity->getName();
							$curCourses = $currntInstitute->getCourses();
							
							$curaddReqInfoVars = array();
							foreach($curCourses as $c){
								if(checkEBrochureFunctionality($c)){
									$curarr['isMultiLocation'.$curinstituteId] = $c->isCourseMultilocation();
									foreach($c->getLocations() as $course_location){
										$locality_name = $course_location->getLocality()->getName();
										if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
										$curaddReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($currntInstitute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
										if($curarr['isMultiLocation'.$curinstituteId]=='false'){
											$curarr['rebLocallityId'.$curinstituteId] = $course_location->getLocality()->getId();
											$curarr['rebCityId'.$curinstituteId] = $course_location->getCity()->getId();
										}else{
											$curarr['rebLocallityId'.$curinstituteId] = '';
											$curarr['rebCityId'.$curinstituteId] = '';
										}
									}
								}
							}
							
							$curaddReqInfoVars=serialize($curaddReqInfoVars);
							$curaddReqInfoVars=base64_encode($curaddReqInfoVars);
							
							$exams = $curcourse->getEligibilityExams();
							$courseFees = $curcourse->getFees()->getValue();
							$courseDuration = $curcourse->getDuration();
	        			
							$onClickEvent = "location.href='$curcourseURL'; trackEventByGAMobile('$courseClick');";
							if($pageName == 'CP_MOB_Reco_ReqEbrochure') {
								$onClickEvent = "trackEventByGAMobile('$courseClick'); processActivityTrackMobile(CP_lastREBCourseId, '$curcourseId', CP_lastREBInstituteId, 'MOBILE_CP_Reco_Viewed', 'CP_Reco_divLayer', 'also_viewed', '$curcourseURL', event);";
							}
							else if($pageName == 'LP_MOB_Reco_ReqEbrochure'){
								$onClickEvent = "trackEventByGAMobile('$courseClick'); processActivityTrackMobile(CP_lastREBCourseId, '$curcourseId', CP_lastREBInstituteId, 'MOBILE_LP_Reco_Viewed', 'LP_Reco_divLayer', 'also_viewed', '$curcourseURL', event);";
							}
	        		?>
	        		
	        		<section class="content-wrap2" >
		        		<article class="req-bro-box clearfix" style="cursor: pointer;" >
						<div class="details">
							<div onclick="<?=$onClickEvent?>" style="cursor: pointer;">
	                        <h4><a href="javascript:void(0);" ><?php echo $curinstituteName?>,</a> <span><?php echo $curmainCityName;?></span></h4>
				<ul>
	                        <li><?php echo $curcourseName;?></li>
	                        <?php if(count($exams) > 0):?>
	                        <?php 
	                      	  if($currntInstitute->getInstituteType() == "Test_Preparatory_Institute"){
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
	                        <?php elseif (!empty($courseFees)):?>
	                        <li><i class="icon-rupee"></i><p><label>Course Fees: </label><?php echo $courseFees;?></p></li>
	                        <?php endif;?>
	                        
	                        
	                        </ul>
	                        </div>
	                        <?php if(in_array($curcourseId,$appliedCourseArr)):?>
	                        	<a href="javascript:void(0);" class="button blue small disabled" id="request_e_brochure<?php echo $curcourseId; ?>" ><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
	                        	<div class="success-msg" id="thanksMsg<?php echo $curcourseId;?>"><i class="sprite icon-tick2"></i><p>E-brochure successfully mailed.</p></div>
	                        <?php else:?>
	                        	<?php if($pageName == 'LP_MOB_Reco_ReqEbrochure'):?>
	                        	<a href="javascript:void(0);" class="button blue small" id="request_e_brochureReco<?php echo $curcourseId; ?>" onclick="trackRequestEbrochure('<?php echo $curcourseId;?>');validateRequestEBrochureFormData('<?php echo $curinstituteId; ?>','<?=$curarr['rebLocallityId'.$curinstituteId];?>','<?=$curarr['rebCityId'.$curinstituteId];?>','<?=$curarr['isMultiLocation'.$curinstituteId];?>','<?php echo $curcourseId;?>','listing_recommendation','<?php echo $trackingPageKeyId;?>');trackEventByGAMobile('<?php echo $brochureClick; ?>'); " ><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
	                        	<?php else:?>
	                        	<a href="javascript:void(0);" class="button blue small" id="request_e_brochureReco<?php echo $curcourseId; ?>" onclick="trackReqEbrochureClick('<?php echo $curcourseId;?>');validateRequestEBrochureFormData('<?php echo $curinstituteId; ?>','<?=$curarr['rebLocallityId'.$curinstituteId];?>','<?=$curarr['rebCityId'.$curinstituteId];?>','<?=$curarr['isMultiLocation'.$curinstituteId];?>','<?php echo $curcourseId;?>','listing_recommendation','<?php echo $trackingPageKeyId;?>');trackEventByGAMobile('<?php echo $brochureClick; ?>');" ><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
	                        	<?php endif;?>
	                        	<form id="brochureFormReco<?php echo $curcourseId;?>">
									<input type="hidden" name="courseAttr" value = "<?php echo $curaddReqInfoVars; ?>" />
									<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
									<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
									<input type="hidden" name="selected_course" value = "<?php echo $curCourseId; ?>" />
									<input type="hidden" name="list" value="<?php echo $curcourseId; ?>" />
									<input type="hidden" name="institute_id" value="<?php echo $curinstituteId;?>" />
									<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
									<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
									<input type="hidden" name="screenwidth" value=<?php echo $screenWidth;?> />
							</form>
	                        	<div style="display: none;" class="success-msg" id="thanksMsgReco<?php echo $curcourseId;?>"><i class="sprite icon-tick2"></i><p>E-brochure successfully mailed.</p></div>
	                        <?php endif; ?>
						</div>
		        		</article>
	        		</section>
	        		
	        		
	        		<!-- 
	            	<div class="carausel-content">
	                    <div class="recommen-left-col" style="width:100%">
	                        <strong><a href="<?php echo $curInstituteUrl;?>" target="_blank" ><?php echo $curinstituteName?>,</a> <span><?php echo $curmainCityName;?></span></strong>
	                        <p><?php echo $curcourseName;?></p>
	                        <?php if(in_array($curcourseId,$appliedCourseArr)):?>
	                        	<a href="javascript:void(0);" class="button blue small disabled" id="request_e_brochure<?php echo $curcourseId; ?>" ><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
	                        	<div class="success-msg" id="thanksMsg<?php echo $curcourseId;?>"><i class="sprite icon-tick2"></i><p>E-brochure successfully mailed.</p></div>
	                        <?php else:?>
	                        	<a href="javascript:void(0);" class="button blue small" id="request_e_brochure<?php echo $curcourseId; ?>" onclick="trackReqEbrochureClick('<?php echo $curcourseId;?>');validateRequestEBrochureFormData('<?php echo $curinstituteId; ?>','<?=$curarr['rebLocallityId'.$curinstituteId];?>','<?=$curarr['rebCityId'.$curinstituteId];?>','<?=$curarr['isMultiLocation'.$curinstituteId];?>','<?php echo $curcourseId;?>')" ><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
	                        	<form id="brochureForm<?php echo $curcourseId;?>">
									<input type="hidden" name="courseAttr" value = "<?php echo $curaddReqInfoVars; ?>" />
									<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
									<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
									<input type="hidden" name="selected_course" value = "<?php echo $curCourseId; ?>" />
									<input type="hidden" name="list" value="<?php echo $curcourseId; ?>" />
									<input type="hidden" name="institute_id" value="<?php echo $curinstituteId;?>" />
									<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
									<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
								</form>
	                        	<div style="display: none;" class="success-msg" id="thanksMsg<?php echo $curcourseId;?>"><i class="sprite icon-tick2"></i><p>E-brochure successfully mailed.</p></div>
	                        <?php endif; ?>
	                    </div>
	                    
	                </div>
	                 -->
	                 
	            <?php endfor;?>
       

        <div class="clearfix"></div>


<?php endif;?>    
