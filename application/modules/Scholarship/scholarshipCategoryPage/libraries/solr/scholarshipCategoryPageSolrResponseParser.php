<?php

class scholarshipCategoryPageSolrResponseParser
{
    public function parseScholarshipSolrResponse($solrResponse,scholarshipCategoryPageRequest $request){
        $returnArray = array();
        if($solrResponse['responseHeader']['status']!=0){
            $returnArray['status'] = 'failed';
            return $returnArray;
        }
        $returnArray['status'] = 'success';
        $returnArray['total'] = $solrResponse['response']['numFound'];
        if(($request->isFilterAjaxCall() || $request->isAjaxCall())){
            $returnArray['facets'] = $solrResponse['facet_counts']['facet_fields'];
            if($solrResponse['stats']['stats_fields']['saScholarshipAwardsCount']['min']!=""){
                $returnArray['facets']['saScholarshipAwardsCount']['min'] = $solrResponse['stats']['stats_fields']['saScholarshipAwardsCount']['min'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAwardsCount_parent']['min']!=""){
                $returnArray['facets']['saScholarshipAwardsCount_parent']['min'] = $solrResponse['stats']['stats_fields']['saScholarshipAwardsCount_parent']['min'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAwardsCount']['max']!=""){
                $returnArray['facets']['saScholarshipAwardsCount']['max'] = $solrResponse['stats']['stats_fields']['saScholarshipAwardsCount']['max'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAwardsCount_parent']['max']!=""){
                $returnArray['facets']['saScholarshipAwardsCount_parent']['max'] = $solrResponse['stats']['stats_fields']['saScholarshipAwardsCount_parent']['max'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate']['min']!=""){
                $returnArray['facets']['saScholarshipApplicationEndDate']['min'] = str_replace('T23:59:59Z', '',$solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate']['min']);
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate_parent']['min']!=""){
                $returnArray['facets']['saScholarshipApplicationEndDate_parent']['min'] = str_replace('T23:59:59Z', '',$solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate_parent']['min']);
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate']['max']!=""){
                $returnArray['facets']['saScholarshipApplicationEndDate']['max'] = str_replace('T23:59:59Z', '',$solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate']['max']);
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate_parent']['max']!=""){
                $returnArray['facets']['saScholarshipApplicationEndDate_parent']['max'] = str_replace('T23:59:59Z', '',$solrResponse['stats']['stats_fields']['saScholarshipApplicationEndDate_parent']['max']);
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAmount_parent']['min']!=""){
                $returnArray['facets']['saScholarshipAmount_parent']['min'] = $solrResponse['stats']['stats_fields']['saScholarshipAmount_parent']['min'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAmount']['min']!=""){
                $returnArray['facets']['saScholarshipAmount']['min'] = $solrResponse['stats']['stats_fields']['saScholarshipAmount']['min'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAmount']['max']!=""){
                $returnArray['facets']['saScholarshipAmount']['max'] = $solrResponse['stats']['stats_fields']['saScholarshipAmount']['max'];
            }
            if($solrResponse['stats']['stats_fields']['saScholarshipAmount_parent']['max']!=""){
                $returnArray['facets']['saScholarshipAmount_parent']['max'] = $solrResponse['stats']['stats_fields']['saScholarshipAmount_parent']['max'];
            }

        }
        if(!$request->isFilterAjaxCall()){
            $returnArray['scholarshipIds'] = array();
            foreach($solrResponse['response']['docs'] as &$scholarshipData){
                $returnArray['scholarshipIds'][] = $scholarshipData['saScholarshipId'];
            }
        }
        return $returnArray;
    }
    
}


