<div class="paytm-message-sec">
<?php if(isset($referralName) && !in_array($referralName, array('ownCode','default')) && $coupon !=''){ // referral?>

<div>Your friend <strong><?php echo $referralName?></strong> has referred you to apply to colleges on Shiksha.com.</div>
<div style="margin-top:6px; margin-bottom:0;">Use code <div class="code-area"><span class="unique-code"><?php echo $coupon;?></span></div> when you apply, both <strong><?php echo $referralName?></strong> and you will get <strong>Rs. 100</strong> on <i class="sprite paytm-logo"></i> after your submission.</div>

<?php }else if(isset($referralName) && $referralName == 'ownCode' && $coupon !=''){ //owncode ?>

<div>Use code <div class="code-area"><span class="unique-code"><?php echo $coupon;?></span></div> when you apply on Shiksha.com and get <strong>Rs. 100</strong> on <i class="sprite paytm-logo"></i></div>
<div style="margin-top:6px; margin-bottom:0;">Use your code every time you apply or share it with friends and get <strong>Rs. 100</strong> on <i class="sprite paytm-logo"></i> each time your friend applies.</div>

<?php }else{ // default ?>

<div>Use code <div class="code-area"><span class="unique-code"><?php if($coupon !=''){echo $coupon;}else{ echo 'SHK101';}?></span></div> when you apply on Shiksha.com and get <strong>Rs. 100</strong> on <i class="sprite paytm-logo"></i></div>
<div style="margin-top: 6px;margin-bottom:0;">Once you apply you will get your unique code. Use your code every time you apply or share it with friends and get <strong>Rs. 100</strong> on <i class="sprite paytm-logo"></i> each time your friend applies.</div>

<?php }?>
</div>

