<?php

class coursepagecmsmodel extends MY_Model {

	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct(){
		parent::__construct('coursePages');
	}

	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read'){

		//connect DB
		if($operation=='read'){
			$dbHandle = $this->getReadHandle();
		} else{
			$dbHandle = $this->getWriteHandle();
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
	public function addSlide($imageTitle,$landingUrl,$imageUrl,$courseHomePageId,$open_new_tab,$addedBy='CMS') {

		$dbHandle = $this->_getDbHandle('write');
		$arrResults = $dbHandle->query("select count(*) as count from course_pages_featured_institutes WHERE status='live' AND courseHomePageId=?", $courseHomePageId);
		$results = $arrResults->result();
		$count = $results[0]->count;

		if($count == 5) {
			return "Can't add more carousel";
		}

		$sql = "select slidePosition from course_pages_featured_institutes WHERE status='live' AND courseHomePageId=?";

		$query = $dbHandle->query($sql, $courseHomePageId);
		foreach($query->result_array() as $row_obj)
		{
			$row_obj_array[] = $row_obj['slidePosition'];
		}

		if(count($row_obj_array) >0) {
			$carousel_order_array = array_diff(array('1','2','3','4', '5'),$row_obj_array);
			sort($carousel_order_array,SORT_REGULAR);
			$slidePosition = $carousel_order_array[0];
		} else {
			$arrResults = $dbHandle->query("select max(slidePosition) as slidePosition from course_pages_featured_institutes WHERE status='live' AND courseHomePageId=?", $courseHomePageId);
			$results = $arrResults->result();
			$slidePosition = $results[0]->slidePosition+1;
		}

		$imageTitle = addslashes(strip_tags($imageTitle));
		$landingUrl = addslashes(strip_tags($landingUrl));
		$status = 'live';

		$sql = "insert into course_pages_featured_institutes ".
				"(imageTitle,landingUrl,imageUrl,courseHomePageId,open_new_tab,slidePosition,status,addedBy) ".
				"VALUES (?,?,?,?,?,?,?,?)";

		$query = $dbHandle->query($sql,array($imageTitle,$landingUrl,$imageUrl,$courseHomePageId,$open_new_tab,$slidePosition,$status,$addedBy));

		return "Slide added successfully";
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getSlides($id,$courseHomePageId) {
		$dbHandle = $this->_getDbHandle();
		$dbHandle->where('status','live');
		$dbHandle->where('courseHomePageId',$courseHomePageId);
		if(!empty($id)) {
			$dbHandle->where('id',$id);
		}
		$dbHandle->order_by('slidePosition','asc');
		$rows = $dbHandle->get('course_pages_featured_institutes')->result_array();

		foreach ($rows as $key=>$value) {
			foreach ($value as $key1=>$value1) {
				if($key1 == 'landingUrl') {
					$value1 = urlencode($value1);
				}
				else if($key1 == 'imageUrl'){
					$value1 = addingDomainNameToUrl(array('url'=>$value1,'domainName'=>MEDIA_SERVER));
					// $value1 = MEDIAHOSTURL.$value1;
				}
				$rows[$key][$key1] = html_entity_decode(stripslashes($value1));
			}
		}

		return $rows;
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateSlide($array) {

		if(!(is_array($array) && count($array)>0)) {
			error_log("UPDATE_ERROR");
			return;
		}

		$append_string = "";
		$count_element = count($array);

		if(!array_key_exists('addedBy', $array)) {
			$array['addedBy'] = 'CMS';
		}

		foreach ($array as $key=>$value) {
			if($key == 'imageTitle') {
				$value = addslashes($value);
			}
			if($key !='id' && !empty($value)) {
				if($count_element == 2) {
					$append_string = $append_string.$key."=".$value." where id=".$array['id'];
				} else {
					$append_string = $append_string.$key."='".$value."',";
				}
			}
		}

		$append_string = strip_tags($append_string);

		if($count_element>2) {
			$append_string = rtrim($append_string,',');
			$append_string = $append_string." where id=".$array['id'];
		}

		$sql = "update course_pages_featured_institutes set ".$append_string;
		error_log('update slide query'.$sql);
		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($sql);
		$rows = $dbHandle->affected_rows();

		return "Slide updated successfully";
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function deleteSlide($carousel_id,$carousel_order_delete,$courseHomePageId) {

		$sql = "select slidePosition from course_pages_featured_institutes WHERE status='live' AND courseHomePageId=?";
		$dbHandle = $this->_getDbHandle('read');
		$query = $dbHandle->query($sql, $courseHomePageId);
		foreach($query->result_array() as $row_obj)
		{
			$row_obj_array[] = $row_obj['slidePosition'];
		}
			
		$include_array = array();
		foreach ($row_obj_array as $order) {
			if($order >$carousel_order_delete) {
				$include_array[] = $order;
			}
		}

		$sql = "UPDATE course_pages_featured_institutes SET status='deleted' where id=?";
		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($sql, $carousel_id);
		$rows1 = $dbHandle->affected_rows();

		if(count($include_array)>0) {

			$sql = "update course_pages_featured_institutes set slidePosition = (slidePosition-1) where status='live' AND (slidePosition) IN (?) AND courseHomePageId=?";
			$query = $dbHandle->query($sql, array($include_array,$courseHomePageId));
			$rows2 = $dbHandle->affected_rows();

		}

		return "Slide deleted successfully";
			
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function reorderSlide($array) {
		$dbHandle = $this->_getDbHandle('write');
		$sql_update1 = "update course_pages_featured_institutes set slidePosition=? where id=?";
		$query = $dbHandle->query($sql_update1, array($array[2], $array[1]));
		$rows1 = $dbHandle->affected_rows();
		$sql_update2 = "update course_pages_featured_institutes set slidePosition=? where id!=? ".
						"AND slidePosition=? AND courseHomePageId=?";
		$query = $dbHandle->query($sql_update2, array($array[0], $array[1], $array[2], $array[3]));
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
	public function addSection($courseHomePageId,$sectionHeading,$sectionPosition,$link_id,$linkTitle,$landinURL,$displayOrder,$open_new_tab,$updateSection,$section_order,$section_id,$section_url) {

		$dbHandle = $this->_getDbHandle('write');

		if(empty($updateSection)) {
			$arrResults = $dbHandle->query("select count(*) as count from  course_pages_featured_institute_sections WHERE status='live' AND courseHomePageId=?", $courseHomePageId);
			$results = $arrResults->result();

			$count = $results[0]->count + count($sectionHeading);


			if($count > 4) {

				return "Can't add more than 4 sections";
			}

			$sql = "select sectionPosition from course_pages_featured_institute_sections WHERE status='live' AND courseHomePageId=?";

			$query = $dbHandle->query($sql, $courseHomePageId);
			foreach($query->result_array() as $row_obj)
			{
				$row_obj_array[] = $row_obj['sectionPosition'];
			}

			if(count($row_obj_array) >0) {
				$carousel_order_array = array_diff(array('1','2','3','4'),$row_obj_array);
				sort($carousel_order_array,SORT_NUMERIC);
				$sectionPosition = $carousel_order_array[0];
			} else {
				$arrResults = $dbHandle->query("select max(sectionPosition) as sectionPosition from course_pages_featured_institute_sections WHERE status='live' "."AND courseHomePageId=?", $courseHomePageId);
				$results = $arrResults->result();
				$sectionPosition = $results[0]->sectionPosition+1;
			}
		}

		$status = 'live';

		foreach ($sectionHeading as $key=>$value) {

			if(!empty($updateSection)) {
				$request = array(
					'sectionPosition'=>$sectionPosition[$key],
					'oldSectionposition'=>$section_order,
					'sectionHeading'=>$value,
				    'sectionId'=>$section_id,
				    'subcatId'=>$courseHomePageId,
					'updateSection'=>$updateSection,
					'sectionURL'=>$section_url[$key] 
				);

				$this->updateLink($request);

			} else {
				$sql = "insert into course_pages_featured_institute_sections ".
				"(sectionHeading,courseHomePageId,sectionPosition,status,sectionURL) ".
				"VALUES (?,?,?,?,?)";                
				$query = $dbHandle->query($sql,array(addslashes(strip_tags($value)),$courseHomePageId,$sectionPosition,$status,$section_url[$key]));
				$section_id = $dbHandle->insert_id();
				$sectionPosition++;
			}

			if($section_id >0) {

				$arrResults = $dbHandle->query("select count(*) as count from course_pages_featured_institute_sections_links WHERE sectionId=? AND status='live'", $section_id);
				$results = $arrResults->result();
				//error_log('ADITYA114'."select count(*) as count from course_pages_featured_institute_sections_links WHERE sectionId=$section_id AND status='live'");
				$count = $results[0]->count + count($linkTitle[$key]);
				if($count > 4) {
					if($results[0]->count == 0) {
						$dbHandle->query("UPDATE course_pages_featured_institute_sections set status='history' WHERE sectionId=?", $section_id);
					}
					return "Can't add more than 4 links";
				}

				$sql = "select displayOrder from  course_pages_featured_institute_sections_links WHERE sectionId=? AND status='live'";
				//error_log('ADITYA114'."select count(*) as count from course_pages_featured_institute_sections_links WHERE sectionId=$section_id AND status='live'");
				$query = $dbHandle->query($sql, $section_id);
				$row_obj_array = array();
				foreach($query->result_array() as $row_obj)
				{
					$row_obj_array[] = $row_obj['displayOrder'];
				}

				if(count($row_obj_array) >0) {
					$carousel_order_array = array_diff(array('1','2','3','4'),$row_obj_array);
					sort($carousel_order_array,SORT_NUMERIC);
					$displayOrder = $carousel_order_array[0];
				} else {
					$arrResults = $dbHandle->query("select max(displayOrder) as displayOrder from course_pages_featured_institute_sections_links WHERE sectionId=? AND status='live'", $section_id);
					$results = $arrResults->result();
					$displayOrder = $results[0]->displayOrder+1;
				}
			}

			foreach ($linkTitle[$key] as $key1=>$title) {

				if(array_key_exists($key1, $open_new_tab[$key])) {
					$tab = 'YES';
				} else {
					$tab = 'NO';
				}

				$sql = "insert into  course_pages_featured_institute_sections_links ".
				"(sectionId,linkTitle,landinURL,displayOrder,status,open_new_tab) ".
				"VALUES (?,?,?,?,?,?)";
				$query = $dbHandle->query($sql,array($section_id,addslashes(strip_tags($title)),(addslashes(strip_tags($landinURL[$key][$key1]))),$displayOrder,$status,$tab));
				$displayOrder++;
					
			}
		}

		return "Sections added successfully";
	}

	public function getSectionsDetails($section_id = '',$courseHomePageId) {

		$additional_clause = "";
		$param = array();
		if(!empty($section_id)) {
			$additional_clause = "AND sections.sectionId =?";
			$param[] = $section_id;
		}

		$sql = "SELECT links.id,links.sectionId,links.linkTitle,links.open_new_tab, ".
				"links.landinURL,links.displayOrder,sections.sectionHeading, ". 
				"sections.courseHomePageId,sections.sectionPosition,sections.sectionURL ".
				"FROM course_pages_featured_institute_sections sections, course_pages_featured_institute_sections_links links ".
				"WHERE links.sectionId = sections.sectionId and links.status='live' and sections.status='live' ".
		        "$additional_clause  AND sections.courseHomePageId=?";
		$param[] = $courseHomePageId;

		$dbHandle = $this->_getDbHandle();
		$query = $dbHandle->query($sql, $param);
		$rows = $query->result_array();

		$response_array = array();
		foreach ($rows as $row) {
			$response_array[$row['sectionId']][$row['id']] = $row;
			$response_array[$row['sectionId']]['position'] = $row['sectionPosition'];
		}
			
		// sort sections
		uasort($response_array, function ($a, $b) {
			if ($a['position'] == $b['position']) {
				return 0;
			}

			return ($a['position'] < $b['position']) ? -1 : 1;
		}
		);
			
		foreach ($response_array as $key=>$values) {
			unset($response_array[$key]['position']);
		}

		// sort links first
		foreach ($response_array as $key=>$values) {
			uasort($values, function ($a, $b) {
				if ($a['displayOrder'] == $b['displayOrder']) {
					return 0;
				}
				return ($a['displayOrder'] < $b['displayOrder']) ? -1 : 1;
			}
			);

			unset($response_array[$key]);
			$response_array[$key] = $values;
		}

		foreach ($response_array as $key=>$value) {
			foreach ($value as $key1=>$value1) {
				foreach ($value1 as $key2=>$value2) {
					if($key2 == 'sectionURL' || $key2 == 'landinURL') {
						$value2 = urlencode($value2);
					}
					$response_array[$key][$key1][$key2] = stripslashes($value2);

				}
			}
		}
		return $response_array;
	}

	function deleteLink($section_id,$link_id,$link_order) {
		// get all the orders of links on a section
		$sql = "select displayOrder from course_pages_featured_institute_sections_links ".
		       "WHERE sectionId=? AND status='live'";

		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($sql, $section_id);

		foreach($query->result_array() as $row_obj) {

			$row_obj_array[] = $row_obj['displayOrder'];
		}
			
		$include_array = array();
		foreach ($row_obj_array as $order) {
			if($order >$link_order) {
				$include_array[] = $order;
			}
		}

		// check whether section has only one link then delete section too

		if(count($row_obj_array) == 1) {

			$sql = "SELECT sectionPosition,sectionId,courseHomePageId FROM course_pages_featured_institute_sections ".
                               "WHERE status='live'";

			$query = $dbHandle->query($sql);
			$section_order_array = array();
			$section_order_include_array = array();
			$sections_results = array();
			$required_courseHomePageId = "";
			$required_section_order = "";

			foreach($query->result_array() as $row_obj) {
				if($row_obj['sectionId'] == $section_id) {
					$required_courseHomePageId = $row_obj['courseHomePageId'];
					$required_section_order = $row_obj['sectionPosition'];
				}
				$sections_results[$row_obj['subcatId']][] = $row_obj;
			}

			$section_order_array = $sections_results[$required_courseHomePageId];
			//error_log('teribahinskee'.print_r($sections_results,true));
			foreach($section_order_array as $order) {
				if($order['sectionPosition']>$required_section_order) {
					$section_order_include_array[] = $order['sectionPosition'];
				}
			}
			//error_log('teribahinskeetang '.print_r($section_order_include_array,true));
			$dbHandle = $this->_getDbHandle('write');
			$sql = "UPDATE course_pages_featured_institute_sections set status='deleted' WHERE ".
				    "sectionId=? AND status='live'";

			$query = $dbHandle->query($sql, $section_id);
			$rows1 = $dbHandle->affected_rows();

			if(count($section_order_include_array)>0) {

				$sql = "UPDATE course_pages_featured_institute_sections set sectionPosition = (sectionPosition-1) where (sectionPosition) IN (?) AND courseHomePageId=? AND status='live'";	
				//error_log('teribahinskee'.$sql);
				$query = $dbHandle->query($sql, array($section_order_include_array, $required_courseHomePageId));

			}

		}

		// delete link

		$sql = "UPDATE course_pages_featured_institute_sections_links set status='deleted' where id=? ".
				"AND sectionId=? AND status='live'";

		$query = $dbHandle->query($sql, array($link_id, $section_id));
		$rows1 = $dbHandle->affected_rows();

		// update order of other links
		if(count($include_array)>0) {

			$sql = "UPDATE course_pages_featured_institute_sections_links set displayOrder = (displayOrder-1) where (displayOrder) IN (?) AND sectionId=? AND status='live'";
			$query = $dbHandle->query($sql, array($include_array,$section_id));
			$rows2 = $dbHandle->affected_rows();

		}

		return "Link deleted successfully";
	}

	function updateLink($request) {
		$request['sectionHeading'] = addslashes($request['sectionHeading']);
		// update and reorder sections
		$param = array();
		$update_section = "UPDATE course_pages_featured_institute_sections set sectionURL=?, "."sectionHeading=?, ".
		                  "sectionPosition=? WHERE sectionId=? AND status='live' AND courseHomePageId=?";
		$param[] = $request['sectionURL'];
		$param[] = $request['sectionHeading'];
		$param[] = $request['sectionPosition'];
		$param[] = $request['sectionId'];
		$param[] = $request['courseHomePageId'];

		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($update_section, $param);
		$rows1 = $dbHandle->affected_rows();

		$param = array();
		if($rows1 >0) {
			// reorder other live sections
			$reorder_sections = "UPDATE course_pages_featured_institute_sections set sectionPosition=?".
		                       "WHERE sectionId!=? AND status='live' AND sectionPosition = ? AND courseHomePageId=?";
		    $param[] = $request['oldSectionposition'];
			$param[] = $request['sectionId'];
			$param[] = $request['sectionPosition'];
			$param[] = $request['courseHomePageId'];
			$dbHandle = $this->_getDbHandle('write');
			$query = $dbHandle->query($reorder_sections, $param);
			$rows1 = $dbHandle->affected_rows();
		}

		$param = array();
		if(empty($request['updateSection'])) {
			$request['linkTitle'] = addslashes($request['linkTitle']);
			$request['landinURL'] = addslashes($request['landinURL']);
			// update and reorder links
			$update_section = "UPDATE course_pages_featured_institute_sections_links set linkTitle=?, ".
		                  "displayOrder=?,landinURL=?,open_new_tab=?".
		                  "WHERE id=? AND status='live'";
		    $param[] = $request['linkTitle'];
		    $param[] = $request['displayOrder'];
		    $param[] = $request['landinURL'];
		    $param[] = $request['open_new_tab'];
		    $param[] = $request['id'];

			$dbHandle = $this->_getDbHandle('write');
			$query = $dbHandle->query($update_section, $param);
			$rows1 = $dbHandle->affected_rows();

			$param = array();
			if($rows1 >0) {
				// reorder other live sections
				$reorder_sections = "UPDATE course_pages_featured_institute_sections_links set displayOrder=?".
		                       "WHERE sectionId=? AND id!=? AND status='live' AND displayOrder = ?";
		        $param[] = $request['oldDisplayOrder'];
		        $param[] = $request['sectionId'];
		        $param[] = $request['id'];
		        $param[] = $request['displayOrder'];
				$dbHandle = $this->_getDbHandle('write');
				$query = $dbHandle->query($reorder_sections, $param);
				$rows1 = $dbHandle->affected_rows();
			}
		}
		return "Updated successfully";
	}

	function getSLideDetailsAddedByCron($categoryIds = array()) {

		if(count($categoryIds) == 0) {
			return array();
		}

		$sql = "SELECT * FROM course_pages_featured_institutes ".
		       "WHERE status='live' AND addedBy='CRON' ".
		        "AND subcatId in (?)";

		$results = $this->db->query($sql,array($categoryIds))->result_array();

		$response = array();

		foreach ($results as $value) {
			$response[$value['subcatId']] = $value;
		}

		return $response;
	}
	/**
	 * this method inserts rows in course_pages_faq_headings
	 *
	 * @param array
	 * @return array
	 */
	public function addFaqHeading($groupHeading,$courseHomePageId) {
		//get the position for the new faq
		$sql = "SELECT max(position)+1 as newPosition FROM course_pages_faq_headings where status='live' and courseHomePageId=?";

		$results = $this->db->query($sql, $courseHomePageId)->result_array();
		$sectionPosition = ($results[0]['newPosition']==NULL? 1 : $results[0]['newPosition']);

		//now add the new heading into the table
		$dbHandle = $this->_getDbHandle('write');
		$sql = "insert into course_pages_faq_headings ".
			"(groupHeading,courseHomePageId,position,status) ".
			"VALUES (?,?,?,?)";                

		$status= 'live';
		$query = $dbHandle->query($sql,array($groupHeading,$courseHomePageId,$sectionPosition,$status));
		$groupHeadingId = $dbHandle->insert_id();

		return $groupHeadingId;
	}

	/**
	 * this method inserts rows in course_pages_faqs
	 *
	 * @param array
	 * @return array
	 */
	public function addFaqDetails($groupHeadingId,$questionText,$answerText) {
		//get the position for the new faq
		$sql =  "SELECT max(position)+1 as newPosition FROM course_pages_faqs ".
			"where status='live' and groupHeadingId =?";
		$results = $this->db->query($sql, $groupHeadingId)->result_array();
		$newPosition = $results[0]['newPosition'];
		if($newPosition=='') $newPosition=1;
		/*if($position != $newPosition && $position>0){
			//update existing faq at $position with $newPosition
			$this->swapFaqPosition($groupHeadingId,$position,$newPosition);
			}*/
		$status= 'live';
		//$no_of_clicks= 0;

		//now insert the faq into the table
		$dbHandle = $this->_getDbHandle('write');

		$sql = "insert into course_pages_faqs ".
			"(groupHeadingId,questionText,answerText,position,status) ".
			"VALUES (?,?,?,?,?)";                

		$query = $dbHandle->query($sql,array($groupHeadingId,$questionText,$answerText,$newPosition,$status));
		$faq_id = $dbHandle->insert_id();
			
		return $faq_id;
	}
	/**
	 * this method searches heading text for exact match in course_pages_faq_headings
	 *
	 * @param array
	 * @return array
	 */
	public function searchFaqHeading($groupHeading,$courseHomePageId) {
		//get the position for the new faq
		$sql =  "SELECT id as matchedId from course_pages_faq_headings ".
			"where groupHeading = ? and courseHomePageId =? ".
			"and status='live'";

		$results = $this->db->query($sql, array($groupHeading, $courseHomePageId))->result_array();
		$groupHeadingId = ($results[0]['matchedId']==NULL? FALSE : $results[0]['matchedId']);

		return $groupHeadingId;
	}

	/**
	 * this method gets all the existing sequence numbers of faq
	 *
	 * @param array
	 * @return array
	 */
	public function getFaqSequence($groupHeadingId) {
		//get the position for the new faq
		$sql =  "select distinct position from course_pages_faqs ".
			"where groupHeadingId = ? ".
			"and status = 'live' ".
			"order by position asc";

		$results = $this->db->query($sql, $groupHeadingId)->result_array();

		return $results ;
	}
	/**
	 * this method updates faq's url with newurl
	 *
	 * @param array
	 * @return array
	 */
	public function updateFaqURL($questionId,$questionUrl){
		$updateStmt =   "UPDATE course_pages_faqs ".
				"set questionUrl=? ".
		                "WHERE id=?";

		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($updateStmt, array($questionUrl, $questionId));
	}
	/**
	 * this method updates faq's position with newPosition
	 *
	 * @param array
	 * @return array
	 */
	public function swapFaqPosition($groupHeadingId,$position,$newPosition){
		$updateStmt =   "UPDATE course_pages_faqs ".
				"set position=? ".
		                "WHERE groupHeadingId=? AND status='live' AND position=?";
		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($updateStmt, array($newPosition, $groupHeadingId, $position));
		//$rows1 = $dbHandle->affected_rows();

	}
	
	/**
	 * this method updates faq heading's position with newPosition
	 *
	 * @param array
	 * @return array
	 */
	public function swapFaqHeadingPosition($courseHomePageId,$position,$newPosition){
		$updateStmt =   "UPDATE course_pages_faq_headings ".
				"set position=? ".
		                "WHERE courseHomePageId=? AND status='live' AND position=?";

		$dbHandle = $this->_getDbHandle('write');
		$query = $dbHandle->query($updateStmt, array($newPosition, $courseHomePageId, $position));
		//$rows1 = $dbHandle->affected_rows();
	}

	function updateCoursePageWidgetsMapping($request) {
		$dbHandle = $this->_getDbHandle('write');
		if(count($request)>0) {
			foreach ($request as $key=>$value) {

				if($value['order']%2 == 0) {
					$column = 2;
					$order = $value['order']/2;
				} else {
					$column = 1;
					$order = ($value['order']+1)/2;
				}

				$sql = "UPDATE course_pages_category_widgets_mapping ".
				   		"SET status=?,displayorder=?,columnPosition=? ".
				         "WHERE id=?";

				$dbHandle->query($sql,array($value['status'],$order,$column,$key));
			}

			return "success";
		}
	}
	/**
	 * this method gets all heading ids,texts
	 *
	 * @param array
	 * @return array
	 */
	function getAddedFaqHeadings($courseHomePageId)
	{
		//get the id,heading text for all headings
		$sql =  "select id,groupHeading from course_pages_faq_headings where status='live' and courseHomePageId = ?";

		$results = $this->db->query($sql, $courseHomePageId)->result_array();
		return $results ;//[row]['colname']
	}

	/**
	 * this method gets all faqs for a particluar group heading(if 0, picks first one ),
	 * under a sub category(passed)
	 * @param array
	 * @return array
	 */
	function getAddedFaqData($courseHomePageId, $groupHeadingId)
	{
		if(!$groupHeadingId){//error_log("Group Heading Id Recieved::".$groupHeadingId);
			$sqlmin = "select min(id) as firstHeadingId from course_pages_faq_headings ".
				  "where status = 'live' ".
				  "and courseHomePageId = ?";
			$resultmin = $this->db->query($sqlmin, $courseHomePageId)->result_array();
			$groupHeadingId = $resultmin[0]['firstHeadingId'];
		}
		//error_log("Group Heading Now::".$groupHeadingId);
		if ($groupHeadingId =='') return false;

		//get the id,heading text for all headings
		$sql =  "select ".
				"cpfh.id as headingId, ".
				"cpfh.groupHeading as headingText, ".
				"cpfs.id as faqId, ".
				"cpfs.questionText as faqQuestion, ".
				"cpfs.answerText as faqAnswer, ".
				"cpfs.position as faqPosition ".
			"from course_pages_faq_headings cpfh ".
			"inner join course_pages_faqs cpfs ".
				"on (cpfh.id=cpfs.groupHeadingId ".
				"and cpfh.courseHomePageId = ?) ".
			"where cpfh.status = 'live' ".
				"and cpfs.status ='live' ".
				"and cpfh.id=? ".
			"order by cpfh.position asc , cpfs.position asc";

		$results = $this->db->query($sql, array($courseHomePageId, $groupHeadingId))->result_array();

		return $results ;//[row]['colname']
	}
	/**
	 * this method deletes a particluar faq(chnages status From live)
	 *
	 * @param array
	 * @return array
	 */
	public function deleteSingleFaq($courseHomePageId,$faqId){

		//get the position,groupHeadingId of the faq to be deleted(later used to shift faqs up)
		$resultFindPos = $this->getSingleFaqPosition($faqId);
		$position= $resultFindPos[0]['position'];
		$groupHeadingId = $resultFindPos[0]['groupHeadingId'];

		//find out ids of faqs that have to be shifted up in position
		$sqlToBeShifted ="select id from course_pages_faqs ".
				 "where status = 'live' ".
				 "and groupHeadingId = ? ";
		$resultToBeShifted = $this->db->query($sqlToBeShifted, $groupHeadingId)->result_array();

		$dbHandle = $this->_getDbHandle('write');
		//now delete (update status)
		$updateStmt =   "UPDATE course_pages_faqs ".
				"set status='deleted' ".
		                "WHERE id=? AND status='live' ";

		$query = $dbHandle->query($updateStmt, $faqId);

		//shift position up of other faqs within same sub category and heading
		$updateShiftStmt = "UPDATE course_pages_faqs ".
				   "set position = position-1 ".
		           "WHERE position > ? and groupHeadingId=? ".
				   "AND status='live' ";

		$query = $dbHandle->query($updateShiftStmt, array($position, $groupHeadingId));
		$rows = $dbHandle->affected_rows();
		//check if there were no affected rows, then delete the heading as well
		if($rows==0 && (count($resultToBeShifted)-1)==0){
			$this->shiftHeadingAfterDeletion($groupHeadingId,$courseHomePageId);
			$updateHeadingStmt =   "UPDATE course_pages_faq_headings ".
						"set status='deleted' ".
						"WHERE id=? AND status='live' ";

			$query = $dbHandle->query($updateHeadingStmt, $groupHeadingId);
			$rowsHeading = $dbHandle->affected_rows();
			$groupHeadingId = 0;
		}
		else {
			$rowsHeading = 0;
		}

		return array($rowsHeading, 1, $groupHeadingId);
	}
	/**
	 * this method loads a particluar faq( for edit)
	 *
	 * @param array
	 * @return array
	 */
	public function loadSingleFaq($faqId)
	{
		$sql =      "select ".
				"cpfs.id as faqId, ".
				"cpfh.id as groupHeadingId, ".
				"cpfh.groupHeading as groupHeading, ".
				"cpfh.position as headingPosition, ".
				"cpfs.questionText as faqQuestion, ".
				"cpfs.answerText as faqAnswer, ".
				"cpfs.position as faqPosition ".
			    "from course_pages_faqs cpfs ".
			    "inner join course_pages_faq_headings cpfh ".
			    "on (cpfs.groupHeadingId = cpfh.id  ".
				"and cpfh.status = 'live') ".
			    "where cpfs.id = ?  ".
				"and cpfs.status = 'live' ";

		$results = $this->db->query($sql, $faqId)->result_array();
		
		return $results ;

	}

	/**
	 * this method gets all heading ids,texts 
	 *
	 * @param array
	 * @return array
	 */	
	function getFaqHeadingsPosition($courseHomePageId)
	{
		//get the id,heading text for all headings
		$sql =  "select position from course_pages_faq_headings where status='live' and courseHomePageId = ? order by position asc";

		$results = $this->db->query($sql, $courseHomePageId)->result_array();
		
		return $results ;//[row]['colname']
	}


	public function updateQuestionClick($question_id) {

		if(empty($question_id)) {
			return false;
		}

		$sql = "UPDATE course_pages_faqs set no_of_clicks = no_of_clicks+1 ".
		       "WHERE status=? AND id=?";

		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->query($sql,array('live',$question_id));
	}
	
	public function addCpgsFaqFeedback($question_id,$session_id,$type_of_feedback,$user_id) {

		if(empty($question_id) || empty($type_of_feedback)) {			
			
			return false;
		}
		
		$sql = "insert into course_pages_faq_feedback ".
				"(sessionId,question_id,user_id,type_of_feedback) ".
				"VALUES (?,?,?,?)";
		
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->query($sql,array($session_id,$question_id,$user_id,$type_of_feedback));
		
		return $feedback_id = $dbHandle->insert_id();
	}

	public function updateCpgsFaqFeedback($question_id,$reason_for_no,$feedback_id) {
		
		if(empty($question_id) || empty($reason_for_no) || empty($feedback_id)) {
			return false;
		}
		
		$sql = "UPDATE course_pages_faq_feedback ".
				"SET reason_for_no=? ".
				"WHERE feedback_id=?";
		
		$dbHandle = $this->_getDbHandle('write');
		$dbHandle->query($sql,array($reason_for_no,$feedback_id));
	}
	/**
	 * this method shifts other heading when one is deleted within a sub category.
	 *
	 * @param array
	 * @return array
	 */	
	public function shiftHeadingAfterDeletion($groupHeadingId,$courseHomePageId)
	{
		//find the position of the heading being deleted
		$results = $this->getSingleFaqHeadingPosition($courseHomePageId,$groupHeadingId);
		$position = $results[0]['position'];
		
		//find if there are headings after the position of the one being deleted
		$sqlCount = "select count(id) as noOfRowsToBeShifted ".
			    "from course_pages_faq_headings ".
			    "where status= 'live' ".
			    "and courseHomePageId = ? ". 
			    "and position>?";
			    
		$results = $this->db->query($sqlCount, array($courseHomePageId, $position))->result_array();
		$noOfRowsToBeShifted = $results[0]['noOfRowsToBeShifted'];
				
		//if there are headings after the one being deleted,reduce the position of those
		if($noOfRowsToBeShifted >0){
			$dbHandle = $this->_getDbHandle('write');
			$updateShiftStmt = "UPDATE course_pages_faq_headings ".
				   "set position = position-1 ".
		                   "WHERE position > ? ".
				   "AND status='live' ".
				   "AND courseHomePageId = ?";
			$query = $dbHandle->query($updateShiftStmt, array($position, $courseHomePageId));
			$rows = $dbHandle->affected_rows();
			return 1;
		}
		else return 0;
		
	}
	
	/**
	 * this method updates a single row in course_pages_faq_headings
	 *
	 * @param array
	 * @return array
	 */
	public function updateFaqHeading($courseHomePageId,$groupHeadingId,$groupHeadingSequence,$groupHeadingText) {
		$dbHandle = $this->_getDbHandle('write');
		//get the old position and replace with new one
		$results = $this->getSingleFaqHeadingPosition($courseHomePageId,$groupHeadingId);
		$position = $results[0]['position'];
		if($groupHeadingSequence != $position){
			$this->swapFaqHeadingPosition($courseHomePageId,$groupHeadingSequence,$position);
		}
		//update the faq
		$sql =  "update course_pages_faq_headings ".
			"set groupHeading = ?, ".
			"position = ? ".
			"where status = 'live' and id = ? ".
			"and courseHomePageId = ? ";

		$dbHandle->query($sql,array($groupHeadingText,$groupHeadingSequence,$groupHeadingId,$courseHomePageId));
		return true;
		
	}

	/**
	 * this method updates a single row in course_pages_faq_headings
	 *
	 * @param array
	 * @return array
	 */
	public function updateFaqDetails($groupHeadingId,$faqId,$questionText,$answerText,$sequence) {
		$dbHandle = $this->_getDbHandle('write');
		//get the old position
		$resultFindPos = $this->getSingleFaqPosition($faqId);
		$position = $resultFindPos[0]['position'];
		if($sequence != $position){
			$this->swapFaqPosition($groupHeadingId,$sequence,$position);
		}
		//get the position for the new faq
		$sql =  "update course_pages_faqs ".
			"set questionText = ?, ".
			"answerText = ?, ".
			"position = ? ".
			"where status = 'live' and groupHeadingId = ? ".
			"and id = ? ";
		$dbHandle->query($sql,array($questionText,$answerText,$sequence,$groupHeadingId,$faqId));

		return true;
	}
	
	/**
	 * this method gets position of a single heading in course_pages_faq_headings
	 *
	 * @param array
	 * @return array
	 */
	public function getSingleFaqHeadingPosition($courseHomePageId,$groupHeadingId)
	{
		$sqlFindPos = " select position from course_pages_faq_headings ".
				"where status = 'live' ".
				"and courseHomePageId = ? ".
				"and id = ?" ;
		
		$results = $this->db->query($sqlFindPos, array($courseHomePageId, $groupHeadingId))->result_array();
		return $results;

	}
	/**
	 * this method gets position of a single faq in course_pages_faqs
	 *
	 * @param array
	 * @return array
	 */
	public function getSingleFaqPosition($faqId)
	{
		$sqlFindPos = "select groupHeadingId,position from course_pages_faqs ".
				  "where status = 'live' ".
				  "and id = ?";
		$resultFindPos = $this->db->query($sqlFindPos, $faqId)->result_array();
		return $resultFindPos;
	}
	
	public function saveRestrictContent($courseHomePageId = '',$contentId = '',$contentType = '',$flagType = '',$userId = ''){
		
		if($courseHomePageId == '' || $contentId == '' || $contentType == '' || $flagType == '' || $userId == ''){
			return ;			
		}
		// Acquiring write mode on DB
		$dbHandle = $this->_getDbHandle('write');
		$result['error'] = 'success';
		if($flagType == "nullify"){
			$sql = "select * from course_pages_content_exceptions where courseHomePageId=?";
			$sql .= " and contentType=? and contentTypeId=?";
			
			$resultSet = $dbHandle->query($sql, array($courseHomePageId, $contentType, $contentId))->result_array();
			
			if(empty($resultSet)){
				$resultSet['error'] = "No Record Exist";
				return $resultSet;
			}else{
				$sql = "UPDATE course_pages_content_exceptions set exceptionFlag=?,modifiedAt=NOW(),modifiedBy=?";
				$sql .= " where courseHomePageId=? and contentType=? and contentTypeId=?";
			
				$dbHandle->query($sql, array($flagType, $userId, $courseHomePageId, $contentType, $contentId));
				$result['restrictedId'] = $dbHandle->insert_id();
				return $result;
			}
			
		}else{
			$sql = "select * from course_pages_content_exceptions where courseHomePageId=?";
			$sql .= " and exceptionFlag !='nullify' and contentType=? and contentTypeId=?";
			
			$resultSet = $dbHandle->query($sql, array($courseHomePageId, $contentType, $contentId))->result_array();
			
			if(!empty($resultSet)){
				$result['error'] = "Record Already Exist";
				return $result;
			}
			
			$sql = "insert into course_pages_content_exceptions(courseHomePageId,contentType,contentTypeId,exceptionFlag,modifiedAt,modifiedBy) ";
			$sql .= "values(?,?,?,?,NOW(),?)";
			
			$dbHandle->query($sql, array($courseHomePageId, $contentType, $contentId, $flagType, $userId));
			$result['restrictedId'] = $dbHandle->insert_id();
			return $result;
		}
		
	}

	/**
	 * Method to add featured article(table 'coursepage_featured_articles')
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-02-23
	 * @param  Array     $data
	 */
	function addFeaturedArticle($data){
		$dbHandle = $this->_getDbHandle('write');

		$insertData = array(
							'courseHomePageId' 	=> $data['courseHomePageId'],
							'from_date' 		=> $data['from_date'],
							'to_date' 			=> $data['to_date'],
							'article_id' 		=> $data['article_id'],
							'created_by' 		=> $data['created_by'],
							'status' 			=> $data['status'],
							'creation_date' 	=> $data['creation_date']
						);
						
		$dbHandle->insert('coursepage_featured_articles',$insertData);
	}

	function getFeaturedArticles($courseHomePageId){

		if(empty($courseHomePageId))
			return;

		$dbHandle = $this->_getDbHandle('read');

		$queryCmd = "SELECT *
				     FROM 
				     coursepage_featured_articles
				     WHERE 
				     status = 'live'
				     AND courseHomePageId = ?
				     ORDER BY from_date";

		$queryRes = $dbHandle->query($queryCmd,array($courseHomePageId))->result_array();

		return $queryRes;
	}

	function deleteFeaturedArticle($id){
		$dbHandle = $this->_getDbHandle('write');
		$udata    = array( 'status' => 'delete', 'modification_date' => date('Y-m-d H:i:s') );

		$dbHandle->where('id', $id);
		// update
		$dbHandle->update('coursepage_featured_articles', $udata);
	}
        
        function getCourseHomePageBaseData(){
                $dbHandle = $this->_getDbHandle('read');
                $dbHandle->select('chp.course_home_id as courseHomeId,chp.course_home_name as Name,
                        chp.tag_id as tagId,
                        chp.oldsubcategory_id as oldsbCatId,chp.stream_id as streamId,
                        chp.substream_id as substreamId,
                        chp.base_course_id as baseCourseId,
                        chp.education_type as educationType,
                        chp.delivery_method as deliveryMethod,
                        chp.seo_url as url,
                        chp.old_url_pattern as oldUrl,
                        b.name as baseCourseName,
                        b.is_popular as isPopular');
                $dbHandle->from('courseHomePages chp');
                $dbHandle->join('base_courses b','b.base_course_id=chp.base_course_id AND b.`status`="live"','left');
                $homePageArray= $dbHandle->get()->result_array();
                foreach ($homePageArray as $key=>$homePageRow){
                    $homePageArray[$key]['url']=SHIKSHA_HOME.$homePageArray[$key]['url'];
                }
                return $homePageArray;
        }
}
