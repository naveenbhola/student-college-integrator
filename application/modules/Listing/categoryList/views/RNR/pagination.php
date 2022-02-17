
<?php
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage = $request->getPageNumberForPagination();
	$totalPages = ceil($totalResults/$request->getSnippetsPerPage());
	$urlRequest = clone $request;
        $start = ($currentPage-1)*$request->getSnippetsPerPage() + 1;
	$end = min($start+$request->getSnippetsPerPage()-1,$totalResults);
?>
<div class="pagination">
    <p class="flLt">Showing <?=$start?> - <?=$end?> of <?=$totalResults?></p>  
	<ul>
		<?php
		if($currentPage > 1){
			echo '<li><a href="'.$urlRequest->getURL($currentPage-1).'" class="prev">Previous</a></li>';
		}
			echo '<li><a href="" class="active">'.$currentPage.'</a></li>';
		if(($currentPage+1) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'">'.($currentPage+1).'</a></li>';
		}		
		if(($currentPage+2) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+2).'">'.($currentPage+2).'</a></li>';
		}	
                if(($currentPage+3) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+3).'">'.($currentPage+3).'</a></li>';
		}
                if(($currentPage+4) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+4).'">'.($currentPage+4).'</a></li>';
		}
                if(($currentPage+5) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+5).'">'.($currentPage+5).'</a></li>';
		}
		if($currentPage < $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'" class="next">Next</a></li>';
		}
		
		?>
	</ul>
</div>