<?php 

 ?>
    <div class="n-pagination">
        <ul>
            <?php if($currentPage > 1){
                $href = "";
                $linkClass = "";
                $hrefLink = "";
                if($product == "Category" || $product == "AllCoursesPage"){
                    if(($currentPage-1) == 1){
                        $href = str_replace(array("-#page_no#","pn=#page_no#"),"", $genericPaginationURL);
                        $href = trim($href,"?");
                    }else{
                        $href = str_replace("#page_no#", ($currentPage-1), $genericPaginationURL);        
                    }
                    $linkClass = " linkpagination";
                    $href = htmlentities($href, ENT_QUOTES);
                    $hrefLink = 'href = "'.$href.'"';
                }
                
                ?>
                    <li class="prev<?php echo $linkClass;?>"><a data-page="<?=$currentPage-1?>" <?php echo $hrefLink;?>><i class="icons ic_left-gry"></i></a></li>
                <?php    
            }
                $startPage = $currentPage-1;
                
                if($totalPages - $startPage < $maxPagesOnPaginitionDisplay){
                    $startPage = $totalPages - $maxPagesOnPaginitionDisplay + 1;
                }
                if($startPage < 1){
                    $startPage = 1;
                }
                
                $loopCountMax = (($maxPagesOnPaginitionDisplay+$startPage-1) > $totalPages) ? $totalPages : ($maxPagesOnPaginitionDisplay+$startPage-1);
                if($startPage != $loopCountMax){
                    for ($pageNo=$startPage; $pageNo<=$loopCountMax ; $pageNo++) {
                        $href = "";
                        $linkClass = "";
                        $hrefLink = "";
                        if($product == "Category" || $product == "AllCoursesPage") {
                            if($pageNo == 1){
                                $href = str_replace(array("-#page_no#","pn=#page_no#"),"", $genericPaginationURL);
                                $href = trim($href,"?");
                            }else{
                                $href = str_replace("#page_no#", $pageNo, $genericPaginationURL);        
                            }
                            $linkClass = " linkpagination";
                            $href = htmlentities($href, ENT_QUOTES);
                            $hrefLink = 'href = "'.$href.'"';
                        }
                        
                        if($pageNo == $currentPage){

                            ?>
                            <li class="actvpage"><a><?php echo $pageNo;?></a></li>
                            <?php
                        }else{
                            ?>
                            <li class="<?php echo $linkClass;?>"><a data-page="<?=$pageNo;?>" <?php echo $hrefLink;?>><?=$pageNo;?></a></li>
                            <?php
                        }
                    }
                }
                
            ?>
            <?php
            if($currentPage != $totalPages){
                $href = "";
                $linkClass = "";
                $hrefLink = "";
                 if($product == "Category" || $product == "AllCoursesPage"){
                    $href = str_replace("#page_no#", ($currentPage+1), $genericPaginationURL);    
                    $linkClass = " linkpagination";
                    $href = htmlentities($href, ENT_QUOTES);
                    $hrefLink = 'href = "'.$href.'"';
                }
                ?>
                <li class="next<?php echo $linkClass;?>"><a data-page="<?=($currentPage+1);?>" <?php echo $hrefLink;?>><i class="icons ic_right-gry"></i></a></li>
                <?php
            }
            ?>
            
        </ul>
        <p class="clr"></p>
    </div>
</div>
