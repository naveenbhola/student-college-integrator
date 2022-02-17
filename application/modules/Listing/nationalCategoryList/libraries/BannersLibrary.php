<?php


class BannersLibrary
{
    function __construct() {
        $this->CI =& get_instance();
        $this->categoryProductModel = $this->CI->load->model('nationalCategoryList/categoryproductmodel');
        $this->redisLib = PredisLibrary::getInstance();
    }


    function findCTPGShoskele($instituteId = NULL,$criteria, $cityFromRequest, $stateFromRequest){
        if(empty($instituteId)) return;
        // $criteria = $request->getAppliedFilters();

        $result = $this->categoryProductModel->findShoskeleBannersForInstitute($instituteId, $criteria);
        $filteredShoshkele = array();
        if(!empty($result)){
            $filteredShoshkele = $this->_getFilteredShoskele($result, $criteria,$cityFromRequest, $stateFromRequest);
        }
        return $filteredShoshkele;
    }

    // Check if any shoskele on city on which ctpg exists,if not check on state on which ctpg exists
        // if not found on any, check on any city in filters, if found return first else 
        //check on any state found in filters and return first
    private function _getFilteredShoskele($result, $appliedFilters, $cityFromRequest,$stateFromRequest){

        $possibleResults = array();
        $confirmedResults = array();

        $cityFromFilters = $appliedFilters['city'];
        $stateFromFilters =$appliedFilters['state'];
        
        foreach ($result as $key => $value) {
            if($value['city_id'] == $cityFromRequest && !empty($cityFromRequest)){
                $confirmedResults = $value;
                break;
            }else if(in_array($value['city_id'], $cityFromFilters) && !empty($cityFromFilters)){
                $possibleResults[] = $value;
            }
        }

        if(empty($confirmedResults)){
            foreach ($result as $key => $value) {
                if($value['state_id'] == $stateFromRequest && !empty($stateFromRequest)){
                    $confirmedResults = $value;
                    break;
                }else if(in_array($value['state_id'], $stateFromFilters) && !empty($stateFromFilters)){
                    $possibleResults[] = $value;
                }
            }            
        }

        if(!empty($confirmedResults)){
            return $confirmedResults;
        }else{
            return reset($possibleResults);
        }

    }

    

}