<?php
class RecatCommonLib{
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
    }
    
    public function getRegistrationFormPopulationDataByParams($params){
        if(empty($params)){
            return array('customFields' => array(), 'customFieldValueSource' => array());
        }
        $customFields = array();
        $customFieldValueSource = array();
        if(isset($params['stream'])){
            $customFields['stream'] = array('value' => $params['stream'],'hidden'=>1,'disabled'=>0);
            $customFieldValueSource[$params['stream']] = array();
            $customFields['flow'] = array('value' => 'specialization','hidden'=>1,'disabled'=>0);
            if(isset($params['substream'])){
                $customFields['subStreamSpec']['value'][$params['substream']] = array();
                $customFieldValueSource[$params['stream']]['subStreamSpecs']['value'][$params['substream']] = array();
                $customFields['subStreamSpec']['hidden'] = 0;
                $customFields['subStreamSpec']['disabled'] = 0;
                if(isset($params['specialization'])){
                    $customFields['subStreamSpec']['value'][$params['substream']][] = $params['specialization'];
                    $customFieldValueSource[$params['stream']]['subStreamSpecs']['value'][$params['substream']][] = $params['specialization'];
                    $customFields['subStreamSpec']['hidden'] = 1;
                }else{
                    $this->CI->load->builder('ListingBaseBuilder','listingBase');
                    $builder = new ListingBaseBuilder;
                    $repo = $builder->getHierarchyRepository();
                    $substreamData = $repo->getSpecializationTreeByStreamSubstreamId($params['stream'], array($params['substream']));
                    $specializationIds = array_keys($substreamData[$params['stream']]['substreams'][$params['substream']]['specializations']);
                    foreach($specializationIds as $id){
                        $customFields['subStreamSpec']['value'][$params['substream']][] = $id;
                        $customFieldValueSource[$params['stream']]['subStreamSpecs']['value'][$params['substream']][] = $id;
                    }
                    if(count($customFieldValueSource[$params['stream']]['subStreamSpecs']['value']) <=1){   // If there is upto 1 substream (including ungrouped)
                        if(count($customFieldValueSource[$params['stream']]['subStreamSpecs']['value'][$params['substream']]) == 1){ // If there is 1 specialization within the singular substream
                            $customFields['subStreamSpec']['value'] = $customFieldValueSource[$params['stream']]['subStreamSpecs']['value'];
                        }
                        $customFields['subStreamSpec']['hidden'] = 1;
                    }
                    foreach($customFields['subStreamSpec']['value'] as $substreamId => $specializationIds){
                        if(empty($specializationIds)){
                            $customFields['subStreamSpec']['value'][$substreamId][] = 0;
                        }
                    }
                    foreach($customFieldValueSource as $streamId=>$streamData){
                        foreach($streamData['subStreamSpecs'] as $substreamId => $specializations){
                            if(empty($specializations)){
                                $customFieldValueSource[$streamId]['subStreamSpecs'][$substreamId][] = 0;
                            }
                        }
                    }
                }
            }else{
                if(isset($params['specialization'])){
                    $customFields['subStreamSpec']['value']['ungrouped'][] = $params['specialization'];
                    $customFields['subStreamSpec']['hidden'] = 1;
                    $customFields['subStreamSpec']['disabled'] = 0;
                    $customFieldValueSource[$params['stream']]['subStreamSpecs']['value']['ungrouped'][] = $params['specialization'];
                }else{
                    $customFields['subStreamSpec']['hidden'] = 0;
                    $customFields['subStreamSpec']['disabled'] = 0;
                    $this->CI->load->builder('ListingBaseBuilder','listingBase');
                    $builder = new ListingBaseBuilder;
                    $repo = $builder->getHierarchyRepository();
                    $streamData = $repo->getSubstreamSpecializationByStreamId($params['stream']);
                    $custData = $this->_preformatStreamDataForRegistrationFormPopulation($streamData);
                    foreach($custData as $streamId => $data){
                        foreach($data as $substreamId => $specializationData){
                            $customFieldValueSource[$params['stream']]['subStreamSpecs']['value'][$substreamId] = array_keys($specializationData);
                            // $customFields['subStreamSpec']['value'][$substreamId] = array_keys($specializationData);
                        }
                    }
                    if(count($customFieldValueSource[$params['stream']]['subStreamSpecs']['value']) == 0){
                        $customFields['subStreamSpec']['hidden'] = 1;
                        $customFields['subStreamSpec']['disabled'] = 1;
                    }
                    if(count($customFieldValueSource[$params['stream']]['subStreamSpecs']['value']) ==1){   //Singular Substream
                        $specs = reset($customFieldValueSource[$params['stream']]['subStreamSpecs']['value']);
                        if(count($specs) == 1){
                            $customFields['subStreamSpec']['hidden'] = 1;
                            $customFields['subStreamSpec']['disabled'] = 0;
                            foreach($specs as $key=>$value){ //will only loop once, need key and value both
                                $customFields['subStreamSpec']['value'][$key] = array($value);
                            }
                        }
                    }
                    foreach($customFields['subStreamSpec']['value'] as $substreamId => $specializationIds){
                        if(empty($specializationIds)){
                            $customFields['subStreamSpec']['value'][$substreamId][] = 0;
                        }
                    }
                    foreach($customFieldValueSource as $streamId=>$streamData){
                        foreach($streamData['subStreamSpecs']['value'] as $substreamId => $specializations){
                            if(empty($specializations) && $substreamId != "ungrouped"){
                                $customFieldValueSource[$streamId]['subStreamSpecs']['value'][$substreamId][] = 0;
                            }
                        }
                    }
                }
            }
            if(isset($params['baseCourseId'])){
                $customFields['baseCourses']['value'] = array($params['baseCourseId']);
                $customFields['baseCourses']['hidden'] = 1;
                $customFields['baseCourses']['disabled'] = 0;
                $customFieldValueSource[$params['stream']]['baseCourses'][] = $params['baseCourseId'];
                if($params['stream'] == 8 && in_array($params['baseCourseId'],array(103,33))){
                    $customFields['subStreamSpec']['hidden'] = 1;
                    $customFields['subStreamSpec']['disabled'] = 1;
                }
            }
        }elseif(isset($params['baseCourseId'])){
            $customFields['baseCourses']['value'] = array($params['baseCourseId']);
            $customFields['baseCourses']['hidden'] = 1;
            $customFields['baseCourses']['disabled'] = 0;
            $customFields['flow'] = array('value' => 'course','hidden'=>1,'disabled'=>0);
            $this->CI->load->builder('ListingBaseBuilder','listingBase');
            $builder = new ListingBaseBuilder;
            $repo = $builder->getBaseCourseRepository();
            $data = $repo->getBaseEntityTreeByBaseCourseIds(array($params['baseCourseId']));
            foreach($data as $streamId => $streamData){
                if(count($data) == 1){
                    $customFields['stream'] = array('value' => $streamId,'hidden' => 1, 'disabled'=>0);
                }
                $customFieldValueSource[$streamId]['baseCourses'] = array($params['baseCourseId']);
                foreach($streamData['substreams'] as $substreamId => $substreamData){
                    if(count($streamData['substreams']) == 1 && count($substreamData['specializations']) <= 1 && isset($customFields['stream'])){
                        $customFields['subStreamSpec'] = array(
                            'value'=>array($substreamId=>array_keys($substreamData['specializations'])),
                            'hidden' => 1,
                            'disabled' => 0
                        );
                    }
                    $customFieldValueSource[$streamId]['subStreamSpecs'][$substreamId] = array_keys($substreamData['specializations']);
                }
                if(!empty($streamData['specializations'])){
                    $customFieldValueSource[$streamId]['subStreamSpecs']['ungrouped'] = array_keys($streamData['specializations']);

                    if(count($customFieldValueSource[$streamId]['subStreamSpecs']) == 1 && count($streamData['specializations']) == 1 && isset($customFields['stream'])){
                        $customFields['subStreamSpec'] = array(
                            'value' => array('ungrouped' => array_keys($streamData['specializations'])),
                            'hidden' => 1,
                            'disabled' => 0
                        );
                    }
                }
                foreach($customFields['subStreamSpec']['value'] as $substreamId => $specializationIds){
                    if(empty($specializationIds)){
                        $customFields['subStreamSpec']['value'][$substreamId][] = 0;
                    }
                }
                foreach($customFieldValueSource as $streamId=>$streamData){
                    foreach($streamData['subStreamSpecs'] as $substreamId => $specializations){
                        if(empty($specializations)){
                            $customFieldValueSource[$streamId]['subStreamSpecs'][$substreamId][] = 0;
                        }
                    }
                }
            }
        }
        if(isset($params['educationType'])){
            $customFields['educationType']['value'][] = $params['educationType'];
            $customFields['educationType']['hidden'] = 1;
            $customFields['educationType']['disabled'] = 0;
        }
        if(isset($params['deliveryMethod'])){
            $customFields['educationType']['value'][] = $params['deliveryMethod'];
            $customFields['educationType']['hidden'] = 1;
            $customFields['educationType']['disabled'] = 0;
        }
        foreach($customFieldValueSource as $streamId => $streamData){
            if(empty($streamData['subStreamSpecs'])){
                $customFieldValueSource[$streamId]['subStreamSpecs'] = array('0' => 0);
            }
        }
        $finalResult = array('customFields' => $customFields, 'customFieldValueSource' => $customFieldValueSource);
        return $finalResult;
    }

    private function _preformatStreamDataForRegistrationFormPopulation($streamData){
        $res = array();
        foreach($streamData as $streamId => $data){
            $res[$streamId] = array();
            $subData = array();
            foreach($data['substreams'] as $substreamId => $ssdata){
                $res[$streamId][$substreamId] = array();
                foreach($ssdata['specializations'] as $specializationId => $spdata){
                    $res[$streamId][$substreamId][$specializationId] = $specializationId;
                }
            }
            foreach($data['specializations'] as $specializationId => $spdata){
                $res[$streamId]['ungrouped'][$specializationId] = $specializationId; 
            }
        }
        return $res;
    }
}

?>