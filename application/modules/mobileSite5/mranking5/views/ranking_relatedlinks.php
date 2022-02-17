<div id="relatedlinks_section"></div>
<?php 
$subcategoryName = $rankingPage->getName();

if($rankingPageOf['FullTimeMba'] == 0 || $rankingPageOf['FullTimeEngineering'] == 0){
	$maximumRelatedLinks = 9;
	$examId 	= $rankingPageRequest->getExamId();
	$cityId 	= $rankingPageRequest->getCityId();
	$stateId 	= $rankingPageRequest->getStateId();
	$countryId 	= $rankingPageRequest->getCountryId();

	$urls = array();
	foreach($filters['city'] as $filter){
		if($cityId != $filter->getId()){
			$cityFilters[] = $filter;
		}
	}
	foreach($filters['state'] as $filter){
		if($stateId != $filter->getId() && strtolower($filter->getName()) != 'all'){
			$stateFilters[] = $filter;
		}
	}

	$counter 	= 0;
	$linkBucket = array();
	$linkBucket['city'] = array();
	$linkBucket['state'] = array();

	while($counter <= $maximumRelatedLinks) {
		//City
		$sliceSize = 3;
		if($maximumRelatedLinks - $counter < 3){
			$sliceSize = $maximumRelatedLinks - $counter;
		}
		if(count($cityFilters) < 3){
			$sliceSize = count($cityFilters);
		}
		$linkBucket['city'] = array_merge($linkBucket['city'], array_slice($cityFilters, 0, $sliceSize));
		array_splice($cityFilters, 0, $sliceSize);
		$counter += $sliceSize;
		if($counter >= $maximumRelatedLinks){
			break;
		}
		
		//State
		$sliceSize = 3;
		if($maximumRelatedLinks - $counter < 3){
			$sliceSize = $maximumRelatedLinks - $counter;
		}
		if(count($stateFilters) < 3){
			$sliceSize = count($stateFilters);
		}
		$linkBucket['state'] = array_merge($linkBucket['state'], array_slice($stateFilters, 0, $sliceSize));
		array_splice($stateFilters, 0, $sliceSize);
		$counter += $sliceSize;
		if($counter >= $maximumRelatedLinks){
			break;
		}
		
		if(count($cityFilters) <= 0 && count($stateFilters) <= 0){
			break;
		}
	}

	if(!empty($linkBucket['city']) || !empty($linkBucket['state'])){
		$urls = array();
		$headerLinkFound = false;
		if(!empty($linkBucket['city'])){
			foreach($linkBucket['city'] as $filter){
				$id 	= $filter->getId();
				if(strtolower($filter->getName()) == 'all'){
					$title 	= "Top ranking colleges offering ". $subcategoryName ." in India";
				} else {
					$title 	= "Top ranking colleges offering ". $subcategoryName ." in " . $filter->getName();
				}
				$temp 	= array();
				$temp['title']		= $title;
				$temp['url']		= $filter->getURL();
				$urls[] 			= $temp;
			}
		}
		if(!empty($linkBucket['state'])){
			foreach($linkBucket['state'] as $filter){
				$id 	= $filter->getId();
				$title 	= "Top ranking colleges offering ". $subcategoryName . " in " . $filter->getName();
				$temp 	= array();
				$temp['title']		= $title;
				$temp['url']		= $filter->getURL();
				$urls[] 			= $temp;
			}
		}

		for($count = 0; $count < count($urls); $count++){
			?>
			<section class="content-wrap2  clearfix">
				<a href="<?php echo $urls[$count]['url'];?>">
				<?php
				if($count==0) {
				?>
					<h2 class="ques-title">
						<p>Related results for <?php echo $subcategoryName;?> courses</p>
					</h2>
				<?php
				}
				?>
				<article class="req-bro-box clearfix" style="cursor: pointer;" >
						<div class="details" style="margin-bottom: 10px;">
								<?php echo $urls[$count]['title'];?>
						</div>
				</article>
				</a>
			</section>
			<?php
		}
	}
}
else{
	if(!empty($rankingRelatedLinks)){
		for($count = 0; $count < count($rankingRelatedLinks); $count++){
			?>
			<section class="content-wrap2  clearfix">
				<a href="<?php echo $rankingRelatedLinks[$count]['url'];?>">
				<?php
				if($count==0) {
				?>
					<h2 class="ques-title">
						<p>Related results for <?php echo $subcategoryName;?> courses</p>
					</h2>
				<?php
				}
				?>
				<article class="req-bro-box clearfix" style="cursor: pointer;" >
						<div class="details" style="margin-bottom: 10px;">
								<?php echo "Top $subcategoryName Colleges in ".$rankingRelatedLinks[$count]['cityName']; ?>
						</div>
				</article>
				</a>
			</section>
			<?php
		}
	}
}
?>