<?php

class ESIndexingLib
{
    private $CI;
    private $clientConn;

    function __construct()
    {
        $this->CI = & get_instance();
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn = $ESConnectionLib->getESServerConnectionWithCredentials(); 
    
    }
    
    public function setSearchCriteria($searchCriteria){
        $this->searchCriteria = $searchCriteria;
    }

    private function _init(){

       
    }
    public function indexDataToElastic($indexData,$indexName,$indexType, $doc_id=''){
        $this->_init();

        foreach ($indexData as $data) {
            $index_array = array('index' => array(
                                            '_index' => $indexName,
                                            '_type' => $indexType
                                        )
                                    );
            if($data['index_id'] !=''){
                $index_array['index']['_id'] = $data['index_id'];
                unset($data['index_id']);
            }

            $params['body'][] = $index_array;

            $params['body'][] = $data;
        }
       
        
        $response = $this->clientConn->bulk($params);
        
        return $response;
    } 
   
     function deleteElasticDocs($id){

        $elasticQuery = array();
        $elasticQuery['index'] = 'lead_tracking';
        $elasticQuery['type'] = 'lead_debug';
        $elasticQuery['id'] = $id;
        
        $result = $this->clientConn->delete($elasticQuery);
    }
}
