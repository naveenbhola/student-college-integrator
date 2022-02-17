<?php
	$dfpFooter  = $this->load->view('dfp/dfpCommonCokkieBanner');
	echo $dfpFooter;
?>    
<div class="cokkie-lyr">
  <div class="cokkie-box">
      <p>We use cookies to improve your experience. By continuing to browse the site, you agree to our <a href="/shikshaHelp/ShikshaHelp/privacyPolicy" target="_blank">Privacy Policy</a> and <a href="/shikshaHelp/ShikshaHelp/cookiePolicy" target="_blank">Cookie Policy</a>.</p>
      <div class="tar"><button class="cookAgr-btn">OK</button></div>
  </div>
</div>

<p class="clr"></p>
<footer id="footer">
	<?php /*$this->load->view('common/jeeMainResultBanner');*/ ?>
	<div class="n-footer2">
		<div class="container">
			<div class="n-fotFolw">
				<a href="javascript:void(0);" class="dwnld-app icons">
				</a>
			</div>
				<?php if(!$isAskButton) {?>
					<div class="n-fotHelplne">
						<p>Student Helpline Number :<b>011-40469621</b><br/>
						Timings : <span>9:30 AM - 6:30 PM, MON - FRI</span></p>
					</div>
				<?php } else {?>
				<div class="n-fotHelplne">
					<p>Get our experts to answer your <br/>questions within
					<strong> 24 Hrs </strong></p>
					<a class="btn__prime button button--orange" href="<?=SHIKSHA_ASK_HOME?>" onclick="askButtonOnFooter()" onclick="gaTrackEventCustom('FooterASK','FOOTER_ASK_BUTTON');">Ask Question</a>
				</div>
				<?php } ?>
			</div>
			<div class="n-fotFolw">
				<ul>
					<li><a track='FOOTER_FACEBOOK' href="https://www.facebook.com/shikshacafe" title="Join us on Facebook"><i class="icons ic_fb"></i></a></li>
					<li><a track='FOOTER_TWITTER' href="https://twitter.com/shikshadotcom" title="Join us on Twitter"><i class="icons ic_tw"></i></a></li>
					<li><a track='FOOTER_GOOGLE+' href="https://plus.google.com/+shiksha/posts" title="Join us on Google+"><i class="icons ic_gp"></i></a></li>
				</ul>
			</div>
			<p class="clr"></p>
		</div>
	</div>
	<div class="n-footer1">
		<div class="container">
			<div class="n-row">
				<div class="col-lg-3">
					<div class="n-fotrCntBx">
						<h3>Shiksha</h3>
						<ul>
							<li><a track='FOOTER_ABOUT_US' href="<?php echo SHIKSHA_ABOUTUS_HOME; ?>" title="About Us" rel="nofollow">About Us</a></li>
							<li><a track='FOOTER_MANAGEMENT' href="<?php echo SHIKSHA_HOME.'/team'; ?>" title="Management Team" rel="nofollow">Management Team</a></li>
							<li><a track='FOOTER_CAREERS' href="https://careers.shiksha.com/" title="Careers With Us" rel="nofollow">Careers</a></li>
							<li><a track='FOOTER_AUTHORS' href="<?php echo SHIKSHA_HOME.'/shiksha-authors'; ?>" title="Shiksha Authors" >Shiksha Authors</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="n-fotrCntBx">
						<h3>Colleges</h3>
						<ul>
							<li><a track='FOOTER_CLIENT_LOGIN' href="https://enterprise.shiksha.com/enterprise/Enterprise/loginEnterprise" title="Client login" rel="nofollow">Client login</a></li>
							<li><a track='FOOTER_ADVERTISE' href="mailto:sales@shiksha.com" title="Advertise with us" rel="nofollow">Advertise with us</a></li>
							<li><a track='FOOTER_ADD_COLLEGES' href="<?php echo ENTERPRISE_HOME; ?>" title="Add courses and colleges here" rel="nofollow">Add Colleges</a></li>
							<li><a track='FOOTER_CONTACT_US' href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" title="Contact Us" rel="nofollow">Contact Us</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="n-fotrCntBx">
						<h3>Others</h3>
						<ul>
							<li><a track='FOOTER_FAQS' href="<?php echo SHIKSHA_FAQ_HOME; ?>" title="FAQs">FAQs</a></li>
							<li><a track='FOOTER_FEEDBACK' action='showFeedBack()' href="javascript:void(0);" title="Feedback" rel="nofollow">Feedback</a></li>
							<li><a track='FOOTER_GRIEVANCES' href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/grievances" title="Grievances" rel="nofollow">Grievances</a></li>
							<li><a track='FOOTER_SUMMONS' href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/summonsNotices" title="Summons/Notices" rel="nofollow">Notices / Summons</a></li>
							<li><a track='FOOTER_PRIVACY' action='popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy")' href="javascript:void(0);" title="Privacy Policy" rel="nofollow">Privacy</a></li>
							<li><a track='FOOTER_SITEMAP' href="<?php echo SHIKSHA_HOME.'/sitemap'; ?>" title="Shiksha Sitemap" >Sitemap</a></li>
							<li><a track='FOOTER_TERMS_CONDITIONS' action='popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition")' href="javascript:void(0);" title="Terms &amp; Conditions" rel="nofollow">Terms &amp; Conditions</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="n-fotrCntBx">
						<h3>Our Group</h3>
						<ul>
							<li><a track='FOOTER_INFOEDGE' href="http://www.infoedge.in" target="_blank" title="Info Edge (India) Limited">Infoedge.in</a></li>
							<li><a track='FOOTER_NAUKRI' href="https://www.naukri.com" target="_blank" title="Jobs">Naukri.com</a></li>
							<li><a track='FOOTER_FIRSTNAUKRI' href="http://www.firstnaukri.com" target="_blank" title="Jobs for Freshers">Firstnaukri.com</a></li>
							<li><a track='FOOTER_NAUKRIGULF' href="http://www.naukrigulf.com" target="_blank" title="Jobs in Middle East">Naukrigulf.com</a></li>
							<li><a track='FOOTER_99ACRES' href="http://www.99acres.com" target="_blank" title="Real Estate">99acres.com</a></li>
							<li><a track='FOOTER_JEEVANSATHI' href="http://www.jeevansathi.com" target="_blank" title="Matrimonials">Jeevansathi.com</a></li>						
							<li><a track='FOOTER_AMBITION' href="http://www.ambitionbox.com/" target="_blank" title="Discover Companies. Prepare for Interviews">AmbitionBox.com</a></li>
							<li><a track='FOOTER_LEARNING' href="https://learning.naukri.com" target="_blank" title="Naukri Learning Certification, Courses & Training">Naukri Learning</a></li>
						</ul>
					</div>
				</div>
				<p class="clr"></p>
			</div>
		</div>
	</div>
	<div class="n-footer3">
		<div class="container">
			
			<ul class="fotr_seo">
				<li>
					<div>MBA<i>:</i></div>
					<div>
						<a track='FOOTER_MBA_HOME' href="<?php echo SHIKSHA_HOME ?>/mba-pgdm-chp">MBA Home</a><i></i>
						<a track='FOOTER_TOP_MBA_COLLEGES' href="<?php echo SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-in-india/2-2-0-0-0">Top MBA Colleges</a><i></i>
						<a track='FOOTER_MBA_COLLEGES' href="<?php echo SHIKSHA_HOME?>/mba/colleges/mba-colleges-india">MBA Colleges</a><i></i>
						<a track='FOOTER_EXECUTIVE_MBA_COLLEGES' href="<?php echo SHIKSHA_HOME ?>/business-management-studies/colleges/executive-mba-colleges-india">Executive MBA Colleges</a><i></i>
						<a track='FOOTER_MBA_EXAMS' href="<?php echo SHIKSHA_HOME; ?>/mba/exams-pc-101">MBA Exams</a>
					</div>
				</li>
				<li>
					<div>Engineering<i>:</i></div>
					<div>
						<a track='FOOTER_ENG_HOME' href="<?php echo SHIKSHA_HOME ?>/engineering-chp">Engineering</a><i></i>
						<a track='FOOTER_TOP_ENG_COLLEGES' href="<?php echo SHIKSHA_HOME ?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0">Top Engineering Colleges</a><i></i>
						<a track='FOOTER_ENG_COLLEGES' href="<?php echo SHIKSHA_HOME ?>/engineering/colleges/colleges-india">Engineering Colleges</a><i></i>
						<a track='FOOTER_ENG_EXAMS' href="<?php echo SHIKSHA_HOME; ?>/b-tech/exams-pc-10">Engineering Exams</a><i></i>
                                                <a track='FOOTER_ENG_JEE_MAIN_RESULT' href="<?php echo SHIKSHA_HOME; ?>/b-tech/jee-main-exam">JEE Main</a><i></i>
                                                <a track='FOOTER_ENG_JEE_MAIN_RP' href="<?php echo SHIKSHA_HOME; ?>/b-tech/jee-advanced-exam">JEE Advanced</a>
					</div>
				</li>
				<li>
					<div>Medicine<i>:</i></div>
					<div>
						<a track='FOOTER_NEET' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/neet-exam">NEET</a><i></i>
						<a track='FOOTER_AIIMS' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/aiims-exam">AIIMS Exam</a><i></i>
						<a track='FOOTER_JIPMER' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/jipmer-mbbs-exam">JIPMER Exam</a><i></i>
						<a track='FOOTER_TOP_MEDICAL_COLLEGE' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/ranking/top-medical-colleges-in-india/100-2-0-0-0">Top Medical Colleges</a><i></i>
						<a track='FOOTER_MEDICAL_COLLEGE' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/colleges/colleges-india">Medical Colleges</a><i></i>
						<a track='FOOTER_MEDICAL_COLLEGE' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/exams-st-18">Medical Exams</a>
					</div>
				</li>
				<li>
					<div>Other Courses<i>:</i></div>
					<div>
						<a track='FOOTER_ANIMATION' href="<?php echo SHIKSHA_HOME?>/animation/colleges/colleges-india">Animation</a><i></i>
						<a track='FOOTER_BCOM' href="<?php echo SHIKSHA_HOME?>/accounting-commerce/colleges/b-com-colleges-india">B Com</a><i></i>
						<a track='FOOTER_BSC' href="<?php echo SHIKSHA_HOME?>/b-sc-chp">B Sc</a><i></i>
						<a track='FOOTER_BBA' href="<?php echo SHIKSHA_HOME?>/bba-chp">BBA</a><i></i>
						<a track='FOOTER_CA' href="<?php echo SHIKSHA_HOME?>/accounting-commerce/colleges/ca-colleges-india">CA</a><i></i>
						<a track='FOOTER_FASHION_TEXTILE' href="<?php echo SHIKSHA_HOME?>/design/fashion-design-chp">Fashion &amp; Textile Design</a><i></i>
						<a track='FOOTER_HOTEL_MGMT' href="<?php echo SHIKSHA_HOME?>/hospitality-travel/hotel-hospitality-management-chp">Hotel Management</a><i></i>
						<!-- <a track='FOOTER_INTERIOR_DESIGN' href="<?php //echo $tabsContentByCategory[13]['subcats'][70]['url']; ?>">Interior Design</a><br/><br/> -->
						<!-- <a track='FOOTER_JOURNALISM' href="<?php //echo $tabsContentByCategory[7]['subcats'][20]['url']; ?>">Journalism</a><i></i> -->
						<a track='FOOTER_LAW' href="<?php echo SHIKSHA_HOME?>/law/colleges/colleges-india">Law</a><i></i>
						<a track='FOOTER_MASS_COMMUNICATION' href="<?php echo SHIKSHA_HOME?>/mass-communication-media-chp">Mass Communication</a>
						<a track='FOOTER_MBBS' href="<?php echo SHIKSHA_HOME?>/mbbs-chp">MBBS</a><i></i>
						<a track='FOOTER_MCA' href="<?php echo SHIKSHA_HOME?>/mca-chp">MCA</a><i></i>
						<a track='FOOTER_MTECH' href="<?php echo SHIKSHA_HOME?>/engineering/colleges/m-tech-colleges-india">M.Tech</a><i></i>
						<!-- <a track='FOOTER_BIOTECHNOLOGY' href="<?php //echo $tabsContentByCategory[5]['subcats'][136]['url']; ?>">Biotechnology</a><i></i> -->
						<a track='FOOTER_BCA' href="<?php echo SHIKSHA_HOME?>/bca-chp">BCA</a><i></i>
						<!-- <a track='FOOTER_EVENT_MANAGEMENT' href="<?php //echo $tabsContentByCategory[6]['subcats'][85]['url']; ?>">Event Management</a><br/><br/> -->
						<a track='FOOTER_PHARMACY' href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/pharmacy-chp">Pharmacy</a><i></i>
						<!-- <a track='FOOTER_GRAPHIC_DESIGN' href="<?php //echo $tabsContentByCategory[12]['subcats'][92]['url']; ?>">Graphic Design</a><i></i> -->

						<a track='FOOTER_MSC' href="<?php echo SHIKSHA_HOME?>/m-sc-chp">M Sc</a>
						
					</div>
				</li>
				<li>
					<div>Study Abroad<i>:</i></div>
					<div>
						<a track='FOOTER_SA_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>">Study Abroad Home</a><i></i>
						<a track='FOOTER_SA_BTECH_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/be-btech-in-abroad-dc11510">BTech abroad</a><i></i>
						<a track='FOOTER_SA_MBA_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/mba-in-abroad-dc11508">MBA abroad</a><i></i>
						<a track='FOOTER_SA_MS_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/ms-in-abroad-dc11509">MS abroad</a><i></i>
						<a track='FOOTER_SA_GRE_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/exams/gre">GRE</a><i></i>
						<a track='FOOTER_SA_GMAT_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/exams/gmat">GMAT</a><i></i>
						<a track='FOOTER_SA_SAT_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>/exams/sat">SAT</a><i></i>
						<a track='FOOTER_SA_IELTS_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/ielts">IELTS</a><i></i>
                        <a track='FOOTER_SA_TOEFL_HOME' href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/toefl">TOEFL</a>
					</div>
				</li>
                <li>
                    <div>Sarkari Exams<i>:</i></div>
                    <div>
                        <a track='FOOTER_GOVT_EXAM_SSC_CGL' href="<?=SHIKSHA_HOME?>/exams/ssc-cgl">SSC CGL</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_IBPS_PO' href="<?=SHIKSHA_HOME?>/exams/ibps-po">IBPS PO</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_SBI_PO' href="<?=SHIKSHA_HOME?>/exams/sbi-po">SBI PO</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_NDA' href="<?=SHIKSHA_HOME?>/exams/nda">NDA</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_UPSC_CIVIL_SERVICES' href="<?=SHIKSHA_HOME?>/exams/upsc-civil-services-exam">UPSC Civil Services Exam</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_IES' href="<?=SHIKSHA_HOME?>/exams/ies">IES</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_SSC_CHSL' href="<?=SHIKSHA_HOME?>/exams/ssc-chsl">SSC CHSL</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_AFCAT' href="<?=SHIKSHA_HOME?>/exams/afcat">AFCAT</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_IBPS_CWE_RRB' href="<?=SHIKSHA_HOME?>/exams/ibps-cwe-rrb">IBPS CWE RRB</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_SSC_JE' href="<?=SHIKSHA_HOME?>/exams/ssc-je">SSC JE</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_CDS' href="<?=SHIKSHA_HOME?>/exams/cds">CDS</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_SBI_CLERK' href="<?=SHIKSHA_HOME?>/exams/sbi-clerk">SBI Clerk</a><i></i>
                        <a track='FOOTER_GOVT_EXAM_IBPS_CLERK' href="<?=SHIKSHA_HOME?>/exams/ibps-clerk">IBPS Clerk</a>
                    </div>
                </li>
				<li>
					<div>Resources<i>:</i></div>
					<div>
						<a track='FOOTER_CAREERS_AFTER_12TH' href="<?php echo SHIKSHA_HOME ; ?>/careers" title="All Careers">Careers after 12th</a><i></i>
						<a track='FOOTER_COURSES_AFTER_12TH' href="<?php echo SHIKSHA_HOME ; ?>/courses-after-12th" title="Courses After 12th">Courses After 12th</a><i></i>
						<a track='FOOTER_BOARDS' href="<?php echo SHIKSHA_HOME ; ?>/boards" title="Education Boards">Education Boards</a><i></i>
						<a track='FOOTER_ASK_QUESTION' href="<?=SHIKSHA_ASK_HOME?>/questions" title="Ask A Question">Ask a Question</a><i></i>
						<a track='FOOTER_DISCUSSIONS' href="<?=SHIKSHA_ASK_HOME?>/all-discussions" title="Discussions">Discussions</a><i></i>
						<a track='FOOTER_WRITE_COLLEGE_REVIEW' href="<?php echo SHIKSHA_HOME ; ?>/college-review-form">Write a college review</a><i></i>
						<a track='FOOTER_ARTICLES' href="<?php echo SHIKSHA_HOME?>/articles-all" title="Featured Articles" >Articles</a><i></i>
						<a track='FOOTER_SHIKSHA_ASK_ANSWER_APP' href="https://play.google.com/store/apps/details?id=com.shiksha.android" title="Shiksha Ask & Answer App" target="_blank">Shiksha Ask & Answer App</a><i></i>
						<a track='FOOTER_EDUCATION_TRENDS' href="<?php echo SHIKSHA_HOME.'/analytics/ShikshaTrends/trendsHomePage';?>" title="Education Trends" >Education Trends</a>
					</div>
				</li>

                                 <?php
                                 $this->benchmark->mark('Footer_New_HTML_Add_Cache_Imp_Updates_start');

                                 $articleLib = $this->load->library('article/ArticleUtilityLib');
                                 $footerLinks = $articleLib->getFooterCustomizedLinks();

                                 $this->benchmark->mark('Footer_New_HTML_Add_Cache_Imp_Updates_end');

                                 if(count($footerLinks) > 0){
                                    $i = 0;
                                 ?>
                                <li>
                                        <div>Important Updates<i>:</i></div>
                                        <div>
                                             <?php foreach ($footerLinks as $link){ ?>
                                                <a track='FOOTER_IMPORTANT_UPDATES' href="<?=$link['URL']?>" title="<?=$link['name']?>"><?=$link['name']?></a>
                                             <?php
                                                if(!(++$i === count($footerLinks))) {
                                                    echo "<i></i>";
                                                }

                                              } ?>
                                        </div>
                                </li>
                                 <?php } ?>

			</ul>
			<p class="clr"></p>
			<div class="n-oPartnrFotr">
               
				<ul class="fotr_seo">
				 <li><div>Our Partners  <i>:</i></div>
				   <div style="margin-bottom:0;"> 
					<a track='FOOTER_ZOMATO' href="http://www.zomato.com" target="_blank" title="Restaurant Reviews" rel="nofollow"> Zomato.com</a><i></i>
					<a track='FOOTER_POLICYBAZAAR' href="http://www.policybazaar.com" target="_blank" title="Insurance Comparison" rel="nofollow">Policybazaar.com</a><i></i>
					<a track='FOOTER_MERITNATION' href="http://www.meritnation.com" target="_blank" title="School Online" rel="nofollow">Meritnation.com</a><i></i>
					<a track='FOOTER_MYDALA' href="http://www.mydala.com/" target="_blank" title="Online discount shopping deals India" rel="nofollow">Mydala.com</a><i></i>
					<a track='FOOTER_HAPPILYUNMARRIED' href="http://www.happilyunmarried.com/" target="_blank" title="Buy funny and unique gifts from India's coolest company" rel="nofollow">HappilyUnmarried.com</a><i></i>
					<a track='FOOTER_CANVERA' href="http://www.canvera.com/" target="_blank" title="Preserving Memories | Online Photobooks | Photobook Printing | Hire Photographers" rel="nofollow">Canvera.com</a>
				 </li>
				
				
				 </ul>
				  
				
			</div>
			<div class="n-tradeMarkFotr"><p>Trade Marks belong to the respective owners. Copyright &copy; <?php echo date('Y'); ?> Info edge India Ltd. All rights reserved.</p>  <p class="clr"></p></div>
		</div>
	</div>
</footer>
<div id="_appbanner"></div>
