<div class="clear__float pagnation-col">
      <ul class="pagniatn-ul">
        <li class="pgNo prev" id="pagination_prev"><a href='javascript:void(0)' class='leftarrow disable-link' data-page="1"> ❮ </a>
        </li>
        <?php
        
        for($pageNo = 1; $pageNo <= $totalPages; $pageNo++)
        {     
        if($pageNo == 1)
        { ?>
              <li class="pgNo active"><a href='javascript:void(0)' data-page='1'><?php echo $pageNo; ?></a></li>      
        <?php
        }
        else
        { ?>
              <li class="pgNo <?php if($pageNo>$maxPageOnPaginationDisplay) echo "hid";?>"><a href='javascript:void(0)' data-page="<?php echo $pageNo; ?>" ><?php echo $pageNo; ?></a></li>      
        <?php }
        } 
        ?>
        <li class="pgNo next" id="pagination_next"><a href='javascript:void(0)' class='rightarrow' data-page="2"> ❯ </a>
      </li>
    </ul>
    <input type='hidden' id='totalPages' value='<?php echo $totalPages; ?>'></input>
    <input type='hidden' id='maxPageDisplay' value='<?php echo $maxPageOnPaginationDisplay; ?>'></input>
</div>