<?php
/*
Purpose       : Library to handle the Zero result category pages
	
Author 	      : Romil Goel

Creation Date : 23-12-2013
*/

class CategoryPageZeroResultHandler
{
    /**
     * Constructor
    **/
    public function __construct($categoryPageURLQueryString = '', $newUrlFlag = false)
    {
 	$this->CI = & get_instance();
	$this->CI->config->load('categoryPageConfig');
	$this->CI->load->library(array('categoryList/categoryPageRequest'));	
	$this->CI->load->builder('CategoryPageBuilder','categoryList');
	
        $this->CI->load->model('categoryList/categorypagemodel');
        $this->CI->load->model('location/locationmodel');
        $locationModel = new LocationModel;
        // get the objects of the model
	$this->categoryPageModelObj = new CategoryPageModel;
        $this->categoryPageModelObj->init($locationModel);
		    
	$this->CI->load->builder('LocationBuilder','location');
        $locationBuilder    = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

    }
    
    /**
    * Function to create the non-zero category page keys
    ***/
    public function createCategoryPageKeys()
    {
	// delete the content of the table if it exists
	$this->categoryPageModelObj->deleteNonZeroCategoryPageResultTable();
	
	// create non-zero category pages for Non-RNR subcategories
	error_log("\n".date("Y-m-d:H:i:s")." ^^^^^^^^^^^^^^^^^^  STARTING FOR NON-RNR Sub-categories ^^^^^^^^^^^^^^^^^^^^^^^",3,"/tmp/zeroresults.log");
	$this->createNonZeroCategoryPageKeys();
	error_log("\n".date("Y-m-d:H:i:s")." ^^^^^^^^^^^^^^^^^^  STOPPING FOR NON-RNR Sub-categories ^^^^^^^^^^^^^^^^^^^^^^^",3,"/tmp/zeroresults.log");
	
	// create non-zero category pages for RNR subcategories
	error_log("\n".date("Y-m-d:H:i:s")." ^^^^^^^^^^^^^^^^^^  STARTING FOR RNR Sub-categories ^^^^^^^^^^^^^^^^^^^^^^^",3,"/tmp/zeroresults.log");
	$this->createNonZeroCategoryPageKeysForRNRSubCats();
	error_log("\n".date("Y-m-d:H:i:s")." ^^^^^^^^^^^^^^^^^^  STOPPING FOR RNR Sub-categories ^^^^^^^^^^^^^^^^^^^^^^^",3,"/tmp/zeroresults.log");
	
    }
    
    /**
     * Purpose : Function to create non-zero result entries of non-RNR subcategories pages
    **/
    public function createNonZeroCategoryPageKeys()
    {
	// create the table for storing the non-zero category page data
	$this->categoryPageModelObj->createNonZeroCategoryPageResultTable();

	// fetch the non-RnR subcategories (except 23,56)
	$Ids = $this->categoryPageModelObj->getNonRnRSubcategories();
	$Ids = $Ids[0]['subCategoryIds'];
	$Ids = explode(",", $Ids);
	$Ids = array_chunk( $Ids, 45 );
	foreach( $Ids as $id=>$value )
	{
	    $Ids[$id] = implode(",", $value);
	}
	
	// for testing
	//$Ids = array( "15,16,17,18,19,20,21,22,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,57,58,59,60,61"
	//	    ,"62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104"
	//	    ,"105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138"
	//	    ,"139,140,141,142,143,144,145,146,147,148,149,151,152,153,154,155,156,157,158,159,160,161,162,163,165,167,168,169,170,171,172,173,174,175"
	//	    ,"176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,210"
	//	    ,"211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,231,232,233,234,235,237,238"
	//	    );
	error_log("\n".date("Y-m-d:H:i:s")." STARTING\n\n",3,"/tmp/zeroresults.log");

	$insertQueries = array();    
	foreach( $Ids as $id=>$value )
	{
	    error_log("\n".date("Y-m-d:H:i:s")." For ids : ".$value."\n",3,"/tmp/zeroresults.log");
	    
	    $insertQueries = array_merge( $insertQueries, $this->_generateCombinationsOfCategoryPage($value) );
	    error_log("\n".date("Y-m-d:H:i:s")." After Inserting Category Pages: ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	    
	    $insertQueries = array_merge( $insertQueries, $this->_createCategoryPageCombinationsUsingLocality($value) );
	    error_log("\n".date("Y-m-d:H:i:s")." After Inserting Locality Pages: ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	    
	    error_log("\n".date("Y-m-d:H:i:s")." Insert Statement prepared",3,"/tmp/zeroresults.log");
	}
	
	error_log("\n".date("Y-m-d:H:i:s")." ------- Before Uniqueness : ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	
	// filter the empty elements from the array
	$insertQueries = array_filter($insertQueries);
	// get only the unique insert statements to remove the duplicacy
	$insertQueries = $this->_getUniqueRows($insertQueries);
	error_log("\n".date("Y-m-d:H:i:s")." ------- After Uniqueness : ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	
	// create chunks of insert statements for easy insertion of data
	$final_array = array_chunk( $insertQueries, 30000 );
	
	// insert all chunks of insert statements
	foreach( $final_array as $key=>$chunk )
	{
	    error_log("\n".date("Y-m-d:H:i:s")." ------- Inserting chunk :  ".$key."\n",3,"/tmp/zeroresults.log");
   	    $insertQueries = implode(",", $chunk);
	    $insertQueries = $this->prepareInsertStatements( $insertQueries );
	    $this->categoryPageModelObj->insertDataInBulk($insertQueries);
	}
	
	// create a temp file to store the insert statement
	$filename = "/tmp/romil_final_insert_zeroresult_".date("Y-m-d:H:i:s").".txt";
	$fp=fopen($filename,'a+');
	fputs($fp,implode(" ", $insertQueries));
	fclose($fp);
	
        error_log("\n".date("Y-m-d:H:i:s")." Sql Files created",3,"/tmp/zeroresults.log");
	error_log("\n".date("Y-m-d:H:i:s")." STOPPING\n",3,"/tmp/zeroresults.log");
    }
    
    /*
    * Purpose : Generate all possible combinations of the Category page using the data obtained from categorypagedata table
    *
    *  Following combinations of category page are generated :
    *  1. Category      + city
    *  2. Category      + state
    *  3. Category      + country
    *  4. Sub-Category  + city
    *  5. Sub-Category  + state
    *  6. Sub-Category  + country
    *  7. LDB Course    + city
    *  8. LDB Course    + state
    *  9. LDB Course    + country
    *  
    *  For Study abroad pages : 
    *  All Category     + City
    *  All Category     + state
    *  All Category     + Country
    *  All Category     + Region
    */
    private function _generateCombinationsOfCategoryPage($value)
    {

	$catPageData = $this->categoryPageModelObj->getCategoryPageDataForSubCategories($value);
	
	//$catPageData = $this->_getUniqueRows($catPageData);
	
	error_log("\n".date("Y-m-d:H:i:s")." Unique Count FOR City: ".count($catPageData)."\n",3,"/tmp/zeroresults.log");
	
	$this->CI->load->builder('CategoryBuilder','categoryList');
	$this->CI->load->library('CacheUtilityLib');
	
	$cacheUtilityLib 	= new CacheUtilityLib;
	$builderObj		= new CategoryBuilder;
	
	$insertQueries = array();
	foreach( $catPageData as $key=>$rowArr )
	{
	    //$subCatObj 	= $repoObj->find($rowArr['category_id']);
	    //$category_id 	= $subCatObj->getParentId();
	    $request 	= new categoryPageRequest();
	    $request->setData(array('categoryId'	=> $rowArr['categoryId'],
				    'subCategoryId' 	=> $rowArr['subCategoryId'],
				    'LDBCourseId'	=> $rowArr['LDBCourseId'],
				    'regionId'		=> $rowArr['regionId'],
				    'countryId'		=> $rowArr['countryId'],
				    'stateId'		=> $rowArr['stateId'],
				    'cityId'		=> $rowArr['cityId'],
				    'zoneId'		=> 0,
				    'localityId'	=> 0,
				    'examName'		=> 'none',
				    'affiliation'	=> 'none',
				    'feesValue'		=> 'none'
				    ));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	}
	
	///***********
	// Check for the combinations of the keys where 1-1-1 is set for the course scope
	///************
	foreach( $catPageData as $catpage )
	{
	    $catpage = array($catpage);
	    $keysOfCourseAndLocationScopeCombinations =  $cacheUtilityLib->_generateKeys( $catpage );
	    
	    $request 	= new categoryPageRequest();
	    foreach( $keysOfCourseAndLocationScopeCombinations as $key=>$val )
	    {
		$request->setDataByPageKey($val);
		
		// exclude the cases like : category scope : 1-1-1 and city or state exists
		if( !($request->getCategoryId() == 1 && ($request->getStateId() != 1 || $request->getCityId() != 1)) )
		    $insertQueries[] = $this->_getInsertStatement( $catpage[0], $request );
	    }
	}
	unset($catPageData);
	unset($keysOfCourseAndLocationScopeCombinations);
	
	return $insertQueries;
    }
    
    /*
    * Purpose : To build combinations of category page provided locality
    * 
    * To build following category pages :
    * 1. LDB Course   + Locality
    * 2. Sub-category + Locality
    * 3. Category     + Locality
    * 4. LDB Course   + Zone
    * 5. Sub-category + Zone
    * 6. Category     + Zone
    */	
    private function _createCategoryPageCombinationsUsingLocality( $value )
    {
	$subCatIds = $this->categoryPageModelObj->getCategoryPageDataWithLocalityForSubCategories($value);
	
	//$subCatIds = $this->_getUniqueRows($subCatIds);
	
	error_log("\n".date("Y-m-d:H:i:s")." Unique Count FOR city: ".count($subCatIds)."\n",3,"/tmp/zeroresults.log");
    
	$this->CI->load->library(array('categoryList/categoryPageRequest'));
	
	$insertQueries 	= array();
	$request 		= new categoryPageRequest();
	$this->CI->load->library(array('categoryList/categoryPageRequest'));
	$this->CI->load->builder('CategoryBuilder','categoryList');
	$builderObj	= new CategoryBuilder;
	$repoObj 	= $builderObj->getCategoryRepository();
	
	foreach( $subCatIds as $key=>$rowArr )
	{
	    
	    $subCatObj 			= $repoObj->find($rowArr['subCategoryId']);
	    $category_id 		= $subCatObj->getParentId();
	    $rowArr['categoryId']	= $subCatObj->getParentId();
	    //$rowArr['categoryId'] = 2;
    
	    /*
	    * To build following category pages :
	    */
	    $request->setData(array('categoryId'	=> $rowArr['categoryId'],
				    'subCategoryId' 	=> $rowArr['subCategoryId'],
				    'LDBCourseId'	=> $rowArr['LDBCourseId'],
				    'countryId'		=> $rowArr['countryId'],
				    'stateId'		=> $rowArr['stateId'],
				    'cityId'		=> $rowArr['cityId'],
				    'zoneId'		=> $rowArr['zoneId'],
				    'localityId'	=> $rowArr['localityId'],
				    'examName'		=> 'none',
				    'affiliation'	=> 'none',
				    'feesValue'		=> 'none'
				    ));
	    
	    // LDB Course + Locality page
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
	    // Sub category + Locality Page
	    $request->setData(array('LDBCourseId'	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
    
	    // Category + Locality Page			    
	    $request->setData(array('subCategoryId' 	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
	    // category + Zone Page
	    $request->setData(array('localityId'	=> 0));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
	    // Sub category + Zone Page
	    $request->setData(array('subCategoryId' => $rowArr['subCategoryId']));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
	    // LDB Course + Zone Page
	    $request->setData(array('LDBCourseId'	=> $rowArr['LDBCourseId']));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	}
	
	return $insertQueries;
    
    }
    
    /**
     * Purpose : Function to create the insert statement using category page request
    **/
    private function _getInsertStatement( $rowArr, $request )
    {
	$feeVal 		= $request->getFeesValue();
	$feeVal 		= (empty($feeVal)|| $feeVal < 0) ? 'none' : $feeVal;
	
	$affiliationName 	= $request->getAffiliationName();
	$affiliationName 	= empty($affiliationName) ? 'none' : $affiliationName;
	
	$examName 		= $request->getExamName();
	$examName 		= empty($examName) ? 'none' : $examName;
	
	$city_name = $rowArr['cityName'];
	if( $request->getCityId() == 1 )
	    $city_name = "";
	else if($request->getCityId() == 10223 || $request->getCityId() == 10224 || $request->getCityId() == 12292 )
	{
            $cityObj        	= $this->locationRepository->findCity($request->getCityId());
            $city_name 		= $cityObj->getName();
	}
	
	$state_name = $rowArr['stateName'];
	if( $request->getStateId() == 1 )
	    $state_name = "";
	
	$region_id = $request->getRegionId();
	if( empty($region_id) )
	    $region_id = 0;
	    
	// exclude the cases of ldb courses pages of study abroad and pages with city and state
	
	if( ($request->isStudyAbroadPage() && ($request->getLDBCourseId() > 1 || $request->getCityId() > 1 || $request->getStateId() > 1)
	    )
	   )
	    return;

	//$insertStmt =  "INSERT into category_page_non_zero_pages (category_page_key, category_id, sub_category_id, LDB_course_id, locality_id, zone_id, city_id, state_id, country_id) VALUES( \"".$request->getPageKey()."\", ".
	$insertStmt =   "( \"".$request->getPageKey()."\", ".
				$request->getCategoryId()	.", ".
				$request->getSubCategoryId().", ".
				$request->getLDBCourseId().",".
				($request->getLocalityId() ? $request->getLocalityId() : 0).", ".
				($request->getZoneId() ? $request->getZoneId() : 0).", ".
				($request->getCityId() ? $request->getCityId() : 1).", ".
				"\"".$city_name."\", ".
				($request->getStateId() ? $request->getStateId() : 1).", ".
				"\"".$state_name."\", ".
				($request->getCountryId() ? $request->getCountryId() : 2).", ".
				$region_id.", ".
			        "\"".$examName."\", ".
				"\"".$affiliationName."\", ".
				"\"".$feeVal."\" )";  // there is some problem with the fees value getting from the request variable
    
	return $insertStmt;
    }
    
    /**
     * Purpose : Function to remove the duplicate rows from the array set
    **/    
    private function _getUniqueRows( $Data )
    {
	$Data = array_unique($Data);
	
	return $Data;
    }
    
    /**
     * Purpose : Function to create non-zero category pages for RNR subcategories.
    **/
    public function createNonZeroCategoryPageKeysForRNRSubCats()
    {
	$this->categoryPageModelObj->createNonZeroCategoryPageResultTable();

	error_log("\n".date("Y-m-d:H:i:s")." ================= STARTING ===================".$count,3,"/tmp/zeroresults.log");	
	$RNRSubcategoryIds = array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
	$RNRSubcategoryIds = implode(",", $RNRSubcategoryIds);
	
	$insertStmts  = array();
	//$insertStmts  = array_merge($insertStmts, $this->_generateCombinationsOfCategoryPage($RNRSubcategoryIds));
	
	// Select statements of locality, exams, affiliation, fees
	$select = array( 'L' => " , CASE WHEN ILT.locality_id < 0 THEN 0 ELSE ILT.locality_id END as localityId " ,
			 'E' => " , LEM.examId as examId
				  , BT.acronym as examName " ,
			 'A' => " , CA.attribute as affiliation " ,
			 'F' => " , CASE WHEN ISNULL(CD.fees_value) OR CD.fees_value <=0 THEN 0 ELSE CD.fees_value END AS common_fees_val
				  , CASE WHEN ISNULL(CD.fees_unit ) OR CD.fees_unit = '' THEN '' ELSE CD.fees_unit END  AS common_fees_unit
				  , CASE WHEN ISNULL(CLA1.attribute_value) OR CLA1.attribute_value <=0  THEN 0  ELSE CLA1.attribute_value END AS location_fees_value
				  , CASE WHEN ISNULL(CLA2.attribute_value) OR CLA2.attribute_value = '' THEN '' ELSE CLA2.attribute_value END AS location_fees_unit ");
	
	// Table list statements of locality, exams, affiliation, fees
	$table  = array( 'L' => " INNER JOIN course_location_attribute CLA
				  ON(CPD.`course_id` = CLA.course_id AND CLA.status = 'live')
				  INNER JOIN institute_location_table ILT
				  ON(CLA.institute_location_id = ILT.institute_location_id AND ILT.locality_id IS NOT NULL AND ILT.locality_id NOT IN(0) AND ILT.status='live') ",
	
			 'E' => " INNER JOIN listingExamMap LEM 
				  ON(LEM.typeId = CPD.course_id AND LEM.type='course' AND LEM.status='live')
				  INNER JOIN blogTable BT 
				  ON(BT.blogId = LEM.examId) AND BT.status='live'
				  AND (LEM.examId = 310 || BT.boardId = CPD.category_id || 
				  CPD.category_id IN (SELECT CASE WHEN ISNULL(B.boardId) THEN 0 ELSE B.boardId END
				  FROM blogTable A LEFT JOIN blogTable B ON(A.parentId = B.blogId AND A.parentId != 0)
				  WHERE A.blogId = LEM.examId)
				  )  ",
	
			 'A' => " INNER JOIN course_attributes CA
	 		          ON(CA.`course_id` = CPD.`course_id` and CA.status = 'live') ",
	
			 'F' => " INNER JOIN course_details CD
				  ON(CD.`course_id` = CPD.course_id AND CD.status='live')
				  LEFT JOIN course_location_attribute CLA1
				  ON(CLA1.course_id = CPD.course_id AND CLA1.`attribute_type` = 'Course Fee Value' AND CLA1.status = 'live')
				  LEFT JOIN course_location_attribute CLA2
				  ON(CLA2.course_id = CLA1.course_id AND CLA2.institute_location_id = CLA1.institute_location_id AND CLA2.`attribute_type` = 'Course Fee Unit' AND CLA2.status = 'live') ");
	
	// Where statements of locality, exams, affiliation, fees
	$where  = array( 'L' => " AND CPD.city_id = ILT.city_id ",
			 'E' => " ",
			 'A' => " AND CA.`attribute` IN ('AICTEStatus', 'UGCStatus', 'DECStatus')
				  AND CPD.category_id IN (23) ",
			 'F' => " AND ((CD.fees_value IS NOT NULL AND CD.fees_value != \"\")
					OR
				      (CLA1.attribute_value IS NOT NULL OR CLA1.attribute_value != \"\"))
				    ");
	
	// ********* Till city
	$selectStmt = "SELECT
			DISTINCT 
		       CBT.parentId 	  as categoryId, 
		       CPD.category_id 	  as subCategoryId,
		       CPD.ldb_course_id  as LDBCourseId,
		       CPD.city_id 	  as cityId,
		       CT.city_name	  as cityName,
		       CPD.state_id 	  as stateId,
		       ST.state_name	  as stateName,
		       CPD.country_id 	  as countryId,
		       CASE WHEN ISNULL(VCM.virtualCityId) THEN 0 ELSE VCM.virtualCityId  END AS virtualCityId,
		       CASE WHEN ISNULL(TRM.regionid) THEN 0 ELSE TRM.regionid END AS regionId ";
		       
	$tableStmt = " categoryPageData CPD
		       INNER JOIN categoryBoardTable CBT ON ( CBT.boardId = CPD.category_id )
		       LEFT JOIN stateTable ST ON(ST.state_id = CPD.state_id)
		       LEFT JOIN countryCityTable CT ON(CT.city_id = CPD.city_id)
		       LEFT JOIN tregionmapping TRM ON(TRM.id = CPD.country_id)
		       LEFT JOIN virtualCityMapping VCM ON(VCM.city_id = CPD.city_id AND VCM.virtualCityId != CPD.city_id)";
	
	$whereStmt = " AND CPD.`category_id` IN ( ".$RNRSubcategoryIds." ) 
		       AND CPD.status    = 'live'
		       AND CPD.city_id NOT IN (10166) ";
		   
	$rs = $this->categoryPageModelObj->getResultSetFromQuery( $selectStmt, $tableStmt, $whereStmt );

	$allCombinations = $this->getPowerSet(array('L','E','A','F'));
	//_p($allCombinations);
	
	// fetch the result for queries from all possible combinations of L,E,A,F
	foreach( $allCombinations as $key => $combination )
	{
		_p("<br><br>For Subset : ".implode(", ",$combination));
		$selectQ = $selectStmt;
		$tableQ  = $tableStmt;
		$whereQ  = $whereStmt;
		foreach( $combination as $value )
		{
		    $selectQ .= $select[$value];
		    $tableQ  .= $table[$value];
		    $whereQ  .= $where[$value];
		}
		error_log("\n".date("Y-m-d:H:i:s")." For combination :".implode(", ",$combination),3,"/tmp/zeroresults.log");
		$rs = $this->categoryPageModelObj->getResultSetFromQuery( $selectQ, $tableQ, $whereQ );

		$rs = $this->resolveFeeValueAndAffiliation( $rs );
		
		// get combinations of category pages based on fees eg. if a page exists for fees = 5lacs than page must exists for 1lakh and 2 lakh
		$rs = $this->_createCategoryPageCombinationsForFees($rs) ;

		$insertStmts = array_merge( $insertStmts, $this->_createCategoryPageCombinationsForCourseScope($rs) );
		$insertStmts = $this->_getUniqueRows($insertStmts);
		error_log("\n".date("Y-m-d:H:i:s")." Unique :".count($insertStmts),3,"/tmp/zeroresults.log");
	    
	}
	
	$insertQueries = $insertStmts;
	error_log("\n".date("Y-m-d:H:i:s")." ------- Before Uniqueness : ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	
	// filter the empty elements from the array
	$insertQueries = array_filter($insertQueries);
	// remove all duplicate entries of the insert statements
	$insertQueries = $this->_getUniqueRows($insertQueries);
	error_log("\n".date("Y-m-d:H:i:s")." ------- After Uniqueness : ".count($insertQueries)."\n",3,"/tmp/zeroresults.log");
	
	$final_array = array_chunk( $insertQueries, 30000 );
	
	foreach( $final_array as $key=>$chunk )
	{
	    error_log("\n".date("Y-m-d:H:i:s")." ------- Inserting chunk :  ".$key."\n",3,"/tmp/zeroresults.log");
   	    $insertQueries = implode(",", $chunk);
	    $insertQueries = $this->prepareInsertStatements( $insertQueries );
	    $this->categoryPageModelObj->insertDataInBulk($insertQueries);
	}

	$filename = "/tmp/romil_zeroresult_RNR_".date("Y-m-d:H:i:s").".txt";
	$fp=fopen($filename,'a+');
	fputs($fp,$insertQueries);
	fclose($fp);
	
	error_log("\n".date("Y-m-d:H:i:s")." ========= STOPPING ===================".$count,3,"/tmp/zeroresults.log");
    }
    
    /**
     * Purpose : Function to create category page combinations of Course scope
    **/
    private function _createCategoryPageCombinationsForCourseScope( $rowset )
    {
	$this->CI->load->library(array('categoryList/categoryPageRequest'));
	$request 	= new categoryPageRequest();
	$request->setNewURLFlag(1);
	$insertQueries  = array();
	$count 		= 0;
	foreach( $rowset as $key=>$rowArr )
	{
	    $count += 14;
	    
	    $subCategoryId 	= isset($rowArr['subCategoryId'])	? $rowArr['subCategoryId'] 	: 1;
	    $LDBCourseId 	= isset($rowArr['LDBCourseId'])		? $rowArr['LDBCourseId']	: 1;
	    $countryId 		= isset($rowArr['countryId'])		? $rowArr['countryId']		: 2;
	    $stateId 		= isset($rowArr['stateId'])		? $rowArr['stateId']		: 1;
	    $cityId 		= isset($rowArr['cityId'])		? $rowArr['cityId']		: 1;
	    $localityId		= isset($rowArr['localityId'])		? $rowArr['localityId']		: 0;
	    $virtualCityId	= isset($rowArr['virtualCityId'])	? $rowArr['virtualCityId']	: 0;
		
	    $request->setData(array('categoryId'	=> isset($rowArr['categoryId'])		? $rowArr['categoryId'] 	: 1,
				    'subCategoryId' 	=> isset($rowArr['subCategoryId'])	? $rowArr['subCategoryId'] 	: 1,
				    'LDBCourseId'	=> isset($rowArr['LDBCourseId'])	? $rowArr['LDBCourseId']	: 1,
				    'countryId'		=> isset($rowArr['countryId'])		? $rowArr['countryId']		: 2,
				    'stateId'		=> isset($rowArr['stateId'])		? $rowArr['stateId']		: 1,
				    'cityId'		=> isset($rowArr['cityId'])		? $rowArr['cityId']		: 1,
				    'zoneId'		=> isset($rowArr['zoneId'])		? $rowArr['zoneId']		: 0,
				    'localityId'	=> isset($rowArr['localityId'])		? $rowArr['localityId']		: 0,
				    'examName'		=> isset($rowArr['examName'])		? $rowArr['examName']		: 'none',
				    'affiliation'	=> isset($rowArr['affiliationVal'])	? $rowArr['affiliationVal']	: 'none',
				    'feesValue'		=> isset($rowArr['final_fees'])		? intVal($rowArr['final_fees'])	: 'none'
				    ));
	    
	    
	    // LDB Course + locality
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + locality
	    $request->setData(array('LDBCourseId'	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
    
	    // Category	+ city	    
	    $request->setData(array('localityId' 	=> 0));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + city
	    $request->setData(array('subCategoryId'	=> $subCategoryId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // LDB Course + city
	    $request->setData(array('LDBCourseId'	=> $LDBCourseId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Category + city
	    $request->setData(array('LDBCourseId'	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );

	    
   	    // Category	+ state
	    $request->setData(array('cityId' 		=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + state
	    $request->setData(array('subCategoryId'	=> $subCategoryId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // LDB Course + state
	    $request->setData(array('LDBCourseId'	=> $LDBCourseId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + locality
	    $request->setData(array('LDBCourseId'	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
   	    // Category	+ country
	    $request->setData(array('stateId' 	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + country
	    $request->setData(array('subCategoryId'	=> $subCategoryId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // LDB Course + country
	    $request->setData(array('LDBCourseId'	=> $LDBCourseId));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    // Sub category + locality
	    $request->setData(array('LDBCourseId'	=> 1));
	    $insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    
	    if( !empty($virtualCityId) )
	    {
		//$virtualCityId
		$request->setData(array('cityId' 	=> $virtualCityId));
		$insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
		
		$request->setData(array('LDBCourseId'	=> $LDBCourseId));
		$insertQueries[] = $this->_getInsertStatement( $rowArr, $request );
	    }
	    
	}
	
	error_log("\n".date("Y-m-d:H:i:s")." Insert Statements for Course Scope : ".$count,3,"/tmp/zeroresults.log");
	
	return $insertQueries;
    }
    
    /**
     * Purpose : Function to create combinations of category pages  based on the fees range
    * 		 eg. if a page exists for fees = 5lacs than page must exists for 1lakh and 2 lakh
     **/    
    public function _createCategoryPageCombinationsForFees( $rowset )
    {
	$feesval 	= $this->CI->config->item("CP_FEES_RANGE");
	$feesval 	= $feesval["RS_RANGE_IN_LACS"];
	$feesRanges 	= array_keys($feesval);
	$newRowEntries	= array();

	foreach( $rowset as $key=>$rowArr )
	{
	    
	    // check if fees values exists for this row or not
	    if($rowArr['final_fees'] && $rowArr['final_fees'] != 'none' )
	    {
		// get the values of ranges of fee less than the fees of the this row
		$newRow 	= $rowArr;
		$index 		= array_search($rowArr['final_fees'], $feesRanges);
		$lowerFeesArr 	= array_slice($feesRanges, $index+1);
		$lowerFeesArr   = array_merge(array("none"),$lowerFeesArr);
		
		// create combinations of the fees less than this fees
		foreach( $lowerFeesArr as $feesCombination )
		{
		    $newRow['final_fees'] = $feesCombination;
		    $newRowEntries[]	= $newRow;
		}
	    }
	}
	
	$rowset = array_merge($rowset , $newRowEntries);
	
	return $rowset;
    }
    
    /**
     * Purpose : Function to create power set of the set provided
     **/
    public function getPowerSet($set)
    {
	$power_set = array();
	
	/* set_size of power set of a set with set_size n is (2**n -1)*/
	$set_size = count($set);
	$pow_set_size = pow(2, $set_size);
	
	/* Run from counter 0 to 15*/
	for($counter = 0; $counter < $pow_set_size; $counter++)
	{
	    $temp_arr = array();
	    for($j = 0; $j < $set_size; $j++)
	    {
		/* Check if jth bit in the counter is set. If set then get jth element from set */
		if($counter & (1<<$j))
			$temp_arr[] = $set[$j];
	    }
	    $power_set[] = $temp_arr;
	}
	
	return $power_set;
	    
    }

    /**
     * Purpose : To resolve the fee value between the two values provided as well as change the affiliation value accordingly
    **/
    public function resolveFeeValueAndAffiliation( $rowset )
    {
	$this->CI->load->service('CurrencyConverterService','categoryList');
	$this->CI->load->service('FeeRangeDeciderService','categoryList');
		
	$baseCurr = "INR";
	$currencyConvertor 	= new CurrencyConverterService($baseCurr);
	$feeRangeDecider 	= new FeeRangeDeciderService();

	// resolve the fees value according to the currency the arrive at a single fee value according from two fees
	foreach( $rowset as $key => $rowArr )
	{
	    	// convert the affiliation values to the onces that are used in front-end
		$rowset[$key]['affiliationVal'] = str_replace(array("status", "Status"), "",$rowArr['affiliation']);
		
		$feeVal = 'none';
		
		if( !empty($rowArr['location_fees_value']) )
		{
		    if( !empty($rowArr['location_fees_unit']))
		    {
			$feeVal = $currencyConvertor->convert( $rowArr['location_fees_value'], $rowArr['location_fees_unit'] );
		    }
		    else
		    {
			$feeVal = $currencyConvertor->convert( $rowArr['location_fees_value'], $baseCurr );
		    }
		}
		else if( !empty($rowArr['common_fees_val']) )
		{
		    if( !empty($rowArr['common_fees_unit']))
		    {
			$feeVal = $currencyConvertor->convert( $rowArr['common_fees_val'], $rowArr['common_fees_unit'] );
		    }
		    else
		    {
			$feeVal = $currencyConvertor->convert( $rowArr['common_fees_val'], $baseCurr );
		    }
		}
		else
		{
		    $feeVal = "none";
		}
		
		if( $feeVal != "none" )
		{
		    $feeRange = $this->CI->config->item("CP_FEES_RANGE");
		    $feeRange = $feeRange["RS_RANGE_IN_LACS"];
		    
		    $calculatedRange = $feeRangeDecider->feesRange($feeVal);
		    
		    if( !$calculatedRange && $calculatedRange == "No Limit" )
		    {
			$calculatedRange = "none";
		    }
		    else
		    {
			$calculatedRange = array_search($calculatedRange, $feeRange);
		    }
		}
	    
	    $rowset[$key]['final_fees'] = $calculatedRange;
	    //error_log("\n".date("Y-m-d:H:i:s")."Calculated Range : ".$calculatedRange." For : 1.".$rowArr['location_fees_value']
		//      ." 2.".$rowArr['location_fees_unit']." 3.".$rowArr['common_fees_val']." 4.".$rowArr['common_fees_unit'],3,"/tmp/fees.log");
	}
	
	return $rowset;	
    }

    /**
     * Purpose : To prepare the insert statement
    **/
    private function prepareInsertStatements( $insertQueries )
    {
	$tableName = $this->categoryPageModelObj->getNonZeroCategoryPageResultTableNameNextMonth();
	$insertQueries = "INSERT into ".$tableName." (category_page_key, category_id, sub_category_id, LDB_course_id, locality_id, zone_id, city_id, city_name, state_id, state_name, country_id, region_id, exam_value, affiliation_value, fees_value) VALUES ".$insertQueries;
	
	return $insertQueries;
    }

    /**
     * Purpose: Function to get Non-zero category pages for the provided criteria
    **/
    public function getNonZeroCategoryPagesForBrowse( $searchCriteria, $type='city')
    {
	$searchDetailsArr['categoryId'] 	= $searchCriteria['categoryId'];
	$searchDetailsArr['subCategoryId']	= $searchCriteria['subCategoryId'];
	$searchDetailsArr['LDB_course_id'] 	= $searchCriteria['LDBCourseId'];
	$searchDetailsArr['country_id'] 	= $searchCriteria['countryId'];
	$searchDetailsArr['exam_value'] 	= $searchCriteria['exam'];
	$searchDetailsArr['affiliation_value'] 	= $searchCriteria['affiliation'];
	$searchDetailsArr['fees_value'] 	= $searchCriteria['fees'];
	$searchDetailsArr['nameInitial'] 	= $searchCriteria['nameInitial'];
	
	$data = $this->categoryPageModelObj->getNonZeroCategoryPagesForBrowse( $searchDetailsArr, $type );
     return $data;
    }

    /**
     * Purpose: Function to get Non-zero category pages data
    **/
    public function getNonZeroCategoryPagesData()
    {
	$data = $this->categoryPageModelObj->getNonZeroCategoryPagesData();
	return $data;
    }    
    
};
