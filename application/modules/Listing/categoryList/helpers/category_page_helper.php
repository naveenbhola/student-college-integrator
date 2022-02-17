<?php

function getPageHeadingTextForRNR($locationname,$pageTitleForFilters,$isSourceRegistration,$request,$categoryPageSolr) {

      $headingText = '';
      if($request->isMainCategoryPage()){
          $change = "Career Option"; 
      } elseif($request->isSubcategoryPage()){
          $change = "Course";
      } elseif($request->isLDBCoursePage()){
          $change = "Course"; 
      }
      
      $subcategoryId   = $request->getSubCategoryId();
      $subcategoryName = $request->getSubCategoryName();
      $examName        = $request->getExamName();
      $ldbCourseId     = $request->getLDBCourseId();
      $feesString     = $request->getFeesString();

      if($subcategoryName == 'Full Time MBA/PGDM' || $subcategoryId == 23){
        $catType = 'MBA';
      }else if($subcategoryName == 'B.E./B.Tech' || $subcategoryId == 56){
        $catType = 'Engineering';
      }

      if($examName != '' && $ldbCourseId != 1 && $feesString != ''){
        // exam + spec +fees
         $headingText = $pageTitleForFilters;
      }else if($examName != '' && $ldbCourseId == 1 && $feesString != ''){
        // exam + spec +fees
         $headingText = $pageTitleForFilters;
      }else if($ldbCourseId != 1){
        // spec
        if($subcategoryId == 23)
          $headingText = $catType." in ".$request->getCourseName()." colleges ";
        else if($subcategoryId == 56)
          $headingText = $request->getCourseName()." colleges ";

      }else{
        $headingText = $catType." colleges ";
      }


      $cityId  = $request->getCityID();
      $stateId = $request->getStateID();
      if($isSourceRegistration and $request->getSubCategoryId() == 23){
        $headingText = $pageTitleForFilters;
      }
      // if($cityId == 1 && $stateId == 1 && (!($isSourceRegistration and $request->getSubCategoryId() == 23)) && !empty($categoryPageSolr)) {
      //    $headingText = $categoryPageSolr->getTotalNumberOfInstitutes()." ".($categoryPageSolr->getSubCategory()->getName()=='All'?$categoryPageSolr->getCategory()->getName():$categoryPageSolr->getSubCategory()->getName()).(($categoryPageSolr->getTotalNumberOfInstitutes() == 1)?" College":" Colleges")." Found";
      // }
      // else{
      //    $headingText = $headingText;
      //}
      
      $localityName  = $request->getLocalityName();
      $cityName      = $request->getCityName();
      $stateName     = $request->getStateName();
      $countryName   = $request->getCountryName();
      $locationName1 = "";
      $locationName2 = "";
      if($localityName) {
          $locationName1 = $localityName.", ";
      }
      $locationName1 = ucfirst($locationName1);
      if($cityId > 0) {
          $locationName2 = $cityName;
          if($cityId == 1 && $stateId > 0) {
              $locationName2 = $stateName;
              if($stateId == 1 && $countryName) {
                  $locationName2 = $countryName;
              }
          }
          $locationName2 = ucfirst($locationName2);
          $locationname = $locationName1.$locationName2;
      }
      else {
          $locationname = "";
      }

      if($isSourceRegistration && $subcat_id_course_page == 23) {
          $locationname = '';
      }
      if(!empty($locationname) && !($cityId == 1 and $stateId == 1)) {
          $headingText .= "in " . $locationname;
      }else{
          $headingText .= "in India";
      }

      if($examName != '' && $feesString == ''){
        $headingText .= " accepting ".$examName." scores";
      }

      if($feesString != '' && $examName == ''){
        if($subcategoryId == 23)
          $headingText .= ", fees upto ".$feesString;
        else if($subcategoryId == 56)
          $headingText .= " with Fees Upto ".$feesString;
      }

      return $headingText;
  }



  function computeFinalCrumb($request){
  	global $pageHeading;
	    
	    $affiliationName = $request->getFullAffiliationName();
	    $feesString = $request->getFeesString();
	    $examName = $request->getExamName();
	    $subCategoryName = $request->getSubCategoryName();
	    $subCategoryId = $request->getSubCategoryId();
	    $courseName = $request->getCourseName();
	    $courseId = $request->getLDBCourseId();

      $doesSubCategoryNameContainMBA = preg_match('/[MBA]{1}/i', $subCategoryName);
//	    if($subCategoryName == "Full Time MBA/PGDM" || $subCategoryId == 23 || $doesSubCategoryNameContainMBA){
//            $subCategoryName = "MBA";
//	    }
	    if($subCategoryName == "B.E./B.Tech" || $subCategoryId == 56){
        //$subCategoryName = "B.Tech";
        /*to change the text from btech to engineering on category pages for breadcrumb
        Story Id : LF-2875
        Changed By: Aman Varshney
        */
		    $subCategoryName = "Engineering";
	    }
	    
	    if($courseId == 2 || $courseId == 52)
		    $courseName = "";
//	    if($courseName)
//		    $courseName = " in ".$courseName;
	    
	    if($examName)
		    $examName = "accepting ".$examName." score ";
	    
	    if($feesString)
		    $feesString = "fees upto ".$feesString." ";
	    $ListingType= " Institutes ";
	    if(in_array($subCategoryId, array(23,56)) || $doesSubCategoryNameContainMBA) {
	    	$ListingType= " Colleges ";
	    } 
	    // error_log("AMITK ".$affiliationName.$subCategoryName.$courseName." colleges ".$examName.$feesString);
      $returnArray = array('affiliationName' => $affiliationName, 'subCategoryName' => $subCategoryName, 'listingType' => $ListingType, 'courseName' => $courseName, 'examName' => $examName, 'feesString' => $feesString);
      return $returnArray;
      return $affiliationName.$subCategoryName.$courseName.$ListingType.$examName.$feesString;
  }

  /**
    * function : getPageHeadingTextForNONRNR
    * param: $pageTitle, $request (category page request object)
    * desc : prepare url and urltitle
    * type : return array list with urltitle and url
    * added by akhter
    **/
  function getPageHeadingTextForNONRNR($pageTitle,$request) {
      $request->setNewURLFlag(1);
      $headingText = $pageTitle;
     
      $cityId  = $request->getCityID();
      $stateId = $request->getStateID();
      
      $localityName  = $request->getLocalityName();
      $cityName      = $request->getCityName();
      $stateName     = $request->getStateName();
      $countryName   = $request->getCountryName();

      $locationName1 = "";
      $locationName2 = "";
      if($localityName) {
          $locationName1 = $localityName.", ";
      }
      $locationName1 = ucfirst($locationName1);
      if($cityId > 0) {
          $locationName2 = $cityName;
          if($cityId == 1 && $stateId > 0) {
              $locationName2 = $stateName;
              if($stateId == 1 && $countryName) {
                  $locationName2 = $countryName;
              }
          }
          $locationName2 = ucfirst($locationName2);
          $locationname = $locationName1.$locationName2;
      }
      else {
          $locationname = "";
      }
      if(!empty($locationname) && !($cityId == 1 and $stateId == 1)) {
          $headingText .= " in " . $locationname;
      }else{
          $headingText .= " in India";
      } 
     return $headingText;
  }