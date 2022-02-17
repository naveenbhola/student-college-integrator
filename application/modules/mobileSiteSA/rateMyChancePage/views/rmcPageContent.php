<?php
	// prepare title for the layer
if(is_array($loggedInUserData)  && $loggedInUserData['userId']>0){
    $layerTitle = "Update your profile";
}else{
    $layerTitle = $referrerPageTitle;
}
?>
<div class="header-unfixed">
	<div class="layer-header">
		<a href="<?=($referrerPageURL)?>" class="back-box"><i class="sprite back-icn"></i></a> <p style="text-align:center"><?=($layerTitle);?></p>
	</div>
</div>
<section class="content-wrap clearfix" data-enhance="false" >
	<?php if($loggedInUserData=== false){ ?>
	<nav class="tabs">
		<ul>
			<li class="active reg-tab" id="reg-tab"><a href="javascript:void(0);" onclick="showRegistrationFormTab(this);">Register To Start</a></li>
			<li id="login-tab" class="login-tab"><a href="javascript:void(0);" id="abroadLoginTab" onclick="showLoginFormTab(this);engageDownloadBrochureWithLogin = 1;">Already Registered?</a></li>
		</ul>
	</nav>
	<?php } ?>
	<div style="margin:0 5px;">
		<div class="counslor-evaluation-sec" id="joinShikshaLabel">
        <div class="clearfix" style="margin:5px 0 5px 5px;"><strong class="font-13">Rate your Chance</strong></div>
			<p class="evaluate-title">An expert Shiksha counselor will review your profile and give an assessment of your admission chances for admission in <?=$brochureDataObj['universityName']." (".$brochureDataObj['destinationCountryName'].")"?></p>
			<div style="width:100%; text-align:center">
				<img src="/public/images/SASingleSignup/RMC_03.jpg" auto alt="rate-my-chance">
			</div>
		</div>
	</div>
	<?php $this->load->view('rateMyChancePage/rmcPageForm'); ?>

</section>


