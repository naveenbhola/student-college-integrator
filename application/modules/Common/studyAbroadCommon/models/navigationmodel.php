<?php
class NavigationModel extends MY_Model{

    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }
    }

   public function getNavigationData(){
        $this->initiateModel('read');
        $this->dbHandle->select('group,main_section_heading,sub_section_heading,links_text,links_url,order');
        $this->dbHandle->from('sa_gnb');
        $this->dbHandle->where('status','live');
        $this->dbHandle->order_by('order','asc');
        $result = $this->dbHandle->get()->result_array();
        return $result;
   }
   public function getFooterNavigationData(){
        $this->initiateModel('read');
        $this->dbHandle->select('group,links_text,links_url,order');
        $this->dbHandle->from('sa_footer');
        $this->dbHandle->where('status','live');
        $this->dbHandle->order_by('order','asc');
        $result = $this->dbHandle->get()->result_array();
        return $result;
   }

}
?>