<?php
	$footerComponents = array(
		'nonAsyncJSBundle'=>'sa-scholarship-detail-page',
		'asyncJSBundle'=>'async-sa-scholarship-detail-page',
		);
	$this->load->view('studyAbroadCommon/saFooter',$footerComponents);

?>
<script type="text/javascript">
var allCountryList = "<?php echo implode(', ', $applicableCountries); ?>";
var countCountry = "<?php echo count($applicableCountries)-5; ?>";
var cntrylst = "<?php echo implode(', ', array_slice($applicableCountries,0,5)); ?>";
var applicableNationalities = "<?php echo implode(', ', $applicableNationalities); ?>";
var countNationalities = "<?php echo count($applicableNationalities)-1; ?>";
var applicableNat = "<?php echo $applicableNationalities[0]; ?>";
$j(window).load(function(){
	initDetailPage();
});
</script>