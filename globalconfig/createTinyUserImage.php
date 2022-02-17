<?php
mysql_connect("localhost", "shiksha", "shiKm7Iv80l") OR DIE (mysql_error());
mysql_select_db ("shiksha") OR DIE ("Unable to select db".mysql_error());
$sql = "select DISTINCT avtarimageurl from tuser";
$result = mysql_query($sql) OR die(mysql_error()); 
while ($row = mysql_fetch_assoc($result)) {
  $imageLink = $row['avtarimageurl'];
  if(strpos($imageLink,"http://")==0)
  {
	$ipaddressPos = strpos(substr($imageLink,7,strlen($imageLink)),"/");
	$imageLink = substr($imageLink,$ipaddressPos+7+1,strlen($imageLink));
  }
  createThumbnail($imageLink);
}
mysql_free_result($result);
echo "DONE!";

function createThumbnail($imageLink)
{
  if(file_exists($imageLink)){
	$imageString = substr($imageLink,0,(strrpos($imageLink,'.')));
	$imageExt = substr($imageLink,(strrpos($imageLink,'.')+1),strlen($imageLink));
	$thumburl_location = $imageString.'_t.'.$imageExt;
	$thumb2=make_thumb($imageLink,$thumburl_location,36,32,"image/".$imageExt);
  }
}

//API to create thumb for image
function make_thumb($img_name,$filename,$new_w,$new_h,$ext)
{
	//creates the new image using the appropriate function from gd library
	if(!strcmp("image/jpg",$ext) || !strcmp("image/jpeg",$ext))
		$src_img=imagecreatefromjpeg($img_name);
	if(!strcmp("image/png",$ext))
		$src_img=imagecreatefrompng($img_name);
	if(!strcmp("image/gif",$ext))
		$src_img=imagecreatefromgif($img_name);

	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	// next we will calculate the new dimmensions for the thumbnail image
	// 	1. calculate the ratio by dividing the old dimmensions with the new ones
	//	2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable
	//		and the height will be calculated so the image ratio will not change
	//	3. otherwise we will use the height ratio for the image
	// as a result, only one of the dimmensions will be from the fixed ones
	$ratio1=$old_x/$new_w;
	$ratio2=$old_y/$new_h;
	$thumb_w = $new_w;
	$thumb_h = $new_h;
	if($new_w == 0)
		$thumb_w = $old_x/$ratio2;
	if($new_h == 0)
		$thumb_h = $old_y/$ratio1;

	if($new_w != 0 && $new_h != 0)
	{	
		if($ratio1>$ratio2)	{
			$thumb_w=$new_w;
			$thumb_h=$old_y/$ratio1;
		}
		else	{
			$thumb_h=$new_h;
			$thumb_w=$old_x/$ratio2;
		}
	}

	// we create a new image with the new dimmensions
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

	// resize the big image to the new created one
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 

	// output the created image to the file. Now we will have the thumbnail into the file named by $filename
	if(!strcmp("image/png",$ext))
		imagepng($dst_img,$filename);
	if(!strcmp("image/gif",$ext))
		imagegif($dst_img,$filename);
	else
		imagejpeg($dst_img,$filename);
	//destroys source and destination images. 
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

?>
