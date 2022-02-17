<?php 
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$tempJsArray = array('myShiksha','user');
$headerComponents = array(
		'css'   =>      array('eng-predictor'),
		'js' => array('common','ajax-api','imageUpload'),
		'jsFooter'=>    $tempJsArray,
		'title' =>      $meta_title,
		'metaDescription' => $meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'rankPredictor',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

$headerComponents['shikshaCriteria'] = array();
$this->load->view('common/header', $headerComponents);
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>

<div id="top-nav" style="visibility:hidden;height:0px"></div>

<?php $this->load->view('rankCollegeInnerPage');?>

<?php $this->load->view('common/footer'); ?>

<script>publishBanners();</script>

<div id="opacityLayer"></div>

<div id="googleRemarketingDiv" style="display: none;"></div>

<?php if(isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0 && isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] !=''){?>
<!-- Google Code for registration Conversion Page -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en_GB";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "O3WQCOaXRRCS3Lz2Aw";
var google_conversion_value = 1.00;
var google_conversion_currency = "INR";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php }?>
