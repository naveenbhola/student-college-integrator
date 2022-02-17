<?php
	global $categoryParentMap;
	foreach($categoryParentMap as $categoryName => $category) {
		$tempCategoryId = $category['id'];
		if($tempCategoryId == $categoryId){
			$selectedCategoryName = $categoryName;
		}
	}
	$showHeaderOfWidget = isset($showHeaderOfWidget)?$showHeaderOfWidget:true;
	$editorialBinQuestionsData = is_array($editorialBinQuestions['results'])?$editorialBinQuestions['results']:array();
    $currentShown = $editorialBinQuestions['currentShown'];
	$lowerLimit = $editorialBinQuestions['lowerLimit'];
	$upperLimit = $lowerLimit+count($editorialBinQuestionsData)-1;
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
	$pageKeyForAskQuestion = $pageKeyInfo.'POSTQUESTION';
	$pageKeyForInlineAnswer = $pageKeyInfo.'InlineAnswer';


	$this->load->view('common/userCommonOverlay');
	$this->load->view('network/mailOverlay',$data);
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
	echo "var loggedInUserId = ".$userId.";";
	echo "</script> ";
?>
<script>
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
</script>
<input type="hidden" id="currentShownDivForGlobalAnAWidget" autocomplete="off" value="<?php echo $editorialBinQuestions['randNum']; ?>" />
<input type="hidden" id="widthForGlobalAnAWidget" autocomplete="off" value="0" />
<input type="hidden" id="selectedCategoryForGlobalAnAWidget" autocomplete="off" value="<?php echo $categoryId; ?>" />
<input type="hidden" id="lowerLimitForGlobalAnAWidget" autocomplete="off" value="<?php echo $lowerLimit; ?>" />
<input type="hidden" id="upperLimitForGlobalAnAWidget" autocomplete="off" value="<?php echo $upperLimit; ?>" />
<input type="hidden" id="totalRowsForGlobalAnAWidget" autocomplete="off" value="<?php echo $editorialBinQuestions['totalRows']; ?>" />
<div>

<?php

$titleTextForAskQuestion = isset($titleTextForAskQuestion)?$titleTextForAskQuestion:'normal';
if($titleTextForAskQuestion == 'normal'){
	$illusionalryText = "Need expert guidance on education or career? Ask our experts here.";
}else if($titleTextForAskQuestion == 'institute'){
	$illusionalryText = "Type your question about ".$extraParamForTitle."  here. Your question will be answered by Shiksha Counselors, Experts, College Alumni and other students.";
}
$questionText = '';
if(isset($_COOKIE['commentContent']) && ($questionText == '')){
	$questionText = $_COOKIE['commentContent'];
	if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
		$questionText = '';
	}
}
$pageName = isset($pageName)?$pageName:'normal';
$listingType = NULL;
$listingTypeId = 0;
$leftMarginForListingPage = 'style="margin-left:15px;"';
if($pageName == 'normal'){
	$illusionalryText = "Need expert guidance on education or career? Ask our experts here.";
}else if(($pageName == 'institute') || ($pageName == 'course')){
	$questionText = '';
	$extraParameter = unserialize($extraParam);
	$illusionalryText = "Type your question about ".$extraParameter['titleOfInstitute']."  here. Your question will be answered by Institute, Shiksha Counselors, Experts, College Alumni and other students.";
	$titleOfAskQuestion =  'Ask '.$extraParameter['titleOfInstitute'];
	$listingType = $extraParameter['listingType'];
	$listingTypeId = $extraParameter['listingTypeId'];
	$leftMarginForListingPage = 'style="margin-left:27px;"';
}

$questionText = str_replace('"','&quot;',$questionText);
$questionCharacterCounter = '0';
if($questionText != ''){
	$questionCharacterCounter = strlen($questionText);
}
$illusionalryText = str_replace(array("\r\n","\n","\r"),array(" "," ",""),$illusionalryText);
?>


<div style="width: 100%;">
	<div class="qnaCup"><b>Shiksha Cafe</b><br><span class="Fnt13 bld">Interact with experts or simply hang out with fellow students!</span></div>
	<div class="blueLine2px">&nbsp;</div>
	<div>	
		<div style="z-index: 100;" class="iE6Hack_top">
		      <div id="home_AnATab">
			      <a title="Ask A Question" tab="ask" onclick="changeHomeCafeTab(this,'question');" class="AnASelected" id="askQuestionTabForGlobalAnAWidgecareerOptiont" href="javascript:void(0);">
				  <b class="aaq">Ask A Question</b>
			      </a>
			      <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			      <a title="Discuss a Topic" tab="search" onclick="changeHomeCafeTab(this,'discuss');" id="searchAnswerTabForGlobalAnAWidget" class="" href="javascript:void(0);"><b class="sfa">Discuss a Topic</b></a>
			      <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			      <a title="Announce" tab="search" onclick="changeHomeCafeTab(this,'announce');" id="announceTabForGlobalAnAWidget" class="" href="javascript:void(0);"><b class="ann">Announce</b></a>
		      </div>
		</div>

		<!--Start Ask question form -->
		<div tabcontent="askContainer" id="askQuestionFormForGlobalAnAWidget" style="width: 100%;">
		<form onsubmit="checkTextElementOnTransition($('questionText'),'focus'); if(validateFields(this) != true){return false;} else { setCookie('homepageQuestion',document.getElementById('questionText').value,0);proceedToPostQuestion(this,'questionText'); }" action="" method="get" name="askQuestionForm" id="askQuestionForm">
			<div style="width: 85px; height: 53px;" class="float_R">
				<div style="width: 100%;">
					<input type="submit" class="homeShik_spiritAll homeShik_AskNowBtn" value="Ask" onClick="trackEventByGA('ASK_NOW_BUTTON','SHIKSHA_HOME_PAGE');">
				</div>
			</div>
			<div style="margin-right: 95px;">
				<div style="width: 100%;" class="float_L">
					<div style="margin-left: 15px;">
						<div style="position: relative; top: -2px; z-index: 1;">
							<div><textarea name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" type="text" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true" style="width:99%;height:47px;color:#ada6ad;overflow:auto;" default="<?php echo $illusionalryText; ?>" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');"><?php if($questionText != ''){ echo $questionText;  }else{ echo $illusionalryText; } ?></textarea></div>
							<div class="row errorPlace">
								<div id="questionText_error" class="errorMsg"> </div>
							</div>	
							<div style="font-size: 10px; color: rgb(103, 103, 103); padding: 1px 0pt 4px;">
								<table cellpadding="0" border="0" width="100%">
								<tbody><tr>
								<td><span id="questionText_counter">0</span> out of 140 characters</td>
								<td><div align="right"><span align="right">Make sure your question follows <a onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');" href="javascript:void(0);">Community Guidelines</a>&nbsp;</span></div></td>
								</tr></tbody></table>
							</div>
							<input type="hidden" value="hackParameter" id="referalUrlForAskQuestionFromHeader" name="referalUrlForAskQuestionFromHeader">
						</div>
					</div>
				</div>
			</div>
			<div class="clear_L">&nbsp;</div>	
		</form>
		</div>
		<!-- End Ask question form -->

		<!-- Start Discussion form -->
		<div tabcontent="discussContainer" id="discussFormForGlobalAnAWidget" style="width: 100%;display:none;">
		<form onsubmit="checkTextElementOnTransition($('discussionText'),'focus'); if(validateFields(this) != true){return false;} else{ setCookie('homepageDiscussionTitle',base64_encode(document.getElementById('discussionText').value),0);}" action="<?php echo SHIKSHA_ASK_HOME;?>" method="post" name="discussForm" id="discussForm">
			<div style="width: 85px; height: 53px;" class="float_R">
				<div style="width: 100%;">
					<input type="submit" class="homeShik_spiritAll homeShik_AskNowBtn" value="Post">
				</div>
			</div>
			<div style="margin-right: 95px;">
				<div style="width: 100%;" class="float_L">
					<div style="margin-left: 15px;">
						<div style="position: relative; top: -2px; z-index: 1;">
							<div><textarea name="discussionText" id="discussionText" autocomplete="off" value="" type="text" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Topic" maxlength="100" minlength="5" required="true" style="width:99%;height:47px;color:#ada6ad;overflow:auto;" default="Enter Discussion Topic" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');">Enter Discussion Topic</textarea></div>
							<div class="row errorPlace">
								<div id="discussionText_error" class="errorMsg"> </div>
							</div>	
							<div style="font-size: 10px; color: rgb(103, 103, 103); padding: 1px 0pt 4px;">
								<table cellpadding="0" border="0" width="100%">
								<tbody><tr>
								<td><span id="discussionText_counter">0</span> out of 100 characters</td>
<!-- 								<td><div align="right"><span align="right">Make sure your discussion follows <a onclick="return popitup('http://172.16.3.228:80/shikshaHelp/ShikshaHelp/communityGuideline');" href="javascript:void(0);">Community Guidelines</a>&nbsp;</span></div></td> -->
								</tr></tbody></table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear_L">&nbsp;</div>	
		</form>
		</div>
		<!-- Start Discussion form -->

		<!-- Start Announcement form -->
		<div tabcontent="announcementContainer" id="announcementFormForGlobalAnAWidget" style="width: 100%;display:none;">
		<form onsubmit="checkTextElementOnTransition($('announcementText'),'focus'); if(validateFields(this) != true){return false;} else{ setCookie('homepageAnnouncementTitle',base64_encode(document.getElementById('announcementText').value),0);}" action="<?php echo SHIKSHA_ASK_HOME;?>" method="post" name="announcementForm" id="announcementForm">
			<div style="width: 85px; height: 53px;" class="float_R">
				<div style="width: 100%;">
					<input type="submit" class="homeShik_spiritAll homeShik_AskNowBtn" value="Shout">
				</div>
			</div>
			<div style="margin-right: 95px;">
				<div style="width: 100%;" class="float_L">
					<div style="margin-left: 15px;">
						<div style="position: relative; top: -2px; z-index: 1;">
							<div><textarea name="announcementText" id="announcementText" autocomplete="off" value="" type="text" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Topic" maxlength="100" minlength="5" required="true" style="width:99%;height:47px;color:#ada6ad;overflow:auto;" default="Enter Announcement Topic" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');">Enter Announcement Topic</textarea></div>
							<div class="row errorPlace">
								<div id="announcementText_error" class="errorMsg"> </div>
							</div>	
							<div style="font-size: 10px; color: rgb(103, 103, 103); padding: 1px 0pt 4px;">
								<table cellpadding="0" border="0" width="100%">
								<tbody><tr>
								<td><span id="announcementText_counter">0</span> out of 100 characters</td>
<!-- 								<td><div align="right"><span align="right">Make sure your announcement follows <a onclick="return popitup('http://172.16.3.228:80/shikshaHelp/ShikshaHelp/communityGuideline');" href="javascript:void(0);">Community Guidelines</a>&nbsp;</span></div></td> -->
								</tr></tbody></table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear_L">&nbsp;</div>	
		</form>
		</div>
		<!-- Start Discussion form -->


	</div>
	<div style="border-top: 1px solid rgb(241, 241, 241);">&nbsp;</div>
</div>


<!--Start_Cafe Wall-->
<div style="width:100%">
    <?php $this->load->view('messageBoard/questionHomePageShiksha');	?>
	<?php //echo Modules::run('CafeBuzz/CafeBuzz/index'); ?>
</div>
<!--End_Cafe Wall-->
<!-- End by Ankur for Homepage-Rehash -->

<script>
var pageKeyForInlineAnswer = '<?php echo $pageKeyForInlineAnswer; ?>';
var categoryIdSelected = '<?php echo $categoryId ?>';
</script>
