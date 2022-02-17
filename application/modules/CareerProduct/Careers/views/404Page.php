<?php
$headerComponents = array(
   'css'	=>	array('common_new','careers','category-styles'),
   'js' 	=>	array('common','ajax-api'),
   'callShiksha'=>1,
   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   'notShowSearch' => true,
   'showBottomMargin' => false,
   'showApplicationFormHeader' => false
);

$this->load->view('common/header', $headerComponents);
$this->load->view('common/calendardiv');
?>
<div class="career-wrapper">
	<div class="career-child-content">
		<div class="no-result-found">
			<span class="no-result-icn"></span>
			<h4>Page Not Found<br />
			<p>Sorry, the page that you are trying to access is not available or has been moved.<br />
			Go back to <span><a href="<?php echo CAREER_HOME_PAGE;?>">Career-Central</a></span> on Shiksha.com
			</p>
			</h4>
		</div>
	</div>
</div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 
