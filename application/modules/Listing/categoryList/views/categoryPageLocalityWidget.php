<?php
global $pageHeading;
global $filters;
$requestUrl = clone $request;
if(!in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
	if($filters['locality'] && $request->getCityId() != 1)
	{
		$localityFilterValues = $filters['locality']->getFilteredValues();
			if(count($localityFilterValues) > 0){
				
	?>
	<div class="spacer20 clearFix"></div>
	<div class="otherCoursesBlock">
		<h4>Browse by Locality</h4>
		<div class="otherCoursesContent">
			<ul>
				<?php foreach($localityFilterValues as $zone=>$filter){
					$requestUrl->setData(array('zoneId'=>$zone,'localityId'=>0));
				?>
				<li onclick="othertoggleMe('zone<?=$zone?>');">
					<div  class="plus-icon" id="zone<?=$zone?>Sign"></div>
					<div class="otherCourseDetails">
						<strong><a style="cursor:pointer">Colleges in <?=$filter['name']?></a></strong>
						<div id="zone<?=$zone?>Container" class="hidedetails">
							<b><a href="<?=$requestUrl->getURL()?>"><?=$pageHeading?> in <?=$filter['name']?></a></b><br/>
							<?php
								$tempArray = array();
								foreach($filter['localities'] as $locality=>$filter1){
									$requestUrl->setData(array('zoneId'=>$zone,'localityId'=>$locality));
									$tempArray[] = '<a href="'.$requestUrl->getURL().'">'.$pageHeading.' in '.$filter1.'</a>';
								}
							?>
							<?=implode("<span> | </span>",$tempArray)?>
						</div>
					</div>
				</li>
				<?php } ?>
			</ul>
			<div class="clearFix"></div>
		</div>
		<div class="clearFix"></div>
	</div>
	<?php
		}
	}
}
?>