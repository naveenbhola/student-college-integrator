<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		$tempJsArray = array('alerts','ana_common','myShiksha','user','ana_expert');
		if($userId != 0){
			$loggedIn = 1;
		}else{
			$loggedIn = 0;
		}
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header','common_new','expert'),
                        'js' => array('common','discussion','facebook','imageUpload', 'ajax-api'),
						'jsFooter'=>    $tempJsArray,
						'title'	=>	'Be an Education Expert on shiksha.com - Clear doubts on study programs, Career, College, Courses . Shiksha.com',
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>'Be an Expert Advisor for Shiksha Cafe, just fill Registration Form and join the Ask and Answer Education Forum. Reply queries/doubts on colleges, courses and careers and enjoy higher reputation and prominence',
						'metaKeywords'	=>'question and answer, Career expert , answer my question, expert advisor, expert advice, ask answer, ask questions get answers, ask expert, online expert, just answer, Career, Colleges, Courses',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'callShiksha'=>1,
						'notShowSearch' => true,
						'postQuestionKey' => 'ASK_ASKHOME_HEADER_POSTQUESTION',
						'showBottomMargin' => false,
					);
		$this->load->view('common/header', $headerComponents);

?>

<?php 
//Create the Category layer
$catHTML = '';
global $categoryParentMap;
foreach($categoryParentMap as $categoryName => $category) 
{
	if(isset($catCountURL))
		$link = str_replace('@cat@',$categoryParentMap[$categoryName]['id'],$catCountURL);
	else 
		$link = "#";
	$catHTML .= '<div class="anchorClass" style="padding:0 5px 5px 10px;cursor:default"><div><a href="'.$link.'" title="'.$categoryName.'" class="quesAnsBullets">'.$categoryName.'</a></div></div>';
} 
//Check for the values in case of Edit and fill them accordingly. Else, fill the default string inside the Text boxes.
if(isset($vcardDetails[0]["VCardDetails"][0]["facebookURL"]) && $vcardDetails[0]["VCardDetails"][0]["facebookURL"]!=''){ $facebookText = $vcardDetails[0]["VCardDetails"][0]["facebookURL"]; $colorF = '#000000';}else{ $facebookText = ''; $colorF = '';}
if(isset($vcardDetails[0]["VCardDetails"][0]["blogURL"]) && $vcardDetails[0]["VCardDetails"][0]["blogURL"]!=''){ $blogText = $vcardDetails[0]["VCardDetails"][0]["blogURL"]; $colorB='#000000';}else{$blogText = ''; $colorB='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["youtubeURL"]) && $vcardDetails[0]["VCardDetails"][0]["youtubeURL"]!=''){$youtubeText =    $vcardDetails[0]["VCardDetails"][0]["youtubeURL"];$colorY = '#000000';}else{$youtubeText = '';$colorY='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["twitterURL"]) && $vcardDetails[0]["VCardDetails"][0]["twitterURL"]!=''){$twitterText = $vcardDetails[0]["VCardDetails"][0]["twitterURL"];$colorT = '#000000';}else{ $twitterText = '';$colorT='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["linkedInURL"]) && $vcardDetails[0]["VCardDetails"][0]["linkedInURL"]!=''){ $linkedInText = $vcardDetails[0]["VCardDetails"][0]["linkedInURL"];$colorL = '#000000';}else{ $linkedInText = '';$colorL='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["userName"]) && $vcardDetails[0]["VCardDetails"][0]["userName"]!=''){$nameText = $vcardDetails[0]["VCardDetails"][0]["userName"];$colorU = '#000000';}else{$nameText = '';$colorU='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["highestQualification"]) && $vcardDetails[0]["VCardDetails"][0]["highestQualification"]!=''){$qualificationText = $vcardDetails[0]["VCardDetails"][0]["highestQualification"];$colorQ = '#000000';}else{$qualificationText = '';$colorQ='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["designation"]) && $vcardDetails[0]["VCardDetails"][0]["designation"]!=''){$designationText = $vcardDetails[0]["VCardDetails"][0]["designation"];$colorD = '#000000';}else{$designationText = '';$colorD='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["aboutCompany"]) && $vcardDetails[0]["VCardDetails"][0]["aboutCompany"]!=''){$aboutCompanyText = $vcardDetails[0]["VCardDetails"][0]["aboutCompany"];$colorAC = '#000000';}else{$aboutCompanyText = '';$colorAC='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["aboutMe"]) && $vcardDetails[0]["VCardDetails"][0]["aboutMe"]!=''){$aboutMeText = $vcardDetails[0]["VCardDetails"][0]["aboutMe"];$colorAM = '#000000';}else{$aboutMeText = '';$colorAM='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["instituteName"]) && $vcardDetails[0]["VCardDetails"][0]["instituteName"]!=''){$instituteText = $vcardDetails[0]["VCardDetails"][0]["instituteName"];$colorI = '#000000';}else{$instituteText = '';$colorI='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["signature"]) && $vcardDetails[0]["VCardDetails"][0]["signature"]!=''){$signatureText = $vcardDetails[0]["VCardDetails"][0]["signature"];$colorSi = '#000000';}else{$signatureText = '';$colorSi='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["firstname"]) && $vcardDetails[0]["VCardDetails"][0]["firstname"]!=''){$firstname = $vcardDetails[0]["VCardDetails"][0]["firstname"];$colorU = '#000000';}else{$firstname = '';$colorU='';}
if(isset($vcardDetails[0]["VCardDetails"][0]["lastname"]) && $vcardDetails[0]["VCardDetails"][0]["lastname"]!=''){$lastname = $vcardDetails[0]["VCardDetails"][0]["lastname"];$colorU = '#000000';}else{$lastname = '';$colorU='';}
?>

<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("expert"); ?>" type="text/css" rel="stylesheet" />
<script>
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
</script>

<?php
	$isCmsUser = 0;
	if($userGroup === 'cms')$isCmsUser = 1;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".$pageUrl."';";	
	echo "var loginRedirectUrl = '/messageBoard/MsgBoard/discussionHome';";
	echo "var loggedIn = '".$loggedIn."';";		
	echo "var loggedInUserId = '".$userId."';";
	echo "</script> ";
	
?>

<script>
var userVCardObject = new Array();
</script>


<div id="content-child-wrap">
<div id="expert-cont" tabindex="-1">
        <?php if( (isset($vcardDetails[0]["VCardDetails"][0]["instituteName"]) && $vcardDetails[0]["VCardDetails"][0]["instituteName"]!='') || (isset($vcardDetails[0]["VCardDetails"][0]["ownerLevel"]) && $vcardDetails[0]["VCardDetails"][0]["ownerLevel"]!='Beginner' && $vcardDetails[0]["VCardDetails"][0]["ownerLevel"]!='Trainee') ){ ?>
        <h4>Edit your Shiksha Expert profile and Showcase yourself.</h4>
        <?php }else{ ?>
        <h4>Become a Shiksha expert.Tell us about yourself.</h4>
        <?php } ?>
    <div class="shade"></div>
    
    <div id="left-col">
		<form action="/messageBoard/MsgBoard/applyExpertProfile" id="expertForm" accept-charset="utf-8" method="post" enctype="multipart/form-data" onsubmit="startValidating();removeHelpText(this); if(validateFields(this) != true){ validateExpertForm(this); validationFail(); return false;} if( validateExpertForm(this) != true){validationFail(); return false;}  storeExpertData(this); return false;" novalidate="novalidate" tabindex="-1">
			<div id="form-section">
        	<h5><b>Personal Details</b></h5>

			<input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
			<input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
			<input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
			<input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
			<input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>
			<input type = "hidden" name = "loginproductname_ForAnA" id = "loginproductname_ForAnA" value = "CAFE_EXPERT_ONBOARD_PAGE"/>
			<input type = "hidden" name = "resolution_ForAnA" id = "resolution_ForAnA" value = ""/>
			<input type = "hidden" name = "coordinates_ForAnA" id = "coordinates_ForAnA" value = ""/>
			<input type = "hidden" name = "referer_ForAnA" id = "referer_ForAnA" value = ""/>
			
			<input type = "hidden" name = "edit" id = "edit" value = "<?php echo $edit;?>"/>
			<input type = "hidden" name = "expertId" id = "expertId" value = "<?php echo $expertId;?>"/>

			<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
			<ul>
                    <li>
                        <div class="field-col">
                        	<label>Your First Name</label>
                        	<input type="text" class="universal-txt-field" value="<?php echo htmlspecialchars($firstname);?>" id='quickfirstname_ForAnA' name='quickfirstname_ForAnA'  maxlength="50" minlength="1" validate="validateDisplayName" caption="First Name" required = "true" onKeyUp="updateVCardForm();"/>
						    <div style="display:none;"><div class="errorMsg" id="quickfirstname_ForAnA_error" style="*float:left"></div></div>
                        </div>
                        <div class="field-col">
                        	<label>Your Last Name</label>
                        	<input type="text" class="universal-txt-field" value="<?php echo htmlspecialchars($lastname);?>" id='quicklastname_ForAnA' name='quicklastname_ForAnA'  maxlength="50" minlength="1" validate="validateDisplayName" caption="Last Name" required = "true" onKeyUp="updateVCardForm();"/>
						    <div style="display:none;"><div class="errorMsg" id="quicklastname_ForAnA_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
                    <?php if($userId<=0){ ?>
                    <li>
                        <div class="field-col">
                        	<label>Your Email</label>
                        	<input type="text" class="universal-txt-field" value="" id = "quickemail" name = "quickemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" blurMethod = "checkAvailability(this.value,'quickemail'); setTimeout('checkEmail()',1500); " />
							<div style="display:none;"><div class="errorMsg" id="quickemail_error" style="*float:left"></div></div>
                        </div>
                        <div class="field-col">
                        	<label>Mobile No</label>
                        	<input type="text" class="universal-txt-field" value="" id = "quickMobile_ForAnA" name = "quickMobile_ForAnA" validate = "validateMobileInteger" required = "true" maxlength = "10" minlength = "10" caption = "Mobile" />
							<div style="display:none;"><div class="errorMsg" id="quickMobile_ForAnA_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Password</label>
                            <input type="password" class="universal-txt-field" value="" id = "quickpassword_ForAnA" name = "quickpassword_ForAnA" validate = "validateStr" required = "true" maxlength = "20" minlength = "5" caption = "Password" />
							<div style="display:none;"><div class="errorMsg" id="quickpassword_ForAnA_error" style="*float:left"></div></div>
                        </div>

                        <div class="field-col">
                        	<label>Confirm Password</label>
                            <input type="password" class="universal-txt-field" value="" id = "quickconfirmpassword_ForAnA" name = "quickconfirmpassword_ForAnA" minlength = "5" maxlength = "20" required = "true" validate = "validateStr" caption = "password again" blurMethod = "validatepassandconfirmpass('quickpassword_ForAnA','quickconfirmpassword_ForAnA');" />
							<div style="display:none;"><div class="errorMsg" id="quickconfirmpassword_ForAnA_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    <?php } ?>
                    
                    <li>
                        <div class="field-col">
                        	<label>Qualification</label>
                        	<input type="text" class="universal-txt-field" value="<?php echo htmlspecialchars($qualificationText);?>" name='highestQualification' id="highestQualification" maxlength="25" minlength="2" validate="validateStr" caption="Qualification" required = "true" style="color:<?php echo $colorQ;?>;" onKeyUp="updateVCardForm();"/>
							<div style="display:none;"><div class="errorMsg" id="highestQualification_error" style="*float:left"></div></div>
                        </div>

                        <div class="field-col">
                                <label>Your Institute Name</label>
                                <input type="text" class="universal-txt-field" name='instituteName' id='instituteName'  value="<?php echo htmlspecialchars($instituteText);?>"  maxlength="150" minlength="2" validate="validateStr" caption="Institute Name" required = "true" style="color:<?php echo $colorI;?>;overflow:hidden;" onKeyUp="updateVCardForm();" />
                                                        <div style="display:none;"><div class="errorMsg" id="instituteName_error" style="*float:left"></div></div>
                        </div>
                        
                    </li>
                    
                    <li>

                        <div class="field-col">
                                <label>Designation</label>
                                <input type="text" class="universal-txt-field" id='designation' name='designation' value="<?php echo htmlspecialchars($designationText);?>"      maxlength="25" minlength="2" validate="validateStr" caption="Designation" style="color:<?php echo $colorD;?>;" onKeyUp="updateVCardForm();"/>
                                                    <div style="display:none;"><div class="errorMsg" id="designation_error" style="*float:left"></div></div>
                        </div>
                        
                        <div class="field-col">
                        	<label>Your Company Name</label>
                        	<input type="text" class="universal-txt-field" name='aboutCompany' id='aboutCompany'  value="<?php echo $aboutCompanyText;?>" maxlength="300" minlength="3" validate="validateStr" caption="Company details" style="color:<?php echo $colorAC;?>;overflow:hidden;"/>
							<div style="display:none;"><div class="errorMsg" id="aboutCompany_error" style="*float:left"></div></div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="field-col" id="FileUpload" style="width:560px;">
                        	<label>Profile Photo</label>
						    <input type='file' size="24" name='userApplicationfile[]' caption="Image" value="" id="BrowserHidden" onchange="$('FileField').value = $('BrowserHidden').value;" style=""/>
                            <div id="BrowserVisible" style="width: 276px; top:20px">
						    <input type="text" id="FileField" value=""  disabled/></div>
                    		<?php if(isset($vcardDetails[0]["VCardDetails"][0]["imageURL"]) && isset($vcardDetails[0]["VCardDetails"][0]["instituteName"])){ ?><div style="margin-top:3px;"><a target="_blank" href="<?php echo ($vcardDetails[0]["VCardDetails"][0]["imageURL"]=='')?'/public/images/photoNotAvailable.gif':$vcardDetails[0]["VCardDetails"][0]["imageURL"];?>">View Profile Image</a></div><?php } ?>
						    <div style='display:none;'><div class='errorMsg' id= 'BrowserHidden_error' style="*float:left"></div></div>
                        </div>
                    </li>
                    <li>
                    	<div class="field-col" style="width:560px;">
                        	<label>About me</label>
                        	<textarea rows="3" cols="3" class="universal-select" name='aboutMe' id='aboutMe' value="<?php echo $aboutMeText;?>"  maxlength="1000" minlength="10" required="true" validate="validateStr" style="position:relative; z-index:0;color:<?php echo $colorAM;?>;" caption="About me details"><?php echo $aboutMeText;?></textarea>
							<div style="display:none;"><div class="errorMsg" id="aboutMe_error" style="*float:left"></div></div>
                        </div>
                    </li>

		      <?php if($edit==true || $edit=='1' || $edit=='true'){ ?>
                    <li>
                    	<div class="field-col" style="width:560px; position:relative">
                        	<label>Signature <img border="0" align="top" id="questionImage" src="/public/images/question-icons2.gif" style="cursor:pointer" onmouseover="$('signatureCloud').style.display='';" onmouseout="$('signatureCloud').style.display='none';"/></label>

		                    <div class="sign-cloud" style="left:83px; top:-40px; display:none;z-index:9999;" id="signatureCloud">
                            	<span class="sign-pointer"></span>
                		        <div class="sign-cloud-inner">
                		            <div class="sign-details" style="padding-top:5px">
                                        <strong>Your signature will be appended to all the answers you give.</strong>
                                        <div class="spacer5 clearFix"></div>
                                        <p style="line-height:22px">
                                        Sample signatures<br />
                                        1. Financial expert, trading and wealth management<br/>2. IT expert, certified in CCNA, SAP and CCNP
                                        </p>
                		            </div>
                        		</div>
		                        
                		    </div>

                        	<input type="text"  class="universal-select" name='signature' id='signature' value="<?php echo $signatureText;?>"  maxlength="200" minlength="10" validate="validateStr" style="color:<?php echo $colorSi;?>;overflow:hidden;" caption="signature details" />
				<div style="display:none;"><div class="errorMsg" id="signature_error" style="*float:left"></div></div>
                        </div>
                    </li>
		      <?php } ?>	

                </ul>
        </div>
        
        <div id="form-section">
        	<h5><b>Social Details</b></h5>
                <ul>
                    <li>
                        <div class="field-col">
                        	<label>Your Facebook Profile</label>
                        	<div class="social-fileds">
                            	<input type="text" value="<?php echo $facebookText;?>" name='facebookURL' id='facebookURL' validate="validateSocial" minlength="3" maxlength="200" caption="Facebook URL" style="color:<?php echo $colorF;?>;" onKeyUp="updateVCardForm();" autocomplete="off"/>
                                <span class="fb-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div></div>
                        </div>
                        
                        <div class="field-col">
                        	<label>Your Twitter Profile</label>
                            <div class="social-fileds">
                            	<input type="text" value="<?php echo $twitterText;?>" name='twitterURL' id='twitterURL' validate="validateSocial" minlength="3" maxlength="200" caption="Twitter URL" style="color:<?php echo $colorT;?>;" onKeyUp="updateVCardForm();" autocomplete="off"/>
                                <span class="twitt-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="twitterURL_error" style="*margin-left:3px;"></div></div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Your Youtube Channel</label>
                        	<div class="social-fileds">
                            	<input type="text" value="<?php echo $youtubeText;?>" name='youtubeURL' id='youtubeURL' validate="validateSocial" minlength="3" maxlength="200" caption="Youtube URL" style="color:<?php echo $colorY;?>;" onKeyUp="updateVCardForm();" autocomplete="off"/>
                                <span class="utube-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="youtubeURL_error" style="*margin-left:3px;"></div></div>
						</div>
                        
                        <div class="field-col">
                        	<label>Your Blog URL</label>
                            <div class="social-fileds">
                            	<input type="text" value="<?php echo $blogText;?>" name='blogURL' id='blogURL' validate="validateSocial" minlength="3" maxlength="200" caption="Blog URL" style="color:<?php echo $colorB;?>;" onKeyUp="updateVCardForm();" autocomplete="off"/>
                                <span class="blog-icn"></span>

                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="blogURL_error" style="*margin-left:3px;"></div></div>
						</div>
                    </li>
                    
                    <li>
                        <div class="field-col">
                        	<label>Your Linkedin Profile</label>
                        	<div class="social-fileds">
                        		<input type="text" value="<?php echo $linkedInText;?>" name='linkedInURL' id='linkedInURL' validate="validateSocial" minlength="3" maxlength="200" caption="LinkedIn URL" style="color:<?php echo $colorL;?>;" onKeyUp="updateVCardForm();" autocomplete="off"/>
                                <span class="in-icn"></span>
                            </div>
							<div style="display:none;float:left;width:100%;"><div class="errorMsg" id="linkedInURL_error" style="*margin-left:3px;"></div></div>
                        </div>
                    </li>
                    <?php if($userId<=0 || $userId==''){ ?>
                    <li class="captcha-row" style="">
                    	<p>Type in the character you see in the picture below</p>
						<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeForAnAReg" width="100" height="40" onabort="javascript:reloadCaptcha(this.id);" onClick="javascript:reloadCaptcha(this.id);" id = "registerCaptacha_ForAnA" align="absmiddle" />&nbsp;&nbsp;&nbsp;
						<input type="text" id = "securityCode_ForAnA" name = "securityCode_ForAnA" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" class="universal-txt-field" style="width:65px; vertical-align:middle; padding:7px" />
						<div style="padding-left:19px;"><div class="errorMsg" id="securityCode_ForAnA_error"></div></div>

						<div class="spacer10 clearFix"></div>
						<input type="checkbox" checked id = "quickagree_ForAnA" required="true"/> I agree to the <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>

						<div class="errorPlace" style="margin-top:2px;display:none; float:left;width:100%;" >
							<div class="errorMsg" id = "quickagree_ForAnA_error"></div>
						</div>
	
						<div class="errorPlace" style="margin-top:5px;display:none; float:left;width:100%;">
							<div class="errorMsg" id = "quickerror_ForAnA"></div>
						</div>	

					</li>
					<?php } ?>

                    <li>
                    	<input type="submit" value="Submit" class="orange-button" id="expertSubmitButton"/>
						&nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/working.gif' border=0 align=""></span>
                    </li>
                    
                </ul>

        </div>
        </form>
        
        <div class="clearFix"></div>
        <div id="testimonial-section">
        	<h6><b>Expert Testimonials</b></h6>
			<div id="slider1" tabindex="-1">
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/Chatterjee.jpg" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" tabindex="-1" style="cursor:default;">Dr Goutam Chatterjee</a><br />
						<em>Director, TITR, Bhopal</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">I am immensely satisfied with shiksha Caf&#233; Buzz, as "Cloud Bonding" with fellow experts feels like a family. I feel even if a single person is able to gain in his life due to my advice, I would have achieved the purpose of joining shiksha.com.</em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/gautam.jpg" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" style="cursor:default;" tabindex="-1">Gautam Joshi</a><br />
						<em>BBA, MBA,IMS</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">Being a part of shiksha Cafe Buzz for three years now is itself a satisfaction. Helping students, sharing and getting knowledge, interacting with intelligent experts and having some more professional links is what makes shiksha at the top position in my favourites list. </em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/Nikhlesh.jpg" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" style="cursor:default;" tabindex="-1">Nikhlesh Mathur</a><br />
						<em>MBA,Chemical Engineer ,NIT Rourkela</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">As a person who spends his time to guide students in their educational pursuits through shiksha.com I have a feeling of satisfaction.</em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/dummyImg.gif" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" style="cursor:default;" tabindex="-1">Rahul Chauhan</a><br />
						<em>B.Sc CS,CCE Operations</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">I feel very honoured and proud to be a member of the literacy movement called "shiksha.com" and the rocking Cafe Buzz even if one student because of my advice makes a correct career choice, becomes successful, my sole motto of joining shiksha.com voluntarily is served.</em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/Govind.jpg" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" style="cursor:default;" tabindex="-1">Govind S</a><br />
						<em>MBA M Phil, CRTI, PGDHRO, Manager- HR & Admin, Sony Music</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">The experience has been good and immensely satisfying. The discussion is also good part, debate, thinking, clarity, communication all has added new dimension to my knowledge and I am learning more.</em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
				<div tabindex="-1" style="outline:none;">
					<div class="figure"><img height=87 width=81 src="/public/images/dummyImg.gif" alt="" /></div>
					<div class="details">
						<p><a href="javascript:void(0);" style="cursor:default;" tabindex="-1">Khushwant Jain</a><br />
						<em>MMS,Assistant Manager, Dachser India Pvt. Ltd.</em></p>
						
						<div class="testiContents">
						<span id="bqstart" class="bqstart">&#8220;</span>
						<em id="student_text">Cafe Buzz is an excellent Section where I was able to ask a question, Discuss a topic or Announce something interesting I got one of the Best Answers from Shiksha Cafe Advisors for my career  and was motivated to  help students by solving career queries regularly.</em>
						<span class="bqend">&#8221;</span></div>
					</div>
				</div>
            </div>
            <div class="carouselBullets carouselBulletsWidth" style="width:84px">
				<ul>
                	<li id="slide0" class="activeButton">&nbsp;</li>
                    <li id="slide1">&nbsp;</li>
                    <li id="slide2">&nbsp;</li>
                    <li id="slide3">&nbsp;</li>
                    <li id="slide4">&nbsp;</li>
                    <li id="slide5">&nbsp;</li>
                </ul>
            </div>
            <div class="clearFix"></div>
        </div>
    </div>
    
    <div id="right-col">
    	<h3>Why becoming an expert is a good idea?</h3>
        <div id="rt-content">
            <ul>
                <li>
                    <h2>Answer Questions</h2>
                    <p>Answer questions and help someone make a career <br />decision. <a href="javascript:void(0);" onmouseover="showCloudDiv('answerQuestion');" onmouseout="hideCloudDiv('answerQuestion');">Know More</a></p>
                    <div class="info-cloud" style="display:none;" id="answerQuestionCloud">
                        <div class="info-inner">
                            <div class="figure"><img src="/public/images/ans-ques.gif" alt="" /></div>
                            <div class="details">
                            	<strong>Answer Questions</strong>
                                <p>Defining a career path is not always easy. Remember, the questions in your mind when you were pondering on the few of the most important questions of your life: what do I want to become? Which career/college is best for me? Clearing doubts by sharing your knowledge or providing expert advice is one of the many ways to make a positive contribution to the community and heighten your professional impact.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                <li class="quality-ans">
                    <h2>Quality Answer</h2>
                    <p>Your quality answers get you reputation <br />within the community. <a href="javascript:void(0);" onmouseover="showCloudDiv('qualityAnswer');" onmouseout="hideCloudDiv('qualityAnswer');" >Know More</a></p>
                    <div class="info-cloud" style="right:206px; top:356px; _left:-342px; _top:215px; top:350px\0/; display:none;" id="qualityAnswerCloud">
                        <div class="info-inner">
                            <div class="figure"><img src="/public/images/best-ans.gif" alt="" /></div>
                            <div class="details">
                            	<strong>Quality Answer</strong>
                                <p>Providing answers that are relevant, to the point and help to dissolve the confusion of a student increases your prominence within the community and is indicative of your thought process and your personality. Your quality answers also get you a reputation within the community. More importantly, your relevant and well thought answers could help shape somebody's career.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                <li class="high-repo">
                    <h2>Higher reputation</h2>
                    <p>With higher reputation, progress through<br />expert levels. <a href="javascript:void(0);" onmouseover="showCloudDiv('higherReputation');" onmouseout="hideCloudDiv('higherReputation');" >Know More</a></p>
                    <div class="info-cloud" style="right:257px; top:590px; _left:-392px; _top:448px; top:583px\0/; display:none;" id="higherReputationCloud">
                        <div class="info-inner">
                            <div class="figure"><img src="/public/images/higher-repo.gif" alt="" /></div>
                            <div class="details">
                            	<strong>Higher reputation</strong>
                                <p>Reputation is a rough measurement of how much the community trusts you. It is earned by providing quality and well researched answers. The more reputation you earn, the more privileges you gain on Shiksha Caf&#233;. You gain reputation when the community votes on your quality answers.  More importantly, it is satisfaction beyond reputation that makes your day.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
                
                <li class="enjoy-promi">
                    <h2>Enjoy Prominence</h2>
                    <p>Enjoy prominence and recognition in community with<br />reputation and expert level. <a href="javascript:void(0);" onmouseover="showCloudDiv('enjoyProminence');" onmouseout="hideCloudDiv('enjoyProminence');" >Know More</a></p>
                    <div class="info-cloud" style="right:177px; top:794px; _left:-313px; _top:652px; top:785px\0/; display:none;" id="enjoyProminenceCloud">
                        <div class="info-inner">
                            <div class="figure"><img src="/public/images/advisor.gif" alt="" /></div>
                            <div class="details">
                            	<strong>Enjoy Prominence</strong>
                                <p>Enjoy prominence and recognition in community with reputation and expert level. It is incredible to have a fan following and to stand apart from the rest by the quality of your answers. Above all, it is the satisfaction of being able to help someone who looks up to you that strengthens your public profile and brings to the forefront your professional expertise, knowledge and above all your generosity in providing good advice.</p>
                            </div>
                        </div>
                        <span class="pointer">&nbsp;</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="vcard-link" onClick="showVCardForm(this);" id="vcardClosedDiv">
    	<strong>See how your Vcard will look</strong>
        <span class="vcard-arrow"></span>
    </div>
    <div class="clearFix"></div>
</div>


</div>

<?php 
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'', 'loadJQUERY'=>'YES');
	$this->load->view('common/footer',$bannerProperties1);
?>

<script>
var a = $j('#right-col');
//var rightMargin = ((screen.width-988)/2)+9;
if(window.innerWidth){
	if(window.innerWidth<988)
		$('vcardClosedDiv').style.left = (a.offset().left-213)+'px';
	else
		$('vcardClosedDiv').style.left = a.offset().left+'px';
}
else{
	if(screen.availWidth<988)
		$('vcardClosedDiv').style.left = (a.offset().left-213)+'px';
	else
		$('vcardClosedDiv').style.left = a.offset().left+'px';
}

$('vcardClosedDiv').style.width = '312px';

if(window.innerHeight !==undefined){
        var topMargin= window.innerHeight-66;
}
else{
        var topMargin= screen.availHeight-120-80;
}
$('vcardClosedDiv').style.top = topMargin+'px';
</script>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.iUtil"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.drag"); ?>"></script>
<script>
//$j(document).ready(function(){
	$j('#slider1').bxSlider({
		auto: true,
		nextText:'',
		prevText:'',
		pause:8000,
		onAfterSlide: function(currentSlide, totalSlides){
			clearClass();
			$('slide'+currentSlide).className = "activeButton";
		}
	});
//});

try{
    //addOnFocusToopTipOnline(document.getElementById('OnlineForm'));
    addOnBlurValidate(document.getElementById('expertForm'));
} catch (ex) {
    // throw ex;
}    


function showUploadResponse(str)
{
    //alert(str);
	if(str == "Blank"){
		return false;
	}
	validationFail();
	if(str == "email" || str == "displayname" || str == "both" || str == "code"){
		reloadCaptcha('registerCaptacha_ForAnA','secCodeForAnAReg');
		if(str == "email"){
			//showErrorMessageForAnAReg('quickemail','The email address provided is already registered.');
			$('quickerror_ForAnA').parentNode.style.display = 'inline';
			$('quickerror_ForAnA').innerHTML = "You are already registered with us. Please <a href='javascript:void(0);' onClick='oristate1();'>Sign In.</a>" ;
		}
		if(str == "displayname"){
			showErrorMessageForAnAReg('quickfirstname_ForAnA','This displayname is not available.');
		}
		if(str == "both"){
			showErrorMessageForAnAReg('quickemail','The email address provided is already registered.');
			$('quickerror_ForAnA').parentNode.style.display = 'inline';
			$('quickerror_ForAnA').innerHTML = "You are already registered with us. Please <a href='javascript:void(0);' onClick='oristate1();'>Sign In.</a>" ;
		}
		if(str == "code"){
			showErrorMessageForAnAReg('securityCode_ForAnA','Please enter the Security Code as shown in the image');
		}
		return false;
	}else if(str!='ExpertModified' && str!='ExpertAdded'){	//Something has gone wrong
		var k = 1;
		str = eval('(' + str + ')');
		var errors = str.errors;
		for(var i=0;i<errors.length;i++) {
			if(errors[i][0] == 'invalidToken') {
				alert(errors[i][1]);
			}
			else {
				$(errors[i][0]+'_error').innerHTML = errors[i][1];
				$(errors[i][0]+'_error').parentNode.style.display="";
				if(k == 1){
					$(errors[i][0]).focus();
					k = 2;
				}
			}
		}
	}
	else{	//Either the expert details have been Added or Modified
		if(str=='ExpertModified'){
				var overlayWidth = 325;
				var overlayHeight = 300;
				urlToRedirect = '/questions';
				var overlayTitle = '<span><b>Success</b></span>';
				var overlayContent = '<div style="margin: 5px 5px 5px 5px;">\
							  Your profile details have been successfully edited.\
							<div style="margin-top:10px;">\
								<input type="button" value="OK" class="attachButton" onClick="window.location.href=\''+urlToRedirect+'\';"/>\
							</div>\
							</div>';
				showOverlayAnA(overlayWidth, overlayHeight, overlayTitle, overlayContent,false);
				window.setTimeout( function(){ window.location.href = urlToRedirect; }, 5000);
		}
		else if(str=='ExpertAdded'){
				var overlayWidth = 500;
				var overlayHeight = 450;
				var overlayTitle = '<span><b>Thank you</b></span>';
				var overlayContent = '<div style="margin: 5px 5px 5px 5px;" class="bld">Thanks for your interest in becoming an expert. A Shiksha moderator is currently reviewing your profile. In the meanwhile, why don\'t you start answering some of the questions on Shiksha Cafe</div>';
				overlayContent += '<div style="margin: 5px 5px 5px 5px;"><font color="grey">Please select a category in which you wish to answer questions</font></div>';
				overlayContent += '<?php echo $catHTML;?>';
				showOverlayAnA(overlayWidth, overlayHeight, overlayTitle, overlayContent,false);
		}
		return false;
	}
}

function populateExpertTooltip(overlayElement)
{
    //var userDetailsObject1 = (eval('userDetailsObject["'+userId+'"]'));
    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
    var weeklyParPointDisplay = ' (0 this week)';
    var weeklyPointDisplay = ' (0 this week)';
    <?php if( isset($vcardDetails[0]["participateUserDetails"][1]["weeklyParticipatePoints"] ))
			$weeklyParPointDisplay = ' ('.$vcardDetails[0]["participateUserDetails"][1]["weeklyParticipatePoints"].' this week)';
		if( isset($vcardDetails[0]["otherUserDetails"][1]["weeklyPoints"] ))
			$weeklyPointDisplay = ' ('.$vcardDetails[0]["otherUserDetails"][1]["weeklyPoints"].' this week)';
	?>
	$('viewAllQuestionAndAnswerLinksVCard').innerHTML = '<a href="/getUserProfile/<?php echo $vcardDetails[0]["VCardDetails"][0]["displayname"];?>/Answer#userActivities"><?php echo $vcardDetails[0]["VCardDetails"][0]["totalAnswers"];?> Answers</a>';
	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["bestAnswers"];?>'=='0')
		var percentBestAns = 0;
	else
		var percentBestAns = Math.ceil((<?php echo $vcardDetails[0]["VCardDetails"][0]["bestAnswers"];?>/<?php echo $vcardDetails[0]["VCardDetails"][0]["totalAnswers"];?>)*100);
	//$('viewAllQuestionAndAnswerLinksVCard').innerHTML = '<?php echo $vcardDetails[0]["VCardDetails"][0]["totalAnswers"];?> Answers</a> 					&nbsp;(&nbsp;</span>'+percentBestAns+'% Best Answers , <?php echo $vcardDetails[0]["otherUserDetails"][0]["likes"];?> <img src="/public/images/hUp.gif" /> likes) <br/> <?php echo $vcardDetails[0]["otherUserDetails"][0]["totalPoints"];?> Points'+'<?php echo $weeklyPointDisplay;?>';
	$('viewAllQuestionAndAnswerLinksVCard').innerHTML = '<?php echo $vcardDetails[0]["VCardDetails"][0]["totalAnswers"];?> Answers</a> 					&nbsp;(&nbsp;</span><?php echo $vcardDetails[0]["otherUserDetails"][0]["likes"];?> <img src="/public/images/hUp.gif" /> likes) <br/> &nbsp; ';

	$('vcardName').innerHTML = $('quickfirstname_ForAnA').value;
	$('vcardLevel').innerHTML = '<?php echo $vcardDetails[0]["VCardDetails"][0]["ownerLevel"];?>';
	$('vcardDesignation').innerHTML = $('designation').value;
	$('vcardInstituteName').innerHTML = $('instituteName').value;
	$('vcardQualification').innerHTML = $('highestQualification').value;
	$('discussionPostCount').innerHTML = '<?php echo $vcardDetails[0]["participateUserDetails"][0]["discussionPosts"];?> discussion posts';
	$('announcementPostCount').innerHTML = '<?php echo $vcardDetails[0]["participateUserDetails"][0]["announcementPosts"];?> announcements';
	//$('participatePoints').innerHTML = '<?php echo $vcardDetails[0]["participateUserDetails"][0]["totalParticipatePoints"];?> Cafe points'+'<?php echo $weeklyParPointDisplay;?>';
	$('participatePoints').innerHTML = '&nbsp;';
	$('onlyForVCard').style.display = '';
	$('vcardQualification').style.display = '';
	//Code for the User level
	$('userLevelStar').className = 'pt2 Fnt11';
	switch('<?php echo $vcardDetails[0]["VCardDetails"][0]["ownerLevel"];?>'){
		case 'Advisor': $('userLevelStar').className = 'pt2 Fnt11 str_1lx33'; break;
		case 'Senior Advisor': $('userLevelStar').className = 'pt2 Fnt11 str_12x33'; break;
		case 'Lead Advisor': $('userLevelStar').className = 'pt2 Fnt11 str_13x33'; break;
		case 'Principal Advisor': $('userLevelStar').className = 'pt2 Fnt11 str_14x33'; break;
		case 'Chief Advisor': $('userLevelStar').className = 'pt2 Fnt11 str_15x33'; break;
	}
	//Code for the Number of followers
	<?php 
		 $followHTML = '(No followers)';
		if(isset($vcardDetails[0]["VCardDetails"][1]) && count($vcardDetails[0]["VCardDetails"][1][0])>0){
			$followHTML = '(<a href="javascript:void(0);" style="cursor:default;">'.count($vcardDetails[0]["VCardDetails"][1][0]).' followers</a>)';
		} 
	?>
	$('followers').innerHTML = '<?php echo $followHTML;?>';

	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["imageURL"]; ?>'!='' && '<?php echo $vcardDetails[0]["VCardDetails"][0]["imageURL"]; ?>'.indexOf('dummyImg') == -1){
			$('vcardImage').innerHTML = '<img src="'+getSmallImage('<?php echo $vcardDetails[0]["VCardDetails"][0]["imageURL"];?>')+'" border="0" id="userImageForQuestion"/>';
	}
	
	//Code for the Sharing links
	var showCatch = false;
	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["blogURL"]; ?>' != ''){
		$('vcardBlog').innerHTML = '<a href="javascript:void(0)" class="blog" style="text-decoration:none" onClick="window.open(\'<?php echo $vcardDetails[0]["VCardDetails"][0]["blogURL"]; ?>\',\'window1\');">&nbsp;</a>';
		showCatch = true;
	}else
		$('vcardBlog').innerHTML = '';

	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["facebookURL"]; ?>' != ''){
		$('vcardBrijj').innerHTML = '<a href="javascript:void(0)" style="text-decoration:none"><img src="/public/images/facebook.gif" onClick="window.open(\'<?php echo $vcardDetails[0]["VCardDetails"][0]["facebookURL"]; ?>\',\'window2\');"/></a>';		
		showCatch = true;
	}
	else
		$('vcardBrijj').innerHTML = '';
	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["linkedInURL"]; ?>' != ''){
		$('vcardLinkedIn').innerHTML = '<a href="javascript:void(0)" class="linkin" style="text-decoration:none" onClick="window.open(\'<?php echo $vcardDetails[0]["VCardDetails"][0]["linkedInURL"]; ?>\',\'window3\');">&nbsp;</a>';
		showCatch = true;
	}
	else
		$('vcardLinkedIn').innerHTML = '';
	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["twitterURL"]; ?>' != ''){
		$('vcardTwitter').innerHTML = '<a href="javascript:void(0)" class="twitter" style="text-decoration:none" onClick="window.open(\'<?php echo $vcardDetails[0]["VCardDetails"][0]["twitterURL"]; ?>\',\'window4\');">&nbsp;</a>';
		showCatch = true;
	}
	else
		$('vcardTwitter').innerHTML = '';
	if('<?php echo $vcardDetails[0]["VCardDetails"][0]["youtubeURL"]; ?>' != ''){
		$('vcardYoutube').innerHTML = '<a href="javascript:void(0)" class="youtube" style="text-decoration:none" onClick="window.open(\'<?php echo $vcardDetails[0]["VCardDetails"][0]["youtubeURL"]; ?>\',\'window5\');">&nbsp;</a>';
		showCatch = true;
	}
	else
		$('vcardYoutube').innerHTML = '';
	if(!(showCatch))
	{
		$('shareLink').style.display = 'none';
	}
	else{
		$('shareLink').style.display = 'block';
	}
	
	$('bottomSpacing').className = 'spacer5';
	$('betweenSpacing').style.display = 'none';
	<?php
		$reputationPonits = 10;
		if(isset($vcardDetails[0]["VCardDetails"][0]["reputationPoints"]))
			$reputationPonits = $vcardDetails[0]["VCardDetails"][0]["reputationPoints"];
	?>
	$('reputationPoints').innerHTML = 'Reputation Index: <b><?php echo $reputationPonits;?></b>';

    
    $('contentDivContribute').style.display = '';
	$('userCommonOverlayForVCard').style.display = '';
	if(!imageAddedOnVCard){
		$('userCommonOverlayForVCard').innerHTML = '<div style="float:right;clear:both;width:100%;text-align:right;padding-right:10px;"><img src="/public/images/close-icn-expert.gif" border=0 onClick="hideVCardForm();" style="cursor:pointer;"></div><div class="clearFix"></div>'+$('userCommonOverlayForVCard').innerHTML;
		imageAddedOnVCard = true;
	}
    return true
}

var imageAddedOnVCard = false;

function checkEmail(){
	if($('quickemail_error').innerHTML=='The email address provided is already registered.')
		$('quickemail_error').innerHTML = 'The email address provided is already registered.<br/><a href=\'javascript:void(0);\' onClick=\'oristate1();\'>Sign In here.</a>';
}

</script>

<script>
function isScrolledIntoView(elem) {
   var docViewTop = $j(window).scrollTop();
   var docViewBottom = docViewTop + $j(window).height();
   var elemTop = $('userCommonOverlayForVCard').style.top;
   elemTop = elemTop.split('px');
   elemTop = elemTop[0];
   var elemBottom = Number(elemTop) + Number(210);
   return ((elemTop >= docViewTop) && (elemBottom <= docViewBottom));
 }
var myelement = $j('#userCommonOverlayForVCard'); // the element to act on if viewable
$j(window).scroll(function() {
if($('userCommonOverlayForVCard') && $('userCommonOverlayForVCard').style.display!='none'){
     if(isScrolledIntoView(myelement)) {
     } else {
         $('userCommonOverlayForVCard').style.top = (Number($j(window).scrollTop())+ Number($j(window).height()/2) - 120) + 'px';
     }
}
});
</script>
