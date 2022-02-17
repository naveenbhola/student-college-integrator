<!-- START : SCHOLARSHIP CATEGORY PAGE PAGINATION SECTION-->
<?php
	$totalResults = $totalTupleCount;
	$currentPage = $request->getPageNumber();
	$resultsPerPage = $request->getSnippetsPerPage();
	$totalPages = ceil($totalResults/$resultsPerPage);	
	$startingResultOfCurrentPage = ($resultsPerPage * ($currentPage - 1)) + 1;
	$endingResultOfCurrentPage = $resultsPerPage * $currentPage;
	if($endingResultOfCurrentPage > $totalResults) {
		$endingResultOfCurrentPage = $totalResults;
	}
	$scholarshipText = ($totalResults > 1)?$totalResults." results":$totalResults." result";
	
	$urlRequest = clone $request;
	
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
	$display = ($totalTupleCount==0 ?'style="display:none;"':'');
?>

<div class="pagination clearwidth" id="pagination" <?php echo $display; ?> >
	<p class="flLt">Showing <?=$startingResultOfCurrentPage?> - <?=$endingResultOfCurrentPage?>  of <?=$scholarshipText?></p>
	<div class="flRt">
		<ul class="pagination-list">
			<?php
			if($currentPage > 1){ ?>
				<li class="first-child">
					<a href="<?=$urlRequest->getPaginatedURL($currentPage-1)?>" onclick="studyAbroadTrackEventByGA('SA_SCHOLARSHIP_CATEGORY_PAGE', 'Pagination');">Previous</a>
				</li>
			<?php } ?>
			<?php
			for($i=$minPage;$i<=$maxPage;$i++){
				echo "<li>";
				if($i == $currentPage){
					echo "<a class='active' onclick=\"studyAbroadTrackEventByGA('SA_SCHOLARSHIP_CATEGORY_PAGE', 'Pagination');\">".$i."</a>";
				}
				else{
					echo "<a href='".$urlRequest->getPaginatedURL($i)."' onclick=\"studyAbroadTrackEventByGA('SA_SCHOLARSHIP_CATEGORY_PAGE', 'Pagination');\">".$i."</a>";
				}
				echo "</li>";
			}
			?>
			<?php if($currentPage < $totalPages){ ?>
				<li class="last-child">
					<a href="<?=$urlRequest->getPaginatedURL($currentPage+1)?>" onclick="studyAbroadTrackEventByGA('SA_SCHOLARSHIP_CATEGORY_PAGE', 'Pagination');">Next</a>
				</li>
			<?php } ?>
		</ul>
    </div>
</div>
<!-- END : SCHOLARSHIP CATEGORY PAGE PAGINATION SECTION-->