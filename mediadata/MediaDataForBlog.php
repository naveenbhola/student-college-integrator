<?php


/*
Copyright 2007 Info Edge India Ltd

$Rev:: 140           $:  Revision of last commit
$Author: nehac $:  Author of last commit
$Date: 2008-03-25 05:00:14 $:  Date of last commit

Retrieves the URL for the image for a particular blog id
$Id: MediaDataForBlog.php,v 1.2 2008-03-25 05:00:14 nehac Exp $: 

*/

error_reporting(E_ALL);

if((strpos($_SERVER['SERVER_NAME'], '172.16.3.247') !== false) || (strpos($_SERVER['SERVER_NAME'], 'shikshatest02.infoedge.com') !== false) || (strpos($_SERVER['SERVER_NAME'], 'shikshatest01.infoedge.com') !== false) || (strpos($_SERVER['SERVER_NAME'], '172.16.3.248') !== false))
{
	$con = mysqli_connect("localhost", "shiksha", "shiKm7Iv80l", "shiksha");
}
else
{
	$con = mysqli_connect("10.208.65.32", "shiksha", "shiKm7Iv80l", "shiksha");
}

if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$type = mysqli_real_escape_string($con,$_POST['type']);
$id = mysqli_real_escape_string($con,$_POST['id']);
// select the db


$sql = mysqli_query($con, "Select url,thumburl,description from 
tImageData mn inner join 
tMediaMapping mp ON(mn.mediaid = mp.mediaid)
where mp.typeid = $id and mp.type = '$type'");

$iCount = 0;
while($row = mysqli_fetch_array($sql))
{
	$arrayofrecords['url'.$iCount] = $row[0];
	$arrayofrecords['thumburl'.$iCount] = $row[1];
	$arrayofrecords['description'.$iCount] = $row[2];
	$iCount = $iCount + 1;
}

$arrayofrecords['count'] = $iCount;
echo serialize($arrayofrecords);
?>
