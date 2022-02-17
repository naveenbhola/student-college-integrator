<div class="cmn-card mb2">
    <h2 class="f20 clor3 mb2 f-weight1">Partner Colleges</h2>
    <div class="colgs-col">
       <ul>
          <?php foreach ($partners['data'] as $key => $value) { ?>
              <li>
                 <div class="pt-clg">
                     <h2 class="titl f16 clor3 f-weight1 mb2"><?=htmlentities($value['name']);?></h2>
                      <?php
                        if($partners['checkDurationExistForOne']) {
                          $duration = ($value['duration']) ? $value['duration'] : '-'; ?>
                            <p class="f16 f-weight1 clor6">Duration <strong class="f-semi"><?=$duration?></strong></p>
                      <?php }
                     ?>
                 </div>
               </li>
          <?php } ?>
        </ul>
    </div>
 </div>