<?php
ob_start('sanitize_output');
$headerComponents = array(
        'js'               =>array(),
        'css'              =>array('shikshaHomepage'),
        'title'            => 'Higher Education in India | Shiksha.com',
        'metaDescription'  => 'Explore thousands of colleges and courses on India\'s leading higher education portal - Shiksha.com. See details like fees, admission process, reviews and much more.',
        'metaKeywords'     => 'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
        'product'          => 'home',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'canonicalURL'     => SHIKSHA_HOME.'/',
        'showGutterBanner' => '1',	//To show Gutter banner only on the Shiksha Homepage
        'showSniperBanner' => '1', 	//To show promotional banner for study abroad
        'showBottomMargin' => false,
        'lazyLoadJsFiles'  => array(),
        'lazyLoadUserRegistrationCss' => true,
        'cssFilePlugins' => array('userRegistrationDesktop')
        );
if($partnerFlag === true) {
    $headerComponents['partnerPage'] = $partner;
}
?>
<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_NATIONAL_HOMEPAGE',1); 
?>

<script>currentPageName = 'Home Page';</script>
<?php 
	$this->load->view('home/homepageRedesign/homepage_redesign');	
?>

</div>
<script>
    var is_user = "<?php echo $logged; ?>";
    var first_name = escape("<?php if(is_array($validateuser['0']))echo addslashes($validateuser['0']['firstname']); ?>");
    var mobile_no = "<?php if(is_array($validateuser['0'])) echo $validateuser['0']['mobile']?>";
    var email_no = "<?php  if(is_array($validateuser['0'])) echo $validateuser['0']['cookiestr']?>";
    email_no = email_no.split('|')['0'];
    var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
</script>

<script>overlayViewsArray.push(new Array('home/registrationCityOverlay','userPreferenceCategoryCity'));</script>
 <div id="tags-layer" class="tags-layer"></div>
 <div class="an-layer an-layer-inner" id="an-layer">
      <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting');?>
 </div>
<?php $this->load->view('common/footer'); ?>
<?php /*
<!-- Google Code for Homepage -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<!--
<script type="text/javascript">
function trackEventByGAMAP(category_name_for_event,eventAction,eventLabel,link) {
if(!category_name_for_event) {
    category_name_for_event = currentPageName;
}
if(typeof(pageTracker)!='undefined' && typeof(category_name_for_event)!='undefined' && category_name_for_event!=null) {
    pageTracker._trackEvent(category_name_for_event, eventAction, eventLabel);
        setTimeout(function (){window.location = link;},100);
}
return true;
}
 <![CDATA[ 
var google_conversion_id = 1053765138;
var google_conversion_label = "EufCCMLbrQQQkty89gM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
 ]]> 
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1053765138/?value=0&amp;label=EufCCMLbrQQQkty89gM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
--> */ ?>

<?php //To open unified layer from mailers ?>
<?php $this->load->view('home/homepageRedesign/marketing_form_mailer_processor'); ?>
<?php $this->load->view('home/homepageRedesign/profile_complete_widget'); ?>
<?php $this->load->view('home/homepageRedesign/mailer_report_spam_widget'); ?>
<?php $this->load->view('home/homepageRedesign/mailer_unsubscribe_widget'); ?>
<script type="text/javascript">
var question_index =2, timer = "", timeout = "", timeout1 = "";
$j(function(){
  initHomePageDocument();
});
lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("quesDiscPosting");?>');
var quesDiscPostingJsFilename = '<?php echo getJSWithVersion("quesDiscPosting");?>';

var isHomePageLoadGTM = true;
function enableHomePageGTM(){
    if(isHomePageLoadGTM){
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.defer=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})
(window,document,'script','dataLayer','GTM-5FCGK6');
        isHomePageLoadGTM = false;
    }
}
window.addEventListener('scroll', function(e) {
  if(window.scrollY>0){
        enableHomePageGTM();
  }
});
document.body.addEventListener('click', enableHomePageGTM, true); 
//enableHomePageGTM();
</script>
