<?php 
$tempJsArray = array('user','ajax-api','imageUpload');     
$headerComponents = array(
    'css'              =>  array('campus-representative'),
    'js'               =>  array('common','facebook','CollegeReview'),
    'jsFooter'         =>  $tempJsArray,
    'title'            =>  $title,
    'metaDescription'  =>  $description,
    'product'          =>  'CollegeReviewForm',
    'showBottomMargin' =>  false,
    'displayname'      =>  (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
    'showUI'           =>  false,
    'canonicalURL'     =>  $canonicalURL,
    'invisibleGNB'     =>  true
);

$this->load->view('common/header', $headerComponents);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("ent-home"); ?>" type="text/css" rel="stylesheet" />

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');
fbq('init', '639671932819149');
fbq('track', 'PageView');
</script>

<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script>
	$j = jQuery.noConflict();
</script>
