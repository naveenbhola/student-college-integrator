<?php 
error_reporting(E_ALL);
include('/var/www/html/shiksha/mediadata/Saveimage.php');

/*
Copyright 2007 Info Edge India Ltd

$Rev:: 139           $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-01-17 05:30:07 $:  Date of last commit

$Id: UploadinServer.php,v 1.1.1.1 2008-01-17 05:30:07 amitj Exp $: 

*/

$Datatype = $_POST['mediatype'];
$iCount = $_POST['count'];
$id = $_POST['Id'];
$type = $_POST['type'];
$i = 1;
$arrayofdescription = array();
while($i < $iCount)
{
$arrayofdescription['description'.$i] = $_POST['description'.$i];
$i = $i + 1;
}

			switch($Datatype)
				{
				/*case "audio":
					insertaudio($type,$size,$description,$name,$name_id);
					break;
				case "video":
					insertvideo($type,$size,$description,$name,$name_id);
					break;*/
				case "image":
					$iFlag = insertimage($arrayofdescription,$iCount,$id,$type);
					break;	
				/*case "text":
					inserttext($type,$size,$description,$name,$name_id);
					break;	
				case "pdf":
					insertpdf($type,$size,$description,$name,$name_id);
					break;*/
				}
if(isset($iFlag))
{
$returnarray = array("status"=>0,"error_msg"=>$iFlag);
echo serialize($returnarray);
}
?>
