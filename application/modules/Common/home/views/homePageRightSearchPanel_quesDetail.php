<div style="width:610px">
  <div>										
	  <form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" >
		  <input type="hidden" name="keyword" id="keyword" autocomplete="off" value="<?php echo htmlspecialchars($keyword); ?>"/>
		  <input type="hidden" name="location" id="location" autocomplete="off" value="<?php echo htmlspecialchars($location); ?>"/>
		  <input type="hidden" name="searchType" id="searchType" autocomplete="off" value="<?php echo htmlspecialchars($searchType); ?>"/>
		  <input type="hidden" name="cat_id" id="cat_id" autocomplete="off" value="<?php echo htmlspecialchars($catID);?>"/>
		  <input name="countOffsetSearch" id="countOffsetSearch" autocomplete="off" value="<?php echo htmlspecialchars($countOffsetSearch); ?>"  type="hidden" />
		  <input name="startOffSetSearch" id="startOffSetSearch" autocomplete="off" value="<?php echo htmlspecialchars($startOffSetSearch); ?>" type="hidden" />
		  <input name="subLocation" id="subLocation" autocomplete="off" value="<?php echo htmlspecialchars($subLocation); ?>" type="hidden" />
		  <input name="cityId" id="cityId" autocomplete="off" value="<?php echo htmlspecialchars($cityId); ?>" type="hidden" />
		  <input name="cType" id="cType" autocomplete="off" value="<?php echo htmlspecialchars($cType); ?>" type="hidden" />
		  <input name="courseLevel" id="courseLevel" autocomplete="off" value="<?php echo htmlspecialchars($courseLevel); ?>" type="hidden" />
		  <input name="subType" id="subType" autocomplete="off" value="<?php echo htmlspecialchars($subType); ?>" type="hidden"/>
		  <input name="showCluster" id="showCluster" autocomplete="off" value="<?php echo (isset($showCluster) || empty($showCluster)) ? '-1' : htmlspecialchars($showCluster); ?>" type="hidden" />
		  <input name="channelId" id="channelId" autocomplete="off" value="<?php echo $channelId; ?>" type="hidden"/>
	  </form>
	  <form method="get" onsubmit="checkTextElementOnTransition(document.getElementById('tempkeyword'),'focus');validateSearch(1,0,1);return false;">

		  <div id="tempKeywordHolder">
			  <input type="text" style="width:500px;padding:3px;margin-right:10px;color:#ada6ad" class="float_L shikshaSearchTextBox" value="Search for questions & answers you are looking for!" default="Search for questions & answers you are looking for!" id="tempkeyword"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"/>
		  </div>
		  <input type="submit" value="" class="spirit_middle shik_searchBtn" onclick="trackEventByGA('SearchClick','SEARCH_BTN');"/>

		  <input type="hidden" value="Ask & Answer" id="tempSearchType" />
		  <div class="float_L" style="width:29%;display:none;" id="tempLocationHolder">
		  <div class="brd1" style="width:100%;display:none;">
			<div class="homeShik_textBoxBorder">
			  <input type="text" style="color:#AdA6Ad" class="homeShik_searchtextBox" value="Enter Location" default="Enter Location" id="templocation"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"/>
			</div>
		  </div>
	  </form>
	  <div class="clear_L">&nbsp;</div>
	</div>
</div>										
<?php   
   $productSelect = isset($productSelect)?$productSelect:'course';
   $showProduct = isset($showProduct)?$showProduct:true;
?>    
