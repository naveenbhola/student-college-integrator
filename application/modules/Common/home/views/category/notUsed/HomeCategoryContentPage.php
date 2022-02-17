<?php
    $locationSelected =  ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) .' '. ucwords($countryNameSelected) ;
    $abroadText = '';
    if($countryNameSelected == 'India' && $cityNameSelected == ''){
        $abroadText = '& Abroad';
    }
	if($subCategorySelected != '' && $subCategorySelected != 'All') {
		$titleText = ucwords($subCategorySelected) .' Career Options - '. ucwords($subCategorySelected) .' Courses Institutes - Important Dates - '. ucwords($subCategorySelected) .' Scholarships' ;
		$metaDescriptionText = 'Find '. ucwords($subCategorySelected) .' Institutes '. ucwords($subCategorySelected).' Courses. Shiksha lists '. ucwords($subCategorySelected) .' institutes along with information for important dates, '. ucwords($subCategorySelected) .' articles and scholarships' ;
		$metaKeywordsText = ucwords($subCategorySelected) .' Institutes, '. ucwords($subCategorySelected) .' Courses, important dates, '.strtolower($subCategorySelected). 'institute, scholarships, '.ucwords($subCategorySelected) .' articles, groups, science, engineering, scholarships, question answers, groups, career options, admissions, exams, results, events, shiksha';
	} else {
		$titleText = ucwords($categoryData['name']) .' Career Options - '. ucwords($categoryData['name']) .' Courses Institutes - Important Dates - '. ucwords($categoryData['name']) .' Scholarships' ;
		$metaDescriptionText = 'Find '. ucwords($categoryData['name']) .' Institutes '. ucwords($categoryData['name']).' Courses. Shiksha lists '. ucwords($categoryData['name']) .' institutes along with information for important dates, '. ucwords($categoryData['name']) .' articles and scholarships' ;
		$metaKeywordsText = ucwords($categoryData['name']) .' Institutes, '. ucwords($categoryData['name']) .' Courses, important dates, '.strtolower($categoryData['name']). 'institute, scholarships, '.ucwords($categoryData['name']) .' articles, groups, science, engineering, scholarships, question answers, groups, career options, admissions, exams, results, events, shiksha';
	}
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
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
        $contentHeaderParams = array(
                                'contentTitle' => $categoryData['name'],
                                'pageType' => 'category'
        );
        $contentPanelsHeight = array(
            'impDatesPanelHeight' => 400,
            'articlesPanelHeight' => 459,
            'msgBoardPanelHeight' => 389,
            'groupsPanelHeight' => 389,
			'mostViewCoursePanelHeight' => 295,
            'scholarshipsPanelHeight' => 339,
            'courseInstitutePanelHeight' => 295,
        );
        $categoryData = array_merge($contentPanelsHeight, $contentPanelsHeight);
    ?>
	<?php	$this->load->view('home/category/HomeContentPageHeader', $contentHeaderParams);?>
    <div class="lineSpace_20">&nbsp;</div>
	<?php	$this->load->view('home/category/HomePageSnippetWidget');?>
    <div class="lineSpace_15">&nbsp;</div>
	<div style="width:100%">
		<!--Start_Examination_And_Financial-->
		<div class="float_L" style="width:49.9%;">
			<div style="margin-right:5px;">
				<?php $this->load->view('home/shiksha/HomeEventsPanel', $categoryData); ?>
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
	<div style="width:100%">
		<!--Start_Most_View_Course-->
		<div class="float_L" style="width:49.9%;">
			<div style="margin-right:5px;">
				<?php $this->load->view('home/shiksha/HomeInstituteCoursePanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Most_View_Course-->

		<!--Start_Schoolarship-->
		<div class="float_L" style="width:50%">
			<div style="margin-left:5px;">
				<?php $this->load->view('home/shiksha/HomeScholarshipsPanel', $categoryData); ?>
			</div>
		</div>
		<!--End_Schoolarship-->
		<div class="clear_L"></div>
	</div>
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
