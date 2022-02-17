<?php
foreach($cityGroup as $city) {
	echo "<option value='".$city['city_id']."'>".$city['city_name']."</option>";
}