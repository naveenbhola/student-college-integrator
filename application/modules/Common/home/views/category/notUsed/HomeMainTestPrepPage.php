<?php
$locationSelected = ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) . ' ' . ucwords($countryNameSelected);
$abroadText = '';
if ($countryNameSelected == 'India' && $cityNameSelected == '') {
	$abroadText = '& Abroad';
}
	$titleText = 'Test Preparation Competitive Exams - MBA  Competitive Exam - Engineering - Medical Competitive Exams - Foreign Education - Government Sector';
	$metaDescriptionText = 'Find information on Test preparation for Competitive Exams. Shiksha lists coaching institutes for MBA competitive exam, Engineering, Medical competitive exams and Government Sector entrance exams along with foreign education and computer certification exams';
	$metaKeywordsText = 'Test Preparation Competitive Exams, MBA Exams, Engineering Exams, Medical Exams, Foreign Education Exams, Government Sector Exams, coaching institutes, competitive exams, entrance exam, MBA, IIT JEE, MAT, CAT, XAT, AIEEE, IAS, NDA, MBA competitive Exams, Medical Competitive Exams, foreign education, computer certification,  scholarships, engineering, mba, entrance exams, events, coaching centers, GMAT, coaching, mbbs';

$headerComponents = array (
	'css' => array (
		'raised_all',
		'header',
		'mainStyle'
	),
	'js' => array (
		'common',
		'prototype',
		'cityList',
		'home',
		
	),
	'title' => $titleText,
	'taburl' => site_url(),
	'bannerProperties' => array (
		'pageId' => 'CATEGORY',
		'pageZone' => $categoryData['page'] . '_TOP'
	),
	'tabName' => 'Event Calendar',
	'product' => 'home',
	'displayname' => (isset ($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
	'metaKeywords' => $metaKeywordsText,
	'metaDescription' => $metaDescriptionText
);
$this->load->view('common/homepage', $headerComponents);
?>
<div class="mar_full_10p">
	<div><h1>Test Preparation Competitive Exams</h1></div>
	<div class="lineSpace_5">&nbsp;</div>
</div>
<div class="mar_full_10p" style="">
	<div>
<?php
foreach ($testPrepExams as $testPrepExamListElementId => $testPrepExamListElement) {
	foreach($testPrepExamListElement as $testPrepExamListElementKey => $testPrepExamListElementValue) {
		$$testPrepExamListElementKey = $testPrepExamListElementValue;
	}
    $resultKey = $testPrepExamListElementId;
    if(!is_array($snippet)) { continue; }
    $parentUrl = '/shiksha/showTestPrepContentPage/'.  $snippet['blogId'] ;
	$snippetBlogUrl = $snippet['url'];
?>
		<div class="float_L" style="width:49%; margin:0 4px">
			<div class="">
				<div class="careerOptionPanelBrd">		
					<div class="careerOptionPanelStudyAbroad"><a href="<?php echo $parentUrl .'/All/'. $snippet['blogTitle']; ?>" title="<?php echo $snippet['blogTitle']; ?>"><?php echo $snippet['blogTitle']; ?></a></div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="pd_full_0_10">
                        <div style="height:115px;">
							<div style="height:106px; float:left;">
							<a href="<?php echo $parentUrl .'/All/'. $snippet['blogTitle']; ?>" title="<?php echo $snippet['blogTitle']; ?>" style="background:url(<?php echo str_replace('_m', '_b',$snippet['blogImageURL']);?>) no-repeat 0 0;height:106px; float:left; padding-left:181px;display:block"></a></div>
							<div>
								<a href="<?php echo $parentUrl .'/All/'. $snippet['blogTitle']; ?>" title="<?php echo $snippet['blogTitle']; ?>" class="blackFont"><?php echo substr((($snippet['summary'] != '') ? $snippet['summary'] : strip_tags($snippet['blogText'])), 0, 300); ?></a>
							</div>
							<div class="txt_align_r"><a href="<?php echo $snippetBlogUrl; ?>">Read More..</a></div>
							<div class="clear_L lineSpace_10">&nbsp;</div>
							
						</div>
						<div class="bld careerSubHeadingColor fontSize_12p">Popular Exams</div>
						<div class="lineSpace_3">&nbsp;</div>
						<div class="dottedLine"><img src="/public/images/dotted.gif" /></div>
						<div class="lineSpace_5">&nbsp;</div>
						<div style="height:25px;">
						        <?php
									$seperator = '';
									foreach($examList as $exam) {
										$examName = $exam['acronym'];
										$examTitle = $exam['blogTitle'];
                                        $examUrl= $parentUrl .'/'.$exam['blogId'] .'/'. $snippet['blogTitle'] .'/'. $examName;
                                        if($examName == '') { continue; }
								?>
								<?php echo $seperator; ?><a href="<?php echo $examUrl; ?>" class="fontSize_12p" title="<?php echo $examTitle; ?>"><?php echo $examName; ?></a>
								<?php									
										$seperator =  ', ';
									}
								?>
						</div>
						<div class="lineSpace_10">&nbsp;</div>												
						<div class="brd careerOptionPanelShowInfo">
							<div class="lineSpace_5">&nbsp;</div>
                            <?php 
                                $criteria = array('exam' => $resultKey);
                                $productsCount = array('resultKey'=>$resultKey, 'productsCount' => $productsCountForTestPrep, 'pageType'=>'exam', 'examId' =>  $snippet['blogId'], 'criteria'=> $criteria, 'examName'=>$snippet['blogTitle']);
                                $this->load->view('home/category/HomeMainPagesProductsCountWidget', $productsCount); 
                            ?>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				</div>
			</div>			
						<div class="lineSpace_15">&nbsp;</div>
		</div>
        <?php
            }
        ?>
		
		<div class="clear_L"></div>
	</div>
</div>

				<div class="lineSpace_20">&nbsp;</div>
<?php
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
