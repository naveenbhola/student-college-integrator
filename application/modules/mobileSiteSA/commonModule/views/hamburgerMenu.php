<?php global $configData; $footerURLS = $configData['dataForHeaderFooter']; ?>
<nav id="<?php echo ($jqMobileFlag===true)?'mypanel':'hamburgerContainer'?>" data-position="left" data-display="push" data-role="panel" class="mm-menu hideHamburgerContainer" data-swipe-close="false" style= "box-shadow:none !important;border-bottom: solid 3px #ee670b;overflow: auto;z-index: 99999;">
<div style = "padding:0px !important;position:relative;z-index:9;">
<ul class="clearfix">
<li>
<a class="pnl_a" href="<?=(SHIKSHA_STUDYABROAD_HOME)?>">Home</a>
</li>
<?php foreach($footerURLS  as $level => $data) {
if(in_array($level,array('bachelors','masters'))) { ?>
<li>
<a class = "hamburger-accordion-label" href="Javascript:void(0);"><?=(ucfirst($level))?><i class="sprite footer-arr-dwn"></i></a>
<div class = "hamburger-accordion-div hide">
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>By Course</span></li>
</ul>
<ol>
<?php foreach($data['browseInsByCourse'] as $course){ ?>
<li><a class="pnl_a" href="<?=($course['url'])?>"><?=($course['title'])?></a></li>
<?php } ?>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>By Stream</span></li>
</ul>
<ol>
<?php foreach($data['browseInsByStream'] as $stream){ ?>
<li><a class="pnl_a" href="<?=($stream['url'])?>"><?=($stream['title'])?></a></li>
<?php } ?>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Student Scholarships</span></li>
</ul>
<ol>
<?php foreach($data['browseScholarships'] as $value){ ?>
<li><a class="pnl_a" href="<?=($value['url'])?>"><?=($value['title'])?></a></li>
<?php } ?>
</ol>
</div>
</li>
<?php }  }?>
<li>
<a class = "hamburger-accordion-label" href="Javascript:void(0);">Application Process<i class="sprite footer-arr-dwn"></i></a>
<div class = "hamburger-accordion-div hide">
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Language Exam</span></li>
</ul>
<ol>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/ielts'?>">IELTS</a></li>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/toefl'?>">TOEFL</a></li>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/pte'?>">PTE</a></li>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Aptitude Exam</span></li>
</ul>
<ol>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/gre'?>">GRE</a></li>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/gmat'?>">GMAT</a></li>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/exams/sat'?>">SAT</a></li>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Application Writing</span></li>
</ul>
<ol>
<?php foreach($footerURLS['applicationProcessPages'] as $key=>$value){ ?>
<li><a class="pnl_a" href="<?=($value['url'])?>"><?=($value['title'])?></a></li>
<?php } ?>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Find best scholarship for you</span></li>
</ul>
<ol>
<li><a class="pnl_a" href="<?php echo $footerURLS['findScholarships']['url']?>"><?php echo $footerURLS['findScholarships']['title']?></a></li>
</ol>
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Application Assistance</span></li>
</ul>
<ol>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/apply'?>">Shiksha counseling service</a></li>
<li><a class="pnl_a" href="<?=SHIKSHA_STUDYABROAD_HOME.'/apply/shipment'?>">DHL Student offer</a></li>
</ol>
</li>
<li>
<a class = "hamburger-accordion-label" href="Javascript:void(0);">Contact Us<i class="sprite footer-arr-dwn"></i></a>
<div class = "hamburger-accordion-div hide"> 
<ul class="level-2-content mm-list">
<li class="mm-subtitle"><span>Students Helpline</span></li>
<li style = "background: none repeat scroll 0 0 #d6d6d6;">
<span>
<div class="sprite mob-icn"></div>
<div class="contact-details ml15">
Call : <strong><a href="tel:<?php echo ABROAD_STUDENT_HELPLINE; ?>" class="hamburger-contact-details" data-enhance = "false" ><?php echo ABROAD_STUDENT_HELPLINE; ?></a></strong>
<p>(09:30 AM - 06:30PM, Monday - Friday)</p>
</div>
<div class="sprite mob-mail-icn"></div>
<div class="contact-details ml15">
Email : <strong><a class="hamburger-contact-details" data-enhance = "false" href="mailto:studyabroad@shiksha.com">studyabroad@shiksha.com</a></strong>
</div>
</span>
</li>
<li class="mm-subtitle"><span>Sales Enquiries</span></li>
<li style = "background: none repeat scroll 0 0 #d6d6d6;">
<span>
<div class="contact-details">
<p>For Sales Enquiries, Contact :</p>
<strong>Nandita Bandhopadhyay</strong><br>
<div class="sprite mob-mail-icn"></div>
<p>Email : <strong><a href="mailto:nandita@shiksha.com" class="hamburger-contact-details" data-enhance = "false">nandita@shiksha.com</a></strong></p>
</div>
</span>
</li>
</ul>
</div>
</li>
</ul>
</div>
<div class="switching-opt clearfix" style="margin: 0; padding: 18px 0 8px; position: absolute; bottom: 10px; left: 50%; -moz-transform: translateX(-50%); -webkit-transform: translateX(-50%); transform: translateX(-50%);z-index:1;">
<a class="pnl_a" href="<?=(SHIKSHA_HOME)?>" style="color: #566ec2 !important; padding-bottom: 0"><i class="sprite switch-ind"></i>Study In India</a>
</div>
</nav>