<?php
        $criteriaArray = array('keyword'=> (!isset($validateuser[0]['displayname']) || $validateuser[0]['displayname'] == '' ?'NOT-LOGGED-IN':''));
		$headerComponents = array(
								'css'	=>	array(
											'homepage'										),
	                            'js'         =>            array('common'),
								'jsFooter'	=>	array(
											'home'
										),
								'title'	=>	'Education India - Search Colleges, Courses, Institutes, University, Schools, Degrees - Forums - Results - Admissions - Shiksha.com',
                                'taburl' =>  site_url(),
								'tabName'	=>	'Event Calendar',
								'product'	=>	'home',
								'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP', 'shikshaCriteria' => $criteriaArray),
								'IS_HOME_PAGE' =>	true,

							 'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
							 'metaDescription' => 'Search Colleges, Courses, Institutes, University, Schools, Degrees, Education options in India. Find colleges and universities in India & abroad. Search Shiksha.com Now! Find info on Foreign University, question and answer in education and career forum. Ask the education and career counselors.',
                              'metaKeywords'	=>'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships'
							);
							if($partnerFlag === true) {
								$headerComponents['partnerPage'] = $partner;	
								$this->load->view('common/homepage', $headerComponents);
							} else {
								$this->load->view('common/homepage', $headerComponents);
							}
?>

<!--Start_Mid_Container-->
<!--Start_Center-->
<div class="mar_full_10p">
	<?php $this->load->view('home/shiksha/HomeRightPanel');?>
	
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_noLpanelhome" style="margin-right:310px">
		<?php $this->load->view('home/shiksha/HomeCategoryPanel');?>	
		<div class="lineSpace_8">&nbsp;</div>
		
		<!--Start_Ask_Discussion_Articles-->		
		<div style="display:inline; float:left; width:100%">
			<?php 
				$this->load->view('home/shiksha/HomeInfosection',$homePageData);
			?>
			<?php $this->load->view('home/shiksha/HomeEventsPanelShiksha',$homePageData);?>
		</div>
		<!--End_Ask_Discussion_Articles-->
		<div class="lineSpace_10">&nbsp;</div>
		<?php $this->load->view('home/shiksha/HomeFeaturedCollegePanel');?>	
		<div class="clear_L"></div>
		<div class="lineSpace_8">&nbsp;</div>
  	</div>
  	
		
	<!--End_Mid_Panel-->
	<br clear="all" />
	<div class="lineSpace_10">&nbsp;</div>
</div>
<!--End_Center-->
<!--End_Mid_Container-->
<?php
	$this->load->view('common/footer', array('pageId'=>'','pageZone'=>''));
?>
<script>
	var pageName = 'SHIKSHA_HOME_PAGE';
	var featuredColleges = eval(<?php echo json_encode($featuredColleges); ?>);
	selectHomeTab('featuredCategory','Management','');
/*    var popupObj = window.open('http://sites.shiksha.com/edufairs/banner/250x250/shiksha_250x250.html','_blank','width=250, height=250, fullscreen=no, channelmode=no,menubar=no,scrollbars=no,titlebar=no,toolbar=no');
    self.focus();
    if(popupObj) popupObj.blur();
    */
</script>
