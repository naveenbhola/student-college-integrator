<?php
	$totalResults = $sorterService->getNoOfForms();
	$currentPage = $page;
	$totalPages = $sorterService->getPages();
	$rawUrl  =  explode("/<page>/",$url);
	$rawUrl = str_replace("<tabId>",$tabId,$rawUrl[0]."/".$currentPage."/");
?>
<div class="globalPagination" style="float:left">
	
	<ul>
		<li style="border:none">Results per page:</li>
	<?php if($limit == 5) {	
	?>
		<li class="activePage"><a href="">5</a></li>
	<?php
	}else{
	?>
		<li><a href="<?=$rawUrl."5"?>">5</a></li>
	<?php
	}
	?>
	<?php if($limit == 10) {	
	?>
		<li class="activePage"><a href="">10</a></li>
	<?php
	}else{
	?>
		<li><a href="<?=$rawUrl."10"?>">10</a></li>
	<?php
	}
	?>
	<?php if($limit == 20) {	
	?>
		<li class="activePage"><a href="">20</a></li>
	<?php
	}else{
	?>
		<li><a href="<?=$rawUrl."20"?>">20</a></li>
	<?php
	}
	?>
	<?php if($limit == 50) {	
	?>
		<li class="activePage"><a href="">50</a></li>
	<?php
	}else{
	?>
		<li><a href="<?=$rawUrl."50"?>">50</a></li>
	<?php
	}
	?>
	<?php if($limit == 100) {	
	?>
		<li class="activePage"><a href="">100</a></li>
	<?php
	}else{
	?>
		<li><a href="<?=$rawUrl."100"?>">100</a></li>
	<?php
	}
	?>
	</ul>
</div>
<?php
	if($totalPages > 1){
?>
<div class="globalPagination">
	<ul>
		<?php
		if($currentPage > 1){
			echo '<li><a href="'.str_replace("<tabId>",$tabId,str_replace("<page>",$currentPage-1,$url)).'">Previous</a></li>';
		}
			echo '<li class="activePage"><a href="">'.$currentPage.'</a></li>';
		if(($currentPage+1) <= $totalPages){
			echo '<li><a href="'.str_replace("<tabId>",$tabId,str_replace("<page>",$currentPage+1,$url)).'">'.($currentPage+1).'</a></li>';
		}		
		if(($currentPage+2) <= $totalPages){
			echo '<li><a href="'.str_replace("<tabId>",$tabId,str_replace("<page>",$currentPage+2,$url)).'">'.($currentPage+2).'</a></li>';
		}		
		if($currentPage < $totalPages){
			echo '<li><a href="'.str_replace("<tabId>",$tabId,str_replace("<page>",$currentPage+1,$url)).'">Next</a></li>';
		}
		
		?>
	</ul>
</div>
<?php
	}
?>