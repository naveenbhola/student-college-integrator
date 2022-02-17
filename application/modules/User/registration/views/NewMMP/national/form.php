<?php
	$this->load->view('registration/common/jsInitialization');
?>
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>

<div id="formScroll" class="scrollbar1">

	<div class="scrollbar">
		<div class="track">
			<div class="thumb">
				<div class="end"></div>
			</div>
		</div>
	</div>
	<div class=" viewport" style="overflow: hidden; height: 450px;">
		<div class="overview" id="newOverviewScroll" style="height: 450px;">

<div id="registration-box2">
	<div class="form-main-container">
                <div class="form-sub-main-container">
			<div class="form-sub-main-box">
				<form action="<?php if(!$formData['userId']) { echo '/registration/Registration/register'; } else { echo '/registration/Registration/updateUser'; } ?>" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
					<input type="hidden" id="mmp_registration_form" value="mmp" />
					<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
					<ul>
					<?php
						//$this->load->view('registration/fields/NewMMP/userPersonalDetails');
						
						if($registrationHelper->fieldExists('fieldOfInterest')) {
							//$this->load->view('registration/fields/NewMMP/fieldOfInterest');
						}
						
						if($registrationHelper->fieldExists('desiredCourse')) {
						//	$this->load->view('registration/fields/NewMMP/desiredCourse');
						}
					?>
					</ul>
						
					<ul id="registrationFormMiddle_<?php echo $regFormId; ?>">
					<?php
						$this->load->view('registration/fields/NewMMP/variable/default');   
					?>
					</ul>
					
					<ul>
						<?php
						if(!empty($mmpData['submitButtonText'])) {
							$buttonText = $mmpData['submitButtonText']; 
						} else {
							if(!$formData['userId']) { 
								$buttonText = 'Sign Up'; 
							} else { 
								$buttonText = 'Submit'; 
							}
						}
						$agreeData = array('buttonText'=>$buttonText); 
						?>
						<li style="margin-top:5px; text-align: center;" onclick="updateTinyScroll()">
							<input type="submit" style="" id="registrationSubmit_<?php echo $regFormId; ?>" value="<?=$buttonText?>" class="submit" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm();" />
						</li>
						
						<li>
							<p class="policy-line"><?php $this->load->view('registration/common/agree', $agreeData); ?></p>
						</li>
						
						<div class="clearFix"></div>
					</ul>
					
					<input type='hidden' id='display_on_page' name='display_on_page' value='newmmp' />
					<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
					<input type='hidden' id='mmpFormId' name='mmpFormId' value='<?php echo $mmpFormId; ?>' />
					<input type='hidden' id='isStudyAbroad' name='isStudyAbroad' value='<?php echo $mmpData['page_type'] == 'abroadpage' ? 'yes' : 'no'; ?>' />
					<input type='hidden' id='isTestPrep' name='isTestPrep' value='<?php echo $mmpData['page_type'] == 'testpreppage' ? 'yes' : 'no'; ?>' />
					<input type='hidden' id='userId' name='userId' value='<?php echo $formData['userId']; ?>' />
					<input type='hidden' id='registrationSource' name='registrationSource' value='MARKETING_FORM' />
					
					<?php
						$currentPageURL = getCurrentPageURL();
						$currentPageURL = str_replace('index/pageID','index/pageIDNew',$currentPageURL);
						
						$referrerText = "";
						if($displayOnPage == 'newmmpcourse') {
							$referrerText = "#CourseSEOMarketing";
						} elseif($displayOnPage == 'newmmpinstitute') {
							$referrerText = "#InstituteSEOMarketing";
						} elseif($displayOnPage == 'newmmparticle') {
							$referrerText = "#ArticleSEOMarketing";
						} elseif($displayOnPage == 'newmmpranking') {
							$referrerText = "#RankingSEOMarketing";
						} elseif($displayOnPage == 'newmmpcategory') {
							$referrerText = "#CategorySEOMarketing";
						} elseif($displayOnPage == 'newmmpexam') {
							$referrerText = "#ExamSEOMarketing";
						}
					?>
					<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?= $trackingPageKeyId; ?>' />
					<input type='hidden' id='referrer' name='referrer' value='<?php echo strip_tags($_SERVER['HTTP_REFERER']).$referrerText;; ?>' />
					<input type='hidden' id='pageReferrer' name='pageReferer' value='<?php echo strip_tags($_SERVER['HTTP_REFERER']); ?>' />
					<input type='hidden' id='fieldsView' name='fieldsView' value='default' />
					<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
					<input type="hidden" id="inlineForm_<?php echo $regFormId; ?>" name="inlineForm" value=false />
					
				</form>
			</div>
		</div>
	</div>
</div>
</div></div></div>
<?php
	$this->load->view('registration/common/OTP/userOtpVerification');
?>


<div style="clear:both;"></div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script> $j = jQuery.noConflict();</script>

<script>
var isRequiredTinyUpd = 0;
var form_user_id = '<?php echo $formData['userId'];?>';
	$j(document).ready(function($j) {
		setTimeout(function(){
			$j("#formScroll").tinyscrollbar();
		},800); 	
		
		if(typeof(single_default_course) !='undefined' && single_default_course == 'YES') { 
			isRequiredTinyUpd = 1;
			$j('#desiredCourse_'+'<?php echo $regFormId; ?>').trigger('change');
			isRequiredTinyUpd = 0;
		}
	});
</script>

<script type="text/javascript">

	function updateTinyScrollBar(isRequired){
		if(isRequired != 1){
			setTimeout(function(){
				$j('#formScroll').tinyscrollbar_update('relative');
			},600);
		}
	}

	function updateTinyScroll(){
		if($j('#desiredCourse_<?php echo $regFormId; ?>').val() != '' && $j('#specialization_block_<?php echo $regFormId; ?>').is(":visible")){
			setTimeout(function(){
				$j('#formScroll').tinyscrollbar_update('relative');
			},500);
		}
	}

	$j(document).click(function(){
		setTimeout(function(){
				$j('#formScroll').tinyscrollbar_update('relative');
			},950);
	});
	$j('#mmpOverlay').css({"overflow-y":"hidden","max-height": "650px"});

	setTimeout(function(){
		var a = document.createElement("script");
		var b = document.getElementsByTagName("script")[0];
		a.src = document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/0281.js?"+Math.floor(new Date().getTime()/3600000);
		a.async = true;
		a.type = "text/javascript";
		b.parentNode.insertBefore(a,b)
	}, 1);
</script>

<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>

<?php
	if($load_ga) {
		
		$data_array = array('beaconTrackData'=>array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array()));
		$this->load->view('common/ga',$data_array);
		
	} 
	
	$beaconTrackData = array('pageIdentifier'=>'MMP','pageEntityId'=>$mmpFormId,'extraData'=>array());
	loadBeaconTracker($beaconTrackData);	
		
	
?>

<style>
	#modalDialog_content_iframe {display:none !important;}
</style>

<?php 
if(empty($data['loadmmponpopup'])){ ?>
	<style type="text/css">
		.layer-outer { position: fixed; }
		.layer-title .close { display: none; }
	</style>
<?php } ?>
