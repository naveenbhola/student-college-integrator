<?php
$subExams = '';
foreach($subCategories as $subCategory) {
    if($subExams != "") { $subExams .= ", ";}
    $subExams .= $subCategory['acronym'] != '' ? $subCategory['acronym'] : $subCategory['blogTitle'];
}
		$titleText = $blogTitle .' - Exam Dates - '. str_replace(',',' -', $subExams) . ' - '. $blogTitle .' Coaching Institutes - '. $blogTitle .'Institutes';
		$metaDescriptionText = 'Find information for '. $blogTitle .', '. $blogTitle .' Exam Dates, '. $subExams .'. Shiksha lists '. $blogTitle .' coaching institutes along with '. $blogTitle .' articles';
		$metaKeywordsText = $blogTitle .', '. $subExams;
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
											'events'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'exams',
											'home'
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
?>

<script>
//var SITE_URL = '<?php echo base_url(); ?>';
</script>
<div class="mar_full_10p normaltxt_11p_blk_arial">
    <?php 
        $contentHeaderParams = array(
                                'contentTitle' => $blogTitle,
                                'pageType' => 'exam'
        );
        $contentPanelsHeight = array(
            'impDatesPanelHeight' => 805,
            'articlesPanelHeight' => 367,
            'groupsPanelHeight' => 440,
			'testPrePanelHeight' => 222,
			'admissionPanelHeight' => 222,
			'mostViewCoursePanelHeight' => 265,
			'msgBoardPanelHeight' => 380,            
        );
        $categoryData = array_merge($contentPanelsHeight, $contentHeaderParams);

    ?>
	<?php	$this->load->view('home/category/HomeContentPageHeader', $categoryData);?>
    <div class="lineSpace_20">&nbsp;</div>
	<?php	$this->load->view('home/category/HomePageSnippetWidget');?>
    <div class="lineSpace_15">&nbsp;</div>
    <div>
		<div class="float_L" style="width:49.9%;">
        	<div style="margin-right:5px;">
    	    <?php $this->load->view('home/shiksha/HomeEventsPanel',$categoryData);?>
            </div>
        </div>
		<div class="float_L" style="width:50%">
			<div style="margin-left:5px;">
	        	<?php $this->load->view('home/category/HomeBlogsPanel',$categoryData);?>
				<div class="lineSpace_10">&nbsp;</div>
				<?php $this->load->view('home/shiksha/HomeTestPrepGroupsPanel',$categoryData);?>
            </div>
        </div>
        <div class="clear_L"></div>
    </div>

    <?php// $this->load->view('home/category/HomeExamDetailPageFurtherReadingPanel'); ?>
    <?php $this->load->view('home/category/HomeTestPrepCollegesPanel',$categoryData); ?>
    <div class="lineSpace_10">&nbsp;</div>
    <?php $this->load->view('home/shiksha/HomeMsgBoardPanel', $categoryData);?>
    <div class="lineSpace_10">&nbsp;</div>
</div>		
<div class="lineSpace_10">&nbsp;</div>
<?php 
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
