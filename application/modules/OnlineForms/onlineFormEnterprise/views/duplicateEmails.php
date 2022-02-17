<?php
if(!empty($email)){
    for($i=0;$i<count($email);$i++){
        echo ($i+1).'. '.$email[$i].'<br/>';
    }
}else{
    echo "No Duplicate Entries.";
}
?>