<div class="content-wrap clearfix tabloidBlock" id=" tabloidBlock<?=$content['data']['content_id']?>">
    <!-- Article Guide Starts Here -->
	<div id="article-header">
	  <?php $this->load->view('saContent/tabloid/tabloidHeader'); ?>
	</div>
	
    <div id="left-col" class="leftCol">
    	<?php $this->load->view('saContent/tabloid/tabloidSAContentDesc'); ?>
    	<?php //echo modules::run('abroadContentOrg/AbroadContentOrgPages/getContentOrgWidget'); ?>
		<?php $this->load->view('saContent/tabloid/tabloidCommentSection',array('content_id' => $content['data']['content_id']));?>
    </div>
    <div id="right-col" class="rightCol">
		<?php $this->load->view('saContent/tabloid/tabloidAuthorWidget');?>
        <?php// $this->load->view('saContent/topRankedInstitutes');?>
		<?php //$this->load->view('saContent/popularArticles');?>
	    <?php $this->load->view('saContent/guideRightWidget');?>
        <?php $this->load->view('listing/abroad/widget/facebookWidget');?>
	    <?php $totalResultCount = count($relatedGuideArticles);?>
	    <?php if($is_downloadable!= 'no' || $totalResultCount >0){ ?>
        <div class="clearwidth"  style="margin-top: 15px; width: 308px;"> 
			<?php $this->load->view('saContent/downloadGuideRightWidget');?>
			<?php echo Modules::run('applyContent/applyContent/loadFindCollegesWidgetOnContentPage', array('contentType'=>$content['data']['type']));?>
			<!-- Related Guide Article Widget : STARTS -->
			<?php
				if($totalResultCount >0){
					$this->load->view('saContent/relatedGuideArticleRightWidget');
				}
			?>
			<!-- Related Guide Article Widget : ENDS -->
		</div>
	    <?php } ?>
    </div>
    <!-- Article Guide Ends Here -->
  <?php echo Modules::run('studyAbroadArticleWidget/articleAbroadWidgets/getListingWidgetsOnArticles', $content['data']['content_id'], true);?>   	  
</div>
