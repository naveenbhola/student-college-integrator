<?php 
	if(strcmp($editFlag,'true')== 0){
		$jsArray = array('cityList','common','myShiksha','alerts','facebook','CalendarPopup');
		$title='Accounts & Settings';
	}else{
		$jsArray = array('cityList','common','myShiksha','facebook','CalendarPopup');
		$title=$userDetails['displayname']."'s profile page";
	}
	$bannerProperties = array('pageId'=>'ACCOUNT_SETTING', 'pageZone'=>'HEADER');
	$headerComponents = array(
							//'css'	=>	array('header','raised_all','mainStyle','common'),
							'css'   => array('user','calender_view'),
							'js'	=>	$jsArray,
							'jsFooter'=>	array('commonnetwork'),
							'title'	=>	$title,
				                        'taburl' =>  site_url(),
							'tabName'	=>	'',
							'product'	=> $tabToSelect,
							'metaKeywords'	=>'Some Meta Keywords',
							'bannerProperties' => $bannerProperties,
			    			        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                            				'callShiksha'	=> true
						);
	$this->load->view('common/homepage', $headerComponents);
?>
<div class="lineSpace_8">&nbsp;</div>
<?php
	//$this->load->view('user/myShikshaSearchPanel');
	$this->load->view('network/mailOverlay');
	if($loggedInUserId != 0)
		$loggedIn = 1;
	else
		$loggedIn = 0;
	
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	echo "<script language=\"javascript\"> "; 	
	echo "var COMPLETE_INFO = ".$quickSignUser.";";	
	echo "var URLFORREDIRECT = '".base64_encode(site_url('user/MyShiksha/index'))."';";	
	echo "</script> ";
	$publicProfilePreference = array();	
	foreach($myShikshaPreferences as $myShikshaPreference) {
		$componentName = $myShikshaPreference['component'];
		$componentDisplay = $myShikshaPreference['display'];
		//$componentPosition = $myShikshaPreference['position'];
		//$componentItemCount = $myShikshaPreference['item_count'];
		$publicProfilePreference[$componentName] = $componentDisplay;
	}
?>

<input type = "hidden" autocomplete="off" id = "myuserId" value = "<?php echo $userId ?>"/>
<script>
		var SITE_URL = '<?php echo base_url() ."/";?>';
		var BASE_URL = SITE_URL;
		var editFlag = '<?php echo $editFlag; ?>';
		var displayName = '<?php echo addcslashes($userDetails['displayname'],"'"); ?>';
		var loggedInUserId = '<?php echo $loggedInUserId; ?>';
</script>
<!--Start_Center-->
<div class="mar_full_10p">
	<!--Start_Right_Panel-->
	<div style="width:120px;float:right">
		<?php     
		        $bannerProperties = array('pageId'=>'ACCOUNT_SETTING', 'pageZone'=>'SIDE');
		        $this->load->view('common/banner.php', $bannerProperties);
		?>

	</div>
	<!--End_Right_Panel-->

	<!--Start_Left_Panel-->
	<div id="left_Panel" style="margin-left:0px;">
	<!-- dif for tooltip -->
	<div id="flotingTooltip" class="user-tooltip" style="display:none;">&nbsp;</div>
	<!-- div for floating tooltip -->
	<?php
		$this->load->view('user/myShikshaLeftPanel');
	?>
	</div>
	<!--End_Left_Panel-->
	
	<!--Start_Mid_Panel-->
	<div style="padding-left:14px; float:left; width:650px;">
			<?php if(strcmp($editFlag,'true')==0): ?>
			<div class="lineSpace_16 myProfile">
					<div class="normaltxt_11p_blk float_R txt_align_r" style="margin-right:10px;">               			
						<img src="<?php echo SHIKSHA_HOME;?>/public/images/view_myProfile.gif" width="19" height="16" align="absmiddle"/> 
						<a href="<?php echo SHIKSHA_HOME.'/publicProfile/'.$userDetails['displayname'];?>">View my Profile as others see it</a>
				</div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<?php endif; ?>
			<div>
			<!--account&setting change-->
			<?php if($mentorshipStatus){ ?>
                        <div class="account-settings-tabs" id="mentorshipMainSection">
                        	<ul>
                            	<li><a href="javascript:void(0);" class="" id="accountSettingTab" onClick="showMyMentorshiprProgram('accountSettingTab','mentorshipTab');">MY ACCOUNTS & SETTINGS</a></li>
                                <li><a href="javascript:void(0);" class="last active" id="mentorshipTab" onClick="showMyMentorshiprProgram('mentorshipTab','accountSettingTab');">MY MENTORSHIP </a></li>
                            </ul>
                            <div style="width:100%" align="center" class="loaderImage">
                            	<img src="<?php echo SHIKSHA_HOME; ?>/public/images/ajax-loader.gif" />
                            </div>
                            <div style="width:100%;font-size:14px;" align="center" class="loaderImage">loading...</div>
                            <div id="mentorshipSection" style="display:none;">
								<?php echo $this->load->view('CA/mentorship/mentorAccountSetting');?>                            
                            </div>
                        </div>
            <?php } ?>
			<div style="float:left; width:99%;<?php if($mentorshipStatus){ echo "display:none";}?>" class="notMentorshipProgram">
				<div class="float_R" style="width:49%">
					<?php 
					$this->load->view('common/addCollege');
					if(strcmp($editFlag,'true')== 0)
						$this->load->view('user/myInbox');	

					?>
				</div>
				<!--End_Right_Panel-->
				<!--Mid_Left_Panel-->
				<div class="float_L" style="width:49%" align="left">
					<?php 
						if(strcmp($editFlag,'true')== 0)
							$this->load->view('user/myPendingRequests');
					?>
					
				</div>
				<!--End_Mid_Left_Panel-->
				<div class="clear_R"></div>
			</div>
			<div  class="notMentorshipProgram" <?php if($mentorshipStatus){ echo "style='display:none;'";}?>>
					<div style="display:inline; float:left; width:99%">
					<?php
						if(($publicProfilePreference['myShikshaDiscussion'] == 1) || ($editFlag == 'true'))
							$this->load->view('user/myShikshaDiscussion');
						
						if(($publicProfilePreference['myShikshaEvents'] == 1) || ($editFlag == 'true'))
							$this->load->view('user/myShikshaEvents');
						
						if(($publicProfilePreference['myShikshaNetwork'] == 1) || ($editFlag == 'true'))
							$this->load->view('user/myShikshaNetwork');
						
//						if(($publicProfilePreference['myShikshaCollgenetwork'] == 1) || ($editFlag == 'true'))
//							$this->load->view('user/myColleges');
						
						if(strcmp($editFlag,'true')== 0)
							$this->load->view('user/myShikshaAlerts');
						
						/*if(strcmp($editFlag,'true')== 0)
							$this->load->view('user/myShikshaListings');
						*/	
						
					?>		
					</div>
					<div class="clear_L"></div>
				<div class="lineSpace_20">&nbsp;</div>	
				<?php
					$bannerProperties1 = array('pageId'=>'ACCOUNT_SETTING', 'pageZone'=>'FOOTER');
					$this->load->view('common/banner',$bannerProperties1);  	
				?> 
			</div>
			</div>
	</div>
	<!--End_Mid_Panel-->
</div>
<script>
<?php
	$componentMap = array();
	foreach($myShikshaPreferences as $myShikshaPreference) {
		$componentName = $myShikshaPreference['component'];
		$componentDisplay = $myShikshaPreference['display'];
		//$componentPosition = $myShikshaPreference['position'];
		//$componentItemCount = $myShikshaPreference['item_count'];
		if($componentDisplay == 0 && $editFlag == 'true') {
			echo "if(document.getElementById('". $componentName ."')) {document.getElementById('". $componentName ."').checked = false;setOpacity(document.getElementById('". $componentName ."Box'),0.4);}";
		} else {
			echo "if(document.getElementById('". $componentName ."')) {document.getElementById('". $componentName ."').checked = true;setOpacity(document.getElementById('". $componentName ."Box'),1); }";
		}
	}
?>	
</script>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);  	
?> 
<script>
function showMyMentorshiprProgram(showId, hideId){
	if(showId=='accountSettingTab'){
		$j('#accountSettingTab').attr('onClick','');
		$j('#mentorshipTab').attr('onClick',"showMyMentorshiprProgram('mentorshipTab','accountSettingTab')");
	}else{
		$j('#mentorshipTab').attr('onClick','');
		$j('#accountSettingTab').attr('onClick',"showMyMentorshiprProgram('accountSettingTab','mentorshipTab')");
	}
	$j('.loaderImage').hide();
	$j('#'+showId).addClass('active');
	$j('#'+hideId).removeClass('active');
	if($j('#mentorshipSection:visible').length == 0){
		$j('#mentorshipSection').fadeIn();
		$j('.notMentorshipProgram').hide();
	}else{
		$j('#mentorshipSection').fadeOut('100');
		$j('.notMentorshipProgram').show();
	}
	changeUI('first');
}
</script>
<?php if($mentorshipStatus){ ?>
	<script>
	var mentorId = '<?php echo $mentorId;?>';
	var scheduleId = '<?php echo $slotData['id'];?>';
	var slotId = '<?php echo $slotData['slotId'];?>';
	var userType = '<?php echo $slotData['userType'];?>';
	showMyMentorshiprProgram('mentorshipTab','accountSettingTab');
	if($j('#mentee-old-chat-container').length > 0 && $j('#mentee-old-chat-container').height() > 300) {
		$j('#mentee-old-chat-container').css({'overflow-y': 'scroll', 'height': '300px'});
	}
	</script>
<?php } ?>

