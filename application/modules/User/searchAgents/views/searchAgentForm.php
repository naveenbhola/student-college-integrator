<form method="post" autocomplete="off"  onSubmit="if(!validateSearchAgentFrom(this)) { document.getElementById('searchagent_name').disabled=false; return false; } new Ajax.Request('<?php echo $Search_Agent_Form_Url; ?>',{onSuccess:function(request){javascript:ajaxsubmitSearchAgent(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id ="search_agent_form" name = "search_agent_form" novalidate="novalidate">
<input type = "hidden" name = "Search_Agent_Update_flag" id = "Search_Agent_Update_flag" value = "<?php echo $Search_Agent_Update_flag; ?>"/>

<input type="hidden" name="courseName" id="courseName" value=""/>

<?php if($Search_Agent_Update_flag == ""){?>
<input type = "hidden" name = "inputData_Search_Agent" id = "inputData_Search_Agent" value = ""/>
<input type = "hidden" name = "inputHTMLData_Search_Agent" id = "inputHTMLData_Search_Agent" value = ""/>
<input type = "hidden" name = "displayData_Search_Agent" id = "displayData_Search_Agent" value = ""/>
<input type = "hidden" name = "Search_Agent_Update_Id" id = "Search_Agent_Update_Id" value = ""/>
<?php } ?>
<input type="hidden" name="viewCredit" id="viewCredit" value="<?php echo $credit_consumption['0']['deductcredit']?>"/>
<input type="hidden" name="emailCredit" id="emailCredit" value="<?php echo $credit_consumption['1']['deductcredit']?>"/>
<input type="hidden" name="smsCredit" id="smsCredit" value="<?php echo $credit_consumption['2']['deductcredit']?>"/>
<input type="hidden" name="premiumViewCredit" id="premiumViewCredit" value="<?php echo $credit_consumption['4']['deductcredit']?>"/>
<?php
//sdump($res);
?>
<div class="mlr10">
	<div class="bBox">
	<div class="Fnt18 fcOrg bbm mb10">Save Search and Setup a Genie</div>
	<?php foreach($res as $tempArray){
	$name=$tempArray['name'];
	$pref_lead_type=$tempArray['auto_download']['detail']['pref_lead_type'];
	$is_active=$tempArray['auto_download']['is_active'];
	$email1=$tempArray['contact_details']['email'][0];
	$email2=$tempArray['contact_details']['email'][1];
	$email3=$tempArray['contact_details']['email'][2];
	$email_freq=$tempArray['auto_download']['detail']['email_freq'];
	$sms_freq=$tempArray['auto_download']['detail']['sms_freq'];
	$mobile1=$tempArray['contact_details']['mobile'][0];
	$mobile2=$tempArray['contact_details']['mobile'][1];
	$leads_daily_limit=$tempArray['auto_download']['detail']['leads_daily_limit'];
	$price_per_lead=$tempArray['auto_download']['detail']['price_per_lead'];
	$from_name=$tempArray['auto_responder']['email']['from_name'];
	$from_emailid=$tempArray['auto_responder']['email']['from_emailid'];
	$auto_responder_email_is_active=$tempArray['auto_responder']['email']['is_active'];
	$auto_responder_email_subject=$tempArray['auto_responder']['email']['subject'];
	$auto_responder_email_msg=$tempArray['auto_responder']['email']['msg'];
	$auto_responder_email_daily_limit=$tempArray['auto_responder']['email']['daily_limit'];
	$auto_responder_sms_is_active=$tempArray['auto_responder']['sms']['is_active'];
	$auto_responder_sms_msg=$tempArray['auto_responder']['sms']['msg'];	
	$auto_responder_sms_daily_limit=$tempArray['auto_responder']['sms']['daily_limit'];
	$group_id=$tempArray['credit_group'];

	}

		$hide_params = 'N';
		foreach ($coursesList as $course_name=> $details) {
			if($details['actual_course_id'] == $actual_course_id) {
				$hide_params = 'Y';
				break;
				
			}					
		}
	?>
	<div class="mb10">
		<?php if($hide_params == 'N') { ?>
		<div style="float:left; width:160px; padding-top:4px;">
		<?php }else { ?>
		<div style="float:left; width:218px; padding-top:4px;">
		<?php } ?>
			<strong>Specify Genie Name:</strong>
		</div>
		<div style="float:left; width:300px;">
        <input type="text"  <?php if($Search_Agent_Update_flag != ""){?>disabled="true" <?php }?> caption="Genie Name" id="searchagent_name" name="searchagent_name" profanity="true" value="<?php echo $name; ?>" <?php if($Search_Agent_Update_flag == ""){?>validate="validateStr" maxlength="100" <?php }?> minlength="4" required="true"/>
		<div class="errorPlace"><div id="searchagent_name_error" class="errorMsg">&nbsp;</div></div>
		</div>
		<div class="clearFix"></div>	
	</div>	
	
	<?php	
	if(!$Search_Agent_Update_flag) { ?>
		<div class="mb10">
			
		<?php if($hide_params == 'N') { ?>
				<div style="float:left; width:160px;">
				<strong>Lead Delivery Method:</strong>
				</div>
		<?php }else { ?>
				<div style="float:left; width:217px;">
				<strong>Matched Response Delivery Method:</strong>
				</div>
		<?php } ?>
			
			<div style="float:left; width:300px;">
				<?php if($hide_params == 'N') { ?>
				<div><div style="float:left"><input type="radio" onclick="$('emailsms_settings').style.display = 'none'; validateLeadDeliveryMethod();" name="leadDeliveryMethod" value="porting" id="leadDeliveryMethodPorting" /></div><div style="float:left; margin-left: 3px; padding-top:2px;">Porting</div><div class="clearFix"></div></div>
				<div style="padding-top:5px;"><div style="float:left"><input type="radio" onclick="$('emailsms_settings').style.display = 'block'; validateLeadDeliveryMethod();" name="leadDeliveryMethod" value="normal" id="leadDeliveryMethodNormal" /></div><div style="float:left; margin-left: 3px; padding-top:2px;">Email/SMS Delivery</div><div class="clearFix"></div></div>
				<?php }else { ?>
				<div><div style="float:left"><input type="radio" onclick="$('emailsms_settings').style.display = 'none'; validateLeadDeliveryMethod();" name="leadDeliveryMethod" value="porting" id="leadDeliveryMethodPorting" /></div><div style="float:left; margin-left: 3px; padding-top:2px;">Porting</div><div class="clearFix"></div></div>
				<div style="padding-top:5px;"><div style="float:left"><input type="radio" onclick="$('emailsms_settings').style.display = 'block'; validateLeadDeliveryMethod();" name="leadDeliveryMethod" value="normal" id="leadDeliveryMethodNormal" /></div><div style="float:left; margin-left: 3px; padding-top:2px;">Email/SMS Delivery</div><div class="clearFix"></div></div>
				<?php } ?>
				<div class="errorPlace" style="margin-top:5px;"><div id="searchagent_deliveryMethod_error" class="errorMsg">&nbsp;</div></div>
				<?php
				
				if($hide_params == 'N') { ?>	
				<input type="hidden" name="group_id" value=""/>
				<input type="hidden" name="type" value="lead" />
				<?php } else { ?>
				<input type="hidden" name="type" value="response" />
				<input type="hidden" name="group_id" value="<?php echo $group_id;?>"/>
				<?php } ?>
			</div>
			<div class="clearFix"></div>	
		</div>
	<?php }  else if((!empty($res)) && (!empty($group_id))) { ?>
				<input type="hidden" name="type" value="response" />
				<input type="hidden" name="group_id" value="<?php echo $group_id;?>"/>
	<?php }  else { ?>
				<input type="hidden" name="type" value="lead" />
				<input type="hidden" name="group_id" value="0"/>
	<?php } ?>

	<div id="emailsms_settings" style="display : block">
	       <div><input id="checkbox_flag_auto_download" name="checkbox_flag_auto_download" <?php if($is_active=='live'){?>checked<?php } ?> type="checkbox" onClick="setUnsetAotoDwnld();auto_download_limit_text();"/> <strong>Set Auto Download Preference</strong></div>
	       
	       <?php if($hide_params == 'N'){ ?>
	       <div class="htxt Fnt11 mb20" style="padding-left:23px">Specify the details below to automatically receive the matching lead with its contact details through e-mail and SMS (optional). Your credits would get automatically deducted for every lead sent to you. You will need to set a daily limit on number of leads you want to receive and must have sufficient credits.</div>
		<?php }else{ ?>
		<div class="htxt Fnt11 mb20" style="padding-left:23px">Specify the details below to automatically receive the matching response with its contact details through e-mail and SMS (optional). Your credits would get automatically deducted for every matched response sent to you. You will need to set a daily limit on number of matched response you want to receive and must have sufficient credits.</div>
		<?php } ?>
	<div id="setUnsetAutoDownload" style="display:<?php if($is_active=='live'){ echo 'block'; } else { echo 'none'; } ?>;">
	<div class="wdh100">
	<div class="mb10">
                        
		<?php if($hide_params == 'N') { ?>
			<div class="float_L w250"><div class="txt_align_r">Select Lead Type:</div></div>
			<div style="margin-left:270px">
				<div>
					<input id="lead_type_shared" checked name="lead_type" value="shared" type="radio" onClick="check(this);auto_download_limit_text();"/> Shared<br />
					<!--<input id="lead_type_premium" < ?php if($pref_lead_type=='premium'){?>checked< ?php } ?> name="lead_type" value="premium" type="radio" onClick="check(this);auto_download_limit_text();"/>Premium -->
				</div>
				<div class="Fnt11 htxt">(to know the exact sharing criteria of your category, please get in touch with your Shiksha sales representetive)</div>
				<div class="errorPlace"><div id="lead_type_error" class="errorMsg">&nbsp;</div></div>
			</div>
		<?php } else { ?>
			<div class="float_L w250"><div class="txt_align_r">Matched Response Type:</div></div>
			<div style="margin-left:270px">
				 <div>Shared</div>
				<div class="Fnt11 htxt">(to know the exact sharing criteria of your category, please get in touch with your Shiksha sales representetive)</div>
				<input type="hidden" id="lead_type_shared" name= "lead_type" value="shared" />
                        </div>
		<?php } ?>
				
                <div class="clear_B">&nbsp;</div>
	</div>
	<div class="mb10">
		<?php if($hide_params == 'N') { ?>
	<div class="float_L w250"><div class="txt_align_r">Select e-mail for lead delivery:<br />(Max 3)</div></div>
	<?php } else { ?>
	<div class="float_L w250"><div class="txt_align_r">Select e-mail for delivery:<br />(Max 3)</div></div>
	<?php } ?>
        <div style="margin-left:270px">
		<div>
							<input caption="1st email" tip="search_agent_email" type="text" validate="validateEmail" minlength="7" maxlength="100" id="auto_download_email_1" value="<?php echo $email1; ?>" name="auto_download_email[]" />
							<input caption="2nd email" tip="search_agent_email" type="text" validate="validateEmail" minlength="7" maxlength="100" id="auto_download_email_2" value="<?php echo $email2; ?>" name="auto_download_email[]" />
							<input caption="3rd email" tip="search_agent_email" type="text" validate="validateEmail" minlength="7" maxlength="100" id="auto_download_email_3" value="<?php echo $email3; ?>" name="auto_download_email[]" />
						</div>
				<div class="errorPlace"><div id="auto_download_email_error" class="errorMsg">&nbsp;</div></div>
				<div class="errorPlace"><div id="auto_download_email_1_error" class="errorMsg">&nbsp;</div></div>
				<div class="errorPlace"><div id="auto_download_email_2_error" class="errorMsg">&nbsp;</div></div>
				<div class="errorPlace"><div id="auto_download_email_3_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="mb10">
                    	<div class="float_L w250"><div class="txt_align_r">Select Email alert frequency:</div></div>
                        <div style="margin-left:270px">
				<?php if($hide_params == 'N') { ?>
                        			<div><input name="send_lead_option" value="asap" checked type="radio" /> Send alert as soon as lead arrives <br /><input name="send_lead_option" value="everyhour" <?php if($email_freq=='everyhour'){?>checked<?php } ?> type="radio" /> Send alert every <b>1 hour</b></div>
						<div class="Fnt11 htxt">(you will also receive a compiled MS Excel file of all leads sent to you at end of the day for free)</div>
				<?php } else { ?>
						<div><input name="send_lead_option" value="asap" checked type="radio" /> Send alert as soon as Matched Response arrives <br /><input name="send_lead_option" value="everyhour" <?php if($email_freq=='everyhour'){?>checked<?php } ?> type="radio" /> Send alert every <b>1 hour</b></div>
						<div class="Fnt11 htxt">(you will also receive a compiled MS Excel file of all matched responses sent to you at end of the day for free)</div>
				<?php } ?>
			    
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
		    <div class="mb10">
                        <div class="float_L w250"><div class="txt_align_r">Select SMS alert frequency:</div></div>
                        <div style="margin-left:270px">
				<?php if($hide_params == 'N') { ?>
                                                <div><input name="send_lead_option_sms" value="asap" checked type="radio" /> Send alert as soon as lead arrives <br /><input name="send_lead_option_sms" value="everyhour" <?php if($sms_freq=='everyhour'){?>checked<?php } ?> type="radio" /> Send alert every <b>1 hour</b></div>
                            <div class="Fnt11 htxt">(you will also receive a compiled MS Excel file of all leads sent to you at end of the day for free)</div>
				<?php } else { ?>
					<div><input name="send_lead_option_sms" value="asap" checked type="radio" /> Send alert as soon as Matched Response arrives <br /><input name="send_lead_option_sms" value="everyhour" <?php if($sms_freq=='everyhour'){?>checked<?php } ?> type="radio" /> Send alert every <b>1 hour</b></div>
                            <div class="Fnt11 htxt">(you will also receive a compiled MS Excel file of all matched responses sent to you at end of the day for free)</div>
				<?php } ?>
		        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="mb10">
                    	<div class="float_L w250">
				<?php if($hide_params == 'N') { ?>
                        	<div class="txt_align_r">Specify mobile number for lead alert:<br />(free)</div>
				<?php } else { ?>
				<div class="txt_align_r">Specify mobile number for Matched Response alert:<br />(free)</div>
				<?php } ?>
                        </div>
                        <div style="margin-left:270px">
                        <div class="Fnt11 htxt">Please ensure that the numbers you have entered are not in NDNC list.</div>
                        			<div><input caption="mobile number" value="<?php echo $mobile1 ?>" validate="validateMobileInteger" minlength="10" name="mobileno[]" id="mobileno" type="text" maxlength="10"/>
						<input type="text" caption="alternate mobile number" value="<?php echo $mobile2 ?>" validate="validateMobileInteger" maxlength="10" minlength="10" id="alternatemobileno" name="mobileno[]" />&nbsp;<span class="Fnt11 htxt">(enter 10 digit mobile numbers e.g 9999999999)</span></div>
			<div class="errorPlace"><div id="mobileno_error" class="errorMsg">&nbsp;</div></div>
			<div class="errorPlace"><div id="alternatemobileno_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="mb10">
                    	<div class="float_L w250">
                        	<div class="txt_align_r">Set daily limit for auto download:</div>
                        </div>
                        <div style="margin-left:270px">
				<?php if($hide_params == 'N') { ?>
                        	<div><input caption="leads per day" blurMethod="auto_download_limit_text();" id="leadsperday" name="leadsperday" maxlength="3" validate="validateInteger" value="<?php echo $leads_daily_limit; ?>" type="text" class="w60" /> leads per day</div>
				<?php } else { ?>
				<div><input caption="matched responses per day" blurMethod="auto_download_limit_text();" id="leadsperday" name="leadsperday" maxlength="3" validate="validateInteger" value="<?php echo $leads_daily_limit; ?>" type="text" class="w60" /> Matched Responses per day</div>
				<?php } ?>
			    <div id="auto_download_limit_text" onMouseOut="auto_download_limit_text();" class="Fnt11 htxt">(you will receive contact details till your daily limit set above is not met)</div>
				<div class="errorPlace"><div id="leadsperday_error" class="errorMsg">&nbsp;</div></div>
                        </div>
					<div class="clear_B">&nbsp;</div>
                    </div>
		    <div class="mb10">
                    	<div class="float_L w250">
				<?php if($hide_params == 'N') { ?>
                        	<div class="txt_align_r">Set price per lead:</div>
				<?php } else { ?>
				<div class="txt_align_r">Set price per Matched Response:</div>
				<?php } ?>
                        </div>
                        <div style="margin-left:270px">
                        	<div><input caption="price" id="set_price_per_lead" name="set_price_per_lead" validate="validateInteger" value="<?php echo $price_per_lead; ?>" maxlength="4" type="text" class="w60" /> credits</div>
                            <div id="suggestedPrice" class="Fnt11 htxt"></div>
				<div class="errorPlace"><div id="set_price_per_lead_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                </div>
		</div>
        <div class="lineSpace_10">&nbsp;</div>
		<div><input id="checkbox_auto_responder" name="checkbox_auto_responder" <?php if($auto_responder_email_is_active=='live' || $auto_responder_sms_is_active=='live'){?>checked<?php } ?> type="checkbox" onClick="setUnsetAotoDwnld();auto_download_limit_text();" /> <strong>Set Auto Responder</strong></div>
                <?php if($hide_params == 'N') { ?>
		<div class="htxt Fnt11 mb20" style="padding-left:23px">Auto Responder will send e-mail or SMS to the matching leads on your behalf as soon as they arrive. Shiksha recommends setting an auto responder as it create awareness about your institute
amongst prospective students prior to your phone call. Its free if you have chosen to auto download the contact details of the lead above else appropriate credits would be deducted.</div>
		<?php } else { ?>
		<div class="htxt Fnt11 mb20" style="padding-left:23px">Auto Responder will send e-mail or SMS to the matching responses on your behalf as soon as they arrive. Shiksha recommends setting an auto responder as it create awareness about your institute
amongst prospective students prior to your phone call. Its free if you have chosen to auto download the contact details of the matched response above else appropriate credits would be deducted.</div>
		<?php } ?>
				<div id="setUnsetAutoResponder" style="display:none;">
				<div class="mb10" style="padding:0 0 0 30px">
		<?php if($hide_params == 'N') { ?>
		<input id="emailmybehalf" onClick="setUnsetAotoDwnld();" <?php if($auto_responder_email_is_active=='live'){?>checked<?php } ?> name="emailmybehalf" type="checkbox" /> Send e-mail on my behalf to the matching lead
		<?php } else { ?>
		<input id="emailmybehalf" onClick="setUnsetAotoDwnld();" <?php if($auto_responder_email_is_active=='live'){?>checked<?php } ?> name="emailmybehalf" type="checkbox" /> Send e-mail on my behalf to the matching response
		<?php } ?>
				</div>
	<div class="wdh100">
	<div class="mb10">
        <div class="float_L w250"><div class="txt_align_r">From Name:<br /></div></div>
        <div style="margin-left:270px">
                <div>
                                                        <input caption="From Name" tip="from_name" type="text" validate="validateStr" minlength="4" maxlength="100" id="from_name" profanity="true" value="<?php echo $from_name; ?>" name="from_name" />
                                                </div>
                                <div class="errorPlace"><div id="from_name_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
	<div class="mb10">
        <div class="float_L w250"><div class="txt_align_r">From Email id:<br /></div></div>
        <div style="margin-left:270px">
                <div>
                                                        <input caption="From Email id" tip="from_emailid" type="text" validate="validateEmail" minlength="7" maxlength="100" id="from_emailid" value="<?php echo $from_emailid; ?>" name="from_emailid" /> <span style="display: block;">( Please enter non-Shiksha domain email Id (Email Id ending with "@shiksha.com" not allowed.)</span>
                                                </div>
                                <div class="errorPlace"><div id="from_emailid_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
		<div class="mb10">
                    <div class="float_L w250"><div class="txt_align_r">Subject:</div></div>
                    <div style="margin-left:270px">
                        <div><input id="subjectforautoresponder" name="subjectforautoresponder" maxlength="250" value="<?php echo $auto_responder_email_subject; ?>" caption="Subject" minlength="15" validate="validateStr" type="text" class="w200" /> <span>( use @NAME to auto pick students name for subject )</span></div>
			<div class="errorPlace"><div id="subjectforautoresponder_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="mb10">
                    	<div class="float_L w250">
                        	<div class="txt_align_r">Message:</div>
                        </div>
                        <div style="margin-left:270px">
                        	<div><textarea id="auto_responder_email_textarea_msg" caption="message" validate="validateStr" minlength="250" maxlength="10000" name="auto_responder_email_textarea_msg"  style="width:490px;height:100px"><?php echo $auto_responder_email_msg; ?></textarea></div>
                            <div>( use @NAME to auto pick students name in message)</div>
				<div class="errorPlace"><div id="auto_responder_email_textarea_msg_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
		    <div class="mb10">
                    	<div class="float_L w250">
                        	<div class="txt_align_r">Set daily limit for auto email:</div>
                        </div>
                        <div style="margin-left:270px">
				<?php if($hide_params == 'N') { ?>
                        	<div><input id="daily_limt_auto_email" blurMethod="auto_download_limit_text();" caption="Auto responder Email limit" name="daily_limt_auto_email" validate="validateInteger" type="text" value="<?php echo $auto_responder_email_daily_limit; ?>" class="w60" maxlength="4"/> leads per day</div>
				<?php } else { ?>
				<div><input id="daily_limt_auto_email" blurMethod="auto_download_limit_text();" caption="Auto responder Email limit" name="daily_limt_auto_email" validate="validateInteger" type="text" value="<?php echo $auto_responder_email_daily_limit; ?>" class="w60" maxlength="4"/> Matched Responses per day</div>
				<?php } ?>
				
				<div id="auto_responder_email_limit_text" onMouseOut="auto_download_limit_text();" class="Fnt11 htxt">(Enter the number of paid Email that you want to be sent <?php if($credit_consumption['1']['deductcredit'] > 0 ){?> @ <?php echo $credit_consumption['1']['deductcredit']?> credit / Email.<?php }?>)</div>
				
				<div class="errorPlace"><div id="daily_limt_auto_email_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
               	</div>
        </div>
        <div class="lineSpace_20">&nbsp;</div>
        <?php if($hide_params == 'N') { ?>
	<div class="mb10" style="padding:0 0 0 30px"><input id="checkbox_sendsms" onClick="setUnsetAotoDwnld();" name="checkbox_sendsms" <?php if($auto_responder_sms_is_active=='live'){?>checked<?php } ?> type="checkbox" /> Send SMS on my behalf to the matching leads <span class="Fnt11 htxt">(sender name would be displayed as Shiksha.com)</span></div>
        <?php } else { ?>
	<div class="mb10" style="padding:0 0 0 30px"><input id="checkbox_sendsms" onClick="setUnsetAotoDwnld();" name="checkbox_sendsms" <?php if($auto_responder_sms_is_active=='live'){?>checked<?php } ?> type="checkbox" /> Send SMS on my behalf to the matching responses <span class="Fnt11 htxt">(sender name would be displayed as Shiksha.com)</span></div>
	<?php } ?>
	<div class="wdh100">
		<div class="mb10">
			<div class="float_L w250">
                        	<div class="txt_align_r">SMS Message:</div>
                        </div>
                        <div style="margin-left:270px">
                        	<div><textarea id="auto_responder_sms_textarea_msg" name="auto_responder_sms_textarea_msg" validate="validateStr" caption="message" minlength="25" maxlength="160" style="width:190px;height:50px"><?php echo $auto_responder_sms_msg; ?></textarea></div>
                            <div class="htxt Fnt11">160 characters only</div>
				<div class="errorPlace"><div id="auto_responder_sms_textarea_msg_error" class="errorMsg">&nbsp;</div></div>
                        </div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="mb10">
                    	<div class="float_L w250">
                        	<div class="txt_align_r">Set daily limit for auto SMS:</div>
                        </div>
                        <div style="margin-left:270px">
				<?php if($hide_params == 'N') { ?>
                        	<div><input caption="Auto responder SMS limit" blurMethod="auto_download_limit_text();" id="daily_limt_auto_sms" maxlength="4" value="<?php echo $auto_responder_sms_daily_limit;?>" name="daily_limt_auto_sms" validate="validateInteger" type="text" class="w60" /> leads per day</div>
				<?php } else { ?>
				<div><input caption="Auto responder SMS limit" blurMethod="auto_download_limit_text();" id="daily_limt_auto_sms" maxlength="4" value="<?php echo $auto_responder_sms_daily_limit;?>" name="daily_limt_auto_sms" validate="validateInteger" type="text" class="w60" /> Matched Responses per day</div>
				<?php } ?>
				
				<div id="auto_responder_sms_limit_text" onMouseOut="auto_download_limit_text();" class="Fnt11 htxt">(Enter the number of paid SMS that you want to be sent <?php if($credit_consumption['2']['deductcredit']>0){?> @ <?php echo $credit_consumption['2']['deductcredit'];?> credit / SMS.<?php }?>)</div>
				
				<div class="errorPlace"><div id="daily_limt_auto_sms_error" class="errorMsg">&nbsp;</div></div>
                        </div>
			<div class="clear_B">&nbsp;</div>
		</div>
         </div>
  </div>
	</div>

  <div class="bbm">&nbsp;</div>
  <div align="center">
	  <div class="mt10">
		<input type="submit" class="btn_3" id="SASubmitButton" value="&nbsp;" /><!--&nbsp;&nbsp;&nbsp;<input type="submit" class="btn_2" value="&nbsp;" />-->
	   </div>
  </div>
</div>
</div>

<div class="lineSpace_10">&nbsp;</div>
</form>
<script>
<?php
if($Search_Agent_Update_flag == ""){
?>
$('emailsms_settings').style.display = 'none';
$('displayData_Search_Agent').value = $('displayData').value;
$('inputData_Search_Agent').value = $('inputData').value;
$('inputHTMLData_Search_Agent').value = base64_encode($('search_result_display_array_content_div').innerHTML);
<?php }?>

document.getElementById('manageLinkOrHeadingDiv').style.display="block";
document.getElementById('manageHeadingOrLinkDiv').style.display="none";


<?php if($isAbroadAgent){ ?>	
	$('courseName').value = 'Study Abroad';
<?php } ?>
setUnsetAotoDwnld();
auto_download_limit_text();
</script>
