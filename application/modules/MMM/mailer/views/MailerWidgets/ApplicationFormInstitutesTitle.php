<?php
$institutes_count = 1;
foreach($InstituteList as $Institutes){
    if($institutes_count == 1){
    echo $Institutes['name'];
    } else{
        echo ", ".$Institutes['name'];
    }
    $institutes_count++;
}
?>
