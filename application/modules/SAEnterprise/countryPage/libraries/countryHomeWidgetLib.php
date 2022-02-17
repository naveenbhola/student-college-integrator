<?php
class countryHomeWidgetLib
{
    private $CI;
    private $countryHomeWidgetModel;
    
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('countryPage/countryhomewidgetmodel');
        $this->countryHomeWidgetModel = new countryhomewidgetmodel();
    }
    
    public function getWidgetDetailByCountryId($countryId){
      $result = array();
      if($countryId >0){
        $data = $this->countryHomeWidgetModel->getWidgetDetailByCountryId($countryId);
        if(count($data)>0){
            $result = reset($data);
        }else{
            $result['error'] = 'Please provide valid country id';
        }
      }else{
        $result['error'] = 'Please provide valid country id';
      }
      return $result;
    }
    
    public function saveCountryHomeWidgets($postData)
    {
        $return  =array();
        if(!empty($postData['widgetDetails']) && $postData['countryId']>2)
        {
         $data = $this->countryHomeWidgetModel->saveCountryHomeWidgets($postData);
        }else{
            $return['error'] = "Invalid country Id";
        }
        return $return;
    }
    
    public function getCountryHomeWidgetTableData($params){
        $tableResult    = $this->countryHomeWidgetModel->getCountryHomeTableData($params);
        //_p($tableResult);
        $totalCount     = $tableResult['totalCount'];
        $userInfo       = $tableResult['userInfo'];
        $tableResult    = $tableResult['rows'];
        
        
        return array( 'tableResult' => $tableResult,
                      'userInfo'    => $userInfo,
                      'totalCount' => $totalCount
                    );
        
    }
}
