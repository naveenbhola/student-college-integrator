<?php
/*
    Model for database related operations related to Tags
    Following is the example this model can be used in the server controllers.
    $this->load->model('TagsModel');
    $this->TagsModel->getTotalQnACountForCriteria('149,12,11,3,8,5,10',$dbHandle);
*/

class TagsModel extends MY_Model {
        private $dbHandle = '';
        private $CI;
        function __construct(){
                parent::__construct('AnA');
                $this->CI = &get_instance();
                $this->CI->load->helper('messageBoard/ana');
        }

        private function initiateModel($operation='read'){
                $appId = 1;
                if($operation=='read'){
                        $this->dbHandle = $this->getReadHandle();
                }
                else{
                        $this->dbHandle = $this->getWriteHandle();
                }
        }

        function getUnansweredQuestionCount($tagId){
                $this->initiateModel();
	        $sql = "SELECT count(*) as questionCount
                FROM messageTable m, tags_content_mapping t 
                WHERE m.msgId = t.content_id AND t.status = 'live' AND m.status IN ('live','closed') AND
                t.content_type = 'question' AND t.tag_id = ? AND m.parentId = 0 AND m.fromOthers='user' AND m.msgCount = 0";
            	$rows = $this->dbHandle->query($sql, array($tagId))->result_array();
                return $rows[0]['questionCount'];
        }


}
