
You chose
<?php
$institutes_count = 1;
foreach($InstituteList as $Institutes){
    if($institutes_count == 1){
    echo " ".$Institutes['name'].", ".$Institutes['location'];
    } else{
        echo " and ".$Institutes['name'].", ".$Institutes['location'];
    }
    $institutes_count++;
}
?>

.<br>
<strong>Apply directly</strong> to these institute(s) right here. You can escape the queue and track your submission online.