<?php
		$headerComponents = array(
								'css' => array('static'),
								'js'	=>	array('common'),
								'title'	=>	'Kum Kum Tandon - India\'s pioneering career counselor - Kum Kum Tandon Profile – Career Counselor',
								'metaKeywords'	=>'Shiksha, MBA, IIT JEE, GMAT, CAT, MAT, coaching, institutes, coaching institutes, study circle, competitive exams, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
								'metaDescription' => 'Kum Kum Tandon is a leading figure in career guidance and counselling in India since 1986. Author of several books on the subject she has also successfully initiated and developed an integrated approach to career counselling by including parents and teachers in the process. She is an inspiring counsellor with profound knowledge and experience and has been providing educational, vocational, personal guidance and counselling to students of leading schools in India and abroad. For several years she has been a regular columnist in newspapers and magazines', 								
								'tabName'	=>	'Articles',
								'taburl' =>  site_url('blogs/shikshaBlog/blogHome'),
								'category_id'   => (isset($CategoryId)?$CategoryId:1),
                                                                'country_id'    => (isset($country_id)?$country_id:2),
                                                                'product' => 'Articles',   
                                                                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                                'callShiksha'=>1
							);
		//$this->load->view('common/header', $headerComponents);
		$this->load->view('common/homepage', $headerComponents);
?>
<div class="mar_full_10p">&nbsp;&nbsp;<a href="/">Home</a> &gt; Kumkum</div>
<div style="line-height:6px">&nbsp;</div>
<!--End_Navigation-->
			<div style="width:100%; float:left">
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>

					<div class="boxcontent_lgraynoBG">				  		
						<div class="mar_full_10p fontSize_12p">
							<div class="OrgangeFont fontSize_14p bld">About the author - Kum Kum Tandon</div>
                            <p style="margin-top:8px">Consultant Career Counselor & Author</p>
                            <div class="clearFix spacer10"></div>
							<p>
								<img src="/public/images/kumkum.gif" align="left" />Kum Kum Tandon has been a leading career counselor and expert in career guidance for over three decades. Her academic qualifications include MA (Psychology), M.Ed., Diploma in Educational. Psychology, Vocational Guidance & Counselling (NCERT, Delhi). She has developed a unique and integrated approach to career counseling, which encompasses both parents and teachers in the key decision‐making processes.
							</p>
                            
                            <p style="margin-top:10px">
                            	Mrs. Tandon's interest and passion in providing career guidance to students inspired her to author her widely acclaimed books 'Career Options After 10+2 and Beyond' and 'Study Abroad'. Her profound knowledge and experience has inspired thousands of students both in India and abroad.
                            </p>
							
                            <p style="margin-top:10px">
                            	Mrs Tandon has been a consultant career counselor with shiksha.com since 2008. She has developed Career central to provide career information and guidance using individual interests based on factors of interests (Guilford et al) to guide the process of career search on shiksha.com.<br/><br/>For queries on careers or guidance her email is  <a href="mailto:kktandon2003@hotmail.com" class="fontSize_12p"> kktandon2003@hotmail.com </a>.
                            </p>

			    <!--<p style="margin-top:15px">
				<div class="profile-view bld">
					<a href="<?php //echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList?type=kumkum';?>">View all articles from Mrs. Tandon's books here</a>
				</div> 
			    </p>-->

							<div class="clearFix"></div>
						</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_35">&nbsp;</div>
			</div>
</div>
<?php
$this->load->view('common/footer');
?>			
