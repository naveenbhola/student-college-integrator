<?php
$id = $_POST['id'];
$type = $_POST['type'];

mysql_connect("10.208.65.32", "shiksha", "shiKm7Iv80l") or
    die("Could not connect: " . mysql_error());
			// select the db
			mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());

$sql = mysql_query("Select url,thumburl,description from 
tImageData id inner join 
tMediaMapping mp ON(id.mediaid = mp.mediaid)
where mp.typeid = $id and mp.mediatype = 'image' and mp.type = '$type'");

$iCount = 0;
while($row = mysql_fetch_array($sql))
{
	$arrayofrecords[$iCount]['url'] = $row[0];
	$arrayofrecords[$iCount]['thumburl'] = $row[1];
	$arrayofrecords[$iCount]['description'] = $row[2];
	$iCount = $iCount + 1;
}
//$arrayofrecords['count'] = $iCount;
echo serialize($arrayofrecords);
?>
