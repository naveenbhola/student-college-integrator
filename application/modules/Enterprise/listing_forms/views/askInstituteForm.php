<?php
        $NameOfUser = is_array($validateuser[0])?$validateuser[0]['firstname']:"";
        $cookiStr =  is_array($validateuser[0])?$validateuser[0]['cookiestr']:"";
        $cookiStrArr = explode("|",$cookiStr);
        $emailId =      isset($cookiStrArr[0])?$cookiStrArr[0]:"";
        $contactNumber = is_array($validateuser[0])?$validateuser[0]['mobile']:"";
        $callBackFunction = 'try{ askInstitute.askInstituteSuccess(request.responseText); } catch (e) {}';
        $askInstituteHelpTip = "Type your question about $titleOfInstitute here. Your question will be answered by the Institute, Shiksha counselors, Experts, College Alumni and other Students.";
        $questionText = '';
        if(isset($_COOKIE['commentContent']) && ($questionText == '')){
                $commentContentData = $_COOKIE['commentContent'];
                if((stripos($commentContentData,'@$#@#$$') !== false) && (stripos($commentContentData,'@#@!@%@') === false)){
                        $questionText = str_replace("@$#@#$$","",$commentContentData);
                }
        }
?>
<div>
        <div class="float_L wdh100">
                        <div class="raised_pinkWhite">
                                <b class="b1"></b><b class="b2"></b><b class="b3" style="background:#FEF9DB"></b><b class="b4" style="background:#FEF9DB"></b>
                                <div class="boxcontent_pinkWhite" style="background:#FEF9DB">
                                        <div class="mlr28-10">
                                                <?php
                                                        $url = site_url("messageBoard/MsgBoard/askQuestionFromListing");
                                                        echo $this->ajax->form_remote_tag( array('url'=>$url,'before' => 'try{ if(askInstitute.validateAskInstitute(this) != true){return false;} else { disableElement(\'askInstituteButton\'); } }catch (e) { return false; }','success' => $callBackFunction));
                                                ?>
                                                <div class="wdh100">
                                                        <div>
                                                                <div class="iconAna"><b>Ask about <?php echo $titleOfInstitute; ?></b></div>
                                                                <div><textarea name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" type="text" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" style="width:98%;height:46px;<?php if ($questionText == '') { echo 'color:#565656;'; } ?>" default="<?php echo $askInstituteHelpTip; ?>" onfocus="try{ askInstitute.callOnFocusOnBlurFunctions('focus');trackEventByGA('askInstituteWidget_questionTextArea_click','askInstitute'); }catch (e){}"><?php if ($questionText != '') { echo $questionText;} else { echo $askInstituteHelpTip; } ?></textarea></div>
                                                        </div>
                                                        <div class="clear_B lineSpace_5">&nbsp;</div>
                                                        <!--Start_Hide/Show_Form--->
                                                        <div style="display:none;overflow:hidden;" id="hiddenFormPartAskInstitute">
                                                                <div class="font_size_11 graycolor"><span id="questionText_counter">0</span> out of 300 character</div>
                                                                <div class="errorPlace" style="display:none"><div class="errorMsg" id="questionText_error"></div></div>
                                                                <div>
                                                                <div>
                                                                        <div class="float_L wdh33">
                                                                                <div class="wdh100">
                                                                                        Name:<span class="redcolor">*</span><br />
                                                                                        <input type="text" name="nameOfUserForAskInstitute" id="nameOfUserForAskInstitute" validate = "validateDisplayName" required = "true" maxlength = "25" minlength = "3" caption = "name" class="wdh90" value="<?php echo $NameOfUser; ?>" />
                                                                                </div>
                                                                                <div class="errorPlace" style="display:none"><div class="errorMsg" id="nameOfUserForAskInstitute_error"></div></div>
                                                                        </div>
                                                                        <div class="float_L wdh33">
                                                                                <div class="wdh100">
                                                                                        Email Id:<span class="redcolor">*</span><br />
                                                                                        <input type="text" name="emailOfUserForAskInstitute" id="emailOfUserForAskInstitute" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" class="wdh90" value="<?php echo $emailId; ?>" <?php if($emailId != ""){ echo 'disabled="true"';} ?> />
                                                                                </div>
                                                                                <div class="errorPlace" style="display:none" ><div class="errorMsg" id="emailOfUserForAskInstitute_error"></div></div>
                                                                        </div>
                                                                        <div class="float_L wdh33">
                                                                                <div class="wdh100">
                                                                                        Contact Number:<span class="redcolor">*</span><br />
                                                                                        <input type="text" id = "mobileOfUserForAskInstitute" name = "mobileOfUserForAskInstitute" type="text" minlength = "10" maxlength = "10" validate = "validateMobileInteger" required = "true" caption = "mobile number" class="wdh90" value="<?php echo $contactNumber; ?>" />
                                                                                </div>
                                                                                <div class="errorPlace" style="display:none"><div class="errorMsg" id="mobileOfUserForAskInstitute_error"></div></div>
                                                                        </div>
                                                                </div>
                                                                </div>
                                                                <div class="clear_B lineSpace_10">&nbsp;</div>
<!--                                                            <div>Type in the character you see in picture below</div>
                                                                <div class="lineSpace_5">&nbsp;</div>
                                                                <div><img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&secvariable=seccodeForAskInstitute&randomkey=<?php echo rand(0,10000000000); ?>"  onabort="reloadCaptcha(this.id)" id="askInstituteCaptcha" align="absbottom"> <input type="text" style="width:100px;" name="secCodeForAskInstitute" id="secCodeForAskInstitute" autocomplete="off" caption="Security Code" required="true" minlength="5" maxlength="5" validate="validateSecurityCode" /></div>
                                                                <div class="errorPlace" style="display:none"><div class="errorMsg" id="secCodeForAskInstitute_error"></div></div>
                                                                <div class="lineSpace_10">&nbsp;</div>-->
                                                                <input type="hidden" name ="instituteId" id="instituteIdForAskInstitute" value="<?php echo $instituteId; ?>" />
                                                                <input type="hidden" name ="categoryId" id="categoryIdForAskInstitute" value="<?php echo $categoryId; ?>" />
                                                                <input type="hidden" name ="locationId" id="locationIdForAskInstitute" value="<?php echo $locationId; ?>" />
                                                                <input type="hidden" name="secCodeIndex" value="seccodeForAskInstitute" />
                                                                <input type="hidden" name="loginproductname_ForAskInstitute" value="<?php echo $pageKeyForAskQuestion; ?>" />
                                                                <input type="hidden" name="referer_ForAskInstitute" id="referer_ForAskInstitute" value="" />
                                                                <input type="hidden" name="resolution_ForAskInstitute" id="resolution_ForAskInstitute" value="" />
                                                                <input type="hidden" name="coordinates_ForAskInstitute" id="coordinates_ForAskInstitute" value="" />
                                                        </div>
                                                        <div class="clear_B">&nbsp;</div>
                                                        <!--Start_Hide/Show_Form--->
                                                        <div>
                                                                <div><button type="Submit" name="askInstituteButton" id="askInstituteButton" class="btn-ANow">Ask Now</button>&nbsp;&nbsp;&nbsp;<span id="cacelLinkContainer_ForAskInstitute" style="display:none;"><a href="javascript:void(0);" onClick="javascript:askInstitute.callOnFocusOnBlurFunctions('blur');" class="fontSize_12p">Cancel</a></span></div>
                                                                <div class="lineSpace_5">&nbsp;</div>
                                                                <div class="font_size_11" id="termsAndConditionDivForAskInstitute" style="display:none;">By successfully posting the question you agree to shiksha <a href="javascript:void(0);" onClick="return popitup('/shikshaHelp/ShikshaHelp/termCondition');">terms &amp; conditions</a></div>
                                                        </div>
                                                        <div class="lineSpace_10">&nbsp;</div>
                                                </div>
                                                </form>
                                        </div>
                                </div>
                                <b class="b4b" style="background:#FEF9DB"></b><b class="b3b" style="background:#FEF9DB"></b><b class="b2b" style="background:#FEF9DB"></b><b class="b1b"></b>
                        </div>
                </div>
        <div class="clear_B">&nbsp;</div>
</div>
<script>
function showMultipleApplyOverlay(overlayFlag){
        if(overlayFlag == 1){
                askInstitute.successMessage='showLogin';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/1',500,260);
        }else if(overlayFlag == 4){
                askInstitute.successMessage='showRegister';
                displayMessage('/MultipleApply/MultipleApply/showoverlay/4',665,380);
        }
        return false;
}
function populatUserData(){
        email_id = $('emailOfUserForAskInstitute').value;
        phone_no = $('mobileOfUserForAskInstitute').value;
        display_name = $('nameOfUserForAskInstitute').value;
        return false;
}
</script>