<?php
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage = $request->getPageNumberForPagination();
	$totalPages = ceil($totalResults/$request->getSnippetsPerPage());
	$urlRequest = clone $request;
?>
<div class="globalPagination">
	<ul>
		<?php
		if($currentPage > 1){
			echo '<li><a href="'.$urlRequest->getURL($currentPage-1).'">Previous</a></li>';
		}
			echo '<li class="activePage"><a href="">'.$currentPage.'</a></li>';
		if(($currentPage+1) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'">'.($currentPage+1).'</a></li>';
		}		
		if(($currentPage+2) <= $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+2).'">'.($currentPage+2).'</a></li>';
		}		
		if($currentPage < $totalPages){
			echo '<li><a href="'.$urlRequest->getURL($currentPage+1).'">Next</a></li>';
		}
		
		?>
	</ul>
</div>