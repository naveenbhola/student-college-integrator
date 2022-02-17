<?php

class ProcessImage
{
	private $imagePath;
	private $imageOriginal;
	private $imageOriginalWidth;
	private $imageOriginalHeight;
	private $imageType;
	private $blurLevel = 100;
	private $modifiedImage;
	private $percentageAllowedForCrop = 10;

	function __construct($path = '')
	{
		if($path)
			$this->load($path);
	}

	function reset(){
		$this->imagePath = '';
		$this->imageOriginal = '';
		$this->imageOriginalWidth = 0;
		$this->imageOriginalHeight = 0;
		$this->imageType = '';
		$this->modifiedImage = '';
	}

	function load($path){

		if(empty($path)){
			return "Image Path can't be empty!";
		}

		$this->imagePath = $path;

		if(!list($this->imageOriginalWidth, $this->imageOriginalHeight) = getimagesize($path)) 
			return "Unsupported picture type!";

		$type = strtolower(substr(strrchr($path,"."),1));
		if($type == 'jpeg') $type = 'jpg';

		switch($type){
			case 'bmp': $this->imageOriginal = imagecreatefromwbmp($path); break;
			case 'gif': $this->imageOriginal = imagecreatefromgif($path); break;
			case 'jpg': $this->imageOriginal = imagecreatefromjpeg($path); break;
			case 'png': $this->imageOriginal = imagecreatefrompng($path); break;
			default : return "Unsupported picture type!";
		}

		$this->imageType = $type;

		return true;
	}

	/**
	 * Method to pad image with blurred version or middle crop it keeping the aspect ratio intact
	 * @author Romil Goel
	 * @date   2016-09-27
	 * @param  [type]     $width  [description]
	 * @param  [type]     $height [description]
	 * @return [type]             [description]
	 */
	function processImage($width, $height, $blurBackgroundFlag = 1){

		if(empty($height) || empty($width))
			return "Please specify proper dimensions of converted image";

		$h_ratio     = $height/$this->imageOriginalHeight;
		$w_ratio     = $width/$this->imageOriginalWidth;
		$ratio       = ($h_ratio < $w_ratio) ? $h_ratio : $w_ratio;
		$dest_height = $this->imageOriginalHeight*($ratio);
		$dest_width  = $this->imageOriginalWidth*($ratio);

		$dest_x = ceil(($width-$dest_width)/2);
		$dest_y = ceil(($height-$dest_height)/2);

		$blurOrCrop = 'crop';
		if($dest_x){
			$percentPaddingX = (($dest_x)/$width)*100;
		}
		if($dest_y){
			$percentPaddingY = (($dest_y)/$height)*100;
		}

		if($percentPaddingX > $percentPaddingY)
			$percentPadding = $percentPaddingX;
		else
			$percentPadding = $percentPaddingY;

		// echo "<br/>width : ".$width." and height : ".$height." and percentPadding : ".$percentPadding;
		if($percentPadding > $this->percentageAllowedForCrop){
			$blurOrCrop = 'blur';
		}

		// dimensions of image that is cropped version of original image in respect of output image
		$cropRatio = $h_ratio>$w_ratio ? $h_ratio : $w_ratio;
		$croppedVersionWidth  = $width * (1/$cropRatio);
		$croppedVersionHeight = $height * (1/$cropRatio);
		$croppedVersion_x = ($this->imageOriginalWidth-$croppedVersionWidth)/2;
		$croppedVersion_y = ($this->imageOriginalHeight-$croppedVersionHeight)/2;

  		$this->modifiedImage = imagecreatetruecolor($width, $height);

		if($this->imageType == "gif" || $this->imageType == "png"){
			imagecolortransparent($this->modifiedImage, imagecolorallocatealpha($this->modifiedImage, 255, 255, 255, 127));
			imagealphablending($this->modifiedImage, false);
			imagesavealpha($this->modifiedImage, true);
		}
		else{
			$clear = imagecolorallocate( $this->modifiedImage, 255, 255, 255);
			imagefill($this->modifiedImage, 0, 0, $clear);
		}

  		if($blurBackgroundFlag && ($dest_x || $dest_y)){

			$backgroundBlurImage = imagecreatetruecolor($width, $height);
		  	imagecopyresampled($backgroundBlurImage, $this->imageOriginal, 0, 0, $croppedVersion_x, $croppedVersion_y, ($width), ($height), $croppedVersionWidth, $croppedVersionHeight);
		  	for ($i = 0; $i < $this->blurLevel; ++$i) {
			    imagefilter($backgroundBlurImage, IMG_FILTER_GAUSSIAN_BLUR);
		  	}
		}

		if($blurOrCrop == 'blur' && $blurBackgroundFlag){
			// for blurred image background
			if($backgroundBlurImage)
		  		imagecopyresampled($this->modifiedImage, $backgroundBlurImage,  0, 0, 0, 0, $width, $height, $width, $height);

		  	// for white background
		 	// $clear = imagecolorallocate( $new, 255, 255, 255);
			// imagefill($new, 0, 0, $clear);
			
			// put original image in the canvas
		  	imagecopyresampled($this->modifiedImage, $this->imageOriginal, $dest_x, $dest_y, 0, 0, $dest_width, $dest_height, $this->imageOriginalWidth, $this->imageOriginalHeight);
		}
		else{
			imagecopyresampled($this->modifiedImage, $this->imageOriginal, 0, 0, $croppedVersion_x, $croppedVersion_y, $width, $height, $croppedVersionWidth, $croppedVersionHeight);

		}
	}

	function scaleImage($upperWidth, $upperHeight){

		$tmpSize = getimagesize($this->imagePath);
        list($width, $height, $type, $attr) = $tmpSize;
		
		$scaledWidth = $upperWidth;
		$scaledHeight = $upperHeight;

        if($width > 640){
			$aspectRatio = $width/$height;
			$scaledWidth = 640;
			$scaledHeight = floor($scaledWidth / $aspectRatio);
			$this->processImage($scaledWidth, $scaledHeight, 0);
		}
		else if($height > 480){
			$aspectRatio = $width/$height;
			$scaledHeight = 480;
			$scaledWidth = floor($scaledHeight * $aspectRatio);
			$this->processImage($scaledWidth, $scaledHeight, 0);
		}
		else{
			$this->processImage($width, $height, 0);
			// $this->modifiedImage = $this->imageOriginal;
		}
	}

	function output($outputImagePath){

		switch($this->imageType){
			case 'bmp': imagewbmp($this->modifiedImage, $outputImagePath); break;
			case 'gif': imagegif($this->modifiedImage, $outputImagePath); break;
			case 'jpg': imagejpeg($this->modifiedImage, $outputImagePath); break;
			case 'png': imagepng($this->modifiedImage, $outputImagePath); break;
		}
	}

	function getImageType(){
		return $this->imageType;
	}
}
?>