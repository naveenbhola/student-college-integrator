<?php
 if($currentPage>1){
 echo '<a class="prev" href="'.$urlRequest->getURL($currentPage-1).'"><i class="icon-prev"></i> Previous</a>';
}
if($totalPages>($currentPage + 1)){
    echo '<a class="next" href="'.$urlRequest->getURL($currentPage+1).'">Next <i class="icon-next"></i></a>';
}
?>
