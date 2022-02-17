<?php

class scholarshipCategoryPageRepository extends EntityRepository
{
    private $scholarshipCategoryPageRequest;
    private $solrRequestGenerator;
    private $solrResponseParser;
    function __construct(
                        $requstData,
                        scholarshipCategoryPageSolrRequestGenerator $solrRequestGenerator,
                        scholarshipCategoryPageSolrResponseParser $solrResponseParser
                        ){
        parent::__construct();
        $this->solrRequestGenerator = $solrRequestGenerator;
        $this->solrResponseParser   = $solrResponseParser;
        /*
         * Load entities required
         */
        $this->CI->load->entities(array('scholarshipCategoryPageRequest'),'scholarshipCategoryPage');
        $this->scholarshipCategoryPageRequest = new scholarshipCategoryPageRequest();
        $this->fillObjectWithData($this->scholarshipCategoryPageRequest, $requstData);
    }
    public function getScholarshipCategoryPageRequest(){
        return $this->scholarshipCategoryPageRequest;
    }
    public function getScholarships($caseFlag='',$tupleCount=''){
        $solrResultData = $this->_getCategoryPageDataFromSolr($tupleCount);
        if($solrResultData['status']=='success'){
            $this->scholarshipCategoryPageRequest->totalResults = $solrResultData['total'];
        }
        // if it's not a call to get only filters & there are no results & its page #>1
        if(!$this->scholarshipCategoryPageRequest->isFilterAjaxCall() && !$this->scholarshipCategoryPageRequest->isAjaxCall() && empty($solrResultData['scholarshipIds']) && $this->scholarshipCategoryPageRequest->getPageNumber()>1){
            redirect($this->scholarshipCategoryPageRequest->getPaginatedUrl(),'location',302);
        }
        if(count($solrResultData['scholarshipIds'])>0)
        {
            $this->_loadScholarshipObjects($solrResultData,$caseFlag);
        }
            
        return $solrResultData;
    }
    private function _getCategoryPageDataFromSolr($tupleCount=''){
        $solrRequestUrl = $this->solrRequestGenerator->getScholarshipCategoryPageRequestUrl($this->scholarshipCategoryPageRequest,$tupleCount);
        $solrClient = $this->CI->load->library("SASearch/AutoSuggestorSolrClient");
        $response = $solrClient->getCategoryPageResults($solrRequestUrl,'scholarshipCategoryPage');
        $response = $this->solrResponseParser->parseScholarshipSolrResponse($response,  $this->scholarshipCategoryPageRequest);
        //for filter ajax call, call filter lib to parse & process filter data
        if($this->scholarshipCategoryPageRequest->isFilterAjaxCall() || $this->scholarshipCategoryPageRequest->isAjaxCall())
        {
            $filterLib = $this->CI->load->library("scholarshipCategoryPage/scholarshipCategoryPageFilterLib");
            $filterData = $filterLib->getProcessedFilterData($response, $this->scholarshipCategoryPageRequest);
            $responseData = array('filterData'=>$filterData['processedFilterData'],
							'appliedFilterData'=>$filterData['formattedAppliedFilters']
						 );
			$responseData['status']= $response['status'];
			$responseData['total'] = $response['total'];
            if($this->scholarshipCategoryPageRequest->isAjaxCall())
            {
                // need results & result count on filter appln
                $responseData['scholarshipIds'] = $response['scholarshipIds'];
            }
            return $responseData;
        }
        return $response; // tuples on page load
    }
    private function _loadScholarshipObjects(& $solrData,$caseFlag)
    {
        $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $scholarshipBuilder = new scholarshipBuilder();
        $scholarshipRepository     = $scholarshipBuilder->getScholarshipRepository();
        $scholarshipIds = array_unique(array_filter($solrData['scholarshipIds']));
        if(count($scholarshipIds)==0)
        {
            return false;
        }
        switch($caseFlag)
        {
            case 'INTERLINKING':
            $requiredData = array(
                'basic'                 =>array('scholarshipId','name','link','category','type','scholarshipType2','seoUrl'),
                'amount'                =>array('totalAmountPayout','amountCurrency','amountInterval','convertedTotalAmountPayout'),
                'deadline'              =>array('applicationEndDate','numAwards'),
            );
            break;
            case 'ULP' :
            case 'ARTICLEPAGE':
            $requiredData = array(
                'basic'                 =>array('scholarshipId','name','link','category','type','scholarshipType2','seoUrl'),
                'amount'                =>array('totalAmountPayout','amountCurrency','amountInterval','convertedTotalAmountPayout'),
                'deadline'              =>array('numAwards'),
                'hierarchy'             =>array('parentCategory','course')
            );
            break;
            default:
            $requiredData = array(
                'basic'                 =>array('scholarshipId','name','link','category','type','scholarshipType2','seoUrl'),
                'application'           =>array('applicableCountries','applyNowLink','scholarshipBrochureUrl','applicableNationalities'),
                'amount'                =>array('totalAmountPayout','amountCurrency','amountInterval','convertedTotalAmountPayout'),
                'specialRestrictions'   =>array('specialRestrictionDescription','specialRestriction'),
                'hierarchy'             =>array('parentCategory','university','course','allCategorizations'),
                'deadline'              =>array('applicationEndDate','numAwards'),
            );
            break;
        }
        $solrData['scholarshipObjs'] = $scholarshipRepository->findMultiple($scholarshipIds,$requiredData);
    }
}
