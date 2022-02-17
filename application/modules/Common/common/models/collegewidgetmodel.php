<?php

class CollegeWidgetModel extends MY_Model
{
    public function getTwoRandomTiles()
    {
        $cacheLib = $this->load->library('cacheLib');
        $key = "getTwoRandomTiles";
        $res = $cacheLib->get($key);
        if($res == 'ERROR_READING_CACHE'){ 
            $this->initiateModel('read');
            $db_query = $this->dbHandle->query("select seoUrl,title from CollegeReview_Tile where seoUrl != '' and status='live' order by tileOrder");
            $result = $db_query->result_array();
            $cacheLib->store($key,$result,10800);
            return $result;        
        }else{
            return $res;
        }
    }
    
    private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
   
}