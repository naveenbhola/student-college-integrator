<?php
/**
 * This class returns the required data to the server
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class studyabroadwidgetmodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct(){
		parent::__construct('Listing');
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read'){
		//connect DB
		$appId = 1;
		$this->load->library('listingconfig');
		if($operation=='read'){
			$dbHandle = $this->getReadHandle();
		}
		else{
			$dbHandle = $this->getWriteHandle();
		}
		if($dbHandle == ''){
			error_log('error can not create db handle');
		}
		return $dbHandle;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function addContentToCarouselWidget($carousel_title,$carousel_photo_url,$carousel_description) {

		$dbHandle = $this->_getDbHandle('write');
		$date = date('y-m-d h:i:s', time());
		$insertData = array(
					"registrationLayerTitle"=>$carousel_title,
		            "registrationBannerURL"=>$carousel_photo_url,
		            "registrationLayerMsg"=>$carousel_description,
		            'status'=>'live',
		            'addedOn'=>$date
		);
		$dbHandle->insert('registrationlayer_configs',$insertData);
		return $dbHandle->insert_id();
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function renderCarouselDeatils() {
		$sql = "select * from registrationlayer_configs where status='live' limit 1";
		error_log('adityaquery'.$sql);
		$dbHandle = $this->_getDbHandle('read');
		$query = $dbHandle->query($sql,array());
		$rows = $query->result();
		return json_encode($rows);
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateCarouselDeatils($array) {

		if(!(is_array($array) && count($array)>0)) {
			error_log("UPDATE_ERROR");
			return;
		}
		$sql = "update registrationlayer_configs set status='history' where id=?";
		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($sql,array($array['carousle_id']));
		unset($array['carousle_id']);
		$dbHandle->insert('registrationlayer_configs',$array);
		return $dbHandle->insert_id();
	}
}
