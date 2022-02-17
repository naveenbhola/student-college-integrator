<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'mobilecss' => array('style','tag')
        );
$this->load->view('/mcommon5/header',$headerComponents);
global $isWebViewCall;
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<script type="text/javascript">
  var isHeaderSticky=1;
</script>

<div id="wrapper" data-role="page" <?php if(!$isWebViewCall){?> style="min-height: 413px;paddding-top:40px;" <?php }?> >	

   <?php if(!$isWebViewCall){ $this->load->view('anaHeader'); }?>

   <div data-role="content" data-enhance="false">
   	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
      <div class="tabs-container">
	
		<div class="tag-col">
			<div class="tag-box">
			   <h1 class="tag_title"><?php echo $data['tagName']; ?></h1>
			   <?php if($data['questionCount'] >= 3){?>
			   		<p class="tag_desc"><?php echo substr($tdpTopMsg, 0, 98); if(strlen($tdpTopMsg) >98){?><span id="tdpDescDots">...</span><span id="tdpRestDesc" style="display:none;"><?php echo substr($tdpTopMsg, 98)?></span><a id="tdpDescRm" onclick="showTDPFullDesc()">Read More</a><?php } ?></p>
			   <?php } ?>
			   <div class="cntr_btns">
			   	 <?php	if($data['isUserFollowing'] == 'true'){?>
		     			<div class="tag-btn"><a href="javascript:void(0)" callforaction="unfollow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_TAGDETAIL_WEBAnA','<?php echo addslashes($data['tagName']);?>');followEntity(this,<?php echo $data['tagId'];?>,'tag',false,'<?php echo $topFollowTrackingPageKeyId;?>')" class="tag-unflw-btn n_follow n_b" reverseclass="tag-flw-btn">Unfollow</a></div>
		     <?php	}else{?>
		     			<div class="tag-btn"><a href="javascript:void(0)" callforaction="follow" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','FOLLOW_TAGDETAIL_WEBAnA','<?php echo addslashes($data['tagName']);?>');followEntity(this,<?php echo $data['tagId'];?>,'tag',false,'<?php echo $topFollowTrackingPageKeyId;?>')" class="tag-flw-btn n_follow n_b" reverseclass="tag-unflw-btn">Follow</a></div>
		     <?php	}?>
			      <a class="n_ask n_b" href="#questionPostingLayerOneDiv" onclick="gaTrackingForAna(this,'TAG DETAIL PAGE','ASK_QUESTION_WEBAnA','<?php echo addslashes($data['tagName']);?>');populateQuesDiscLayer('question','<?=$topAskQuesTrackingKeyId;?>');"  data-inline="true" data-rel="dialog" data-transition="fade">ASK QUESTION</a> 
			   </div>
			</div>
		   <div class="tag-count">
		      <div class="tag-qstn">
			<h2 class="tag-h2">Questions</h2>
			<span class="tag-num"><?php echo $data['questionCount'] ? formatNumber($data['questionCount']) : 0;?></span>
		      </div>
		      <div class="tag-qstn">
			<h2 class="tag-h2">DISCUSSIONS</h2>
			<span class="tag-num"><?php echo $data['discussionCount'] ? formatNumber($data['discussionCount']) : 0;?></span>
		      </div>
		      <div class="tag-qstn">
			<h2 class="tag-h2">ACTIVE USERS</h2>
			<span class="tag-num"><?php echo $data['expertCount'] ? formatNumber($data['expertCount']) : 0;?></span>
		      </div>
		      <div class="tag-qstn">
			<h2 class="tag-h2">FOLLOWERS</h2>
			<span class="tag-num"><?php echo $data['followerCount'] ? formatNumber($data['followerCount']) : 0;?></span>
				<input type="hidden" id="breadFcount" value="<?=$data['followerCount'];?>"/>
		      </div>
		   </div>
		</div>

		<?php $this->load->view('anaTabs');?>
		
		<!-- 
		Moved to footerDialogCode.php
		
		<div class="comment-layer" style="display:none;">
                <div class="cmnts-layer">
                  <a href="javascript:void(0);" class="layer-cls" onclick="hideAnswerResponseMessage()">&times</a>
                      <div class="cmnts-msg">
                            <p id="answerMessageLayer"></p>
                      </div>
                      <div class="usr-choice">
                        <a href="javascript:void(0);" onclick="hideAnswerResponseMessage()" >OK</a>
                      </div>
              </div>
        </div> -->
		
		<?php $this->load->view('homepageContent');?>

		<?php if(strlen($paginationHTMLForGoogle) >= 35){ ?>
		<div class="pagnation-col">
                      <?php echo $paginationHTMLForGoogle;?>
                </div>               
		<?php } ?>
		
      </div>

      <?php $this->load->view('/mcommon5/footerLinks'); ?>
      <?php $this->load->view('/mAnA5/mobile/shareLayer'); ?>
   </div>
   
</div>


<?php $this->load->view('/mcommon5/footer', array("jsMobileFooter" => array('ana')));?>
<?php echo includeJSFiles('shikshaMobileWebsiteTour'); ob_end_flush(); ?>

	<input type="hidden" id="tag_detail_ask_id" name="tag_detail_ask_id" value="">

<script>
var globalTagId = '<?=$data['tagId'];?>';
  <?php if(!empty($viewTrackParams)){ ?>
  window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
  window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
  window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
  window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
  <?php } ?>
  $(document).ready(function(){
      initializeTagPage();
      window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
      initializeWebsiteTour('cta', 'mobileQuestion', contentMapping);
  });
</script>
