<!-- code for pagination -->
<?php 
$pagereq = clone $request;
$maxpages = ceil($totalInstituteCount/SEARCH_PAGE_LIMIT);
$currpagenum = $request->getCurrentPageNum();
if($currpagenum > $maxpages && $maxpages > 0){
  $pagereq->setPageNumber(1);
  redirect($pagereq->getUrl(),'location',301);
}
if($totalInstituteCount > 0 && $maxpages > 1){
  ?>
    <div class="n-pagination">
        <ul>
          <?php 
          if($currpagenum-1 > 0){
            ?>
            <li class="prev"><a data-page="<?=$currpagenum-1?>"><i class="icons ic_left-gry"></i></a></li>
            <?php
          }
          ?>
          <li class="actvpage"><a><?php echo $currpagenum; ?></a></li>
          <?php 
          if($currpagenum+1 <= $maxpages){
            ?>
            <li><a data-page="<?=$currpagenum+1?>"><?php echo $currpagenum+1; ?></a></li>
            <?php
          }
          if($currpagenum+2 <= $maxpages){
            ?>
            <li><a data-page="<?=$currpagenum+2?>"><?php echo $currpagenum+2; ?></a></li>
            <?php
          }
          if($currpagenum+3 <= $maxpages){
            ?>
            <li class="truncate"><a>. . .</a></li>
            <?php
          }
          if($currpagenum+1 <= $maxpages){
            ?>
            <li class="next"><a data-page="<?=$currpagenum+1?>"><i class="icons ic_right-gry"></i></a></li>
            <?php
          }
          ?>
        </ul>
        <p class="clr"></p>
    </div>   
  <?php
}

  ?>
</div>
  <p class="clr"></p>