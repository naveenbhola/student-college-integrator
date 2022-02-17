<tr>
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Zone:  </label>
        </div>
    </td>

        <td class="">
          <div class="ul-block">
              <ul class="">

                  <?php
                  foreach($zoneToStateMapping as $zone => $states) {
                    $stateIds = '';
                    foreach ($states as $key => $value) {
                      $stateIds .= $value.',';
                    }
                    $stateIds = substr($stateIds, 0,-1);
                  ?>

                      <li>
                          <div class="Customcheckbox2">
                              <input type="checkbox" id="<?php echo 'zone'.$zone.'_'.$criteriaNo;?>" stateList=<?php echo $stateIds ?> class="zone zone_<?=$criteriaNo;?> clone" />
                              <label for="<?php echo 'zone'.$zone.'_'.$criteriaNo;?>" class="clone"><?php echo $zoneIdMapping[$zone];?></label>
                          </div> 

                      </li>

                  <?php
                  }                      
                  ?>

              </ul>

          </div>

      </td>  

</tr>
