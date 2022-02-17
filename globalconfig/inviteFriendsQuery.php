<?php
mysql_connect("localhost", "shiksha", "shiKm7Iv80l") OR DIE (mysql_error());
mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());
//Query to revert the points of user in userpointsystembymodule table
$sql = "Update userpointsystembymodule set userpointvaluebymodule = (userpointvaluebymodule - (SELECT sum(pointvalue) FROM `userpointsystemlog` WHERE action='inviteFriends' and userId=userpointsystembymodule.userid group by userId)) where modulename = 'InviteFriends'";
$result = mysql_query($sql) OR die(mysql_error()); 
//Query to revert the points of user in userPointSystem table
$sql = "Update userPointSystem set userPointValue = (userPointValue - ifnull((SELECT sum(pointvalue) FROM `userpointsystemlog` WHERE action='inviteFriends' and userId=userPointSystem.userid group by userId),0))";
$result = mysql_query($sql) OR die(mysql_error()); 
//Query to revert the points of user in userpointsystemlog table to 0
$sql = "Update userpointsystemlog set pointvalue = '0' where action = 'inviteFriends'";
$result = mysql_query($sql) OR die(mysql_error()); 
echo "DONE!";
?>

