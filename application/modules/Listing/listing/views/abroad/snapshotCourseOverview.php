<!-- Header code Starts-->
<?php 
//$seoData = $courseObj->getMetaData();
//$seoData['seoUrl'] = $courseObj->getUrl();
$headerComponents = array(
	'css'=>array('studyAbroadListings', 'studyAbroadCommon'),
	// 'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),
	'canonicalURL'      => $seoData['seoUrl'],	
	'title'             => $seoData['seoTitle'],
	'metaDescription'   => $seoData['seoDescription'],
	'metaKeywords'      => $seoData['seoKeywords'],
	'pgType'	        => 'snapshotPage'
);

$this->load->view('common/studyAbroadHeader', $headerComponents);

// echo jsb9recordServerTime('SHIKSHA_ABROAD_COURSE_DETAIL_PAGE',1);
?>
<!--Header code Ends-->




<?php
//BreadCrumb view File
$this->load->view('listing/abroad/widget/breadCrumbs');
?>
<div class ="content-wrap clearfix">
    <div id="left-col">
        <div id="course-title" class="course-title" style="position:relative">
            <h1><?=htmlentities($snapshotCourse['course_name'])?></h1>
            <div class="snapshot-box clearwidth">
		<p>Course type: <strong><?=$snapshotCourse['course_type']?></strong></p>
		<p>Complete details of this course are not available right now.</p>
		
		<?php if($snapshotCourse['website_link'] != ''){?>
		    <?php if(0 === strpos($snapshotCourse['website_link'],'http')){
			$website_link = $snapshotCourse['website_link'];
		    }else{
			$website_link = 'http://'.$snapshotCourse['website_link'];
		    }
		    ?>
		    <a href="<?=$website_link?>" target="_blank" rel="nofollow" class="button-style view-size"><strong>View course details on university website</strong><i class="common-sprite view-icon"></i></a>
		<?php }?>
		
		<div class="clearfix"></div>
		<?php if(!$snapshotRequestFlag){?>
		    <p id="req_query">Or request Shiksha.com to <a href="javascript:void(0);" onclick="reqConvertDetail();">add more details to this course</a></p>
		    <p id="req_thank" style="display: none"><strong><span class="tick-mark">&#10004;</span>Thank you for requesting</strong>, meanwhile you can view this course details on the university website by clicking the above button.</p>
		<?php }?>
	    </div>
        </div>
        <div id="mediaForStudyAbroadPage"></div>
	
	<?php if(!empty($similarCoursesData)) { ?>
	    <div class="other-course-box widget-wrap clearwidth" style="border:0 none;">
		<h2>Other Courses at this university</h2>
		<ul class="gray-list-item">
		    <?php foreach( $similarCoursesData as $similarCourseObj ) { ?>
		    <li><a href="<?=$similarCourseObj["url"]?>"><?=htmlentities($similarCourseObj["name"])?></a>
		    <!--<span style="color:#999; display:block;">(2 years / full time)</span>-->
		    </li>
		    <?php } ?>
		</ul>
		<?php $stateName = ($universityObj->getLocation()->getState()->getName())? ', '.$universityObj->getLocation()->getState()->getName():'';?>
		 <a href="<?=$universityObj->getURL().'#coursesOfUniversitySec'?>" class="flRt">View all courses offered by <?=$universityObj->getName().$stateName?>  &gt;</a> 
	    </div>
	<?php } ?>
	
   <?php
	//$this->load->view('listing/abroad/widget/similarCoursesRecommendations');
    ?>
	<?php //echo modules::run('abroadContentOrg/AbroadContentOrgPages/getContentOrgWidget'); ?>
    </div>    
    
    <?php
        $this->load->view('listing/abroad/rightColumn');
    ?>
 <img id ='beacon_img' src="/public/images/blankImg.gif" width=1 height=1 style="visibility: hidden;" />
</div>

<!-- Footer code Starts-->
<?php 	$footerComponents = array(
			    'js'                => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
                'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script crossorigin src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("studyAbroadListings"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinycarousel.min"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.royalslider.min"); ?>"></script>

<script>
<?php if($snapshotCourse['university_id'] != '') {?>
$j('#mediaForStudyAbroadPage').load('/listing/abroadListings/getMediaForAbroadListing/<?=$snapshotCourse['university_id']?>/university');
<?php }
    if($validateuser != 'false'){
	$userId = $validateuser['0']['userid'];
    }else{
	$userId = 0;
    }
?>

function reqConvertDetail() {
    $j.ajax({'url':'/listing/abroadListings/trackSnapshotRecord',
	     'async': false,
	     'type':'POST',
	     'data':{'userId':<?=$userId?>,'snapshotCourseId':<?=$snapshotCourse['snapshotCourseId']?>,'universityId':<?=$snapshotCourse['university_id']?>},
	     'success': function(response){
		    try{
			var rsp = JSON.parse(response);
			if (rsp == 1) {
			    $j('#req_query').hide('slow');
			    $j('#req_thank').show();
			}
		    }catch(e){}
		}
	});
}

var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011006/<?=$snapshotCourse['course_id']?>+<?=$listingType?>';

</script>
<!-- Footer code Ends-->