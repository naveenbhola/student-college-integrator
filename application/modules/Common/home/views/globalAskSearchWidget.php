<?php
$showSearchOption = isset($showSearchOption)?$showSearchOption:true;
$titleTextForAskQuestion = isset($titleTextForAskQuestion)?$titleTextForAskQuestion:'normal';
if($titleTextForAskQuestion == 'normal'){
	$illusionalryText = "Type your education related question in this box. Your questions will be answered by Shiksha Counselors, Experts, College Alumni and Students.";
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
$showSearchOption = isset($showSearchOption)?$showSearchOption:true;
$pageName = isset($pageName)?$pageName:'normal';
$listingType = NULL;
$listingTypeId = 0;
$leftMarginForListingPage = 'style="margin-left:15px;"';
if($pageName == 'normal'){
	$illusionalryText = "Type your education related question in this box. Your questions will be answered by Shiksha Counselors, Experts, College Alumni and Students.";
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
	<?php if($showSearchOption): ?>
	<div class="iE6Hack_top" style="z-index:100">
        <div class="shikIcons" id="home_AnATab">
            	<a href="javascript:void(0);" id="askQuestionTabForGlobalAnAWidgecareerOptiont" class="AnASelected" onClick="selectTab(this, 'askQuestionFormForGlobalAnAWidget','AnASelected','');" tab="ask" title="Ask A Question">Ask A Question</a>
				<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="javascript:void(0);" class="" id="searchAnswerTabForGlobalAnAWidget" onClick="selectTab(this, 'searchAnswerFormForGlobalAnAWidget','AnASelected','');" tab="search" title="Search for Answer">Search for Answer</a>
        </div>
    </div>
	<?php else: ?>
	<div><img src="/public/images/aShikQIcon.gif" alingn="absmiddle" /><span href="javascript:void(0);" id="askQuestionTabForGlobalAnAWidgecareerOptiont" class="AnASelected" onClick="selectTab(this, 'askQuestionFormForGlobalAnAWidget','AnASelected','');" tab="ask" style="background:none;color:#000000;font-size:14px;font-weight:bold;"><?php echo $titleOfAskQuestion;?></span></div>
	<div class="lineSpace_5">&nbsp;</div>
	<?php endif; ?>
<!--Start Ask question form -->
	<div style="width:100%" id="askQuestionFormForGlobalAnAWidget" tabContent="askContainer">
	<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionText'),'focus'); if(validateFields(this) != true){return false;} setCookie('homepageQuestion',document.getElementById('questionText').value,0);proceedToPostQuestion(this,'questionText');">
		<div class="float_R" style="width:85px;height:53px">
			<div style="width:100%">
                            <?php $requestUrl = (string)$_SERVER['REQUEST_URI'];
                            $check = (strrpos($requestUrl,"testprep")!='')?'found':'notfound';
                            if($check == 'found'){
                                $eventLabelForGA = 'TEST_PREP_PAGE';
                            }else{
                                $eventLabelForGA = 'CATEGORY_PAGE';
                            }
                            ?>
				<input type="submit" value="Ask Now" onClick="trackEventByGA('ASK_NOW_BUTTON','<?php echo $eventLabelForGA?>');" class="homeShik_spiritAll homeShik_AskNowBtn" />
			</div>
		</div>
		<div style="margin-right:95px">
			<div class="float_L" style="width:100%">
				<div <?php echo $leftMarginForListingPage; ?>>
					<div style="position:relative;top:-2px;*top:-1px;z-index:1">
						<div><textarea name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" type="text" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true" style="width:95%;height:64px;color:#ada6ad;overflow:auto;" default="<?php echo $illusionalryText; ?>" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');"><?php if($questionText != ''){ echo $questionText;  }else{ echo $illusionalryText; } ?></textarea></div>
						<div class="row errorPlace">
							<div class="errorMsg" id="questionText_error"> </div>
						</div>	
						<div style="font-size:10px;color:#676767;padding:1px 0 4px 0">
							<table width="100%" cellpadding="0" border="0">
							<tr>
							<td><span id="questionText_counter"><?php echo $questionCharacterCounter; ?></span> out of 140 characters</td>
							<td><div align='right'><span align="right">Make sure your question follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
							</tr></table>
						</div>
						<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="hackParameter"/>
					</div>
				</div>
			</div>
		</div>
		<div class="clear_L">&nbsp;</div>	
	</form>
	</div>
	<!-- End Ask question form -->
	<!--Start Search anwer form -->
	<div style="width:100%;display:none;" id="searchAnswerFormForGlobalAnAWidget" tabContent="searchContainer">
		<div class="float_R" style="width:85px;height:53px">
			<div style="width:100%">
				<input type="button" value="Search" class="homeShik_spiritAll homeShik_searchAnABtn" onClick="searchForGlobalAnAWidget();" />
			</div>
		</div>
		<div style="margin-right:95px">
			<div class="float_L" style="width:100%">
				<div style="margin-left:15px">
					<div style="position:relative;top:-2px;z-index:1">
						<div><textarea name="keywordForGlobalAnAWidget" id="keywordForGlobalAnAWidget" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');" default="Type your question or keywords in this box and click Search to find your answer from similar question previously answered." style="width:99%;height:64px;color:#ada6ad;">Type your question or keywords in this box and click Search to find your answer from similar question previously answered.</textarea></div>
						<input type="hidden" name="searchTypeForGlobalAnAWidget" id="searchTypeForGlobalAnAWidget" value="question" />
						<div class="row errorPlace">
							<div id="keywordForGlobalAnAWidget_error" class="errorMsg">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="clear_B">&nbsp;</div>
	<!--End Search anwer form -->
