<?php 
  $stateCities = $country_state_city_list[0]['stateMap'];
  $locationids = explode(',', trim($location));
  $locationids = array_fill_keys($locationids,"1");
  $AllIndiaDefault =0;
  if (empty($location)){
    $AllIndiaDefault =1;
  }
?>

<div class="ul-block <?php echo $id?>" AllIndiaDefault = '<?php echo $AllIndiaDefault;?>' id = '<?php echo $id;?>' 
  cityBlock = "cityBlock">
              <ul class="">

                  <li>

                      <div class="Customcheckbox2">
                          <input type="checkbox" isAllIndia="1" id="<?php echo $id; ?>_AllIndia" class="all-india clone" onclick="checkAllDropDown('<?php echo $id; ?>_AllIndia');" value = "-1"
                          name="<?php echo $id; ?>_AllIndia"
                          <?php if ($AllIndiaDefault ==1) {
                            echo "checked";
                          }
                          ?>

                          />
                          <label for="<?php echo $id; ?>_AllIndia" class="clone">All India</label>
                      </div> 


                      <div class="Customcheckbox2">
                          <input type="checkbox" 
                          isVirtualCity="1" 
                          id="<?php echo $id; ?>_Metropolitian" 
                          class="parentCity parentCity_<?=$criteriaNo;?> clone" 
                          onclick=" checkedParams('<?php echo $id; ?>_Metropolitian'); checkMetroSub(this);"
                          <?php if ($AllIndiaDefault ==1) {
                            echo "checked";
                          }
                          ?> />
                          <label for="<?php echo $id; ?>_Metropolitian" class="clone">Metropolitian Cities</label>
                      </div> 

                      <div class="l-col" id="<?php echo $id; ?>_Metropolitian_Drop">
                          <ul class="">

                          <?php 
                          $virtualCityIds = array();
                          foreach($virtualCities as $list) { 
                              $virtualCityIds[] = $list['cityId'];
                              ?>

                              <li>
                                  <div class="Customcheckbox2">
                                      <input 
                                      type="checkbox" 
                                      parentId="<?php echo $id; ?>_Metropolitian" 
                                      class="subCities subCities_<?=$criteriaNo;?> cmsMetroCities_<?=$criteriaNo;?> clone" 
                                      id="<?php echo $id."_".$list['cityId']."_metro"?>" 
                                      value="<?php echo $list['cityId']; ?>" 
                                      isVirtualCity="1" 
                                      needLocality="1" 
                                      originalState = "<?php echo $id."_".$list['cityId']."_city"?>"
                                      currentLocCitiesName="<?php echo $list['cityName']; ?>" 
                                      onclick= "checkOriginalState(this); checkedParentParams('<?php echo $id; ?>_Metropolitian')" 
                                      isChildren ="1"
                                      <?php 
                                      if ($locationids[$list['cityId']]){
                                        echo "checked";
                                        $parentCity[] = $id."_Metropolitian";
                                      }
                                      if ($AllIndiaDefault ==1) {
                                        echo "checked";
                                      }
                          

                                      ?>
                                      name="<?php echo $id?>"
                                      />
                                      <label for="<?php echo $id."_".$list['cityId']."_metro"?>" class="clone"><?php echo $list['cityName']; ?></label>
                                  </div> 
                              </li>

                            <?php } ?>

                          </ul>
                      </div>

                  </li>


                  <?php
                  foreach($stateCities as $stateCity) { 
                      $stateId = $stateCity['StateId'];
                  ?>

                      <li>
                          <div class="Customcheckbox2">
                              <input type="checkbox" isVirtualCity="0" id="<?php echo $id."_".$stateId.'_' ?>" class="parentCity parentCity_<?=$criteriaNo;?> clone" onclick="checkedParams('<?php echo $id."_".$stateId.'_' ?>'); checkStateSub(this);" 

                              <?php if ($AllIndiaDefault ==1) {
                            echo "checked";
                          }
                          ?>
                          />
                              <label for="<?php echo $id."_".$stateId.'_' ?>" class="clone"><?php echo $stateCity['StateName'];?></label>
                          </div> 

                          <div class="l-col clone" id="<?php echo $id."_".$stateId.'_' ?>_Drop">
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
                                          <input 
                                          type="checkbox" 
                                          parentId="<?php echo $id."_".$stateId.'_' ?>" 
                                          class="subCities subCities_<?=$criteriaNo;?> <?php echo $stateId.'_'.$criteriaNo;?> clone" 
                                          id="<?php echo $id."_".$cities['CityId']."_city"?>" 
                                          isVirtualCity="0" 
                                          metroCity = "<?php echo $id."_".$cities['CityId']."_metro"?>"
                                          needLocality="<?php echo $needLocality;?>" 
                                          value="<?php echo $cities['CityId']; ?>" 
                                          currentLocCitiesName="<?php echo $cities['CityName']; ?>" isChildren ="1" 
                                          onclick= "checkMetroCity(this); checkedParentParams('<?php echo $id."_".$stateId.'_' ?>');"

                                      <?php 
                                        if ($locationids[$cities['CityId']]){
                                        echo "checked";
                                        $parentCity[] = $id."_".$stateId.'_';
                                      }

                                     if ($AllIndiaDefault ==1) {
                                       echo "checked";
                                    }
                          

                                      ?>
                                      name="<?php echo $id ?>"
                                          />
                                          <label for="<?php echo $id."_".$cities['CityId']."_city"?>" class="clone"><?php echo $cities['CityName']; ?></label>
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

<div stateBlock="stateBlock" style="display: none">
  <?php 
    echo implode(",", array_unique($parentCity));
  ?>
</div>
