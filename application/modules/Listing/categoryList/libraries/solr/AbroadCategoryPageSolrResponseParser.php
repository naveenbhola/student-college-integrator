<?php

class AbroadCategoryPageSolrResponseParser
{
    private $CI;
    private $searchWrapper;
	private $rotationService;
    
    function __construct()
    {
        $this->CI = & get_instance();
		$this->cache = $this->CI->load->library('cache/CategoryPageCache');
    }
    
    public function getCategoryPageDataFromSolrResponse($solrResponse,$getfilteredResultsFlag=true)
    {
        // tuple results
        if($solrResponse['responseHeader']['params']['group.field']=='saCourseInventoryType')
        {
            $grouped = $solrResponse['grouped']['saCourseInventoryType'];
        }else{
            $grouped = $solrResponse['grouped']['saUnivId'];
        }

        $groups = $grouped['groups'];
        $totalCount = $grouped['ngroups'];
        $universities  = array();
        $return = array();
		// for the case of sponsored process univs differently
        if($solrResponse['responseHeader']['params']['group.field']=='saCourseInventoryType')
        {
			//$this->CI->benchmark->mark('start_sorting');
			// sort inventory groups such that if a univ has courses of different inventory types, it falls in the higher one
			$univsByInventory = $this->_sortUniversitiesByInventoryType($groups);
			//$this->CI->benchmark->mark('end_sorting');
			//error_log( "SRB1 time taken: sorting (arranging courses in higher priority buckets) = ".$this->CI->benchmark->elapsed_time('start_sorting', 'end_sorting'));
			
			$return['courseCount'] = $univsByInventory['courseCount'];
            $return['totalCount']   = $univsByInventory['total'];

			//$this->CI->benchmark->mark('start_rotation');
			// apply rotation to each group of saCourseInventoryType
			$universities = $this->applyRotationToInventoryGroups($univsByInventory['universities']);
			//$this->CI->benchmark->mark('end_rotation');
			//error_log( "SRB2 time taken: rotation = ".$this->CI->benchmark->elapsed_time('start_rotation', 'end_rotation'));
			// apply pagination			
			//$this->CI->benchmark->mark('start_paginate');
			$universities = $this->applyPaginationToInventoryGroups($universities);
			//$this->CI->benchmark->mark('end_paginate');
			//error_log( "SRB3 time taken: paginate = ".$this->CI->benchmark->elapsed_time('start_paginate', 'end_paginate'));
			//die;
        }else{
			
			//$this->CI->benchmark->mark('start_regularParsing');
			$courseCount = 0;
	        foreach($groups as $group) {
				$docs = $group['doclist']['docs'];
				$courseCount += $group['doclist']['numFound'];
				foreach($docs as $doc) {
					$univId = $doc['saUnivId'];
					if(!array_key_exists($univId, $universities)){
						$universities[$univId] = array();
					}
					$universities[$univId][$doc['saCourseId']] = $doc;
				}
			}

			// this is done only in case of sort by : popularity(viewCount)
			if(!$this->request->isSolrFilterAjaxCall() && $this->request->getSortingCriteria()['sortBy'] == 'viewCount'){
				$universities = $this->rotationService->rotateCategorySponsorsAmongPopularUnivs($universities,$this->banner);
			}
            $return['totalCount']   = $totalCount;
			$return['courseCount'] = $courseCount;
			//$this->CI->benchmark->mark('end_regularParsing');
			//error_log( "SRB3 time taken: regular parsing = ".$this->CI->benchmark->elapsed_time('start_paginate', 'end_paginate'));

        }

        if($getfilteredResultsFlag)
        {
            $return['rawFilters'] = $solrResponse['facet_counts'];
        }
        
        $return['universities'] = $universities;
        $return['rawUniversities'] = $groups;
        
        return $return;
        exit();
    }

	/*
	 * Rotate sticky, main and paid
	 */
    public function applyRotationToInventoryGroups($universtiesByInventoryGroups)
    {
    	// rotate cat spon bucket 
        if(is_array($universtiesByInventoryGroups[1]) && count($universtiesByInventoryGroups[1]) > 1) {
            $universities['sticky'] = $this->rotationService->rotateCategorySponsors($universtiesByInventoryGroups[1],$this->banner);
        }
		else if(is_array($universtiesByInventoryGroups[1]) && count($universtiesByInventoryGroups[1]) == 1)
		{
            $universities['sticky'] = $universtiesByInventoryGroups[1];
		}
        // rotate main bucket
		if(is_array($universtiesByInventoryGroups[2]) && count($universtiesByInventoryGroups[2]) > 1) {
            $universities['main'] = $this->rotationService->rotateMainUnivs($universtiesByInventoryGroups[2]);
        }
		else if(is_array($universtiesByInventoryGroups[2]) && count($universtiesByInventoryGroups[2]) == 1)
		{
            $universities['main'] = $universtiesByInventoryGroups[2];
		}
		// rotate paid bucket
        if(is_array($universtiesByInventoryGroups[3]) && count($universtiesByInventoryGroups[3]) > 1) {
            $universities['paid'] = $this->rotationService->rotatePaidUnivs($universtiesByInventoryGroups[3]);
        }
		else if(is_array($universtiesByInventoryGroups[3]) && count($universtiesByInventoryGroups[3]) == 1)
		{
            $universities['paid'] = $universtiesByInventoryGroups[3];
		}
        // free not rotated
		$universities['free'] = $universtiesByInventoryGroups[4];
		// rotate free bucket
		/*if(is_array($universtiesByInventoryGroups[4]) && count($universtiesByInventoryGroups[4]) > 1) {
            $universities['free'] = $this->rotationService->rotateFreeUnivs($universtiesByInventoryGroups[4]);
        }
		else if(is_array($universtiesByInventoryGroups[4]) && count($universtiesByInventoryGroups[4]) == 1)
		{
            $universities['free'] = $universtiesByInventoryGroups[4];
		}*/
		// join 'em
        return $universities;
    }

	/*
	 * function that sorts records by inventory type
	 */
	private function _sortUniversitiesByInventoryType($inventoryGroups)
	{
		$universities = $universitiesByInventoryType = array();
		$courseCount = 0;
		//structure for $universitiesByInventoryType = array( '<univid>'=>'invType')
		foreach($inventoryGroups as $group)
		{
			$universities[$group['groupValue']] = array();
			foreach($group['doclist']['docs'] as $doc)
			{
				$courseCount += $group['doclist']['numFound'];
				// check if this doc's univ is already under any of the inventory groups...
				if(array_key_exists($doc['saUnivId'], $universitiesByInventoryType))
				{
					$universities[$universitiesByInventoryType[$doc['saUnivId']]][$doc['saUnivId']][] = $doc;
					// if the inventory type for the univ in the doc is less than the one encountered before...
					if($universitiesByInventoryType[$doc['saUnivId']]>$doc['saCourseInventoryType'])
					{
						// shift the univ courses to that inventory type..
						$universities[$doc['saCourseInventoryType']][$doc['saUnivId']] = $universities[$universitiesByInventoryType[$doc['saUnivId']]][$doc['saUnivId']];
						unset($universities[$universitiesByInventoryType[$doc['saUnivId']]][$doc['saUnivId']]);
						// ...also that would be the new inv type for that univ
						$universitiesByInventoryType[$doc['saUnivId']] = $doc['saCourseInventoryType'];
					}
				}else{
					$universities[$group['groupValue']][$doc['saUnivId']][] = $doc;
					$universitiesByInventoryType[$doc['saUnivId']] = $doc['saCourseInventoryType'];
				}
			}
			//echo "<br>bm".$group['groupValue'];_p($universitiesByInventoryType);
			//_p(array_keys($universities[$group['groupValue']]));
		}
		ksort($universities);
		return array('universities'=>$universities,'total'=>count($universitiesByInventoryType),'courseCount'=>$courseCount);
	}
	/*
	 * set request
	 */
	public function setRequest($request)
	{
		$this->request = $request;
	}
	/*
	 * set banners
	 */
	public function setBanner($banner)
	{
		$this->banner = $banner;
	}
	/*
	 * flattens the inventory groups array & picks elements by page size
	 */
	public function applyPaginationToInventoryGroups($universities)
	{
		$pageNo = $this->request->getPageNumberForPagination();
		$pageSize = $this->request->getSnippetsPerPage();
        if(empty($universities['sticky'])){
            $universities['sticky'] = array();
        }
        if(empty($universities['main'])){
            $universities['main'] = array();
        }
        if(empty($universities['paid'])){
            $universities['paid'] = array();
        }
        if(empty($universities['free'])){
            $universities['free'] = array();
        }

		$flatArray = $universities['sticky'] + $universities['main'] + $universities['paid'] + $universities['free'];
		$flatArray = array_slice($flatArray,(($pageNo-1)*$pageSize),$pageSize,true);
		$paginatedArray  = array();
		foreach($flatArray as $univId=>$courseDocs)
		{
			foreach($courseDocs as $courseDoc)
			{
				if(!array_key_exists($univId,$paginatedArray))
				{
					$paginatedArray[$univId] = array();
				}
				$paginatedArray[$univId][$courseDoc['saCourseId']] = $courseDoc;
			}
		}
		return $paginatedArray;
	}
	
	public function setRotationService($rotationService)
	{
		$this->rotationService = $rotationService;
	}
}