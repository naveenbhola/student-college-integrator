<?php

class scholarshipHomePageSolrResponseParser
{
    public function parsePopularScholarshipSolrResponse($solrResponse){
        $returnArray = array();
        if($solrResponse['responseHeader']['status']!=0){
            $returnArray['status'] = 'failed';
            return $returnArray;
        }
        $returnArray['status'] = 'success';
        $returnArray['total'] = $solrResponse['response']['numFound'];
        $returnArray['scholarships'] = array();
        $returnArray['countries'] = array();
        $returnArray['allScholarships'] = array();
        foreach($solrResponse['response']['docs'] as &$scholarshipData){
            $scholarshipData['countryCount'] = count($scholarshipData['saScholarshipCountryId']);
            $scholarshipData['saScholarshipCountryId'] = array_slice($scholarshipData['saScholarshipCountryId'], 0, 2);
            $returnArray['scholarships'][] = $scholarshipData;
            foreach ($scholarshipData['saScholarshipCountryId'] as $key => $value) {
                $returnArray['countries'][] = $value;
            }
            $returnArray['allScholarships'][] = $scholarshipData['saScholarshipId'];
        }
        $returnArray['countries'] = array_unique($returnArray['countries']);
        $returnArray['allScholarships'] = array_unique($returnArray['allScholarships']);
        return $returnArray;
    }
}


