<?php
class exampagemodel_gnb extends MY_Model {
  private $dbHandle = '';
  private $dbHandleMode = '';
  
  function __construct() {
    parent::__construct();
    }
    
    private function initiateModel($mode = "write"){
      if($this->dbHandle && $this->dbHandleMode == 'write') {
          return;
      }
      $this->dbHandleMode = $mode;
      $this->dbHandle = NULL;
      if($mode == 'read') {
        $this->dbHandle = $this->getReadHandle();
      } else {
        $this->dbHandle = $this->getWriteHandle();
      }
    }
  function getFeaturedExamsData() {
      return array();
      $this->initiateModel('read');
      $examPageRequest = $this->load->library('examPages/examPageRequest');
      $examListQuery = "SELECT category_name, exam_name FROM exampage_master WHERE status = 'live' and is_featured = 1 order by exam_order asc";
      foreach ($this->dbHandle->query($examListQuery)->result_array() as $key => $value) {
        $res[$value['category_name']][$value['exam_name']]['exam_name'] = $value['exam_name'];
        $examPageRequest->setExamName($value['exam_name']);
        $exam_url = $examPageRequest->getUrl('home');
        $res[$value['category_name']][$value['exam_name']]['url'] = $exam_url['url'];
      }
      return $res;
    }
  }
?>
