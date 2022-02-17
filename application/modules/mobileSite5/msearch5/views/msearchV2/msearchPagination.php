<!-- pagination -->
<?php 
$lazyLoadsPerPage = 4;
$maxPages = ceil($totalInstituteCount/SEARCH_PAGE_LIMIT_MOBILE);
$maxPageSlots = ceil($totalInstituteCount/(SEARCH_PAGE_LIMIT_MOBILE * $lazyLoadsPerPage));
$currentPageNum = $request->getCurrentPageNum(); //1 5 9 13 17
$currentPageSlot = ceil($currentPageNum/$lazyLoadsPerPage); //1 2 3 4 5

$pagereq = clone $request;
if($currentPageNum > $maxPages && $maxPages > 0){ //safe check
    $pagereq->setPageNumber(1);
    redirect($pagereq->getUrl(),'location',301);
}

if($totalInstituteCount > 0 && $maxPages > $lazyLoadsPerPage) { ?>
    <div id="searchPagination" class="pagnation-col">
        <ul class="pagniatn-ul">
            <li>
                <?php if($currentPageSlot > 1) { 
                    $pagereq->setPageNumber($currentPageNum - $lazyLoadsPerPage); ?>
                    <a class='leftarrow' href="<?php echo $pagereq->getUrl(); ?>"> ❮ </a>
                <?php } else { ?>
                    <a class="leftarrow disable-link"> ❮ </a>
                <?php } ?>
            </li>
            
            <li>
                <?php if($currentPageSlot == 1) { ?>
                    <a class="active"><?php echo $currentPageSlot; ?></a>
                <?php } 
                elseif($currentPageSlot == 2) {
                    $pagereq->setPageNumber($currentPageNum - $lazyLoadsPerPage); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 1); ?></a>
                <?php } 
                elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
                    $pagereq->setPageNumber($currentPageNum - (3 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 3); ?></a>
                <?php }
                else {
                    $pagereq->setPageNumber($currentPageNum - (2 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 2); ?></a>
                <?php } ?>
            </li>
            
            <?php if($maxPageSlots >= 2) { ?>
            <li>
                <?php if($currentPageSlot == 1) { 
                    $pagereq->setPageNumber($currentPageNum + $lazyLoadsPerPage); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 1); ?></a>
                <?php } 
                elseif($currentPageSlot == 2) { ?>
                    <a class="active"><?php echo $currentPageSlot; ?></a>
                <?php } 
                elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) {
                    $pagereq->setPageNumber($currentPageNum - (2 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 2); ?></a>
                <?php } 
                else {
                    $pagereq->setPageNumber($currentPageNum - $lazyLoadsPerPage); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 1); ?></a>
                <?php } ?>
            </li>
            <?php } ?>
            
            <?php if($maxPageSlots >= 3) { ?>
            <li>
                <?php if($currentPageSlot == 1) { 
                    $pagereq->setPageNumber($currentPageNum + (2 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 2); ?></a>
                <?php } 
                elseif($currentPageSlot == 2) { 
                    $pagereq->setPageNumber($currentPageNum + (1 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 1); ?></a>
                <?php } 
                elseif($currentPageSlot == $maxPageSlots && $maxPageSlots > 3) { 
                    $pagereq->setPageNumber($currentPageNum - (1 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot - 1); ?></a>
                <?php } 
                else { ?>
                    <a class="active"><?php echo $currentPageSlot; ?></a>
                <?php } ?>
            </li>
            <?php } ?>

            <?php if($maxPageSlots >= 4) { ?>
            <li>
                <?php if($currentPageSlot == 1) { 
                    $pagereq->setPageNumber($currentPageNum + (3 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 3); ?></a>
                <?php } 
                elseif($currentPageSlot == 2) { 
                    $pagereq->setPageNumber($currentPageNum + (2 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 2); ?></a>
                <?php } 
                elseif($currentPageSlot == $maxPageSlots) { ?>
                    <a class="active"><?php echo ($currentPageSlot); ?></a>
                <?php } 
                else { 
                    $pagereq->setPageNumber($currentPageNum + (1 * $lazyLoadsPerPage)); ?>
                    <a href="<?php echo $pagereq->getUrl(); ?>"><?php echo ($currentPageSlot + 1); ?></a>
                <?php } ?>
            </li>
            <?php } ?>
            
            <li>
                <?php if($currentPageSlot < $maxPageSlots) { 
                    $pagereq->setPageNumber($currentPageNum + $lazyLoadsPerPage); ?>
                    <a class='rightarrow' href="<?php echo $pagereq->getUrl(); ?>"> ❯ </a>
                <?php } else { ?>
                    <a class="rightarrow disable-link"> ❯ </a>
                <?php } ?>
            </li>
            <p class="clr"></p>
        </ul>
    </div>
<?php } ?>