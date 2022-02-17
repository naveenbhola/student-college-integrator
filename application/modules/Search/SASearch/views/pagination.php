<!--pagination placeholder-->
<?php if($paginationDetails['noData'] != true) { ?>
<div class="paginationDiv">
  <ul class="newPagination">
      <?php
      if($paginationDetails['prevActive']){ ?>
        <li class="prev"><a href="<?php echo $paginationDetails['prevLink'] ?>"><i class="srpSprite iprev"></i>prev</a></li>
      <?php } ?>
      <?php
      echo $paginationDetails['allLinks'];
      ?>
      <?php
      if($paginationDetails['nextActive']){ ?>
        <li class="next"><a href="<?php echo $paginationDetails['nextLink'] ?>"><i class="srpSprite inext"></i>next</a></li>
      <?php } ?>
  </ul>
</div>
<?php } ?>
<!--pagination ends-->