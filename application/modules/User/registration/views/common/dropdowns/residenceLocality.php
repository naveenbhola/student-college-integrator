<option value=""><?php if($defaultOptionText) echo $defaultOptionText; else echo 'Current City'; ?></option>
<?php
$params = array('cities' => TRUE);
if($removeVirtualCities == 'yes'){
    $params['removeVirtualCities'] = 'yes';
}
$values = $fields['residenceCityLocality']->getValues($params);
$virtualCities = $values['virtualCities'];

foreach ($values['virtualCities'] as $virtualCity) {
    echo '<optgroup label="'.$virtualCity['name'].'">';
    foreach ($virtualCity['cities'] as $city) {
        if ($city['virtualCityId'] != $city['city_id']) {
            $selected = '';
            if (($residenceCityLocality == $city['city_id']) && ($mmpFormId)) {
                $selected = 'selected = "selected"';
            }
            echo '<option '.$selected.' value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
    }
    echo '</optgroup>';
}

if($removeVirtualCities == 'yes'){
    echo '<optgroup label="Popular Cities">';
}else{
    echo '<optgroup label="Metro Cities">';
}

foreach ($values['metroCities'] as $city) {
    $selected = '';
    if (($residenceCityLocality == $city['cityId']) && ($mmpFormId)) {
        $selected = 'selected = "selected"';
    }
    echo '<option '.$selected.' value="'.$city['cityId'].'">'.$city['cityName'].'</option>';
}
echo '</optgroup>';

foreach ($values['stateCities'] as $stateCitiesData) {
    echo '<optgroup label="'.$stateCitiesData['StateName'].'">';
    foreach ($stateCitiesData['cityMap'] as $city) {
        $selected = '';
        if (($residenceCityLocality == $city['CityId']) && ($mmpFormId)) {
            $selected = 'selected = "selected"';
        }

        if(($residenceCityLocality == $city['CityId']) && ($isUnifiedProfile == 'YES')){
            $selected = 'selected = "selected"';
        }

        echo '<option '.$selected.' value="'.$city['CityId'].'">'.$city['CityName'].'</option>';
    }
    echo '</optgroup>';
}
?>