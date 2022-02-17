<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		if($userId != 0){
			$tempJsArray = array('discussion','commonnetwork','alerts','ana_common','tooltip','myShiksha');
			$loggedIn = 1;
		}else{
			$tempJsArray = array('discussion','commonnetwork','ana_common');
			$loggedIn = 0;
		}
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header','shiksha_common'),
                        'js' => array('common','vcard'),
						'jsFooter'=>    $tempJsArray,
						'title'	=>	'Ask and Answer - V-Card Form',
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>'Ask Questions on various education and career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.',
						'metaKeywords'	=>'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'callShiksha'=>1,
					);
		$this->load->view('common/header', $headerComponents);
		$data = array(
				'successurl'=> '',
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,
				
			    );
		$this->load->view('network/mailOverlay',$data);
		$this->load->view('common/commonOverlay');
		$uploadData['vcard'] = '1';
		$this->load->view('user/uploadMyImage',$uploadData);
		if(is_array($Result)){
		    $totalPoints = $Result[0]['otherUserDetails'][0]['totalPoints'];
		    $likes = $Result[0]['otherUserDetails'][0]['likes'];
		    if(isset($Result[0]['otherUserDetails'][1]))
		      $weeklyPoints = $Result[0]['otherUserDetails'][1]['weeklyPoints'];
		    $totalParPoints = $Result[0]['participateUserDetails'][0]['totalParticipatePoints'];
		    $discussionPosts = $Result[0]['participateUserDetails'][0]['discussionPosts'];
		    $announcementPosts = $Result[0]['participateUserDetails'][0]['announcementPosts'];
		    if(isset($Result[0]['participateUserDetails'][1]))
		      $weeklyParPoints = $Result[0]['participateUserDetails'][1]['weeklyParticipatePoints'];
		}
		if(is_array($followUser))
		    $followUserCount = count($followUser);
		$userName = (isset($vCardFields[0]['userName']) && (trim($vCardFields[0]['userName'])!=''))?1:0;
		$designation = (isset($vCardFields[0]['designation']) && (trim($vCardFields[0]['designation'])!=''))?1:0;
		$instituteName = (isset($vCardFields[0]['instituteName'])&&(trim($vCardFields[0]['instituteName'])!=''))?1:0;
		$highestQualification = (isset($vCardFields[0]['highestQualification']) && (trim($vCardFields[0]['highestQualification'])!=''))?1:0;
		$aboutMe = (isset($vCardFields[0]['aboutMe']) && (trim($vCardFields[0]['aboutMe'])!=''))?1:0;
		$imageURL = isset($vCardFields[0]['imageURL'])?$vCardFields[0]['imageURL']:'';
		if($imageURL != ''){
		  echo "<script language=\"javascript\"> ";
		  echo "var displayImage = getSmallImage('".$imageURL."');";
		  echo "</script> ";
		}
		$blogURL = (isset($vCardFields[0]['blogURL'])&&($vCardFields[0]['blogURL']!=''))?1:0;
		$brijjURL = (isset($vCardFields[0]['brijjURL'])&&($vCardFields[0]['brijjURL']!=''))?1:0;
		$linkedInURL = (isset($vCardFields[0]['linkedInURL'])&&($vCardFields[0]['linkedInURL']!=''))?1:0;
		$twitterURL = (isset($vCardFields[0]['twitterURL'])&&($vCardFields[0]['twitterURL']!=''))?1:0;
		$youtubeURL = (isset($vCardFields[0]['youtubeURL'])&&($vCardFields[0]['youtubeURL']!=''))?1:0;
		$userLevel = (isset($vCardFields[0]['ownerLevel']) && ($vCardFields[0]['ownerLevel']!=''))?$vCardFields[0]['ownerLevel']:'Beginner';
		$previewDisplayName = preg_replace("/((.){15}?)/i",'$1<br/>',$vCardFields[0]['displayname']);
		$vcardStatus = ($designation==0 && $institute==0)?0:1;
		echo "<script language=\"javascript\"> ";
		echo "var loggedIn = '".$userId."';";		
		echo "var loggedInUserId = '".$userId."';";
		echo "</script> ";		
?>
<script>
var userVCardObject = new Array();
</script>
<div width="100%" id='previewDiv' style="display:none;">
  <div> <table cellspacing='0' border='0' cellpadding='0'>
  <tr><td valign='top' ><div id='usernamePreview' style='margin-top:65px;margin-left:10px;'></div></td>
  <td valign='top'><div width='100%'><div id="vcardPreview" style='width:450px;position:relative;margin-left:0px;margin-top:-10px;'><?php $this->load->view('common/userCommonOverlay');?></div></div></td>
  </tr></table></div>
  <div style='margin-top:15px;margin-bottom:10px;' id="okButtonDiv" class="txt_align_c"><input type="button" align='middle' class="homeShik_SubmitBtn" value="OK" onClick="hideOverlay();" />&nbsp;</div>
</div>
<div style="" class="wrapperFxd">
    <div class="mar_full_10p">
        <div class="">
        	<div class="">
            	<div class="rht">
                	<div class="pr250"><br /> <br /><br />
                    	<b class="bld Fnt15">Get popular and increase your fan following with shiksha exclusive v-card</b>
                        <div class="lineSpace_5">&nbsp;</div>
                        <div class="Fnt12">Shiksha.com gives you an option to create your own personalized V-Card. In this card, you can share more personal & professional details about yourself and allow other people connect with you through your social profiles</div>
                    </div>
                </div>
            </div>
        </div>
        <?php
	  $formDisabled = false;
	  if($loggedIn == 0 || $userLevel == 'Beginner' || $userLevel == 'Trainee'){
		$formDisabled = true;
		if($designation != 1){
	?>
	<div class="lineSpace_10">&nbsp;</div>
        <div class="vCdErrBox" style="height:35px;padding: 5px 5px 5px 5px;"><img src="/public/images/vCdErrIcn.gif" align="absmiddle" />
        <?php if($loggedIn == 0){ ?>
	  <span class="Fnt14">&nbsp;Please <a href="/user/Userregistration/index/">Join Shiksha</a> or <a id='signincard' name='signincard' href="#" onclick="showuserLoginOverLay(this,'SHIKSHA_HEADER_TOP_SIGNIN','refresh');">Sign In</a> to create the V-Card</span>
	<?php }else{ ?>
	  <span class="Fnt14">&nbsp;Your current Shiksha Level is "<span class="fcOrg"><?php echo $userLevel;?></span>". You need to achieve Shiksha <span class="fcOrg">Advisor</span> Level to create the V-CARD</span>
	<?php } ?>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<?php
	}
	}{
	?>

	<div class="lineSpace_10">&nbsp;</div>
        <div class="Fnt16 bld">Create Your Exclusive VCard Here</div>
        <div class="lineSpace_5">&nbsp;</div>
	<div id="formDiv"><form action="/messageBoard/MsgBoard/vcardInsert" id='VCardForm' name ='VCardForm' onsubmit="if(!validateFormForCard(this)){return false;};new Ajax.Request('/messageBoard/MsgBoard/vcardInsert',{onSuccess:function(request){javascript:showSuccessOverlay(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post"  >
        <div>
            <div class="raised_lgraynoBG">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_lgraynoBG">
            	<div class="wdh100">
                	<div class="lineSpace_10">&nbsp;</div>
                	<div class="mlr15">
                    	<div>
                            <div class="float_L w250">
				<?php if($imageURL == '' || strrpos($imageURL, 'dummyImg.gif') || strrpos($imageURL, 'photoNotAvailable.gif')){?>
                                <img src="/public/images/dummyImg.gif" align="absbottom" id="userImage"/>&nbsp;&nbsp;	<?php if(!($formDisabled)){ ?><a href="#" onClick="return showUploadMyImage('updateMyImageOverlay');" ><span id="uploadImageText">Upload Photo</span></a><?php } ?>
				<?php } else { ?>
                                <img src="/public/images/loader.gif" align="absbottom" id="userImage" />&nbsp;&nbsp;	<?php if(!($formDisabled)){ ?><a href="#" onClick="return showUploadMyImage('updateMyImageOverlay');"><span id="uploadImageText">Edit</span></a><?php } ?>
				<?php } ?>
                            </div>
                            <div class="float_L w650">
				
				<?php if($userName == 0){?>
				  <div id='userNameDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" id='userNameInput' name='userNameInput' class="w200 " default="Enter Your Name" value="Enter Your Name" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" maxlength="25" minlength="3" validate="validateDisplayName" caption="Name" <?php if($formDisabled) echo "disabled='true'";?>/> </td></tr></table></div> 
				<?php } else { ?>
				  <div id='userNameDiv' style="display:none;"></div>
				  <div id='userNameSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='userNameSpan' class='w200 intBox' style='width:200px'><?php echo (strlen($vCardFields[0]['userName']) <= 25)?$vCardFields[0]['userName']:(substr($vCardFields[0]['userName'],0,25).'...'); ?></span> &nbsp;&nbsp;</td><?php if(!$formDisabled){ ?><td><a href="javascript:void(0);" onClick="editText('userName','200',25,3)">Edit</a></td><?php } ?></tr></table></div>
				<?php } ?>
				  <div style="display:none"><div class="errorMsg" id="userName_error" style="*margin-left:3px;"></div></div>
                                <div class="lineSpace_10">&nbsp;</div>
				
				<?php if($designation == 0){?>
				  <div id='designationDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" id='designationInput' name='designationInput' tip='designationInput' class="w300 " default="Enter Your Designation" value="Enter Your Designation" onblur="checkTextElementOnTransition(this,'blur');hidetip();" style="color:#ada6ad;font-size:12px;font-family:Arial;" maxlength="100" minlength="5" validate="validateStr" caption="Designation" <?php if($formDisabled) echo "disabled='true'";?>/> </td></tr></table></div> 
				<?php } else { ?>
				  <div id='designationDiv' style="display:none;"></div>
				  <div id='designationSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='designationSpan' class='w300 intBox' style='width:300px'><?php echo (strlen($vCardFields[0]['designation']) <= 34)?$vCardFields[0]['designation']:(substr($vCardFields[0]['designation'],0,34).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('designation','300',100,5)">Edit</a></td></tr></table></div>
				<?php } ?>
				  <div style="display:none"><div class="errorMsg" id="designation_error" style="*margin-left:3px;"></div></div>
                                <div class="lineSpace_10">&nbsp;</div>
				
				<?php if($instituteName == 0){?>
                                <div id='instituteNameDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='instituteNameInput' id='instituteNameInput' class="w300 " default="Enter Your Company Name/Institute" value="Enter Your Company Name/Institute" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" maxlength="150" minlength="3" validate="validateStr" caption="Company/Institute Name" <?php if($formDisabled) echo "disabled='true'";?>/></td></tr></table></div> 
				<?php } else { ?>
				<div id='instituteNameDiv' style="display:none;"></div>
                                <div id='instituteNameSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='instituteNameSpan' class='w300 intBox' style='width:300px'><?php echo (strlen($vCardFields[0]['instituteName']) <= 34)?$vCardFields[0]['instituteName']:(substr($vCardFields[0]['instituteName'],0,34).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('instituteName','300',150,3)">Edit</a></td></tr></table></div>
				<?php } ?>
                                <div style="display:none"><div class="errorMsg" id="instituteName_error" style="*margin-left:3px;"></div></div>

                            </div>
                            <div class="clear_B">&nbsp;</div>
						</div>
                        <div class="lineSpace_10">&nbsp;</div>

			<?php if($highestQualification == 0){?>
			<div id='highestQualificationDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='highestQualificationInput' id="highestQualificationInput" class="w200" default="Enter Your Highest Qualification" value="Enter Your Highest Qualification" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" maxlength="150" minlength="2" validate="validateStr" caption="Highest Qualification" <?php if($formDisabled) echo "disabled='true'";?>/></td></tr></table></div> 
			<?php } else { ?>
			<div id='highestQualificationDiv' style="display:none;"></div>
			<div id='highestQualificationSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='highestQualificationSpan' class='w200 intBox' style='width:200px'><?php echo (strlen($vCardFields[0]['highestQualification']) <= 25)?$vCardFields[0]['highestQualification']:(substr($vCardFields[0]['highestQualification'],0,25).'...'); ?></span> &nbsp;&nbsp;</td><?php if(!$formDisabled){ ?><td><a href="javascript:void(0);" onClick="editText('highestQualification','200',150,2)">Edit</a></td><?php } ?></tr></table></div>
			<?php } ?>
                        <div style="display:none"><div class="errorMsg" id="highestQualification_error" style="*margin-left:3px;"></div></div>
                        
			<div class="lineSpace_15">&nbsp;</div>
                        
			Level: <b><span name='userLevelInput' id='userLevelInput'><?php if($loggedIn == 0) echo "...";else echo $userLevel; ?></span></b>
                        
			<div class="lineSpace_15">&nbsp;</div>

			<?php if($aboutMe == 0){?>
			<div id='aboutMeDiv'><table border='0' cellspacing='0' cellpadding='0' width="100%"><tr><td><textarea type="text" name='aboutMeInput' id='aboutMeInput' default="About me" value="About me" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;width:80%;height:42px;" rows="1" <?php if($formDisabled) echo "disabled='true'";?>>About me</textarea>&nbsp;<?php if($vcardStatus==1){?><a href="javascript:void(0);" onClick="saveElement('aboutMe',165);">Save</a><?php } ?></td></tr></table></div>
			<div id='aboutMeSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='aboutMeSpan' class='intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden;"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('aboutMe','165',0,0)">Edit</a></td></tr></table></div>
			<?php } else { ?>
			<div id='aboutMeDiv' style="display:none;"></div>
			<div id='aboutMeSpanDiv'><table border='0' cellspacing='0' cellpadding='0' width="100%"><tr><td width="30%"><span id='aboutMeSpan' class='intBox' style="DISPLAY: inline-block; overflow: hidden;"><?php echo (strlen($vCardFields[0]['aboutMe']) <= 40)?$vCardFields[0]['aboutMe']:(substr($vCardFields[0]['aboutMe'],0,40).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('aboutMe','165',350,3)">Edit</a></td></tr></table></div>
			<?php } ?>
            <div style="display:none"><div class="errorMsg" id="aboutMe_error" style="*margin-left:3px;"></div></div>

			<div class="lineSpace_15">&nbsp;</div>
                        <div class="line_1">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>
                        Catch me on:
                        <div class="lineSpace_10">&nbsp;</div>
                        <div>
			    <table border='0' cellpadding='0' cellspacing='0' align='top'>
			    <tr><td>
			     <div class="float_L w230 mar_bottom_15p">
                            	<div>
                                <img src="/public/images/blog.gif" /><br />

				<?php if($blogURL == 0){?>
				<div id='blogURLDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='blogURLInput' id='blogURLInput' class="w165 " default="Enter Your Blog URL" value="Enter Your Blog URL" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" <?php if($formDisabled) echo "disabled='true'";?>/>&nbsp;</td><?php if($vcardStatus==1){?><td><a href="javascript:void(0);" onClick="saveElement('blogURL',165);">Save</a></td><?php } ?></tr></table></div>
				<div id='blogURLSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='blogURLSpan' class='intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden;"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('blogURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } else { ?>
				<div id='blogURLDiv' style="display:none;"></div>
				<div id='blogURLSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='blogURLSpan' class='intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden;"><?php echo (strlen($vCardFields[0]['blogURL']) <= 22)?$vCardFields[0]['blogURL']:(substr($vCardFields[0]['blogURL'],0,22).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('blogURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } ?>

                                <div style="display:none"><div class="errorMsg" id="blogURL_error" style="*margin-left:3px;"></div></div>
                                <span class="Fnt11 drkGry">eg: http://web20ash.blogspot.com/</span>
                                </div>
                            </div>
			    </td><td>

                            <div class="float_L w230 mar_bottom_15p">
                            	<div>
                            	<img src="/public/images/brijj.gif" /><br />

				<?php if($brijjURL == 0){?>
				<div id='brijjURLDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='brijjURLInput' id='brijjURLInput' class="w165 " default="Enter Your Brijj Profile" value="Enter Your Brijj Profile" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"  style="color:#ada6ad;font-size:12px;font-family:Arial;" <?php if($formDisabled) echo "disabled='true'";?>/> &nbsp;</td><?php if($vcardStatus==1){?><td><a href="javascript:void(0);" onClick="saveElement('brijjURL',165);">Save</a></td><?php } ?></tr></table></div>
				<div id='brijjURLSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='brijjURLSpan' class='intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('brijjURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } else { ?>
				<div id='brijjURLDiv' style="display:none;"></div>
				<div id='brijjURLSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='brijjURLSpan' class='intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"><?php echo (strlen($vCardFields[0]['brijjURL']) <= 22)?$vCardFields[0]['brijjURL']:(substr($vCardFields[0]['brijjURL'],0,22).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('brijjURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } ?>

                                <div style="display:none"><div class="errorMsg" id="brijjURL_error" style="*margin-left:3px;"></div></div>
                                <span class="Fnt11 drkGry">eg: http://www.brijj.com/ashish122</span>
                                </div>
                            </div>
			    </td><td>

                            <div class="float_L w230 mar_bottom_15p">
                            	<div>
                            	<img src="/public/images/linkedIn.gif" /><br />
				<?php if($linkedInURL == 0){?>
				<div id='linkedInURLDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='linkedInURLInput' id='linkedInURLInput' class="w165 " default="Enter Your Linked Profile" value="Enter Your Linked Profile" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"  style="color:#ada6ad;font-size:12px;font-family:Arial;" <?php if($formDisabled) echo "disabled='true'";?>/>&nbsp; </td><?php if($vcardStatus==1){?><td><a href="javascript:void(0);" onClick="saveElement('linkedInURL',165);">Save</a></td><?php } ?></tr></table></div>
				<div id='linkedInURLSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='linkedInURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('linkedInURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } else { ?>
				<div id='linkedInURLDiv' style="display:none;"></div>
				<div id='linkedInURLSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='linkedInURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"><?php echo (strlen($vCardFields[0]['linkedInURL']) <= 22)?$vCardFields[0]['linkedInURL']:(substr($vCardFields[0]['linkedInURL'],0,22).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('linkedInURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } ?>
                                <div style="display:none"><div class="errorMsg" id="linkedInURL_error" style="*margin-left:3px;"></div></div>
                                <span class="Fnt11 drkGry">eg: http://in.linkedin.com/in/mashish</span>
                                </div>
                            </div>
			    </td><td>

                            <div class="float_L w230 mar_bottom_15p">
                            	<div>
                            	<img src="/public/images/twitter.gif" /><br />
				
				<?php if($twitterURL == 0){?>
				<div id='twitterURLDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='twitterURLInput' id='twitterURLInput' class="w165 " default="Enter Your Twitter Profile" value="Enter Your Twitter Profile" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" <?php if($formDisabled) echo "disabled='true'";?>/>&nbsp; </td><?php if($vcardStatus==1){?><td><a href="javascript:void(0);" onClick="saveElement('twitterURL',165);">Save</a></td><?php } ?></tr></table></div>
				<div id='twitterURLSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='twitterURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('twitterURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } else { ?>
				<div id='twitterURLDiv' style="display:none;"></div>
				<div id='twitterURLSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='twitterURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"><?php echo (strlen($vCardFields[0]['twitterURL']) <= 22)?$vCardFields[0]['twitterURL']:(substr($vCardFields[0]['twitterURL'],0,22).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('twitterURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } ?>

                                <div style="display:none"><div class="errorMsg" id="twitterURL_error" style="*margin-left:3px;"></div></div>
                                <span class="Fnt11 drkGry">eg: http://twitter.com/ashishmehra28</span>
                               	</div>
                            </div>
			    </td></tr>
			    <tr><td>

                            <div class="float_L w230 mar_bottom_15p">
                            	<div>
                            	<img src="/public/images/youtube.gif" /><br />
				
				<?php if($youtubeURL == 0){?>
				<div id='youtubeURLDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><input type="text" name='youtubeURLInput' id='youtubeURLInput' class="w165 " default="Enter Your Youtube Channel" value="Enter Your Youtube Channel" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" style="color:#ada6ad;font-size:12px;font-family:Arial;" <?php if($formDisabled) echo "disabled='true'";?>/>&nbsp;</td><?php if($vcardStatus==1){?><td>&nbsp;<a href="javascript:void(0);" onClick="saveElement('youtubeURL',165);">Save</a></td><?php } ?></tr></table></div>
				<div id='youtubeURLSpanDiv' style="display:none;"><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='youtubeURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('youtubeURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } else { ?>
				<div id='youtubeURLDiv' style="display:none;"></div>
				<div id='youtubeURLSpanDiv'><table border='0' cellspacing='0' cellpadding='0'><tr><td><span id='youtubeURLSpan' class='w165 intBox' style="DISPLAY: inline-block; WIDTH: 165px; overflow: hidden"><?php echo (strlen($vCardFields[0]['youtubeURL']) <= 22)?$vCardFields[0]['youtubeURL']:(substr($vCardFields[0]['youtubeURL'],0,22).'...'); ?></span> &nbsp;&nbsp;</td><td><a href="javascript:void(0);" onClick="editText('youtubeURL','165',0,0)">Edit</a></td></tr></table></div>
				<?php } ?>
                                
				<div style="display:none"><div class="errorMsg" id="youtubeURL_error" style="*margin-left:3px;"></div></div>
                                <span class="Fnt11 drkGry">eg: http://www.youtube.com/user/pitstoper</span>
                               	</div>
                            </div>
			    </td></tr></table>
                            <div class="clear_B">&nbsp;</div>
                        </div>
                        <div class="lineSpace_20">&nbsp;</div>
                        <div class="line_1">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>
                        <div class="txt_align_c"><input type="button" class="btn_vCard" value="Preview Your VCard" onClick="showPreview('previewDiv');" <?php if($formDisabled) echo "disabled='true'";?>/>&nbsp;&nbsp;
			<?php if($vcardStatus==0){?>
			<input type="submit" class="btn_vCard" value="Save &amp; Continue" <?php if($formDisabled) echo "disabled='true'";?>/></div>
			<?php }else{ ?>
			<input type="button" class="btn_vCard" value="Continue" onClick="javascript: window.location.href='/messageBoard/MsgBoard/discussionHome';"/></div>
			<?php } ?>
                        <div class="lineSpace_10">&nbsp;</div>                        
                    </div>
			<div class="defaultAdd lineSpace_4" style="background:#799ed5;border-bottom:1px solid #fff">&nbsp;</div>
			<div class="defaultAdd lineSpace_4" style="background:#fea34a">&nbsp;</div>
			<input type="hidden" name="userLevelPost" id="userLevelPost" value="" />
			<input type="hidden" name="imageURLInput" id="imageURLInput" value="" />
			<input type="hidden" name="userNameHidden" id="userNameHidden" value="<?php if($userName==1)echo $vCardFields[0]['userName'];else echo "";?>" />
			<input type="hidden" name="designationHidden" id="designationHidden" value="<?php if($designation==1)echo $vCardFields[0]['designation'];else echo "";?>" />
			<input type="hidden" name="instituteNameHidden" id="instituteNameHidden" value="<?php if($instituteName==1)echo $vCardFields[0]['instituteName'];else echo "";?>" />
			<input type="hidden" name="highestQualificationHidden" id="highestQualificationHidden" value="<?php if($highestQualification==1)echo $vCardFields[0]['highestQualification'];else echo "";?>" />
			<input type="hidden" name="aboutMeHidden" id="aboutMeHidden" value="<?php if($aboutMe==1)echo $vCardFields[0]['aboutMe'];else echo "";?>" />
			<input type="hidden" name="blogURLHidden" id="blogURLHidden" value="<?php if($blogURL==1)echo $vCardFields[0]['blogURL'];else echo "";?>" />
			<input type="hidden" name="brijjURLHidden" id="brijjURLHidden" value="<?php if($brijjURL==1)echo $vCardFields[0]['brijjURL'];else echo "";?>" />
			<input type="hidden" name="linkedInURLHidden" id="linkedInURLHidden" value="<?php if($linkedInURL==1)echo $vCardFields[0]['linkedInURL'];else echo "";?>" />
			<input type="hidden" name="twitterURLHidden" id="twitterURLHidden" value="<?php if($twitterURL==1)echo $vCardFields[0]['twitterURL'];else echo "";?>" />
			<input type="hidden" name="youtubeURLHidden" id="youtubeURLHidden" value="<?php if($youtubeURL==1)echo $vCardFields[0]['youtubeURL'];else echo "";?>" />
			<input type="hidden" name="vcardStatus" id="vcardStatus" value="<?php echo $vcardStatus;?>" />
			<input type="hidden" name="userDisplayName" id="userDisplayName" value="<?php echo $vCardFields[0]['displayname'];?>" />
			<input type="hidden" name="previewDisplayName" id="previewDisplayName" value="<?php echo $previewDisplayName;?>" />
			<input type="hidden" name="userTotalAnswers" id="userTotalAnswers" value="<?php echo $vCardFields[0]['totalAnswers'];?>" />
			<input type="hidden" name="userBestAnswer" id="userBestAnswer" value="<?php echo $vCardFields[0]['bestAnswers'];?>" />
			<input type="hidden" name="totalPoints" id="totalPoints" value="<?php echo $totalPoints;?>" />
			<input type="hidden" name="likes" id="likes" value="<?php echo $likes;?>" />
			<input type="hidden" name="weeklyPoints" id="weeklyPoints" value="<?php echo $weeklyPoints;?>" />
			<input type="hidden" name="totalParPoints" id="totalParPoints" value="<?php echo $totalParPoints;?>" />
			<input type="hidden" name="discussionPosts" id="discussionPosts" value="<?php echo $discussionPosts;?>" />
			<input type="hidden" name="announcementPosts" id="announcementPosts" value="<?php echo $announcementPosts;?>" />
			<input type="hidden" name="weeklyParPoints" id="weeklyParPoints" value="<?php echo $weeklyParPoints;?>" />
			<input type="hidden" name="followUserCount" id="followUserCount" value="<?php echo $followUserCount;?>" />
			
                    <div class="lineSpace_5">&nbsp;</div>
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
	</form></div>
     <?php
    }
    ?>
    </div>
</div>
<div id='successDiv' style="display:none;width:100%;">
  <div class="lineSpace_20">&nbsp;</div>
  <div class="txt_align_c"><span align='center' style='font-size:14px;font-family:Arial;'>Your VCard has been saved.</span></div>
  <div class="lineSpace_20">&nbsp;</div>
  <div class="txt_align_c"><input type="button" align='middle' class="homeShik_SubmitBtn" value="OK" onClick="window.location.href='/messageBoard/MsgBoard/discussionHome';" />&nbsp;</div>
  <div class="lineSpace_20">&nbsp;</div>
</div>
	<div class="lineSpace_10">&nbsp;</div>
            <?php
	    $bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	    $this->load->view('common/footer',$bannerProperties1);
            ?>
<script>
try{
    addOnFocusToopTip($('VCardForm'));
} catch (ex) {
    // throw ex;
} 

window.onload = function () {
  <?php 
    if(!($imageURL == '' || strrpos($imageURL, 'dummyImg.gif') || strrpos($imageURL, 'photoNotAvailable.gif'))){
      ?>
	if($('userImage'))
	$('userImage').src = displayImage;
      <?php
    }
  ?>
}
</script>
