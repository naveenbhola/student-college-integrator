<?php
$tempJsArray = array('myShiksha','user');
$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js' => array('common','facebook','ajax-api','imageUpload','ana_common','processForm','campusConnectHome'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $m_meta_title,
                'metaDescription' => $m_meta_description,
                'canonicalURL' =>$canonicalURL,
                'product'       =>'campusConnect',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

$this->load->view('common/header', $headerComponents);

?>
<script
        type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
        $j = jQuery.noConflict();
	var instituteId = '<?=$instituteId?>';
	
</script>
<?php $this->load->view( 'messageBoard/headerPanelForAnA',array('collegePredictor' => true) );?>

<div id="content-wrapper-div">
<!--start campus-college-sub-container--><div class="managment-studies-main-container">
<div id="management-left">
	
<?php

if(isset($_COOKIE['intermediateCookie']) && $_COOKIE['intermediateCookie'] == 'askQuestion') {
    
	$this->load->view('campus_connect/askIntermediate');
}
	
?>	
	
<div class="managment-studies-box gap-bottom">

	<div class="managment-heading-container" style="height: auto;">
		<div class="managment-heading-sub-container">
			<div class="managment-heading-left" style="width:100%;">
			  <h1 style="width: 100%; margin:0; padding:6px 6px 0 6px;box-sizing:border-box;" class="clear-width">
			<a href="<?php echo SHIKSHA_HOME.'/mba/resources/ask-current-mba-students'; ?>" title="Back"style="margin:0px;"><img width="12" height="16" src="/public/images/arrow-img.jpg" style="margin-top:2px;"></a>	
			  <!--<span style="" class="flLt">&lt;</span>-->
			  <div style="padding-left:18px;"><span	><?php echo $instituteName;?>,</span><b style="font-weight: normal; padding-left: 4px;"><?php echo $cityName; ?></b></div></h1>
			<div class="clear-width" style="margin-bottom:8px;">	
			<div class="flLt" style="margin:10px 0 0 24px; width:350px;"><a href="<?=$courseURL?>"> <?php echo $courseName; ?></a></div>
			<div class="managment-heading-right" style="margin-top:8px;">
				<div class="revwListBx2 bx3">
				<?php if(is_array($naukridata[$instituteId]) && !empty($naukridata[$instituteId])) { ?><a href="<?=$courseURL.'#naukri_widget_data'?>">View Placement Data</a><?php } ?> <?php if(is_array($courseReviews[$courseIdforHeader]) && !empty($courseReviews[$courseIdforHeader])) { ?><?php if(!empty($naukridata[$instituteId]) && !empty($courseReviews[$courseIdforHeader])) { ?> <a href="#"> | </a> <?php } ?> <a href="<?=$courseURL.'#course-reviews'?>">Read Reviews</a><br>
					<div class="rv_ratng rv"> Alumni Rating:<span><?php echo $courseReviews[$courseIdforHeader]['overallRating'] ?><b>/5</b></span></div><?php } ?>
				</div>
			</div>
			</div>
			</div>
			
		
		
		</div>
	</div>

	<div class="successfully-ques-container">
		<h2><span>Your question has been successfully posted.</span></h2>
		<p>We will update you as soon as we get an answer from a Current Student of this college. <br>
		In the meantime, you can also...</p>
		<div class="successfully-ques-container-bootom-link ">
		<ul>
		<li><a href="#">Ask another question</a> |</li>
		<li> <a href="#">View details of this college</a> |</li>  
		<li><a href="#">Ask question to other colleges</a></li>
		</ul>
		</div>
	</div>

</div>

<div class="managment-studies-box" style="border-top:none !important">

  <div class="tab-nav-bar">
  <h2 id="RecentQuestionsTab" style="cursor:pointer; width:33.2%; text-align: center; border-right:1px solid #ccc"><a style="width:100%; padding:0;" class="active" onclick="showQuestions('Recent')">Recent Questions</a></h2>
  <h2 id="FeaturedQuestionsTab" style="cursor:pointer; width:33.2%; text-align: center; border-right:1px solid #ccc"><a style="width:100%; padding:0" onclick="showQuestions('Featured')">Featured</a></h2>
  <h2 id="MostViewedQuestionsTab" style="cursor:pointer; width:33.2%; text-align: center;"><a style="width:100%; padding:0" onclick="showQuestions('MostViewed')">Most Viewed</a></h2>
  </div>
  <!--start ques-tab-bar--><div class="ques-tab-bar">
  <div id="questionstab" class="campus-college-sub-container2 campus-college-sub-container3" style= "
   height: 500px;
   width: 96%;
    overflow-y: hidden;
    overflow-x:hidden ">
  <?php $this->load->view('campus_connect/questionTab'); ?>
  </div>
 </div>  <!--end ques-tab-bar-->
  
  
</div>
	
    <!--ask-question-container-->
<?php

	if(!isset($_COOKIE['intermediateCookie']))
	{
		
		$this->load->view('campus_connect/askIntermediate');
		
	}
	
?>
	
    <!--campus-connect-static-container-->
    <?php $this->load->view('campus_connect/staticWidget'); ?>

</div>
<div id="management-right">
	
    <!--current-student-container-->
    <?php //$this->load->view('campus_connect/currentStudentIntermediateWidget'); ?>

    <!--most-viewed-colleges-container-->
    <?php //$this->load->view('campus_connect/mostViewdIntermediateWidget'); ?>
    <!--campus-connect-shortlist-container-->
     <div class="shortlisting-box-intermediate">
    <?php $this->load->view('examPages/widgets/examPageShortlistWidget'); ?>
    </div>
     
</div>
</div>
<div class="clearFix"></div>
</div>
<?php $this->load->view('common/footer');?>
<script>
	$j(document).ready(function(){
		setScrollbarForMsNotificationLayer();
		$j('.scrollbar1').tinyscrollbar({'wheelSpeed':250});
	});
</script>
