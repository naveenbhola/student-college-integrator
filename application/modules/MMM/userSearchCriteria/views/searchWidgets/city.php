<tr>
    <td class="nxt-td">
        <div class="nxt">
            <label class="label"><?php echo (empty($cityLabel))?'City:':$cityLabel;?>  </label>
            <?php
              if(!empty($cityLabel)){ ?>
                <label class="label-text" style="font-size: 12px">(By default, all locations are selected.)</label>
              <?php }
            ?>
        </div>
    </td>

        <td class="">
          <div class="ul-block">
              <ul class="">

                  <li>
                      <div class="Customcheckbox2">
                          <input type="checkbox" zones="<?php echo $allZonesInMetroCity; ?>" isVirtualCity="1" id="cmsMetroCities_<?=$criteriaNo;?>" class="parentCity parentCity_<?=$criteriaNo;?> clone"/>
                          <label for="cmsMetroCities_<?=$criteriaNo;?>" class="clone">Metropolitian Cities</label>
                      </div> 

                      <div class="l-col" id="cmsMetroCitiesSubCity_<?=$criteriaNo;?>">
                          <ul class="">

                          <?php 
                          $virtualCityIds = array();
                          foreach($virtualCities as $list) { 
                              $virtualCityIds[] = $list['cityId'];
                              ?>

                              <li>
                                  <div class="Customcheckbox2">
                                      <input type="checkbox" parentId="cmsMetroCities_<?=$criteriaNo;?>" class="subCities subCities_<?=$criteriaNo;?> cmsMetroCities_<?=$criteriaNo;?> clone" id="<?php echo "virtualcurrentCities".$list['cityId'].'_'.$criteriaNo;?>" value="<?php echo $list['cityId']; ?>" isVirtualCity="1" needLocality="1" currentLocCitiesName="<?php echo $list['cityName']; ?>"/>
                                      <label for="<?php echo "virtualcurrentCities".$list['cityId'].'_'.$criteriaNo;?>" class="clone"><?php echo $list['cityName']; ?></label>
                                  </div> 
                              </li>

                            <?php } ?>

                          </ul>
                      </div>

                  </li>


                  <?php
                  foreach($stateCities as $stateCity) { 
                      $stateId = $stateCity['StateId'];
                      $zones = '';
                      foreach ($stateCity['zone'] as $zone){
                        $zones .= $zone.',';
                      }
                      $zones = substr($zones, 0,-1);
                  ?>

                      <li>
                          <div class="Customcheckbox2">
                              <input type="checkbox" zones='<?php echo $zones; ?>' isVirtualCity="0" id="<?php echo $stateId.'_'.$criteriaNo;?>" class="parentCity parentCity_<?=$criteriaNo;?> clone" />
                              <label for="<?php echo $stateId.'_'.$criteriaNo;?>" class="clone"><?php echo $stateCity['StateName'];?></label>
                          </div> 

                          <div class="l-col clone" id="<?php echo $stateCity['StateName'];?>SubCity_<?=$criteriaNo;?>">
                              <ul class="">

                                <?php
                                foreach($stateCity['cityMap'] as $cities) { 
                                    $needLocality = 0;
                                    if((in_array($cities['CityId'], $citiesHavingLocalities)) || (in_array($cities['CityId'], $virtualCityIds)) || ($virtualCitiesChildParentMapping[$cities['CityId']] > 0)) {
                                      $needLocality = 1;
                                    }
                                ?>

                                    <li>
                                      <div class="Customcheckbox2">
                                          <input type="checkbox" parentId="<?php echo $stateId.'_'.$criteriaNo;?>" class="subCities subCities_<?=$criteriaNo;?> <?php echo $stateId.'_'.$criteriaNo;?> clone" id="<?php echo "currentCities".$cities['CityId'].'_'.$criteriaNo;?>" isVirtualCity="0" needLocality="<?php echo $needLocality;?>" value="<?php echo $cities['CityId']; ?>" currentLocCitiesName="<?php echo $cities['CityName']; ?>"/>
                                          <label for="<?php echo "currentCities".$cities['CityId'].'_'.$criteriaNo;?>" class="clone"><?php echo $cities['CityName']; ?></label>
                                      </div> 
                                    </li>

                                <?php } ?>

                              </ul>
                          </div>

                      </li>

                  <?php
                  }                      
                  ?>

              </ul>

          </div>

          <div style="width:290px;margin-top:10px;">
              <div style="border:1px solid #e8e6e7;background:#fffbff;padding:10px 15px 0">
                  <div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="javascript:void(0);" class="removeAllCurrentLocation" style="font-size:11px">Remove all</a>&nbsp;]</div>
                  <div id="cmsSearchCurrentLocDiv_<?=$criteriaNo?>" class="clone"></div>
              </div>
          </div>

      </td>  

</tr>

<tr id="localityBlock_<?=$criteriaNo;?>" class="clone" style="display:none">
    <td class="nxt-td">
        <div class="nxt">
            <label class="label" style="margin-top:6px;">Localities:</label>
        </div>
    </td>

    <td class="clone" id="localitiesBlock_<?=$criteriaNo;?>" style="padding-top:0;"></td>
</tr>
