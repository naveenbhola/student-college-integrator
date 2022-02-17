<div class="pink-box shadow-box section-cont">
	<div class="sprite-bg cols-title">
		<span class="sprite-bg inst-icn"></span>
		<p>Find the best institute for yourself</p>
	</div>
	<div class="padd-10">
	<p>We need a few details from you to suggest you relevant institutes </p>
	<div class="spacer10 clearFix"></div>
		<?php echo Modules::run('registration/Forms/LDB',$form,'findInstitute',array('registrationSource'
=> 'Registration_listingPageRight','referrer' => $_SERVER['HTTP_REFERER'])); ?>
	</div>
	<div class="bottom-girl"></div>
	<div class="clearFix"></div>
</div>
