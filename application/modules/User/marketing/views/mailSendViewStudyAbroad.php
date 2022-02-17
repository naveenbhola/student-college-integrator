<?php
error_log("LKJ123 Shirish789".print_r($userData,true));
echo "<br/>";
if(trim($userData['fieldOfInterestName']) != "") 
	echo "<br/><b>Field of Interest</b>: ".$userData['fieldOfInterestName'];
if(trim($userData['countryOfInterest']) != "") 
	echo "<br/><b>Country(s) of Interest</b>: ".$userData['countryOfInterest'];
if(trim($userData['courseStartTime']) != "") 
	echo "<br/><b>Planning to Start</b>: ".$userData['courseStartTime'];
if(trim($userData['modeoffinance']) != "") 
	echo "<br/><b>Sources of Funding</b>: ".$userData['modeoffinance'];
if(trim($userData['nearestMetroCity']) != "") 
	echo "<br/><b>Nearest Metropolitan City</b>: ".$userData['nearestMetroCity'];
if(trim($userData['name']) != "") 
	echo "<br/><b>Name</b>: ".$userData['name'];
if(trim($userData['mobile']) != "") 
	echo "<br/><b>Mobile</b>: ".$userData['mobile'];
if(trim($userData['email']) != "") 
	echo "<br/><b>Email</b>: ".$userData['email'];
if(trim($userData['residenceLocationName']) != "") 
	echo "<br/><b>Residence Location</b>: ".$userData['residenceLocationName'];
if(trim($userData['age']) != "") 
	echo "<br/><b>Age</b>: ".$userData['age'];
if(trim($userData['gender']) != "") 
	echo "<br/><b>Gender</b>: ".$userData['gender'];
if(trim($userData['highestQualificationName']) != "") 
	echo "<br/><b>Highest Qualification</b>: ".$userData['highestQualificationName'];
?>
