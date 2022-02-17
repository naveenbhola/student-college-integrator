<div itemscope itemtype="http://schema.org/Article">
<?php
if(strlen($content['data']['strip_title']) <= 80){
	$contentTitle = $content['data']['strip_title'];
}
else{
	$contentTitle =  (preg_replace('/\s+?(\S+)?$/', '', substr($content['data']['strip_title'], 0, 80))."...") ;
}

?>
<div id="breadcrumb">
  <span>
    <a href="<?php echo SHIKSHA_STUDYABROAD_HOME;?>">
      <span>Home</span>
    </a>
    <span class="breadcrumb-arr">›</span>
  </span>
  <span>
    <span><?php echo $contentTitle;?></span>
  </span>
</div>
    <!-- Article Guide Starts Here -->
<div class="content-wrap clearfix">
    <?php $this->load->view('saContent/articleTopNavigation'); ?>
	<div class="articleGuide-content clearfix">
    	<div id="left-col">
				<div class="artGuide-cont clearfix">
					<div id="article-header">
		   	    <?php $this->load->view('saContent/SAContentHeader'); ?>
		      </div>
    	   <?php $this->load->view('saContent/SAContentDesc'); ?>
    	   <?php //echo modules::run('abroadContentOrg/AbroadContentOrgPages/getContentOrgWidget'); ?>
	   <?php $this->load->view('saContent/authorWidget');?>
	 			</div>
					<?php $this->load->view('saContent/commentSection',array('content_id' => $content['data']['content_id']));?>
					</div>

    	<div id="right-col">
            <?php //$this->load->view('saContent/topRankedInstitutes');?>

            <?php $this->load->view('saContent/popularArticles');?>

	    <?php $this->load->view('saContent/guideRightWidget');?>
        <?php $this->load->view('listing/abroad/widget/facebookWidget');?>
	    <?php $totalResultCount = count($relatedGuideArticles);?>
        <div  id="rightStickyPanel" class="clearwidth"  style="margin-top: 15px; width: 380px;">
	    <?php if($is_downloadable!= 'no' || $totalResultCount >0){ ?>
	    <?php $this->load->view('saContent/downloadGuideRightWidget');?>
		<?php } ?>
	    <?php echo Modules::run('applyContent/applyContent/loadFindCollegesWidgetOnContentPage', array('contentType'=>$content['data']['type'])); ?>
	     	<!-- Related Guide Article Widget : STARTS -->
		<?php
			if($totalResultCount >0){
				$this->load->view('saContent/relatedGuideArticleRightWidget');
			}
		?>
        	<!-- Related Guide Article Widget : ENDS -->
		</div>
        </div>
          <?php if($content['data']['type'] == 'guide' && count($content['data']['sections'])>=1){ ?>
		<div class="guide-menu" id="jump" style="position:absolute;" onmouseover='showJumpSection();' onmouseout='hideJumpSection();'>
			<div class="positionRel">
		<a id = "span1"   style="display: none;" href="javascript:void(0);" class="guide-btn"><i class="article-sprite guide-menu-icon"></i>
			<span class="guide-text">Guide Menu</span>
		</a>

		<a href="javascript:void(0);" id = "span2" style="display: none;" class="guide-btn"><i class="article-sprite guide-menu-icon"></i>
			<i class="article-sprite guide-arrow"></i>
		</a>
		<!-- <p class="guide-info" id="guideInfo" style="display: none;">Quickly access the <br/> guide topics</p> -->
			<?php $this->load->view('saContent/jumpSection');?>
		</div>
	</div>
	<?php }?>
        <!-- Article Guide Ends Here -->
	</div>
	</div>
</div>
