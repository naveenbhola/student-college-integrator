<?php
global $filters;
global $appliedFilters;
global $filtersApplied;
global $filterDisplayString;
$filtersApplied = false;
$filterDisplayString = '';

$appliedFilters = sanitizeAppliedFilters($appliedFilters,$request->isStudyAbroadPage());

if( !empty($appliedFilters['fees']) || !empty($appliedFilters['city']) || !empty($appliedFilters['exams']) || !empty($appliedFilters['degreePref']) || !empty($appliedFilters['courseLevel']) || !empty($appliedFilters['state']) || !empty($appliedFilters['country']) || !empty($appliedFilters['locality']) ){
    $filtersApplied = true;
    if(!empty($appliedFilters['exams'])){
        $filterDisplayString = implode(',',$appliedFilters['exams']);
    }

    if(!empty($appliedFilters['exams']) && !empty($appliedFilters['fees'])){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';
    }
    if(!empty($appliedFilters['fees'])){
        $filterDisplayString .= implode(',',$appliedFilters['fees']);
    }
    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees'])) && (!empty($appliedFilters['degreePref'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';
    }
    if(!empty($appliedFilters['degreePref'])){
	foreach($appliedFilters['degreePref'] as $filter){
           foreach($affiliationSuffix as $affiliationfilter=>$value){
             if($affiliationfilter == $filter)
                $appliedFilters['degreePrefs'][]=strtoupper($affiliationfilter)." ".ucfirst(strtolower($value));
            }
        }
        $filterDisplayString .= implode(',',$appliedFilters['degreePrefs']);
    }
    
    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees']) || !empty($appliedFilters['degreePref'])) && (!empty($appliedFilters['courseLevel'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(!empty($appliedFilters['courseLevel'])){
        $filterDisplayString .= implode(',',$appliedFilters['courseLevel']);
    }
    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees']) || !empty($appliedFilters['degreePref']) || !empty($appliedFilters['courseLevel'])) && (!empty($appliedFilters['locality'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    
    if(!empty($appliedFilters['locality'])){
        $localityArray = $filters['locality']->getFilteredValues();
        $filterStringlocality = '';
        foreach ($appliedFilters['locality'] as $locality){
	    foreach ($localityArray as $zoneArray) {
		if(!empty($zoneArray['localities'][$locality])){
		  $filterStringLocality .= ($filterStringLocality=='')?$zoneArray['localities'][$locality]:','.$zoneArray['localities'][$locality];
		}
	    }
         }
        $filterDisplayString .= $filterStringLocality;
    }

    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees']) || !empty($appliedFilters['degreePref']) || !empty($appliedFilters['courseLevel']) || !empty($appliedFilters['locality']) ) && (!empty($appliedFilters['city'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }

    if(!empty($appliedFilters['city'])){
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
    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees']) || !empty($appliedFilters['degreePref']) || !empty($appliedFilters['courseLevel']) || !empty($appliedFilters['locality']) || !empty($appliedFilters['city'])) && (!empty($appliedFilters['state'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(!empty($appliedFilters['state'])){
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
    if( (!empty($appliedFilters['exams']) || !empty($appliedFilters['fees']) || !empty($appliedFilters['degreePref']) || !empty($appliedFilters['courseLevel']) || !empty($appliedFilters['locality']) || !empty($appliedFilters['city']) || !empty($appliedFilters['state'])) && (!empty($appliedFilters['country'])) ){
        //$filterDisplayString .= '<span>.</span>';
        $filterDisplayString .= ',';        
    }
    if(!empty($appliedFilters['country'])){
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
