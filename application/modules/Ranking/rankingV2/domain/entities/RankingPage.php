<?php

class RankingPage {
	
	private $rankingPageId;
	
	private $rankingPageName;
	
	private $publisherId;
	
	private $tupleType;
	
	private $publisherData;
	
	private $created;
	
	private $updated;
	
	private $disclaimer;
        
    private $stream_id;
    
    private $substream_id;
    
    private $specialization_id;
    
    private $education_type;
    
    private $delivery_method;
    
    private $credential;
    
    private $base_course_id;

    private $rankingPageData;
        
    public function getStreamId(){
            return $this->stream_id;
    }
    public function getSubstreamId(){
            return $this->substream_id;
    }
    public function getShikshaSpecializationId(){
            return $this->specialization_id;
    }
    public function getEducationType() {
            return $this->education_type;
    }
    public function getDeliveryMethod() {
            return $this->delivery_method;
    }
    public function getCredential() {
            return $this->credential;
    }
    
    public function getBaseCourseId(){
            return $this->base_course_id;
    }
        
	public function __construct(){
		
	}
	
	public function getId(){
		return $this->rankingPageId;
	}
	
	public function getName(){
		return $this->rankingPageName;
	}
	
	public function getSpecializationId(){
		return $this->specializationId;
	}
	
	
	public function getRankingPageData(){
		return $this->rankingPageData;
	}
	
	public function setRankingPageData($data){
		$this->rankingPageData = $data;
	}
	
	public function getLastUpdatedTime(){
		return $this->updated;
	}
	
	public function getDisclaimer(){
		return $this->disclaimer;
	}
	
	public function __set($property,$value) {
		$this->$property = $value;
	}

	function getPublisherId() {
		return $this->publisherId;
	}

	function getPublisherData() {
		return $this->publisherData;
	}

	function getTupleType() {
		return $this->tupleType;
	}
}	
	