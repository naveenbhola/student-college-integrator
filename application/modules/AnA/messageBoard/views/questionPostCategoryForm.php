<?php
$entityType = (isset($entityType))?$entityType:'question';
switch ($entityType){
  case 'question': $buttonText = 'Post Question';
		    $checkBoxText = "Send me an email if someone answers my question";
		    $titleWord = "Question";
		    $displayExtraText = "display:block";
		    $displayCity = "display:none";
		    $fromOthers = "user";
		   break;
  case 'discussion': $buttonText = 'Post';
		    $checkBoxText = "Notify me when someone posts a new comment on my discussion";
		    $titleWord = "Discussion";
		    $displayExtraText = "display:none";
		    $displayCity = "display:none";
		    $fromOthers = "discussion";
		   break;
  case 'announcement': $buttonText = 'Post';
		    $checkBoxText = "Notify me when someone posts a new comment on my announcement";
		    $titleWord = "Announcement";
		    $displayExtraText = "display:none";
		    $displayCity = "display:none";
		    $fromOthers = "announcement";
		   break;
  case 'review': $buttonText = 'Post Review';
		    $checkBoxText = "Notify me when someone posts a new comment on my discussion";
		    $titleWord = "Discussion";
		    $displayExtraText = "display:none";
		    $displayCity = "display:none";
		    $fromOthers = "review";
		   break;
  case 'eventAnA': $buttonText = 'Post Event';
		    $checkBoxText = "Notify me when someone posts a new comment on this event";
		    $titleWord = "Event";
		    $displayExtraText = "display:none";
		    $displayCity = "display:block";
		    $fromOthers = "eventAnA";
		   break;
}
?>

<div id="category_page_ana_question_post_display_count" class="txt_align_r"></div>
<form id="askQuestionFormPost" autocomplete="off" onSubmit="
		    $('mainCreateTopicButton').disabled = true;
		    if(!isValidatingPost){
		    if(AnA_PQ_validateCreateTopic(document.getElementById('askQuestionFormPost'))){
			return true;
		    }
		    else
		    {
			$('mainCreateTopicButton').disabled = false;
			return false;
		    }
		    }
		    " method="post" action="/messageBoard/MsgBoard/createTopic" >
<input type="hidden" name="topicDesc" value="" id="topicDesc1" />
<input type="hidden" name="editit" value="-1" id="editit" />
<input type="hidden" name="listingType" value="<?php echo $listingType; ?>" id="listingType" />
<input type="hidden" name="listingTypeId" value="<?php echo $listingTypeId; ?>" id="listingTypeId" />
<input type="hidden" name="fromOthers" value="<?php echo $fromOthers;?>" id="fromOthers" />
<input type="hidden" name="topicDescription" value="" id="topicDescription" />
<input type="hidden" name="mentionedUsersList" value="" id="mentionedUsersList" />
<input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'>
<div style="width:680px">
        <div>
	      <!--country id will be 2 for all articles---->
		  <div style="display: none;">
			<span><b><?php echo $titleWord;?> is related to : <span class="redcolor">*</span></b></span>
			<span style="padding-left:5px"><input type="radio" name="siI" onclick="showHideCountry(); " id="study_india" value="study_india" style="position:relative;top:2px" checked /> Study in India</span>
			<span style="padding-left:5px;">
			  <input type="radio" onclick="showHideCountry();" id="study_abroad" value="study_abroad"  name="siI" style="position:relative;top:2px" /> Study Abroad
			</span>
		  </div>
		  <div style="width:100%">
			  <div id="country_combo" style="display:none;">
			  <select name="countryListForCreateTopic[]" id="countryListForCreateTopic" tip="question_country" style="width:139px;" caption="country" validate="validateSelect" onChange="checkDropDown(this,''); ">
			  <option value="">Select Country</option>
			  <?php
			  foreach($countryList as $country => $value) {
				  $countryId = $country; $countryName = $value;
				  if (($countryId != '1') && ($countryId != '2')) {
				  ?>
				  <option value="<?php echo $countryId; ?>">
				  <?php echo $countryName; ?></option>
				  <?php
				  }
				  }
				  ?>
			  </select>
			  </div>
			  <div class="row" style="display:none;">
			  <div id="countryListForCreateTopic_error" class="errorMsg"></div>
			  </div>
		  </div>
            <div style="width:100%;" id="category-subcategory-div-ana">
                <div style="padding-top: 0px;">
                <div>
                    <div style="float:left;width:295px;margin-right:16px">
                        <div style="width:100%">
                            <div style="padding-top:10px"><b>Category <span class="redcolor">*</span></b></div>
                            <div style="padding-top:3px">
                                <select size="10" required="true" validate="validateSelect" name="catselect" style="width:290px;height:155px" caption="category" minlength="1" id = "catselect" onChange = "changeSelection('category',this.value);">
				<?php $select_default = '';
				if(!$question_predicted_category) $select_default='selected';
				?>
                                <option value = '' selected="<?php echo $select_default;?>">Select a Category</option>
                                <?php 
				foreach($categoryList as $value) {
				$selected = '';
                                if($value['parentId'] == 1){
                                if($question_predicted_category == $value['categoryID'])$selected = 'selected';
				?>
                                <option value = "<?php echo $value['categoryID'] ;?>" <?php echo $selected;?> > <?php echo $value['categoryName']?></option>
                                <?php }
                                }
                                ?>
                                </select>
                                <div style="margin-top:2px;display:none;">
                                    <div class="errorMsg" id="catselect_error"></div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div style="float:left;width:225px">
                        <div style="width:100%">
                            <div style="padding-top:10px"><b>Sub Category <span class="redcolor">*</span></b></div>
                            <div style="padding-top:3px">
                                <select size="10" required="true" validate="validateSelect" style="width:350px;height:155px"
                                 id = "subcatselect" name="selectCategory" caption="sub category" minlength="1" onClick = "checkDropDown(this,'');" onChange = "changeSelection('subcategory',this.value);"><option value = '' selected="selected">Select a sub category</option>
                                    </select>
                                    <div style="margin-top:2px;display:none;">
                                        <div class="errorMsg" id= "subcatselect_error"></div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
                <div style="line-height:10px;overflow:hidden">&nbsp;</div>
            </div>
        </div>
		</div>
<div style="padding-top:10px">
    <div style="<?php echo $displayExtraText;?>">Question posted will be answered by shiksha experts &amp; users</div>

    <!-- Code for Facebook integration -->
    <?php $isChecked = 'checked';
	  if(isset($_COOKIE['facebookCheck']) &&  $_COOKIE['facebookCheck'] == 'no'){
	    $isChecked = ''; } 
    ?>
  <!--  <div style="padding-top:5px;padding-bottom:5px;">
	<input id="facebookCheck" name="facebookCheck" value="true" type="checkbox" <?php //echo $isChecked; ?> style="position:relative;top:2px;left:-4px" onClick="setFacebookCheck('');"/>Also post this on my Facebook wall
    </div>-->
    <!-- Code End for Facebook integration -->
    <div style="padding-top:0px;">
    <input checked="" value="on" id="setAlert" name="setAlert" type="checkbox" style="position:relative;top:2px;left:-4px" /><?php echo $checkBoxText;?></div>
    <input type='hidden' name='SHOW_QUESTION_CAPTCHA' value='<?=SHOW_QUESTION_CAPTCHA?>' id='SHOW_QUESTION_CAPTCHA'>
    <?php if(SHOW_QUESTION_CAPTCHA==1){ ?>
    <div class="Fnt11" style="padding-top:8px"><b>Type in the character you see in the picture below:</b></div>
    <input type="hidden" name="secCodeIndex" value="seccodeForAskQuestion" />
    <?php } ?>
    <div style="padding-left:0px">
        <?php if(SHOW_QUESTION_CAPTCHA==1){ ?>
        <div style="padding-top:5px"><img src="/CaptchaControl/showCaptcha?characters=5&secvariable=seccodeForAskQuestion&randomkey=<?php echo rand(0,10000000000); ?>"  onabort="reloadCaptcha(this.id)" id="topicCaptcha" align="absmiddle"/>&nbsp;<input type="text" name="secCode" id="secCode" autocomplete="off" secvariable="seccodeForAskQuestion" size="5" align="absmiddle" validate="validateSecurityCode" maxlength="5" minlength="5" required="true" caption="Security Code"/>
        </div>
        <div class="row errorPlace">
            <div id="secCode_error" class="errorMsg"></div>
        </div>
        <?php } ?>
        <!--////QnA Rehash Phase-2 start code for showing error message text-->
        <div class="row errorPlace">
            <div id="postText_error" class="errorMsg"></div>
        </div>
        <!--////QnA Rehash Phase-2 end code for showing error message text-->
    </div>
    <div style="padding-left:0px; padding-bottom: 10px;">
        <div style="padding-top:12px">
             <input type="button" onclick="<?php if($buttonText == 'Post Question'):?>trackEventByGA('ASK_NOW_BUTTON','ANA_CATEGORY_POST_QUESTION_BUTTON');<?php endif;?>
		    $('mainCreateTopicButton').disabled = true; 
		    if(!isValidatingPost){
		    if(AnA_PQ_validateCreateTopic(document.getElementById('askQuestionFormPost'))){
			return true;
		    }
		    else 
		    {
			$('mainCreateTopicButton').disabled = false; 
			return false;
		    }
		    }
		    " id="mainCreateTopicButton" class="orange-button" value="<?php echo $buttonText;?>"/>&nbsp; &nbsp;
<!--            <input type="submit" id="mainCreateTopicButton" class="fbBtn" value="<?php echo $buttonText;?>"/>&nbsp; &nbsp;-->
	    <a onclick="hideCatCounOverlay();" href="javascript:void(0);" style="font-size:15px;">Cancel</a>&nbsp;&nbsp;<span id="waitingPost" style="display:none;"><img src="/public/images/working.gif" border=0 align="absmiddle"/></span>
        </div>
    </div>
</div>
</form>
<script>
$j(document).ready(function(){
	if(tab_required_course_page) {
		$j('#catselect').val(cat_id_course_page);
		changeSelection('category',cat_id_course_page);
		$j('#subcatselect').val(subcat_id_course_page);
    	AnA_PQ_validateCreateTopic(document.getElementById('askQuestionFormPost'));
    	$j('#genOverlayAnA').hide();                  
        showQnaLoader();                                                     	         
} else {
	changeSelection('category','<?php echo $question_predicted_category;?>');
}
    
});
</script>
