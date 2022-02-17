<!-- START : CATEGORY PAGE PAGINATION SECTION-->
<?php
	$totalResults = $totalTuplesOnPage;
	$currentPage = $categoryPageRequest->getPageNumberForPagination();
	$resultsPerPage = $categoryPageRequest->getSnippetsPerPage();
	$totalPages = ceil($totalResults/$resultsPerPage);	
	$startingResultOfCurrentPage = ($resultsPerPage * ($currentPage - 1)) + 1;
	$endingResultOfCurrentPage = $resultsPerPage * $currentPage;
	if($endingResultOfCurrentPage > $totalResults) {
		$endingResultOfCurrentPage = $totalResults;
	}
	$collegeText = ($totalResults > 1)?$totalResults." results":$totalResults." result";
	
	$urlRequest = clone $categoryPageRequest;
	
	// Now to get the range of page numbers to be shown
	$minPage = 0;
	$maxPage = 0;
	switch($currentPage){
		case 1:
		case 2:
			$minPage = 1; $maxPage = min(5,$totalPages);
			break;
		default:
			$minPage = $currentPage-2;
			$maxPage = min($currentPage+2,$totalPages);
	}
	if($totalPages-$currentPage < 2){
		$minPage = max(1,$totalPages-4);
		$maxPage = $totalPages;
	}
?>

<div class="pagination clearwidth">
	<p class="flLt">Showing <?=$startingResultOfCurrentPage?> - <?=$endingResultOfCurrentPage?>  of <?=$collegeText?></p>
	<div class="flRt">
		<ul class="pagination-list">
			<?php
			if($currentPage > 1){ ?>
				<li class="first-child">
					<a href="<?=$urlRequest->getURL($currentPage-1)?>" onclick="studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'Pagination');">Previous</a>
				</li>
			<?php } ?>
			<?php
			for($i=$minPage;$i<=$maxPage;$i++){
				echo "<li>";
				if($i == $currentPage){
					echo "<a class='active' onclick=\"studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'Pagination');\">".$i."</a>";
				}
				else{
					echo "<a href='".$urlRequest->getURL($i)."' onclick=\"studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'Pagination');\">".$i."</a>";
				}
				echo "</li>";
			}
			?>
			<?php if($currentPage < $totalPages){ ?>
				<li class="last-child">
					<a href="<?=$urlRequest->getURL($currentPage+1)?>" onclick="studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'Pagination');">Next</a>
				</li>
			<?php } ?>
		</ul>
    </div>
</div>
<!-- END : CATEGORY PAGE PAGINATION SECTION-->

<!-- Back to top section -->
<!--a href="javascript:void(0);" class="backtop-btn" id="toTop" style="left: 77.8%"><i class="common-sprite bcktop-icon"></i>Back to top</a-->
