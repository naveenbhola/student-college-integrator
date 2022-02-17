<?php
$id = $_POST['id'];
$type = $_POST['type'];


mysql_connect("10.208.65.32", "shiksha", "shiKm7Iv80l") or
    die("Could not connect: " . mysql_error());
			// select the db
			mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

$sql = mysql_query("Select url,thumburl,description,duration from 
tVideoData vd inner join 
tMediaMapping mp ON(vd.mediaid = mp.mediaid)
where mp.typeid = $id and mp.mediatype = 'video' and mp.type = '$type'");

$iCount = 0;
while($row = mysql_fetch_array($sql))
{
	$arrayofrecords[$iCount]['url'] = $row[0];
	$arrayofrecords[$iCount]['thumburl'] = $row[1];
	$arrayofrecords[$iCount]['description'] = $row[2];
	$arrayofrecords[$iCount]['duration'] = $row[3];
	$iCount = $iCount + 1;
}
//$arrayofrecords['count'] = $iCount;
echo serialize($arrayofrecords);
?>
