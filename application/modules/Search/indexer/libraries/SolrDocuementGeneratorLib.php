<?php class SolrDocuementGeneratorLib {
	
	private $_CI;

	public function __construct()
	{
		$this->_CI = & get_instance();
        $this->isFullIndexing = false;
        $this->updateFlag = false;

        $this->_CI->load->config('indexer/nationalIndexerConfig');

        $this->_CI->load->library('indexer/SolrServerLib');
        $this->solrServerLib = new SolrServerLib;

        $this->_CI->load->library('indexer/aggregators/CourseDataAggregator');
        $this->courseDataAggregator = new CourseDataAggregator;
	}

    public function generateDocumenteAndIndex($courseId, $courseData,$instituteData,$indexingEntity, $fieldsArray, $extraData){

        // Check Whether CASE of full Indexing ot NOT and set the property
        if(empty($fieldsArray)){
            $this->isFullIndexing = true;
	}else{
	   $this->isFullIndexing = false;
	}

        // Validation
        if($this->isFullIndexing && (empty($courseData))) return;

        // Call The Function to get the Document for isFullIndexing

        if($this->isFullIndexing){
            $this->solrServerLib->deleteDocuments('course',$courseId);

            // Since Mandatory sections
            if(isset($courseData['location']) && isset($courseData['course_type_information'])){
                $response = $this->_createFullIndexingDocuments($courseData, $instituteData);    
            }
            $this->solrServerLib->softCommitChanges();
            $this->updateFlag = false;            
        }else{
            $this->updateFlag = true;
            $response = $this->_createPartialIndexingDocuments($courseData, $instituteData, $indexingEntity);
        }
        return $response;
    }

    /**
    * Document Generator Wrapper for FullIndexing
    *
    */

    private function _createFullIndexingDocuments($courseData, $instituteData){
        $count = 0;

        $indexDataList = array();
        foreach ($courseData['location'] as $courseLocationId => $locationData) {
            foreach ($courseData['course_type_information'] as $courseTypeInformationKey => $hierarchyData) {

                $count++;
                // Keep Adding Documents to the Array
                $indexDataList[] = $this->_generateDocumentForIndexing($courseData, $instituteData, $courseLocationId,$courseTypeInformationKey);

                // If Array Size equals the MAX BATCH , Size, send the Indexing SOlr Call
                if($count == INDEXING_BATCH_SIZE){
                    $indexResponse = $this->solrServerLib->indexFinalData($indexDataList);
                    $count = 0;
                    $indexDataList = array();
                }
            }
        }

        // Check at end, whether any documents are left for indexing
        if(!empty($indexDataList)){
            $indexResponse = $this->solrServerLib->indexFinalData($indexDataList);
        }

        return $indexResponse;
    }

    /**
    * Document Generator Wrapper for Partial Indexing
    */
    private function _createPartialIndexingDocuments($courseData, $instituteData, $indexingEntity){

       //  Generate the Condition for fetching The Documents Ids to Index

        // Generate the condition
       $solrUniqueIdConditions = array();
       if(isset($indexingEntity['course'])){
            $solrUniqueIdConditions['nl_course_id'] = $indexingEntity['course'];
       }else if($indexingEntity['institute']){
            $solrUniqueIdConditions['nl_institute_id'] = $indexingEntity['institute'];
       }


       // Fetch the Ids
       if(!empty($solrUniqueIdConditions)){
            $uniqueIdList = $this->solrServerLib->getUniqueIdsToUpdate($solrUniqueIdConditions);
            if(!empty($uniqueIdList)){
                $indexData = array();
                $count = 0;
                // For each IDs, Generate the Document & Index on batch size

                if(!empty($instituteData['popularity'])){
                    $popularityData = $instituteData['popularity'];
                    unset($instituteData['popularity']);
                }
                foreach ($uniqueIdList as $uniqueId) {
                    $uniqueIdData = $this->solrServerLib->parseUniqueKey(array($uniqueId));
                  
                    if(!empty($popularityData)) {
                        $typeInfo = array();
                        $tempInfo['nl_stream_id'] = $uniqueIdData[$uniqueId]['stream_id'];
                        $tempInfo['nl_substream_id'] = $uniqueIdData[$uniqueId]['substream_id'];
                        $tempInfo['nl_base_course_id'] = $uniqueIdData[$uniqueId]['base_course_id'];
                        $typeInfo[] = $tempInfo;
                        $resultData = $this->courseDataAggregator->popularityData($typeInfo,$popularityData);
                        $courseData['popularity'] = $resultData[0];
                    }

                    $indexDataList[]  = $this->_generateDocumentForIndexing($courseData, $instituteData,$uniqueIdData[$uniqueId]['course_location_id'],null,$uniqueId);
                    $count++;

                    // Index Call on batch size
                    if($count == INDEXING_BATCH_SIZE){
                        $indexResponse = $this->solrServerLib->indexFinalData($indexDataList,true);
                        $count = 0;
                        $indexDataList = array();
                    }
                }
                
                if(!empty($indexDataList)){
                    $indexResponse = $this->solrServerLib->indexFinalData($indexDataList,true);
                }                
            }
            return $indexResponse;
       }
    }

    /**
    * Club all the data together & return the final Document
    */
    private function _generateDocumentForIndexing($courseData, $instituteData, $courseLocationId=null, $courseTypeInformationKey=null,$uniqueKey=null){

        $indexingData = $this->_clubCompleteData($courseData, $instituteData, $courseLocationId, $courseTypeInformationKey,$uniqueKey);
        $document = $this->_generateDocument($indexingData);
        return $document;
    }

    /**
    * Club all the Data Together in one Array
    */
    private function _clubCompleteData($courseData, $instituteData,$courseLocationId=null, $courseTypeInformationKey=null,$uniqueKey=null){
        $indexingData = array();
        
        // Institute Data
        foreach ($instituteData as $key => $value) {
            if(!empty($value)){
                $indexingData['institute_'.$key] = $value;
            }
        }

        // Course Data
        foreach ($courseData as $key => $value) {
            if(!empty($value)){
                switch ($key) {
                    case 'location':
                    case 'fees':
                        if(!empty($value[$courseLocationId])){
                            $indexingData['course_'.$key] = $value[$courseLocationId];    
                        }else{
                            $indexingData['course_'.$key] = null;    
                        }
                        break;

                    case 'course_type_information':
                        if(!empty($value[$courseTypeInformationKey])){
                            $indexingData['course_'.$key] = $value[$courseTypeInformationKey];    
                        }
                        break;
                    
                    default:
                        $indexingData['course_'.$key] = $value;
                        break;
                }
                
            }
        }

        
        $indexingData['facetype'] = "course";

        if($uniqueKey == null){
            $data['institute_id'] = $indexingData['institute_basic']['nl_institute_id'];
            $data['course_id'] = $indexingData['course_basic']['nl_course_id'];
            $data['stream_id'] = $indexingData['course_course_type_information']['nl_stream_id'];
            $data['substream_id'] = $indexingData['course_course_type_information']['nl_substream_id'];
            $data['specialization_id'] = $indexingData['course_course_type_information']['nl_specialization_id'];
            $data['base_course_id'] = $indexingData['course_course_type_information']['nl_base_course_id'];
            $data['course_location_id'] = $courseLocationId;
            $indexingData['unique_id'] = $this->solrServerLib->generateUniqueKey($data);
        }else{
            $indexingData['unique_id'] = $uniqueKey;
        }
        
        return $indexingData;
    }


    /**
    * Generate Document Array from Clubbed Data
    */ 
    private function _generateDocument($indexingData){
        // Create Final Document
        $document = array();
        foreach ($indexingData as $key => $individualSections) {
            // If Array, iterate the array
            if(is_array($individualSections)){
                foreach ($individualSections as $schemaKey => $schemaValue) {
                    $document[$schemaKey] = $schemaValue;
                }    
            }else{ // If not array, directly add it
                $document[$key] = $individualSections;
            }
            
        }
        return $document;
    }
	
}


?>
