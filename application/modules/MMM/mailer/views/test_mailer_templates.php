<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog','user'),
	'jsFooter'         => array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
</SCRIPT>
</head>
<body>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
	<!-- <form id="formForTestmail_Template" action="/mailer/Mailer/getClientIdAndSubscriptionId" method="POST"> -->
	<form id="formForTestmail_Template" action="/mailer/Mailer/scheduleMailer" method="POST">
	<input type="hidden" name="edit_form_mode" value="<?php echo $edit_form_mode; ?>" />
        <input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
	<input type="hidden" name="templateType"  value="<?php echo $templateType; ?>">
	<input type="hidden" name="sums_data" value="<?php echo $sumsData; ?>" />
	<input type="hidden" name="numUsers" value="<?php echo $numUsers; ?>" />
	<input type="hidden" name="criteria" value="<?php echo $criteria; ?>" />
	<input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
	<input type="hidden" id="download_check" name="download_check" value="0" />
		
		<div class="OrgangeFont fontSize_18p bld"><strong>Test <?php  echo $templateType;?></strong></div>
		
		<div id='Testbutton_mail' style="color:red;"></div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="txt_align_r float_L fontSize_13p" style="width:220px; font-size: 13px; padding-top:3px; color:#333;"><?php  if($templateType == "sms"){echo "Phone No: &nbsp;";}else{echo "Email id: &nbsp;";}?></div>
		<div class="float_L"  >
<?php if($templateType == "sms"){
?>
<input type="text" caption="mobile number" required="1" tip="phone_id" validate="validateInteger" size="45" maxlength="14" minlength="6" id="userFeedbackEmail" name="userFeedbackEmail" class="normaltxt_11p_blk_arial fontSize_12p" style="border: 1px solid #ccc; padding:3px; width:300px; font-size: 13px;" />

			<div class="row errorPlace" style="display: inline;">
			<div id="userFeedbackEmail_error" class="errorMsg" style="margin-left: 40px;"></div>
			</div>

 <?php   }else {?>
            <input type="text" class="normaltxt_11p_blk_arial fontSize_12p" caption="email" minlength="3" maxlength="100" validate="validateEmail" required="true" name="userFeedbackEmail" size="45" id="userFeedbackEmail" style="border: 1px solid #ccc; padding:3px; width:300px; height: 18px; font-size: 13px;" />
			<div class="row errorPlace" style="display: inline;">
			<div id="userFeedbackEmail_error" class="errorMsg" style="margin-left: 0px;"></div>
			</div>
<?php }?>
		</div>
        <div class="clear_L"></div>

		<div class="lineSpace_10">&nbsp;</div>
        <div class="r1_1 bld" style="width:210px;">&nbsp;</div>
		<div class="r2_2" id='send_tb'>
			<input type="button" value="Send Test Email" onclick=" Ajax_send_Test_Mailer('<?php echo $temp_id;?>','userFeedbackEmail', 'shiksha');" />
		
			<!--button class="btn-submit19" type="button" onclick=" Ajax_send_Test_Mailer('< ?php echo $temp_id;?>','userFeedbackEmail');" value="" style="width:100px">
			<div  class="btn-submit19">
				<p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:10px" class="btn-submit20">Send</p>
			</div>
			</button-->
		</div>
		<div class="r2_2" id='resend_tb' style="width:65%;">
			<div style="width:100%;padding-bottom:10px">
				<input type="button" value="Re-send Test Email" onclick=" Ajax_send_Test_Mailer('<?php echo $temp_id;?>','userFeedbackEmail', 'shiksha');" />
			</div>

			<div style="width:100%;padding-bottom:10px;padding-left:70px">
				<label>OR</label>
			</div>

			<div style="width:100%;padding-bottom:10px">
				<div style="float:left;">
					<input type="button" id="sendMailByAWSLink" disabled="disabled" value="Send Mail By AWS" onclick=" Ajax_send_Test_Mailer('<?php echo $temp_id;?>','userFeedbackEmail', 'amazon');" />
				</div>
				<div style="float:left;font-size:12px;padding:5px 0 0 5px;width:100%" id="AWSTimer">
					<div>
						<label>(Above Button will enable in </label>
						<span style="color:red"><label id="minute">10</label>:<label id="second">00</label> minutes</span> )
					</div>
				</div>
			</div>
			
			<!--button class="btn-submit19" type="button" onclick=" Ajax_send_Test_Mailer('< ?php echo $temp_id;?>','userFeedbackEmail');" value="" style="width:100px">
			<div  class="btn-submit19">
				<p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:10px" class="btn-submit20">Re send</p>
			</div>
			</button-->
		</div>
		
		
		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		
		<div class="clearFix"></div>
				
				
		<div class="OrgangeFont fontSize_18p bld"><strong>Spam Score</strong></div><br />		
				
				
		<div class="txt_align_r float_L fontSize_13p" style="width:220px; font-size: 13px; padding-top:3px; color:#333;">Spam Score: &nbsp;</div>
		<div class="float_L"  >
            <input type="text" class="normaltxt_11p_blk_arial fontSize_12p" caption="spam score"  name="spamScore" size="45" id="spamScoreId" style="border: 1px solid #ccc; padding:3px; width:300px; height: 18px; font-size: 13px;" />
			<div class="row errorPlace" style="display: inline;">
			<div id="spamScoreId_error" class="errorMsg" style="margin-left: 0px;"></div>
			</div>
		</div>
        <div class="clear_L"></div>		
				
		<div class="lineSpace_10">&nbsp;</div>
        <div class="r1_1 bld" style="width:210px;">&nbsp;</div>
		<div class="r2_2" id='send_tb'>
			<input type="button" value="Save Spam Score" onclick=" Ajax_Spam_Score('<?php echo $temp_id;?>','spamScoreId');" />
			<!--button class="btn-submit19" type="button" onclick=" Ajax_send_Test_Mailer('< ?php echo $temp_id;?>','userFeedbackEmail');" value="" style="width:100px">
			<div  class="btn-submit19">
				<p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:10px" class="btn-submit20">Save</p>
			</div>
			</button-->
		</div>		
				
				
		<div class="lineSpace_10">&nbsp;</div>
        <div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_20">&nbsp;</div>
				
				<div class="clearFix"></div>		
				
				<div style="background:#f0f0f0; text-align: center; padding:5px 0; margin-top:10px;">
					<button class="btn-submit19" type="button" onclick="history.go(-1);return false;" value="" style="width:100px">
						<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Back</p></div>
					</button>
	
					<button class="btn-submit19" type="button" onclick="return validate_test_mail_tpl();" value="" style="width:100px">
						<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
					</button>
				</div>
				
				
            	<!--div class="r1_1 bld">&nbsp;</div>
            	<div class="r2_2">
                <button class="btn-submit19" type="button" onclick="history.go(-1);return false;" value="" style="width:100px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Back</p></div>
				</button>

                <button class="btn-submit19" type="button" onclick="return validate_test_mail_tpl();" value="" style="width:100px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
				</button>
            	</div-->
        </form>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	<script>
	$('resend_tb').style.display = 'none';
	document.getElementById('Testbutton_mail').innerHTML = '';
   	addOnBlurValidate(document.getElementById('formForTestmail_Template'));
	$('sendMailByAWSLink').setAttribute('disabled','disabled');

   	var minutes = 10;var seconds = 0;var AWSTimer= '';
   	function startAWSTimer() {   		
   		if(seconds == 0) {
   			$('second').innerHTML = '59';
   			minutes = minutes-1
   			$('minute').innerHTML = minutes;
   			seconds = 59;
   		} else {
   			seconds = seconds-1
   			$('second').innerHTML = seconds;
   		}
   		if(minutes == 0 && seconds == 0) {
   			clearInterval(AWSTimer);
   			AWSTimer = '';
   			$('sendMailByAWSLink').removeAttribute('disabled');
   			$('AWSTimer').style.display = 'none';
   			minutes = 10;seconds = 0;
   		}
   	} 
   	</script>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
