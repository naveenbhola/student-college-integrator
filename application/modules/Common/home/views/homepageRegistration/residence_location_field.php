<div class="find-field-row">
<select class="resLocationAbrd" id = "cities_studyAbroad" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence" onblur="validateStudyAbroadCity();">
    	<option value=""><b>Residence Location</b></option>
                                                <?php
                                                    $optionSelectedStr = '';
                                                    if ( isset($userData[0]['city']) ) {
                                                        $userSelectedCity = $userData[0]['city'];
                                                    }
                                                    foreach($cityTier1 as $list) {
                                                        if ($userSelectedCity == $list['cityId']) {
                                                            $optionSelectedStr = "selected";
                                                        } else { 
                                                            $optionSelectedStr = ''; 
                                                        }
                                                ?>
                                                        <option <?php echo $optionSelectedStr; ?> value="<?php echo $list['cityId']; ?>"><?php echo $list['cityName'];?></option>
                                                <?php
                                                    }
                                                    $optionSelectedStr = '';
                                                ?>
                                                <?php
                                                foreach($country_state_city_list as $list) {
                                                    if($list['CountryId'] == 2) {
                                                        foreach($list['stateMap'] as $list2) {
                                                            echo '<OPTGROUP LABEL="'.$list2['StateName'].'">';
                                                            foreach($list2['cityMap'] as $list3) {
                                                                if ($userSelectedCity == $list3['CityId']) {
                                                                    $optionSelectedStr = "selected";
                                                                } else { $optionSelectedStr = ''; }
                                                                ?>
                                                                    <option <?php echo $optionSelectedStr; ?> value="<?php echo $list3['CityId']; ?>"><?php echo $list3['CityName'];?></option>
                                                                    <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                        </select>
                                        
<div class="errorPlace" style="display:none;">
	<div class="errorMsg" id= "cities_studyAbroad_error"></div>
</div>
</div>
