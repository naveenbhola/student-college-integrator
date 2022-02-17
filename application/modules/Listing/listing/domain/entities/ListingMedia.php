<?php

class ListingMedia
{
	private $media_type;
	private $name;
	private $url;
	private $thumburl;
	private $caption;
	// private $institute_location_id;


	function __construct()
	{

	}
	
	// public function getInstituteLocationId() {
	// 	return $this->institute_location_id;
	// }
	
	public function getType()
	{
		return $this->media_type;
	}

	public function getURL()
	{
		if($this->media_type != 'video'){
			if(!empty($this->url)){
				return MEDIAHOSTURL.$this->url;
			}	
		}
		return $this->url;
	}

	public function getName()
	{
                if($this->media_type == 'video' || !strrpos($this->name,'.')) {
                        return $this->name;
                }
		return substr($this->name, 0,strrpos($this->name,'.'));
	}

	public function getThumbURL($size = "medium")
	{
		 //echo "vinay ".$size;
		if($this->media_type != 'video'){
			if(!empty($this->thumburl)){
				$thumburl = MEDIAHOSTURL.$this->thumburl;
			}else{
				$thumburl = $this->thumburl;
			}
		}else{
			$thumburl = $this->thumburl;
		}
		$tempURL =  str_replace("_s.","_m.",$thumburl);
		if($size == "medium"){
			return str_replace("_s.","_m.",$thumburl);
		}elseif($size == "large"){
			return str_replace("_s.","_l.",$thumburl);
		}elseif($size == "300x200"){
			return str_replace("_m.","_300x200.",$tempURL);
		}elseif($size == "172x115"){
			return str_replace("_m.","_172x115.",$tempURL);
		}elseif($size == "75x50"){
			return str_replace("_m.","_75x50.",$tempURL);
		}elseif($size == "135x90"){
			return str_replace("_m.","_135x90.",$tempURLl);
		}else{
			return $thumburl;
		}	
	}

	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	function getCaption()
	{
		return $this->caption;
	}
}
