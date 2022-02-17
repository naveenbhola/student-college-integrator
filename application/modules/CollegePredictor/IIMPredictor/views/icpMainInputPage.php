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
        'js'               =>array('ajax-api'),
        'css'              =>array('IIMPredictor', 'registration'),
        'jsFooter'         =>array('shikshaHomePage','common', 'iimPredictor', 'userRegistration'),
        'title'            => 'IIM & Non IIM Call Predictor – Check IIM Cut offs, CAT Cut offs, Predict Calls',
        'metaDescription'  => "Check CAT Cut offs and predict calls from IIMs and other Top MBA Colleges, whether your CAT score is 70 percentile, 80 percentile or 90 percentile. Check Fees, Placement Reviews, Admission, Shortlist Criteria and eligibility of all MBA Colleges",
        'metaKeywords'     => 'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
        'product'          => 'iimPredictor',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'canonicalURL'     => SHIKSHA_HOME.'/mba/resources/iim-call-predictor',
        'showBottomMargin' => false
        );
if($partnerFlag === true) {
    $headerComponents['partnerPage'] = $partner;
}

if(!($userId > 0)){
  $headerComponents['lazyLoadJsFiles'] = array('userRegistration');
}
?>

<?php $this->load->view('common/header', $headerComponents); ?>
<!-- Loader Starts-->
  <div class="loader-coll initial_hide">
       <div class="three-quarters-loader">Loading…</div>
  </div>
<!-- Loader ends-->


<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'> -->
    <div class="wrapper">

      <!-- Information Layer -->
      <div class="initial_hide" id="helpText">
          <div class="txt-layer" id="txt-layer">
             
          </div>
          <div class="txt-col">
                 <a href="javascript:void(0);">UG Marks Guidelines
                  <span id="txt-crs" class="txt-cross-icon">&times;</span>
                  <!-- <span id="txt-crs" ><i class="txt-crs"></i></span> -->
                </a>
                 
                    <ol class="a">
                        <li>Percentage of marks of an applicant who is yet to complete bachelor’s degree will be computed based on his/her latest available marks (for example – till 3rd year or 6th/7th semester).</li>
                        <li>The percentage obtained by the candidate in the bachelor’s degree would be based on the practice followed by the university/institution from where the candidate has obtained the degree. In case of the candidates being awarded grades/CGPA instead of marks, the equivalence would be based on the equivalence certified by applicant’s university/institution. In case the university/institution does not have any scheme for converting CGPA into equivalent marks, applicants can get equivalent percentage by dividing obtained CGPA with the maximum possible CGPA and multiplying the resultant with 100.</li>
                   </ol>
             </div>
         </div>
      <!-- Information Layer End-->
        <div class="iim_bg">
                <div class="iim_txtblock">
                  <div class="head_block">
                    <h1 class="fnt28">IIM & Non IIM Call Predictor - See IIM Cut offs & CAT Cutoffs</h1> 
                  </div>
                  <!-- <p class="fnt16 side_pad">Shiksha's IIM call predictor takes into account your profile, work-ex & scores to predict your eligibility & chances of getting a call.</p> -->
                  <h2 class="h2-iimtxt">Shiksha's call predictor takes into account your profile, work-ex & CAT <?php echo date('Y')-1; ?> scores to predict your eligibility & chances of getting a call from 250+ IIMs & Non IIMs</h2>
                  <div class="table_p clear_s">
                    <p>Prediction based on scores and profiles of CAT 2017 IIM & Non IIM call getters</p>
                    <p>Normalized for all Boards & Universities across India</p>
                    <p>Also see Eligibility, Shortlist Criteria & Cut Offs of 250+ IIMs & Non IIMs</p>
                  </div>
                </div>
        </div>
         <div class="tabs_border">
                <div class="iim_container">
                   <div class="clear_s">
                      <div class="iim_tabs">
                          <a class="active sticky-tabs" id="sticky-tab1">1.Enter Personal Details</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab2">2.Enter Academic Details</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab3">3.Enter CAT/Expected Score</a>
                          <a class="sticky-tabs" href="javascript:void(0)" id="sticky-tab4">4.Expected Calls</a>
                          <span class="slide_bar"></span>
                      </div>

                   </div>
                </div>
              </div>

        <section class="desktop-predictor-col">
            <div class="container pLR0">
            <form autocomplete="off">
                 <div class="col-lg-9 pL0" id="iimPredictorForm">
                      <?php // $this->load->view('IIMPredictor/icpLandingPage',$data); ?>
                      <?php // $this->load->view('IIMPredictor/icpNavigation'); ?>
                      <?php $this->load->view('IIMPredictor/personalDetails'); ?>
                      <?php $this->load->view('IIMPredictor/academicDetails'); ?>
                      <?php // $this->load->view('IIMPredictor/CATDetails'); ?>
                      <?php $this->load->view('IIMPredictor/checkIfCatScore'); ?>
                      <?php $this->load->view('IIMPredictor/CATScore'); ?>
                      <?php $this->load->view('IIMPredictor/interimOutput'); ?>
                      <div id="icpregistration">

                      </div>
                 </div>
            </form>
                <!-- left panel ends -->
                 <!-- right panel start -->
                 <aside class="ranking-section">
                    <div class="aside col-lg-3 pL0">
                        <div class="left_nav">
                          <div class="college-widget">
                            <div class="college-banner" id="rightWidget"></div>
                          </div>
                        </div>
                    </div>
                </aside>
                <!-- right panel ends here -->
                <p class="clr"></p>
            </div>
        </section>
    </div>
<script type="text/javascript">
    var pageType = 'inputScreens';
    var getStringType = '<?php echo $this->input->get("type"); ?>';
    var GA_currentPage = "ICP INPUT PAGE";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var ga_commonCTA_name = "_ICP_INPUT_PAGE_DESK";
    var groupId = '<?php echo $eResponseData['groupId'];?>';
    var examName = 'CAT';
    
    if(isUserLoggedIn){
      setCookie('isICPUser',1, 30);
    }else{
      setCookie('isICPUser',0, 30);
      setCookie('isZeroResult','',30);
    }
</script>
<?php $this->load->view('common/footer'); ?>

