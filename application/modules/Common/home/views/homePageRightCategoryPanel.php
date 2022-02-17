<div class="Fnt16" style="padding:10px 0 5px 0;color:#0f0f0f"><b>Browse Institutes &amp; Courses</b></div>
<div>
	<div class="homeshik_MenuTab" style="width:100%">
		<div id="homePageTabs" style="width:100%">
			<a href="javascript:void(0);" class="homeShik_OpenTap" style="color:#000" onclick="return selectTab(this,'homeCategoryTabContent','homeShik_OpenTap','')" tab="homeRightPanelTabs">Career Options</a>
			<a href="javascript:void(0);" style="color:#000" onclick="return selectTab(this,'homeCountryTabContent','homeShik_OpenTap','')" tab="homeRightPanelTabs">Study Abroad</a>
		</div>
	</div>
	<div style="border:1px solid #d6dbde;background:#FFF">
		<div style="width:100%">
			<div style="width:100%;margin:2px 0 0 0;height:168px;overflow:hidden">
				<div id="homeCategoryTabContent" tabContent="categoryTab">
					<span class="homeBrowseMBA homeBrowseCommonCSS homeManagement">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					global $tabsContentByCategory;
					foreach($tabsContentByCategory[3]['subcats'] as $cat) {
						if(in_array($cat['id'],array(23,24,26,28))){
					?>
							&nbsp;&nbsp;<a href="<?=$cat['url']?>" 
onclick="trackEventByGA('<?php echo 'CategoryClick_'.$cat['name'];?>',this.innerHTML);"><?=$cat['name']?></a> &nbsp;|
						<?php }} ?>
					</span>
					<?php
						foreach($tabsContentByCategory as $category) {
							$class = '';
							if($category['id']==3){continue;}
							switch($category['id']){
								case 2:	 $class = 'homeScience';break;
								case 3:	 $class = 'homeManagement';break;
								case 4:	 $class = 'homeBanking';break;
								case 5:	 $class = 'homeMedicine';break;
								case 6:	 $class = 'homeHospitality';break;
								case 7:	 $class = 'homeMedia';break;
								case 9:	 $class = 'homeArt';break;
								case 10: $class = 'homeInformation';break;
								case 11: $class = 'homeRetail';break;
								case 12: $class = 'homeAnimation';break;
								case 13: $class = 'homeScience';break;
								case 14: $class = 'homeScience';break;
							}
					?>
					<span style="height:24px;overflow:hidden" class="homeBrowseCommonCSS <?=$class?>"><a href="<?=$category['url']?>" id="homeCategory<?=$category['id']?>" onmouseover="showSubcategories('homeCategory<?=$category['id']?>',<?=$category['id']?>);" onmouseout="hideSubcategoties(this);" url="animation"><?=$category['name']?></a></span>
					<?php }?>
				</div>
				<div id="homeCountryTabContent" style="display:none" tabContent="countryTab">
				<?php
				global $countriesForStudyAbroad;
				foreach($countriesForStudyAbroad as $countryId => $country) {
					if(strtoupper($countryId) == 'INDIA') continue;
					$countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
					$countryName = isset($country['name']) ? $country['name'] : '';
					$linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
				?>
					<span class="fontSize_12p shik_allFlagSpirit f<?php echo str_replace(' ','',$countryName);  ?>">
						<a href="<?php echo $linkUrl; ?>" class="" title="Education Information - <?php echo $countryName;?>" onclick="trackEventByGA('CountryClick',this.innerHTML);"><?php echo $countryName; ?></a>
					</span>
					<?php 
						}
					?>
				</div>
				<div class="clear_L">&nbsp;</div>
			</div>
		</div>
	</div>
</div>



<div id="subcategoryDiv" onmouseover="showSubcategories();" onmouseout="hideSubcategoties();" style="z-index: 500; position: absolute; top: 403px; left: 610px; overflow-y: auto; height: 325px;display:none">
	<div  class="inline-l shikSCDC" id="subcategoryDivContent" ></div>
	<div class="clear_L"></div>
</div>
