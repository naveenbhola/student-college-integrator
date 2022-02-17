<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wurfl
{

	public $wurflManager;
	public $device;
	public $id;
	public $fallBack;

	function Wurfl()
	{
		
		$wurflDir = FCPATH . 'WURFL';
		
		$resourcesDir = '/var/www/html/mobileResources';
		
		require_once $wurflDir.'/Application.php';
		
		$persistenceDir = $resourcesDir.'/persistence';

		$cacheDir = $resourcesDir.'/cache';
		
		// Create WURFL Configuration
		$wurflConfig = new WURFL_Configuration_InMemoryConfig();
		
		// Set location of the WURFL File
		$wurflConfig->wurflFile($resourcesDir.'/wurfl.zip');
		
		// Set the match mode for the API ('performance' or 'accuracy')
		$wurflConfig->matchMode('performance');
		
		// Setup WURFL Persistence
		$wurflConfig->persistence('file', array('dir' => $persistenceDir));
		
		// Setup Caching
		$wurflConfig->cache('file', array('dir' => $cacheDir, 'expiration' => 36000000000));
		
		// Create a WURFL Manager Factory from the WURFL Configuration
		$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
		
		// Create a WURFL Manager
		/* @var $wurflManager WURFL_WURFLManager */
		$this->wurflManager = $wurflManagerFactory->create();
		
	}

	function load($device = "")
	{
		if (is_array($device)){
			$this->device = $this->wurflManager->getDeviceForHttpRequest($device);
		} else {
			$this->device = $this->wurflManager->getDeviceForUserAgent($device);
		}
		if (!empty($this->device)){
			$this->id = $this->device->id;
			$this->fallBack = $this->device->fallBack;
		} else {
			return false;
		}
	}

	function getDevice(){
		return $this->device;
	}

	function getCapability($capabilityName = ""){
		return $this->device->getCapability($capabilityName);
	}

	function getAllCapabilities(){
		return $this->device->getAllCapabilities();
	}

	function getId(){
		return $this->id;
	}

	function getFallback(){
		return $this->fallback;
	}
}
