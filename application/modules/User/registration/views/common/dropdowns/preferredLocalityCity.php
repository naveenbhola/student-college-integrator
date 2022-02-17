<option value="">Select City</option>
<?php
$values = $fields['preferredStudyLocality']->getValues(array('cities' => TRUE));
$virtualCities = $values['virtualCities'];

foreach ($values['virtualCities'] as $virtualCity) {
    echo '<optgroup label="'.$virtualCity['name'].'">';
    foreach ($virtualCity['cities'] as $city) {
        if ($city['virtualCityId'] != $city['city_id']) {
            echo '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
    }
    echo '</optgroup>';
}

echo '<optgroup label="Metro Cities">';
foreach ($values['metroCities'] as $city) {
    echo '<option value="'.$city['cityId'].'">'.$city['cityName'].'</option>';
}
echo '</optgroup>';

foreach ($values['stateCities'] as $stateCitiesData) {
    echo '<optgroup label="'.$stateCitiesData['StateName'].'">';
    foreach ($stateCitiesData['cityMap'] as $city) {
        echo '<option value="'.$city['CityId'].'">'.$city['CityName'].'</option>';
    }
    echo '</optgroup>';
}
?>