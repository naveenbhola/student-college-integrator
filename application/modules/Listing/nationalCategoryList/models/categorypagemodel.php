<?php

/**
 * Class categorypagemodel
 *
 */
class categorypagemodel extends  MY_Model
{

    private function initiateModel($mode = "write") {
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

    public function getCategoryPageNickName($criteriaArray = array()){
        if(empty($criteriaArray)) return;

        $this->initiateModel('read');
        
        $sql = "SELECT * from category_subscription_criteria where ";

        $params = array();
        
        $where[] = 'stream_id = ?';
        $params[] = $criteriaArray['stream_id'];

        $where[] = 'substream_id = ?';
        $params[] = $criteriaArray['substream_id'];

        $where[] = 'base_course_id = ?';
        $params[] = $criteriaArray['base_course_id'];

        $where[] = 'credential = ?';
        $params[] = $criteriaArray['credential'];

        $where[] = 'education_type = ?';
        $params[] = $criteriaArray['education_type'];

        $where[] = 'delivery_method = ?';
        $params[] = $criteriaArray['delivery_method'];
        
        $whereClause = implode(' AND ', $where);

        $sql = $sql.$whereClause;
        
        $query = $this->dbHandle->query($sql, $params);
        
        $result = $query->result_array();
        return $result;
    }

    function getCategoryPageRelatedLinks($pageData){
        if(empty($pageData)) {
            return;
        }
        $this->initiateModel('read');

        $params = array();
        $selectStmt ="SELECT DISTINCT".
                      " url,".
                      " heading_desktop,".
                      " heading_mobile,".
                      " id";

        $fromStmt   = 'category_page_seo';
        $whereAndStmt  = "status = 'live' AND result_count > 0 AND id != ?";
        $params[] = $pageData['excludePageId'];

        if(!empty($pageData['AND'])){
            foreach ($pageData['AND'] as $key => $val) {                
                if(is_array($val)){
                    $whereAndStmt .= " AND ".$key." in (?) ";
                }else if($key == 'topCities'){
                    $whereAndStmt .= " AND city_id > ?";
                }else{
                    $whereAndStmt .= " AND ".$key." = ?";
                }
                $params[] = $val;
            }
        }

        $orCounter = 0;
        $whereORStmt = '';
        if(!empty($pageData['OR'])){
            foreach ($pageData['OR'] as $key => $val) {
                if($orCounter != 0){ $whereORStmt .= " OR "; }
                $whereORStmt .=  $key." != 0";
                $orCounter++;
            }
        }

        $orderByStmt = " ORDER BY result_count DESC";

        if(!empty($pageData['limit'])) {
            $limitStmt .= " LIMIT ?";
            $params[] = (int) $pageData['limit'];
        }
        
        if($whereORStmt)
            $sql  = $selectStmt. " FROM ". $fromStmt. " WHERE (" .$whereAndStmt .") AND ( ".$whereORStmt." ) ".$orderByStmt." ".$limitStmt;
        else
            $sql  = $selectStmt. " FROM ". $fromStmt. " WHERE ".$whereAndStmt ." ".$orderByStmt." ".$limitStmt;


        //error_log(" CAT InterLinking QUERY \n".$sql);
        $result = $this->db->query($sql, $params)->result_array();
        
        return $result;
    }

    function storeSolrQueryDataInDb($data) {
        $this->initiateModel('write');

        foreach ($data as $key => $value) {
            $insertData[$key]['date'] = $value['Date'];
            $insertData[$key]['referrer'] = $value['referrer'];
            $insertData[$key]['transactionId'] = $value['transactionId'];
            $insertData[$key]['server'] = $value['Server'];
            $insertData[$key]['type'] = $value['Page'];
            $insertData[$key]['solrServer'] = $value['solrServer'];
            $insertData[$key]['collection'] = $value['collection'];
            $insertData[$key]['handler'] = $value['handler'];
            $insertData[$key]['query'] = $value['Query'];
            $insertData[$key]['timeTaken'] = $value['Time taken']; 
        }

        _p($insertData);

        $this->dbHandle->insert_batch('solr_query', $insertData); 
    }

    function getCTPCriteriaByInstituteOrCourse($instituteIds, $courseIds, $virtualCititesMapping) {
        $this->initiateModel('read');
        
        $sql = "select DISTINCT sc.primary_id, st.stream_id, st.substream_id, st.specialization_id, st.base_course, sc.education_type, sc.delivery_method, st.credential, se.exam_id, sl.city_id, sl.state_id from shiksha_courses sc ".
                "inner join shiksha_courses_type_information st on st.course_id = sc.course_id and st.status='live' and st.type='entry' ".
                "left join shiksha_courses_eligibility_exam_score se on se.course_id = sc.course_id and se.status='live' ".
                "inner join shiksha_institutes_locations sl on sl.listing_id = sc.primary_id and sl.status = 'live' ".
                "where sc.status = 'live' ";
        
        $params = array();
        if(!empty($instituteIds) && !empty($courseIds)) {
            $where = " and (sc.primary_id in (?) or sc.course_id in (?)) ";
            $params[] = $instituteIds;
            $params[] = $courseIds;
        }
        elseif(!empty($instituteIds)) {
            $where = " and sc.primary_id in (?) ";
            $params[] = $instituteIds;
        }
        elseif(!empty($courseIds)) {
            $where = " and sc.course_id in (?) ";
            $params[] = $courseIds;
        }

        $sql = $sql.$where;
        $result = $this->db->query($sql, $params)->result_array();
        //_p($this->db->last_query()); die;

        foreach ($result as $key => $value) {
            $criteriaArray[$value['primary_id']]['stream_id'][0] = '0';
            if(!empty($value['stream_id'])) {
                $criteriaArray[$value['primary_id']]['stream_id'][$value['stream_id']] = $value['stream_id'];
            }
            $criteriaArray[$value['primary_id']]['substream_id'][0] = '0';
            if(!empty($value['substream_id'])) {
                $criteriaArray[$value['primary_id']]['substream_id'][$value['substream_id']] = $value['substream_id'];
            }
            $criteriaArray[$value['primary_id']]['specialization_id'][0] = '0';
            if(!empty($value['specialization_id'])) {
                $criteriaArray[$value['primary_id']]['specialization_id'][$value['specialization_id']] = $value['specialization_id'];
            }
            $criteriaArray[$value['primary_id']]['base_course'][0] = '0';
            if(!empty($value['base_course'])) {
                $criteriaArray[$value['primary_id']]['base_course'][$value['base_course']] = $value['base_course'];
            }
            $criteriaArray[$value['primary_id']]['education_type'][0] = '0';
            if(!empty($value['education_type'])) {
                $criteriaArray[$value['primary_id']]['education_type'][$value['education_type']] = $value['education_type'];
            }
            $criteriaArray[$value['primary_id']]['delivery_method'][0] = '0';
            if(!empty($value['delivery_method'])) {
                $criteriaArray[$value['primary_id']]['delivery_method'][$value['delivery_method']] = $value['delivery_method'];
            }
            $criteriaArray[$value['primary_id']]['credential'][0] = '0';
            if(!empty($value['credential'])) {
                $criteriaArray[$value['primary_id']]['credential'][$value['credential']] = $value['credential'];
            }
            $criteriaArray[$value['primary_id']]['exam_id'][0] = '0';
            if(!empty($value['exam_id'])) {
                $criteriaArray[$value['primary_id']]['exam_id'][$value['exam_id']] = $value['exam_id'];
            }
            $criteriaArray[$value['primary_id']]['city_id'][1] = '1';
            if(!empty($value['city_id'])) {
                $criteriaArray[$value['primary_id']]['city_id'][$value['city_id']] = $value['city_id'];
                if($virtualCititesMapping[$value['city_id']]) {
                    $criteriaArray[$value['primary_id']]['city_id'][$virtualCititesMapping[$value['city_id']]] = $virtualCititesMapping[$value['city_id']];
                }
            }
            $criteriaArray[$value['primary_id']]['state_id'][1] = '1';
            if(!empty($value['state_id'])) {
                $criteriaArray[$value['primary_id']]['state_id'][-1] = '-1';
                $criteriaArray[$value['primary_id']]['state_id'][$value['state_id']] = $value['state_id'];
            }
        }
        
        // _p($criteriaArray);
        return $criteriaArray;
    }
}