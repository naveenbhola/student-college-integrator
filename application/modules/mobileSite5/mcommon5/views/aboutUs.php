<?php $this->load->view('header'); ?>
<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel',false,'aboutus');
   ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true, '', 'aboutus');?>
    </header>	 
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?> 
	  
    <div data-role="content">
	  
        <div class="head-group">
            <h1>About Us</h1>
        </div>

    <div class="content-wrap2">
    	<section class="static-cont clearfix">
        	<h6 class="pt20">About Shiksha.com</h6>
            <p>
	    Shiksha.com is a one-stop-solution making course and college selection easy for students looking to pursue undergraduate (UG) and postgraduate (PG) courses in India and abroad; also accessible to users on the move through the website’s mobile site. Launched in 2008, Shiksha.com belongs to Info Edge (India) Ltd, the owner of established brands like Naukri.com, 99acres.com, Jeevansathi.com, among several others. With this strong brand pedigree, Shiksha offers its users the unique privilege of customised tools like Alumni Employment Statistics that includes salary data powered by Naukri.com.<br/><br/>

Our website is a repository of reliable and authentic information for over 14,000 institutions, 40,000 plus courses and has a registered data base of more than 3.5 million students. We offer specific information for students interested in UG/PG courses in India (shiksha.com) and Abroad (studyabroad.shiksha.com) across the most popular  educational streams – Management; Science & Engineering; Banking & Finance; Information Technology; Animation, VFX, Gaming & Comics; Hospitality, Aviation & Tourism; Media, Films & Mass Communication; Design; Medicine, Beauty & Health Care; Retail; Arts, Law, Languages & Teaching; and Test Preparation.<br/><br/>

Education seekers get a personalised experience on our site, based on educational background and career interest, enabling them to make well informed course and college decisions. The decision making is empowered with easy access to detailed information on career choices, courses, exams, colleges, admission criteria, eligibility, fees, placement statistics, rankings, reviews, scholarships, latest updates etc as well as by interacting with other Shiksha.com users, experts, current students in colleges and alumni groups. We have introduced several student oriented products and tools like Career Central, Common Application Form, Top Colleges, College Compare, Alumni Employment Stats, Campus Connect, College Reviews, College Predictors, MyShortlist and Shiksha Café.<br/><br/>

Our active ask and answer community called Shiksha Café has over 1000 experts answering career and college related queries. Students can ask questions, participate in discussions and stay updated with latest news, articles related to their education interest. Shiksha.com is India’s smartest college gateway that blends higher education related domain knowledge, with technology, innovation, and credibility to give students  personalised insights to make informed career, course and college decisions.<br/><br/>
            </p>
        </section>
    </div>
    <?php $this->load->view('footerLinks'); ?>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('footer'); ?>

