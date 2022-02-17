<?php
$widget = "ebrocher";
?>
<div style="width:500px;margin: 0pt auto;display:none;" id="mainApplyDiv">
    <?php
    $data = array();
    $data['widget'] = $widget;
    $this->load->view('common/userAuthentication', $data);
    ?>
    <form id="form_<?=$widget?>" name="myForm1" onSubmit="authenticateUser(); return false;" novalidate>
    <input type = "hidden" name = "resolution" id = "resolution" value = ""/>
    <input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
    <input type = "hidden" name = "referer" id = "referer" value = ""/>
    <input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
	<div class="blkRound">
		<div class="layer-title">
		<a title="Close" href="javascript:void(0);" class="close" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();"></a>
                <?php if($validateuser == "false"){ ?>
		<div style="float:right; margin:5px 10px;"><a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(); return false;" href="javascript:void(0);">Existing Users, Sign In</a></div>
		<?php } ?>
		<div class="title" style="width: 60%"><?=($multkeyname=="RANKING_PAGE"?"Request":"Download")?> E-Brochure</div>
        </div>
		<div class="whtRound">
			<div style="display:none;color:#FF0000;font-weight:bold; margin-bottom:5px" id="flag_totalChecked_error">ERROR! Please select atleast one Institute to proceed</div>
            
		<div class="fontSize_14p">You have chosen to <?=($multkeyname=="RANKING_PAGE"?"request":"download")?> free brochure from:</div>
            <div class="spacer10 clearFix"></div>
            <div id="c_value_html_innerhtml">
				<script>
					$j("#c_value_html_innerhtml").html(c_value_html);
				</script>
			</div>
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px; line-height:20px"><div class="txt_align_r">Name: &nbsp;</div></div>
                <div style="margin-left:105px;" class="OrgangeFont">
                    <span><input value="<?php echo $firstname? htmlentities($firstname):"Your First Name";?>" id="usr_first_name_<?=$widget?>"  tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_<?=$widget?>" style="width:150px;color:#999999;" default="Your First Name" onfocus="removeHelpText(this);" /></span>
                    <span><input value="<?php echo $lastname? htmlentities($lastname):"Your Last Name";?>" id="usr_last_name_<?=$widget?>"  tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_<?=$widget?>" style="width:150px;color:#999999;" default="Your Last Name" onfocus="removeHelpText(this);" /><br></span>
                    <span style="display:none"><span class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px;"></span></span>
			<div class="clearFix"></div>
                    <span style="display:none"><span class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px;"></span></span>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px; line-height:20px"><div class="txt_align_r">Email:&nbsp;</div></div>
                <div style="margin-left:105px;" class="OrgangeFont">
                    <div><input profanity="true" required="true" tip="multipleapply_email" name="contact_email_<?=$widget?>" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; } ?>" id="contact_email_<?=$widget?>" type="text" validate="validateEmail"  maxlength="100" minlength="10" style="width:185px" caption="email" /></div>
                    <div style="display:none"><div class="errorMsg" style="padding-left:3px;" id="contact_email_<?=$widget?>_error"></div></div>
                    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
                </div>
            </div>    
            <div style="padding-bottom:10px">
                <div class="float_L" style="width:100px; line-height:20px"><div class="txt_align_r">Mobile Phone: &nbsp;</div></div>
                <div style="margin-left:105px;" class="OrgangeFont">
                    <div><input value="<?php echo $mobile;?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" style="width:185px" /></div>
                    <div class="graycolor" style="font-size:10px">eg: 9871787683</div>
                    <div style="display:none"><div class="errorMsg" id="mobile_phone_<?=$widget?>_error" style="padding-left:3px;" ></div></div>
                </div>
                <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
            </div>
			
			<?php
			if($studyAbroadResponse) {
				echo Modules::run('MultipleApply/MultipleApply/getExtraFieldsForStudyAbroadResponseForm','CategoryPageOverlay',$widget);	
			}
			?>
			
			<div style="margin-left:105px">
			<?php if (empty($displayname)) { ?>
				<script type="text/javascript">
					flagSignedUser = true; 
				</script>
				
				<?php
				if(!($OTPVerification || $ODBVerification)) {
				?>
				<div>
					<div class="fontSize_11p" style="line-height:15px">Type in the characters you see in the picture below</div>
				</div>
				<div class="lineSpace_4">&nbsp;</div>
				<div>
					<img align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
					<input type="text" style="margin-left:3px;width:135px;font-size:12px;margin-top:12px;" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
				</div>
				<div>
					<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
						<div style="margin-left:3px;" class="errorMsg" id= "homesecurityCode_<?=$widget?>_error"></div>
					</div>
				</div>
				<div class="lineSpace_4">&nbsp;</div>
				<?php
				}
				?>
				
		<?php } else {
			if ((!empty($displayname)) && (!empty($b[0]))) {
		?>
                <script>
                        document.getElementById('contact_email_<?=$widget?>').disabled=true;
                        document.getElementById('contact_email_<?=$widget?>').setAttribute('readonly','readonly');
                </script>
                <?php
                    }
                } ?>
            <div style="margin-top:4px;"><input type="submit" class="fbBtn orange-button"  value="<?=($multkeyname=="RANKING_PAGE"?"Request":"Download")?> E-Brochure" id="submitRegisterFormButton" uniqueattr="CategoryPageApplyRegisterButton"/></div>
			</div>
		</div>
	</div>
        <!--End Request E-Brochure-->
    <script type="text/javascript">
        /*  :(    */
		addOnBlurValidate($('form_<?=$widget?>'));
		try{
		<?php if($multkeyname=="RANKING_PAGE"){ echo "var isRankingPage=1;";} else {echo "var isRankingPage=0;";} ?>;
		}
		catch(e){
		    logJSErrors(e);
		}
		//alert(isRankingPage );
		recommendation_json = {};
		var brochureAvailable = "YES";
		if(keyname != ""){
			$j('#submitRegisterFormButton').attr('uniqueattr',keyname);
		}
        shortRegistrationFormResponse = 1;
		shortRegistrationFormUnified = true;
		shortRegistrationFormCallBack = function(){
		    closeMessage();
		    if(typeof(category_course_base_url) !='undefined' && category_course_base_url == 'COMPARE_1') {
		        recommendation_json_i = compare_institute_id;
		    	recommendation_json_c = compare_course_id;
		    	//checkRecommendations($categorypage.categoryId);
                	displayMessage('/MultipleApply/MultipleApply/showoverlay/19',500,50);
		    } else {
			var showDivLayer = <?php if($multkeyname == 'CategoryPageApplyRegisterButton' || $multkeyname == 'StudyAbroadApplyRegisterButton') { echo 1; } else { echo 0; } ?>;
			for(key in recommendation_json) {
			    var instituteId = key;
			    var courseId = recommendation_json[key];
			}
			 
			    if(isRankingPage == 0){
				var el_id = "course"+courseId+"BrochureURL";
			    var brochureURL = $j("#"+el_id).val();
			    if(typeof(brochureURL)!='undefined' && brochureURL !="")
			     {brochureAvailable = "YES";}
			     else
			     {brochureAvailable = "NO";}
			   }
			    
			if (userlogin == false && typeof(new_rnr_cat_page)=='undefined') {
			    displayMessage("/categoryList/CategoryList/showRecommendation/"+courseId+"/CP_Reco_popupLayer"+"/0/0/0/"+brochureAvailable+"/"+isRankingPage, 745, 390);
			}
			else if (userlogin == true || (typeof(new_rnr_cat_page)!='undefined')) {
			    if (showDivLayer) {
					
					var recommendationDiv = document.getElementById('recommendation_inline'+instituteId);				
					var recommend_url = "/categoryList/CategoryList/showRecommendation/"+courseId+"/CP_Reco_divLayer"+"/0/0/0/"+brochureAvailable+"/"+isRankingPage;
					if((typeof(new_rnr_cat_page)!='undefined')) {
						recommend_url = recommend_url + '/' + 'new_layer';	
					}
					if(userlogin == false) {
						 window.location.reload();
						 setCookie('show_inline_reco','recommendation_inline'+instituteId+"*****"+recommend_url+"*****"+instituteId);	
					} else {
						$j.post(recommend_url, function(recoHTML) {				
							if (recoHTML.search('div') >= 0) {
								if(typeof hideSimilarCoursesTupleIfInlineRecoComes == 'function') {
									hideSimilarCoursesTupleIfInlineRecoComes(instituteId);
									}
							recommendationDiv.innerHTML = recoHTML;
							ajax_parseJs(recommendationDiv);
							recommendationDiv.style.display = 'block';
							}
						});
				  }	
					
			    }
			    else {
					var recommend_pop_url = "/categoryList/CategoryList/showRecommendation/"+courseId+"/CP_Reco_popupLayer"+"/0/0/0/"+brochureAvailable+"/"+isRankingPage;
					if((typeof(new_rnr_cat_page)!='undefined')) {
						recommend_pop_url = recommend_pop_url + '/' + 'new_layer';	
					} else {
						if( window.L_tracking_keyid ){
							tracking_keyid = window.L_tracking_keyid;
							recommend_pop_url += "/"+ tracking_keyid;
							delete window.L_tracking_keyid;
						}
					}
					displayMessage(recommend_pop_url, 745, 390);
			    }
			}
		    }
		}
		category_course_base_url = category_course_base_url?category_course_base_url:"";
	
	function authenticateUser() {
	    var OTPVerification = <?=$OTPVerification?>;
	    var ODBVerification = <?=$ODBVerification?>;
	    
	    if (OTPVerification || ODBVerification) {
		if(validateShortForm('<?=$widget?>', OTPVerification, ODBVerification)) {
		    showVerificationLayer('<?=$widget?>', OTPVerification, ODBVerification);
		}
	    }
	    else {
		processData();
	    }
	}
	
        function processData()
        {
			recommendation_json[$j('#institute_id_<?=$widget?>').val()] = $j('#selected_course_<?=$widget?>').val();
			var isCategoryPage = <?php if($multkeyname == 'CategoryPageApplyRegisterButton') { echo 1; } else { echo 0; } ?>;
			processOverlayForm('<?=$widget?>', isCategoryPage, <?=$OTPVerification?>, <?=$ODBVerification?>);
			if(typeof $searchPage != 'undefined'){
				trackEventByGA('LinkClick','SEARCH_PAGE_APPLY_FINAL/'+ keyname);
			} else if(typeof $rankingPage != 'undefined'){
				trackEventByGA('LinkClick','RANKING_PAGE_APPLY_FINAL/'+ keyname);
			} else {
				trackEventByGA('LinkClick','CATEGORY_APPLY_FINAL/'+$categorypage.NaukrilearningTrack+keyname);	
			}
		}
		
		function selectCourse(){
			
		}
		function removeHelpText(obj) {
			if(obj.value == obj.getAttribute('default')) {
				obj.value = "";
			}
			obj.style.color = '#111111';
		}
    </script>
    </form>
</div>
<script>
	try {

		populateLocations("selected_course_<?=$widget?>", "locality_<?=$widget?>", $j('#institute_id_<?=$widget?>').val(), "ebrocher");
		$j('#selected_course_<?=$widget?>').change(function(){
			populateLocations("selected_course_<?=$widget?>", "locality_<?=$widget?>", $j('#institute_id_<?=$widget?>').val(), "ebrocher");
		});
		
		if($('contact_email_<?=$widget?>').value != ""){
			userlogin = true;
		}else{
			userlogin = false;
		}
		if(typeof(subcatSameAsldbCourseCategoryPage) == 'undefined'){
			subcatSameAsldbCourseCategoryPage = 0;
		}
		
		if(typeof $searchPage != "undefined"){
			var tempInstituteId = $j('#institute_id_<?=$widget?>').val();
			if(ShikshaHelper.in_array(tempInstituteId, $searchPage.instituteWithMultipleCourseLocations)){
				$('mainApplyDiv').style.display = 'block';
			} else {
				if($('contact_email_<?=$widget?>').value != ""  && $('mobile_phone_<?=$widget?>').value != ''){
					$('mainApplyDiv').style.display = 'none';
					processData();
				} else {
					$('mainApplyDiv').style.display = 'block';
				}
			}
		} else if(typeof $rankingPage != "undefined"){
			var tempInstituteId = $j('#institute_id_<?=$widget?>').val();
			if(ShikshaHelper.in_array(tempInstituteId, $rankingPage.instituteWithMultipleCourseLocations)){
				$('mainApplyDiv').style.display = 'block';
			} else {
				if($('contact_email_<?=$widget?>').value != ""  && $('mobile_phone_<?=$widget?>').value != ''){
					$('mainApplyDiv').style.display = 'none';
					processData();
				} else {
					$('mainApplyDiv').style.display = 'block';
				}
			}
		} else {
			if ( (category_course_base_url != '' && $('contact_email_<?=$widget?>').value != ""  && $('mobile_phone_<?=$widget?>').value != '') || ( ($categorypage.LDBCourseId != 1 || subcatSameAsldbCourseCategoryPage == 1) && $('contact_email_<?=$widget?>').value != "" && $('mobile_phone_<?=$widget?>').value != '')) {
				$('mainApplyDiv').style.display = 'none';
				processData();
			}else{
				$('mainApplyDiv').style.display = 'block';
			}
		}
	}catch (ex){
		if (debugMode){
			throw ex;
		} else {
			logJSErrors(ex);
		}
	}   
</script>
