<div class="brochure-form 0collapsed">
	<h5 class="font-18" style="cursor: auto;">
		<div class="icon-wrap" style="width:48px"><i class="sprite-bg best-institute-icon2"></i></div>
			<p>Find the best institute for yourself</p>
		<div class="sprite-bg pointer"></div>
	</h5>
	<?php 
	if($userLoggedIn=='false')
	{
		$girlImageClass= 'girl-reg-brochure';
		$applyPadding = "";
	}
	else {
		$girlImageClass= 'girl-brochure';
		$applyPadding= 'style="padding-bottom: 65px;"';
	}
	?>

	<div class="form-wrap form-width2" <?php echo $applyPadding;?>>
		<p class="font-14">We need a few details from you to suggest you relevant institutes</p>
		<?php echo Modules::run('registration/Forms/LDB',$form,'findInstitute',array('registrationSource'
=> 'Registration_listingPageNationalBottom','referrer' => $_SERVER['HTTP_REFERER'])); ?>
	</div>
	<div class="<?php echo $girlImageClass;?> flRt"></div>
	<div class="clearFix"></div>
</div>
