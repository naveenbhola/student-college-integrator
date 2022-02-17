<?php
	class CountryHomeCrons extends MX_Controller{
		public function __construct(){
			parent::__construct();
		}

		public function storeCountsForFeeAffordabilityWidget(){
			$this->validateCron(); // prevent browser access
			error_log("::START:: Starting storage of counts for Country Home Pages");
			$lib = $this->load->library("countryHome/CountryHomeLib");
			$lib->storeCountsForFeeAffordabilityWidget();
			error_log("::COMPLETED:: Storage of counts for Country Home Pages Complete");
		}
	}
?>