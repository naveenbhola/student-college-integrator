<?php 
ob_start('compressHTML');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.


$headerComponents = array(
		'css'   =>      array('IIMPredictor'),
		'js' => array('iimPercentile'),
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>$boomr_pageid,
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'metaKeywords' => $m_meta_keywords
);

$headerComponents['shikshaCriteria'] = array();
$this->load->view('common/header', $headerComponents);
$this->load->view('messageBoard/desktopNew/toastLayer');
?>

<?php 
	if(isset($catScore)){
	    $this->load->view('IIMPredictor/iimPercentile//iimPercentileResultPage');
	}
	else{
		$this->load->view('IIMPredictor/iimPercentile//iimPercentileForm');
	}
	?>

<?php
echo modules::run('comparePage/comparePage/generateCollegeCompareTool');
?>

<?php $this->load->view('common/footer'); ?>

<script>
	var GA_currentPage = 'IIM_PERCENTILE_DESKTOP';
	var ga_user_level = "DESKTOP";
	var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('searchTuple'); ?>';
	var groupId = '<?php echo $eResponseData['groupId'];?>';
    var examName = 'CAT';
	myCompareObj = new myCompareClass();
	$j(document).ready(function () {
	    initIIMPercentilePredictor();
	});
</script>
<div id="opacityLayer"></div>

<div id="googleRemarketingDiv" style="display: none;"></div>


<?php 
ob_end_flush();
?>