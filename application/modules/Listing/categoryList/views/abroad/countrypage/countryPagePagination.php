<!-- START : CATEGORY PAGE PAGINATION SECTION-->
<?php
	if(empty($totalCount))
		return;
	
	$totalResults = $totalCount;
	$currentPage = $paginationArr["pageNumber"];
	$resultsPerPage = $paginationArr["limitRowCount"];
	$totalPages = ceil($totalResults/$resultsPerPage);
	$startingResultOfCurrentPage = ($resultsPerPage * ($currentPage - 1)) + 1;
	$endingResultOfCurrentPage = $resultsPerPage * $currentPage;
	if($endingResultOfCurrentPage > $totalResults) {
		$endingResultOfCurrentPage = $totalResults;
	}
	
	$urlRequest = clone $categoryPageRequest;
	//$countryPageUrl = $urlRequest->getURLForCountryPage($countryId);
	
?>

<div class="pagination clearwidth">
	<p class="flLt">Showing <?=$startingResultOfCurrentPage?> - <?=$endingResultOfCurrentPage?>  of <?=$totalResults?> <?=($totalResults == 1)?"university":"universities"?></p>
	<div class="flRt">
		<ul class="pagination-list">
			<?php
			if($currentPage > 1){ ?>
				<li class="first-child">
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage-1);?>">Previous</a>
				</li>
			<?php } ?>
			
			<li>
				<a class="active" href=""><?=$currentPage?></a>
			</li>
			
			<?php if(($currentPage+1) <= $totalPages){ ?>
				<li>
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage+1);?>"><?=$currentPage+1?></a>
				</li>
			<?php } ?>
			
			<?php if(($currentPage+2) <= $totalPages){ ?>
				<li>
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage+2);?>"><?=$currentPage+2?></a>
				</li>
			<?php } ?>
			
			<?php if(($currentPage+3) <= $totalPages){ ?>
				<li>
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage+3)?>"><?=$currentPage+3?></a>
				</li>
			<?php } ?>
			
			<?php if(($currentPage+4) <= $totalPages){ ?>
				<li>
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage+4)?>"><?=$currentPage+4?></a>
				</li>
			<?php } ?>
			
			<?php if($currentPage < $totalPages){ ?>
				<li class="last-child">
					<a href="<?=$urlRequest->getURLForCountryPage($countryId, $currentPage+1)?>">Next</a>
				</li>
			<?php } ?>
		</ul>
    </div>
</div>