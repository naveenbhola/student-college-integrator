<div style="width:410px;height:250px;margin-bottom:30px;">
<?php
if ( $pagename == 'it') {
$bannerProperties = array(
			'pageId'=>'IT_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'testprep') {
$bannerProperties = array(
			'pageId'=>'TESTPREP_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'animation') {
$bannerProperties = array(
			'pageId'=>'ANIMATION_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'hospitality') {
$bannerProperties = array(
			'pageId'=>'HOSPITALITY_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'science') {
$bannerProperties = array(
			'pageId'=>'SCIENCE_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'bba') {
$bannerProperties = array(
			'pageId'=>'BBA_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'clinical_research') {
$bannerProperties = array(
			'pageId'=>'CLINICALRESEARCH_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'fashion_design') {
$bannerProperties = array(
			'pageId'=>'FASHIONDESIGN_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
if ( $pagename == 'mass_communications') {
$bannerProperties = array(
			'pageId'=>'MASSCOMM_MARKETING',
			'pageZone'=>'LEFT'
		    );
}
$this->load->view('common/banner.php', $bannerProperties);
?>
</div>
<div><?php echo $config_data_array['TEXT_BELOW_LEFT_PANEL']; ?></div>