<?php 
file_put_contents($_POST['imgPath'], $_POST['data']);
$thumbs = array('_t'=>array(40,40), '_s'=>array(64,64), '_m'=>array(160,160));
foreach ($thumbs as $key => $value) {
	$urlParts = array();
	$urlParts = explode('.', $_POST['imgPath']);
	$urlParts[count($urlParts)-2] .= $key;
	$thumbName = implode('.', $urlParts);
	make_thumbnail($_POST['imgPath'], $thumbName, $value[0], $value[1], 'png');
}

function make_thumbnail($img_name, $filename, $new_w, $new_h, $ext){
	$src_img = imagecreatefrompng($img_name);
	
	$old_x = imageSX($src_img);
	$old_y = imageSY($src_img);

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
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	imagepng($dst_img,$filename);

	imagedestroy($dst_img);
	imagedestroy($src_img);
}
?>