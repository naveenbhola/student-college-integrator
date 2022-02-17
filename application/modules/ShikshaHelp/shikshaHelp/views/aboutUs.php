<?php
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
				          'css' => array('static'),
						  'js'	=>	array('common'),
						'title'	=>	'About Us',
						'tabName' =>	'About Us',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaKeywords'	=>'Some Meta Keywords',
						'product'	=>'aboutus',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                'callShiksha'=>1,
						'canonicalURL' => $current_page_url
					);
		$this->load->view('common/header', $headerComponents);
?>
<link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/aboutus" >
<div class="clearFix"></div>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:14px; padding-left: 10px; padding-right:10px; font-weight:bold; color:#FD8103;">About  Shiksha.com</div>
<div style="height:4px;">&nbsp;</div>
<div style="height:1px;  background-color:#EAE9E9; overflow:hidden; padding-left: 10px; padding-right:10px;">&nbsp;</div>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:11px; padding-left: 10px; padding-right:10px; line-height:16px; ">
<div style="height:14px;">&nbsp;</div>
<p  style="font-size:12px; margin-bottom:12px">
<img src="/public/images/education-about.jpg" width="215" height="157" hspace="2" vspace="2" align="left" />Shiksha.com is a one-stop-solution making course and college selection easy for students looking to pursue undergraduate (UG) and postgraduate (PG) courses in India and abroad; also accessible to users on the move through the website’s mobile site. Launched in 2008, Shiksha.com belongs to Info Edge (India) Ltd, the owner of established brands like Naukri.com, 99acres.com, Jeevansathi.com, among several others. With this strong brand pedigree, Shiksha offers its users the unique privilege of customised tools like Alumni Employment Statistics that includes salary data powered by Naukri.com. 
</p>

<p style="font-size:12px; margin-bottom:12px">
Our website is a repository of reliable and authentic information for over 14,000 institutions, 40,000 plus courses and has a registered data base of more than 3.5 million students. We offer specific information for students interested in UG/PG courses in India (shiksha.com) and Abroad (studyabroad.shiksha.com) across the most popular  educational streams – Management; Science & Engineering; Banking & Finance; Information Technology; Animation, VFX, Gaming & Comics; Hospitality, Aviation & Tourism; Media, Films & Mass Communication; Design; Medicine, Beauty & Health Care; Retail; Arts, Law, Languages & Teaching; and Test Preparation.
</p>
<p style="font-size:12px; margin-bottom:12px">
Education seekers get a personalised experience on our site, based on educational background and career interest, enabling them to make well informed course and college decisions. The decision making is empowered with easy access to detailed information on career choices, courses, exams, colleges, admission criteria, eligibility, fees, placement statistics, rankings, reviews, scholarships, latest updates etc as well as by interacting with other Shiksha.com users, experts, current students in colleges and alumni groups. We have introduced several student oriented products and tools like Career Central, Common Application Form, Top Colleges, College Compare, Alumni Employment Stats, Campus Connect, College Reviews, College Predictors, MyShortlist and Shiksha Café.
</p>
<p style="font-size:12px; margin-bottom:12px">
Our active ask and answer community called Shiksha Café has over 1000 experts answering career and college related queries. Students can ask questions, participate in discussions and stay updated with latest news, articles related to their education interest. Shiksha.com is India’s smartest college gateway that blends higher education related domain knowledge, with technology, innovation, and credibility to give students  personalised insights to make informed career, course and college decisions.
</p>

</div>
<div class="spacer15 clearFix"></div>
<?php $this->load->view('common/footer');  ?>
