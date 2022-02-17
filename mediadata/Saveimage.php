<?php
error_reporting(E_ALL);
/*
Copyright 2007 Info Edge India Ltd

$Rev:: 138           $:  Revision of last commit
$Author: nehac $:  Author of last commit
$Date: 2008-03-25 05:00:54 $:  Date of last commit

Saves the file to the server

$Id: Saveimage.php,v 1.2 2008-03-25 05:00:54 nehac Exp $: 

*/

define ("MAX_SIZE","100"); 
// width and height for the thumbnail
define ("WIDTH","150"); 
define ("HEIGHT","100"); 
define("MAX_IMAGE_SIZE","2000000");
$url = 'http://'.$_SERVER['SERVER_NAME'];
$url .= ($_SERVER['SERVER_PORT']!='') ? ':'. $_SERVER['SERVER_PORT'] : '';
define("URL", $url);
define("MEDIA_BASE_PATH", "/var/www/html/shiksha/static_mediadata");


function checktype($iCount)
{
	$iFlag = 1;
	while($iFlag < $iCount)
	{
		$size = getimagesize($_FILES['file'.$iFlag]['tmp_name']);
		$type = $size['mime'];
		if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png"))
		return 0;
		else
		return 1;
	}
}

function checksize($iCount)
{
	$iFlag = 1;
	while($iFlag < $iCount)
	{
		$size = $_FILES['file'.$iFlag]['size'];
		if($size > MAX_IMAGE_SIZE)
		return 0;
		else
		return 1;
	}
}

function insertimage($arrayofdescription,$iCount,$id,$typeofmedia)
{
if(!checktype($iCount))
	return("type not jpeg or gif or png");
	if(!checksize($iCount))
	return("size limit of 2MB exceeded");
	$ImagesPath = MEDIA_BASE_PATH."/images";
	
	//Check for directory in Server
	if(!is_dir($ImagesPath))
		mkdir($ImagesPath,0777);
	        chmod($ImagesPath,0777);
	if(!is_dir($ImagesPath."/thumbs"))
		mkdir($ImagesPath."/thumbs",0777);
	        chmod($ImagesPath."/thumbs",0777);

	//Set the target locations for the files and move them
	$iSuccess = 1;
	$iFlag = 1;
	$mediaid = 0;
	$date = date("y.m.d");
	
	while($iFlag < $iCount)
	{
		//Set the values		
		$name_id = time().basename($_FILES['file'.$iFlag]['tmp_name']);		
		$target_location = $ImagesPath."/".$name_id;
		$imageurl = URL ."/mediadata/images/".$name_id;
		$media= getimagesize($_FILES['file'.$iFlag]['tmp_name']);
		$type = $media['mime'];
		$size = $_FILES['file'.$iFlag]['size'];
		$name = $_FILES['file'.$iFlag]['name'];
		$thumburl = URL."/mediadata/images/thumbs/thumb_".$name_id;
		$thumburl_location = $ImagesPath.'/thumbs/thumb_'.$name_id;
		$description = $arrayofdescription['description'.$iFlag];
		

		if(!(move_uploaded_file($_FILES['file'.$iFlag]['tmp_name'],$target_location)))
		{		
			
			if($_FILES['uploadedFile']['error'] > 0)
			{
			switch($_FILES['uploadFile'] ['error'])
			{
				case 1: echo "File exceeded maximum server upload size";
					break;
				case 2: echo "File exceeded maximum file size";
					break;
				case 3: echo "File only partially uploaded";
					break;
				case 4: echo "File not uploaded";
					break;
			}
			}				
			$iSuccess = 0;
			break;
		}
		else
		{		
						
			$conn = new mysqli("10.208.65.32", "shiksha", "shiKm7Iv80l", "shiksha");			
			// $con = mysqli_connect("10.208.65.32", "shiksha", "shiKm7Iv80l", "shiksha");
			// if (mysqli_connect_errno())
			// {
			//  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
			//   	die;
			// }
			// select the db
			// mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());
			//sql query


			if ($conn->connect_error) {
		    	die("Connection failed: " . $conn->connect_error);
			}

			$sql = "INSERT INTO tImageData ( mediaid , type ,size, 		name,url,description,uploadeddate,thumburl)
				       VALUES
				       (?,?,?,?,?,?,?,?)";

			$stmt  = $conn->prepare($sql);
			$stmt->bind_param('isssssss',$mediaid,$type,$size,$name,$imageurl,$description,$date,$thumburl);
			$mediaid="";
			$stmt->execute();
			if($stmt->error) 
			{
			//Delete the saved file		  
				unlink($target_location);
				return("file uploading failed");
			}

			$mediaid = $stmt->insert_id;
			
			$sql = "INSERT INTO tMediaMapping(typeid,mediaid,type)values(?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("iis",$id,$mediaid,$typeofmedia);
			$stmt->execute();
			
			if($stmt->error) 
			{
				unlink($target_location);
				$delsql = "DELETE from tImageData where mediaid = ?";
				$delStmt = $conn->prepare($delsql);
				$delStmt->bind_param("i",$mediaid);
				$delStmt->execute();
				return("file uploading failed");
			}
						
			 // call the function that will create the thumbnail. The function will get as parameters 
			 //the image name, the thumbnail name and the width and height desired for the thumbnail
        	$thumb=make_thumb($target_location,$thumburl_location,100,100,$type,$mediaid);
  		 	$returnarray['mediaid'.$iFlag] = $mediaid;
	 		$returnarray['imageurl'.$iFlag]= $imageurl;
	 		$returnarray['thumburl'.$iFlag]= $thumburl;
	
		}
		$iFlag = $iFlag + 1;
	}
	if(!$iSuccess)
	return("file uploading failed.Please try again");
	else
	$returnarray["status"] = 1;	
	echo serialize($returnarray);
	
}

function make_thumb($img_name,$filename,$new_w,$new_h,$ext)
{
//creates the new image using the appropriate function from gd library
//$ext ="jpg";

 	if(!strcmp("image/jpg",$ext) || !strcmp("image/jpeg",$ext))
 		$src_img=imagecreatefromjpeg($img_name);


//echo $src_img;
  	if(!strcmp("image/png",$ext))
 		$src_img=imagecreatefrompng($img_name);
	if(!strcmp("image/gif",$ext))
 		$src_img=imagecreatefromgif($img_name);

 	//gets the dimmensions of the image
 	$old_x=imageSX($src_img);
 	$old_y=imageSY($src_img);

 	// next we will calculate the new dimmensions for the thumbnail image
 	// the next steps will be taken: 
 	// 	1. calculate the ratio by dividing the old dimmensions with the new ones
 	//	2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable
 	//		and the height will be calculated so the image ratio will not change
 	//	3. otherwise we will use the height ratio for the image
 	// as a result, only one of the dimmensions will be from the fixed ones
 	$ratio1=$old_x/$new_w;
 	$ratio2=$old_y/$new_h;
 	if($ratio1>$ratio2)	{
 		$thumb_w=$new_w;
 		$thumb_h=$old_y/$ratio1;
 	}
 	else	{
 		$thumb_h=$new_h;
 		$thumb_w=$old_x/$ratio2;
 	}

  	// we create a new image with the new dimmensions
 	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

 	// resize the big image to the new created one
 	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 

 	// output the created image to the file. Now we will have the thumbnail into the file named by $filename
 	if(!strcmp("image/png",$ext))
	{
	
	if(!imagepng($dst_img,$filename))
		{
		
	unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
		}
	}
 	if(!strcmp("image/gif",$ext))
	{
 		
if(!imagegif($dst_img,$filename))
		{
			
unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
		}
	}
 	else
	{ 		
		if(!imagejpeg($dst_img,$filename))
		{
			unlink($img_name);
			$sql = "DELETE from tImageData where mediaid = $mediaid";
			mysqli_query($con, $sql);
			$sql = "DELETE from tMediaMapping where mediaid = $mediaid";
			mysqli_query($con, $sql);
			//return("thumburl creation failed");
		}
	}
  	//destroys source and destination images. 
 	imagedestroy($dst_img); 
 	imagedestroy($src_img); 
}
?>
