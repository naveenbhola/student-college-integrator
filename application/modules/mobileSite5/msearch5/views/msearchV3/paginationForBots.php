<?php 
	if($totalPages > 1){
		?>
		<noscript>
			<div id="searchPagination" class="pagnation-col">
				<ul class="pagniatn-ul">
					<?php 
						if($currentPage > 1){
							?><a class='leftarrow' href="<?php echo str_replace('#page_no#',$currentPage-1,$genericPaginationURL); ?>"> ❮ </a><?php
						}
						else{
							?><a href="javascript:void(0)" class="leftarrow disable-link"> ❮ </a><?php
						}

						$range = 4;$links = array();
						for($i=$currentPage,$j = $range;$j >= 0;$j--){
							if($i-$j > 0){
								$links[] = array('url'=>str_replace("#page_no#",$i-$j,$genericPaginationURL),'pageNumber'=>$i-$j);
							}
							else{
								$links[] = null;
							}
						}
						for($i = $currentPage,$j = 1;$j < $range;$j++){
							if($i+$j <= $totalPages){
								$links[] = array('url'=>str_replace("#page_no#",$i+$j,$genericPaginationURL),'pageNumber'=>$i+$j);
							}
							else{
								$links[] = null;
							}
						}
						
						$i = count($links)/2;$j = $i + $range/2;$html = '';
						for($k = $i;$k >= $i-$range/2;$k--){
							if(!empty($links[$k])){
								$html = "<a href={$links[$k]['url']}>{$links[$k]['pageNumber']}</a>".$html;
							}
							else{
								$j++;
							}
						}
						for($k = $i+1;$k<=$j;$k++){
							if(!empty($links[$k])){
								$html .= "<a href={$links[$k]['url']}>{$links[$k]['pageNumber']}</a>";
							}
						}

						echo $html;

						if($currentPage < $totalPages){
							?><a class='rightarrow' href="<?php echo str_replace('#page_no#',$currentPage+1,$genericPaginationURL); ?>"> ❯ </a><?php
						}
						else{
							?><a href="javascript:void(0)" class="rightarrow disable-link"> ❯ </a><?php
						}
					?>
				</ul>
			</div>
		</noscript>
		<?php 
	}
?>