<div id="categorysearchoverlay">
<?php
	$parentCat = $personalizedArray['category']->getParentId();
?>
<script>
var studyabroad_navigation_lebel = "";
var reset_array = {0:'topstudyabroadUGcourses',1:'topstudyabroadPGcourses',2:'topstudyabroadPHDcourses'};
</script>


<div id="drpDown" onMouseOver="MM_personal_showHideLayers('drpDown','','show')" onMouseOut="MM_personal_showHideLayers('drpDown','','hide'); MM_personal_showHideLayers('countryOption','','hide');MM_personal_showHideLayers('personalizedCareerOption','','hide');" class="brd" style="display:none">
		<div style="line-height:8px">&nbsp;</div>
		<a href="javascript:void(0)" onMouseOver="openSubDiv(this, 'personalizedCareerOption');" id="careerMenus" style="width:120px"> &nbsp; &nbsp;Career Options</a>
</div>



<div id="SubCategoryLocations" onMouseOver="MM_personal_showHideLayers('SubCategoryLocations','','show');" onMouseOut="MM_personal_showHideLayers('SubCategoryLocations','','hide');"  style="padding:10px">
    <?php
    echo Modules::run('NavigationWidgets/locationLayerBySubcategory', $personalizedArray['categoryId']);
    ?>
</div>

<div id="AboutSubCategory" onMouseOver="MM_personal_showHideLayers('AboutSubCategory','','show');" onMouseOut="MM_personal_showHideLayers('AboutSubCategory','','hide');" style="padding:10px">
    <?php
    echo Modules::run('NavigationWidgets/aboutMbaWidgetBySubcategory',$parentCat, $personalizedArray['categoryId'],  $personalizedArray['countryId'],  $personalizedArray['regionId']);
    ?>
</div>

<div id="NewsWidget" onMouseOver="MM_personal_showHideLayers('NewsWidget','','show');" onMouseOut="MM_personal_showHideLayers('NewsWidget','','hide');" style="padding:10px">
    <?php
    echo Modules::run('NavigationWidgets/newsNUpdatesWidget',$parentCat, $personalizedArray['categoryId'],  $personalizedArray['countryId'],  $personalizedArray['regionId']);
    ?>
</div>

<div id="personalCareerOption" onMouseOver="MM_personal_showHideLayers('personalCareerOption','','show'); $('all_course').className='viewall-course-link-active';" onMouseOut="MM_personal_showHideLayers('personalCareerOption','','hide'); $('all_course').className='viewall-course-link';">	
<ul id="category_navigation">
<?php
			$this->load->library('category_list_client');
			global $categoryTree;
			$categoryTree = $this->category_list_client->getCategoryTree($appID,1);
			global $tabsContentByCategory;
			$tabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
			$i = -1;
			foreach($tabsContentByCategory as $category) {
				$i++;
?>	
<li alreadyclicked ='NO' onclick="makeItSticky(<?=$i?>,<?=$category['id']?>,this);" id="catagoryli_<?=$category['id']?>" <?php if($category['id'] == 11){ echo 'class="last"';}?>  >
		<span>
		<a onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" id="catagory_<?=$category['id']?>" onmouseover="showSubCatagories(<?=$i?>,<?=$category['id']?>,this)" href="javascript:void(0);" style="cursor:default"><?php echo str_replace(array('AVGC',')','(','Visual Effects'),array('','','','VFX'),$category['name']); ?>
		</a></span>
</li>
			<?php
		    }
?>
</ul>
<div style="display:none"></div>
		<div id="subCatagories">
		
            <div id="subCourse">d</div>
            <div id="subCat">r</div>
		</div>
</div>
<script type="text/javascript">
		var categoryTree = new Array();
		categoryList = categoryTree;
		tabsContentByCategory = <?=json_encode($tabsContentByCategory)?>;
</script>

<div id="personalCafeOption" onMouseOver="MM_personal_showHideLayers('personalCafeOption','','show');" onMouseOut="MM_personal_showHideLayers('personalCafeOption','','hide');">
	 
</div>

<div id="personalSubCatagories_country" onMouseOver="MM_personal_showHideLayers('personalSubCatagories_country','','show');" onMouseOut="MM_personal_showHideLayers('personalSubCatagories_country','','hide');"">

		<ul>
		<?php
			
			$urlRequest = new CategoryPageRequest;		
			$urlRequest->setData(array('categoryId'=>$parentCat,'subCategoryId'=>$personalizedArray['crossCat']->getId()));
			
			$locationBuilder = new LocationBuilder;
			$locationRepository = $locationBuilder->getLocationRepository();
			$regionsData=$locationRepository->getRegions();
		
			foreach($regionsData as $regionData){
				$urlRequest->setData(array('countryId'=>1,'regionId'=>$regionData->getId())); ?>
				<li onmouseover="this.className='hover'" onmouseout="this.className=''"><a href="<?=$urlRequest->getURL()?>"><?php echo $regionData->getName(); ?></a></li>
				<?php
		         }	
		?>
			
			
		<?php
			$urlRequest->setData(array('countryId'=>3,'regionId'=>0));
		?>
				<li onmouseover="this.className='hover'" onmouseout="this.className=''"><a href="<?=$urlRequest->getURL()?>">USA</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>5,'regionId'=>0));
		?>
				<li onmouseover="this.className='hover'" onmouseout="this.className=''"><a href="<?=$urlRequest->getURL()?>">Australia</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>8,'regionId'=>0));
		?>
				<li onmouseover="this.className='hover'" onmouseout="this.className=''"><a href="<?=$urlRequest->getURL()?>">Canada</a></li>
		<?php
			$urlRequest->setData(array('countryId'=>36,'regionId'=>0));
		?>
				<li onmouseover="this.className='hover last'" onmouseout="this.className='last' " class="last"><a href="<?=$urlRequest->getURL()?>">China</a></li>

		</ul>
</div>

<div id = "personalCoursesOption" onMouseOver="MM_personal_showHideLayers('personalCoursesOption','','show');" onMouseOut="MM_personal_showHideLayers('personalCoursesOption','','hide');">
		
		<ul>
		<?php
		
		$count=count($tabsContentByCategory[$parentCat]['subcats']);
		$i=0;
				foreach($tabsContentByCategory[$parentCat]['subcats'] as $category){
						$i++;
		?>
		        <li  <?php if($i!=$count){ ?> onmouseover="this.className='hover';" onmouseout="this.className='';"  <?php } ?><?php if($i==$count){ echo 'class="last"';}?> ><a onclick="" href="<?=$category['url']?>" title="<?=html_escape($category['name'])?>"><?=html_escape($category['name'])?></a></li>

		<?php
		} 
		?>
		</ul>
</div>
</div>
