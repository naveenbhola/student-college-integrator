<?php
if($totalItems <= $pageSize || ($pageSize<=0 || $pageSize=='') || $totalItems<=0 || !is_numeric($totalItems) || !is_numeric($currentPage) || $currentPageUrl == '' || $queryParam == '' || ($pageSize && !is_numeric($pageSize)) || ($maxPages && !is_numeric($maxPages))){
		return null;
	}
	$result = pagination($totalItems, $currentPage, $pageSize);
	$paginationUrl = $currentPageUrl.'?'.$queryParam.'=';
    $prevUrl = ($result['currentPage']>=2) ? $paginationUrl.($result['currentPage']-1) : '';
    $nextUrl = ($result['currentPage']<$result['totalPages']) ? $paginationUrl.($result['currentPage']+1) : '';
?>
<div class="pagnation-col">
    <ul class="pagniatn-ul">
    	<?php if($prevUrl){?>
    		<li><a class="leftarrow" href="<?php echo $prevUrl?>"> <i class="Lft-arrw"></i> </a></li>
    	<?php }else{?>
    		<li><a class="leftarrow disable-link" href="javascript:void(0);"> <i class="LftDisbl-arrw"></i> </a></li>
        <?php }?>

        <?php foreach($result['pages'] as $row){ $activeClass = ($result['currentPage'] == $row) ? 'active' : '';?>
        		<li><a class="<?php echo $activeClass;?>" href="<?php echo $paginationUrl.$row?>"><?php echo $row;?></a></li>
    	<?php }?>

        <?php if($nextUrl){?>
    		<li><a class="rightarrow" href="<?php echo $nextUrl?>"> <i class="Rgt-arrw"></i> </a></li>
    	<?php }else{?>
    		<li><a class="rightarrow disable-link" href="javascript:void(0);"> <i class="RgtDisbl-arrw"></i> </a></li>
        <?php }?>
    </ul>
</div>