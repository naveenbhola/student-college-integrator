<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		if($userId != 0){
			$tempJsArray = array('commonnetwork','ana_common','myShiksha');
			$loggedIn = 1;
		}else{
			$tempJsArray = array('commonnetwork','ana_common','myShiksha');
			$loggedIn = 0;
		}
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header'),
                        'js' => array('common','discussion'),
						'jsFooter'=>    $tempJsArray,
						'title'	=>	'Ask and Answer - Education Career Forum Community ? Study Forum ? Education Career Counselors ? Study Circle -Career Counseling',
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/userProfile'),
						'metaDescription'	=>'Ask Questions on various education and career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.',
						'metaKeywords'	=>'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'callShiksha'=>1,
						'notShowSearch' => true,
						'postQuestionKey' => 'ASK_ASKHOME_HEADER_POSTQUESTION',
						'noIndexNoFollow' => true
					);
		$this->load->view('common/header', $headerComponents);
         	$dataForRankAndReputation = array('loginreputationPoints' => $userDetailsArray[0][0][0][loginreputationPoints],'reputationPoints' => $userDetailsArray[0][0][0][reputationPoints],'rank' => $userDetailsArray[0][0][0][rank]);
		$this->load->view('messageBoard/reputationOverlay',$dataForRankAndReputation);

?>

<?php
		if(isset($isValidUser) && $isValidUser==false){	//Start If block for checking if a valid user
?>
		  <div class="wrapperFxd raised_greenGradient mar_full_10p"> 
			  <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			  <div class="boxcontent_greenGradient">
				  <div class="txt_align_c bld fontSize_18p" style="line-height:100px;">
					No such user exists.
				  </div>
			  </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		  </div>
		  <div style="line-height:100px;">&nbsp;</div>
<?php		 
		}
		else	//In case of a Valid user display name
		{
?>
<script>
overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
overlayViewsArray.push(new Array('user/getUserNameImage','updateNameImageOverlay'));
overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
</script>

<?php
	$isCmsUser = 0;
	if($userGroup === 'cms')$isCmsUser = 1;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".$pageUrl."';";	
	echo "var loginRedirectUrl = '/messageBoard/MsgBoard/userProfile';";
	echo "var loggedIn = '".$loggedIn."';";		
	echo "var loggedInUserId = '".$userId."';";
	echo "var isCmsUser = '".$isCmsUser."';";
	echo "</script> ";
	
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("search"); ?>" type="text/css" rel="stylesheet" />
<input type="hidden" id="pageKeyForSubmitComment" value="ASK_USER_PROFILE_MIDDLEPANEL_SUBMITANSWER" />
<input type="hidden" autocomplete="off" id="showUpdateUserNameImage" value="" name="showUpdateUserNameImage"/>
<input type="hidden" name="questionDetailPage" id="questionDetailPage" value="" />
<div id="userNameImageDiv" style="display:none"></div>
<!-- Start of Dig up and YOU image tooltip overlay -->
<div id = "digUpDownTooltip" class="blur" style="position:absolute;left:0px;z-index:1000;display:none;top:0px;">
  <div class="shadow">
    <div class="content" id="digTooltipContent"></div>
  </div>
</div>
<!-- End of Dig up and YOU image tooltip overlay -->
<script>
var userVCardObject = new Array();
</script>
	
<div style="display:none;">
		<?php $this->load->view('messageBoard/autoSuggestorForInstitute',array('movable'=>'true'));  ?>
</div>


		<?php
			$userProfile = site_url('getUserProfile').'/';
		?>

		<div class="mar_full_10p">

			  <!--Start_Mid_Panel-->
			  <div style="width:100%;">
				  
				  <!--BlogNavigation-->
				  <div id="discussionTabs">

					  <!-- Start of Displaying question/answers block -->
					  <div style="width:100%;">
						  <div class="raised_lgraynoBG">
								  <div class="" id="mainContainer">
										  <!-- start of User details -->
										  <div>
											  <?php 
												  $this->load->view('CA/userProfileDetails');	
											  ?>
										  </div>
										  <div class="lineSpace_20">&nbsp;</div>
										  <!-- start of Wall topics -->
										  <div class="mb5" id="userActivities">
											  <div class="float_L Fnt14 bld"><?php if($userId!=$viewedUserId) echo $viewedUserName;else echo "Your";?> Activities</div>
											  <div id="myPfrId" class="float_R sptrClr">
												  <?php if($tabSelected==''){ ?><span style="color:#000;font-weight:700;">All</span> &nbsp; | &nbsp;<?php }else{ ?><a href="<?php echo $tabURL;?>">All</a> &nbsp; | &nbsp; <?php } ?>
												  <?php if($tabSelected=='Question'){ ?><span style="color:#000;font-weight:700;">Questions</span> &nbsp; | &nbsp;<?php }else{ ?><a href="<?php echo $tabURL;?>/Question">Questions</a> &nbsp; | &nbsp;<?php } ?>
												  <?php if($tabSelected=='Answer'){ ?><span style="color:#000;font-weight:700;">Answers</span> &nbsp; | &nbsp;<?php }else{ ?><a href="<?php echo $tabURL;?>/Answer">Answers</a> &nbsp; | &nbsp;<?php } ?>
												  <?php if($tabSelected=='Comment'){ ?><span style="color:#000;font-weight:700;">Comments</span> &nbsp; | &nbsp;<?php }else{ ?><a href="<?php echo $tabURL;?>/Comment">Comments</a> &nbsp; | &nbsp;<?php } ?>
												  <?php if($tabSelected=='Discussion'){ ?><span style="color:#000;font-weight:700;">Discussions</span> &nbsp; | &nbsp;<?php }else{ ?><a href="<?php echo $tabURL;?>/Discussion">Discussions</a> &nbsp; | &nbsp;<?php } ?>
												  <?php if($tabSelected=='Announcement'){ ?><span style="color:#000;font-weight:700;">Announcements</span><?php }else{ ?><a href="<?php echo $tabURL;?>/Announcement">Announcements</a><?php } ?>
											  </div>                
											  <div class="clear_B">&nbsp;</div>
										  </div>
										  <div class="brd2px">&nbsp;</div>
										  <div><img style="position: relative; top: -2px; left: 20px;" src="/public/images/dnAw.png"></div>
										  <div id="wall" class="w705">
											  <?php
												  $dataArray = array();
 												  if(isset($userDetailsArray) && is_array($userDetailsArray) && is_array($otherUserDetails)){
 													$viewedUserLevel = $userDetailsArray[0][0][0]['ownerLevel'];
 													$viewedUserLevelP = $userDetailsArray[0][0][0]['ownerLevelP'];

 													if($otherUserDetails[0]['avtarimageurl']!='') $viewedUserImage = ($otherUserDetails[0]['avtarimageurl']);else $viewedUserImage = ('/public/images/photoNotAvailable.gif');
 													$dataArrayUser['viewedOwnerDetails'] = array('viewedUserLevel'=>$viewedUserLevel,'viewedUserLevelP'=>$viewedUserLevelP,'viewedUserImage'=>$viewedUserImage);
 												  }
												  $this->load->view('messageBoard/questionHomePageWall',$dataArrayUser);	
												  $paginationParam = 'ForWallQuestion';
											  ?>
										  </div>
										  <!-- end of Wall topics -->
										  <div class="clear_L"></div>
										  <div class="lineSpace_5">&nbsp;</div>

							  </div>
							  <div class="lineSpace_10">&nbsp;</div>
							  <div class="float_R"><b>Disclaimer:</b> <a href="javascript:void(0);" style="color: rgb(112, 112, 112);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">Views expressed by the users above are their own, Info Edge (India) Limited does not endorse the same.</a></div>
							  <div class="clear_B"></div>
							  <div class="lineSpace_10">&nbsp;</div>
					  </div>
					  <!--End of Displaying question/answers block-->
				</div>
				<!--End BlogNavigation-->
			</div>
			<!-- End of Mid Panel -->

		</div>
</div>

<div class="clearFix"></div>
<!--End_Center-->
<?php
}	//End If block for checking if a valid user
?>
<script>
if(typeof(initializeAutoSuggestorInstance) == "function") {
	initializeAutoSuggestorInstance(); //For initiating AutoSuggestor Instance
}
//Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
if(typeof(handleClickForAutoSuggestor) == "function") {
    if(window.addEventListener){
	document.addEventListener('click', handleClickForAutoSuggestor, false);
    } else if (window.attachEvent){
	document.attachEvent('onclick', handleClickForAutoSuggestor);
    }
}    
</script>
<?php 
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?>
