
<div class="n-pagination">
      <ul class='pagination'>
            <li class="pgNo hid prev" id="pagination_prev"><a data-page="1"><i class="icons ic_left-gry"></i></a></li>
            <?php
           
            for($pageNo = 1; $pageNo <= $totalPages; $pageNo++)
            {     
            if($pageNo == 1)
            { ?>
                  <li class="pgNo actvpage"><a data-page='1'><?php echo $pageNo; ?></a></li>      
            <?php
            }
            else
            { ?>
                  <li class="pgNo <?php if($pageNo>$maxPageOnPaginationDisplay) echo "hid";?>"><a data-page="<?php echo $pageNo; ?>" ><?php echo $pageNo; ?></a></li>      
            <?php }
            } 
            ?>
            <li class="pgNo next" id="pagination_next"><a data-page="2"><i class="icons ic_right-gry"></i></a></li>
      </ul>
      <input type='hidden' id='totalPages' value='<?php echo $totalPages; ?>'></input>
      <input type='hidden' id='maxPageDisplay' value='<?php echo $maxPageOnPaginationDisplay; ?>'></input>
      <p class="clr"></p>
</div>
