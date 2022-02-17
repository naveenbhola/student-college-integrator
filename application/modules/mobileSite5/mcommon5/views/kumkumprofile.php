<?php $this->load->view('header'); ?>
<div id="wrapper" data-role="page">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <header id="page-header"  class="header" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    </header>	
	  
    <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>  
	  
        <div class="head-group">
            <h1>Kum Kum Tondon profile</h1>
        </div>

    <div class="content-wrap2">
    	<section class="static-cont clearfix">
        	<h6 class="pt20">About the author - Kum Kum Tandon</h6>
            <p>Consultant Career Counsellor & Author</p>
<p><img src="/public/images/kumkum.gif" align="left" />
Kum Kum Tandon has been a leading career counselor and expert in career guidance for almost three decades. Her academic qualifications include MA (Psychology), M.Ed., Diploma in Educational Psychology, Vocational Guidance & Counselling (NCERT, Delhi). She has developed a unique and integrated approach to career counseling, which encompasses both parents and teachers in the key decision‐making processes.<br/><br/>

Mrs. Tandon's interest and passion in providing career guidance to students inspired her to author her widely acclaimed books 'Career Options After 10+2 and Beyond' and 'Study Abroad'. Her profound knowledge and experience has inspired thousands of students both in India and abroad.<br/><br/>

Mrs Tandon has been a consultant career counselor with shiksha.com since 2008. She has developed Career central to provide career information and guidance using individual interests to guide the process of career search on shiksha.com.<br/><br/>For queries on careers or guidance  her email is <a href="mailto:kktandon2003@hotmail.com" class="fontSize_12p">kktandon2003@hotmail.com </a>.<br/><br/>
            </p>
        </section>
    </div>
    
    <?php $this->load->view('footerLinks'); 
	 $this->load->view('footer'); ?>
</div>
</div>
