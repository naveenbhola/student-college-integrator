<?php
		$titleText = 'Shiksha.com – MBA – IIT JEE – GMAT – CAT – MAT – Coaching Institutes – Study Circle – Entrance Coaching institutes';
		$metaDescriptionText = 'Search coaching institutes for all India entrance tests , MBA, IIT JEE, GMAT, CAT, MAT. Find study circles and institutes which offer various coaching programs for all India Competitive exams. Search now on Shiksha.com';
		$metaKeywordsText = 'Shiksha, MBA, IIT JEE, GMAT, CAT, MAT, coaching, institutes, coaching institutes, study circle, competitive exams, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
										//	$categoryData['page'],
											'events'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'exams',
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
</script>
<div class="mar_full_10p normaltxt_11p_blk_arial">
    <div style="width:266px; float:right">
	<?php $this->load->view('home/category/HomeExamDetailPageRightPanel'); ?>	
    </div>
		<div style="margin-right:276px">
			<div>
                <?php $this->load->view('home/category/HomeExamDetails'); ?>
				<div class="lineSpace_10">&nbsp;</div>
                <?php $this->load->view('home/category/HomeExamDetailPageFurtherReadingPanel'); ?>
				<div class="lineSpace_10">&nbsp;</div>
                <?php $this->load->view('home/category/HomeExamPageCollegesPanel'); ?>
				<div class="lineSpace_10">&nbsp;</div>
                <?php 
                    $details['parentCatsForMB'] = '';
                    $listingType = '';
                    $quesContents = array(
                        'details' => array('parentCatsForMB'=>$boardId),
                        'listingType' => 'blogs',
                        'listingTypeId' => $blogId,
                        'fullUrl' => "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']
                    );
                    $this->load->view("listing/relatedQns", $quesContents); 
                ?>
				<div class="lineSpace_10">&nbsp;</div>
			</div>		
		</div>
</div>
<img id = 'beacon_img' width=1 height=1 />
<script>
    var img = document.getElementById('beacon_img');
    var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0000003/<?php echo $selectedExamCategory; ?>';
</script>
<div class="lineSpace_10">&nbsp;</div>
<?php 
    $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
