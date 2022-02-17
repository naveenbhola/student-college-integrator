<div id="cateTitleBlock">
	<h1 style="color:black;">
		<?php
			if($request->isMainCategoryPage()){
				$change = "Career Option"; 
			} elseif($request->isSubcategoryPage()){
				$change = "Course";
			} elseif($request->isLDBCoursePage()){
				$change = "Course"; 
			}
			
			$cityId = $request->getCityID();
			$stateId = $request->getStateID();
			if($cityId == 1 and $stateId == 1 and (!($isSourceRegistration and $subcat_id_course_page == 23))) {
				echo "<span style='color:black;' id='pageTitleCount'>".$categoryPage->getTotalNumberOfInstitutes()."</span> ".($categoryPage->getSubCategory()->getName()=='All'?$categoryPage->getCategory()->getName():$categoryPage->getSubCategory()->getName()).(($categoryPage->getTotalNumberOfInstitutes() == 1)?" Institute":" Institutes")." Found";
			}
			else{
				echo $pageTitleForFilters;
			}
		?>
		<span>
		<?php
			global $locationname;
			$localityName = $request->getLocalityName();
			$cityName = $request->getCityName();
			$stateName = $request->getStateName();
			$countryName = $request->getCountryName();
			$locationName1 = "";
			$locationName2 = "";
			
			if($localityName)
			{
			    $locationName1 = $localityName.", ";
			}
			$locationName1 = ucfirst($locationName1);
			if($cityId > 0)
			{
				$locationName2 = $cityName;
			    if($cityId == 1 && $stateId > 0)
			    {
					$locationName2 = $stateName;
					if($stateId == 1 && $countryName)
					{
						$locationName2 = $countryName;
					}
			    }
			    $locationName2 = ucfirst($locationName2);
				$locationname = $locationName1.$locationName2;
			}
			else {
				$locationname = "";
			}
			
			if($isSourceRegistration && $subcat_id_course_page == 23) {
				$locationname = '';
			}
			
			if(!empty($locationname) and !($cityId == 1 and $stateId == 1)) {
				echo "in " . $locationname;
			}
		?>
		</span>
	</h1>
	<div style="position:relative;z-index:1200;">
	<div class="changeLocation" id="changeLocationdiv">
		<strong>
			[&nbsp;</strong><a href="#"  id="changeCategorylink" onclick="openCategoryLayer();return false;" onmouseout = "dissolveOverlayHackForIE();$('overlayCategoryHolder').style.display='none'">Change <?=$change?> <span class="orangeColor">&#9660;</span></a><strong>&nbsp;]</strong>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<strong>[&nbsp;</strong><a href="#" id="changeLocationlink" onclick="openLocationLayer();return false;">Change Location <span class="orangeColor">&#9660;</span></a><strong>&nbsp;]</strong>
	</div>

	</div>
	
	
	<?php
	$headerText = $categoryPage->getHeaderText();
	if($headerText && strlen($headerText)>0){
	?>
	<p id="partialHeaderText">
		<?=html_escape(substr($headerText,0,200))?>
		<?php if(strlen($headerText)>201){ ?>
		... <a href="#" onclick="$('partialHeaderText').style.display= 'none'; $('fullHeaderText').style.display= ''; return false;">Read more</a>
		<?php } ?>
	</p>

	<p id="fullHeaderText" style="display:none">
		<?=html_escape($headerText)?>
	</p>
	<?php } ?>
</div>
