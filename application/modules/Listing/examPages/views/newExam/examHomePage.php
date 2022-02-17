<?php $this->load->view('examPages/newExam/examPageHeader'); ?>
<!-- conten section -->
    <?php $this->load->view('examPages/newExam/sectionPages');?>
<!-- content end -->
<!-- Ask now layer -->
<div id="tags-layer" class="tags-layer"></div>
<div class="an-layer an-layer-inner" id="an-layer" style="display:none;">
    <?php $this->load->view('messageBoard/desktopNew/quesDiscPosting',$displayData);?>
</div>

<div class="main__layer" id="exmPopUpLayer"></div>

<?php $this->load->view('examPages/newExam/examPageFooter');
      echo includeJSFiles('shikshaDesktopWebsiteTour');
?>
<?php 
  if($isHomePage)
  {
    $GA_currentPage  = 'EXAM PAGE'; 
    $GA_commonCTA_Name = '_EXAM_PAGE_DESK'; 
  }
  else
  {
    $GA_currentPage  = 'EXAM '.strtoupper($sectionNameMapping[$activeSectionName]).' PAGE'; 
    $GA_commonCTA_Name = '_EXAM_'.str_replace(' ', '_', strtoupper($sectionNameMapping[$activeSectionName])).'_PAGE_DESK';
  }
  $httpQueryParams = '';
  if(array_key_exists('course', $_GET))
  {
    $httpQueryParams = 'course='.$_GET['course'];
  }
?>
<script type="text/javascript">
  var examId = '<?=$examId;?>';
  var groupId = '<?=$groupId;?>';
  var currentPageUrl   = '<?=$examBasicObj->getUrl();?>';
  var httpQueryParams  = '<?php echo $httpQueryParams;?>';
  var isGuide = '<?php echo $guideDownloaded;?>';
  var isSubscribe = '<?php echo $isSubscribe;?>';
  var applyOnlineKey = <?php echo $trackingKeyList['apply_online'];?>; 
  
  <?php if(!empty($actionType)){ ?>
    var uriActionType   = '<?=$actionType;?>';
    var uriFromWhere    = '<?=$fromwhere;?>';
    var fileNo             = '<?=$fileNo;?>';
  <?php }?>
  <?php if($validResponseUser){?>
    var validResponseUser = <?php echo $validResponseUser;?>;
    var viewedResponseKey = <?php echo $trackingKeyList['viewed_response'];?>;
    var viewedResponseAction = '<?php echo $viewedResponseAction;?>';
  <?php }?>
  <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
      var samplePaperExist = true;
  <?php } ?>
  var currentSectionId = '<?=$activeSectionName;?>';
  var showAllUpdate = '<?=$showAllUpdate?>'; 
  var streamCheck = '<?php echo $streamCheck ?>';
  if(streamCheck == 'beBtech'){
	var CalCatName = 'Engineering';
	var CalCourseId = '<?php echo ENGINEERING_COURSE ; ?>';
	var educationTypeId = '<?php echo EDUCATION_TYPE; ?>';
  }else if(streamCheck == 'fullTimeMba'){
	var CalCatName = 'MBA';
	var CalCourseId = '<?php echo MANAGEMENT_COURSE ; ?>';
	var educationTypeId = '<?php echo EDUCATION_TYPE; ?>';
  }
  var GA_currentPage = "<?php echo $GA_currentPage;?>";
  var ga_user_level = "<?php echo $GA_userLevel;?>";
  var ga_commonCTA_name = "<?php echo $GA_commonCTA_Name;?>";
	var isExamHomePage = "<?php echo $isHomePage; ?>";
  $j(document).ready(function(){
      window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
      initializeWebsiteTour('cta',window.shikshaProduct,contentMapping);

      //DFP banner sticky
      var bntop = $j('#rightPanelDFPBanner').offset().top + 535;
      $j(window).scroll(function(){
          var ftrh = ($j('#footer').length>0) ? $j('#footer').offset().top : 0; 
          var maxh = $j(this).scrollTop() + $j(this).height();
          if(maxh >= ftrh) {
            $j('#rightPanelDFPBanner').css({'position':'','width':'380px','top':'100px'}); 
          }else if(maxh >= bntop) {
            $j('#rightPanelDFPBanner').css({'position':'fixed','width':'380px','top':'100px'});
          }else {
            $j('#rightPanelDFPBanner').css({'position':'','width':'380px','top':$j('#rightPanelDFPBanner').offset().top}); 
          }
      });
  });
function goToByScroll(id) {
  $j('html,body').animate({
        scrollTop: $j("#" + id).offset().top-$j('#fixed-card').height() - 30
    }, 'slow');
}

$j("#discusn").click(function(e) {
    e.preventDefault();
    goToByScroll('ana');
});
</script>
</script>
