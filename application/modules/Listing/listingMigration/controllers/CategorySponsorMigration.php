<?php

class CategorySponsorMigration extends MX_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('listingMigration/CategorySponsorMigrationLibrary');
	}


	public function migrateCategorySponsor(){
		return;
		$library = new CategorySponsorMigrationLibrary();
		$library->migrateCategorySponsor();
	}


	public function migrateMainInstitute(){
		return;
		$library = new CategorySponsorMigrationLibrary();
		$library->migrateMainInstitutes();
	}
}