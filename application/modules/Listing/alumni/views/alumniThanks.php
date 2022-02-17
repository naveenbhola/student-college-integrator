<?php 
		$headerComponents = array(
								'css'	=>	array(
											'header',
											'mainStyle',
											'raised_all',
											'footer',
											'common_new'
										),
								'js'	=>	array(
											'header',
											'common',
											'user',
											'inviteFriends',
										),
								'title'	=>	'Shiksha :: Alumni Feedback',
								'metaDescription' => 'Alumni Feedback',
								'metaKeywords'	=>'ALumni Feedback',
							);
                    $this->load->view('common/homepage_simple', $headerComponents); 
?>
<div class="wrapperFxd">
<?php 
$visiblity = 'hidden';
$statusMsg = $feedbackStatus;
if($feedbackStatus === 'Failure') {
    $statusMsg = 'We have already received feedback from your email Id '. $email .'. Thank you.';
}
if($feedbackStatus === 'Success') {
    $visiblity = '';
    $statusMsg = 'Thank you for your valuable feedback.';
}

?>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
	<?php if($statusMsg !== ''){ ?>
    <div style="background:#fffdd6;border:1px solid #facb9d;line-height:30px;display:block;opacity:1;filter:alpha(opacity=10); -moz-opacity:1" id="statusInfo" >
        <a style="margin:0 10px 0 0;float:right" onclick="this.parentNode.style.display= 'none';" href="#">Hide</a>
        &nbsp; &nbsp;<b class="fontSize_16p"><?php echo $statusMsg; ?></b><br/>
        <div style="clear:right"></div>
    </div>
	<?php
}
?>
    <div>
      <div class="lineSpace_5">&nbsp;</div>		
    </div>
    <div class="raised_skyWithBG"> 
       <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_skyWithBG">
            	<div style="margin:0 10px">
                    <div class="lineSpace_5">&nbsp;</div>               
                    <div class="row">
                      <div style="font-size:16px;padding:5px 0" class="bld">Please Invite other alumni of <span class="OrgangeFont"><?php echo $instituteName; ?></span> to share their feedback</div>                       
                    </div>                    
                    <!--Start_Infrasture_Teaching_facilities-->
                    <div style="line-height:16px">&nbsp;</div>
                    <div>
                        <div class="row float_L">
                            <div class="row" id="globalContainer">
                                <div id="wait" style="display:none" class="mar_full_10p">
                                    <div class="lineSpace_20">&nbsp;</div>
                                    <div class="OrgangeFont fontSize_16p bld" align="center">Uploading Webmail Contacts...... </div>
                                    <div class="lineSpace_5">&nbsp;</div>
                                    <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">Please wait while your upload process,</div>
                                    <div class="lineSpace_5">&nbsp;</div>
                                    <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">during that time this page will reload automatically.</div>
                                    <div class="lineSpace_10">&nbsp;</div>
                                    <div align="center"><img src="/public/images/ajax-loader.gif"  /></div>
                                    <div class="lineSpace_5">&nbsp;</div>
                                    <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">loading....</div>
                                    <div class="lineSpace_35">&nbsp;</div>
                               </div>
                               <div id="box"><?php $this->load->view('inviteFriends/contentGrabber'); ?></div>
                           </div>
                       </div>
                       <div style="line-height:12px;clear:both">&nbsp;</div>
                    </div>
                       <div style="line-height:22px;clear:both">&nbsp;</div>
                        <div class="linseSpace_10">&nbsp;</div>
                    <div class="row">
                        <div class="row1" style="width:125px"><b>Subject:</b></div>
                        <div class="row2" style="margin-left:135px">
                            <div>
                                <span class="OrgangeFont bld fontSize_16p"><?php echo $email; ?></span> invites you to share your experience at <span class="OrgangeFont bld"><?php echo $instituteName; ?></span> on Shiksha.com
                            </div>
                            <div style="display:none"><div class="errorMsg"></div></div>
                        </div>
                   </div>
                   <div style="line-height:3px;clear:both">&nbsp;</div>
                   <div class="row">
                        <div class="row1" style="width:125px"><b>Message:</b></div>
                        <div class="row2" style="margin-left:135px">
                            <div>Hi</div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <p>Your classmate <?php echo $email; ?> from <?php echo $instituteName; ?> has shared his experience at the institute on Shiksha.com and invited you to do the same to help prospective students make an informed career decision. Please click on the link below to provide your valuable feedback.</p>
							<p>Shiksha.com is a Naukri.com venture, which aims to help students seeking further education by providing them tools to make the right choice amongst many options.</p><br />
							<p>Regards<br />Shiksha.com</p>
                        </div>
                   </div>
                   <div style="line-height:3px;clear:both">&nbsp;</div> 
           </div> 
           <div style="line-height:1px;clear:both">&nbsp;</div>
        </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>
    <div style="lineSpace_10">&nbsp;</div>
    <div class="fontSize_13p">Go To <a href="/">Shiksha.com</a></div>
    <input type="hidden" id="fromEmail" value="<?php echo $email; ?>"/>
    <input type="hidden" id="templateId" value="<?php echo $templateId; ?>"/>
    <input type="hidden" id="mailerId" value="<?php echo $mailerId; ?>"/>
    <input type="hidden" id="instituteId" value="<?php echo $instituteId; ?>"/>
    <input type="hidden" id="instituteName" value="<?php echo $instituteName; ?>"/>
</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<?php
$this->load->view('common/footer');
?>
<script>
function addAlumVarsToForm(formObj) {
    formObj.appendChild(createElementForAlumni('instituteId'));
    formObj.appendChild(createElementForAlumni('instituteName'));
    formObj.appendChild(createElementForAlumni('mailerId'));
    formObj.appendChild(createElementForAlumni('templateId'));
    formObj.appendChild(createElementForAlumni('fromEmail'));
}

function createElementForAlumni(elementName) {
    var newElement= document.createElement('input');
    newElement.type = 'hidden';
    newElement.name = elementName;
    newElement.value = document.getElementById(elementName).value;
    return newElement;
}
document.getElementById('inviteFriendsForm').action = '/alumni/AlumniSpeakFeedBack/alumniFeedbackCompletion';
addAlumVarsToForm(document.getElementById('inviteFriendsForm'));
document.getElementById('inviteFriendsForm').onsubmit = function () {  
            new Ajax.Request (this.action,{ method:'post', evalScripts:true, parameters:Form.serialize(this), onSuccess:function (xmlHttp) {
                document.body.innerHTML = xmlHttp.responseText;
                return false;
            }});
            return false;
};
//dissolveElement(document.getElementById('statusInfo'));
</script>
