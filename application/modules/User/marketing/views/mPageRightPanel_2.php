<?php
    $url = site_url('user/Userregistration/userfromMarketingPage/'.$prefix.'seccodehome');
    $pageandfields = array(	
    	'management' => array('course','studylocation'),
    	'management_2' => array('course','studylocation'),
	'distancelearningmanagement' => array('course'),
	'animation' => array('course','studylocation'),
	'it' => array('course','studylocation'),
	'bcait' => array('studylocation'),
	'mcait' => array('studylocation'),
	'generic' => array('categoryinterest','course','studylocation')
   );

$selectedarray = $pageandfields[$pagename];

$selectfields = array('course' => '<div class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Desired Course:<b class="redcolor">*</b> </div></div><div style="margin-left:175px"><div id="subCategory" class="float_L"><select style="font-size:11px;width:170px" name = "homesubCategories" validate = "validateSelect" required = "true" caption = "the desired course" id="'.$prefix.'homesubCategories"><option value="">Select</option></select></div><div class="clear_L withClear">&nbsp;</div><div><div class="errorPlace" style="margin-top:2px;display:none;line-height:15px"><div class="errorMsg" id= "'.$prefix.'homesubCategories_error"></div></div></div></div><div class="clear_L withClear">&nbsp;</div><div class="lineSpace_10">&nbsp;</div>','studylocation' => 
'<div id = "studylocation" class="float_L" style="width:175px;line-height:18px"><div class="txt_align_r" style="padding-right:5px">Preferred Study Location(s):<b class="redcolor">*</b> </div></div><div style="margin-left:175px"><div class="float_L" style="width:150px;background:url(/public/images/bgDropDwn.gif) no-repeat left top;height:19px" onclick="abc(this);"><div id="marketingPreferedCity" style="position:relative;top:2px">&nbsp;Select</div><div class="clear_L withClear">&nbsp;</div><script>document.getElementById("marketingPreferedCity").innerHTML= "&nbsp;Select";document.getElementById("mCityList").value = "";</script></div><div class="clear_L withClear">&nbsp;</div><div><div class="errorPlace" style="margin-top:2px;display:none;line-height:15px"><div class="errorMsg" id= "'.$prefix.'preferedLoc_error"></div></div></div><div style="clear:left;font-size:1px">&nbsp;</div></div><div class="clear_L withClear">&nbsp;</div><div class="lineSpace_10">&nbsp;</div>');

?>
                            <form method="post" onsubmit="new Ajax.Request('<?php echo $url?>',{onSuccess:function(request){javascript:newuserresponse<?php echo $prefix;?>(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="<?php echo $url?>" novalidate="novalidate" id = "<?php echo $prefix; ?>marketingUser" name = "marketingUser">
                            <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
                            <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
                            <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
                            <input type = "hidden" name = "mCityListName" id = "mCityListName" value = ""/>
                            <input type = "hidden" name = "mPageName" id = "mPageName" value = "<?php echo $pagename?>"/>
                            <input type = "hidden" name = "mcourse" id = "mcourse" value = "<?php echo $course?>"/>
                            
			<!--Form_Start-->
                            <div style="width:100%">
								<div style="padding-left:20px">
								<div class="OrgangeFont bld" style="font-size:24px;padding-top:2px">Let Us Find a B-School for You</div>
                                <div style="padding-top:2px">We need a few details from you to suggest you relevant institutes &amp; create your  free Shiksha account.</div>
                                <div class="lineSpace_10">&nbsp;</div>
				<div id = "pagefields">
				</div>
				<!-- Dynamic Fields to be populated according to the page -->
                                <div style="display:none" id = "categoryinterest">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Field of Interest:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="margin-left:175px;">
                                    	<select class="normaltxt_11p_blk_arial fontSize_11p" name="board_id" id="<?php echo $prefix; ?>board_id" style="width:150px;" validate = "validateSelect" required = "true" caption = "your education interest" onchange='showSubcategoriesM("subCategory",document.getElementById("<?php echo $prefix; ?>board_id").value)' onfocus="isCityOverlayOpen();">

                                        <option value="">Select Category</option>
                                        <?php
                                        //error_log("FGH".print_r($subCategories,true));
                                        global $categoryMap;
					$tempType = preg_replace("/_\d+/i","",$type);
                                        foreach ($categoryMap as $categoryName=>$categoryData){
                                            if(trim($categoryName) == trim($tempType)) {
                                                $categoryId = $categoryData['id'];
                                            }
                                        }
                                        if(isset($subCategories) && is_array($subCategories)) {
                                            $otherElementId = '';
                                            foreach($subCategories as $subCategory) {
                                                $subCategoryId = $subCategory['boardId'];
                                                $subCategoryName = $subCategory['name'];
                                                if(strpos($subCategoryName,'Others..') !== false){
                                                    $otherElementId = $subCategoryId ;
                                                    continue;
                                                }
                                                if($subCategoryId == $categoryId) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                    <option value="<?php echo $subCategoryId; ?>" <?php echo $selected; ?> title = "<?php echo $subCategoryName ?>" <?php echo $selected ?> ><?php echo $subCategoryName; ?></option>
                                                    <?php
                                            }
                                            if($otherElementId != '') {
                                                if($otherElementId == $categoryId) {
                                                    $selected = 'selected';
                                                } else{
                                                    $selected = '';
                                                }
                                                ?>
                                                    <option value="<?php echo $otherElementId ; ?>" <?php echo $selected ;?> <?php echo $selected ?>>Others..</option>
                                                    <?php
                                            }
                                        }
?>
                                    </select>
                                    
                                    </div>
                                    <div>
                                    <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                                    <div  style="margin-left:175px" class="errorMsg" id= "<?php echo $prefix; ?>board_id_error"></div>
                                    </div>
                                    </div>

                                    <div class="clear_L withClear">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>
                                </div>
<?php
for($i = 0;$i < count($pageandfields[$pagename]);$i++)
{
echo $selectfields[$selectedarray[$i]];
}
?>

				</div>
				<!-- Fields to be populated according to the page -->
				<!-- Fixed Fields -->
				
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Your Name:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width: 155px;">
										<div class="float_L">
											<input type="text" id = "<?php echo $prefix; ?>homename" name = "homename" class = "fontSize_11p" validate = "validateDisplayName" maxlength = "25" minlength = "3" required = "true" caption = "name" style = "width:150px;height:15px;font-size:12px" value="<?php if($logged == "Yes") {echo $userData[0]['firstname']." ".$userData[0]['lastname'];} else { echo $userName; }?>"/>
										</div>
										
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homename_error"></div>
											</div>
										</div>
                                    </div>                                    
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <?php if(($logged == "Yes" && strlen($userData[0]['mobile']) !=10)) { $userData[0]['mobile']  = ''; } ?>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Mobile:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div class="float_L">
											<input type="text" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number" tip="mobile_numM" maxlength="<?php if(($logged == "Yes")&&(strlen($userData[0]['mobile']) > 10)) {echo strlen($userData[0]['mobile']);}else {echo "10";}?>" maxlength1="10" minlength = "10" style="width:150px;height:15px;font-size:12px" value="<?php if($logged == "Yes") {echo $userData[0]['mobile'];}?>"/>
										</div>
										<div class="clear_L withClear">&nbsp;</div>
										<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
											<div class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>
										</div>
                                    </div>                                    									                                   
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>							
								<div class="lineSpace_10">&nbsp;</div>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Residence Location:<b class="redcolor">*</b> </div>
                                    </div>
                                    <?php global $citiesforRegistration; ?>
                                    <script>var citiesarray = eval(<?php echo json_encode($citiesforRegistration);?>);
                                    
                                    </script>
                                    <input type="hidden" value="2" id="country<?php echo $prefix;?>"/>
                                    <div style="margin-left:175px">
										<div class="float_L" id="residenceLoc">
											<select style = "width:150px" class = "normaltxt_11p_blk_arial fontSize_12p" id = "cities<?php echo $prefix; ?>" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence"><option value="">Select City</option></select>
										</div>
										<div class="clear_L withClear">&nbsp;</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "cities<?php echo $prefix; ?>_error"></div>
											</div>
										</div>
                                    </div>                                    
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>

                                <div style="padding-left:20px">
									<div class="float_L" style="width:175px; line-height:18px">
										<div class="txt_align_r" style="padding-right:5px">Your Highest Qualification:<b class="redcolor">*</b> </div>
									</div>
									<div style="margin-left:175px">                                     
										<div class="float_L">
											<select class = "normaltxt_11p_blk_arial fontSize_11p" style = "width:150px;" id = "<?php echo $prefix; ?>homehighesteducationlevel" name = "homehighesteducationlevel" validate = "validateSelect" required = "true" caption = "your highest education"></select>
										</div>
										<div class="clear_L withClear">&nbsp;</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homehighesteducationlevel_error"></div>
											</div>
										</div>
									</div>
                                </div>
                                
                                <div class="lineSpace_10">&nbsp;</div>
                                <!-- Highest Education Level Ends -->
                                <script>getEducationLevel('<?php echo $prefix; ?>homehighesteducationlevel','',1,'reqInfo');
                                var logged = "";
                                <?php if($logged == "Yes") {echo "logged='Yes';"; }
                                
                                ?>
                                if(logged == "Yes") {
                                    var highestQualificationId = document.getElementById("<?php if($logged == "Yes") echo $prefix; ?>homehighesteducationlevel"); 
                                    for(var kl=0;kl<highestQualificationId.options.length;kl++) {
                                           if(highestQualificationId.options[kl].title=="<?php  if($logged == "Yes") echo $userData[0]['degree']?>") {
                                            highestQualificationId.options[kl].selected=true;
                                            break;

                                        }
                                    }
                                }
                                </script>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Age:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input id = "<?php echo $prefix?>homeYOB" name = "homeYOB" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age field" style="width:20px;font-size:12px;"  value="<?php if($logged == "Yes") {echo $userData[0]['age'];}?>"/>
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homeYOB_error"></div>
											</div>
										</div>
                                    </div>                                    
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
								
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Gender: &nbsp;</div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input type="radio" name = "homegender" id = "<?php echo $prefix?>Male" value = "Male" />Male 
											<input type="radio" name = "homegender" id = "<?php echo $prefix?>Female" value = "Female" />Female                      
										</div>
										<script>
										<?php if($logged=="Yes") {if($userData[0]['gender']=="Male") {
											echo "document.getElementById('".$prefix."Male').checked='true';";
										}
										}?>
										<?php if($logged=="Yes") {if($userData[0]['gender']=="Female") {
											echo "document.getElementById('".$prefix."Female').checked='true';";
										}
										}?>
										</script>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "homegender_error"></div>
											</div>
										</div>
                                    </div>
                                    

                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php if($logged=="No") {?>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Your Email ID:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input type="text" id = "<?php echo $prefix; ?>homeemail" name = "homeemail" value="<?php echo $userEmail; ?>" validate = "validateEmail" required = "true" caption = "email id" maxlength = "125" style = "width:150px;height:15px;font-size:12px" tip="email_idM" />
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>
											</div>
										</div>
                                    </div>                                    
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style="padding-left:20px">
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Create Password:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input type="password" id = "<?php echo $prefix; ?>homepassword" name = "homepassword" value = "<?php echo $userPassword; ?>" validate = "validateStr" required = "true" caption = "password" minlength = "5"  maxlength = "20" style = "width:150px;height:15px;font-size:11px;"/>
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homepassword_error">cv</div>
											</div>
										</div>
                                    </div>
                                    
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style="padding-left:20px">
									<div class="float_L" style="width:175px;line-height:18px">
										<div class="txt_align_r" style="padding-right:5px">Confirm Password:<b class="redcolor">*</b> </div>
									</div>
									<div style="float:left;width:155px">
										<div>
											<input type="password" caption="Password again" required="1" blurmethod="validateConfirmPassword('<?php echo $prefix; ?>homepassword','<?php echo $prefix; ?>confirmpassword')" validate="validateStr" id="<?php echo $prefix; ?>confirmpassword" name="<?php echo $prefix; ?>confirmpassword" style = "width:150px;height:15px;font-size:11px;" minlength = "5"  maxlength = "20" />
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>confirmpassword_error">cv</div>
											</div>
										</div>
									</div>                                
									<div class="clear_L withClear">&nbsp;</div>
                                </div>

                                <div class="lineSpace_10">&nbsp;</div>
                                <?php }else {?>
                                    <input type="hidden" id = "<?php echo $prefix; ?>homeemail" value="<?php 
                                    $cookiesArr = array();
                                    $cookieData = $userData[0]['cookiestr'];
                                    $cookieArr = split('\|',$cookieData);
                                    echo $cookieArr[0];
                                    ?>"/>
                                        <input type="hidden" id = "<?php echo $prefix; ?>userId" value="<?php 
                                    echo $userData[0]['userid'];
                                    ?>"/>

                                    
                                <?php }?>
                                <div style="padding:0 10px 0 65px">
                                	<div>Type in the characters you see in the picture below</div>
                                    <div class="lineSpace_5">&nbsp;</div>

                                    <div>
                                    <img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=<?php echo $prefix; ?>seccodehome" width="100" height="34"  id = "<?php echo $prefix; ?>secureCode"/>
                                    <input type="text" style="margin-left:20px;width:135px;height:15px;font-size:12px" name = "homesecurityCode" id = "<?php echo $prefix; ?>homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                                    </div>
                                    <div>
                                    <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                                    <div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homesecurityCode_error"></div>
                                    </div>
                                    </div>

                                    <div class="lineSpace_5">&nbsp;</div>
                                    <?php if($logged=="No") { ?>
                                    <div><input type="checkbox" name="cAgree" id="<?php echo $prefix; ?>cAgree" />
                                    I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
                                    </div>
                                    <?php }?>
                                </div>
                                <div>
                                <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                                <div class="errorMsg" id= "<?php echo $prefix; ?>cAgree_error" style="padding-left:24px"></div>                    
                                </div>
                                </div>

                                <div class="lineSpace_10">&nbsp;</div>
                                <div>
                                    <div class="float_L" style="width:1px;line-height:31px">
                                    	<div class="txt_align_r" style="padding-right:5px">&nbsp;</div>
                                    </div>
                                    <div>
                                    	<div align="center">
                                        <input uniqueattr="MarkeingPageLayer1Submit" type="submit" id = 'subm' disabled = "true" onclick="return sendReqInfo<?php echo $prefix;?>(this.form);" value="Submit" class="continueBtn" style="border:0  none" />
                                        <script>
                                        document.getElementById('subm').disabled = '';
                                        </script>
                                        </div>                                      
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_26">&nbsp;</div>
							</div>
							<div style="clear:left;font-size:1px;background:#F4F4F4">&nbsp;</div>
                            <!--End_Form_Start-->
                            </form>
    <!--Start_OuterBorder-->
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>
<script>
var TRACKING_CUSTOM_VAR_MARKETING_FORM = "marketingpage";
if(typeof(setCustomizedVariableForTheWidget) == "function") {
	if (window.addEventListener){
		window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', setCustomizedVariableForTheWidget);
	}
}
</script>
