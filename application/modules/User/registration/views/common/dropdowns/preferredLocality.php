<?php
foreach($localities as $zoneId => $localitiesInZone) {
    $firstLocality = reset($localitiesInZone);
    echo "<optgroup label='".$firstLocality['zoneName']."'>";
    foreach($localitiesInZone as $locality) {
        echo "<option value='".$locality['localityId']."'>".$locality['localityName']."</option>";
    }
    echo "</optgroup>";
}