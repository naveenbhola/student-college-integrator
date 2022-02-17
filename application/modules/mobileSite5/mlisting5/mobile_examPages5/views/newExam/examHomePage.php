<?php $this->load->view('/mcommon5/headerV2');
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_EXAM_PAGES',1);?>
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<style type="text/css">
	<?php $this->load->view('mobile_examPages5/newExam/css/examPageCSS'); ?>
</style>
<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide ui-page-theme-a ui-page-active">
<?php $this->load->view('mobile_examPages5/newExam/examPageHeader'); ?>
<div data-role="content" id="pageMainContainerId" style="padding-top:40px;">
	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
	<div data-enhance="false" >
	<!-- conten section -->
	<?php $this->load->view('mobile_examPages5/newExam/sectionPages'); ?>
	<!-- content end -->
	<?php $this->load->view('/mcommon5/footerLinksV2',array(
														  'jsFooter'=>array('mExamPage','websiteTour','bootstrap-tour-standalone.min'),
														  'cssFooter'=>array('websiteTour')
													)
	); ?>
	</div>
</div>
	<input type="hidden" id="exam_ask_id" name="exam_ask_id" value="">
	<input type="hidden" id="exam_group_ask_id" name="exam_group_ask_id" value="<?=$groupId;?>">
	<input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="exam_ask_question">
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php 
	if($isHomePage)
	{
		$GA_currentPage  = 'EXAM PAGE';	
		$GA_commonCTA_Name = '_EXAM_PAGE_MOB';	
	}
	else
	{
		$GA_currentPage  = 'EXAM '.strtoupper($sectionNameMapping[$activeSectionName]).' PAGE';	
		$GA_commonCTA_Name = '_EXAM_'.str_replace(' ', '_', strtoupper($sectionNameMapping[$activeSectionName])).'_PAGE_MOB';
	}

	$httpQueryParams = '';
	if(array_key_exists('course', $_GET))
	{
		$httpQueryParams = 'course='.$_GET['course'];
	}
?>
<script type="text/javascript">
	var examId           = '<?php echo $examId ?>';
	var groupId          = '<?php echo $groupId ?>';
	var currentPageUrl   = '<?=$examBasicObj->getUrl();?>';
	var httpQueryParams  = '<?php echo $httpQueryParams;?>';
	var currentSectionId = '<?=$activeSectionName;?>';
	var showAllUpdate    = '<?=$showAllUpdate?>';
	var streamCheck      = '<?php echo $streamCheck ?>';

	//amp variables
	<?php if(!empty($actionType)){ ?>
		var guideKeyIdAmp           = '<?=$ampKeys['download_guide']?>';
	    var samplePaperKeyIdAmp     = '<?=$ampKeys['download_sample_paper']?>';
	    var samplePaperPageKeyIdAmp     = '<?=$ampKeys['download_sample_paper_page']?>';
	    var prepGuideKeyIdAmp     = '<?=$ampKeys['download_prep_guide']?>';
	    var prepTipKeyIdAmp     = '<?=$ampKeys['download_prep_tips_page']?>';
	    var applyOnlineKeyIdAmp   = '<?=$ampKeys['apply_online']?>';
	    var askKeyIdAmp      = '<?=$ampKeys['ask']?>';
	    var subscribeLatestUpdateKeyIdAmp     = '<?=$ampKeys['subscribe_to_latest_updates']?>';
	    var applicationFormKeyIdAmp     = '<?=$ampKeys['download_application_form']?>';
		var pos             = '<?=$pos;?>'; 
		var fileNo             = '<?=$fileNo;?>';	
		var instituteName             = '<?=$instituteName;?>';	
		var courseId             = '<?=$courseId;?>';	
		var isInternal             = '<?=$isInternal;?>';	
	 	var uriFromWhere    = '<?=$fromwhere;?>';
	  	var replaceStateUrl = '<?=$replaceStateUrl;?>';
	  	var uriActionType   = '<?=$actionType;?>';
  	<?php } ?>
  	 <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
	      var samplePaperExist = true;
	 <?php } ?>

	<?php if($validResponseUser){?>
	    var validResponseUser = <?php echo $validResponseUser;?>;
	    var viewedResponseKey = <?php echo $trackingKeys['viewed_response'];?>;
	    var viewedResponseAction = '<?php echo $viewedResponseAction;?>';
  	<?php }?>
	if(streamCheck == 'beBtech'){
	var CalCatName      = 'Engineering';
	var CalCourseId     = '<?php echo ENGINEERING_COURSE ; ?>';
	var educationTypeId = '<?php echo EDUCATION_TYPE; ?>';
  	}else if(streamCheck == 'fullTimeMba'){
	var CalCatName      = 'MBA';
	var CalCourseId     = '<?php echo MANAGEMENT_COURSE ; ?>';
	var educationTypeId = '<?php echo EDUCATION_TYPE; ?>';
  	}

  	var GA_currentPage = "<?php echo $GA_currentPage;?>";
  	var ga_user_level = "<?php echo $GA_userLevel;?>";
  	var ga_commonCTA_name = "<?php echo $GA_commonCTA_Name;?>";

  	var examAskKeyId = <?=$trackingKeys['ask'];?>;
  	
  	/*var img = document.getElementById('beacon_img');
    var randNum = Math.floor(Math.random()*Math.pow(10,16));
    img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010005/<?=$groupId?>+exam_group';*/
	$(window).on('load', function() {
	    	window.contentMapping = <?php echo json_encode($websiteTourContentMapping);?>;
	})
</script>
<script type="text/javascript">
  // This is a functions that scrolls to #{blah}link
function goToByScroll(id) {
    // Remove "link" from the ID
   // id = id.replace("link", "");
    // Scroll
    $('html,body').animate({
        scrollTop: $("#" + id).offset().top-$('#tab-section').height() - 
$('#page-header').height() - 15
    }, 'slow');
}

$("#discusn").click(function(e) {
    // Prevent a page reload when a link is pressed
    e.preventDefault();
    // Call the scroll function
    goToByScroll('qna');
});
</script>
<?php $this->load->view('/mcommon5/footerV2');?>

