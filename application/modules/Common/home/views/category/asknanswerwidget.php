<?php $pageKeyForAskQuestion = 'STUDYABROAD_STUDYABROADMAIN_ASK_INSTITUTE_POSTQUESTION'; ?>
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />
<?php
$questionText = '';
if(isset($_COOKIE['commentContent']) && ($questionText == '')){
	$questionText = $_COOKIE['commentContent'];
	if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
		$questionText = '';
	}
}
$questionCharacterCounter = '0';
if($questionText != ''){
	$questionCharacterCounter = strlen($questionText);
}
if($countryNameSelected == "UK-ireland"){
	$countryNameSelected = "UK-Ireland";
}
                                $msgBoards['results'] = $msgBoards['results'][0]; 
                                if(is_array($msgBoards['results'])) {
                                if(count($msgBoards['results']) && !empty($msgBoards['results'][0])) { ?>
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                	
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt14" style="padding-left:10px"><b> Ask & Answer about <?php echo $countryNameSelected?></b></span></div>
                                        <div class="mlr10">
                                            <div class="lh10"></div>
                                            <div class="fcOrg"><b>Have your queries answered by Experts. Ask now</b></div>
	<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionText'),'focus'); if(validateFields(this) != true){return false;} setCookie('homepageQuestion',document.getElementById('questionText').value,0); proceedToPostQuestion(this,'questionText');">
                                            <div style="height:110px;overflow:hidden" id = "mainquestiondivid">
                                                <div style="margin:5px 0 0">
                                                <textarea style="overflow:auto;width:98%;height:40px;color:#ada6ad" name="questionText" id = "questionText" autocomplete = "off" default = "Type your education related question in this box" value = "<?php echo $questionText;?>" onfocus = "showInlineBox('focus')" onBlur = "showInlineBox('blur')" type = "text" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true"><?php if($questionText != ''){ echo $questionText;}else {echo 'Type your education related question in this box'; }?></textarea>
                                                </div>

						<div class="row errorPlace">
							<div class="errorMsg" id="questionText_error"> </div>
						</div>
                        <div>

                                                 <div class = "Fnt10 fcGray" style = "color:#676767"><span id="questionText_counter"><?php echo $questionCharacterCounter; ?></span> out of 140 characters</div>
                                                <div align="right">
                                                <input type="submit" onClick="trackEventByGA('ASK_NOW_BUTTON','STUDY_ABROAD');" class="spirit_header shik_AskNowBtn" value="&nbsp;"/>
                                                </div>
                                            </div>
                                            </div>
						<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="hackParameter"/>
						<input name="listingTypeForAskQuestion" id="listingTypeForAskQuestion" type="hidden" value=""/>
						<input name="listingTypeIdForAskQuestion" id="listingTypeIdForAskQuestion" type="hidden" value="0"/>
                                        </form>
                                            <div class="lh10"></div>
                                            <!--Start_Repeating_Question-->
                                            <?php
                                            for($i = 0 ;$i < count($msgBoards['results']);$i++) {
                                            if(is_array($msgBoards['results'][$i])) {
                                            ?>
                                            <div class="hSep pb10 mb10">
                                                <div class="shik_bgQuestionSign">
                                                    <div><a href="<?php echo $msgBoards['results'][$i]['question']['url']?>">
                                                    <?php echo $msgBoards['results'][$i]['question']['msgTxt']; ?>
                                                    </a></div>
	        <?php $userProfile = site_url('getUserProfile').'/'.$msgBoards['results'][$i]['question']['displayname']; ?>
                                                    <div class="fs11 drkGry">Asked by 
													<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $msgBoards['results'][$i]['question']['userid']; ?>');}catch(e){}" >
													  <a href="<?php echo $userProfile;?>"><?php echo $msgBoards['results'][$i]['question']['displayname']?></a>
													</span>
													, <?php echo $msgBoards['results'][$i]['question']['viewCount']?> Views</div>
										
                                                </div>
                                                <div class="aMarked">
                                                    <div><?php echo substr($msgBoards['results'][$i]['answer']['msgTxt'],0,200).'...'?></div>
                                                </div>
                                                <div class="pl33">
                                                    <div id="bgRtng">
                                                        <a href="#" class="rtGrn" onClick = "updateDig(this,<?php echo $msgBoards['results'][$i]['answer']['msgId']?>,1);return false;"><?php echo $msgBoards['results'][$i]['answer']['digUp']?></a><a href="#" class="rtRed" onClick = "updateDig(this,<?php echo $msgBoards['results'][$i]['answer']['msgId']?>,0);return false;"><?php echo $msgBoards['results'][$i]['answer']['digDown']?></a>
                                                    </div>
                                                </div>
                                                <div class="clear_B"></div>                                                                                               
			<div id="confirmMsg<?php  echo $msgBoards['results'][$i]['answer']['msgId']; ?>" class="errorMsg mar_left_10p"></div>
                                                <div class="pl33"><a href="<?php echo $msgBoards['results'][$i]['question']['url']?>"><b>Answer Now</b></a> <a href="<?php echo $msgBoards['results'][$i]['question']['url']?>">View <?php echo $msgBoards['results'][$i]['question']['noOfAnswer']?> Answers</a></div>                                                
                                            </div>
                                            <?php }} ?>
                                            <!--End_Repeating_Question-->
                                           <?php if(strpos($countryId,',') === false) { ?>
                                            <div class="txt_align_r"><a href="<?php echo SHIKSHA_ASK_HOME_URL. '/messageBoard/MsgBoard/discussionHome/1/2/'.$countryId?>">View all Questions</a></div>
                                          <?php } else
                                           {?>
                                            <div class="txt_align_r"><a href="#" onClick = "showCountryOverlay('ask',this);return false;">View all Questions</a></div>
                                            <?php } ?>
                                        </div>
                                        <div class="lh10"></div>
                                    </div>
                                </div>
                                <!--End_AnA-->
                                <div class="lh10"></div>
                                        <?php }} ?>
                                        <script>

function showInlineBox(action)
{
    if(action == 'focus')
    {
            if(document.getElementById('questionText').value == "Type your education related question in this box")
            {
                document.getElementById('questionText').value = '';
            }
            document.getElementById('questionText').style.color = '';
//        document.getElementById('hiddenbuttonforask').style.display = '';
    }
    else
    {
        if(document.getElementById('questionText').value == "")
        {
            document.getElementById('questionText').value = 'Type your education related question in this box';
            document.getElementById('questionText').style.color = '#ada6ad';
        }
       // document.getElementById('mainquestiondivid').style.height = 60 + 'px';
//        document.getElementById('hiddenbuttonforask').style.display = 'none';

    }
}
</script>
