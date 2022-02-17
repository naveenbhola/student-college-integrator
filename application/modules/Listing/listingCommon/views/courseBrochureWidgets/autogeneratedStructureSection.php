<div class="cmn-card mb2">
    <h2 class="f20 clor3 mb2 f-weight1">Course Structure</h2>
    <?php 
      $counter = 0;
      foreach ($courseStructure as $key => $value) {
          $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';
          if($value['period'] != 'program') { ?>
              <h3 class="prt-title mb2 f16"><?=ucfirst($value['period'])." ".$key." ".$courseTextString;?></h3>
    <?php } ?>
        <ul class="partner-clgs">
    <?php 
          foreach ($value['structure'] as $key => $val) { ?>
              <li><p class="f16 clor6"><?=$val['courses_offered'];?></p></li>
    <?php } ?>
          </ul>
    <?php }
    ?>
</div>