<?php
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage = $request->getPageNumberForPagination();
	$start = ($currentPage-1)*$request->getSnippetsPerPage() + 1;
	$end = min($start+$request->getSnippetsPerPage()-1,$totalResults)
	
?>
<p>Showing <?=$start?> - <?=$end?> of <span id="catPageTotResults"><?=$totalResults?></span></p>