<?php
$yearValue = '';
$curDate  = date('j');
$curMonth = date('n');
if($curMonth >= 4 && $curDate >= 1){
  $yearValue = date('Y').'-'.(date('y')+1);
}else{
  $yearValue = (date('Y')-1).'-'.date('y');
}
$headerComponents = array(
        'js'               =>array(ajax-api),
        'css'              =>array('IIMPredictor'),
        'jsFooter'         =>array('shikshaHomePage','common', 'iimPredictor'),
        'title'            => 'IIM & Non IIM Call Predictor - Predict Calls, Check CAT '.(date('Y')-1).' Cut Offs, Admission & Shortlist Criteria',
        'metaDescription'  => "Predict calls from best IIMs and non IIMs for your CAT score, whether 60 percentile, 70 percentile, 80 percentile or 90 percentile. Check Reviews, Cut Offs, Admission & Shortlist Criteria of all Colleges",
        'metaKeywords'     => 'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
        'product'          => 'iimPredictor',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'canonicalURL'     => SHIKSHA_HOME.'/mba/resources/iim-call-predictor',
        'showGutterBanner' => '1',	//To show Gutter banner only on the Shiksha Homepage
        'showSniperBanner' => '1', 	//To show promotional banner for study abroad
        'showBottomMargin' => false,
        'lazyLoadJsFiles'  => array('userRegistration')
        );
if($partnerFlag === true) {
    $headerComponents['partnerPage'] = $partner;
}
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'> -->
    <div class="wrapper">
      <div class="tabs_border">
                <div class="iim_container">
                   <div class="clear_s">
                      <div class="iim_tabs">
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab1">1.Enter Personal Details</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab2">2.Enter Academic Details</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab3">3.Enter CAT/Expected Score</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab4">4.Expected Calls</a>
                          <span class="active slide_bar" style='left: 655px; width: 152px;'></span>
                      </div>

                   </div>
                </div>
              </div>
        <section class="desktop-predictor-col">
            <div class="container pLR0">
                 <div class="col-lg-9 pL0" id="iimPredictorForm">
                      <?php //$this->load->view('IIMPredictor/icpNavigation'); ?>
                    <?php $this->load->view('IIMPredictor/personalDetails'); ?>
                    <?php $this->load->view('IIMPredictor/academicDetails'); ?>
                    <?php // $this->load->view('IIMPredictor/CATDetails'); ?>
                    <?php $this->load->view('IIMPredictor/checkIfCatScore'); ?>
                    <?php $this->load->view('IIMPredictor/CATScore'); ?>
                    <?php $this->load->view('IIMPredictor/interimOutput'); ?>
                      <?php $this->load->view('IIMPredictor/icpEligibleIneligibleOutputPage'); ?>

                 </div>
                <!-- right panel start -->
                    <?php $this->load->view('IIMPredictor/icpSideWidgetPage'); ?>
                <!-- right panel ends here -->
                <p class="clr"></p>
            </div>
        </section>
    </div>

<script type="text/javascript">
    var pageType = 'outputScreens';
    currentPageName= 'IIMPredictorOutputPage';
    var GA_currentPage = "ICP OUTPUT PAGE";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var ga_commonCTA_name = "_ICP_OUTPUT_PAGE_DESK";
</script>
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
$j(document).ready(function() {
    bindCTA();
});
</script>
<?php if($this->input->get('registration') == 'new'){ ?>
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
            <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
            <noscript>
            <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
            </div>
            </noscript>
	    -->


            <!-- Facebook Pixel Code -->
	    <!--
            <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','//connect.facebook.net/en_US/fbevents.js');

            fbq('init', '639671932819149');
            fbq('track', "CompleteRegistration");
            </script>
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1" /></noscript>
	    -->
            <!-- End Facebook Pixel Code -->

<?php } ?>
