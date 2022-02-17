<div class="paytm-message-sec">
<?php if(isset($referralName) && !in_array($referralName, array('ownCode','default')) && $coupon !=''){ // referral?>
<p>Your friend <strong><?php echo $referralName?></strong> has referred you to buy applications on Shiksha.com</p>
<div style="margin-bottom:0;">Use code <div class="code-area"><span class="unique-code"><?php echo $coupon;?></span></div> when you apply. Both you and <?php echo $referralName?> will get <strong>Rs. 100</strong> on <i class="paytm-sprite paytm-large-logo"></i> <span class="que-mark" style="position: relative" onmouseout="$j('#paytm-wdgttooltip').hide();" onmouseover="paytmWidgettooltip<?=$viewType?>(this)">?</span>
<div class="paytm-tooltip" style="left: 58%;display: none;" id="paytm-wdgttooltip">
			     <i class="paytm-sprite tooltip-pointer"></i>
			     <ul class="tooltip-list">
							     <li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
							     <li>You will get a mail from PayTM to claim your money</li>
			     </ul>

</div>
<p style="margin-top:6px;">Once you submit, your unique code will be generated.</p>
<ul class="used-code-list">
			     <li>Use that code to get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime you apply on Shiksha.</li>
			     <li>Share that code with a friend and get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime your friend applies on Shiksha with your code.</li>
</ul>
</div>

<?php }else if(isset($referralName) && $referralName == 'ownCode' && $coupon !=''){ //owncode ?>

<div style="margin-bottom:0;">Use code <div class="code-area"><span class="unique-code"><?php echo $coupon;?></span></div> before applying to get <strong>Rs. 100</strong> credited to your <i class="paytm-sprite paytm-large-logo"></i> <span class="que-mark" style="position: relative" onmouseout="$j('#paytm-wdgttooltip').hide();" onmouseover="paytmWidgettooltip<?=$viewType?>(this)">?</span>accounts</p>

<div class="paytm-tooltip" style="left: 58%; display: none;" id="paytm-wdgttooltip">
			     <i class="paytm-sprite tooltip-pointer"></i>
			     <ul class="tooltip-list">
							     <li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
							     <li>You will get a mail from PayTM to claim your money</li>
			     </ul>

</div>
<ul class="used-code-list">
			     <li>Use your code to get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime you apply on Shiksha.</li>
			     <li>Share your code with a friend and get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime your friend applies on Shiksha with your code.</li>
</ul>
</div>
<?php }else{ // default ?>
<div style="margin-bottom:0;">Use code <div class="code-area"><span class="unique-code"><?php if($coupon !=''){echo $coupon;}else{ echo 'SHK101';}?></span></div> before applying to get <strong>Rs. 100</strong> credited to your <i class="paytm-sprite paytm-large-logo"></i> <span class="que-mark" style="position: relative" onmouseout="$j('#paytm-wdgttooltip').hide();" onmouseover="paytmWidgettooltip<?=$viewType?>(this)">?</span>accounts</p>

<div class="paytm-tooltip" style="left: 58%;display: none;" id="paytm-wdgttooltip">
			     <i class="paytm-sprite tooltip-pointer"></i>
			     <ul class="tooltip-list">
							     <li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
							     <li>You will get a mail from PayTM to claim your money</li>
			     </ul>

</div>
<p style="margin-top:6px;">Once you submit, your unique code will be generated.</p>
<ul class="used-code-list">
			     <li>Use that code to get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime you apply on Shiksha.</li>
			     <li>Share that code with a friend and get Rs.100 in your <i class="paytm-sprite paytm-sml-logo"></i> account everytime your friend applies on Shiksha using your code.</li>
</ul>
</div>
<?php }?>
</div>