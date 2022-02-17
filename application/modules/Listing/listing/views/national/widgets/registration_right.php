<div class="brochure-form collapsed">
	<?php $unique_attr = "LISTING_".$listing_type."_PAGES/FIND_BEST_INSTITUTE_RIGHT" ?>
	<h5 uniqueattr="<?php echo $unique_attr;?>" onmouseleave="pushCustomVariable('<?php echo $unique_attr;?>');">
		<div class="icon-wrap" style="width:48px">
		<i class="sprite-bg best-institute-icon2"></i></div>
		<p>Find the best institute for yourself</p>
		<div class="sprite-bg pointer"></div>
	</h5>
	<div class="form-wrap" style="display: none;">
		<p class="font-14">We need a few details from you to suggest you
			relevant institutes</p>
		<?php echo Modules::run('registration/Forms/LDB',$form,'findInstitute',array('registrationSource'
=> 'Registration_listingPageNationalRight','referrer' => $_SERVER['HTTP_REFERER'])); ?>

	</div>
<div class='clearFix'></div>
</div>
<style>

#registerFreeFormIndia .orange-button {

	background-color: #F29341;
    border-radius: 6px 6px 6px 6px !important;
    color: #FFFFFF !important;
    font: bold 12px Tahoma,Geneva,sans-serif !important;
    height: inherit;
    border: 1px none;
    cursor: pointer;
    display: inline-block;
    padding: 5px 15px;
    
}
</style>