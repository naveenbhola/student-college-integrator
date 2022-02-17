<?php 
include('SaveMediaData.php');
$Datatype = $_POST['mediatype'];
$iCount = $_POST['count'];
$id = $_POST['Id'];
$type = $_POST['type'];
$i = 1;
$arrayofdescription = array();
//echo $_POST['Description1'];
if($Datatype != "ytvideo")
{
	while($i < $iCount)
	{
		$arrayofdescription['description'.$i] = $_POST['description'.$i];
		$arrayofdescription['type'.$i] = $_POST['type'.$i];
		$arrayofdescription['name'.$i] = $_POST['name'.$i];
		$i = $i + 1;
	}
}
else
{
	while($i < $iCount)
	{
		$arrayofurls['url'.$i] = $_POST['file'.$i];
		$arrayofurls['description'.$i] = $_POST['description'.$i];
		$arrayofurls['name'.$i] = $_POST['name'.$i];
		$i = $i + 1;
	}
}
			switch($Datatype)
				{
				case "audio":
					$iFlag = insertaudio($arrayofdescription,$iCount,$id,$type);
					break;
				case "video":
					$iFlag = insertvideo($arrayofdescription,$iCount,$id,$type);
					break;
				case "image":
					$iFlag = insertimage($arrayofdescription,$iCount,$id,$type);
					break;	
				case "pdf":
				case "email":
				        $iFlag = insertdocument($arrayofdescription,$iCount,$id,$type);
					break;

				case "ytvideo":
				        $iFlag = insertyoutubedata($arrayofurls,$iCount,$id,$type);
					break;
				case "recording":
					$iFlag = insertrecording($arrayofurls,$iCount,$id,$type);
					break;

				case "spliceMedia":
					$iFlag = insertSpliceMedia($arrayofdescription,$iCount,$id,$type);
					break;
				case "saApplyMedia":
					$iFlag = insertSpliceMedia($arrayofdescription,$iCount,$id,$type,'shikshaApply');
					break;	
				}
if(isset($iFlag))
{
$returnarray = array("status"=>0,"error_msg"=>$iFlag);
echo serialize($returnarray);
}
?>
