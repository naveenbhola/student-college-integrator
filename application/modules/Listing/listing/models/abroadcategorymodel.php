<?php
class AbroadCategoryModel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
        
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
    
    
} ?>