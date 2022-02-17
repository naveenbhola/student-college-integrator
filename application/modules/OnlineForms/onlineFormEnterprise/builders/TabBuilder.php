<?php
namespace onlineFormEnterprise\builders;
class TabBuilder{
	
	public static function getTab($tabId){
		if($tabId){
			return self::getCustomTab($tabId);
		}else{
			return self::getMainTab($tabId);
		}
	}
	
	public static function getMainTab($tabId){
		return new \onlineFormEnterprise\libraries\Tab\MainTab($tabId);
	}
	
	public static function getCustomTab($tabId){
		return new \onlineFormEnterprise\libraries\Tab\CustomTab($tabId);
	}
}