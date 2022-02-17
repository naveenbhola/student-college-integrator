<?php

	class ChpLibrary{
		function __construct(){
			$this->CI = & get_instance();
			$this->CI->load->config("chp/chpAPIs");
			$this->ChpClient = $this->CI->load->library('chp/ChpClient');
		}
		/**
		Input Request format
		  array( 0 => array('streamId' => 1,'substreamId' => 2, 'specializationId' => 3, 'basecourseId' => 4, 'educationtypeId' => 0, 'deliverymethodId' => 0,'credentialId' => 0))
		*/
		function getChpUrlBasedOnHierarchies($hierachyRequest){
			if(!empty($hierachyRequest)){
		        $apiUrl = $this->CI->config->item('CHP_URL_BY_HIERACHIES');
		        $result = $this->ChpClient->makeCURLCall('POST',$apiUrl, json_encode($hierachyRequest));
		        
		        $result = json_decode($result,true);
		        return $result['data'][0]['url'];
			}
			return "";
		}

		    //below function is used for fetch number of pageviews for groupId from Elastic Search
	    function fetchCHPViewCount($durationInDays = 90)
	    {
	        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
	        $clientCon = $ESConnectionLib->getShikshaESServerConnection();

	        //prepare fetch exam page count query

	        $startDate = time();
	        $startDate = date('Y-m-d', strtotime('-'.$durationInDays.' day', $startDate));
	        $startDate .= ' 00:00:00';
	        $startDate = convertDateISTtoUTC($startDate);
	        $startDate = str_replace(" ", "T", $startDate);
	    
	        $elasticQuery['index'] = "shiksha_trafficdata_pageviews_realtime";
	        $elasticQuery['type']  = 'pageview';

	        $elasticQuery['body']['size'] = 0;
	        $elasticQuery['body']['query'] = array();

	        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isStudyAbroad'] = "no";

	        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['pageIdentifier'] = 'CHP';

	        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['range'] = array(
	            'visitTime' => array(
	                'gte' => $startDate
	            )
	        );

	        $elasticQuery['body']['aggs']['pageViews'] = array(
	            'terms' => array(
	                'field'    => 'pageEntityId',
	                'size' => ELASTIC_AGGS_SIZE
	        ));
	        $result = $clientCon->search($elasticQuery);
	        $result = $result['aggregations']['pageViews']['buckets'];
	        $finalArray = array();
	        foreach ($result as $key => $value) {
	            $finalResult[$value['key']] = $value['doc_count'];
	        }
	        return $finalResult;
	    }

	}
?>