<?php
global $filters;
global $appliedFilters;
global $filtersApplied;
global $filterDisplayString;
$filtersApplied = false;
$filterDisplayString = '';

$appliedFilters = sanitizeAppliedFilters($appliedFilters,$request->isStudyAbroadPage());
//_p($appliedFilters); exit;
if( !empty($appliedFilters['fees']) || isset($appliedFilters['exams']) || isset($appliedFilters['city']) || isset($appliedFilters['duration']) || isset($appliedFilters['mode']) || isset($appliedFilters['courseLevel']) || isset($appliedFilters['state']) || isset($appliedFilters['country']) || isset($appliedFilters['locality']) ){
    $filtersApplied = true;
    if(isset($appliedFilters['duration'])){
        $filterDisplayString = implode(',',$appliedFilters['duration']);
    }
    if(isset($appliedFilters['duration']) && isset($appliedFilters['exams'])){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';
    }
    if(isset($appliedFilters['exams'])){
        $filterDisplayString .= implode(',',$appliedFilters['exams']);
    }
    
    
    
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams'])) && (!empty($appliedFilters['fees'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';
    }
    
    if(!empty($appliedFilters['fees'])){
        $filterDisplayString .= implode(',',$appliedFilters['fees']);
    }
    
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams']) || !empty($appliedFilters['fees'])) && (isset($appliedFilters['mode'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';
    }
    if(isset($appliedFilters['mode'])){
        $filterDisplayString .= implode(',',$appliedFilters['mode']);
    }
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams'] ) || !empty($appliedFilters['fees']) || isset($appliedFilters['mode'])) && (isset($appliedFilters['courseLevel'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(isset($appliedFilters['courseLevel'])){
        $filterDisplayString .= implode(',',$appliedFilters['courseLevel']);
    }
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams']) || !empty($appliedFilters['fees']) || isset($appliedFilters['mode']) || isset($appliedFilters['courseLevel'])) && (isset($appliedFilters['locality'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    
    if(isset($appliedFilters['locality'])){
        $localityArray = $filters['locality']->getFilteredValues();
        $filterStringlocality = '';
        foreach ($appliedFilters['locality'] as $locality){
	    foreach ($localityArray as $zoneArray) {
		if(isset($zoneArray['localities'][$locality])){
		  $filterStringLocality .= ($filterStringLocality=='')?$zoneArray['localities'][$locality]:','.$zoneArray['localities'][$locality];
		}
	    }
         }
        $filterDisplayString .= $filterStringLocality;
    }

    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams']) || !empty($appliedFilters['fees']) || isset($appliedFilters['mode']) || isset($appliedFilters['courseLevel']) || isset($appliedFilters['locality']) ) && (isset($appliedFilters['city'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }

    if(isset($appliedFilters['city'])){
        $cityArray = $filters['city']->getFilteredValues();
        $filterStringCity = '';
        foreach ($appliedFilters['city'] as $city){ 
	    $newCityArray= explode(',',$filterStringCity); 
	    if(!in_array($cityArray[$city],$newCityArray)){
              $filterStringCity .= ($filterStringCity=='')?$cityArray[$city]:','.$cityArray[$city];
            }
         }
//	 $filterDisplayString=trim($filterDisplayString,",");
        $filterDisplayString .= $filterStringCity;
    }
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams']) || !empty($appliedFilters['fees']) || isset($appliedFilters['mode']) || isset($appliedFilters['courseLevel']) || isset($appliedFilters['locality']) || isset($appliedFilters['city'])) && (isset($appliedFilters['state'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(isset($appliedFilters['state'])){
        $stateArray = $filters['state']->getFilteredValues();
        $filterStringstate = '';
        foreach ($appliedFilters['state'] as $state){
	    $newStateArray = explode(',',$filterStringstate);
	    if(!in_array($countryArray[$country],$newCountryArray)){
           	 $filterStringstate .= ($filterStringstate=='')?$stateArray[$state]:','.$stateArray[$state];
           }
	}
        $filterDisplayString .= $filterStringstate;
//	 $filterDisplayString=trim($filterDisplayString,",");

    }
    if( (isset($appliedFilters['duration']) || isset($appliedFilters['exams']) || !empty($appliedFilters['fees']) || isset($appliedFilters['mode']) || isset($appliedFilters['courseLevel']) || isset($appliedFilters['locality']) || isset($appliedFilters['city']) || isset($appliedFilters['state'])) && (isset($appliedFilters['country'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(isset($appliedFilters['country'])){
        $countryArray = $filters['country']->getFilteredValues();
        $filterStringcountry = '';
        foreach ($appliedFilters['country'] as $country){
	    $newCountryArray = explode(',',$filterStringcountry);
             if(!in_array($countryArray[$country],$newCountryArray)){
             $filterStringcountry .= ($filterStringcountry=='')?$countryArray[$country]:','.$countryArray[$country];
            }
	}
      $filterDisplayString .= $filterStringcountry;
//	$filterDisplayString=trim($filterDisplayString,",");  
    }
    $filterDisplayString = displayTextAsPerMobileResolutionSmall($filterDisplayString);
}

?>
