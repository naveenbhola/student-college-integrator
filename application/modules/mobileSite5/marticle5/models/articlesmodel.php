<?php
class articlesmodel extends MY_Model {
protected function initiateModel($operation='read'){
		$appId = 1;	
		$this->load->library('OnlineFormConfig');
		$onlineConfig = new OnlineFormConfig();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$onlineConfig->getDbConfig($appID,$dbConfig);
		//$this->dbHandle = $this->load->database($dbConfig,TRUE);

		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}		
	}
        
        //Get subcategory Names which have more than 5 articles in last one year.
        function getSubcategoriesHavingArticles($articlecount = 5, $blogType = '') {
		
		
		$this->initiateModel();
		$queryCmd = "SELECT boardId FROM blogTable WHERE creationDate>DATE_ADD( NOW( ) , INTERVAL -1 YEAR ) AND status = 'live' ";
		
		if($blogType == 'news')
				$queryCmd .= " AND blogType = ".$this->dbHandle->escape($blogType);

		$queryCmd .= " GROUP BY boardId having count(*) >= ".$articlecount;
		$queryRes = $this->dbHandle->query($queryCmd);
		$data = $queryRes->result_array();
		
		$output_array = array();
		
		foreach($data as $row)
			$output_array[] = $row['boardId'];
			
		return $output_array;
        }
	
	
	// Get subcategory Name on the basis of subcatID
        function getSubcategoryName($subcategoryId) {
		
		$this->initiateModel();
		
		// prepare query 
		$queryCmd = "select name from categoryBoardTable where boardId = ? ";
		
		// get results
		$queryRes = $this->dbHandle->query($queryCmd, array($subcategoryId));
		$subcatName = $queryRes->result_array();
		
		return $subcatName[0]['name'];
        }
}
