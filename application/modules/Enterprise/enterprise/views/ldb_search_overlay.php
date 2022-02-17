<?php
	if($overlay == 'Send SMS') {
		$textToDisplay = 'SMS';
		$addText = 'to ';
	}
	else if($overlay == 'Send Email') {
		$textToDisplay = 'Email';
		$addText = 'to ';
	}
	else if($overlay == 'Download') {
		$textToDisplay = 'Download';
		$addText = '';
	}
?>

<?php if($type == 'confirm') { ?>

	<form name="LDB_confirmOverlayForm" id="LDB_confirmOverlayForm">

		<div class="layer-outer">
			<div>
        		<div>
            		<div class="layer-title">
                		<a href="javascript:void(0);" onclick="closeMessage();" class="close"></a> 
            			<h4><?php if ($overlay == 'Download') { echo "Download CSV"; } else { echo $overlay; } ?></h4>
		            </div>       
		        </div>

        		<div>
					<?php if($countNdnc>0 && $overlay=='Send SMS' && $selected-$countNdnc!=0){ ?>

						<?php if($inputDataMR){ ?>
    					<div class="wdh100">
    						<b><?php echo $countNdnc; ?></b> matched response<?php echo ($countNdnc<=1)? '' : 's'; ?> selected by you <?php echo ($countNdnc<=1)? 'is' : 'are'; ?> in NDNC. SMS is being sent to rest <b><?php echo $selected-$countNdnc; ?></b>&nbsp; matched response<?php echo (($selected-$countNdnc)<=1)? '' : 's'; ?></b> and &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b> will be deducted for the same. Click Confirm &amp; <?php echo $textToDisplay;?> to start.
    					</div>
    					<?php } else { ?>
    					<div class="wdh100">
    						<b><?php echo $countNdnc; ?></b> lead<?php echo ($countNdnc<=1)? '' : 's'; ?> selected by you <?php echo ($countNdnc<=1)? 'is' : 'are'; ?> in NDNC. SMS is being sent to rest <b><?php echo $selected-$countNdnc; ?></b>&nbsp; lead<?php echo (($selected-$countNdnc)<=1)? '' : 's'; ?></b> and &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b> will be deducted for the same. Click Confirm &amp; <?php echo $textToDisplay;?> to start.</div>
    					<?php } ?>
	    			
	    			<?php } else if($selected-$countNdnc==0 && $overlay=='Send SMS' && $countNdnc>0){ ?>

	    				<?php if($inputDataMR){ ?>
            			<div class="wdh100">All matched responses selected by you are in NDNC. No SMS can be delivered. Please select other users.</div>
	    				<?php } else { ?>
	    				<div class="wdh100">All leads selected by you are in NDNC. No SMS can be delivered. Please select other users.</div>
	    				<?php } ?>

	    			<?php } else if($overlay=='Download' && $nonviewable>0){ ?>
	    			
	    				<?php if($inputDataMR){ ?>
            			<div class="wdh100"><b><?php echo $nonviewable; ?></b> matched responses<?php echo ($nonviewable<=1)? '' : 's'; ?> selected by you <?php echo ($nonviewable<=1)? 'is' : 'are'; ?> not Downloadable as <?php echo ($nonviewable<=1)? 'its' : 'their'; ?> limit to view contact details has been reached. You are about to <?php echo $overlay; ?>&nbsp;<b><?php echo $selected-$nonviewable; ?>&nbsp;matched response<?php echo (($selected-$nonviewable)<=1)? '' : 's'; ?></b>. Your account will be charged with &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. Click Confirm &amp; <?php echo $textToDisplay;?> to start.</div>
	    				<?php } else { ?>
	    				<div class="wdh100"><b><?php echo $nonviewable; ?></b> lead<?php echo ($nonviewable<=1)? '' : 's'; ?> selected by you <?php echo ($nonviewable<=1)? 'is' : 'are'; ?> not Downloadable as <?php echo ($nonviewable<=1)? 'its' : 'their'; ?> limit to view contact details has been reached. You are about to <?php echo $overlay; ?>&nbsp;<b><?php echo $selected-$nonviewable; ?>&nbsp;lead<?php echo (($selected-$nonviewable)<=1)? '' : 's'; ?></b>. Your account will be charged with &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. Click Confirm &amp; <?php echo $textToDisplay;?> to start.</div>
	    				<?php } ?>

	     			<?php } else { ?>
	    				
	    				<?php if($inputDataMR){ ?>
	    				<div class="wdh100">You are about to <?php echo $overlay; ?>&nbsp;<?php echo $addText; ?><b><?php echo $selected; ?>&nbsp;matched response<?php echo ($selected<=1)? '' : 's'; ?></b>. Your account will be charged with &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. Click Confirm &amp; <?php echo $textToDisplay;?> to start.</div>
	    				<?php } else { ?>
	    				<div class="wdh100">You are about to <?php echo $overlay; ?>&nbsp;<?php echo $addText; ?><b><?php echo $selected; ?>&nbsp;lead<?php echo ($selected<=1)? '' : 's'; ?></b>. Your account will be charged with &nbsp;<b><?php echo $ReqCredits;?> credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. Click Confirm &amp; <?php echo $textToDisplay;?> to start.</div>
	    				<?php } ?>
            		
            		<?php } ?>
		            
		            <div class="lineSpace_30">&nbsp;</div>
		            <div class="clear_B">&nbsp;</div>
        			
            		<div style="padding-left:100px">
						<?php if($selected-$countNdnc==0 && $overlay=='Send SMS' && $countNdnc>0){ ?>
						<div style="padding-left:100px">
                        	<div class="Fnt18">
                        		<a onclick="closeMessage();" href="javascript:void(0);"><b>Close</b></a>
                        	</div>
                		</div>	
						<?php } else { ?>
            			<div class="float_L">
                    		<span class="btnLftCrnr30">
                        		<span class="btnRtCrnr30">
                        			<input id="final_confirmation_btn" onclick="return startAction('<?php echo $ReqCredits;?>','<?php echo $AvlCredits;?>');" name="submit" value="Confirm &amp; <?php echo $textToDisplay; ?>" type="button" style="width:150px">
                        		</span>
                    		</span>
                		</div>
                		<div class="float_L">
	                		<div class="pf7 Fnt14">
	                			<a onclick="closeMessage();" href="javascript:void(0);"><b>Cancel</b></a>
	                		</div>
                		</div>
						<?php } ?>
                		<div class="clear_L">&nbsp;</div>
            		</div>

            		<div class="clear_B">&nbsp;</div>
            		<div class="lineSpace_10">&nbsp;</div>

        		</div>
    		</div>
		</div>

	</form>
<?php } ?>

<?php if($type == 'form') { ?>

	<form method="post" autocomplete="off" onSubmit="if(!submit_input_form(this)) { return false; }" id="frm_inputform" name="frm_inputform">

		<div class="layer-outer">
			<div>

        		<div>
            		<div class="layer-title">
            			<a href="javascript:void(0);"onclick="closeMessage();" class="close"></a>
           				<h4><?php if ( $overlay == 'DownloadCSV') { echo "Download CSV"; } if ( $overlay == 'SendSMS') { echo "Send SMS";} if ( $overlay == 'SendEmail') { echo "Send Email";} ?> - <?php if ($filter == 'smsed') { echo "SMSed";} if($filter == 'emailed') {	echo "Emailed";} if($filter == 'all') { echo "All";} if($filter == 'unviewed') { echo "Unviewed";} if($filter == 'viewed') { echo "Viewed";} ?></h4>
           			</div>                     
        		</div>

        		<div>
        			<div>
	            		<div class="mb10">Please select an option</div>
		    			<?php if($inputDataMR){ ?>
    	        		<div class="mb10">
    	        			<input onclick="radio_selection();" id="userselection_id" checked name="userselection" type="radio" /> Select Matched Responses <span class="Fnt11 fcdGya">(<?php echo $selected;?> of total <?php echo $totalCount; ?>)</span>
    	        		</div>
        	    		<?php } else { ?>
		    			<div class="mb10">
		    				<input onclick="radio_selection();" id="userselection_id" checked name="userselection" type="radio" /> Select Leads <span class="Fnt11 fcdGya">(<?php echo $selected;?> of total <?php echo $totalCount; ?>)</span>
		    			</div>
		    			<?php } ?>		    
		    			
		    			<?php if($inputDataMR){ ?>
		    			<div>
		    				<input onclick="radio_selection();" id="userselection_txt" name="userselection" type="radio" />
		    				<span id="next_span_show_count" style="display:none;"> Next &nbsp;</span>
		    				<input id="inputleads" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Number" caption="leads" maxlength="3" type="text" value="Number" size="5" style="color:#ada6ad;" /> Matched Responses 
		    				<span style="display:none;" id="next_span_show_text"></span>
		    			</div>
						<?php } else { ?>
						<div>
							<input onclick="radio_selection();" id="userselection_txt" name="userselection" type="radio" />
							<span id="next_span_show_count" style="display:none;"> Next &nbsp;</span>
							<input id="inputleads" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="Number" caption="leads" maxlength="3" type="text" value="Number" size="5" style="color:#ada6ad;" /> Leads 
							<span style="display:none;" id="next_span_show_text"></span>
						</div>
						<?php } ?>

						<?php if($inputDataMR){ ?>
						<div class="Fnt11 fcdGya mb10" style="padding-left:24px">Max <?php echo $maxlimitTextBox; ?> Matched Response<?php echo ($maxlimitTextBox<=1)? '' : 's'; ?> at one time</div>
		 				<?php } else { ?>
		 				<div class="Fnt11 fcdGya mb10" style="padding-left:24px">Max <?php echo $maxlimitTextBox; ?> Lead<?php echo ($maxlimitTextBox<=1)? '' : 's'; ?> at one time</div>
		 				<?php } ?>

						<?php if (($overlay != 'DownloadCSV') && ($filter != 'viewed') && ($filter != 'emailed') && ($filter != 'smsed')) { ?>
						
							<?php if($inputDataMR){ ?>
							<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="checkbox_filter_ldb" type="checkbox" checked name="checkbox_filter" value="<?php echo $overlay;?>"/> Exclude matched responses already <?php if ($overlay == 'SendSMS') { echo "SMSed"; } if ($overlay == 'SendEmail') { echo "Emailed";} ?> </div>
							<?php } else { ?>
							<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="checkbox_filter_ldb" type="checkbox" checked name="checkbox_filter" value="<?php echo $overlay;?>"/> Exclude Leads already <?php if ($overlay == 'SendSMS') { echo "SMSed"; } if ($overlay == 'SendEmail') { echo "Emailed";} ?> </div>
							<?php } ?>

						<?php } ?>

						<div style="display:none;">
							<div id="inputleads_error" style="padding-left:24px" class="errorMsg"></div>
						</div>
            		
            		</div>
            		
            		<div class="lineSpace_10">&nbsp;</div>
            		<div class="clear_B">&nbsp;</div>
            		
            		<div style="padding-left:100px">
            			<div class="float_L">
                    		<span class="btnLftCrnr30">
                        		<span class="btnRtCrnr30">
                        			<input id="submit_inputform" name="submit" value="<?php if ( $overlay == 'DownloadCSV') { echo "Download CSV"; } if ( $overlay == 'SendSMS') { echo "Send SMS";} if ( $overlay == 'SendEmail') { echo "Send Email";} ?>" type="submit" style="width:150px">
                        		</span>
                    		</span>
                		</div>
                		<div class="float_L">
	                		<div class="pf7 Fnt14">
	                			<a href="javascript:void(0);" onclick="closeMessage();"  ><b>Cancel</b></a>
	                		</div>
                		</div>
                		<div class="clear_L">&nbsp;</div>
            		</div>
		            
		            <div class="clear_B">&nbsp;</div>
		            <div class="lineSpace_10">&nbsp;</div>

		    		<div class="Fnt11"><b>* Caution:</b> Simultaneous activity from one account may result in multiple consumption of credits.</div>
	    			<div class="lineSpace_10">&nbsp;</div>

        		</div>
    		</div>
		</div>
	</form>

<script>
	var str_ldb_txt = '';
	$('next_span_show_count').style.display = 'none';
	$('next_span_show_text').style.display = 'none';
	$('next_span_show_text').innerHTML = '';
	
	<?php if ($overlay == 'DownloadCSV') { ?>
		str_ldb_txt = '(You have already Downloaded '+ldb_result_start_csv_flag_ajax+' of '+<?php echo $totalCount; ?>+')';
		if(ldb_result_start_csv_flag_ajax > 0 ) {
			$('next_span_show_count').style.display = '';
			$('next_span_show_text').style.display = '';
			$('next_span_show_text').innerHTML = str_ldb_txt;
		}
	<?php } if ($overlay == 'SendSMS') { ?>
		str_ldb_txt = '(You have already SMSed '+ldb_result_start_sms_flag_ajax+' of '+<?php echo $totalCount; ?>+')';
		if (ldb_result_start_sms_flag_ajax > 0 ) {
			$('next_span_show_count').style.display = '';
			$('next_span_show_text').style.display = '';
			$('next_span_show_text').innerHTML = str_ldb_txt;
		}
	<?php } if ($overlay == 'SendEmail') { ?>
		str_ldb_txt = '(You have already Emailed '+ldb_result_start_email_flag_ajax+' of '+<?php echo $totalCount; ?>+')';
		if (ldb_result_start_email_flag_ajax > 0 ) {
			$('next_span_show_count').style.display = '';
			$('next_span_show_text').style.display = '';
			$('next_span_show_text').innerHTML = str_ldb_txt;
		}
	<?php } ?>

	<?php if ($overlay == 'SendEmail') { ?>
		msg_help_flag = 'Send Email';
	<?php } ?>
	<?php if ($overlay == 'DownloadCSV') { ?>
		msg_help_flag = 'Download CSV';
	<?php } ?>
	<?php if ($overlay == 'SendSMS') { ?>
		msg_help_flag = 'Send SMS';
	<?php } ?>
	$('inputleads').disabled = true;

</script>

<script>
	try {
		if (msg_help_flag != 'Download CSV') {
			$('checkbox_filter_ldb').disabled = true;
		}
	}catch(e) {}
</script>

<?php } ?>

<?php if ($type == 'prompt') { 
		if ($nonviewable != 'all') { ?>

		<div class="layer-outer">
			<div>
		        <div class="layer-title">                        
		            <a href="javascript:void(0);" onclick="closeMessage();" class="close"></a>            
		            <h4>Insufficient Credits</h4>
		        </div>
        	<div>
        	
        	<div>
	            <?php if($inputDataMR){ ?>
		    	<div class="infoIcons mb10">You have chosen to&nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?><b><?php echo $selected; ?>&nbsp;</b>matched response<?php echo ($selected<=1)? '' : 's'; ?> which requires&nbsp;<b><?php echo $ReqCredits;?>&nbsp;credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. <span class="fcOrg">You have <b>only&nbsp;<?php echo $AvlCredits;?>&nbsp;credit<?php echo ($AvlCredits<=1)? '' : 's'; ?></b> in your account. </span> You may choose to &nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?>less number of matched responses or contact our Sales Executive to buy more credits.</div>
				<?php } else { ?>
				<div class="infoIcons mb10">You have chosen to&nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?><b><?php echo $selected; ?>&nbsp;</b>lead<?php echo ($selected<=1)? '' : 's'; ?> which requires&nbsp;<b><?php echo $ReqCredits;?>&nbsp;credit<?php echo ($ReqCredits<=1)? '' : 's'; ?></b>. <span class="fcOrg">You have <b>only&nbsp;<?php echo $AvlCredits;?>&nbsp;credit<?php echo ($AvlCredits<=1)? '' : 's'; ?></b> in your account. </span> You may choose to&nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?>less number of leads or contact our Sales Executive to buy more credits.</div>
				<?php } ?>
			</div>
            
            <div class="lineSpace_30">&nbsp;</div>
            <div class="clear_B">&nbsp;</div>
            
            <div align="center">
	            <div class="Fnt14">
	            	<a href="javascript:void(0);" onclick="closeMessage();" ><b>Cancel</b></a>
	            </div>
            </div>
            
            <div class="clear_B">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>

        </div>
    <!-- </div>
</div> -->
<?php } else { ?>
		
		<div class="w480" style="background:none;">
			<div class="bgWhite">
        		<div class="otit">
					<?php if($inputDataMR){ ?>
            		<div class="float_L">Selected Matched Response(s) is/are non-downloadable</div>
	    			<?php } else { ?>
	    			<div class="float_L">Selected Lead(s) is/are non-downloadable</div>
	    			<?php } ?>
            		<div class="float_R allShikCloseBtnWrapper">
            			<div onclick="closeMessage();" class="cssSprite1 allShikCloseBtn">&nbsp;</div>
            		</div>
            		<div class="clear_B">&nbsp;</div>
        		</div>
        		
        		<div class="pf10">
        			<div class="wdh100">
						<?php if($inputDataMR){ ?>

	            		<div class="infoIcons mb10" style="min-height: 35px !important;">You have chosen to&nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?><b><?php echo $selected; ?>&nbsp;</b>matched response<?php echo ($selected<=1)? '' : 's'; ?>. Limit to View Contact Details for the matched response<?php echo ($selected<=1)? '' : 's'; ?> has been reached, <?php echo ($selected<=1)? 'it' : 'they'; ?> cannot be viewed further.</div>

						<div class="mb10" style="margin-left: 50px;"><input onclick="radio_selection();" id="userselection_id" checked name="userselection" type="radio" /> Select Matched Responses <span class="Fnt11 fcdGya">(<?php echo $selected;?> of total <?php echo $totalCount; ?>)</span></div>

        	    		<?php } else { ?>

		     			<div class="infoIcons mb10" style="min-height: 35px !important;">You have chosen to&nbsp;<?php echo $overlay;?>&nbsp;<?php echo $addText;?><b><?php echo $selected; ?>&nbsp;</b>lead<?php echo ($selected<=1)? '' : 's'; ?>. Limit to View Contact Details for the lead<?php echo ($selected<=1)? '' : 's'; ?> has been reached, <?php echo ($selected<=1)? 'it' : 'they'; ?> cannot be viewed further.</div>

			        	<div class="mb10" style="margin-left: 50px;"><input onclick="radio_selection();" id="userselection_id" checked name="userselection" type="radio" /> Select Leads <span class="Fnt11 fcdGya">(<?php echo $selected;?> of total <?php echo $totalCount; ?>)</span></div>

		    			<?php } ?>
	    			</div>

            		<div class="lineSpace_30">&nbsp;</div>
            		<div class="clear_B">&nbsp;</div>
            		
            		<div align="center">
	            		<div class="Fnt14">
	            			<a href="javascript:void(0);" onclick="closeMessage();" ><b>Cancel</b></a>
	            		</div>
            		</div>
		            
		            <div class="clear_B">&nbsp;</div>
		            <div class="lineSpace_10">&nbsp;</div>
        		
        		</div>
    		</div>
		</div>
	<?php } 
	} ?>
