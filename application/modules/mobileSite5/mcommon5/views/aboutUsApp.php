<!DOCTYPE html>
  <html>
<head>
<!-- Load CSS files -->
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mobileApp",'nationalMobile'); ?>" >
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion("jquery.mobile-1.4.5",'nationalMobileVendor'); ?>" >  
<!-- Load JS files -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.mobile-1.4.5.min","nationalMobileVendor"); ?>"></script>
  
<meta charset="utf-8">
<title>About Us</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>

<body>
<div class="mobile-container" data-role="page" data-enhance="false">
  <div class="cont-content" data-role="main">
    <section class="wht-not-do" style="padding-top:0.5em;">
     <div class="user-txt user-point-content">
	  <div style="text-align: center; margin:5px 0;"><i class="mbl shiksha-image"></i></div>
          <p>Shiksha is a one-stop-solution making course and college selection easy for students looking to pursue undergraduate (UG) and postgraduate (PG) courses in India and abroad; also accessible to users on the move through the website's mobile site. Launched in 2008, Shiksha belongs to Info Edge (India) Ltd, the owner of established brands like Naukri.com, 99acres.com, Jeevansathi.com, among several others. With this strong brand pedigree, Shiksha offers its users the unique privilege of customised tools like Alumni Employment Statistics that includes salary data powered by Naukri.com. </p>
	  <p>Our website is a repository of reliable and authentic information for over 14,000 institutions, 40,000 plus courses and has a registered data base of more than 3.5 million students. We offer specific information for students interested in UG/PG courses in India (shiksha.com) and Abroad (studyabroad.shiksha.com) across the most popular educational streams - Management; Science & Engineering; Banking & Finance; Information Technology; Animation, VFX, Gaming & Comics; Hospitality, Aviation & Tourism; Media, Films & Mass Communication; Design; Medicine, Beauty & Health Care; Retail; Arts, Law, Languages & Teaching; and Test Preparation</p>
	  <p>Education seekers get a personalised experience on our site, based on educational background and career interest, enabling them to make well informed course and college decisions. The decision making is empowered with easy access to detailed information on career choices, courses, exams, colleges, admission criteria, eligibility, fees, placement statistics, rankings, reviews, scholarships, latest updates etc as well as by interacting with other Shiksha users, experts, current students in colleges and alumni groups. We have introduced several student oriented products and tools like Career Central, Common Application Form, Top Colleges, College Compare, Alumni Employment Stats, Campus Connect, College Reviews, College Predictors, MyShortlist and Shiksha Cafe. </p>
	  <p>Our active ask and answer community called Shiksha Cafe has over 1000 experts answering career and college related queries. Students can ask questions, participate in discussions and stay updated with latest news, articles related to their education interest. Shiksha is India's smartest college gateway that blends higher education related domain knowledge, with technology, innovation, and credibility to give students personalised insights to make informed career, course and college decisions. </p>
       </div>
       
       <a href="#studentHelpline" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont"  onclick="myInterface.setTitle('Student Helpline');">
         <div class="div-left">
	    <div class="wht-txt" style="line-height:40px; padding: 1em; font-size:17px !important">
		<p style="line-height: 22px;">Student Helpline</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
       
        <a href="#termsCondition" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont" onclick="myInterface.setTitle('Terms and conditions');">
         <div class="div-left">
	  <div class="wht-txt" style="line-height:40px; padding: 1em; font-size:17px !important">
	    <p style="line-height: 22px;">Terms and conditions</p>
	   </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
       
       <a href="#privacy" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont"  onclick="myInterface.setTitle('Privacy Policy');">
         <div class="div-left">
	    <div class="wht-txt" style="line-height:40px; padding: 1em; font-size:17px !important">
		<p style="line-height: 22px;">Privacy Policy</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>

       <a href="#cookie" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont"  onclick="myInterface.setTitle('Cookie Policy');">
         <div class="div-left">
	    <div class="wht-txt" style="line-height:40px; padding: 1em; font-size:17px !important">
		<p style="line-height: 22px;">Cookie Policy</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
       
       <a href="#legal" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont"  onclick="myInterface.setTitle('Legal');">
         <div class="div-left">
	    <div class="wht-txt" style="line-height:40px; padding: 1em; font-size:17px !important">
		<p style="line-height: 22px;">Legal</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
    </section>
  </div>
  </div>
  
  <div class="mobile-container" data-role="page" id="studentHelpline" class="of-hide" data-enhance="false">
  
    <section class=" cont-content wht-not-do" style="padding-top:1em;">
       <div class="user-txt user-point-content">
	<div style="text-align: center"><a href="#" class="helpline-bg"><i class="mbl helpline-icon"></i></a></div>
	<p>Call us between</p>
	<p>09:30 AM to 06:30 PM,</p>
	<p>Monday to Friday</p>
	<p>Call::<strong style="font-weight:bold;">1800-103-5547</strong></p>
	
	<div><a href="tel:18001035547" class="call-now-btn">Call Now</a></div>
       </div>
    <section>
   </div>
  
  
<div class="mobile-container" data-role="page" id="termsCondition" class="of-hide" data-enhance="false">
    <section class=" cont-content wht-not-do" style="padding-top:0;">
    	<?php $this->load->view('shikshaHelp/termConditionContent',array("platform"=>"app"));?>
    </section>
</div>
  
<div class="mobile-container" data-role="page" id="privacy" class="of-hide" data-enhance="false">
  <section class=" cont-content wht-not-do" style="padding-top:0;">
  		<?php $this->load->view('shikshaHelp/privacyContent',array("platform"=>"app"));?>
  </section>
</div>
  
<div class="mobile-container" data-role="page" id="cookie" class="of-hide" data-enhance="false">
  <section class=" cont-content wht-not-do" style="padding-top:0;">
  	<?php $this->load->view('shikshaHelp/cookieContent');?>
  </section>
</div>

<div class="mobile-container" data-role="page" id="legal" class="of-hide" data-enhance="false">
  <section class=" cont-content wht-not-do" style="padding-top:1em;">
  <div class="user-txt user-point-content">  
  <p>We have used following open source libraries in app. The terms of the Apache License can be viewed at: <a href="http://www.apache.org/licenses/LICENSE-2.0">http://www.apache.org/licenses/LICENSE-2.0</a></p>

 <ul class="bullet-list">

 <li>Facebook<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/facebook/facebook-android-sdk">https://github.com/facebook/facebook-android-sdk</a></li>

 <li>android-volley<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/mcxiaoke/android-volley">https://github.com/mcxiaoke/android-volley</a></li>

 <li>Acra<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/ACRA/acra/">https://github.com/ACRA/acra/</a></li>

 <li>Apache HttpCore<br/>Licensed under the Apache License, Version 2.0<br/><a href="http://hc.apache.org/httpcomponents-core-ga/">http://hc.apache.org/httpcomponents-core-ga/</a></li>

 <li>Apache HttpClient Mime<br/>Licensed under the Apache License, Version 2.0<br/><a href="http://hc.apache.org/httpcomponents-client-ga/httpmime/">http://hc.apache.org/httpcomponents-client-ga/httpmime/</a></li>

 <li>glide<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/bumptech/glide">https://github.com/bumptech/glide</a></li>

 <li>ORMLite<br/>Licensed under the ISC license<br/><a href="http://ormlite.com/">http://ormlite.com/</a></li>

 <li>android-crop<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/jdamcd/android-crop">https://github.com/jdamcd/android-crop</a></li>

 <li>ShowcaseView<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/amlcurran/ShowcaseView">https://github.com/amlcurran/ShowcaseView</a></li>

 <li>image-chooser-library<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/coomar2841/image-chooser-library">https://github.com/coomar2841/image-chooser-library</a></li>

 <li>AndroidSlidingUpPanel<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/umano/AndroidSlidingUpPanel">https://github.com/umano/AndroidSlidingUpPanel</a></li>

 <li>FlowLayout-for-Android<br/>Licensed under MIT license. 2013<br/><a href="https://github.com/ultimate-deej/FlowLayout-for-Android">https://github.com/ultimate-deej/FlowLayout-for-Android</a></li>

 <li>FloatingEditText<br/>Licensed under the Apache License, Version 2.0<br/><a href="https://github.com/keithellistemp/MaterialWidget">https://github.com/keithellistemp/MaterialWidget</a></li>
</ul>
</div>
  </section>
</div>
 
</body>
</html>
  
<script>
  myInterface.setTitle('About us');
</script>
