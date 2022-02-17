<?php
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css' => array('faq-page'),
						'js'	=>	array('common'),
						'title'	=>	'Shiksha FAQ',
						'tabName' =>	'Shiksha Help',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaKeywords'	=>'Some Meta Keywords',
						'product'	=>'help',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                'callShiksha'=>1,
						'canonicalURL' => $current_page_url
					);
		$this->load->view('common/header', $headerComponents);
?>

<div class="main_content" >
	<div class="container">
		<div class="n-row faq">
           <h2 class="faq-h2">Frequently Asked Questions </h2>
              <section class="Faq-Tab">
                <ul class="faq-tabs">
                  <li class="current"><a href="#tab_0">For Students</a></li>
                  <li><a href="#tab_1">For Colleges</a></li>
                </ul>

                 <div class="n-row">
                  <div class="faq-tab-cont">
                 
                   <div class="faq-tabpane" id="tab_0">
                         <div class="accordion">
                            
                            <div  class="section">
                              
                                <a href="#one" class="accr-a">How to register?</a>
                           
                              <div id="one" class="sec-cont">
                                <section class="ac-sec">
                                 <h4 class="sec-h4">Registration</h4>
                                 <p class="sec-p">Click 'Register' on the top right corner of the page and you will see a registration form. This will take you hardly 2 minutes to fill up. Just submit your information, verify your mobile number and enjoy the various benefits of being a Shiksha member.</p>
                               </section>
                                <section class="ac-sec">
                                 <h4 class="sec-h4">Mobile number - Is it compulsory?</h4>
                                 <p class="sec-p">Yes, mobile number is needed to verify and complete your registration and also ensure account safety. </p>
                               </section>
                               <section class="ac-sec">
                                 <h4 class="sec-h4">How do I edit my profile on Shiksha?</h4>
                                 <p class="sec-p">Editing your Shiksha account profile information is very simple. Here's how to do it:</p>
                                 <div class="sub-sec">
                                      <h4 class="sec-h4">Can I update the email id?</h4>
                                      <p class="sec-p">Email id cannot be changed as it is mapped to your account at the time of registration. However, you can choose to add your alternate email id in your ‘Personal Information’ section. The other option is to simply create a new Shiksha account with your new email id that you wish to use. </p>
                                   </div>
                                    <div class="sub-sec">
                                      <h4 class="sec-h4">How do I change my password? </h4>
                                      <p class="sec-p">To change your password, Go to your account homepage and click 'Settings'. Now you can change or replace your new password and save. </p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">How do I update my mobile number? </h4>
                                      <p class="sec-p">Go to your ‘Profile’ page, click ‘Edit’ (top right corner) on the ‘Personal Information’ section and then change your new mobile number. Please remember to click ‘Save’.</p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">How do I update profile details?</h4>
                                      <p class="sec-p">You can update your profile information divided into four sections – Personal Information, Educational Background, Work Experience (if applicable) and Communication Settings.</p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">How to update communication preferences?</h4>
                                      <p class="sec-p">You can change or update your ‘Communication Preferences’ under ‘Settings’ to start or stop receiving email notifications from Shiksha. This is useful mail, especially delivered to your inbox to serve your information needs such as college application deadlines, exam dates, prep tips etc. based on your profile. In case you no longer need this service, you may uncheck the box under ‘Communication Preferences’. </p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">How do I update my profile photo?</h4>
                                      <p class="sec-p">Update Photo option is seen if you point the mouse over your profile photo box. You can select this and upload your image. </p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">Is all information compulsory to create my profile?</h4>
                                      <p class="sec-p">No. Apart from your full name, mobile no. and email id, the rest of the fields are optional. However, the page asks for only necessary information and adding these details will help create a complete profile page for you and help us cater to your information needs better.</p>
                                   </div>
                                   <div class="sub-sec">
                                      <h4 class="sec-h4">What privacy settings do I have for my Profile?</h4>
                                      <p class="sec-p">You have adequate control on your Shiksha profile privacy. The fields that have a privacy icon (eye) next to them can be set on or off by you to show or hide those details from public view.</p>
                                   </div>
                                 
                               </section>
                               
                              </div>
                            </div>
                            <div  class="section">
                             
                                <a href="#two" class="accr-a">How do I search for colleges or courses?</a>
                             
                              <div id="two" class="sec-cont">
                                <section class="ac-sec">
                                    <p class="sec-p">To search for a particular college or course, just enter its name in the search box on top of <a href="<?=SHIKSHA_HOME?>" target="_blank">Shiksha homepage</a>. As you start typing, you'll see a dropdown, you can select the name of your desired course/college from this menu as well.</p>
                                    <p class="sec-p">The other way is to search for colleges by categories - MBA, Engineering, Design and Other Courses - shown on the Top global navigation bar. There is an option to select Study Abroad as well. Under each of these respective categories, you can search - Popular Courses, Colleges by Location, Top Colleges and even Compare Colleges. </p>
                                </section>
                              </div>
                            </div>
                            <div class="section">
                              
                                <a href="#three" class="accr-a">How can shiksha help me in making the right college decision?</a>
                              
                              <div class="sec-cont" id="three">
                                <section class="ac-sec">
                                   <h4 class="sec-h4">MBA</h4>
                                    <p class="sec-p">If you are planning to do MBA, we are here to guide you through every step of the journey. So, get set to make an informed career and college decision. </p>
                                    <p class="sec-p">Shortlist your target colleges with the help of our thoughtfully designed, easy-to-use Shiksha tools to help you select the best college for your MBA and know a lot more in the process. Just click the links below to discover each of these MBA tools: </p>
                                     <ul class="accordin-ul">
                                        <li><a href="<?=SHIKSHA_HOME?>/mba/exam" target="_blank">MBA Exams</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/mba/resources/exam-calendar" target="_blank">MBA Entrance Exams Calendar</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/mba/ranking/top-mba-colleges-india/2-2-0-0-0" target="_blank">MBA Rankings</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/mba/resources/mba-alumni-data" target="_blank">Career Compass - MBA Alumni Salary Data</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/mba/resources/college-reviews" target="_blank">MBA College Reviews</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/ask-current-mba-students" target="_blank">Campus Connect - Ask Current MBA Students</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/compare-colleges" target="_blank">Compare Colleges</a></li>
                                    </ul>
                                    <p class="sec-p">More useful tools coming soon including IIM Call Predictor and B-School Finder!</p>
                                    <p class="sec-p">Still got questions?  Click here to <a href="<?=SHIKSHA_ASK_HOME?>" target="_blank">Ask our experts.</a></p>
                                    <p class="sec-p">In case, you are an MBA student/graduate, you can also  <a href="<?=SHIKSHA_HOME?>/college-review-rating-form" target="_blank">Rate/Review your College</a>  and help other students looking for reliable information about your college.</a></p>
                                </section>
                                
                                <section class="ac-sec">
                                   <h4 class="sec-h4">Engineering</h4>
                                    <p class="sec-p">To guide engineering aspirants through their college selection journey, we have just the right tools one would need.  Just click the links below to discover each of these wonderful tools:</p>
                                     <ul class="accordin-ul">
                                        <li><a href="<?=SHIKSHA_HOME?>/engineering/exam" target="_blank">Engineering Exams</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/engineering-exams-dates" target="_blank">Engineering Entrance Exams Calendar</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/top-engineering-colleges-in-india-rankingpage-44-2-0-0-0" target="_blank">Engineering Rankings</a></li>
                                        <li><em>Rank Predictor For<a href="<?=SHIKSHA_HOME?>/jee-main-rank-predictor" target="_blank">JEE Mains;</a><a href="<?=SHIKSHA_HOME?>/jee-advanced-rank-predictor" target="_blank">JEE Advanced;</a><a href="<?=SHIKSHA_HOME?>/comedk-rank-predictor" target="_blank">COMEDK</a></em></li>
                                        <li><em>College Predictors for -<a href="<?=SHIKSHA_HOME?>/cgpet-college-predictor" target="_blank"> CGPET ;</a><a href="<?=SHIKSHA_HOME?>/comedk-college-predictor" target="_blank"> COMEDK ;</a><a href="<?=SHIKSHA_HOME?>/hstes-college-predictor" target="_blank"> HSTES ;</a><a href="<?=SHIKSHA_HOME?>/jee-mains-college-predictor" target="_blank"> JEE Mains ;</a><a href="<?=SHIKSHA_HOME?>/kcet-college-predictor" target="_blank"> KCET ;</a><a href="<?=SHIKSHA_HOME?>/keam-college-predictor" target="_blank"> KEAM ;</a><a href="<?=SHIKSHA_HOME?>/mhcet-college-predictor" target="_blank"> MHCET ;</a><a href="<?=SHIKSHA_HOME?>/mppet-college-predictor" target="_blank"> MPPET ;</a><a href="<?=SHIKSHA_HOME?>/ptu-college-predictor" target="_blank"> PTU ;</a><a href="<?=SHIKSHA_HOME?>/tnea-college-predictor" target="_blank"> TNEA ;</a><a href="<?=SHIKSHA_HOME?>/upsee-college-predictor" target="_blank"> UPSEE ;</a><a href="<?=SHIKSHA_HOME?>/wbjee-college-predictor" target="_blank"> WBJEE </a>
                                        <a href="<?=SHIKSHA_HOME?>/ap-eamcet-college-predictor" target="_blank"> AP-EAMCET </a><a href="<?=SHIKSHA_HOME?>/ts-eamcet-college-predictor" target="_blank"> TS-EAMCET </a><a href="<?=SHIKSHA_HOME?>/ojee-college-predictor" target="_blank"> OJEE </a>
                                        <a href="<?=SHIKSHA_HOME?>/bitsat-college-predictor" target="_blank"> BITSAT </a>
                                        <a href="<?=SHIKSHA_HOME?>/ggsipu-college-predictor" target="_blank"> GGSIPU </a>
                                        <a href="<?=SHIKSHA_HOME?>/gujcet-college-predictor" target="_blank"> GUJCET </a>
                                        </em></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/engineering-colleges-reviews-cr" target="_blank">Engineering College Reviews  </a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses" target="_blank">Get a Mentor for Engineering Prep & College Selection Guidance</a></li>
                                        <li><a href="<?=SHIKSHA_HOME?>/compare-colleges" target="_blank">Compare Colleges</a></li>
                                    </ul>
                                </section>
                                
                                <section class="ac-sec">
                                   <h4 class="sec-h4">Other Courses</h4>
                                   <p class="sec-p">Just click on 'Browse by Categories' and select the course of your choice. It will display listings of colleges falling under that course category. So, go ahead and look for your favourite college. The listings will give you information on course/college details, photos and videos of the college, faculty, top recruiting companies, rankings & awards, infrastructure, hostel details, academic scholarships, international linkages along with contact details of the college. </p>
                                </section>
                                
                              </div>
                            </div>
                            <div  class="section">
                             
                                <a href="#four" class="accr-a">I'm interested in Study Abroad options. How do I find information?</a>
                              
                              <div class="sec-cont" id="four">
                                <section class="ac-sec">
                                 <p class="sec-p"><a href="<?=SHIKSHA_STUDYABROAD_HOME;?>" target="_blank">Studyabroad.shiksha.com</a> is specially created to guide students aspiring to study abroad. It offers up-to-date and very useful information at country level for colleges, courses, exams required, funding options, application process, visa requirements, etc .  One can select colleges by course and by stream for bachelors and masters. Choose from the listings and click on the college or university you want to study in for more information. You will also find interesting articles on study related topics along with Student Guides, College Application and Visa Guides for Popular Study Abroad countries.</p>
                                </section>
                             </div>
                            </div>
                            <div class="section">
                             
                                <a href="#five" class="accr-a">Can shiksha help me in making a career choice?</a>
                              
                              <div class="sec-cont" id="five">
                                <section class="ac-sec">
                                 <h4 class="sec-h4">Career Central</h4>
                                 <p class="sec-p">Deciding the right career for yourself can be tricky. Career Central helps you choose a suitable stream right after XII - Science/Commerce/Humanities or Arts - as per your aptitude and liking. It opens up a world of career options before you, where you can read about a career, its job profile, career opportunities, eligibility for the course and where to seek admission to study.</p>
                                </section>
                                <section class="ac-sec">
                                 <h4 class="sec-h4">Get expert guidance - questions and discussions</h4>
                                 <p class="sec-p">It's easy to decide a mall where you would like to hang out or a cafe where you would like to go with friends. However, when it comes to deciding the right college or course, we know it's quite a task. So, 'Shiksha Experts' or 'Shiksha Counselors' will help and guide you in making the right decision. For example: If you ask them - which course should I opt for? Or which college is best for animation & multimedia? etc., they will try to give you the best possible advice.</p>
                                 <p class="sec-p">You can ask your questions on 'Ask & Answer'.  Just click on the 'Ask a New Question' bar and post your queries. You can also search for questions from 'Search Ask & Answer'. It will help you in finding questions related to your topic. For example: If you are confused whether to take up MBA or MCA, just type 'MBA or MCA'. The search tool will display questions related to MBA & MCA with their answers.</p>
                                 <p class="sec-p">If you wish to answer a question you have knowledge and information about, just click on the question, and it will take you on the discussion page. Here, click on the 'Add a Comment' bar and share your answer.</p>
                                </section>
                              </div>
                            </div>
                            <div  class="section">
                              
                                <a href="#six" class="accr-a">How do I stay updated with the latest news?</a>
                             
                              <div class="sec-cont" id="six">
                                <section class="ac-sec">
                                  <p class="sec-p">News and articles will keep you up-to-date and well informed about all your education related information needs. You can check news for entrance exam dates, application deadlines, changes in exam pattern, etc , while articles offer you an interesting range of topics to read from topper interviews and exam prep tips to college life and new and upcoming courses and careers... and much more. </p>
                                </section>
                              </div>
                            </div>
                            <div  class="section">
                                <a href="#seven" class="accr-a">Can I unsubscribe to mails?</a>
                              <div id="seven" class="sec-cont">
                                <section class="ac-sec">
                                 <p class="sec-p">Yes, you have the option to unsubscribe. You can do this by unchecking the box shown under 'Communication Preferences' on your Profile homepage. </p>
                               </section>
                              </div>
                            </div>
                            <div  class="section">
                             
                                <a href="#eight" class="accr-a">Can I contact shiksha? </a>
                             
                              <div id="eight" class="sec-cont">
                               <section class="ac-sec">
                                 <p class="sec-p">If you have any feedback or suggestions to share for the website, you can write to us at: feedback@shiksha.com or call on our Student helpline number: 1800-103-5547 (between 09:30 AM to 06:30 PM, Monday to Friday).</p>
                               </section>
                              </div>
                            </div>
                          </div>
                        </div>
                  
                  <!--by students end-->

                  <!--by college start-->
                   <div class="faq-tabpane" id="tab_1">
                          <div class="accordion">
                            
                            <div class="section">
                               <a href="#nine" class="accr-a">How can I add my college/courses on Shiksha? </a> 
                              <div id="nine" class="sec-cont">
                                <section class="ac-sec">
                                 <p class="sec-p">You can add your college/course on Shiksha by registering with us and using <a href="<?=ENTERPRISE_HOME?>/enterprise/Enterprise/loginEnterprise" target="_blank">Client login.</a> </p>
                                </section>
                              </div>
                            </div>
                            <div class="section">
                              <a href="#ten" class="accr-a">My college/course listing is already on Shiksha. How can I modify it?</a>
                             
                              <div id="ten" class="sec-cont">
                                <section class="ac-sec">
                                  <p class="sec-p">If your course/college is already listed on Shiksha and you wish to modify the information, please mail to: <a href="mailto:sales@shiksha.com">sales@shiksha.com</a></p>
                                </section>
                              </div>
                            </div>
                            
                            <div class="section">
                               <a href="#eleven" class="accr-a">How can I advertise on Shiksha?</a>
                              <div id="eleven" class="sec-cont">
                               <section class="ac-sec">
                                 <p class="sec-p">For advertising requirements and queries, please mail to: <a href="mailto:sales@shiksha.com">sales@shiksha.com </a></p>
                               </section>
                              </div>
                            </div>
                            
                          </div>
                   </div>
                 <!--by college end-->

                
                </div>
                
               </div>

              
              <p class="clr"></p>
          </section>

			<p class="clr"></p>
		</div>
	</div>
 </div>


<?php $this->load->view('common/footer');  ?>

<script type="text/javascript">
$j(document).ready(function() {
	$j("ul.faq-tabs li").click(function() {
		if(!$j(this).hasClass('current')){
			$j("ul.faq-tabs li").removeClass("current");
			$j(this).addClass("current");
			$j(".faq-tabpane").hide();
			var activeTab = $j(this).find("a").attr("href");
			$j(activeTab).show();
		}
		return false;
	});
	$j('.accr-a').click(function(e) {
    // Grab current anchor value
    var currentAttrValue = $j(this).attr('href');
    if($j(e.target).is('.active')) {
        close_accordion_section($j(e.target));
    }else {
    	scrollToSection($j(e.target));

        // Add active class to section title
        $j(this).addClass('active');
        // Open up the hidden content panel
        $j('.accordion ' + currentAttrValue).show().addClass('open'); 
    }
    e.preventDefault();
});
});

function scrollToSection(sectionId) {
         additionalTop = 50;
     $j('html, body').animate({
         scrollTop: $j(sectionId).offset().top - additionalTop
     }, 500);
}

function close_accordion_section(obj) {
    $j(obj).removeClass('active');
    $j(obj).siblings('.sec-cont').hide().removeClass('open');
}
 

</script>
