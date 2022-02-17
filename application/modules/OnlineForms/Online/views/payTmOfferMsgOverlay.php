
<div class="rep-form-layer" id="ca-inform-layer">

<?php $this->load->view('Online/payTmOfferMsg');?>

<div class="coupen-code-field flLt" style="width:100%; margin: 10px 0 0 0;border-bottom: 0px;padding-bottom: 0px;">
  <p style="display: block;padding-bottom: 15px;font-size: 16px;">You will now be taken to the institute website to complete the application.</p>
      <label style="line-height: 31px;">Enter Coupon Code to get Rs.100 on <i class="paytm-sprite paytm-large-logo"></i> <span class="que-mark" style="position: relative" onmouseover="paytmWidgettooltipOverlay(this)" onmouseout="$j('#paytm-wdgttooltip').hide();">?</span></label>

			<div style="float:left; width:170px;margin-left: 10px;">
				<input value = "<?=$preFilledCoupon?>" style="width:150px;" type="text" name="Merchant_Param" id="addCoupon" maxlength="8" autocomplete="off"/><br /><div class="paytm_error-msg" id="error_coupon"></div>
			</div>
			<input onclick="validateAndApplyCoupon();" type="button" class="payNowButton" value="Apply Coupon" style="background: #0066ff; color: #fff; border: 0 none; padding:3px 4px;"/>
			</div>
                        <div style="width:100%; float:left; margin:10px 0; font-size:14px; line-height: 20px;">
                            
                            <p style="font-size: 16px;">Please click OK to continue</p>
                        </div>
  <!--     <a href="javascript:void(0);" onClick="window.open('/Online/OnlineFormConversionTracking/send/191564','_blank','toolbar=yes,resizeable=yes,menubar=yes,location=yes,scrollbars=yes');" class="orange-button" id="caSubmitBtn">OK</a>
-->
  <a href="/Online/OnlineFormConversionTracking/send/<?php echo $courseId;?>" target="_blank" onClick="validateAndApplyCoupon(<?=$courseId ?>);" class="orange-button" id="caSubmitBtn">OK</a>
  <a href="<?php echo SHIKSHA_HOME; ?>/mba/resources/application-forms" style="margin-left: 10px;">Return to application dashboard</a>
     
</div>
			<div class="paytm-tooltip" style="left: 58%;display: none;" id="paytm-wdgttooltip12">
			     <i class="paytm-sprite tooltip-pointer"></i>
			     <ul class="tooltip-list">
							     <li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
							     <li>You will get a mail from PayTM to claim your money</li>
			     </ul>
			</div>