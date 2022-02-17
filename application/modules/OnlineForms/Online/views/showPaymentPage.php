        <form action="" method="post" name="OF_Payment_Form" id="OF_Payment_Form">
		
        <div class="formChildWrapper">
        	<div class="formSection">
                <h2 class="reviewTitle"><a href="/Online/OnlineForms/displayForm/<?php echo $courseId;?>">Review your form</a></h2>
				
				<!-- <input type="hidden" name="merchant_id" id="merchant_id" value="" />
				<input type="hidden" name="amount" id="amount" value="" />
				<input type="hidden" name="order_id" id="order_id" value="" />
				<input type="hidden" name="cancel_url" id="cancel_url" value="" />
				<input type="hidden" name="redirect_url" id="redirect_url" value="" />
				<input type="hidden" name="language" id="language" value="" />
				<input type="hidden" name="currency" id="currency" value="" />
				<input type="hidden" name="Notes" value="" /> -->

				<input type=hidden name=encRequest id="encRequest" value="">
				<input type=hidden name=access_code id="access_code" value="">

			<!--paytm-->
			<?php
			global $usersForPaytmTesting;
			if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){?>
			<div class="coupen-code-field"><label style="width:345px;">Enter Coupon Code to get Rs.100 on <i class="paytm-sprite paytm-large-logo"></i> <span class="que-mark" style="position: relative" onmouseout="$j('#paytm-wdgttooltip').hide();" onmouseover="paytmWidgettooltip(this)">?</span></label>
			<div class="paytm-tooltip" style="left: 58%;display: none;" id="paytm-wdgttooltip">
			     <i class="paytm-sprite tooltip-pointer"></i>
			     <ul class="tooltip-list">
							     <li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
							     <li>You will get a mail from PayTM to claim your money</li>
			     </ul>
			</div>
			<div style="float:left; width:220px;">
				<input style="width:205px;" type="text" name="Merchant_Param" id="addCoupon" maxlength="8" value="<?php if(isset($couponCode)){ echo $couponCode;}?>" autocomplete="off"/><br /><div class="paytm_error-msg" id="error_coupon"></div>
			</div>
			<input type="button" class="payNowButton" id="applyCouponBtn" value="Apply Coupon" onclick="validateCoupon()"/>
			</div>
			<?php }?>
			<!--end-paytm-->
				
				
		<?php if($courseId=='27232') {?>
		<h3 class="noBorder">Pay online - Rs. <?php echo $amount; ?>- for General/OBC/EBC/WBC and Rs.200/- for SC/ST as application fee to <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?> for <?php echo $instituteInfo[0]['instituteInfo'][0]['courseTitle'];?>, <?=$instituteInfo[0]['instituteInfo'][0]['sessionYear']; ?> session </h3>
		
		<?php } elseif($courseId=='128457') {?>
		<h3 class="noBorder">Pay online - Rs. <?php echo $amount; ?> till Feb 15 (Thereafter Rs.1500) as application fee to <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?> for <?php echo $instituteInfo[0]['instituteInfo'][0]['courseTitle'];?>, <?=$instituteInfo[0]['instituteInfo'][0]['sessionYear']; ?> session </h3>
		<?php } else { ?>
		
				
                <h3 class="noBorder">Pay online - Rs. <?php echo $amount; ?> as application fee to <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?> for <?php echo $instituteInfo[0]['instituteInfo'][0]['courseTitle'];?>, <?=$instituteInfo[0]['instituteInfo'][0]['sessionYear']; ?> session </h3>
		<?php } ?>
                <ul>
                <li>
					<span class="inputRadioSpacer">
						<input type="radio" class="radio" name="paymenent" value="online" <?php if($instituteInfo[0]['instituteInfo'][0]['institute_id']!='3698'){ echo "checked='checked'";} else {echo "disabled";} ?> onclick="setPaymentMode('online');" /> 
						<span class="paymentLabelBig">Credit/ Debit Cards / Net Banking</span> 
					</span>
				</li>
                </ul>
				
		<?php //if($instituteInfo[0]['instituteInfo'][0]['institute_id']=='36454' || $instituteInfo[0]['instituteInfo'][0]['institute_id']=='28230'){ $hideDDOption = true;} 
		$hideDDOption = true;
		if(ENVIRONMENT=='test2'){
			$hideDDOption = false;
		}
		?>	
                <div style="border-top:1px solid #e9e9e9; position:relative; margin:5px 0 20px 0; clear:both; width:100%; float:left; <?php if($hideDDOption) echo "display:none;";?>">
                	<span style="background:#FFF; position:absolute; top:-10px; text-align:center; left:50%; font-size:16px; padding:0 5px">OR</span>
                </div>
                
                <h3 <?php if($hideDDOption) echo "style='display:none;'";?> class="noBorder">Submit this application now and make payment through Demand Draft</h3>
                <ul <?php if($hideDDOption) echo "style='display:none;'";?>>
			<?php if($courseId=='128457'){?>
			<li>
				<span style="color:red;">
					If you wish to make a payment through Demand Draft, the DD should reach before 28th Feb.
				</span>
			</li>
			<?php } ?>
			<?php //if($instituteInfo[0]['instituteInfo'][0]['institute_id']!='3698'){ ?>
                	<li>
                    	<span class="inputRadioSpacer"><input class="radio" type="radio" name="paymenent" id="payment_by_draft" value="demandDraft" onclick="setPaymentMode('draft');" /> <span class="payOption">Submit the application now and send a Demand Draft of Rs. <?php echo $amount; ?> in favor of <?php echo $instituteInfo[0]['instituteInfo'][0]['demandDraftInFavorOf']; ?> payable at <?php echo $instituteInfo[0]['instituteInfo'][0]['demandDraftPayableAt']; ?> to the following address:</span></span>
                    	<div class="spacer10 clearFix"></div>                        
                        <div class="DD_detailsCols" style="width:175px;">
				<strong>Ms. Anupama Mattegunta</strong><br/>
                        	<strong>Info Edge India Ltd.</strong><br />
                            <p>C-130, Sector – 2,<br/>2<sup>nd</sup> Floor,<br />
							Noida - 201301
                            </p>
                        </div>
			<div class="clear_B">&nbsp;</div>
			<div style="padding-left:20px; padding-top: 10px;"><strong>Please note:</strong> Mention your name and application reference number at the back of the DD</div>
	                </li>
			<?php?>
                </ul>
                
				<input type="hidden" name="onlineFormId" value="<?php echo $onlineFormId; ?>" />
				
				</form>
                
            </div>
       	</div>
        <div class="clearFix"></div>
        <div class="buttonWrapper">
        	<a class="cancelLink" href="/Online/OnlineForms/showOnlineForms/<?php echo $courseId; ?>/1/4" title="Back"><span>&laquo;</span> Back</a>
        	<div class="buttonsAligner">
                <?php
		global $usersForPaytmTesting;
		if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){?>
		<input type="button" title="Pay Now" value="Pay Now" id="payNow" class="payNowButton" onclick="trackEventByGA('ONLINE_FORM_BUTTON','PAY_NOW_BUTTON/<?=$this->courselevelmanager->getCurrentDepartment();?>'); validateCoupon(this,'<?php echo $onlineFormId; ?>');" />
		<?php }else{?>
		<input type="button" title="Pay Now" value="Pay Now" id="payNow" class="payNowButton" onclick="trackEventByGA('ONLINE_FORM_BUTTON','PAY_NOW_BUTTON/<?=$this->courselevelmanager->getCurrentDepartment();?>'); processPayment(this,'<?php echo $onlineFormId; ?>');" />
		<?php }?>
                <input type="button" title="Pay Later" value="Pay Later" id="payLater" class="payLaterButton" onclick="trackEventByGA('ONLINE_FORM_BUTTON','PAY_LATER_BUTTON/<?=$this->courselevelmanager->getCurrentDepartment();?>'); goToStudentDashboard();" />
            </div>
        </div>
     </form>
	<script>
		function goToStudentDashboard()
		{
			window.location = '/studentFormsDashBoard/StudentDashBoard/index';
		}
		function setPaymentMode(mode)
		{
			if(mode == 'online')
			{
				document.getElementById('payNow').value = 'Pay Now';
				document.getElementById('payLater').value = 'Pay Later';
			}
			if(mode == 'draft')
			{
				document.getElementById('payNow').value = 'Submit Now';
				document.getElementById('payLater').value = 'Submit Later';
			}
		}
	</script>
