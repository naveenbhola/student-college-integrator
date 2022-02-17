<?php
$headerComponents = array(
        //'js'=>array('common','homePage','header','user'),
		//'js'=>array('homePage','shikshaAutosuggest'),
        'js'=>array('homePage'),
	'css'=>array('shiksha_common'),
        'jsFooter'=>array('ana_common','user','common'),
        'title'	=>	'Education India - Search Colleges, Courses, Institutes, University, Schools, Degrees - Forums - Results - Admissions - Shiksha.com',
        'metaDescription' => 'Search Colleges, Courses, Institutes, University, Schools, Degrees, Education options in India. Find colleges and universities in India & abroad. Search Shiksha.com Now! Find info on Foreign University, question and answer in education and career forum. Ask the education and career counselors.',
        'metaKeywords'	=>'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
        'product' => 'home',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
	'canonicalURL' => SHIKSHA_HOME.'/',
	'showGutterBanner' => '1'	//To show Gutter banner only on the Shiksha Homepage
        );
if($partnerFlag === true) {
    $headerComponents['partnerPage'] = $partner;
}
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<style>
.loaderBg{background: url(https://www.shiksha.com/public/images/loader.gif) no-repeat center;}
</style>
<script>
currentPageName = 'Home Page';
</script>
<?php $this->load->view('home/homePageFirstPanel'); ?>
<div class="defaultAdd lineSpace_11">&nbsp;</div>
<?php $this->load->view('home/homePageSecondPanel'); ?>
<div class="defaultAdd lineSpace_11">&nbsp;</div>
<div align="center">
<?php
$bannerProperties = isset($bannerProperties) ? $bannerProperties : array('pageId'=>'HOME', 'pageZone'=>'FOOTER');
$this->load->view('common/banner',$bannerProperties); 
?>
</div>
<div class="defaultAdd lineSpace_11">&nbsp;</div>
<script>overlayViewsArray.push(new Array('home/registrationCityOverlay','userPreferenceCategoryCity'));</script>
<?php $this->load->view('common/footerNew'); ?>
<!--
<style>
.shImg2{background:url(/public/images/homeShikImg_2.jpg) no-repeat}
.shImg3{background:url(/public/images/homeShikImg_3.jpg) no-repeat}
</style>
-->
<script>
//addOnBlurValidate(document.getElementById('marketingUser')); 
//rotateImages(generateShowCaseImagesPool(),showCasingImage, 0);
</script>

<!-- Google Code for Homepage Remarketing List Start -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 984794453;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "666666";
var google_conversion_label = "nylTCLv_1wIQ1YrL1QM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src=" http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src=" http://www.googleadservices.com/pagead/conversion/984794453/?label=nylTCLv_1wIQ1YrL1QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<!-- Google Code for Homepage Remarketing List End -->

<!-- Google Code for Homepage List Remarketing List Start Ticket # 305 -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "666666";
var google_conversion_label = "anOJCMKfkAIQkty89gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src=" http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src=" http://www.googleadservices.com/pagead/conversion/1053765138/?label=anOJCMKfkAIQkty89gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<!-- Google Code for Homepage List Remarketing List End Ticket # 305 -->

