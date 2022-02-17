<?php
/**
 * This class returns the required data to the server
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class homepageslideshowmodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct(){
		parent::__construct('Homepage');
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
		//$dbConfig = array('hostname'=>'localhost');
		//$this->listingconfig->getDbConfig_test($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
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
	public function addContentToCarouselWidget($carousel_title,$carousel__destination_url,$carousel_photo_url,$carousel_description,$carousel_open_new_window) {

		$dbHandle = $this->_getDbHandle('write');
		$arrResults = $dbHandle->query("select count(*) as count from HOMEPAGERDGN_carousel_widget");
		$results = $arrResults->result();
		$count = $results[0]->count;
		if($count == 4) {
			return "Can't add more carousel";
		}
                $sql = "select carousel_order from HOMEPAGERDGN_carousel_widget";
		$query = $dbHandle->query($sql);
		foreach($query->result_array() as $row_obj)
		{
			$row_obj_array[] = $row_obj['carousel_order'];
		} 
                $carousel_order_array = array_diff(array('1','2','3','4'),$row_obj_array);
                if(count($carousel_order_array)>0 && count($row_obj_array) == 3) {
                        sort($carousel_order_array,SORT_NUMERIC);
                	$carousel_order = $carousel_order_array[count($carousel_order_array)-1];
                } else {
                	$arrResults = $dbHandle->query("select max(carousel_order) as carousel_order from HOMEPAGERDGN_carousel_widget");
			$results = $arrResults->result();
			$carousel_order = $results[0]->carousel_order+1;
                }
		$carousel_title = addslashes(strip_tags($carousel_title));
		$carousel__destination_url = addslashes(strip_tags($carousel__destination_url));
		$carousel_description = addslashes(strip_tags($carousel_description));
		$sql = "insert into HOMEPAGERDGN_carousel_widget (carousel_title,carousel__destination_url,carousel_description,carousel_photo_url,carousel_open_new_window,carousel_order) values(?,?,?,?,?,?)";
		$query = $dbHandle->query($sql, array($carousel_title,$carousel__destination_url,$carousel_description,$carousel_photo_url,$carousel_open_new_window,$carousel_order));
		return $dbHandle->insert_id();
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function renderCarouselDeatils($carousel_id) {
		$dbHandle = $this->_getDbHandle();
		if(!empty($carousel_id)) {
			$clause = " where carousel_id=?";
			$sql = "select * from HOMEPAGERDGN_carousel_widget".$clause." order by carousel_order asc";
			$query = $dbHandle->query($sql, array($carousel_id));
		} else {
			$sql = "select * from HOMEPAGERDGN_carousel_widget order by carousel_order asc";
			$query = $dbHandle->query($sql);
		}
		$rows = $query->result();
		return $rows;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateCarouselDeatils($array) {
		$dbHandle = $this->_getDbHandle('write');
		if(!(is_array($array) && count($array)>0)) {
			error_log("UPDATE_ERROR");
			return;
		}
		$append_string = "";
		$count_element = count($array);
		foreach ($array as $key=>$value) {
			if($key !='carousel_id' && !empty($value)) {
				if($count_element == 2) {
					$append_string = $append_string.$key."=".$value." where carousel_id=".$dbHandle->escape($array['carousel_id']);
				} else {
					$append_string = $append_string.$key."='".$value."',";
				}
			}
		}
		if($count_element>2) {
			$append_string = rtrim($append_string,',');
			$append_string = $append_string." where carousel_id=".$dbHandle->escape($array['carousel_id']);
		}
		$sql = "update HOMEPAGERDGN_carousel_widget set ".$append_string;
		$query = $dbHandle->query($sql);
		$rows = $dbHandle->affected_rows();
		return $rows;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function deleteCarousel($carousel_id,$carousel_order_delete) {
		$sql = "delete from HOMEPAGERDGN_carousel_widget where carousel_id=?";
		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($sql, array($carousel_id));
		$rows = $dbHandle->affected_rows();
		if($rows>0) {
			$sql = "select carousel_order from HOMEPAGERDGN_carousel_widget";
			$query = $dbHandle->query($sql);
			foreach($query->result_array() as $row_obj)
			{ 
				$row_obj_array[] = $row_obj['carousel_order'];      
			}
                        if(count($row_obj_array) == 3) {
                        if($carousel_order_delete ==4) {
				$row_obj_array = array('1','2','3');
                        } else if($carousel_order_delete ==1) {
				$row_obj_array = array('4');
                        }  else if($carousel_order_delete ==2) {
				$row_obj_array = array('1');
                        }  else if($carousel_order_delete ==3) {
				$row_obj_array = array('1');
                        } 
                        } else if(count($row_obj_array) == 2 && $carousel_order_delete ==1) {
				$row_obj_array = array('0');
                        }
			$row_obj_array = implode(',', $row_obj_array);
			$sql = "update HOMEPAGERDGN_carousel_widget set carousel_order = (carousel_order-1) where (carousel_order-1) NOT IN($row_obj_array) AND (carousel_order-1)!=0";
			$query = $dbHandle->query($sql);
			$rows = $dbHandle->affected_rows();
			return $rows;
		} else {
			return "NO DATA FOUND";
		}
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function reorderCarousel($array) {
		$dbHandle = $this->_getDbHandle('write');
		$sql_update1 = "update HOMEPAGERDGN_carousel_widget set carousel_order=? where carousel_id=?";
		$query = $dbHandle->query($sql_update1, array($array[0],$array[3]));
		$rows1 = $dbHandle->affected_rows();
		$sql_update2 = "update HOMEPAGERDGN_carousel_widget set carousel_order=? where carousel_id=?";
		$query = $dbHandle->query($sql_update2, array($array[2],$array[1]));
		$rows2 = $dbHandle->affected_rows();
		return ($rows1+$rows);
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getDataForHomepageCafeWidget($request) {
		$dbHandle = $this->_getDbHandle();
		$rows = array();

		// get questions realted data 
		$sql = "SELECT mtb.msgId,mtb.msgTxt,mtb.creationDate,mtb.threadId,mtb.userId,mct.categoryId,cb.name as cat_name,tusr.displayname,mtb.fromOthers ".
		       "FROM `messageTable` mtb,messageCategoryTable mct,categoryBoardTable cb,tuser tusr WHERE".
		       "(mtb.`fromOthers`='user' OR mtb.`fromOthers`='discussion') AND  mtb.status in ('live','closed') AND mtb.msgId ".
			"IN(".implode(",",$request).") AND mct.threadId = mtb.threadId ".
			"AND cb.boardId=mct.categoryId AND tusr.userId = mtb.userId AND mct.categoryId > 1 AND mct.categoryId < 15  ";

		$query = $dbHandle->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$rows[$row->threadId] = $row;
			}
		}

		$answers_rows = array();
		// get answers count
		$sql = "SELECT threadId,count(*) as count FROM `messageTable` WHERE `fromOthers`='user' AND `parentId`=`threadId` ".
		       "AND `mainAnswerId`='0' AND  status = 'live' ".
		       "AND threadId IN(".implode(",",$request).") GROUP BY threadId";	

                $query = $dbHandle->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$answers_rows[$row->threadId] = $row->count;
			}
		}
		//return $answers_rows;
		// merge it
                foreach($rows as $key=>$row) {
                        $row->msgTxt = html_escape($row->msgTxt);
			$row->count = $answers_rows[$key];
			$rows[$key] = $row;	
			$row->url = getSeoUrl($row->threadId,'question',$row->msgTxt,'','',$row->creationDate); 	
		}
                
                /*query to get no of experts in last seven days starts*/
		$sql = "select count(userId) as no_of_experts from userpointsystembymodule where userpointvaluebymodule>=1150 AND modulename ='AnA'";
		$query = $dbHandle->query($sql);
		$rows1 = $query->result();
                $final_results = array('experts'=>$rows1[0]->no_of_experts,'otherdata'=>$rows);

		return $final_results;
	}
}
