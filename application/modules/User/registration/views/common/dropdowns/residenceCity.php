<option value=""><?php if($defaultOptionText) echo $defaultOptionText; else echo 'Select'; ?></option>
<?php

if($isNational){
    $values = $fields['residenceCity']->getValues(array('isNational' => $isNational));
    $virtualCities = $values['virtualCities'];
    foreach ($values['virtualCities'] as $virtualCity) {
        echo '<optgroup label="'.$virtualCity['name'].'">';
        foreach ($virtualCity['cities'] as $city) {
            if ($city['virtualCityId'] != $city['city_id']) {
                $selected = '';
                if (($residenceCity == $city['city_id']) && ($mmpFormId)) {
                    $selected = 'selected = "selected"';
                }
                echo '<option '.$selected.' value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
            }
        }
        echo '</optgroup>';
    }

    echo '<optgroup label="Metro Cities">';
    foreach ($values['metroCities'] as $city) {
        $selected = '';
        if (($residenceCity == $city['cityId']) && ($mmpFormId)) {
            $selected = 'selected = "selected"';
        }
        echo '<option '.$selected.' value="'.$city['cityId'].'">'.$city['cityName'].'</option>';
    }
    echo '</optgroup>';

    foreach ($values['stateCities'] as $stateCitiesData) {
        echo '<optgroup label="'.$stateCitiesData['StateName'].'">';
        foreach ($stateCitiesData['cityMap'] as $city) {
            $selected = '';
            if (($residenceCity == $city['CityId']) && ($mmpFormId)) {
                $selected = 'selected = "selected"';
            }
            echo '<option '.$selected.' value="'.$city['CityId'].'">'.$city['CityName'].'</option>';
        }
        echo '</optgroup>';
    }
}
else{
    
    $residenceLocationValues = $fields['residenceCity']->getValues();
    $tier1Cities = $residenceLocationValues['tier1Cities'];
    $citiesByStates = $residenceLocationValues['citiesByStates'];
    $selected = '';

    if($order == 'alphabetical') {
        echo '<optgroup label="Metro Cities">';
    }
    $tier1Cities = array_filter(array_map(function($a){ if($a['stateId'] != -1){ return $a; } },$tier1Cities));
    usort($tier1Cities,function($a,$b){return $a['cityName'] > $b['cityName']; });
    foreach($tier1Cities as $city) {
    
        if ($formData['residenceCity'] == $city['cityId']) {
            $selected = 'selected = "selected"';
        } else if (($formData['residenceCity'][0] == $city['cityId']) && ($mmpFormId)) {
            $selected = 'selected = "selected"';
        } else {
            $selected = "";
        }
    ?>
        <option <?php echo $selected; ?> value="<?php echo $city['cityId']; ?>"><?php echo $city['cityName'];?></option>
    <?php
    }

    if($order == 'alphabetical') {
        $cityList = array();
        foreach($citiesByStates as $list) {
            foreach($list['cityMap'] as $city) {
                $cityList[$city['CityId']] = $city['CityName'];
            }
        }
    
        asort($cityList);

        echo '<optgroup label="Other Cities">';

        foreach($cityList as $cityId => $cityName) {
            if ($formData['residenceCity'] == $cityId) {
                $selected = 'selected = "selected"';
            } else if (($formData['residenceCity'][0] == $cityId) && ($mmpFormId)) {
                $selected = 'selected = "selected"';
            } else {
                $selected = "";
            }
    ?>
            <option <?php echo $selected; ?> value="<?php echo $cityId; ?>"><?php echo $cityName;?></option>
    <?php
        }
    }
    else {
        foreach($citiesByStates as $list) {

            echo '<optgroup label="'.$list['StateName'].'">';

            foreach($list['cityMap'] as $city) {

                if ($formData['residenceCity'] == $city['CityId']) { 
                    $selected = 'selected = "selected"';
                }
                else {
                    $selected = '';
                }
    ?>
                <option <?php echo $selected; ?> value="<?php echo $city['CityId']; ?>"><?php echo $city['CityName'];?></option>
    <?php
            }
            echo '</optgroup>';
        }
    }
}
?>