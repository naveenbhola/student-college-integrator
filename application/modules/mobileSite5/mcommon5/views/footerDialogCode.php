<?php
global $isHamburgerMenu;
//$footerHamburgerDivs = $this->config->item('footerHamburgerDivs');
if(!isset($this->config->config['footerHamburgerDivs'])){
	$this->load->config('mcommon5/mobi_config');
}
$footerHamburgerDivs = $this->config->item('footerHamburgerDivs');
$footerHamburgerDivsToBeLoaded = $footerHamburgerDivs[$pageType];
if(key_exists('subCategoryId', $footerHamburgerDivsToBeLoaded)){
	$footerHamburgerDivsToBeLoaded = $footerHamburgerDivsToBeLoaded['subCategoryId'][$subCategoryId];
} 

if(is_array($footerHamburgerDivsToBeLoaded) && $pageName !='home'){?>
	<?php if(in_array('mbaEntranceExamHamburgerDiv', $footerHamburgerDivsToBeLoaded)){?>
		<div data-role="page" id="mbaEntranceExamHamburgerDiv" data-enhance="false"><!-- dialog-->
	        <?php $this->load->view('marticle5/allMbaExamDiv');?>
		</div>
	<?php }
		if(in_array('engineeringExamHamburgerDiv', $footerHamburgerDivsToBeLoaded)){
	?>
		<div data-role="page" id="engineeringExamHamburgerDiv" data-enhance="false"><!-- dialog-->
	        <?php $this->load->view('marticle5/allExamDiv');?>
		</div>
	<?php }
		if(in_array('collegePredictorHamburgerDiv', $footerHamburgerDivsToBeLoaded)){
	?>
		<div data-role="page" id="collegePredictorHamburgerDiv" data-enhance="false"><!-- dialog-->
	        <?php $this->load->view('mcollegepredictor5/allExamsDiv');?>
		</div>
	<?php }
		if(in_array('rankPredictorHamburgerDiv', $footerHamburgerDivsToBeLoaded)){
	?>
		<div data-role="page" id="rankPredictorHamburgerDiv" data-enhance="false"><!-- dialog-->
	        <?php $this->load->view('mRankPredictor/allExamsDiv');?>
		</div>
	<?php }?>
<?php }?>
<?php if(MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){ ?>
	<div data-role="dialog" id="collegeSearchWidget" data-enhance="false"><!-- dialog--> </div>
	<div data-role="popup" id="shikshaSelectDialog"></div>
<?php } ?>
<div data-role="dialog" id = "shikshaModalLayer"     data-enhance="false"></div>
<div data-role="popup" id = "shikshaHelpTextLayer"></div>
<div data-role="page"   id = "AnANotifications"      data-enhance="false"></div>
<div data-role="dialog" id = "newHomepageToolLayer"  data-enhance="false"></div>

<div data-role="page" id="questionPostingLayerOneDiv" data-enhance="false"></div>
<div data-role="page" id="questionPostingLayerTwoTagDiv" data-enhance="false"></div>
<div data-role="page" id="addMoreTagsPostLayer" data-enhance="false"></div>
<div data-role="page" id="socialShare" data-enhance="false"></div>
<?php if($boomr_pageid == 'mobilesite_LDP') {?>
	<div id="galleryList" data-role="page" data-enhance="false"></div>
	<div id="galleryDetailList" class="galleryDetailList" data-role="page" data-enhance="false"></div>
	<div class="allContentLoader" id="loader-image" style="display: none">
	    <div class="loader-image">
	    	<img src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
	    </div>
	</div>
<?php } ?>

<?php if($boomr_pageid == 'mobilesite_AnA_homePage' || $boomr_pageid == 'mobilesite_AnA_QDP' || $boomr_pageid == 'mobilesite_TDP' || $boomr_pageid == 'listing_detail_course' || $boomr_pageid == 'MQuesSearchV3'){?>
	<div data-role="page" id="answerPostingLayerDiv" data-enhance="false">
        <?php $this->load->view('mAnA5/mobile/answerPostingLayer'); ?>
	</div>
<?php } ?>
<?php if($boomr_pageid == 'mobilesite_AnA_homePage' || $boomr_pageid == 'mobilesite_AnA_QDP' || $boomr_pageid == 'mobilesite_TDP' || $boomr_pageid == 'mobilesite_AnA_DDP'){?>
<div data-role="page" id="commentPostingLayerDiv" data-enhance="false">
	     <?php $this->load->view('mAnA5/mobile/commentPostingLayer'); ?>
</div>
<?php } ?>

<?php if($boomr_pageid == 'mobilesite_AllContent_Page'){?>
	<div data-role="page" id="allContentPageFilters" data-enhance="false">
	</div>
	<div class="allContentLoader" id="loader-image" style="display: none">
	    <div class="loader-image">
	    	<img src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
	    </div>
	</div>
<?php } ?>

<!--  Toast Msg on Web ANA-->
<div class="report-msg">
 <p class="toastMsg" id="toastMsg"></p>
</div>

<!--  Customize alert in Web AnA-->
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
    </div>