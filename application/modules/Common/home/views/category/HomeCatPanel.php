<?php
	$eventsImage = isset($eventsImage) && $eventsImage != '' ? $eventsImage : '/public/images/foreign-edu-calender.jpg';
	$notificationsImage = isset($notificationsImage) && $notificationsImage != '' ? $notificationsImage : '/public/images/foreign-edu-calender.jpg';
	$latestUpdatesImage = isset($latestUpdatesImage) && $latestUpdatesImage != '' ? $latestUpdatesImage : '/public/images/foreign-edu-calender.jpg';
	
	$newsCaption = isset($newsCaption) && $newsCaption != '' ? $newsCaption : 'Latest';
	$notificationCaption = isset($notificationCaption) && $notificationCaption != '' ? $notificationCaption : 'Announcements';
	$eventsCaption = isset($eventsCaption) && $eventsCaption != '' ? $eventsCaption : 'Events';
	$eventsPosition = isset($eventsPosition) &&  $eventsPosition!= '' ?  $eventsPosition : 'left';
	$class = $eventsPosition == 'left' ? 'float_L' : 'float_R';
	$notificationCaption = 'Admissions';
	$newsCaption = 'Results';
	$eventsCaption = 'Events';
	$height = isset($homePageData) && $homePageData['page'] == 'SHIKSHA_HOME_PAGE' ? 205 : 227;
	$rowCount = isset($homePageData) && $homePageData['page'] == 'SHIKSHA_HOME_PAGE' ? 4 : 4;
	
	if(!(is_array($validateuser) && $validateuser != "false")) { 
		$onClick = 'showuserOverlay(this,\'add\',1);return false;';
	}else {
		if($validateuser['quicksignuser'] == 1) {
	        $base64url = base64_encode($_SERVER['REQUEST_URI']);
	        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
	echo $base64url?>/1\');return false;';
		} else {  
			$onClick = '';
		}
	}
	
	$clientWidth =  (isset($_COOKIE['client']) && $_COOKIE['client'] != '') ? $_COOKIE['client'] : 1024;
	$characterLength = ($clientWidth < 1000) ? 29 : 40;
	$characterLength = ($categoryData['page'] == '') ? 130 : 250; /* Can be removed */
	$tabMargin = ($clientWidth < 1000) ? 'margin-right:2px;' : '';
	$admissionNotificationText = ($clientWidth < 1000) ? 'Add an Adm Notification ' : 'Add an Admission Notification' ;
?>
<?php $this->load->view('common/subCategoryOverlay');?>
<div>
	<input id = "countOffset" type = "hidden" value = "14"/>
	<input id = "startOffSet" type = "hidden" value = "0"/>
	<input type = "hidden" id = "pagetype" value = "<?php echo $pagetype ?>"/>	
	<div>
				<!--Start_TabSelection-->
				<div id="blogTabContainer">
					<div id="blogNavigationTab" style="width:99%;">
						<ul>
							<?php if($pagetype != "course") { ?>
							<li container="category" tabName="categoryinstitutes" class="selected" onClick="return selectHomeTab('category','institutes');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Main Institutes">Main Institutes</a>						
							</li>
							<?php } else { ?>
							<li container="category" tabName="categorycourses" class="selected" onClick="return selectHomeTab('category','courses');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Courses">Courses</a>						
								</li>                    
							<?php } ?>
							<li container="category" tabName="categorydates" class="" onClick="return selectHomeTab('category','dates');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Important Dates">Important Dates</a>						
							</li>
							<li container="category" tabName="categoryScholarships" class="" onClick="return selectHomeTab('category','Scholarships');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Scholarships">Scholarships</a>						
							</li>
							<li container="category" tabName="categoryarticles" class="" onClick="return selectHomeTab('category','articles');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Articles">Articles</a>						
							</li>
							<li container="category" tabName="categoryQA" class="" onClick="return selectHomeTab('category','QA');" style="<?php echo $tabMargin; ?>">						
								<a href="#" title="Q & A">Q & A</a>						
							</li>
						</ul>
					</div>
					<div class="clear_L"></div>
				</div>
				<!--End_TabSelection-->

				
				<div style="border:1px solid #D6DBDF;position:relative;top:1px;width:100%">
							<?php if($pagetype == "course") { ?>
					<div class="mar_full_10p" style="display:none;" id="categorycoursesBlock">
                        <div class = "lineSpace_15">&nbsp;</div>
								<?php
									$messageText = '';
									
									$totalResults = $courseList[0]['total'];
									if($totalResults > 0) {
										$startNum = 1;
										$endNum = 14;
										$endNum = $endNum > $totalResults ? $totalResults : $endNum;
										$messageText = 'Showing '. $startNum  . ' - '. $endNum .' courses out of '. $totalResults;
									} else {
										$messageText = 'No courses Available.';
									}	
								?>
                                <div><label id="categoryCourseCountLabel"><?php echo $messageText; ?></label></div>
                        <div class = "lineSpace_10">&nbsp;</div>
					<div id="categorycoursesPlace">
                        <?php $this->load->view('/home/shiksha/HomeCourseWidget') ?>
                        <?php $count = $courseList[0]['total'] ;?>
                    </div>
<div class = "clear_L"></div>
                        <div class = "lineSpace_10">&nbsp;</div>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
									<!--Pagination Related hidden fields Starts-->
									<input type="hidden" id="methodName1" value="getFeaturedCourses"/>
									<!--Pagination Related hidden fields Ends  -->
										<div  id="paginataionPlace3"></div>
										<div  id="paginataionPlace4" style = "display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
    <script>
    document.getElementById('startOffSet').value = 0;
doPagination(<?php echo $count ; ?>, 'startOffSet', 'countOffset', 'paginataionPlace3', 'paginataionPlace4', 'methodName1', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
</script>

									</div><?php } ?>
					<div class="mar_full_10p" style="padding:10px 0px;display:none;<?php echo count($events) < 1 ? 'height:'. $height .'px' : ''; ?>" id="categoryinstitutesBlock">
								<?php
									$messageText = '';
									
									$totalResults = $collegeList[0]['total'];
									if($totalResults > 0) {
										$startNum = 1;
										$endNum = 14;
										$endNum = $endNum > $totalResults ? $totalResults : $endNum;
										$messageText = 'Showing '. $startNum  . ' - '. $endNum .' institutes out of '. $totalResults;
									} else {
										$messageText = 'No institutes Available.';
									}	
								?>
								<div><label id="categoryCollegesCountLabel"><?php echo $messageText; ?></label></div>
								<div class="lineSpace_15">&nbsp;</div>
								<div id="collegeListPlace">
										<?php 
                                                $selectedCityList = explode(',',$selectedCity);
												foreach($collegeList[0]['institutes'] as $college){
												if(empty($college['id'])) {  continue; }
												$collegeId 		= $college['id'];
												$collegeName 	= ucwords($college['title']);
                                                if($countrySelected =='' &&  $selectedCity=='') {
												    $collegeCity	= $college['locationArr'][0]['city_name'];
                                                    $collegeCountry = $college['locationArr'][0]['country_name'];
                                                } else {
                                                    $collegeCity = '';
                                                    $collegeCountry = '';
                                                }
                                                if($countrySelected != '') {
                                                    for($locationCount = 0; $locationCount < count($college['locationArr']); $locationCount++){
                                                        if($college['locationArr'][$locationCount]['country_id'] == $countrySelected) {
                                                            $collegeCity	= $college['locationArr'][$locationCount]['city_name'];
                                                            $collegeCountry = $college['locationArr'][$locationCount]['country_name'];
                                                            if($selectedCity == '') {
                                                                break;
                                                            }

                                                            if($selectedCity != '' && in_array($college['locationArr'][$locationCount]['city_id'], $selectedCityList )) { break; }
                                                        }
                                                    }
                                                }
											    $country1 = $collegeCountry;
												$url = $college['url'];
												$location = $collegeCity;
												if($collegeCity != '' && $collegeCountry!= '') {
													$location .= ' - ';
												}
												$location .= $collegeCountry;
												
												$screen_res = $_COOKIE['client'];
												if($screen_res < 1000)
												$truncateStrLength = 16;
												else
												$truncateStrLength = 33;

												$sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
												$truncateStrLengthForRecord = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
												$sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
												$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
												$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
												$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
												$courseId = $college['courseArr'][0]['course_id'];
												$courseUrl = html_entity_decode($college['courseArr'][0]['url']);
												$courseName = html_entity_decode($college['courseArr'][0]['title']);
												$courseNameDisplayText = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
												$courseNameDisplayText = $courseNameDisplayText=='' ? '&nbsp;' : $courseNameDisplayText;
																			
										?>									
										<div class="w49_per float_L">
												<div class="row" style = "height:55px">
														<div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>">
															<img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/>
														</div>
														<div class="float_L">
															<div class="normaltxt_11p_blk_arial">
																<a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>
															</div>
															<?php if(strlen($courseName) != 0) { ?>
																<div class="normaltxt_11p_blk_arial">
																	<a href="<?php echo $courseUrl; ?>" title="<?php echo $courseName; ?>" class="blackFont"><?php echo $courseNameDisplayText; ?></a>
																</div>
															<?php } ?>
															<div class="normaltxt_11p_blk_arial">
																<span class="fontGreenColor"><?php echo $location /*.' - ' . $country1*/; ?></span>
															</div>
															<script>
																var isQuickSignUpUser = 0;
																var base64url = '';
																var UserLogged = 1;
															</script>
															<?php /* if($college['isSendQuery'] == 1){
																if(!(is_array($validateuser) && $validateuser != "false")) {
															?> 
															<script>
																var UserLogged = 0;
															</script>
															<?php $onClick = "showuserLoginOverLay('',2);return false;";
															}else {
																if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['orusergroup'] == "quicksignupuser") {
																	$base64url = base64_encode($_SERVER['REQUEST_URI']);?>
																<script>
																	isQuickSignUpUser = 1;
																	base64url = '<?php echo $base64url ?>';
																</script>
															<?php $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
															} else {  
																	$onClick = "setRequestInfoForSearchParams('institute',$collegeId,'$collegeName','$url','');";
															}}?>
															<div class="normaltxt_11p_blk_arial">
																<a class="grayFont" onClick = "<?php echo $onClick?>" style = "cursor:pointer">Send Question to this Institute</a>
															</div>
                                                            <?php } */?>
															<div class="lineSpace_10">&nbsp;</div>
														</div>
														<div class="clear_L"></div>
												</div>
										</div>
										<?php } ?>								
								</div>
					<div class="clear_L"></div>
                        <?php $count = $collegeList[0]['total'] ;?>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
									<!--Pagination Related hidden fields Starts-->
									<input type="hidden" id="methodName" value="getFeaturedCollegesForCatgeoryPages"/>
									<!--Pagination Related hidden fields Ends  -->
										<div id="paginataionPlace1"></div>
										<div  id="paginataionPlace2" style="display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
    <script>
    document.getElementById('startOffSet').value = 0;
doPagination(<?php echo $count ; ?>, 'startOffSet', 'countOffset', 'paginataionPlace1', 'paginataionPlace2', 'methodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
</script>
                        <!-- Pagination Place -->
					</div>
					<!--End_Internal_Data_Main_Institute-->
					<!--Start_IInd_Tab-->
					<div class="mar_full_10p" style="padding:10px 0;<?php echo count($examNotifications) < 1 ? 'height:'. $height .'px' : ''; ?>;display:none" id="categorydatesBlock">
							<?php
								if(is_array($examNotifications)) {
									foreach($examNotifications['results'] as $notification) {
										$sponsoredResult = '';
										$sponsoredResult = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? '<img src="/public/images/check.gif" style="margin-left:0px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
										$sponsoredClass = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? 'quesAnsBulletsSponsored': 'quesAnsBullets';
										$notificationId = isset($notification['id']) ? $notification['id'] : '';
										
										 $notificationTitle = isset($notification['title']) && !empty($notification['title']) ? $notification['title'] :'';
										$notificationDisplayTitle = (strlen($notificationTitle) > $characterLength)? substr($notificationTitle,0,$characterLength - 3).'...'  : $notificationTitle;
										
										$city = isset($notification['city']) && !empty($notification['city']) ? $notification['city'] : '';
										$location = isset($notification['venue_name']) && !empty($notification['venue_name'])? $notification['venue_name'] .',' : '';
										$startDate = isset($notification['start_date']) && !empty($notification['start_date'])? $notification['start_date'] : '';
										$startDate = date('jS M, y',strtotime( $startDate));
										$datePattern = '/(\d{1,2})(\w{2}) (\w+), (\d+)/i';
										$dateReplacePattern = '${1}<sup>${2}</sup> ${3}, $4';
										$startDate = preg_replace($datePattern, $dateReplacePattern, $startDate);
										$notificationUrl = $notification['url'];
							?>
							<div class="normaltxt_11p_blk arial">
									<div style="margin-bottom:2px" class="<?php echo $sponsoredClass; ?>">
										<a title="<?php echo $notificationTitle; ?>" href="<?php echo $notificationUrl; ?>" class="fontSize_12p"><?php if(strlen($city)>0) { echo $notificationDisplayTitle .', '. $city; } else { echo $notificationDisplayTitle ; } ?></a>
									</div>
									<div style="color:#838487; margin-left:15px;display:none;">
										<?php //echo $location; ?> 
										<?php echo $city; ?> <?php echo $startDate; ?>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
							</div>
							<?php }
							} else { echo "No exam notifications as of now. Check back soon!"; } ?>
							<div class="row">
								<div class="txt_align_r">
									<?php if(count($notification) > 0) { 
								?>
                                    <a href="<?php echo "/events/Events/index/1/".$countrySelected."/".$categoryId?>" title="View All">View All</a>
									<?php } ?>
								</div>
							</div>
					</div>
					<!--Second_Tab-->

					<!--Start_IIIrd_Tab-->
					<div class="mar_full_10p" style="padding:10px 0;<?php echo count($notifications) < 1 ? 'height:'. $height .'px' : ''; ?>;display:none;" id="notificationAnnouncementsBlock">
						<?php
							if(is_array($notifications)) {
								foreach($notifications['results'] as $notification) {
									$sponsoredResult = '';
									$sponsoredResult = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? '<img src="/public/images/check.gif" style="margin-left:0px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
									$sponsoredClass = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? 'quesAnsBulletsSponsored': 'quesAnsBullets';
									$notificationId = isset($notification['id']) ? $notification['id'] : '';
									
									 $notificationTitle = isset($notification['title']) && !empty($notification['title']) ? $notification['title'] :'';
									$notificationDisplayTitle = (strlen($notificationTitle) > $characterLength)? substr($notificationTitle,0,$characterLength - 3).'...'  : $notificationTitle;
									
									$city = isset($notification['city']) && !empty($notification['city']) ? $notification['city'] : '';
									$location = isset($notification['venue_name']) && !empty($notification['venue_name'])? $notification['venue_name'] .',' : '';
									$startDate = isset($notification['start_date']) && !empty($notification['start_date'])? $notification['start_date'] : '';
									$startDate = date('jS M, y',strtotime( $startDate));
									$datePattern = '/(\d{1,2})(\w{2}) (\w+), (\d+)/i';
									$dateReplacePattern = '${1}<sup>${2}</sup> ${3}, $4';
									$startDate = preg_replace($datePattern, $dateReplacePattern, $startDate);
									$notificationUrl = $notification['url'];
						?>
						<div class="normaltxt_11p_blk arial">
							<div style="margin-bottom:2px" class="<?php echo $sponsoredClass; ?>">
								<a title="<?php echo $notificationTitle; ?>" href="<?php echo $notificationUrl; ?>" class="fontSize_12p"><?php if(strlen($city)>0) {echo $notificationDisplayTitle .', '. $city; } else{ echo $notificationDisplayTitle; } ?></a>
							</div>
							<div style="color:#838487; margin-left:15px;display:none;">
								<?php echo $city; ?> <?php echo $startDate; ?>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
						<?php }
							} else { echo "No admission announcements as of now. Check back soon!"; }
						?>
						<div>
							<div class="txt_align_r">
								<a href="<?php echo SHIKSHA_EVENTS_HOME_URL; ?>/events/Events/showAddEvent#admission" onClick="<?php echo $onClick; ?>" title="Add an Admission Notification" class="bld"><?php echo $admissionNotificationText; ?></a> &nbsp; &nbsp;
								<?php if(count($notifications) > $rowCount) { ?>
                                    <a href="'/events/Events/index/1/'" title="View All">View All</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<!--End_IIIrd_Tab-->

					<!--IVth_Tab-->
					<div class="mar_full_10p" style="padding:10px 0px;display:none" id="categoryarticlesBlock">
						<?php 
							$CI_Instance = & get_instance();
							$clientWidth =  $CI_Instance->checkClientData();
							$characterLength = 250;
							foreach($blogs['results'] as $blog) {
								$blogId = isset($blog['blogId']) ? $blog['blogId'] : '';
								$blogTitle = isset($blog['blogTitle']) ? $blog['blogTitle'] : '';
								$blogUrl = isset($blog['url']) ? $blog['url'] : '';
						?>
						<div class="normaltxt_11p_blk arial">
							<div style="margin-bottom:2px" class="quesAnsBullets">
								<a class="fontSize_12p" href="<?php echo $blogUrl; ?>" title="<?php echo $blogTitle; ?>"><?php echo (strlen($blogTitle)>$characterLength)?substr($blogTitle,0,$characterLength-3)."...":$blogTitle; ?></a>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
						<?php } ?>
						<div class="mar_full_10p normaltxt_11p_blk_arial txt_align_r"><a href='<?php echo "/blogs/shikshaBlog/showArticlesList?countryId=".$countryId."&categoryId=".$categoryId."&c=".rand()?>'>View all</a>&nbsp;</div>
					</div>	
					
					<div class="mar_full_10p" style="padding:10px 0px;display:none" id="categoryQABlock">
						<div class="mar_full_10p">
								<?php	
										foreach($msgBoards['results'] as $msgBoardId => $msgBoard) {
											$msgBoardTopic = isset($msgBoard['msgTxt']) ? $msgBoard['msgTxt'] : '';
											$numComments = isset($msgBoard['Count']) ? $msgBoard['Count'] : '';
											$numUsers = isset($msgBoard['numUsers']) ? $msgBoard['numUsers'] : '';
											$userImage = isset($msgBoard['userImage']) && !empty($msgBoard['userImage']) ? getSmallImage($msgBoard['userImage']) : '/public/images/unkownUser.gif';
											if(strlen($msgBoardTopic) > 200)
                                                $msgBoardTopic = substr($msgBoardTopic,0,197) . "...";
                                            $msgBoardTopic = wordwrap($msgBoardTopic, 60, "\n",true);
								?>
								<div>
									<div class="float_L">
										<img src="<?php echo $userImage; ?>" width="58" height="52" />
									</div>
									<div class="mar_left_70p normaltxt_11p_blk arial">
                                        <div><a href="<?php echo $msgBoard['url']?>"><?php echo $msgBoardTopic; ?></a></div>
										<div class="lineSpace_5">&nbsp;</div>
										<div class="lineSpace_20"><a href="<?php echo $msgBoard['url']?>">Answer Now</a></div>
									</div>
									<div class="clear_L"></div>
									<div class="lineSpace_10">&nbsp;</div>
								</div>
								<?php }	?>
						</div>		
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p normaltxt_11p_blk_arial txt_align_r"><a href='<?php echo "/messageBoard/MsgBoard/discussionHome/".$categoryId."/1/".$countrySelected?>'>View all</a>&nbsp;</div>
					</div>
					
					<div class="mar_full_10p" style="padding:10px 0px;display:none" id="categoryScholarshipsBlock">
						<?php
							$scholarships = $scholarshipResults['scholarhips'];
							if(is_array($scholarships)) {
								foreach($scholarships as $scholarship) {
									$scholarshipTitle = $scholarship['title'];
									$scholarshipId = $scholarship['id'];
									$scholarshipUrl = $scholarship['url'];
									$scholarshipCityId = $scholarship['city_id'];
									$scholarshipCountryId = $scholarship['country_id'];
									$scholarshipCityName = $scholarship['city_name'];
									$scholarshipValue = strlen($scholarship['value']) > 70 ? substr($scholarship['value'],0,67) . '...' : $scholarship['value'];
									$scholarshipApplicableto = strlen($scholarship['applicableTo']) > 70 ? substr($scholarship['applicableTo'],0,67) . '...' : $scholarship['applicableTo'];
									$scholarshipCountryName = $scholarship['country_name'];
									$displayTitle = $scholarshipTitle;
									if(strlen($displayTitle) > $characterLength) {
										$displayTitle = substr( $displayTitle, 0,$characterLength-3) . '...';
									}
									$sponsoredResult = (isset($scholarship['isSponsored']) && $scholarship['isSponsored'] == true) ? '<img src="/public/images/check.gif" style="margin-left:4px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
									$sponsoredClass = (isset($scholarship['isSponsored']) && $scholarship['isSponsored'] == "true") ? 'quesAnsBulletsSponsored': 'quesAnsBullets';
						?>
						<div class="normaltxt_11p_blk arial">
							<div style="margin-bottom:2px" class="<?php echo $sponsoredClass; ?>">
								<a class="fontSize_12p" href="<?php echo $scholarshipUrl; ?>" title="<?php echo $scholarshipTitle;?>"><?php echo $displayTitle; ?></a>
							</div>
							<?php if($scholarshipValue != '') { ?>
								<div style="margin-bottom:2px;padding-left:10px;" class = "normaltxt_11p_blk arial">Value :<?php echo $scholarshipValue; ?></div>
							<?php } ?>
							<?php if($scholarshipApplicableto != '') { ?>
								<div style="margin-bottom:2px;padding-left:10px;" class = "normaltxt_11p_blk arial">Applicable To :<?php echo $scholarshipApplicableto; ?></div>
							<?php } ?>			
							<div class="lineSpace_10px">&nbsp;</div>
						</div>
                        <?php }?> 
                        
						<div class="mar_full_10p normaltxt_11p_blk_arial txt_align_r"><a href='<?php echo "/listing/Listing/showScholarshipsList?countryId=".$countryId."&categoryId=".$categoryId."&c=".rand()?>'>View all</a>&nbsp;</div>
                    <?php } else { echo "No scholarships as of now. Check back soon!"; } ?>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<!--End_IVth_Tab-->
					<div class="clear_L"></div>
                                <!-- Pagination Place -->
				</div>
				<!--End_Border-->
	</div>
</div>

<?php
     
if($pagetype == "course") { ?>                  
	<script> document.getElementById('categorycoursesBlock').style.display = 'block';</script>
<?php } else {?>
	<script> document.getElementById('categoryinstitutesBlock').style.display = 'block';</script>
<?php } ?>
