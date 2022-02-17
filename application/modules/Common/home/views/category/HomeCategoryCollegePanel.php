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

<?php
    $panelHeading = "Institutes & Universities";
?>
	<div>
			<input id = "countOffset" type = "hidden" value = "<?php echo $numColleges; ?>"/>
			<input id = "startOffSet" type = "hidden" value = "0"/>
			<input type = "hidden" id = "pagetype" value = "<?php echo $pagetype ?>"/>
			<?php
				$messageText = '';
				
				$totalResults = $collegeList[0]['total'];
				if($totalResults > 0) {
					$startNum = 1;
					$endNum = $numColleges;
					$endNum = $endNum > $totalResults ? $totalResults : $endNum;
					$messageText = 'Showing '. $startNum  . ' - '. $endNum .' of '. $totalResults;
									} else {
										$messageText = 'No institutes Available.';
									}	
								?>

        <div class="careerOptionPanelBrd">
            <div class="careerOptionPanelHeaderBg">
                <div class="float_R"><label id="categoryCollegesCountLabel" class="blackFont fontSize_12p"><?php echo $messageText; ?></label></div>
		    	<h5><span class="blackFont fontSize_13p"><?php echo $panelHeading; ?></span></h5>
		    </div>
            <?php $count = $collegeList[0]['total'] ;?>
            <?php
            // make sure multiple apply header will not call in studyabord page
            // also see last lines of js .. here we empty this header if
            // paid clients are not shown got it man !!! :)
             if ( is_array($categoryData) && ($categoryData['page'] !== 'FOREIGN_PAGE' )) { ?>
            <!-- Multiple Apply button start -->
            <div style="background:#e9e9e9;height:30px;">
            <div style="padding-left:9px"><input type="checkbox" style="position:relative;top:3px;" onclick="checkAllFields(1);" id="checkAll" /> <a id="hrefcheckall" onclick ="var chkAll = document.getElementById('checkAll'); if (chkAll.checked == false) { chkAll.checked = true ; checkAllFields(1); }" href="javascript:void(0);" >Select All</a>&nbsp;|&nbsp; <a href="javascript:void(0);" onclick="var chkAll = document.getElementById('checkAll'); chkAll.checked = false ; checkAllFields(1);" id="hrefcheckall">Clear All</a>
            <input type="button" onclick="return checkformMultipleApply('MULTIPLE_APPLY_INSTITUTE_LIST_REQUESTE_BROCHURE_OVERLAY_CLICK');" class="doneBtnReqOrg" value="Request E-Brochure" /></div>
            </div>
            <!-- Multiple Apply button End -->
            <?php } ?>
            <div class="mar_full_10p" style="padding:10px 0px;" id="categoryinstitutesBlock">                
    			<div id="collegeListPlace">
					<?php 
						$selectedCityList = explode(',',$selectedCity);
						$collegeCount = count($collegeList[0]['institutes']);
						$currentCollegeCount = 0;
						foreach($collegeList[0]['institutes'] as $college){
						$currentCollegeCount++;
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
							//	if($college['locationArr'][$locationCount]['country_id'] == $countrySelected) {
									$collegeCity	= $college['locationArr'][$locationCount]['city_name'];
									$collegeCountry = $college['locationArr'][$locationCount]['country_name'];
									if($selectedCity == '') {
										break;
									}

									if($selectedCity != '' && in_array($college['locationArr'][$locationCount]['city_id'], $selectedCityList )) { break; }
								//}
							}
						}
						$country1 = $collegeCountry;
						$url = $college['url'];
						$location = $collegeCity;
						if($collegeCity != '' && $collegeCountry!= '') {
							$location .= ', ';
						}
						$location .= $collegeCountry;
						
						$screen_res = $_COOKIE['client'];
						if($screen_res < 1000)
						$truncateStrLength = 16;
						else
						$truncateStrLength = 86;

						$sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
						$sponsoredResultClass = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'quesAnsBulletsSponsored ' : 'quesAnsBullets' ;
						$truncateStrLengthForRecord = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? $truncateStrLength-3 : $truncateStrLength;
						$sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
						$sponsoredResultMargin = ''; // As image gets distorted in IE
						$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
						$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
						$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
						$courseId = $college['courseArr'][0]['course_id'];
						$courseUrl = html_entity_decode($college['courseArr'][0]['url']);
						$courseName = html_entity_decode($college['courseArr'][0]['title']);
						$courseNameDisplayText = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
						$courseNameDisplayText = $courseNameDisplayText=='' ? '&nbsp;' : $courseNameDisplayText;
													
					?>									
					<div class="">
						<div class="row">
							<script>
								var isQuickSignUpUser = 0;
								var base64url = '';
								var UserLogged = 1;
							</script>
							<?php
                            /*
                            Again we check if paid clients as well as
                            user is logged-in or not
                            */
								if($college['isSendQuery'] == 1){
									if(!(is_array($validateuser) && $validateuser != "false")) {
							?>
								<script>
								var UserLogged = 0;
								</script>
							<?php
								$onClick = "showuserLoginOverLay(this,'HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO','refresh');return false;";
								} else {
								if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['orusergroup'] == "quicksignupuser") {
								$base64url = base64_encode($_SERVER['REQUEST_URI']);
							?>
								<script>
								isQuickSignUpUser =1;
								base64url = '<?php echo $base64url;?>';
								</script>
							<?php
										 $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
									 } else {
										 $onClick = "setRequestInfoForSearchParams('institute',$collegeId,'". addcslashes($collegeName,"'") ."','$url','');";
									 }
							   }
							   $sendQueryMargin = 'margin-right:165px;';
							?>
                            <div class="normaltxt_11p_blk_arial float_R ">
								<!--<input type="button" value="Send Query" class="sendQueryBtn"  onClick ="<?php //echo $onClick?>" />-->
								<?php if (is_array($categoryData) && ($categoryData['page'] !== 'FOREIGN_PAGE' )) { ?>
									<!-- Multiple Apply button start -->
									<input type="button" onclick="return calloverlayInstitute('<?php echo $collegeId; ?>','MULTIPLE_APPLY_INSTITUTE_LIST_REQUESTE_BROCHURE_OVERLAY_CLICK');" class=" doneBtnReqGray" value="Request E-Brochure"/>
									<!-- Multiple Apply button end -->
								<?php } else { ?>
                                    <!-- ADD BUTTON FOR STUDY ABROAD PAGE -->
                                    <input type="button" value="Send Query" class="sendQueryBtn"  onClick ="<?php echo $onClick?>" />
                            
                                <?php } ?>
                            </div>
							<?php } else { ?>
							<div class="normaltxt_11p_blk_arial float_R " style="width:152px">
								&nbsp;
							</div>
							<?php } ?>
							<div>
								<div style="float:left;width:20px" class="">
									<?php if ( is_array($categoryData) && ($categoryData['page'] !== 'FOREIGN_PAGE' )) { ?> 
										<?php if($college['isSendQuery'] == 1){ ?>
											<!-- Multiple Apply button start --> 
											<input type="checkbox" value="<?php echo $collegeId; ?>" name="reqEbr[]" onclick="checkAllFields(2);" > 
											<!-- Multiple Apply button end --> 
										<?php } else { ?>
											&nbsp;
										<?php } ?>
									<?php }?>
								</div>
								<div class="<?php echo $sponsoredResultClass; ?> " style="<?php echo $sendQueryMargin; ?>;margin-left:30px">
									<div url="<?php echo $url; ?>"
                                    title="<?php echo $collegeName; ?>" type="institute" displayname="<?php echo $collegeDisplayName; ?>"
                                    locationname="<?php echo $collegeCity ; ?>" id="reqEbr_<?php echo $collegeId; ?>" class="normaltxt_11p_blk_arial">
										<a url="<?php echo $url; ?>" title="<?php echo $collegeName; ?>" type="institute" id="anchor_<?php echo $collegeId; ?>" class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>, <span class="fontGreenColor"><?php echo $location /*.' - ' . $country1*/; ?></span>
									</div>
									<?php if(strlen($courseName) != 0) { ?>
									<div class="normaltxt_11p_blk_arial">
										<a href="<?php echo $courseUrl; ?>" title="<?php echo $courseName; ?>" class="blackFont"><?php echo $courseNameDisplayText; ?></a>
									</div>
									<?php } ?>
								</div>
								<?php if($currentCollegeCount < $collegeCount) { ?>
								<div class="lineSpace_8">&nbsp;</div>
								<div class="grayLine"></div>
								<?php } ?>
								<div class="lineSpace_8">&nbsp;</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>				
				<div>					
					<div align="right">
						<div id="pagingIDc" style="padding-top:0px">
							<input type="hidden" id="methodName" value="getFeaturedCollegesForCatgeoryPages" />
							<div id="paginataionPlace2"></div>
						</div>
					</div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
                <div class="quesAnsBulletsSponsored graycolor" style="font-size:11px;">Sponsored Links</div>
			</div>
			<script>
			document.getElementById('startOffSet').value = 0;
			selectComboBox(document.getElementById('countOffset_DD2'), '<?php echo $numColleges; ?>');
			selectComboBox(document.getElementById('countOffset_DD1'), '<?php echo $numColleges; ?>');
			doPagination(<?php echo $count == '' ? 0 : $count ; ?>, 'startOffSet', 'countOffset', 'paginataionPlace1', 'paginataionPlace2', 'methodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 5;?>);
			try {
			var chkAll = document.getElementById('checkAll');
			chkAll.checked = false;
			var checks = document.getElementsByName('reqEbr[]');
			var boxLength = checks.length;
			if (boxLength == 0) {
			document.getElementById('hrefcheckall').parentNode.style.display="none";
			} else {
			document.getElementById('hrefcheckall').parentNode.style.display="inline";
			}
			} catch (ex) {
			}
			</script>
		</div>
	</div>
