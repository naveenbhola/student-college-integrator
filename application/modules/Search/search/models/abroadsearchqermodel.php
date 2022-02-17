<?php

class abroadsearchqermodel extends MY_Model {
	private $dbHandle = '';
	private $shikshaDbHandle = '';
   
    function __construct(){
		parent::__construct('qerSA');
    }
	
	private function initiateModel($mode = "write"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function insertQERTableData($tableData)
	{
		$this->initiateModel('write');
		// start the transaction
	    $this->dbHandle->trans_start();
		// loop across all tables available in the table data array & perform inserts
		$tableDataForBatchInsert = array();
		foreach($tableData as $tableName=>$rows)
		{
			$tableDataForBatchInsert[$tableName] =array();
			foreach($rows as $row)
			{
				$rowForInsert  = array();
				$rowForInsert['ldb_id']				= $row['id'];
				$rowForInsert['local_full_name'] 	= $row['name'];
				$rowForInsert['ldb_full_name'] 	= $row['name'];
				if($row['name']['more_popular_abb']) // available for universities
				{
					$rowForInsert['more_popular_abb'] 	= $row['more_popular_abb'];
				}
					
				if($row['url']!='')
				{
					$rowForInsert['url'] = $row['url'];
				}
				if($row['variant_csv']!='')
				{
					$rowForInsert['variant_csv'] = $row['variant_csv'];
				}
				if($row['more_popular_abb']!='')
				{
					$rowForInsert['more_popular_abb'] = $row['more_popular_abb'];
				}
				// add to batch
				$tableDataForBatchInsert[$tableName][] = $rowForInsert;
			}
		}
		// insert /update if duplicate key
		foreach($tableDataForBatchInsert as $tableName => $batchForinsert)
		{
			$this->insertBatchUpdateOnDuplicate($tableName, $batchForinsert,true);
		}
		//Transaction Complete
	    $this->dbHandle->trans_complete();
		
	    //Check Transaction Status
	    if ($this->dbHandle->trans_status() === FALSE) {
		    throw new Exception('Transaction Failed');
	    }
	    return true;
	}
	/*
	 *  this function inserts rows in a batch similar to CI 's insert_batch and updates if duplicate key exists
	 *  @params: tablename , rows to be inserted in a batch
	 */
	public function insertBatchUpdateOnDuplicate($tableName, $batchForinsert, $isTransactionActive = false)
	{  //echo "table is ".$tableName;
		if(!$isTransactionActive)
		{ // get the write DB handle
			$this->initiateModel("write");
			$this->dbHandle->trans_start();
		}
		$fieldNames = array_keys($batchForinsert[0]);
		foreach($batchForinsert as $row)
		{
			$updateStr = $valueStr = "";
			foreach($row as $columnName => $column)
			{
				$updateStr 	.= $columnName." = '".$this->dbHandle->escape_str($column)."',";
				$valueStr 		.= "'".$this->dbHandle->escape_str($column)."',";
			}
			$updateStr = rtrim($updateStr,",");
			$valueStr   = rtrim($valueStr,",");
			$sql  = "INSERT INTO ".$this->dbHandle->escape_str($tableName)." (".implode(', ',$fieldNames).") ";
			$sql .= "VALUES (".$valueStr.") ";
			$sql .= "ON DUPLICATE KEY UPDATE ".$updateStr.";";
			//echo "<br>QRY:: ".$sql;
			$query = $this->dbHandle->query($sql);
			if($query === true){
				// keep a count or something
			}
		}
		// commit transaction only if no transaction is already active
		if(!$isTransactionActive)
		{
			$this->dbHandle->trans_complete();
			if ($this->dbHandle->trans_status() === FALSE) {
				throw new Exception('Transaction Failed');
			}
		}
	}
	/*
	 * delete records from QER DB 
	 */
	public function deleteFromQERTableData($dataForDeletion)
	{
		$this->initiateModel('write');
		// start the transaction
	    $this->dbHandle->trans_start();
		foreach($dataForDeletion as $tableName=>$rows)
		{ echo "<br>deleting from ".$tableName;
			if(count($rows)>0){
				$this->dbHandle->where_in('ldb_id',array_map(function($a){return $a['id'];},$rows));
				$this->dbHandle->delete($tableName);
				//echo "<br>".$this->dbHandle->last_query();
			}
		}
		//Check Transaction Status
	    if ($this->dbHandle->trans_status() === FALSE) {
		    throw new Exception('Transaction Failed');
	    }
	    return true;
	}
}