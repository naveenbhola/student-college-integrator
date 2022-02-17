<?php
    $locationSelected =  ucwords($cityNameSelected == 'All' || is_numeric($cityNameSelected)? '' : $cityNameSelected) .' '. ucwords($countryNameSelected) ;
    $abroadText = '';
    if($countryNameSelected == 'India' && $cityNameSelected == ''){
        $abroadText = '& Abroad';
    }
		$titleText = 'Study Abroad '. ucwords($locationSelected) .' - Study in '. ucwords($locationSelected) .' - '. ucwords($locationSelected) .' Colleges Universities - Enterance Examinations - '. ucwords($locationSelected) .' Scholarships - Career Options' ;
		$metaDescriptionText = 'Find information for Study Abroad '. ucwords($locationSelected) .', Study in '. ucwords($locationSelected) .', '. ucwords($locationSelected) .' Colleges Universities, Entrance Examinations, '. ucwords($locationSelected) .' Scholarships, Career and education loans for studying in '. ucwords($locationSelected) .'. Site lists '. ucwords($locationSelected) .' colleges and universities along with career options in '. ucwords($locationSelected) .'.';
		$metaKeywordsText = 'Study in '. ucwords($locationSelected) .', career options in '. ucwords($locationSelected) .', studying in '. ucwords($locationSelected) .', Entrance Examinations, GRE, TOEFL, LSAT, GMAT, IELTS, Education loans, Scholarships, Courses, Institutes, accommodation in '. ucwords($locationSelected) .', '. ucwords($locationSelected) .' institutes, courses in '. ucwords($locationSelected) .', '. ucwords($locationSelected) .' study visa, '. ucwords($locationSelected) .' study permits, '. ucwords($locationSelected) .' traveling tips, shiksha, '. ucwords($locationSelected) .', education';
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
											//$categoryData['page'],
											//'events'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'cityList',
											'home',
										),
								'title'	=>	$titleText,
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP'),'tabName'	=>	'Event Calendar',
								'product'	=>	'home',
							    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'metaKeywords'	=>$metaKeywordsText,
								'metaDescription' => $metaDescriptionText
							);
		$this->load->view('common/homepage', $headerComponents);
        global $criteriaArray;
        $criteriaArray = array(
                'category' => $categoryId,
                'country' => $countryId,
                'city' => $selectedCity,
                'keyword'=>''
                );
?>

<script>
var SITE_URL = '<?php echo base_url(); ?>';
var collegeList = eval(<?php echo $collegeList; ?>);
</script><!--Start_Mid_Container-->
<!--Start_Center-->
<div class="mar_full_10p">
    <input type="hidden" id="categoryId" value="<?php echo $categoryId?>"/>
    <input type="hidden" id="countryId" value="<?php echo $countryId?>"/>
	<!--Start_Mid_Panel-->

    <?php 
		if($countryNameSelected=='newzealand'){
			$countryNameSelected='New Zealand';
		}
        $contentHeaderParams = array(
                                'contentTitle' => $countryNameSelected,
                                'pageType' => 'country'
        );
        $contentPanelsHeight = array(
            'examStudyCountryPanelHeight' => 402,
			'educationLoanPanelHeight' => 98,
			'articlesPanelHeight' => 412,
			'scholarshipsPanelHeight' => 282,
			'impDatesPanelHeight' => 215,                                    
            'mostViewCoursePanelHeight' => 235,
			'groupsPanelHeight' => 400,
            'msgBoardPanelHeight' => 400,
			'courseInstitutePanelHeight' => 175,
        );
        $categoryData = array_merge($contentPanelsHeight, $contentPanelsHeight);

    ?>
	<?php	$this->load->view('home/category/HomeContentPageHeader', $contentHeaderParams);?>
    <div class="lineSpace_15">&nbsp;</div>
	<?php	$this->load->view('home/category/HomePageSnippetWidget');?>
    <div class="lineSpace_15">&nbsp;</div>
	
	<div style="width:100%">
		<!--Start_Examination_And_Financial-->
		<div class="float_L" style="width:49.9%;">
			<div style="margin-right:5px;">
				<?php $this->load->view('home/category/HomeExamAndFinancialPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Examination_And_Financial-->

		<!--Start_Articiles-->
		<div class="float_L" style="width:50%">
			<div style="margin-left:5px;">
				<?php $this->load->view('home/category/HomeBlogsPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Articiles-->
		<div class="clear_L"></div>
	</div>
	
	<div class="lineSpace_1">&nbsp;</div>
	<div style="width:100%">
		<!--Start_Schoolarship-->
		<div class="float_L" style="width:49.9%;">
			<div style="margin-right:5px;">
				<?php $this->load->view('home/shiksha/HomeScholarshipsPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Schoolarship-->

		<!--Start_ImportantDates-->
		<div class="float_L" style="width:50%">
			<div style="margin-left:5px;">
				<?php $this->load->view('home/shiksha/HomeEventsPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_ImportantDates-->
		<div class="clear_L"></div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div style="width:100%">
		<!--Start_Most_View_Course-->
		<div>			
			<?php $this->load->view('home/shiksha/HomeInstituteCoursePanel', $categoryData); ?>
		</div>
		<!--End_Most_View_Course-->
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div style="width:100%">
		<!--Start_Institute_Group-->
		<div class="float_L" style="width:49.9%;">
			<div style="margin-right:5px;">
				<?php $this->load->view('home/shiksha/HomeGroupsPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Institute_Group-->

		<!--Start_QuestionAnswer-->
		<div class="float_L" style="width:50%">
			<div style="margin-left:5px;">
				<?php $this->load->view('home/shiksha/HomeMsgBoardPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_QuestionAnswer-->
		<div class="clear_L"></div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
<!--End_Mid_Panel-->
<br clear="all" />
</div>
<!--End_Center-->
<!--End_Mid_Container-->
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
<script>
	var SITE_URL = '<?php echo base_url(); ?>';
	var collegeList = eval(<?php echo $collegeList; ?>);
	
	var pageName = '<?php echo  $categoryData['page']; ?>';
	var selectedCategoryId = '<?php echo $categoryId; ?>';
</script>
