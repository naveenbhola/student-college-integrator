<?php

class AbroadListingSeoMigration extends MX_Controller
{
   
    private $abroadCommonLib;
	private $migrationModelObj;
	
	public function __construct()
    {
		//$this->config->load('studyAbroadCMSConfig');
		//$this->abroadCommonLib 		= $this->load->library('listingPosting/AbroadCommonLib');
		$this->migrationModelObj 	= $this->load->model('listingPosting/abroadlistingseomigrationmodel');
	}
	
	public function migrateListingSeoDetails(){
		
		error_log("SEO Migration: Starting for University ".date("h:i:sa"));
		$uMigStartTime = microtime(true);
		$this->_migrateUniversitySeoDetails();
		$uMigEndTime = microtime(true);
		error_log("SEO Migration: Done for University ".date("h:i:sa"));
		error_log("SEO Migration: Time Taken for University ".($uMigEndTime-$uMigStartTime));
		
		error_log("SEO Migration: Starting for Course ".date("h:i:sa"));
		$courseMigStartTime = microtime(true);
		$this->_changeCourseSeoUrl();
		$courseMigEndTime = microtime(true);
		error_log("SEO Migration: Done for Course ".date("h:i:sa"));
		error_log("SEO Migration: Time Taken for Course ".($courseMigEndTime-$courseMigStartTime));
	}

	private function _migrateUniversitySeoDetails(){
		$univData = $this->migrationModelObj->getUniversityDetailsForMigration();
		$this->_prepareNewUniversityUrlForUpdation($univData);
		$this->migrationModelObj->updateNewUniversitySeoUrl($univData);
	}
    
	private function _changeCourseSeoUrl()
	{
		$this->migrationModelObj->updateCourseSeoUrl();
	}

	private function _prepareNewUniversityUrlForUpdation(& $data){
		$result = array();
		$lib = $this->load->library('listingPosting/AbroadPostingLib');
		foreach($data as $row){
			$temp['listing_type_id'] = $row['universityId'];
			$temp['listing_seo_url'] = $lib->getUniversityUrl($row['universityId'],$row['universityName'],$row['countryName']);
			$result[] = $temp;
		}
		$data = $result;
	}
	
}
