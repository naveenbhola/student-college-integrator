<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'mobilecss' => array('style')
        );
$this->load->view('/mcommon5/header',$headerComponents);
global $isWebViewCall;
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<script type="text/javascript">
  var isHeaderSticky=1;
</script>
<div id="wrapper" data-role="page" <?php if(!$isWebViewCall){?> style="min-height: 413px;paddding-top:40px;" <?php }?>>	

   <?php $this->load->view('mobile/anaHeader');?>

   <div data-role="content" data-enhance="false">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
      ?>

         <?php if($displayTopCard){
                $this->load->view('mobile/profileCard');
          } ?>

      <div class="tabs-container">
      <div class="n-title"><p>Ask & Answer</p></div>

   	 <?php $this->load->view('mobile/homepageContent');?>
   	   <div id="homePageAjaxData">

   </div>
          <div data-enhance="false">
            <input type="hidden" name="nextPaginationIndex" id="nextPaginationIndex" value="<?php echo $nextPaginationIndex;?>" />
            <input type="hidden" name="nextPageNo" id="nextPageNo" value="<?php echo $nextPageNo;?>" />
        </div>
		 <div data-enhance="false" id="pagination_end_msg"> 
            <?php echo $pagination_end_msg; ?>
        </div>

        <div style="text-align: center; margin-top: 7px; margin-bottom: 10px; display: none;" id="loader-id">
          <img border="0" alt="" id="loadingImage1" class="small-loader" style="border-radius:50%" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
        </div>
      </div>

      <?php $this->load->view('mobile/cards/newQuestionDiscussionCTA'); ?>
      <?php $this->load->view('/mcommon5/footerLinks'); ?>
      <?php $this->load->view('mobile/shareLayer'); ?>
   </div>
   
</div>


<?php $this->load->view('/mcommon5/footer', array("jsMobileFooter" => array('ana')));?>
<?php echo includeJSFiles('shikshaMobileWebsiteTour'); ob_end_flush(); ?>


<script>
  <?php if(!empty($viewTrackParams)){ ?>
  window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
  window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
  window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
  window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
  <?php } ?>
  $(document).ready(function(){
      handleToastMsg();
      initializeAnAPage();
      window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
      initializeWebsiteTour('cta', 'mobileQuestion', contentMapping);
  });
</script>
