<div id="SARightCol">		
                    <?php if($categoryPage->getBanner() && $categoryPage->getBanner()->getURL()){
                ?>
						<div class="ad_displayBox" id="ad-displayBox"></div>
						<script>
							shoshkeleUrl = '<?=$categoryPage->getBanner()->getURL()?>';
							shoshkeleType = 'Abroad';
						</script>  
                        <div class="spacer20 clearFix"></div>
                        <div class="spacer10 clearFix"></div>
                <?php
                   }
                ?>
                
                <?php $nomore_content = Modules::run('articleWidgets/articleWidgets/index', 1, $request->getCategoryId(), $request->getSubCategoryId(), $request->getCountryId(), $request->getRegionId()); 
				if(!empty($nomore_content)):
					echo $nomore_content;     
				?>
                <div class="spacer20 clearFix"></div>
                <div class="spacer10 clearFix"></div>
                <?php endif;?>
	<?php               
		global $criteriaArray;
		$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'FOREIGN_PAGE_RIGHT1','shikshaCriteria' => $criteriaArray);
		$this->load->view('common/banner',$bannerProperties);		
?>              
				<?php 
		echo Modules::run('articleWidgets/articleWidgets/index', 2, $request->getCategoryId(), $request->getSubCategoryId(), $request->getCountryId(), $request->getRegionId());

		$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'FOREIGN_PAGE_RIGHT2','shikshaCriteria' => $criteriaArray);
		
		
		?>
		<div class="ad-box">
        	<?php $this->load->view('common/banner',$bannerProperties);?>
		</div>
        
        <div class="spacer20 clearFix"></div>
        <div class="spacer10 clearFix"></div>
		<div id="askCafeWidget"  uniqueattr="CategoryPage/cafeWidget">
			<script>
				var jsForWidget = new Array();
				jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_common"); ?>');
				jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("homePage"); ?>');
				addWidgetToAjaxList('/CafeBuzz/CafeBuzz/index/<?php echo $categoryPage->getCategory()->getId(); ?>','askCafeWidget',jsForWidget);
			</script>
		</div> 
		<div id="AskAQuestionWidget" uniqueattr="CategoryPage/askAQues">
			<script>
				addWidgetToAjaxList('/AskAQuestion/AskAQuestion/index/categoryPageRight/','AskAQuestionWidget',Array());
			</script>
		</div>
		
	<?php
		$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'FOREIGN_PAGE_SKYSCRAPPER','shikshaCriteria' => $criteriaArray);
			

	?>
    	<div class="ad-box">
        	<?php $this->load->view('common/banner',$bannerProperties); ?>
        </div>
	
</div>
