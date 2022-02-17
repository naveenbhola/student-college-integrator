<?php
error_log("LKJ123 ".print_r($userData,true));
    echo "<br/>";
    if(trim($userData['name']) != "") 
    echo "<b>Name</b>: ".$userData['name'];
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
    if(trim($userData['fieldOfInterestName']) != "") 
    echo "<br/><b>Field of Interest</b>: ".$userData['fieldOfInterestName'];
    if(trim($userData['desiredCourseName']) != "") 
    echo "<br/><b>Desired Course</b>: ".$userData['desiredCourseName'];
    if(trim($userData['prefferedStudyLoc']) != "") 
    echo "<br/><b>Preferred Study Location(s)</b>: <br/>". trim(str_replace(',',', ',$userData['prefferedStudyLoc']),', ') .'.';
?>
