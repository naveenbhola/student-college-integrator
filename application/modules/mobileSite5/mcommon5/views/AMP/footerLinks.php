 <!--footer-->
 <?php $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "footer"));?>
   <div class="m-btm">
     <amp-accordion class="ampstart-dropdown" disable-session-states>
        <section class="ac-rd">
          <h4 class="color-w pad10 color-b f12 font-w6 txt-trns-u ga-analytic" data-vars-event-name="FOOTER_STREAM_ACCORDIAN">Streams</h4>
          <div class="color-w">
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/business-management-studies/colleges/colleges-india
">BUSINESS & MANAGEMENT STUDIES</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/engineering/colleges/colleges-india
">ENGINEERING</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/design/colleges/colleges-india
">DESIGN </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/hospitality-travel/colleges/colleges-india
">HOSPITALITY & TRAVEL</a>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/law/colleges/colleges-india
">LAW</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/animation/colleges/colleges-india
">ANIMATION</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/mass-communication-media/colleges/colleges-india
">MASS COMMUNICATION & MEDIA</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/it-software/colleges/colleges-india
">IT & SOFTWARE</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/humanities-social-sciences/colleges/colleges-india
">HUMANITIES & SOCIAL SCIENCES</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/arts-fine-visual-performing/colleges/colleges-india
">ARTS ( FINE / VISUAL / PERFORMING )</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/science/colleges/colleges-india
">SCIENCE</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/architecture-planning/colleges/colleges-india
">ARCHITECTURE & PLANNING </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/accounting-commerce/colleges/colleges-india
">ACCOUNTING & COMMERCE </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/banking-finance-insurance/colleges/colleges-india
">BANKING, FINANCE & INSURANCE </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/aviation/colleges/colleges-india
">AVIATION</a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/teaching-education/colleges/colleges-india
">TEACHING & EDUCATION </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/nursing/colleges/colleges-india
">NURSING </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/colleges/colleges-india
">MEDICINE & HEALTH SCIENCES </a><span class="color-9"> | </span>
             <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?php echo SHIKSHA_HOME?>/beauty-fitness/colleges/colleges-india">BEAUTY & FITNESS </a><span class="color-9"> | </span>
              <a class="l-22 pad-5tb color-3 ga-analytic" data-vars-event-name="FOOTER_STREAM_LINK" href="<?=SHIKSHA_HOME?>/government-exams/exams-st-21">GOVERNMENT EXAMS </a>
          </div>
        </section>
        <section class="ac-rd">

<?php
         function examYear($examName, $examArray){
             if(isset($examArray[$examName])){
                 return ' '.$examArray[$examName];
             }
         }
         $examPageLib = $this->load->library('examPages/ExamPageLib');
         $examYearArr = $examPageLib->getExamYears();
?>

          <h4 class="color-w pad10 color-b f12 font-w6 txt-trns-u ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS">top exams</h4>
          <div class="color-w">
              <p class="color-3 f12 font-w6 m-top">MBA</p>
              <aside class="">
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/cat-exam">CAT<?=examYear('cat',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/cmat-exam">CMAT<?=examYear('cmat',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/snap-exam">SNAP<?=examYear('snap',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/xat-exam">XAT<?=examYear('xat',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/mat-exam">MAT<?=examYear('mat',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_MBA_LINK" href="<?php echo SHIKSHA_HOME?>/mba/nmat-by-gmac-exam">NMAT by GMAC<?=examYear('nmat by gmac',$examYearArr);?></a>
              </aside>
              <p class="color-3 f12 font-w6 m-top">Engineering</p>
              <aside class="">
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/jee-main-exam">JEE Main<?=examYear('jee main',$examYearArr);?></a><span class="color-9">|</span>
		<a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/jee-main-exam-results">JEE Main<?=examYear('jee main',$examYearArr);?> Result</a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/comedk-uget-exam">COMDEK<?=examYear('comedk uget',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/engineering/gate-exam">GATE<?=examYear('gate',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/wbjee-exam">WBJEE<?=examYear('wbjee',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/upsee-exam">UPSEE<?=examYear('upsee',$examYearArr);?></a><span class="color-9">|</span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/b-tech/jee-advanced-exam">JEE Advanced<?=examYear('jee advanced',$examYearArr);?></a><span class="color-9">|</span>
		<a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_TOPEXAMS_ENG_LINK" href="<?php echo SHIKSHA_HOME?>/university/lovely-professional-university/lpu-nest-exam">LPU-NEST<?=examYear('lpu nest',$examYearArr);?></a>
              </aside>
          </div>
        </section>
         <?php
         $articleLib = $this->load->library('article/ArticleUtilityLib');
         $footerLinks = $articleLib->getFooterCustomizedLinks();
         if(count($footerLinks) > 0){
            $i = 0;
         ?>
        <section class="ac-rd">
          <h4 class="color-w pad10 color-b f12 font-w6 txt-trns-u ga-analytic" data-vars-event-name="FOOTER_IMPORTANT_UPDATES">Important Updates</h4>
          <div class="color-w">
               <?php foreach ($footerLinks as $link){ ?>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_IMPORTANT_UPDATES_LINK" title="<?=$link['name']?>" href="<?=$link['URL']?>"><?=$link['name']?></a>
               <?php
                  if(!(++$i === count($footerLinks))) {
                      echo '<span class="color-9"> | </span>';
                  }

                } ?>                
          </div>
        </section>
         <?php } ?>

        <section class="ac-rd">
          <h4 class="color-w pad10 color-b f12 font-w6 txt-trns-u ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD">studyabroad</h4>
          <div class="pad10 color-w">
              <p class="color-3 f12 font-w6 m-top">Courses</p>
              <aside class="">
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COURSE_LINK" title = "BE/BTech abroad" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/be-btech-in-abroad-dc11510">BE/Btech</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COURSE_LINK" title = "MS" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/ms-in-abroad-dc11509">MS</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COURSE_LINK" title = "MBA" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/mba-in-abroad-dc11508">MBA</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COURSE_LINK" title = "Law" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/bachelors-of-law-in-abroad-cl1245">Law</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COURSE_LINK" title = "All Courses" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>">All Courses</a>
              </aside>
              <p class="color-3 f12 font-w6 m-top">Countries</p>
              <aside class="">
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COUNTRY_LINK" title = "USA" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/usa">USA</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COUNTRY_LINK" title = "UK" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/uk">UK</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COUNTRY_LINK" title = "Canada" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/canada">Canada</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COUNTRY_LINK" title = "Australia" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/australia">Australia</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_COUNTRY_LINK" title = "All Countries" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/abroad-countries-countryhome">All Countries</a>
              </aside>
              <p class="color-3 f12 font-w6 m-top">Exams</p>
               <aside class="">
                  <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_EXAM_LINK" title = "GRE" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/gre">GRE</a><span class="color-9"> | </span>
                  <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_EXAM_LINK" title = "GMAT" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/gmat">GMAT</a><span class="color-9"> | </span>
                  <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_EXAM_LINK" title = "SAT" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/sat">SAT</a><span class="color-9"> | </span>
                  <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_EXAM_LINK" title = "IELTS" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/ielts">IELTS</a><span class="color-9"> | </span>
                  <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_STUDYABROAD_EXAM_LINK" title = "TOEFL" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/toefl">TOEFL</a>
              </aside>
          </div>
        </section>
        <section class="ac-rd">
          <h4 class="color-w pad10 color-b f12 font-w6 txt-trns-u ga-analytic" data-vars-event-name="FOOTER_HELP">help</h4>
          <div class="color-w">
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="About Us" href="<?php echo $this->config->item('aboutusUrl'); ?>">About us</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="Careers With Us" href="https://careers.shiksha.com/">Careers With Us</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" id="flSHB" title = "Student HelpLine" href="<?php echo $this->config->item('helplineUrl'); ?>">Student helpline</a><span class="link-sep"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="Privacy" href="<?php echo $this->config->item('policyUrl'); ?>">Privacy policy</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="Contact Us" href="<?php echo $this->config->item('contactusUrl'); ?>">Contact Us</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="Terms of use" href="<?php echo $this->config->item('termUrl'); ?>">Terms of Use</a><span class="color-9"> | </span>
                <a class="color-3 f12 l-22 pad-5tb ga-analytic" data-vars-event-name="FOOTER_HELP_LINK" title="Shiksha Sitemap" href="<?php echo SHIKSHA_HOME.'/sitemap'; ?>">Sitemap</a><span class="color-9">
          </div>
        </section>
        </amp-accordion>
   </div>
