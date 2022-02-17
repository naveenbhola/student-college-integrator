 <?php
    $footerComponents = array(
			    'js'                => array('countryHome')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>

<script>
$j(document).ready(function($j) {
      applyTinyscrollbarOnGuideWidget();
      initializeFeaturedCollegesSlider();
      applyTinyscrollbarOnGuideWidget();
});    

</script>