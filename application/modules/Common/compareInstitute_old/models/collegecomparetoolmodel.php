<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CollegeCompareToolModel extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }
    
    private function init($mode = "write", $module = '')
    {
        if($mode == 'read')
        {
                $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
        } else {
                $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
        }
    }
    
    function getRecentViewedInstitute($session_id)
    {
       $this->init('read');
       $this->dbHandle->select('user_session_info.session_id,user_session_info.user_id,listing_track.id as track_id,listing_track.user_session_id,listing_track.course_id,listing_track.institute_id'); 
       $this->dbHandle->from(user_session_info);
       $this->dbHandle->join('listing_track', 'listing_track.user_session_id = user_session_info.id');
       $this->dbHandle->where('user_session_info.session_id', $session_id);
       $this->dbHandle->order_by("listing_track.id", "desc");
       $this->dbHandle->limit(5);
       $query = $this->dbHandle->get();
       return $query->result();
    }

}
