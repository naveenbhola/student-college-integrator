<div id="cateRightCol">
        <div style="padding-right:10px; position:relative">

			<?php
				if(!empty($filteredBanner) && $filteredBanner->getURL()){
						$displayBanner = $filteredBanner;
				} else if($categoryPage->getBanner() && $categoryPage->getBanner()->getURL()) {
						$displayBanner = $categoryPage->getBanner();
				}
				
				if(!empty($displayBanner) && !preg_match('/(\.swf)/',$displayBanner->getURL()) && $displayBanner->getURL() != '') { ?>
					<div style="height:155px;position:relative;right:0px;">
					<iframe id="categoryPagePushDownBannerFrame" width="879" scrolling="no" height="160" frameborder="0" src="about:blank" id="TOP" bordercolor="#000000" vspace="0" hspace="0" marginheight="0" marginwidth="0" style="position:absolute; right:0;z-index:1"></iframe>
					</div>
					<script>
						pushBannerToPool('categoryPagePushDownBannerFrame', '<?=$displayBanner->getURL()?>');
					</script>
					<?php
				}
				else if(!empty($displayBanner) && $displayBanner->getURL()) {
				?>
						<style>
							.shikFL object{ position:absolute; right:0; }
						</style>
						<div class="ad_displayBox" id="ad-displayBox" style="height:170px;display:block;"></div>
						<script>
							shoshkeleUrl = '<?=$displayBanner->getURL()?>';
							shoshkeleType = 'India';
						</script>
				<?php
				} else {
					//leave empty div with no styling
					
					?>
					<div class="ad_displayBox" id="ad-displayBox" style="display:none;"></div>
					<?php
				}
				?>
         
        <div class="spacer20 clearFix"></div>
         <!--       QUICK LINKS WIDGET      -->
        <?php         
        echo Modules::run('articleWidgets/articleWidgets/index', 1, $request->getCategoryId(), $request->getSubCategoryId(), $request->getCountryId(), $request->getRegionId()); ?>
                         <div class="ad_displayBox">
        	<div class="spacer20 clearFix"></div>
        		<?php
				global $criteriaArray;
				$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHT2','shikshaCriteria' => $criteriaArray);
				$this->load->view('common/banner',$bannerProperties);
				?>	
            <div class="spacer20 clearFix"></div>
        </div>

	<!--
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
				addWidgetToAjaxList('/AskAQuestion/AskAQuestion/index/categoryPageRight/0/<?php echo $request->getCategoryId(); ?>/<?php echo $request->getSubCategoryId(); ?>/<?php echo $questionTrackingPageKeyId;?>','AskAQuestionWidget',Array());
			</script>
		</div>
        
		<div class="spacer20 clearFix"></div>	
	-->
		<div style="margin-top:20px;">
				<?php
				if($request->isSubcategoryPage()){
						echo Modules::run('ranking/RankingMain/getRankingPageWidgetHTML', array($request->getSubCategoryId()), array(), true, 'categorypage');
				} else if($request->isLDBCoursePage()){
						echo Modules::run('ranking/RankingMain/getRankingPageWidgetHTML', array(), array($request->getLDBCourseId()) , true, 'categorypage');
				}
				?>
		</div>
				
        <!--div class="fbActivity" id="fbActivity" uniqueattr="CategoryPage/FBWidget">
			<script>
				addWidgetToAjaxList('/RecentActivities/RecentActivities/index/','fbActivity',Array());
			</script>
        </div-->

        <div class="spacer20 clearFix" ></div>

        <div class="latestNewsBlock">
          <strong>Like us on Facebook</strong>
          <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="300" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
         
        </div>

         <div class="clearFix" id="widgetsToScrollTop"></div>

       <div id="widgetsToScroll" style="width:300px;">
             <!--       LATEST NEWS WIDGET      -->
            <?php echo Modules::run('articleWidgets/articleWidgets/index', 2, $request->getCategoryId(), $request->getSubCategoryId(), $request->getCountryId(), $request->getRegionId()); ?>

            <div class="featuredInstBlock" id="catPageRightBottomBannerDiv">
                    <?php
                            global $criteriaArray;
                            $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHTBOTTOM','shikshaCriteria' => $criteriaArray);
                            $this->load->view('common/banner',$bannerProperties);
                     ?>
            </div>
        </div>
         <div id="spacerDivForAdsenseBanner" style="width:300px;height:450px;" class="clearFix">&nbsp;</div>
    </div>
</div>
