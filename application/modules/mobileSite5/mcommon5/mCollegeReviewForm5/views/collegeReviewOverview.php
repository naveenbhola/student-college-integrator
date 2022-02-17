<?php
$headerComponent = array(
        //'m_meta_title' => 'Search Institutes and Courses on Shiksha',
	'collegeReviewPage'=>'true',
        'pageName'=>'collegeReview',
        'canonicalURL' => $canonicalURL,
        'mobilePageName' => 'CollegeReviewForm'
);
$this->load->view('/mcommon5/header',$headerComponent); 
$rateSectionHeading = "Rate your UG College on the following parameters";

?>
<?php //$this->load->view('examPages/examPageHighlightBar'); ?>
<?php //if($pageType == "home" ) $this->load->view('examPages/examPageTileNavigation'); ?>

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

<?php 	
$viewData = array('pageType'=>$pageType);
if($pageType=='campusRep'){
	$this->load->view('mCollegeReviewForm5/campusRepCollegeReviewWrapper',$viewData);
}else{
 	$this->load->view('mCollegeReviewForm5/collegeReviewWrapper',$viewData);
 	} ?>

<?php //loadBeaconTracker($beaconTrackData);?>
<?php //$this->load->view('mcommon5/footer');?>
<?php $this->load->view('mcommon5/footerCommonCode'); ?>


<script type="text/javascript">
var RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE = "<?=RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE?>";
     var isShikshaInstitute = "<?=$isShikshaInstitute?>",
        selectedlocationId = '',
        selectedCourseId = '';

    $(document).ready(function () {
        collegeReviewReadyCalls();
        <?php if($isShikshaInstitute == 'YES'){?>
            selectedlocationId = '<?=$selectedlocationId;?>';
            selectedCourseId   = '<?=$selectedCourseId;?>';
            setTimeout(function(){updateCollegeAutosuggestorData('<?=base64_encode($instituteName)?>','<?=$instituteIdentifier?>');},500);
        <?php } ?>
    });
</script>

<style>
.loader-col-regForm {
    position: fixed;
/*    background: rgba(0, 0, 0, 0.45) none repeat scroll 0% 0%;
*/    width: 100%;
    height: 100%;
    top: 0;
/*    left;
    0px;*/
    right: 0px;
    bottom: 0px;
    z-index: 999999;
}
.three-quarters-loader-regForm {
    background: url("<?=IMGURL_SECURE;?>/public/mobile5/images/ShikshaMobileLoader.gif");
    box-sizing: border-box;
    display: inline-block;
    position: relative;
    overflow: hidden;
    text-indent: -9999px;
    width: 80px;
    height: 80px;
    position: fixed;
    left: 46%;
    top: 40%;
    margin-left: -24px;
    margin-top: -24px;
    box-shadow: 0px 0px 8px 2px #B5B5B5;
    border-radius: 50%;
}

</style>