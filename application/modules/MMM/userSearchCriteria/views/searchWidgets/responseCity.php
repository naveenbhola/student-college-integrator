<tr>
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Response City:</label>
        </div>
    </td>

        <td class="">
          <div class="ul-block">
              <ul class="">

                  <li>
                      <div class="Customcheckbox">
                          <input type="checkbox" isVirtualCity="1" id="rescmsMetroCities_<?=$criteriaNo;?>" class="resparentCity resparentCity_<?=$criteriaNo;?> clone"/>
                          <label for="rescmsMetroCities_<?=$criteriaNo;?>" class="clone">Metropolitian Cities</label>
                      </div> 

                      <div class="l-col" id="rescmsMetroCitiesSubCity_<?=$criteriaNo;?>">
                          <ul class="">

                          <?php 
                          $virtualCityIds = array();
                          foreach($virtualCities as $list) { 
                              $virtualCityIds[] = $list['cityId'];
                              ?>

                              <li>
                                  <div class="Customcheckbox">
                                      <input type="checkbox" parentId="rescmsMetroCities_<?=$criteriaNo;?>" class="ressubCities ressubCities_<?=$criteriaNo;?> rescmsMetroCities_<?=$criteriaNo;?> clone" id="<?php echo "resvirtualcurrentCities".$list['cityId'].'_'.$criteriaNo;?>" value="<?php echo $list['cityId']; ?>" isVirtualCity="1" needLocality="0" currentLocCitiesName="<?php echo $list['cityName']; ?>"/>
                                      <label for="<?php echo "resvirtualcurrentCities".$list['cityId'].'_'.$criteriaNo;?>" class="clone"><?php echo $list['cityName']; ?></label>
                                  </div> 
                              </li>

                            <?php } ?>

                          </ul>
                      </div>

                  </li>


                  <?php $needLocality = 0;
                  foreach($stateCities as $stateCity) { 
                      $stateId = $stateCity['StateId'];
                  ?>

                      <li>
                          <div class="Customcheckbox">
                              <input type="checkbox" isVirtualCity="0" id="<?php echo 'res'.$stateId.'_'.$criteriaNo;?>" class="resparentCity resparentCity_<?=$criteriaNo;?> clone" />
                              <label for="<?php echo 'res'.$stateId.'_'.$criteriaNo;?>" class="clone"><?php echo $stateCity['StateName'];?></label>
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
                                      <div class="Customcheckbox">
                                          <input type="checkbox" parentId="<?php echo 'res'.$stateId.'_'.$criteriaNo;?>" class="ressubCities ressubCities_<?=$criteriaNo;?> <?php echo 'res'.$stateId.'_'.$criteriaNo;?> clone" id="<?php echo "rescurrentCities".$cities['CityId'].'_'.$criteriaNo;?>" isVirtualCity="0" needLocality="<?php echo $needLocality;?>" value="<?php echo $cities['CityId']; ?>" currentLocCitiesName="<?php echo $cities['CityName']; ?>"/>
                                          <label for="<?php echo "rescurrentCities".$cities['CityId'].'_'.$criteriaNo;?>" class="clone"><?php echo $cities['CityName']; ?></label>
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
                  <div class="txt_align_r" style="padding-bottom:5px">[&nbsp;<a href="javascript:void(0);" class="resremoveAllCurrentLocation" style="font-size:11px">Remove all</a>&nbsp;]</div>
                  <div id="rescmsSearchCurrentLocDiv_<?=$criteriaNo?>" class="clone"></div>
              </div>
          </div>

      </td>  

</tr>

<tr id="reslocalityBlock_<?=$criteriaNo;?>" class="clone" style="display:none">
    <td class="nxt-td">
        <div class="nxt">
            <label class="label" style="margin-top:6px;">Localities:</label>
        </div>
    </td>

    <td class="clone" id="reslocalitiesBlock_<?=$criteriaNo;?>" style="padding-top:0;"></td>
</tr>