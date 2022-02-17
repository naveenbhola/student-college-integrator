<?php
/**
 * function to get an attribute of an XML element.
 */
class simple_xml_extended extends SimpleXMLElement
{
	public function  Attribute($name)
	{
		foreach($this->Attributes() as $key=>$val)
		{
			if($key == $name)
				return (string)$val;
		}
	}

}

class SearchCI extends MX_Controller
{
	// This varialbe is used in searchCI to switch from older version to newer version
	// It can take two values "new" or "old"
	private $SOLR_RESPONSE_FORMAT = "new";

	var $cacheLib;
	function index()
	{
		$this->init();

		$config['functions']['indexMsgRecord']=array('function'=>'SearchCI.indexMsgRecord');
		$config['functions']['indexListingRecord']=array('function'=>'SearchCI.indexListingRecord');
		$config['functions']['searchListingKeywordCMS']=array('function'=>'SearchCI.searchListingKeywordCMS');
		$config['functions']['getFeaturedImageUrls']=array('function'=>'SearchCI.getFeaturedImageUrls');
		$config['functions']['saveSearch']=array('function'=>'SearchCI.saveSearch');
		$config['functions']['isSaved']=array('function'=>'SearchCI.isSaved');
		$config['functions']['deleteSaveSearch']=array('function'=>'SearchCI.deleteSaveSearch');
		$config['functions']['updateSaveSearchStatus']=array('function'=>'SearchCI.updateSaveSearchStatus');
		$config['functions']['updateSaveSearchFrequency']=array('function'=>'SearchCI.updateSaveSearchFrequency');
		$config['functions']['getSaveSearch']=array('function'=>'SearchCI.getSaveSearch');


		$config['functions']['updateSponsorListingByKeyword'] = array('function' => 'SearchCI.updateSponsorListingByKeyword');
		$config['functions']['addSponsorListingByKeyword'] = array('function' => 'SearchCI.addSponsorListingByKeyword');
		$config['functions']['deleteSponsorListingByKeyword'] = array('function' => 'SearchCI.deleteSponsorListingByKeyword');
		$config['functions']['getSponsorListingStatusByKeyword'] = array('function' => 'SearchCI.getSponsorListingStatusByKeyword');

		$config['functions']['getSearchSnippetCount'] = array('function' => 'SearchCI.getSearchSnippetCount');
		$config['functions']['updateSearchSnippetCount'] = array('function' => 'SearchCI.updateSearchSnippetCount');

		$config['functions']['searchListingWithSponsor']=array('function'=>'SearchCI.searchListingWithSponsor');
		$config['functions']['updateSponsorSnippetCount']=array('function'=>'SearchCI.updateSponsorSnippetCount');
		$config['functions']['getDocumentDetailFromSearch']=array('function'=>'SearchCI.getDocumentDetailFromSearch');

		$config['functions']['shikshaApiSearch']=array('function'=>'SearchCI.shikshaApiSearch');

		$config['functions']['checkValidKeywordForListing']=array('function'=>'SearchCI.checkValidKeywordForListing');

		$config['functions']['deleteListingRecord']=array('function'=>'SearchCI.deleteListingRecord');
		$config['functions']['deleteMsgbrdRecord']=array('function'=>'SearchCI.deleteMsgbrdRecord');

		$config['functions']['getSaveSearchFeeds']=array('function'=>'SearchCI.getSaveSearchFeeds');
		$config['functions']['addSponsorListing']=array('function'=>'SearchCI.addSponsorListing');
		$config['functions']['cancelSubscription']=array('function'=>'SearchCI.cancelSubscription');
		$config['functions']['updateSponsorListingDetails']=array('function'=>'SearchCI.updateSponsorListingDetails');
		$config['functions']['getDataForGenerationOfSeoUrl']=array('function'=>'SearchCI.getDataForGenerationOfSeoUrl');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);
	}

	function init() {
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('messageboardconfig');
		$this->load->library('listing_client');
		$this->load->library('upload_client');
		$this->load->library('search_lib');
		$this->load->helper('url');
		$this->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
		$this->dbLibObj = DbLibCommon::getInstance('Search');
	}
	
	public function getDbReadHandle(){
		$dbHandle = $this->_loadDatabaseHandle();
		return $dbHandle;
	}
	
	public function getDbWriteHandle(){
		$dbHandle = $this->_loadDatabaseHandle('write'); 
		return $dbHandle;
	}
	

	/**
	 * Function to create the URL to search in SOLR.
	 * The various search parameters are taken in and converted to a solr URL, which is called to get the results.
	 * The various parameters are converted to either query or filter query and thus converting into solr Query.
	 * @Input : Solr Base URl , Keyword to Search , Location to Search , Category Id as filter , Country Id as filter , Number of Rows to be Fetched , Starting element count for pagination , Type of Result (Type and Search Type) , City Id as filter , courseType, courseLevel , Duration Filter etc
	 * OutPut: A solr URl which can be queried.
	 */
	function createUrl($baseUrl,$keyw,$location,$categoryId,$countryId,$rows,$start,$type,$searchType='+',$cityId='',$facet=0, $highlight=0, $addHandler=0,$cType='',$courseLevel='',$minDuration='',$maxDuration='')
	{
		$url=$baseUrl;
		if(($keyw!="")&&(isset($keyw)))
		{
			$new_query=explode(" ",$keyw);
			$keyw=implode("%20",$new_query);
			//$url=$url."q=".$keyw."&";
			if(trim($location) == ""){
				$url=$url."q=".$keyw."&";//dhwaj	
			} else {
				$url=$url."q=".$keyw."%20".$location."&";//dhwaj	
			}
		}
       		//dhwaj : location based filter on query are not used in
  		/*if(($location!="")&&(isset($location)))               
                {
                        $new_query=explode(" ",$location);
                        $location=implode("%20",$new_query);
                        $url=$url."loc=".$location."&";
                }*/
		if(isset($start)||$start!="")
		{
			$url=$url."start=".$start."&";
		}
		else
		{
			$url=$url."start=0&";
		}
		if(isset($rows)||$rows!="")
		{
			$url=$url."rows=".$rows."&";
		}
		else
		{
			$url=$url."rows=15&";
		}
		if(isset($categoryId)&&$categoryId!="")
		{
			$url=$url."fq=categoryId:".$categoryId."&";
		}
		if(isset($countryId)&&$countryId!="")
		{
			$url=$url."fq=(countryId:".$countryId.")&";
		}
		if(isset($cityId)&&$cityId!="")
		{
			$url=$url."fq=(cityId:".$cityId.")&";
		}
		if(isset($cType)&&$cType!="")
		{
			$url=$url."fq=(cType:".$cType."%20type:Event%20type:blog%20type:notification%20type:question%20type:scholarship)&";
		}
		if(isset($courseLevel)&&$courseLevel!="")
		{
			$url=$url."fq=(courseLevel:".$courseLevel."%20type:Event%20type:blog%20type:notification%20type:question%20type:scholarship)&";
		}
		if(isset($minDuration) && $minDuration!="" && isset($maxDuration) && $maxDuration!="")
		{
			$url=$url."fq=(sduration:[".$minDuration."%20TO%20".$maxDuration."]%20type:Event%20type:blog%20type:notification%20type:question%20type:scholarship)&";
		}
		if(isset($searchType)&&$searchType!=""&&$searchType!="+")
		{
			switch($searchType)
			{
				case "foreign": $url=$url."fq=countryList:(-india)&";
				break;
				case "testprep": $url=$url."fq=tags:Exam%20Preparation&";
				break;
				case strstr($searchType,'Category'):
				break;
				case "msgbrd": $url=$url."fq=facetype:question&";
				break;
				case "ask":  $url=$url."fq=facetype:question&";
				break;
				case "relatedData":  $url=$url."fq=facetype:question&";
				break;
				case "blog": $url=$url."fq=type:blog&";
				break;
				case 'course': $searchType='institute';
					       break;
					       case "groups": break;
				default: $url=$url."fq=facetype:".$searchType."&";
			}
			if(strstr($searchType,'Category'))
			{
				$catArray=split("-",$searchType);
				error_log_shiksha("Shirish CategoryArray is:".$catArray[1]);
				$url=$url."fq=categoryId:".$catArray[1]."&";
			}
		}
		if(isset($type)&&$type!=""&&$type!="+")
		{
			switch($type)
			{
				case "eventAdm": $url=$url."fq=facetype:Event%20facetype:notification&";
				break;
				case "Category":
					break;
				case "foreign": $url=$url."fq=countryList:(-india)&";
				break;
				case "testprep": $url=$url."fq=tags:Exam%20Preparation&";
				break;
				case "msgbrd": $url=$url."fq=facetype:question&";
				break;
				case "ask":  $url=$url."fq=facetype:question&";
				break;
				case "relatedData":  $url=$url."fq=facetype:question&";
				break;
				case 'course': $searchType='institute';
					       break;
				default: $url=$url."fq=facetype:".$type."&";
			}
		}
		$facet_addition="";
		$highlight_addition="";
		if($facet)
		{
			if($countryId!='')
			{
				$facet_addition="&facet=true&facet.field=facetype&facet.field=countryId&facet.field=cityId&facet.field=cType&facet.field=courseLevel&facet.sort=true&facet.limit=-1&f.cityId.facet.limit=500&facet.zeros=false&";
			}
			else
			{
				$facet_addition="&facet=true&facet.field=facetype&facet.field=countryId&facet.field=cityId&facet.field=cType&facet.field=courseLevel&facet.sort=true&facet.limit=-1&f.cityId.facet.limit=20&facet.zeros=false&";
			}
		}
		if($highlight)
		{
			$highlight_addition="hl=on&hl.fl=title,courseTitle,content,misc_content,instituteList&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=0&f.content.hl.fragsize=200&";
		}
		if($addHandler)
		{
			if($searchType=="collegegroup"||$searchType=="schoolgroups"||$searchType=="groups"|| $searchType=="examgroup")
			{
				$url=$url."qt=groups&";
			}
			elseif($searchType == "relatedData")
			{
				$url=$url."qt=relatedData&";
			}
			else
			{
				$url=$url."qt=shiksha&";
			}
		}
		$url=$url.$facet_addition.$highlight_addition;
		$url=$url."&wt=phps&";
		return($url);
	}

	/**
	 * Some Function that takes in DB handle, table name, data variables so that the same set of statements is not repeated everywhere. May be removed in future
	 */
	function selectDBQuery($dbHandle,$selectQuery,$data)
	{
		$query=$dbHandle->query($selectQuery,array($user_id,$product_id));
		return($query);
	}
	


	/**
	 * getDocCount is a simple function that gets the Number of Documents in the search Result Document
	 * @Input: The xml string from SOLR
	 * @Output: The number of results for the query from SOLR xml
	 */
	function getDocCount($xml)
	{
		return (int)$xml['response']['numFound'];
	}

	function getAttribute(&$result,$attr){
		//error_log("getAtt ".print_r($result[$attr], true));
		return $result[$attr];
	}

	/**
	 * updateSponsorSnippetCount is a function that increases the count of the number of times the listing has been shown as sponsored result
	 * This will increase the count against the listing by one
	 * @Input : Id of the record whose count needs a increase\
	 * @Output : 1/0 (Success/Failure)
	 */
	function updateSponsorSnippetCount($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		$Id=$parameters[1];
		$queryCmd= "update tSponsoredListingByKeyword set count=count+1 where Id= ?";
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		$query = $dbHandle->query($queryCmd, $Id);
		return($this->xmlrpc->send_response(array(1,'int')));
	}

	/**
	 * getDocumentDetailFromSearch is a function that will return the details of a document
	 * This function will return the elements that are in the search index.
	 * @Input : type of document, Id of the document
	 * @Output: the details of the above unique document.
	 */
	function getDocumentDetailFromSearch($request)
	{
		$parameters=$request->output_parameters();
		$type=trim($parameters[1]);
		$Id=trim($parameters[2]);
		if($type == "question")
		{
			$type = "msgbrd";
		}
		$uniqueId=$type.$Id;
		$url=SOLR_SELECT_URL_BASE;
		$url.="q=uniqueId:$uniqueId&fl=*&";
		$url=$url."hl=on&hl.fl=content&hl.fragsize=150";
		error_log_shiksha("HEHEHE".$url);
		$xml_content=$this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha');
		$xml = simplexml_load_string($xml_content, 'simple_xml_extended');
		$results = $xml->xpath('/response/result/doc');
		$highlightResult=$xml->xpath('/response/highlighting');
		$temp_array=array();
		foreach($results as $result)
		{
			$docDetail=$this->getApiDocDetail($result,$highlightResult);
			array_push($temp_array,$docDetail);
		}
		$response=array(array('results'=>array($temp_array,'array')),'struct');
		error_log_shiksha(print_r($response,true));
		return($this->xmlrpc->send_response($response));
	}

	/**
	 * getSponsorListingFromDb is a function that will return the ids of sponsored institutes.
	 * This function will return the sponsored institutes that are in the db.
	 */
	function getSponsorListingFromDb($keyword,$loc,$searchType)
	{
		return; /*
		$this->init();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == null || $dbHandle == '')
		{
			log_message('error','searchListingWithSponsor can not create db handle');
		}
		//if(!isset($loc) || trim($loc)==''){
		//	$loc='%';
		//}
		$baseCmd="(select id, type, listingId, type as type2, listingId as listingId2  from tSponsoredListingByKeyword,listings_main where keyword=? and location like ? and type='institute' and isDeleted=0 and set_time <= now() and unset_time >= now() and sponsorType='sponsor'  and listings_main.listing_type_id=tSponsoredListingByKeyword.listingId and listings_main.listing_type=tSponsoredListingByKeyword.type and listings_main.status='live' group by listingId order by update_time,RAND() ) union (select id, type, listingId, 'institute' as type2, institute_courses_mapping_table.institute_id as listingId2 from tSponsoredListingByKeyword,listings_main, institute_courses_mapping_table where keyword=? and location like ? and type='course' and isDeleted=0 and set_time <= now() and unset_time >= now() and sponsorType='sponsor'  and listings_main.listing_type_id=tSponsoredListingByKeyword.listingId and listings_main.listing_type=tSponsoredListingByKeyword.type and listings_main.status='live' and tSponsoredListingByKeyword.listingId = institute_courses_mapping_table.course_id group by listingId order by update_time,RAND())";
		$queryCmd="select * from($baseCmd)t order by RAND()";
		if($searchType != 'institute') {
			$loc='%';
		}
		$query=$dbHandle->query($queryCmd,array($keyword,$loc,$keyword,$loc));
		return $query; */
	}

	/**
	 * getNewCourseWiseSearchParamAppended is a function that will append parameters to the input url.
	 * This function will append parameters to the query for course wise grouped search.
	 */
	function getNewCourseWiseSearchParamAppended($url){
		$url = str_replace('facetype:institute','facetype:course',$url);
		$url=$url."&wt=phps&group=true&group.field=instituteId&group.limit=20&facet=true&facet.limit=-1&facet.zeros=false&facet.field=instituteId&";

		return $url;
	}

	/**
	 * getSolrResults is a function that will make call to the solr.
	 */
	function getSolrResults($url,$keyw,$start,$rows,$wt,$searchType){
		$xml_content=$this->search_lib->search_curl($url,$keyw,$start,$rows,$wt);
		$xml = unserialize($xml_content);
		if($searchType=="course" || $searchType=="institute"){
			$numfound= count($xml['facet_counts']['facet_fields']['instituteId']);
			$results = $xml['grouped']['instituteId']['groups'];
			$highlightResult=$xml['highlighting'];
			$numCourses = (int)$xml['grouped']['instituteId']["matches"];
		}
		else{
			$numfound =  $xml['response']['numFound'];
			$highlightResult=$xml['highlighting'];
			$results = $xml['response']['docs'];
		}
		$solrResultArray=array('xml'=>$xml, 'numfound'=>$numfound,'numCourses'=>$numCourses,'resultP'=>$resultP,'results'=>$results,'highlightResult'=>$highlightResult);

		return $solrResultArray;
	}

	/**
	 * appendSponsoredListingToResults is a function that will return the detailed documents (search result format) of sponsored institutes.
	 */
	function appendSponsoredListingToResults($start,$rows,$keyw,$location,$countryId,$categoryId,$type,$searchType,$cityId,$facet,$cType,$courseLevel,$minDuration,$maxDuration){
		return; /*
		$keyword=preg_replace("/\+/"," ",$keyw);
		$loc=preg_replace("/\+/"," ",$location);

		
		$temp_array=array();
		$IdArray=array();
		$query = $this->getSponsorListingFromDb($keyword,$loc,$searchType);
		$i=0;
		foreach ($query->result() as $row)
		{
			array_push($IdArray,array('id'=>$row->type2.$row->listingId2,'type2'=>$row->type,'id2'=>$row->listingId));
			if($i<2)
			{
				$ListingClientObj = new Listing_client();
				$searchResult = $ListingClientObj->updateSponsorSnippetCount(12,$row->id);
			}
			$i+=1;
		}
		$i=0;
		foreach ($IdArray as $row)
		{
			if($i<2)
			{
				$uniqueId=str_replace("institute","",$row['id']);
				$url=$this->search_lib->getSolrUrlByType($type,"select");
				$url=$this->createUrl($url,$keyw,$location,$categoryId,$countryId,2,0,$type,$searchType,$cityId,$facet,1,"shiksha",$cType,$courseLevel,$minDuration,$maxDuration);
				$url.="&fq=instituteId:$uniqueId";
				$url = $this->getNewCourseWiseSearchParamAppended($url);
				$solrRes= $this->getSolrResults($url,$keyw,$start,$rows,'shiksha',$searchType);
				error_log("dhwaj sponsored solr res".print_r($solrRes,true));
				$xml = $solrRes['xml'];
				$numfound= $solrRes['numfound'];
				error_log("dhwaj sponsor".$url." numfound=".$numfound);
				//$xml = simplexml_load_string($xml_content, 'simple_xml_extended');
				if($numfound == 0)
				{
					error_log("dhwaj sponsor inside num=0");
					$url=$this->search_lib->getSolrUrlByType($type,"select");
					$url = $url."&wt=phps&q=instituteId:$uniqueId";

					$url = $this->getNewCourseWiseSearchParamAppended($url);
					$solrRes= $this->getSolrResults($url,$keyw,$start,$rows,'shiksha',$searchType);
					$xml = $solrRes['xml'];
					$numfound= $solrRes['numfound'];

				}

				$results = $solrRes['results'];
				error_log("dhwaj sponsor results ".print_r($results,true));
				$highlightResult=$solrRes['highlightResult'];
				foreach($results as $result)
				{
					$docDetail=$this->getDocDetailForInstitute($result,$highlightResult,1);
					error_log("dhwaj sponsor docDetail". print_r($docDetail, true));
					$docDetail[0]['sponsorType'] = $row['type2'];
					$docDetail[0]['sponsorTypeId'] = $row['id2'];
					$this->increaseSearchSnippetCount($docDetail[0]['typeId'],$docDetail[0]['type'],'sponsored',$i,$start,$rows,$keyw,$location,$countryId,$categoryId,$searchType);
					array_push($temp_array,$docDetail);
				}
			}
			$i+=1;

		}
		$sponsoredResArr = array('temp_array'=>$temp_array,'IdArray'=>$IdArray);
		return $sponsoredResArr;
		*/
	}

	/**
	 * getQEROutputParamAppended is a function that will form the query for QER.
	 */
	function getQEROutputParamAppended($requestParams){
		error_log("15mar in getQEROutputParamAppended function");
		if(USE_QER == "true"){
			$curlClient = new Upload_client();
			$qerUrl = QER_URL;
			$qerParam = array();
			$qerUrl = $qerUrl."?inkeyword=".$requestParams['keyw']."+".$requestParams['location'];
			$qerUrl = $qerUrl."&output=solrquery&action=Submit";
			$entityQuery = $curlClient->makeCurlCall($qerParam, $qerUrl);
		} else {
			$entityQuery = "";
		}
		if($entityQuery != null && isset($entityQuery) && $entityQuery!='q.alt=*%3A*&' && $entityQuery!='q.alt=*:*&'){
			$url = $this->createUrl($requestParams['url'].$entityQuery."&hl.q=".$requestParams['keyw']."+".$requestParams['location']."&", "", "", $requestParams['categoryId'], $requestParams['countryId'] ,$requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		} else {
			$url=$this->createUrl($requestParams['url']."&hl.q=".$requestParams['keyw']."+".$requestParams['location']."&", $requestParams['keyw'], $requestParams['location'], $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		}
		$returnArray = array();
		$returnArray['url'] = $url;
		$returnArray['entity_query'] = $entityQuery;
		return $returnArray;
	}

	function getSpecificCluster($clusterResults,$specificId){
		$resultArray=array();
		foreach($clusterResults as $key=>$value)
		{
			$key=trim($key);
			if($specificId!='')
			{
				if($key==$specificId)
				{
					$resultArray[$key]=$value.'';
				}
			}
			else
			{
				$resultArray[$key]=$value.'';
			}
		}
		return $resultArray;
	}
	/**
	 * getClustersForSearchFaceting is a function that will return the clusters of group candidates.
	 * This function will return the sponsored institutes that are in the db.
	 */
	function getClustersForSearchFaceting($xml,$searchType,$countryId,$cityId){
		$countryClusterResults=array();
		$typeClusterResults=array();
		$resultArray=array();

		$countryClusterResults = $xml['facet_counts']['facet_fields']["countryId"];
		$resultArrayCountry=$this->getSpecificCluster($countryClusterResults,$countryId);

		$typeClusterResults = $xml['facet_counts']['facet_fields']['facetype'];
		$resultArrayType=$this->getSpecificCluster($typeClusterResults,'');
		
		$cityClusterResults = $xml['facet_counts']['facet_fields']['cityId'];
		$resultArrayCity=$this->getSpecificCluster($cityClusterResults,$cityId);


		$resultArrayCourseType = array();
		$resultArrayCourseLevel = array();
		if($searchType == 'institute')
		{
			$courseTypeClusterResults = $xml['facet_counts']['facet_fields']['cType'];
			$resultArrayCourseType=$this->getSpecificCluster($courseTypeClusterResults,'');
			/*foreach($courseTypeClusterResults as $key=>$value)
			{
				foreach($value as $x=>$y)
				{
					$resultArrayCourseType[$x]=$y.'';
				}

			}*/
			$courseLevelClusterResults = $xml['facet_counts']['facet_fields']['courseLevel'];
			$resultArrayCourseLevel=$this->getSpecificCluster($courseLevelClusterResults,'');
		}
		$clusters =array();
		array_push($clusters,
				array(
					array(
						'type'=>array($typeClusterResults,'struct'),
						'country'=>array($resultArrayCountry,'struct'),
						'city'=>array($resultArrayCity,'struct'),
						'courseType'=>array($courseTypeClusterResults,'struct'),
						'courseLevel'=>array($courseLevelClusterResults,'struct')
					     ),'struct')
			  );
		return array('clusters'=>$clusters,'resultArrayCity'=>$resultArrayCity);
	}

	
	public function getNormalSearchResultsUsingQER($requestParams = array(), $resultsToBeExcluded = array()){
		error_log("15mar in getNormalSearchResultsWithQER function");
		$searchType = (!empty($requestParams['searchType'])) ? $requestParams['searchType'] : "institute";
		$url = $this->search_lib->getSolrUrlByType($searchType, "select");
		//Solr URL modification starts
		$QERQueryString = "";
		if($searchType == "institute" || $searchType == "course"){
			$requestParams["url"] = $url;
			$qerOutputArray = $this->getQEROutputParamAppended($requestParams);
			$url = $qerOutputArray['url'];
			$QERQueryString = $qerOutputArray['entity_query'];
			$url = $this->getNewCourseWiseSearchParamAppended($url);
		} else {
			$url = $this->createUrl($url."&hl.q=".$requestParams['keyw']."+".$requestParams['location']."&", $requestParams['keyw'], $requestParams['location'], $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		}
		// add relax param to solr url
		if($requestParams['relaxFlag'] !=0 ){
			$url = $url . "&rlx=true&";
			$url = $url . "&mm=1&";
		}
		// avoid results that are already coverd in sponsor results
		foreach($resultsToBeExcluded as $row){
			$uniqueId = $row['id'];
			$url = $url."&fq=-uniqueId:".$uniqueId."&";
		}
		$solrResults = $this->getSearchResults($url, $requestParams['keyw']." ".$requestParams['location'], $requestParams['start'], $requestParams['rows'], $searchType);
		$solrResults['qerquerystring'] = $QERQueryString;
		return $solrResults;
	}
	
	public function getNormalSearchResultsUsingSpellCheckAndQER($requestParams = array()){
		$keywordAfterSpellCheck = $this->spellChecker($requestParams["keyw"] ." ". $requestParams["location"], 1);
		$url = $this->search_lib->getSolrUrlByType($requestParams['searchType'], "select");
		$QERQueryString = "";
		if($requestParams['searchType'] == "institute" || $requestParams['searchType'] == "course"){
			$requestParams["url"] = $url;
			$requestParams["keyw"] = $keywordAfterSpellCheck;
			$qerOutputArray = $this->getQEROutputParamAppended($requestParams);
			$url = $qerOutputArray['url'];
			$QERQueryString = $qerOutputArray['entity_query'];
			$url = $this->getNewCourseWiseSearchParamAppended($url);
		} else{
			$url = $this->createUrl($url, $keywordAfterSpellCheck, "", $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		}
		$solrResults = $this->getSearchResults($url, "", $requestParams['start'], $requestParams['rows'], $requestParams['searchType']);
		$solrResults["spellCheckedKeyword"] = $keywordAfterSpellCheck;
		$solrResults["solrUrlWithSpellCheckedKeyword"] = $url;
		$solrResults['qerquerystring'] = $QERQueryString;
		return $solrResults;
	}
	
	public function getNormalSearchResultsUsingSpellCheckedKeyword($keyword, $spellCheckedKeyword, $requestParams){
		error_log("15mar in getNormalSearchResultsUsingSpellCheckedKeyword function");
		$url = $this->search_lib->getSolrUrlByType($requestParams['searchType'], "select");
		$searchAltKeyword = "";
		if(trim($spellCheckedKeyword) != ""){
			$searchAltKeyword = trim(str_replace("+"," ",$spellCheckedKeyword));
			$flagPro = 2;
			$url = $this->createUrl($url, $spellCheckedKeyword, "", $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		} else {
			$flagPro=0;
			$url = $this->createUrl($url, $keyword, $requestParams['location'], $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
		}
		if($requestParams['searchType'] == "institute" || $requestParams['searchType'] == "course"){
			$url = $this->getNewCourseWiseSearchParamAppended($url);
		}
		$solrResults = $this->getSearchResults($url, "", $requestParams['start'], $requestParams['rows'], $requestParams['searchType']);
		$solrResults["flagPro"] = $flagPro;
		$solrResults["searchAltKeyword"] = $searchAltKeyword;
		$solrResults["solrUrl"] = $url;
		
		$urlParamArray = $this->search_lib->getURLParamValue($url, array("q", "q.alt", "fq"), array("facetype:course"));
		$urlParamString = $this->search_lib->getURLParamString($urlParamArray);
		$solrResults['qerquerystring'] = $urlParamString;
		return $solrResults;
	}
	
	public function getNormalSearchResultUsingExtraFields($url, $requestParams, $fieldsWithBoost = array(), $operatorType = NULL) {
		$flagPro=0;
		$fieldsWithBoostString = "";
		if(!empty($fieldsWithBoost) && is_array($fieldsWithBoost)){
			foreach($fieldsWithBoost as $key=>$value){
				if($value == NULL){
					$fieldsWithBoostString .= $key."+";
				} else {
					$fieldsWithBoostString .= $key."^".$value."+";
				}
			}	
		}
		$url = $url . "&qf=" . $fieldsWithBoostString . "&";
		if($operatorType != NULL){
			$url .= "&q.op=".$operatorType."&";
		}
		$solrResults = $this->getSearchResults($url, "", $requestParams['start'], $requestParams['rows'], $requestParams['searchType']);
		$solrResults["flagPro"] = $flagPro;
		$urlParamArray = $this->search_lib->getURLParamValue($url, array("q", "q.alt", "fq"), array("facetype:course"));
		$urlParamString = $this->search_lib->getURLParamString($urlParamArray);
		$solrResults['qerquerystring'] = $urlParamString;
		
		return $solrResults;
	}
	
	public function getNormalSearchResultByEliminateQERParams($url, $qerParams = array(), $requestParams = array(), $QERQueryString = NULL){
		
		$qerParamsExploded = explode("&", $QERQueryString);
		$tempQerParams = array();
		foreach($qerParamsExploded as $key=>$value){
			if(trim($value) != ""){
				$tempQerParams[] = $value;
			}
		}
		$qerParamsPresent = count($tempQerParams);
//		error_log("11apr qerpparams array : " . print_r($tempQerParams, true));
		
		$finalResult = array();
		$qerParamCounter = 0;
		if(!empty($qerParams) && $qerParamsPresent > 1){
			$originalUrl = $url;
			foreach($qerParams as $paramKey=>$paramValue){
				if($paramValue == "rawTextQuery"){ // special handling for rawTextQuery
					if(stripos($originalUrl, "q.alt") !== false){
						$key = "q.alt=";	
					} else {
						$key = "q=";
					}
				} else {
					$filterPrefix = "fq=";
					$key = $filterPrefix . $paramValue;
				}
				$start = false;
				$keyTypeFirst = "?".$key;
				$keyTypeSecond = "&".$key;
				$strPositionForKeyTypeFirst = stripos($originalUrl, $keyTypeFirst);
				$strPositionForKeyTypeSecond = stripos($originalUrl, $keyTypeSecond);
				if($strPositionForKeyTypeFirst !== false){ // check for ?key 
					$start = $strPositionForKeyTypeFirst + 1;
					error_log("11apr keyTypeMatched : " . "?".$key);
				} else if($strPositionForKeyTypeSecond !== false){
					$start = $strPositionForKeyTypeSecond + 1;
					error_log("11apr keyTypeMatched : " . "&".$key);
				}
				error_log("11apr start : " . $start);
				if($start !== false && $qerParamsPresent > 1) {
					$urlStringLength = strlen($originalUrl);
					$substr =  substr($originalUrl, $start, $urlStringLength);
					$ampersandPosition = stripos($substr, "&");
					if($ampersandPosition === false) { //no ampersand found after filter parameter, means its the last param of URL
						$end = $urlStringLength;
					} else { //ampersand found after filter parameter
						$end = $ampersandPosition;
					}
//					error_log("11apr part eliminated : " . print_r(substr($originalUrl, $start, $end), true));
					if($paramValue == "rawTextQuery"){ // special handling for rawTextQuery
						$originalUrl = substr_replace($originalUrl, 'q.alt=*:*', $start, $end);
					} else {
						$originalUrl = substr_replace($originalUrl, '', $start, $end);
					}
					$solrResults = $this->getSearchResults($originalUrl, "", $requestParams['start'], $requestParams['rows'], $requestParams['searchType']);
					$numFound = $solrResults['numfound'];
					if($numFound > 0){ // after removal of current qer param we have some result, so break the loop here and return
						$urlParamArray = $this->search_lib->getURLParamValue($originalUrl, array("q", "q.alt", "fq"), array("facetype:course"));
						error_log("11apr in numfound > 0");
//						error_log("11apr originalUrl " . print_r($originalUrl, true));
//						error_log("11apr urlParamArray " . print_r($urlParamArray, true));
						$urlParamString = $this->search_lib->getURLParamString($urlParamArray);
						$solrResults['qerquerystring'] = $urlParamString;
						$finalResult = $solrResults;
//						error_log("11apr getURLParam : " . print_r($urlParamArray, true));
						break;
					}
					$qerParamsPresent--;
				}
			}
		}
		//error_log("15mar final result array in eliminateQERparams " . print_r($finalResult, true));
		return $finalResult;
	}
	
	public function getSearchResults($url, $keyword, $start, $rows, $searchType, $queryType = 'shiksha'){
		//error_log("15mar in getSearchResults function");
		$solrRes = $this->getSolrResults($url, $keyword, $start, $rows, $queryType, $searchType);
		$returnResult = $solrRes;
		//error_log("15mar returnresult " . print_r($returnResult, true));
		return $returnResult;
	}
	
	/**
	 * searchListingWithSponsor is a webservice API that takes in a set of parameters to search such as keyword, location etc and then retuerns the list of
	 * results for the query.
	 * The Api first checks for the sponsored listings for the given keyword and location. Then, we make a solr query and try to get the results. In case there are less than 10 results then we make another query to the SOLR, after spellchecking, and with relaxFlag as an OR search.
	 * @Input : keyword, location, countryId, categoryId, rows, start, type ,searchType , cityId, relaxFlag, courseType, CourseLevel, max and min Duration
	 * @Output: the set of results and clusters of type, location, courseType, courseLevel and Duration
	 * @TODO: Split this one function to multiple functions. Too many things happening
	 */
	function searchListingWithSponsor($request)
	{
		return; /*
		error_log("15mar entering searchListingWithSponsor");
		$requestParams = array();
		$parameters = $request->output_parameters();
		$requestParams['keyw'] = strtolower(urlencode(addcslashes($parameters[1],'\"')));
		$requestParams['location'] = strtolower(urlencode($parameters[2]));
		$requestParams['countryId'] = trim($parameters[3]);
		$requestParams['categoryId'] = trim($parameters[4]);
		$requestParams['start'] = trim($parameters[5]);
		$requestParams['rows'] = trim($parameters[6]);
		$requestParams['type'] = trim($parameters[7]);
		$requestParams['searchType'] = trim($parameters[8]);
		$requestParams['cityId'] = trim($parameters[9]);
		$requestParams['relaxFlag'] = trim($parameters[10]);
		$requestParams['cType'] = trim($parameters[11]);
		$requestParams['courseLevel'] = trim($parameters[12]);
		$requestParams['minDuration'] = trim($parameters[13]);
		$requestParams['maxDuration'] = trim($parameters[14]);
		$requestParams['listingDetail'] = trim($parameters[15]);
		$requestParams['facet'] = 1;
		$requestParams['highlight'] = 1;
		$requestParams['addHandler'] = 1;
		// variables declaration
		$IdArray=array();
		$temp_array=array();
		$ext_array=array();
		$zeroResultCase = true;
		// solr schema fields with respective boost value
		$fieldsWithBoosts = array();
		$fieldsWithBoosts = $this->search_lib->getSearchSchemaFieldsWithBoost();
		//QER fields
		$QERFields = array();
		$QERFields = $this->search_lib->getQERFields();
		
		// fetch sponsor results					
		if(($requestParams['searchType'] != 'Event')){
			$sponsoredResArr = $this->appendSponsoredListingToResults($requestParams['start'], $requestParams['rows'], $requestParams['keyw'], $requestParams['location'], $requestParams['countryId'], $requestParams['categoryId'], 'institute', 'institute', $requestParams['cityId'], $requestParams['facet'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
			$temp_array = $sponsoredResArr['temp_array'];
			$IdArray = $sponsoredResArr['IdArray'];
		}
		
		//fetch normal results for query
		$solrResultsWithQER = $this->getNormalSearchResultsUsingQER($requestParams, $IdArray);
		$resultCountWithQER = $solrResultsWithQER['numfound'];
		$QERQueryString =  $solrResultsWithQER['qerquerystring'];
		$initialQERQuery = $QERQueryString;
		$functionCallReturnInfo['return_from'] = "QER";
		$searchResults = $solrResultsWithQER;
		if($resultCountWithQER > 0){
			$zeroResultCase = false;
		}
		
		if($resultCountWithQER == 0) {
			$solrResultsWithSpellCheckAndQER = $this->getNormalSearchResultsUsingSpellCheckAndQER($requestParams); //apply QER to search query and fetch results
//			error_log("11apr result : " . print_r($solrResultsWithSpellCheckAndQER, true));
			
			$resultCountWithSpellCheckAndQER = $solrResultsWithSpellCheckAndQER['numfound'];
			$numCourses = $solrResultsWithSpellCheckAndQER['numCourses'];
			$searchResults = $solrResultsWithSpellCheckAndQER;
			$spellCheckedKeyword = $solrResultsWithSpellCheckAndQER['spellCheckedKeyword'];
			$solrUrlWithSpellCheckedKeyword = $solrResultsWithSpellCheckAndQER['solrUrlWithSpellCheckedKeyword'];
			$QERQueryString =  $solrResultsWithSpellCheckAndQER['qerquerystring']; // this QERString will be used in QERParamEliminate function
			$functionCallReturnInfo['return_from'] = "SPELL_CHECK_QER";
		
			if($resultCountWithSpellCheckAndQER == 0){ // use spell checked word or normal word
				$solrResultWithSpellCheckedKeyword = $this->getNormalSearchResultsUsingSpellCheckedKeyword($requestParams['keyw'], $spellCheckedKeyword, $requestParams);
				//$searchAltKeyword = $solrResultWithSpellCheckedKeyword['searchAltKeyword'];
				//$flagPro = $solrResultWithSpellCheckedKeyword['flagPro'];
				$resultCountWithSpellCheckedKeyword = $solrResultWithSpellCheckedKeyword['numfound'];
				$solrUrl = $solrResultWithSpellCheckedKeyword['solrUrl'];
				$searchResults = $solrResultWithSpellCheckedKeyword;
				$functionCallReturnInfo['return_from'] = "SPELL_CHECKED_OR_NORMAL_WORD_NO_QER";
				
				if($resultCountWithSpellCheckedKeyword == 0) { 
					$solrResultByEliminateQER = $this->getNormalSearchResultByEliminateQERParams($solrUrlWithSpellCheckedKeyword, $QERFields, $requestParams, $QERQueryString);
					$solrResultCount = $solrResultByEliminateQER['numfound'];
					$searchResults = $solrResultByEliminateQER;
					$functionCallReturnInfo['return_from'] = "SPELL_CHECK_QER_PARAM_ELIMINATE";
					$QERQueryString =  $solrResultByEliminateQER['qerquerystring'];
				
					if($solrResultCount == 0) { //still zero, use the solr URL and use content field with 0.1 boost
						$tempFieldsWithBoost = $fieldsWithBoosts;
						$tempFieldsWithBoost['content'] = 0.1;
						$solrResultsUsingFields = $this->getNormalSearchResultUsingExtraFields($solrUrl, $requestParams, $tempFieldsWithBoost, NULL);
						//$flagPro = $solrResultsUsingFields["flagPro"];
						$searchResults = $solrResultsUsingFields;
						$resultCountUsingFields = $solrResultsUsingFields["numfound"];
						$QERQueryString =  $solrResultsUsingFields['qerquerystring'];
						$functionCallReturnInfo['return_from'] = "SEARCH_CONTENT_FIELD";
						
						if($resultCountUsingFields < 5) { // still zero use plain keyword and OR operator instead of and
							$url = $this->search_lib->getSolrUrlByType($requestParams['searchType'], "select");
							$url = $this->createUrl($url, $requestParams['keyw'], $requestParams['location'], $requestParams['categoryId'], $requestParams['countryId'], $requestParams['rows'], $requestParams['start'], $requestParams['type'], $requestParams['searchType'], $requestParams['cityId'], $requestParams['facet'], $requestParams['highlight'], $requestParams['addHandler'], $requestParams['cType'], $requestParams['courseLevel'], $requestParams['minDuration'], $requestParams['maxDuration']);
							if($requestParams['searchType'] == "institute" || $requestParams['searchType'] == "course"){
								$url = $this->getNewCourseWiseSearchParamAppended($url);
							}
							$url = str_replace("qt=shiksha", "", $url);
							$tempFieldsWithBoost = $fieldsWithBoosts;
							$tempFieldsWithBoost['content'] = 0.1;
							$solrResultsUsingFields = $this->getNormalSearchResultUsingExtraFields($url, $requestParams, $tempFieldsWithBoost, "OR");
							$searchResults = $solrResultsUsingFields;
							$resultCountUsingFields = $solrResultsUsingFields["numfound"];
							$QERQueryString =  $solrResultsUsingFields['qerquerystring'];
							$functionCallReturnInfo['return_from'] = "OR_SEARCH_QUERY";
							$searchAltKeyword = trim(str_replace("+"," OR ", $requestParams['keyw']));
							$flagPro = 2;
							// final num found : $resultCountUsingFields
						}
					}
				} else{
					if(strlen(trim($spellCheckedKeyword)) > 0){
						//$searchAltKeyword = trim(str_replace("+"," ", $spellCheckedKeyword));
						//$flagPro=2;
					}
				}
			}
		}
		$numfound = $searchResults['numfound'];
		$numCourses = $searchResults['numCourses'];
		$finalQERQuery = $QERQueryString;
		$functionCallReturnInfo['initial_qer_query'] = $initialQERQuery;
		$functionCallReturnInfo['final_qer_query'] = $finalQERQuery;
//		error_log("11apr call return info : " . print_r($functionCallReturnInfo, true));
		
		$clusters = array();
		$resultArrayCity = array();
		if($requestParams['searchType'] == "institute" || $requestParams['searchType'] == "course"){
			$clustersRes = $this->getClustersForSearchFaceting($searchResults['xml'], $requestParams['searchType'], $requestParams['countryId'], $requestParams['cityId']);
			$clusters = $clustersRes['clusters'];
			$resultArrayCity = $clustersRes['resultArrayCity'];
		}
		$sponsoredCount = count($IdArray);
		if($sponsoredCount > 2) {
			$sponsoredCount = 2;
		}
		$numfound += $sponsoredCount;

		$highlightResult = $searchResults['highlightResult'];
		$results = $searchResults['results'];
		$i=0;
		foreach($results as $result) {
			$docDetail = $this->getDocDetail($result, $highlightResult, 0, 0, $requestParams['searchType']);
			$this->increaseSearchSnippetCount($docDetail[0]['typeId'], $docDetail[0]['type'], 'normal', $i, $requestParams['start'], $requestParams['rows'], $requestParams['keyw'], $requestParams['location'], $requestParams['countryId'], $requestParams['categoryId'], $requestParams['searchType']);
			array_push($temp_array,$docDetail);
			$i++;
		}
		$cityCountryMap = $this->getCityCountryName($resultArrayCity);
		$response = array(array('numOfRecords'=>array($searchResults['numfound'],'int'), 'zero_result_case'=>array($zeroResultCase,'boolean'),'numOfCourse'=>array($numCourses,'int'), 'call_return_from'=>array($functionCallReturnInfo['return_from'],'string'), 'initial_qer_query'=>array($initialQERQuery,'string'), 'final_qer_query'=>array($finalQERQuery,'string'), 'relaxedFlag'=>array($flagPro,'int'),'altKeyWord'=>$searchAltKeyword,'results'=>array($temp_array,'array'),'cluster'=>array($clusters,'struct'),'cityCountryNameMap'=>array($cityCountryMap,'struct'),'extKeyword'=>$searchExtKeyword,'extenedResults'=>array($ext_array,'array') ),'struct');
		return $this->xmlrpc->send_response($response); */
	}

	function getDocDetailForQuestions($result,$highlightResult,$isSponsored,$isSaveSearch){
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;

		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId="msgbrd".$Id;
		$highlightArray=$highlightResult["$uniqueId"];
		if(isset($highlightArray['content']))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray['content']),'<b>');
		}
		//$url=(string)$result->url;
		$url = getSeoUrl($Id,$type,$titleName);
		
		$facetype=(string)$result->facetype;
		if(isset($highlightArray['title']))
		{
			$title=$highlightArray['title'];
		}	
		else
		{
			$title=$titleName;
		}
		$questionInfo = (string)$result->questionUserInfo;
		$questionUserInfo = unserialize(html_entity_decode($questionInfo));
		$viewCount = (string)$result->noOfViews;
		$noOfComments = (string)$result->noOfComments;
		$creationTime = (string)$result->questionTime;
		$instituteName= (string)$result->instituteName;
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'noOfComments'=>$noOfComments,'viewCount'=>$viewCount,'creationDate'=>$creationTime,'userlevel'=>$questionUserInfo['userLevel'],'displayname'=>$questionUserInfo['displayname'],'imageUrl'=>$questionUserInfo['userImage'],'profession'=>$questionUserInfo['profession'],'isSponsored'=>$isSponsored,'instituteName'=>$instituteName),'struct'));

	}

	function getDocDetailForCourse($result,$highlightResult,$isSponsored,$isSaveSearch){
		$this->init();
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$fees=(string)$result->fees;
		$duration=(string)$result->duration;
		$eligibility=(string)$result->eligibility;
		$imageUrl=(string)$result->imageUrl;
		$instituteName=(string)$result->instituteList;
		$instituteId=(string)$result->instituteId;
		$packtype=(string)$result->packtype;
		$contact_email=(string)$result->contact_email;
		if(isset($highlightArray->instituteList))
		{
			$instituteList=$highlightArray->instituteList;
		}
		else
		{
			$instituteList=$instituteName;
		}
		if(isset($highlightArray->title))
		{
			$title=$highlightArray->title;
		}
		else
		{
			$title=$titleName;
		}
		$cityIdList=(string)$result->cityId;
		$cityIdArray=explode(" ",trim($cityIdList));
		foreach($cityIdArray as $cityId)
		{
			
			/*
			if(!apc_fetch("city2Country_".$cityId))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city2Country_".$cityId))
			{
				$cityCountry=apc_fetch("city2Country_".$cityId);
			}
			*/
			$cacheTempKey = "city2Country_".$cityId;
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE') {
				$cityCountry = $cacheKeyValue;
			}
			
			if(isset($location)){
				$location=$location.",".$cityCountry;
			}
			else
			{
				$location=$cityCountry;
			}
		}
		$optionalArgs['location']=split(",",$location);
		$optionalArgs['institute']=$instituteName;
		error_log_shiksha($instituteName);
		$url=getSeoUrl($Id,$type,$titleName,$optionalArgs);
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'fees'=>$fees,'duration'=>$duration,'eligibility'=>$eligibility,'imageUrl'=>$imageUrl,'college'=>$instituteList." ".$location,'collegeName'=>$instituteList,'collegeId'=>trim($instituteId),'packtype'=>$packtype,'contact_email'=>$contact_email,'isSponsored'=>$isSponsored),'struct'));
	}

	function getDocDetailForScholarship($result,$highlightResult,$isSponsored,$isSaveSearch){
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$url=getSeoUrl($Id,$type,$titleName);
		$value=(string)$result->value;
		$number=(string)$result->number;
		$imageUrl=(string)$result->imageUrl;
		$eligibility=(string)$result->eligibility;
		$applicableTo=(string)$result->levels;
		if(isset($highlightArray->title))
		{
			$title=$highlightArray->title;
		}
		else
		{
			$title=$titleName;
		}
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'eligibility'=>$eligibility,'applicableTo'=>$applicableTo,'number'=>$number,'value'=>$value,'imageUrl'=>$imageUrl,'isSponsored'=>$isSponsored),'struct'));

	}

	function getDocDetailForNotification($result,$highlightResult,$isSponsored,$isSaveSearch){
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$url=getSeoUrl($Id,$type,$titleName);
		$value=(string)$result->value;
		$number=(string)$result->number;
		$endDate=date('jS M, y, h:ia',strtotime($result->endDate));
		$imageUrl=(string)$result->imageUrl;
		$instituteList=(string)$result->instituteList;
		if(isset($highlightArray->instituteList))
		{
			$instituteList=$highlightArray->instituteList;
		}
		if(isset($highlightArray->title))
		{
			$title=$highlightArray->title;
		}
		else
		{
			$title=$titleName;
		}
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'number'=>$number,'value'=>$value,'imageUrl'=>$imageUrl,'endDate'=>$endDate,'collegeInfo'=>$instituteList,'isSponsored'=>$isSponsored),'struct'));

	}

	function getDocDetailForInstitute($result,$highlightResult,$isSponsored,$isSaveSearch){
		$this->init();
		$ListingClientObj = new Listing_client();
		$Id=$result["groupValue"];
		$resultDocs=$result['doclist'];
		$type="institute";//$result["type"];
		$titleName=$resultDocs['docs'][0]["title"];
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=$resultDocs['docs'][0]["content"];
		}
		else
		{
			$shortContent="";
		}
		$imageCount=$resultDocs['docs'][0]["imageCount"];
		$videoCount=$resultDocs['docs'][0]["videoCount"];
		$categoryId=$resultDocs['docs'][0]["cat_s"];
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[$uniqueId];
		//error_log("Shivam highlightResult Array ".print_r($highlightArray,true));
		if(isset($highlightArray["content"]))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray["content"]),'<b>');
		}
		$cityIdList=$resultDocs['docs'][0]["cityId"];
		$contact_email=$resultDocs['docs'][0]["contact_email"];
		$cityIdArray=explode(" ",trim($cityIdList));
		foreach($cityIdArray as $cityId)
		{
			/*
			if(!apc_fetch("city2Country_".$cityId))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city2Country_".$cityId))
			{
				$cityCountry=apc_fetch("city2Country_".$cityId);
			}
			*/
			$cacheTempKey = "city2Country_".$cityId;
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE') {
				$cityCountry = $cacheKeyValue;
			}
			
			if(isset($location)){
				$location=$location.",".$cityCountry;
			}
			else
			{
				$location=$cityCountry;
			}
		}
		$optionalArgs['location']=split(",",$location);
		error_log_shiksha(print_r($optionalArgs,true));
		//Added by Ankur for adding Seo url in Lucene
		$tmpSeoUrl = $result["seoUrl"];
		if(isset($tmpSeoUrl) && !empty($tmpSeoUrl))
			$url=(string)$result["seoUrl"];
		else
			$url=getSeoUrl($Id,$type,$titleName,$optionalArgs,'old');

		//$courses=(string)$resultDocs['docs'][0]["courseTitle"];
		$imageUrl=(string)$resultDocs['docs'][0]["imageUrl"];
		$packtype=(string)$resultDocs['docs'][0]["packtype"];
		//dhwaj hero
		$courses = (string)$resultDocs['docs'][0]["courses"];
		$course_count = (int)$resultDocs['docs']['0']["course_count"];
		if(isset($highlightArray["courseTitle"]))
		{
			$courseHighLight=$highlightArray["courseTitle"];
		}
		if(isset($highlightArray["title"]))
		{
			$title=$highlightArray["title"];
		}
		else
		{
			$title=$titleName;
		}

		$refinedCourseTitleList=array();
		for($i=0; $i<(int)$resultDocs['numFound'];$i++){
			//	error_log("dhwaj course refined title ".print_r($resultDocs['docs'][$i],true))ultDocs['docs']
			$refinedCourseTitleList[$i]=html_entity_decode($resultDocs['docs'][$i]["courseTitle"]);
		}

		//error_log("dhwaj course refined title ".print_r($refinedCourseTitleList,true));
		$refinedLen=sizeof($refinedCourseTitleList);
		$courseList;
		//error_log("dhwaj course list initialized ".print_r($courseList,true));
		$courseListAll = json_decode(base64_decode($courses),true);

		//error_log("LSEARCH total count". count($courseListAll));
		error_log("dhwaj course orignal array". print_r($courseListAll,true));
		$optionalArgsArray = array();
		//$allCourseTitleList = array();
		$optionalArgsArray['location'][0] = $location;
		$optionalArgsArray['institute'] = strip_tags($title);
		for($i=0;$i<sizeof($courseListAll);$i++)
		{
			//Added by Ankur for adding Seo url in Lucene
			if( isset($courseListAll[$i]['listing_seo_url']) && (string)$courseListAll[$i]['listing_seo_url'] != '')
				$courseListAll[$i]['url']=(string)$courseListAll[$i]['listing_seo_url'];
			else
				$courseListAll[$i]['url'] = getSeoUrl($courseListAll[$i]['course_id'],'course',$courseListAll[$i]['courseTitle'],$optionalArgsArray,'old');

			$keyIndex=array_search($courseListAll[$i]['courseTitle'], $refinedCourseTitleList);
			//error_log("dhwaj course keyIndex ".$keyIndex);
			if(isset($keyIndex) && is_numeric($keyIndex))
			{
				$courseList[$keyIndex]=$courseListAll[$i];
				$highlightId = "course".$courseListAll[$i]['course_id'];
				//error_log("Shivam1 ".$keyIndex."highlightArray ".$highlightId);
				$highlightArray=$highlightResult[$highlightId];
				//error_log("Shivam1 highlightArra Result ".print_r($highlightResult,true));
				if(isset($highlightArray["courseTitle"]))
				{
					$courseHighLightArray[]=$highlightArray["courseTitle"][0];
					//error_log("Shivam1 ".$keyIndex." courseHighLight ".print_r($courseHighLight,true));
				}
				if(isset($highlightArray["title"]))
				{
					$title=$highlightArray["title"];
					//error_log("Shivam1 title ".$title);
				}
				if(isset($highlightArray["content"]))
				{
					$shortContent=strip_tags(html_entity_decode($highlightArray["content"][0]),'<b>');
					//error_log("Shivam1 hortContent ".$shortContent);
				}
			}

		}
		$courseHighLight= implode(":::",$courseHighLightArray);	
		//dhwaj course refined
		error_log("dhwaj course final list ".print_r($courseList,true));
		$courses = base64_encode(json_encode($courseList));

		// Ravi
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'imageUrl'=>trim($imageUrl),'location'=>$location,'courseHighlight'=>$courseHighLight,'courses'=>$courses,'course_count'=>$course_count,'packtype'=>$packtype,'contact_email'=>$contact_email,'isSponsored'=>$isSponsored),'struct'));

	}

	function getDocDetailForBlog($result,$highlightResult,$isSponsored,$isSaveSearch){
		error_log("PANKY in getDocDetailForBlog : resultArray  : " . print_r($result, true));
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult["$uniqueId"];
		if(isset($highlightArray['content']))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray['content']),'<b>');
		}
		$url=(string)$result->url;
		$facetype=(string)$result->facetype;
		if(isset($highlightArray['title']))
		{
			$title=$highlightArray['title'];
		}
		else
		{
			$title=$titleName;
		}
		if($shortContent=="")
		{
			$shortContent=(string)$result->content;
		}
		$noOfComments=(string)$result->noOfComments;
		return(array(array('typeId'=>$Id,'type'=>$type,'blogtype'=>$facetype,'noOfComments'=>$noOfComments,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'isSponsored'=>$isSponsored),'struct'));

	}
	function getDocDetailForSchoolGroup($result,$highlightResult,$isSponsored,$isSaveSearch){
		$this->init();
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$url=getSeoUrl($Id,$type,$titleName);
		if(isset($highlightArray->title))
		{
			$title=$highlightArray->title;
		}
		else
		{
			$title=$titleName;
		}
		if($shortContent=="")
		{
			$shortContent=(string)$result->content;
		}
		$cityIdList=(string)$result->cityId;
		$cityIdArray=explode(" ",trim($cityIdList));
		foreach($cityIdArray as $cityId)
		{
			/*
			if(!apc_fetch("city2Country_".$cityId))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city2Country_".$cityId))
			{
				$cityCountry=apc_fetch("city2Country_".$cityId);
			}
			*/
			$cacheTempKey = "city2Country_".$cityId;
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE') {
				$cityCountry = $cacheKeyValue;
			}
			
			if(isset($location)){
				$location=$location.",".$cityCountry;
			}
			else
			{
				$location=$cityCountry;
			}
		}
		error_log_shiksha("HEHEHE".$location);
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'location'=>$location,'isSponsored'=>$isSponsored),'struct'));

	}
	function getDocDetailForCollegeGroup($result,$highlightResult,$isSponsored,$isSaveSearch){
		$this->init();
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$url=getSeoUrl($Id,$type,$titleName);
		if(isset($highlightArray->title))
		{
			$title=$highlightArray->title;
		}
		else
		{
			$title=$titleName;
		}
		if($shortContent=="")
		{
			$shortContent=(string)$result->content;
		}
		$cityIdList=(string)$result->cityId;
		$cityIdArray=explode(" ",trim($cityIdList));
		foreach($cityIdArray as $cityId)
		{
			/*
			if(!apc_fetch("city2Country_".$cityId))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city2Country_".$cityId))
			{
				$cityCountry=apc_fetch("city2Country_".$cityId);
			}
			*/
			
			$cacheTempKey = "city2Country_".$cityId;
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE') {
				$cityCountry = $cacheKeyValue;
			}
			
			if(isset($location)){
				$location=$location.",".$cityCountry;
			}
			else
			{
				$location=$cityCountry;
			}
		}
		error_log_shiksha("HEHEHE".$location);
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'location'=>$location,'isSponsored'=>$isSponsored),'struct'));

	}
	function getDocDetailForMisc($result,$highlightResult,$isSponsored,$isSaveSearch){
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		//$tags=$result->tags;
		if($isSaveSearch==1)
		{
			$shortContent=(string)$result->content;
		}
		else
		{
			$shortContent="";
		}
		$imageCount=(string)$result->imageCount;
		$videoCount=(string)$result->videoCount;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<b>');
		}
		$url=(string)$result->misc_url;
		$url=getSeoUrl($url,$type);
		$sourceUrl=(string)$result->misc_url;
		$imageUrl=(string)$result->imageUrl;
		$title=(string)$result->misc_title;
		$host=(string)$result->misc_host;
		if(isset($highlightArray->misc_content))
		{
			$content=$highlightArray->misc_content;
			$shortContent=strip_tags(html_entity_decode($content),'<b>');
			$shortContent=strip_tags(html_entity_decode($content),'</b>');
		}
		else
		{
			$shortContent=(string)$result->misc_content;
		}
		return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'imageUrl'=>$imageUrl,'host'=>$host,'sourceUrl'=>$sourceUrl,'isSponsored'=>$isSponsored),'struct'));

	}
	/**
	 * getDocDetail is a function that fetches the details of te elements from the XML
	 * @Input : the XML part of the listings, isSponsored, isSaveSearch
	 * @Output: the details of the document, isSponsored will set it as sponsored and highlightResult will highlight the searched Keyword
	 */
	function getDocDetail($result,$highlightResult,$isSponsored=0,$isSaveSearch=0,$searchType="")
	{
		$result=(object)$result;
		//$highlightResult=(object)$highlightResult;
		$type=(string)$result->type;

		error_log("PANKY in getDocDetail type:" . $type." searchType:".$searchType);

		if($searchType=="course" || $searchType=="institute"){
			$result=(array)$result;
			//$highlightResult=(array)$highlightResult;
			return $this->getDocDetailForInstitute($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="question")
		{
			return $this->getDocDetailForQuestions($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="course")
		{
			$result=(array)$result;
			//Because institue is a group of course now (new grouped format)
			return $this->getDocDetailForInstitute($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="scholarship")
		{
			return $this->getDocDetailForScholarship($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="notification")
		{
			return $this->getDocDetailForNotification($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="institute")
		{
			$result=(array)$result;
			return $this->getDocDetailForInstitute($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="blog")
		{
			return $this->getDocDetailForBlog($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="schoolgroups")
		{
			return $this->getDocDetailForSchoolGroup($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="collegegroup")
		{
			return $this->getDocDetailForCollegeGroup($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else if($type=="misc")
		{
			return $this->getDocDetailForMisc($result,$highlightResult,$isSponsored,$isSaveSearch);
		}
		else
		{
			$url=(string)$result->url;
			$imageUrl=(string)$result->imageUrl;
			if(isset($highlightArray->title))
			{
				$title=$highlightArray->title;
			}
			else
			{
				$title=$titleName;
			}
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'imageCount'=>$imageCount,'videoCount'=>$videoCount,'url'=>$url,'imageUrl'=>$imageUrl,'isSponsored'=>$isSponsored),'struct'));
		}
	}

	/**
	 * ShikshaApiSearch is a function to fetch results for the Api Search.
	 * The relaxFlag is used to define the OR or AND behaviour of the search.
	 * @Input : keyword, rows, start, listingtype ,relaxFlag
	 * @Output: the set of results
	 * @TODO: add location field to this search
	 */
	function shikshaApiSearch($request)
	{
		$parameters=$request->output_parameters();
		$keyw=strtolower(urlencode($parameters[1]));
		$start=trim($parameters[2]);
		$rows=trim($parameters[3]);
		$listingType=trim($parameters[4]);
		$relaxFlag = trim($parameters[5]);
		$categoryId = trim($parameters[6]);
		$excludeList = trim($parameters[7]);

		error_log_shiksha("apiurl ".print_r($parameters,true));
		if($listingType == 'relatedData')
		{
			$url=SOLR_RELATED_SELECT_URL_BASE;
		}
		else
		{
			$url = SOLR_SELECT_URL_BASE;
			if($listingType == 'institute' || $listingType == 'course'){
				$url = SOLR_INSTI_SELECT_URL_BASE;
			}
		}

		$url=$this->createApiUrl($url,$keyw,$start,$rows,$listingType,$categoryId,$excludeList);
		if($relaxFlag!=0)
		{
			$url = $url."rlx=true&";
		}
		if($relaxFlag!=2)
		{
			if($listingType == 'relatedData'){
				$url=$url."qt=relatedData&";
			}else{
				$url=$url."qt=shikshaApi&";
			}
		}
		$highlight=1;
		if($highlight)
		{
			$url=$url."hl=on&hl.fl=content&hl.fragsize=150";
		}
		$url=$url.$facet_addition;
		error_log_shiksha("apiurl ".$url);

		$xml_content=$this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha');

		$xml = simplexml_load_string($xml_content, 'simple_xml_extended');
		$resultsP = $xml->xpath('/response/result');
		foreach($resultsP as $result1)
		{
			$numfound =  $result1->Attribute("numFound");
		}
		error_log_shiksha($numfound);
		$highlightResult=$xml->xpath('/response/highlighting');
		$results = $xml->xpath('/response/result/doc');
		$temp_array=array();
		foreach($results as $result)
		{
			$docDetail=$this->getApiDocDetail($result,$highlightResult);
			array_push($temp_array,$docDetail);
		}
		error_log_shiksha(print_r($temp_array,true));
		$response=array(array('numOfRecords'=>array($numfound,'int'), 'results'=>array($temp_array,'array')),'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * createApiUrl is used to create the URL for API calls
	 * this is just like the createUrl function just that it has limited capabilities
	 * @Input : baseUrl, keyword, start , rows and listing Type
	 * @Output: url to be curled to SOLR
	 */
	function createApiUrl($baseUrl,$keyw,$start,$rows,$listingType,$categoryId="",$filterList="")
	{
		$url=$baseUrl;
		if(($keyw!="")&&(isset($keyw)))
		{
			$url=$url."q=".$keyw."&";
		}
		if(isset($start)||$start!="")
		{
			$url=$url."start=".$start."&";
		}
		else
		{
			$url=$url."start=0&";
		}
		if(isset($rows)||$rows!="")
		{
			$url=$url."rows=".$rows."&";
		}
		else
		{
			$url=$url."rows=15&";
		}
		if(isset($listingType)&&$listingType!=""&&$listingType!="+")
		{
			switch($listingType)
			{
				case "college": $url=$url."fq=facetype:institute&";
				break;
				case "course": $url=$url."fq=facetype:course&";
				break;
				case "event": $url=$url."fq=facetype:Event&";
				break;
				case "relatedData": $url=$url."fq=facetype:question&";
				break;
				case "all": break;
				default: $url=$url."fq=facetype:".$listingType."&";
			}
		}
		if(isset($categoryId) && $categoryId != "")
		{
			$url=$url."categoryList=".$categoryId."&";
		}
		if(isset($filterList) && $filterList != "")
		{
			if($listingType == 'relatedData')
			{
				$questionList = explode(" ",$filterList);
				foreach($questionList as $value)
				{
					$url=$url."fq=-(uniqueId:msgbrd".$value.")&";
				}
			}
		}
		error_log("Shirish : ".$url);

		return($url);
	}

	/**
	 * getApiDocDetail is a function that fetches the details of te elements from the XML
	 * @Input : the XML part of the listings, highLightResult
	 * @Output: the details of the document, isSponsored will set it as sponsored and highlightResult will highlight the searched Keyword
	 */
	function getApiDocDetail($result,$highlightResult)
	{
		$this->init();
		$ListingClientObj = new Listing_client();
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$titleName=(string)$result->title;
		$categoryId=(string)$result->cat_s;
		$uniqueId=$type.$Id;
		$highlightArray=$highlightResult[0]->{$uniqueId};
		if(isset($highlightArray->content))
		{
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'<em>');
			$shortContent=strip_tags(html_entity_decode($highlightArray->content),'</em>');
		}
		if($type=="course")
		{
			$fees=(string)$result->fees;
			$duration=(string)$result->duration;
			$eligibility=(string)$result->eligibility;
			$imageUrl=(string)$result->imageUrl;
			$instituteName=(string)$result->instituteList;
			$instituteId=(string)$result->instituteId;
			$instituteList=$instituteName;
			$title=$titleName;
			$cityIdList=(string)$result->cityId;
			$cityIdArray=explode(" ",trim($cityIdList));
			foreach($cityIdArray as $cityId)
			{
				/*
				if(!apc_fetch("city2Country_".$cityId))
				{
					$ListingClientObj->updateApcForSearch();
				}
				if(apc_fetch("city2Country_".$cityId))
				{
					$cityCountry=apc_fetch("city2Country_".$cityId);
				}
				*/
				$cacheTempKey = "city2Country_".$cityId;
				if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
					$ListingClientObj->updateApcForSearch();
				}
				$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
				if($cacheKeyValue != 'ERROR_READING_CACHE') {
					$cityCountry = $cacheKeyValue;
				}
				
				if(isset($location)){
					$location=$location.",".$cityCountry;
				}
				else
				{
					$location=$cityCountry;
				}
			}
			$optionalArgs['location']=split(",",$location);
			$optionalArgs['institute']=$instituteName;
			$url=getSeoUrl($Id,$type,$titleName,$optionalArgs);
			$optionalArgs1['location']=split(",",$location);
			$collegeUrl=getSeoUrl(trim($instituteId),"institute",$titleName,$optionalArgs1);
			$packtype=(string)$result->packtype;
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'fees'=>$fees,'duration'=>$duration,'eligibility'=>$eligibility,'imageUrl'=>$imageUrl,'college'=>$instituteList." ".$location,'collegeName'=>$instituteList,'collegeUrl'=>$collegeUrl,'packtype'=>$packtype),'struct'));
		}
		else if($type=="scholarship")
		{
			$url=getSeoUrl($Id,$type,$titleName);
			$value=(string)$result->value;
			$number=(string)$result->number;
			$imageUrl=(string)$result->imageUrl;
			$eligibility=(string)$result->eligibility;
			$applicableTo=(string)$result->levels;
			$title=$titleName;
			$packtype=(string)$result->packtype;
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'eligibility'=>$eligibility,'applicableTo'=>$applicableTo,'number'=>$number,'value'=>$value,'imageUrl'=>$imageUrl,'packtype'=>$packtype),'struct'));
		}
		else if($type=="institute")
		{
			$cityIdList=(string)$result->cityId;
			$cityIdArray=explode(" ",trim($cityIdList));
			foreach($cityIdArray as $cityId)
			{
				/*
				if(!apc_fetch("city2Country_".$cityId))
				{
					$ListingClientObj->updateApcForSearch();
				}
				if(apc_fetch("city2Country_".$cityId))
				{
					$cityCountry=apc_fetch("city2Country_".$cityId);
				}
				*/
				$cacheTempKey = "city2Country_".$cityId;
				if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
					$ListingClientObj->updateApcForSearch();
				}
				$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
				if($cacheKeyValue != 'ERROR_READING_CACHE') {
					$cityCountry = $cacheKeyValue;
				}
				
				if(isset($location)){
					$location=$location.",".$cityCountry;
				}
				else
				{
					$location=$cityCountry;
				}
			}
			$optionalArgs['location']=split(",",$location);
			error_log_shiksha(print_r($optionalArgs,true));
			$url=getSeoUrl($Id,$type,$titleName,$optionalArgs);
			$courses=(string)$result->courseTitle;
			$imageUrl=(string)$result->imageUrl;
			$title=$titleName;
			$packtype=(string)$result->packtype;
			if($courses!="")
			{
				$courseArray=array(split(":::",$courses),'struct');
			}
			else
			{
				$courseArray="";
			}
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'imageUrl'=>$imageUrl,'location'=>$location,'courses'=>$courseArray,'packtype'=>$packtype),'struct'));
		}
		else if($type=="Event")
		{
			$cityName=(string)$result->cityList;
			$startDate=(string)$result->startDate;
			$endDate=(string)$result->endDate;
			$location=(string)$result->cityList."-".$result->countryList;
			$optionalArgs['location']=split(",",$location);
			$title=$titleName;
			$packtype=(string)$result->packtype;
			$url=getSeoUrl($Id,'event',$titleName,$optionalArgs);
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'location'=>$location,'startDate'=>date('jS M, y, h:ia',strtotime($startDate)),'endDate'=>date('jS M, y, h:ia',strtotime($endDate)),'packtype'=>$packtype),'struct'));
		}
		else if($type=="question")
		{
			$url=getSeoUrl($Id,$type,$titleName);
			$answerText=(string)$result->answerText;
			$answerTime=(string)$result->answerTime;
			$questionTime=(string)$result->questionTime;
			$answerUserInfo=(string)$result->answerUserInfo;
			$questionUserInfo=(string)$result->questionUserInfo;
			$viewCount=(string)$result->noOfViews;
			$noOfComments=(string)$result->noOfComments;
			$packtype=(string)$result->packtype;
			if(isset($highlightArray->title))
			{
				$title=$highlightArray->title;
			}
			else
			{
				$title=$titleName;
			}
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'url'=>$url,'shortContent'=>$shortContent,'noOfComments'=>$noOfComments,'viewCount'=>$viewCount,'creationDate'=>date('jS M, y, h:ia',strtotime($questionTime)),'questionUserInfo'=>array(unserialize(html_entity_decode($questionUserInfo)),'struct'),'answerUserInfo'=>array(unserialize(html_entity_decode($answerUserInfo)),'struct'),'answer'=>$answerText,'answerTime'=>$answerTime,'packtype'=>$packtype),'struct'));
		}
		else if($type=="blog")
		{
			$url=getSeoUrl($Id,$type,$titleName);
			$packtype=(string)$result->packtype;
			if(isset($highlightArray->title))
			{
				$title=$highlightArray->title;
			}
			else
			{
				$title=$titleName;
			}
			if($shortContent=="")
			{
				$shortContent=(string)$result->content;
			}
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'shortContent'=>$shortContent,'url'=>$url,'packtype'=>$packtype),'struct'));
		}
	}



	/**
	 * indexMsgRecord is a function that converts the php Array containing messageBoard details into an indexable SOLR xml document and then sends the same to SOLR
	 * @Input : the php Array containg the details of the message Board
	 * @Output: the response of the SOLR after indexing the document
	 */
	function indexMsgRecord($request) {
		return; /*
		error_log("PANKY in indexMsgRecord in searchCI");
		$parameters=$request->output_parameters();
		$p = $parameters[0];
		$type = $p['type'];
		$id = $p['Id'];
		error_log("PANKY : type : " . $type);
		error_log("PANKY : id : " . $id);
		$p = $this->preprocessMessageBoardDataForIndex($p);
		$ignoreFields = array('docBoost');
		$xmlContent = $this->search_lib->getDocumentFieldsAsXMLString($p, $ignoreFields);
		$indexResponse = false;
		if($xmlContent){
			$docBoost = NULL;
			if($p['docBoost'] == true){
				$docBoost = "1.2";
			}
			$documentXML = $this->search_lib->getDocumentAsXMLString($xmlContent, $docBoost);
			if($documentXML){
				$url = $this->search_lib->getSolrUrlByType($type, 'update');
				error_log("PANKY solr url : ". $url);
				$indexResponse = $this->search_lib->makeSolrCurl($documentXML, $url);
			}
		}
		error_log("PANKY indexresponse : " . print_r($indexResponse, true));
		$returnVal = -1;
		$solrResponseStr = '';
		if(is_array($indexResponse)){
			$returnVal = $indexResponse['result'];
			$solrResponseStr = $indexResponse['solr_response'];
		}
		$response = array(
				array(
					'result' => $returnVal,
					'response' => print_r($solrResponseStr,true)),
				'struct');
		return $this->xmlrpc->send_response($response);
		*/
	}

	function preprocessMessageBoardDataForIndex($params = array()){
		return; /*
		$p = $params;
		if(!(isset($p['facetype']))) {
			$p['facetype'] = $p['type'];
		}
		if(isset($p['categoryList'])&& $p['categoryList']!="") {
			$p['cat_s'] = $p['categoryList'];
			$catlist = $this->getBoardTree( $p['categoryList']);
			$p['categoryList'] = $catlist['boardNameString'];
			$p['categoryId'] = $catlist['boardIdString'];
		}
		if(isset($p['countryList'])) {
			$countryList = $this->getCountryList($p['countryList']);
			$p['countryId'] = $countryList['countryIdList'];
			$p['countryList'] = $countryList['countryNameList'];
		}
		return $p;
		*/
	}

	/**
	 * indexListingRecord is a function that converts the php Array containing Listing details into an
	 * indexable SOLR xml document and then sends the same to SOLR
	 * @Input : the php Array containg the details of the Listing
	 * @Output: the response of the SOLR after indexing the document
	 */

	function indexListingRecord($request) {
		return; /*
		error_log("PANKY in indexListingRecord in searchCI");
		$this->init();
		$parameters = $request->output_parameters();
		$p = utility_decodeXmlRpcResponse($parameters[1]);
		$type = $p['type'];
		$id = $p['Id'];
		$docBoost = $this->getBoost($p['packtype']);
		error_log("PANKY docboost : " . $docBoost);
		error_log("PANKY type : " . $type);
		error_log("PANKY id : " . $id);
		$p = $this->preprocessInstituteDataForIndex($p);
		$p['uniqueId'] = $type.$id;
		if($type == "autosuggestor"){ //hack for autosuggestor facetype data
			$p['Id'] = $p['original_id'];
			$p['title'] = $p['instituteList'];
			unset($p['original_id']);
		}
		
		$p['shortContent'] = $p['content'];
		$xmlContent = $this->search_lib->getDocumentFieldsAsXMLString($p);
		error_log("PANKY after getDocumentFieldsAsXMLString xmlcontent : " . print_r($xmlContent, true) );
		$indexResponse = false;
		if($xmlContent){
			$documentXML = $this->search_lib->getDocumentAsXMLString($xmlContent, $docBoost);
			if($documentXML){
				$url = $this->search_lib->getSolrUrlByType($type, 'update');
				error_log("PANKY solr url : ". $url);
				$indexResponse = $this->search_lib->makeSolrCurl($documentXML, $url);
			}
		}
		error_log("PANKY indexresponse : " . print_r($indexResponse, true));
		$returnVal = -1;
		$solrResponseStr = '';
		if(is_array($indexResponse)){
			$returnVal = $indexResponse['result'];
			$solrResponseStr = $indexResponse['solr_response'];
		}
		$response = array(
				array(
					'result' => $returnVal,
					'response' => print_r($solrResponseStr,true)),
				'struct');
		return $this->xmlrpc->send_response($response);
		*/
	}

	function preprocessInstituteDataForIndex($params = array()) {
		return; /*
		$p = $params;
		if(!(isset($p['facetype']))) {
			$p['facetype'] = $p['type'];
		}
		if($p['type'] == 'institute' || $p['type'] == 'course' || $p['type'] == 'schoolgroups' || $p['type'] == 'collegegroup') {
			$p['abbr'] = $this->getAbbr($p['title']);
		}
		if(isset($p['categoryList'])&& $p['categoryList']!="") {
			$p['cat_s'] = $p['categoryList'];
			$catlist = $this->getBoardTree( $p['categoryList']);
			$p['categoryList'] = $catlist['boardNameString'];
			$p['categoryId'] = $catlist['boardIdString'];
		}
		if(isset($p['countryList'])&&($p['countryList']!="")) {
			$countryList = $this->getCountryList($p['countryList']);
			$p['countryId'] = $countryList['countryIdList'];
			$p['countryList'] = $countryList['countryNameList'];
			$p['continent'] = $countryList['continent'];
		}
		if(isset($p['cityList'])&&($p['cityList']!="")) {
			$cityList = $this->getCityList($p['cityList']);
			$p['cityId'] = $cityList['cityIdList'];
			$p['cityList'] = $cityList['cityNameList'];
			$p['state'] = $cityList['stateList'];
		}	
		if(isset($p['instituteId'])&&($p['instituteId']!="")) {
			$instituteDetails = $this->getInstituteDetails($p['instituteId']);
			$p['instituteId'] = $instituteDetails['instituteIdList'];
			$p['instituteList'] = $instituteDetails['instituteNameList'];
			$p['instituteDesc'] = $instituteDetails['instituteDescList'];
			$p['imageUrl'] = $instituteDetails['logo_link'];
		}
		if(isset($p['addressCountry'])&&($p['addressCountry']!="")) {
			$addressCountryList = $this->getCountryList($p['addressCountry']);
			$p['addressCountryId'] = $addressCountryList['countryIdList'];
			$p['addressCountry'] = $addressCountryList['countryNameList'];
		}
		if(isset($p['addressCity'])&&($p['addressCity']!="")) {
			$addressCityList = $this->getCityList($p['addressCity']);
			$p['addressCityId'] = $addressCityList['cityIdList'];
			$p['addressCity'] = $addressCityList['cityNameList'];
		}
		if(isset($p['courses'])) {
			$p['courses'] = json_decode($p['courses']);
			$p['course_count'] = count($p['courses']);
			foreach($p['courses'] as $k=>$v) {
				foreach($v as $key=>$value) {
					if($key == "courseType" || $key == "courselevel") {
						$p['courses'][$k]->$key = $this->cleanCSVField($value);
					}
					$p['course_'.$key.'_'.$k] = $value;
					if($key == "courseContent") {
						unset($p['courses'][$k]->$key);
					}
				}
			}
			$p['courses'] = base64_encode(json_encode($p['courses']));
		}

		return $p;
		*/
	}

	
	/**
	 * getBoost is a function that gets the boost needed to boost to listing according to the listing packType
	 * @Input : The packtype
	 * @Output: boosting factor
	 */
	function getBoost($packtype)
	{
		if(SOLR_BUCKET)
		{
			return(1);
		}
		else
		{
			$packtypeBoostMapping = array (0=>1.2, 1=>3.0, 2=>3.0, 3=>3.0, 4=>3.0, 5=>3.0, 6=>3.0, 7=>1.3, 8=>3.0, 9=>3.0, 10=>3.0, 11=>1.2); //move this to web-service and then to APC
			if(isset($packtypeBoostMapping[$packtype]))
			{
				return($packtypeBoostMapping[$packtype]);
			}
			else
			{
				return(1);
			}
		}
	}

	/**
	 * getEventBoost is a function that gets the boost needed to boost to the Event according to the listing packType
	 * @Input : logrithmic distance of the events date from today
	 * @Output: boosting factor
	 */
	function getEventBoost($packtype)
	{
		if($packtype<0||$packtype==0)
		{
			return(2);
		}
		else
		{
			return((10-$packtype)/10);
		}
	}


	
	/**
	 * deleteListingRecord is a function that deletes a record from the SOLR index
	 * @Input : listing Type, listing Id
	 * @Output: 0/1 (success/failure)
	 */
	function deleteListingRecord($request) {
		error_log("PANKY in searchCI deleteListingRecord");
		$parameters = $request->output_parameters();
		$type = $parameters[1];
		$id = $parameters[2];
		$uniqueId = $type.$id;
		$response = $this->deleteRecordFromSolr($type, $uniqueId);
		return $response;
	}

	/**
	 * deleteMsgbrdRecord is a function that deletes a message Board from the SOLR index
	 * @Input : type, boardId and topicId
	 * @Output: 0/1 (success/failure)
	 */
	function deleteMsgbrdRecord($request) {
		error_log("PANKY in searchCI deleteMsgbrdRecord");
		$parameters=$request->output_parameters();
		$type = $parameters[1];
		$boardId = $parameters[2];
		$topicId = $parameters[3];
		$uniqueId = $type.$boardId."topic".$topicId;
		$response = $this->deleteRecordFromSolr($type, $uniqueId);
		return $response;
	}

	function deleteRecordFromSolr($type = '', $uniqueId = '') {
		error_log("PANKY in deleteRecordFromSolr");
		$type = trim($type);
		$uniqueId = trim($uniqueId);
		error_log("PANKY type: " . $type . " - uniqueid : " . $uniqueId);
		$returnValue = false;
		$indexResponse = false;
		if(strlen($type) > 0 && strlen($uniqueId) > 0) {
			$solrUrl = $this->search_lib->getSolrUrlByType($type,"update");
			error_log("PANKY solrURL : " . $solrUrl);
			if($solrUrl) {
				$xmlContent = "<delete><query>uniqueId:$uniqueId</query></delete>";
				$indexResponse = $this->search_lib->makeSolrCurl($xmlContent, $solrUrl);
			}
		}
		error_log("PANKY indexresponse : " . print_r($indexResponse, true));
		$returnVal = -1;
		$solrResponseStr = '';
		if(is_array($indexResponse)){
			$returnVal = $indexResponse['result'];
			$solrResponseStr = $indexResponse['solr_response'];
		}
		$response = array(
				array(
					'result' => $returnVal,
					'response' => print_r($solrResponseStr,true)),
				'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getBoardTree is a function that gets the subCategory tree from the categoryBordTree
	 * @Input : categoryId
	 * @Output: categoryBoardTree
	 */
	function getBoardTree($boardId)
	{
		return; /*
		$this->init();
		//error_log_shiksha($boardId);
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

		$queryCmd = ' SELECT t1.boardId AS lev1, t1.name as lev1Name, t2.boardId as lev2, t2.name as lev2Name ,t3.boardId as lev3, t3.name as lev3Name, t4.boardId as lev4, t4.name as lev4Name FROM categoryBoardTable AS t1 '.
			'RIGHT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
			'RIGHT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
			'RIGHT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t4.boardId in ('.$boardId.')';

					//error_log_shiksha($queryCmd);

					log_message('debug', 'get board child query is ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
					foreach ($query->result() as $row){
					if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1)){
					if(strlen($boardIdString)>0){
					$boardIdString .= ' ';
					}
					$boardIdArray[$row->lev1]=$row->lev1;
					$boardIdString .= $row->lev1;
					$boardNameString .= $row->lev1Name.' ';

					}
					if(!array_key_exists($row->lev2,$boardIdArray) && !empty($row->lev2)){
					if(strlen($boardIdString)>0){
					$boardIdString .= ' ';
					}
					$boardIdArray[$row->lev2]=$row->lev2;
					$boardIdString .= $row->lev2;
					$boardNameString .= $row->lev2Name.' ';
					}
					if(!array_key_exists($row->lev3,$boardIdArray) && !empty($row->lev3)){
						if(strlen($boardIdString)>0){
							$boardIdString .= ' ';
						}
						$boardIdArray[$row->lev3]=$row->lev3;
						$boardIdString .= $row->lev3;
						$boardNameString .= $row->lev3Name.' ';
					}
					if(!array_key_exists($row->lev4,$boardIdArray) && !empty($row->lev4)){
						if(strlen($boardIdString)>0){
							$boardIdString .= ' ';
						}
						$boardIdArray[$row->lev4]=$row->lev4;
						$boardIdString .= $row->lev4;
						$boardNameString .= $row->lev4Name.' ';
					}
					}
					/*if(strlen($boardIdString)==0)
					  {
					  $boardIdString .= $boardId;
					  }*/
					  /*
					$result['boardIdString']=$boardIdString;
					$result['boardNameString']=$boardNameString;
					return $result;
		*/
	}

	/**
	 * getCityStateMap is a function to store the stateCityMap in the apc
	 * the city->state map is stored in the apc via city2state_<city_id>
	 * @Input: void
	 * @Output:void
	 */
	function getCityStateMap()
	{
		$this->init();
		ini_set('memory_limit','1024M');
		//connect DB
		if($this->cacheLib->get("city2state_map_status")!=1)
		{
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == '')
		{
			log_message('error','get stateForCity can not create db handle');
		}

		$queryCmd = 'select city_id,state_name from countryCityTable, stateTable where countryCityTable.state_id=stateTable.state_id';
		error_log_shiksha($queryCmd);

		log_message('debug', 'get stateForCity Query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row)
		{	
			$this->cacheLib->store("city2state_".$row->city_id , $row->state_name);
			//apc_store("city2state_".$row->city_id,$row->state_name);
			//error_log_shiksha($row->city_id." : ".$row->state_name);
		}
			$this->cacheLib->store("city2state_map_status",1);
		}
		return;
	}

	/**
	 * getCountryContinentMap is a function to store the country to continent in the apc
	 * the country to continent map is stored in the apc via country2Continent_<city_id>
	 * @Input: void
	 * @Output:void
	 */
	function getCountryContinentMap()
	{
		//connect DB
		$this->init();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == '')
		{
			log_message('error','get stateForCity can not create db handle');
		}

		$queryCmd = 'select countryTable.countryId, continentTable.name from countryTable,continentTable where countryTable.continent_id=continentTable.continent_id';
		error_log_shiksha($queryCmd);

		log_message('debug', 'get countryContinentTable Query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row)
		{
			$this->cacheLib->store("country2Continent".$row->countryId,$row->name);
			//apc_store("country2Continent".$row->countryId,$row->name);
			//error_log_shiksha($row->countryId." : ".$row->name);
		}
		return;
	}


	/**
	 * getBoardTreeForCluster is a replica of getBoardTree except that instead of getting the entire tree it gets only the tree whose nodes are there in the category
	 * @Input: categoryId
	 * @Output: category Board Tree
	 */
	function getBoardTreeForCluster($boardId)
	{
		return; /*
		$this->init();
		//error_log_shiksha($boardId);
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

		$queryCmd = "SELECT t1.boardId AS lev1, t1.name as lev1Name, t2.boardId as lev2, t2.name as lev2Name ,t3.boardId as lev3, t3.name as lev3Name, t4.boardId as lev4, t4.name as lev4Name FROM categoryBoardTable AS t1 RIGHT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId RIGHT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId RIGHT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t4.boardId in ($boardId)";

		error_log_shiksha($queryCmd);

		log_message('debug', 'get board tree query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row)
		{
			if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1))
			{
				$boardIdArray["C-".$row->lev1]=$row->lev1;
			}
			if(!array_key_exists($row->lev2,$boardIdArray) && !empty($row->lev2))
			{
				$boardIdArray["C-".$row->lev2]=$row->lev2;
			}
			if(!array_key_exists($row->lev3,$boardIdArray) && !empty($row->lev3))
			{
				$boardIdArray["C-".$row->lev3]=$row->lev3;
			}
			if(!array_key_exists($row->lev4,$boardIdArray) && !empty($row->lev4))
			{
				$boardIdArray["C-".$row->lev4]=$row->lev4;
			}
		}
		$queryCmd = "select boardId as lev1 from categoryBoardTable where categoryBoardTable.parentId in ($boardId)";
		error_log_shiksha('get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row){
			if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1))
			{
				$boardIdArray["C-".$row->lev1]=$row->lev1;
			}
		}
		error_log_shiksha(print_r($boardIdArray,true));
		return $boardIdArray;
		*/
	}

	/**
	 * getCountryList takes in CSV of countryId and returns respective countryNames
	 * @Input: countryId's as CSV
	 * @Output: country Names as an array
	 */
	function getCountryList($list)
	{
		$this->init();
		$countryArray=explode(',',$list);
		$idList=' ';
		$nameList=' ';
		$continentList=' ';
		$ListingClientObj = new Listing_client();
		foreach($countryArray as $country)
		{
			$idList.=$country.' ';
			/*
			if(!apc_fetch("country_".$country))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("country_".$country))
			{
				$nameList.=apc_fetch("country_".$country).' ';
			}
			
			if(!apc_fetch("country2Continent".$country))
			{
				$this->getCountryContinentMap();
			}
			if(apc_fetch("country2Continent".$country))
			{
				$continentList.=apc_fetch("country2Continent".$country);
			}
			*/
			$cacheTempKey = "country_".$country;
			if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE'){
				$nameList .= $cacheKeyValue . ' ';
			}
			
			$cacheTempContinentKey = "country2Continent".$country;
			if($this->cacheLib->get($cacheTempContinentKey) == 'ERROR_READING_CACHE'){
				$this->getCountryContinentMap();
			}
			$cacheTempContinentValue = $this->cacheLib->get($cacheTempContinentKey);
			if($cacheTempContinentValue != 'ERROR_READING_CACHE'){
				$continentList .= $cacheTempContinentValue;
			}
			
		}
		$result['countryIdList']=$idList;
		$result['countryNameList']=$nameList;
		$result['continent']=$continentList;
		return($result);
	}
	/**
	 * getCityList takes in CSV of cityId and returns respective cityNames
	 * @Input: cityId's as CSV
	 * @Output: city Names as an array
	 */
	function getCityList($list)
	{
		$this->init();
		$cityArray=explode(',',$list);
		$idList=' ';
		$nameList=' ';
		$stateList=' ';
		$ListingClientObj = new Listing_client();
		foreach($cityArray as $city)
		{
			$idList.=$city.' ';
			/*
			if(!apc_fetch("city_".$city))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city_".$city))
			{
				$nameList.=apc_fetch("city_".$city).' ';
			}
			if(!apc_fetch("city2state_".$city))
			{
				$this->getCityStateMap();
			}
			if(apc_fetch("city2state_".$city))
			{
				$stateList.=apc_fetch("city2state_".$city)." ";
			}
			*/
			$cacheTempKey = "city_".$city;
			if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE'){
				$nameList .= $cacheKeyValue . ' ';
			}
			
			$cacheTempStateKey = "city2state_".$city;
			if($this->cacheLib->get($cacheTempStateKey) == 'ERROR_READING_CACHE'){
				$this->getCityStateMap();
			}
			$cacheTempStateValue = $this->cacheLib->get($cacheTempStateKey);
			if($cacheTempStateValue != 'ERROR_READING_CACHE'){
				$stateList .= $cacheTempStateValue. " ";
			}
			
		}
		$result['cityIdList']=$idList;
		$result['cityNameList']=$nameList;
		$result['stateList']=$stateList;
		return($result);
	}

	/**
	 * getMessageBoardDetails fetches all details of a messageBoard given messageId
	 * @Input: messageId
	 * @Output: Array with messageBoard Details (key=>value)
	 * @TODO: Deprecated. Remove this
	 */
	function getMessageBoardDetails($threadId)
	{
		$this->load->library(array('message_board_client'));
		$msgbrdClient = new Message_board_client();
		$request=array(array($threadId));
		$response=$msgbrdClient->getDetailsForSearch(12,$request);
		//print_r($response);
		return($response);
	}
	/**
	 * getEventDetails fetches all details of a Event given eventId
	 * @Input: eventId
	 * @Output: Array with event Details (key=>value)
	 * @TODO: Deprecated. Remove this
	 */
	function getEventDetails($eventId)
	{
		$this->load->library(array('event_cal_client'));
		$eventCalClient = new Event_cal_client();
		$request=array(array($eventId));
		$response=$eventCalClient->getDetailsForSearch(12,$request);
		//print_r($response);
		return($response);
	}

	/**
	 * saveSearch is a function that sets a search alert for the given search
	 * @Input: userId, keyword, location, searchType
	 * @Output: String signifying wether the alert is created or not
	 * @TODO: move to Alerts
	 */
	function saveSearch($request)
	{	return; /*
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("saveSearch");
		$userId=$parameters[1];
		$keyword=$parameters[2];
		$type=$parameters[3];
		$location=$parameters[4];
		$status=1;
		//error_log_shiksha($boardId);
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == ''){
			log_message('error','saveSearch can not create db handle');
		}
		$queryCmd1 = "select count(*) count from tSaveSearch where user_id= ?";
		$query1 = $dbHandle->query($queryCmd1, $userId);
		foreach($query1->result() as $row1)
		{
			$totalCount=$row1->count;
		}
		if($totalCount>=5)
		{
			$message="You have already saved five searches.";
			$response = array($message, 'string');
			return $this->xmlrpc->send_response($response);
		}
		$keywordArray=preg_split("/[\p{P}\s]/",$keyword);
		$searchKeyword="";
		foreach($keywordArray as $key)
		{
			/*
			if(!apc_fetch(strtolower($key)))
			{
				$searchKeyword=$searchKeyword." ".$key;
			}
			*/
			/*
			$cacheTempKey = strtolower($key);
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$searchKeyword=$searchKeyword." ".$key;
			}
		}
		$frequency='daily';
		$data=array('user_id'=>$userId,'keyword'=>$keyword,'type'=>$type,'location'=>$location,'status'=>$status,'search_keyword'=>$searchKeyword,'frequency'=>$frequency);
		$queryCmd= $dbHandle->insert_string('tSaveSearch',$data);;

		//error_log_shiksha($queryCmd);
		$queryCmd = $queryCmd." on duplicate key update updateTime=current_timestamp";
		error_log_shiksha('saveSearch query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		$queryCmd="update userPointSystem set userPointValue= userPointValue+10 where userId= ?";
		error_log_shiksha('saveSearch query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, $userId);
		$message = "Search Saved";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);
		*/
	}
	/**
	 * deleteSaveSearch is a function that removes a search alert
	 * @Input: alertId set
	 * @Output: String signifying wether the alert is deactivated or not
	 * @TODO: move to Alerts
	 */
	function deleteSaveSearch($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("deleteSaveSearch");
		$Id=$parameters[1];
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == ''){
			log_message('error','deleteSaveSearch can not create db handle');
		}
		$queryCmd = "delete from tSaveSearch where id= ?";
		$query = $dbHandle->query($queryCmd, $Id);
		$message = "search deleted successfully";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * updateSaveSearchStatus is a function that changes the status of a search alert
	 * @Input: alertId set, new status
	 * @Output: string signifying the status has been updated successfully
	 * @TODO: move to Alerts
	 */
	function updateSaveSearchStatus($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("deleteSaveSearch");
		$Id=$parameters[1];
		$status=$parameters[2];
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == ''){
			log_message('error','updateSaveSearchStatus can not create db handle');
		}
		$queryCmd = "update tSaveSearch set status= ? where id= ?";
		$query = $dbHandle->query($queryCmd, array($status, $Id));
		$message = "search updated successfully";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getSaveSearch is a function that gets all the search alerts for the user
	 * @Input: userId, start, rows
	 * @Output: the array of (keyword, location, searchType, status, frequency and alertId) of the created alerts
	 * @TODO: move to Alerts
	 */
	function getSaveSearch($request)
	{
		return; /*
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("getSaveSearch");
		$userId=$parameters[1];
		$start=$parameters[2];
		$row=$parameters[3];
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == ''){
			log_message('error','getSaveSearch can not create db handle');
		}

		$queryCmd = "select * from tSaveSearch where user_id= ? order by id limit $start, $row";
		$query = $dbHandle->query($queryCmd, array($userId));
		$temp=array();
		foreach ($query->result() as $row)
		{
			$keyword=$row->keyword;
			$location=$row->location;
			$type=$row->type;
			$status=$row->status;
			$frequency=$row->frequency;
			$id=$row->id;
			$response=array('location'=>$location,'keyword'=>$keyword,'type'=>$type,'status'=>$status,'frequency'=>$frequency,'id'=>$id);
			array_push($temp,array($response,'struct'));
		}

		$queryCmd1 = "select count(*) count from tSaveSearch where user_id= ?";
		$query1 = $dbHandle->query($queryCmd1, $userId);
		foreach($query1->result() as $row1)
		{
			$totalCount=$row1->count;
		}
		error_log_shiksha($totalCount);
		$temp_resp=array('totalCount'=>array($totalCount,'int'),'results'=>array($temp,'array'));
		$final_response=array($temp_resp,'struct');
		return $this->xmlrpc->send_response($final_response);
		*/
	}

	/**
	 * updateSaveSearchFrequency is a function that updates the frequency of an alert (daily/weekly/monthly)
	 * @Input: alert Id, new frequency
	 * @Output: text signifying the frequency has been update successfully
	 * @TODO: move to Alerts
	 */
	function updateSaveSearchFrequency($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("updateSaveSearchFrequency");
		$Id=$parameters[1];
		$frequency=$parameters[2];
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == ''){
			log_message('error','updateSaveSearchStatus can not create db handle');
		}
		$queryCmd = "update tSaveSearch set frequency= ? where id= ?";
		$query = $dbHandle->query($queryCmd, array($frequency, $Id));
		$message = "search frequency updated successfully";
		$response = array($message, 'string');
		return $this->xmlrpc->send_response($response);

	}

	/**
	 * getSaveSearchFeeds is a function that updates the frequency of an alert (daily/weekly/monthly)
	 * @Input: startDate, endDate
	 * @Output: all the documents that have been added after the specified date
	 * @TODO: move to Alerts
	 */
	function getSaveSearchFeeds($request)
	{
		return; /*
		$this->init();
		$parameters=$request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		error_log_shiksha("getInfoSearch".$startDate." ".$endDate);
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == ''){
			log_message('error','saveSearch can not create db handle');
		}
		$queryCmd = "select distinct location,search_keyword from tSaveSearch where status=1";
		$query = $dbHandle->query($queryCmd);
		$temp=array();
		foreach ($query->result() as $row)
		{
			$keyword=$row->search_keyword;
			$location=$row->location;
			$type=$row->type;
			$startDate=$this->dateFormater($startDate);
			$endDate=$this->dateFormater($endDate);
			$url=SOLR_SELECT_URL_BASE;
			$url=$this->createUrl($url,$keyword,$location."","","",10,0,"","");
			$url=$url."&fq=timestamp:%5B".$startDate."+TO+".$endDate."%5D&qt=shiksha";
			$result=$this->search_lib->search_curl($url);
			$xml = simplexml_load_string($result, 'simple_xml_extended');
			$results = $xml->xpath('/response/result/doc');
			$numfound = $this->getDocCount($xml);
			$temp_array=array();
			foreach($results as $result)
			{
				array_push($temp_array,$this->getDocDetail($result,0,0,1));
			}
			$response=array('location'=>$location,'keyword'=>$keyword,'results'=>array($temp_array,'array'));
			array_push($temp,array($response,'struct'));
		}
		$final_response=array($temp,'struct');
		return $this->xmlrpc->send_response($final_response);
		*/
	}

	/**
	 * getInstituteDetails is a function that fetches the details of an institute
	 * @Input: instituteId
	 * @Output: get the array with institute details
	 * Deprecated this is not being used any more
	 */
	function getInstituteDetails($boardId)
	{
		return; /*
		$this->init();
		//error_log_shiksha($boardId);
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}
		$queryCmd= "select institute_id, institute_name, logo_link from institute where institute_id in (".$boardId.") and status=\"live\"";

		//error_log_shiksha($queryCmd);
		log_message('debug', 'get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);

		foreach($query->result() as $row)
		{
			$instituteIdList.= $row->institute_id.' ';
			$instituteNameList.= $row->institute_name.' ';
			$logo_link .=$row->logo_link.' ';
		}

		$result['instituteIdList']=$instituteIdList;
		$result['instituteNameList']=$instituteNameList;
		$result['instituteDescList']=$instituteDescList;
		$result['logo_link']=$logo_link;
		return $result;
		*/
	}

	/**
	 * getCategoryKing is a function that takes a keyword and fetches the top Categories of the result set
	 * @Input: keyword
	 * @Output: array of (categoryId, count)
	 */
	function getCategoryKing($keyword)
	{
		$this->init();
		$url=$this->createUrl(SOLR_SELECT_URL_BASE,$keyword,"","","",0,0,"","");
		$url=$url."qt=shiksha&facet=true&facet.field=categoryId&facet.zeros=false";
		$xml_content=$this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha');
		$xml = simplexml_load_string($xml_content, 'simple_xml_extended');
		$results =$xml->xpath('/response/facet_counts/facet_fields/categoryId');
		$maxCount=-1;
		$categoryKing=array();
		foreach($results as $value)
		{
			foreach($value as $x=>$y)
			{
				if($y<($maxCount/2) && $maxCount != -1)
				{
					break;
				}
				$xlist=explode('-',$x.'');
				if($xlist[1]!=1)
				{
					array_push($categoryKing,$xlist[1]);
					//echo "insert into tCategoryTagCloud (keyword,categoryId,count) values ($keyword,$xlist[1],1) on duplicate key update count=count+1";
					if($maxCount==-1)
					{
						$maxCount=$y;
					}
				}
			}
			break;
		}
		return($categoryKing);
	}

	/**
	 * spellChecker is function that fetches the alternative to a given keyword
	 * spellchecker removes all the keywords that do not exist in the index if remove=1
	 * it changes the words with suggestions that are 10 times more popular and at least 80% same
	 * @Input: keyword, removeFlag
	 * @Output: keyword suggestion
	 */
	function spellChecker($keywords,$remove=1)
	{
		$this->init();
		$temp="";
		$keywords=strtolower(trim($keywords));
		$keywords = str_replace(" ","+",$keywords);
		$keywords = str_replace("%20","+",$keywords);
		$keywordArr=explode("+",$keywords);
		foreach ($keywordArr as $word){
			$url=SOLR_INSTI_SELECT_URL_BASE;
			$url=$this->createUrl($url,$word,"","","",0,0);
			$url=$url."&qt=spellchecker";
			error_log("dhwaj spell url ".$url);
			$result=$this->search_lib->search_curl($url);
			$xml = unserialize($result);
			$suggest = $xml['suggestions'];
			$exist = $xml['exist'];
			
			
			$cacheTempKey = strtolower($word);
			if($this->cacheLib->get($cacheTempKey) != 'ERROR_READING_CACHE'){
				$temp = $temp . "+" . $word;
			}
			elseif($exist=="true")
			{

				$temp=$temp."+".$word;
			}
			else
			{
				if(count($suggest)>0)
				{
					foreach($suggest as $suggestion)
					{
						if($suggestion!="")
						{
							$temp=$temp."+".$suggestion;
						}
					}
				}
				else
				{
					if($remove!=1)
					{
						$temp= $temp."+".$word;
					}
				}
			}
		}
		//error_log("dhwaj spell return ".$temp);
		return(trim($temp));
	}

	/**
	 * getAbbr is a function that generates the abbrevation from the title
	 * getAbbr will remove all the stopwords(if removeStopWords=1) & pick up the starting alphabets from the rest of the words and creates an abbrevation
	 * @Input: tile, removeStopWords
	 * @Output: abbrevation
	 */
	function getAbbr($title,$removeStopWords=1)
	{
		$this->init();
		$RemoveLocation=preg_split("/,/",$title);
		$keyArray=preg_split("/[\p{P}\s]/",$RemoveLocation[0]);
		$abbrv="";
		foreach($keyArray as $key)
		{
			if($removeStopWords)
			{
				$fullpath_to_file=STOP_WORDS_FILE_PATH;
				if($this->cacheLib->get("an") != 'ERROR_READING_CACHE')
				{
					if( !@file_exists($fullpath_to_file) )
						error_log_shiksha("getAbbr: file ".$fullpath_to_file." does not exist");
					else
					{
						$handle = @fopen($fullpath_to_file, "r");
						if ($handle) {
							while (!feof($handle)) {
								$buffer = fgets($handle);
								$line=chop($buffer,"\n");
								error_log_shiksha("Loading APC ".$line);
								if($line!="")
									$this->cacheLib->store($line,"1");
							}
							fclose($handle);
						}
					}
				}
				if($this->cacheLib->get(strtolower($key)) != 'ERROR_READING_CACHE') {
					$abbrv=$abbrv.substr($key,0,1);
				}
			}
			else
			{
				$abbrv=$abbrv.substr($key,0,1);
			}
		}
		if(strlen($abbrv)>1)
		{
			return strtoupper($abbrv);
		}
		else
		{
			return "";
		}
	}

	/**
	 * ascii127Convert is a function that removes all charecters that fall outside the range of 32-127
	 * @Input: string
	 * @Output: formatted string with no values in ascii range 32-127
	 */
	function ascii127Convert($string)
	{
		return preg_replace('/[^\x20-\x7F]/e', ' ', $string);
	}
	/**
	 * This function take Mysql date and converts it in Solr Date
	 * @Input:date string MYSQL format
	 * @Output: date string SOLR format
	 */
	function dateFormater($date)
	{
		$datesp=explode(" ",$date);
		$newdate=$datesp[0]."T".$datesp[1]."Z";
		return $newdate;
	}

	/**
	 * isSaved is a function to check if the currect searchCriteria (keyword+location) has been used to set a save search alert by the user
	 * @Input: userId, keyword, type, location
	 * @Output: whether the saveSearch alert has been set or not for the present criteria
	 */
	function isSaved($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha("isSaved");
		$userId=$parameters[1];
		$keyword=$parameters[2];
		$type=$parameters[3];
		$location=$parameters[4];
		$status=1;
		//error_log_shiksha($boardId);
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == ''){
			log_message('error','isSaved can not create db handle');
		}
		$keywordArray=preg_split("/[\p{P}\s]/",$keyword);
		$searchKeyword="";
		foreach($keywordArray as $key)
		{
			$cacheTempKey = strtolower($key);
			if($this->cacheLib->get($cacheTempKey) != 'ERROR_READING_CACHE') {
				$searchKeyword=$searchKeyword." ".$key;
			}
		}

		//error_log_shiksha($queryCmd);
		$queryCmd = "select count(*) count from tSaveSearch where keyword= ? and location= ? and user_id= ?";
		$query = $dbHandle->query($queryCmd, array($keyword, $location, $userId));
		$totalCount=0;
		foreach($query->result() as $row)
		{
			$totalCount=$row->count;
		}
		$response = array($totalCount, 'int');
		return $this->xmlrpc->send_response($response);
	}


	/**
	 * updateSponsorListingByKeyword is a function to set the update the set of sponsored listings for set keyword/location
	 * this function takes in a keyword & location and sets all the listings in the array as featured/sponsored
	 * @Input: keyword, location, listingArray, user_id
	 * @Output: inserted id's in tSponsoredListing Table
	 * @TODO: REMOVE THIS
	 */
	function updateSponsorListingByKeyword($request)
	{
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$appId=$parameters['0'];
		$keyword=$parameters['1'];
		$location=$parameters['2'];
		$listingArray=$parameters['3'];
		$user_id=$parameters['4'];

		//$this->load->library('alertconfig');
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		
		$dbHandle = $this->getDbWriteHandle();

		$dt=date("Y:m:d H:i:s");

		if($dbHandle == '')
		{
			log_message('error','addSponsorListingByKeyword can not create db handle');
		}
		$data=array('isDeleted'=>1,'unsetUserId'=>$user_id);
		$queryCmd = $dbHandle->update_string('tSponsoredListingByKeyword',$data,"keyword='".$keyword."' and location='".$location."'");
		$query=$dbHandle->query($queryCmd);

		error_log_shiksha($queryCmd);

		foreach($listingArray as $row)
		{
			$type=$row['type'];
			$listingId=$row['listingId'];
			$data=array('keyword'=>$keyword,'location'=>$location,'setUserId'=>$user_id,'listingId'=>$listingId,'type'=>$type);
			$queryCmd=$dbHandle->insert_string('tSponsoredListingByKeyword',$data);
			error_log_shiksha($queryCmd);
			$query=$dbHandle->query($queryCmd);
		}
		$response = array(
				array(
					'result'=>0,
					'insertId'=>$insertId
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getFeaturesSponsoredListingFromDb is a function to get the featured and sponsored listings from db, for cms search.
	 */
	function getFeaturedSponsoredListingsFromDb($keyw, $location,$type,$searchType, &$sponsorIdArray, &$featuredIdArray){

		$this->init();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == '')
		{
			log_message('error','searchListingKeywordCMS can not create db handle');
		}
		$queryCmd="select * from tSponsoredListingByKeyword where keyword= ? and location= ? and isDeleted=0 and type= ? and searchType= ? and sponsorType='sponsor' order by update_time,RAND()";
		$query = $dbHandle->query($queryCmd,array($keyw, $location, $type, $searchType));
		foreach ($query->result() as $row)
		{
			$sponsorIdArray[$row->listingId]=$row->type;
		}
		$queryCmd="select * from tSponsoredListingByKeyword where keyword= ? and location= ? and isDeleted=0 and type= ? and searchType= ? and sponsorType='featured' order by update_time,RAND()";
		$query = $dbHandle->query($queryCmd,array($keyw, $location, $type, $searchType));
		foreach ($query->result() as $row)
		{
			$featuredIdArray[$row->listingId]=$row->type;
		}  
	}
	/**
	 * getSolrListingWithoutFeaturedSponsoredForCMS is a function to get the search result doc array (solr only).
	 */
	function getSolrListingWithoutFeaturedSponsoredForCMS($url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,&$temp_array){
		$url=$this->createUrl($url,$keyw,$location,"","",$rows,$start,$type,$searchType);
		$url=$url=$url."qt=shiksha&";
		if($userId!='')
		{
			$url=$url."&fq=userId:".$userId."&";
		}
		// Pankaj chnages : new code
		// in new solr the format has been changed, all the fields have extra name attribute, earlier it was missing
		$new_xml_format = $this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha'); // no change here
		$new_xml_content = unserialize($new_xml_format); // unserialize the data
		$contentResults = $new_xml_content['response']['docs']; // docs will be present in this key
		$numfound = $new_xml_content['response']['numFound']; // total results will be present in this key
		error_log("PANKAJ in getSolrListingWithoutFeatured : xml : " . print_r($new_xml_content, true));
		foreach($contentResults as $result)
		{
			$result = (object)$result;
			array_push($temp_array,$this->getDocDetailCMS($result,$sponsorIdArray,$featuredIdArray));
		}
	}

	/**
	 * getSolrListingWithSponsoredOnTopForCMS is a function to get the search result doc array (solr + db-featured).
	 */
	function getSolrListingWithSponsoredOnTopForCMS($sponseredOnTop,$featuredOnTop,$searchEntityType,$url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,&$temp_array){

		if(count($sponsorIdArray)>$start)
		{
			$i=0;
			foreach ($sponsorIdArray as $key=>$value)
			{
				if((($start+$rows)>$i) && ($i>=$start))
				{
					//Pankaj
					$url = SOLR_SELECT_URL_BASE;
					if($searchEntityType == "institute" || $searchEntityType == "course") {
						$url = SOLR_INSTI_SELECT_URL_BASE;	
					}
					$url=$this->createUrl($url,$keyw,$location,"","",$rows,$start,$type,$searchType);
					$uniqueId=$value.$key;
					if($userId!='')
					{
						$url=$url."fq=userId:".$userId."&";
					}
					$url.="fq=uniqueId:$uniqueId&fl=*&";
					$url=$url=$url."qt=shiksha&";


					// Pankaj chnages : new code
					// in new solr the format has been changed, all the fields have extra name attribute, earlier it was missing
					$new_xml_format = $this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha'); // no change here
					$new_xml_content = unserialize($new_xml_format); // unserialize the data
					$contentResults = $new_xml_content['response']['docs']; // docs will be present in this key
					$numfound = $new_xml_content['response']['numFound']; // total results will be present in this key
					foreach($contentResults as $result) {
						$result = (object)$result;
						array_push($temp_array,$this->getDocDetailCMS($result,$sponsorIdArray,$featuredIdArray));
						$numSponser = $numSponser + 1;
					}

				}
				$i=$i+1;
			}
		}
		if($start>count($sponsorIdArray))
			$start=$start-count($sponsorIdArray);
		else
			$start=0;

		$url = SOLR_SELECT_URL_BASE;
		if($searchEntityType == "institute" || $searchEntityType == "course"){
			$url = SOLR_INSTI_SELECT_URL_BASE;	
		}
		$url=$this->createUrl($url,$keyw,$location,$categoryId,$countryId,($rows-$numSponser-$numFeatured),$start,$type,$searchType);
		$url=$url=$url."qt=shiksha&";

		foreach($sponsorIdArray as $key=>$val)
		{
			$uniqueId=$val.$key;
			$url=$url."fq=-uniqueId:".$uniqueId."&";
		}
		if($userId!='')
		{
			$url=$url."fq=userId:".$userId."&";
		}
		// Pankaj chnages : new code
		// in new solr the format has been changed, all the fields have extra name attribute, earlier it was missing
		$url .= "&wt=phps"; // now the url should contain wt=phps for the data to be in php serialized format
		$new_xml_format = $this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha'); // no change here
		$new_xml_content = unserialize($new_xml_format); // unserialize the data
		$contentResults = $new_xml_content['response']['docs']; // docs will be present in this key
		$numfound = $new_xml_content['response']['numFound']; // total results will be present in this key	
		$numfound+=count($sponsorIdArray);

		foreach($contentResults as $result) {
			$result = (object)$result;
			array_push($temp_array,$this->getDocDetailCMS($result,$sponsorIdArray,$featuredIdArray));
		}
	}
	/**
	 * getSolrListingWithFeaturedOnTopForCMS is a function to get the search result doc array (solr + db-featured).
	 */
	function getSolrListingWithFeaturedOnTopForCMS($sponseredOnTop,$featuredOnTop,$searchEntityType,$url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,&$temp_array){
		if(count($featuredIdArray)>$start)
		{
			$i=0;
			foreach ($featuredIdArray as $key=>$value)
			{
				if((($start+$rows)>$i) && ($i>=$start))
				{
					if(($sponsorIdArray[$key]!=$value) || ($sponseredOnTop==0))
					{
						//Pankaj
						$url=$this->search_lib->getSolrUrlByType($type,"select");
						$url=$this->createUrl($url,$keyw,$location,"","",$rows,$start,$type,$searchType);
						$uniqueId=$value.$key;
						if($userId!='')
						{
							$url=$url."fq=userId:".$userId."&";
						}
						$url.="fq=uniqueId:$uniqueId&fl=*&";
						$url=$url=$url."qt=shiksha&";

						// Pankaj chnages : new code
						// in new solr the format has been changed, all the fields have extra name attribute, earlier it was missing
						$new_xml_format = $this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha'); // no change here
						$new_xml_content = unserialize($new_xml_format); // unserialize the data
						$contentResults = $new_xml_content['response']['docs']; // docs will be present in this key
						$numfound = $new_xml_content['response']['numFound']; // total results will be present in this key
						foreach($contentResults as $result) {
							$result = (object)$result;
							array_push($temp_array,$this->getDocDetailCMS($result,$sponsorIdArray,$featuredIdArray));
							$numFeatured = $numFeatured + 1;
						}

					}
				}
				$i=$i+1;
			}
		}
		if($start>count($featuredIdArray))
			$start=$start-count($featuredIdArray);
		else
			$start=0;

		$url=$this->search_lib->getSolrUrlByType($type,"select");
		$url=$this->createUrl($url,$keyw,$location,$categoryId,$countryId,($rows-$numSponser-$numFeatured),$start,$type,$searchType);
		$url=$url=$url."qt=shiksha&";

		foreach($featuredIdArray as $key=>$val)
		{
			$uniqueId=$val.$key;
			$url=$url."fq=-uniqueId:".$uniqueId."&";
		}
		if($userId!='')
		{
			$url=$url."fq=userId:".$userId."&";
		}

		// Pankaj chnages : new code
		// in new solr the format has been changed, all the fields have extra name attribute, earlier it was missing
		$new_xml_format = $this->search_lib->search_curl($url,$keyw,$start,$rows,'shiksha'); // no change here
		$new_xml_content = unserialize($new_xml_format); // unserialize the data
		$contentResults = $new_xml_content['response']['docs']; // docs will be present in this key
		$numfound = $new_xml_content['response']['numFound']; // total results will be present in this key	
		$numfound+=count($featuredIdArray);
		foreach($contentResults as $result) {
			$result = (object)$result;
			array_push($temp_array,$this->getDocDetailCMS($result,$sponsorIdArray,$featuredIdArray));
		}

	}
	/**
	 * searchListingKeywordCMS is a webservice API that takes in a set of parameters to search such as keyword, location etc and then retuerns the
	 * list of results for the query.
	 * The api is a replica of SearchListingWithSponsor, but does not do the OR parameter search
	 * it also has a flag for showing sponsored results on top
	 * @Input : keyword, location, countryId, categoryId, rows, start, type ,searchType , cityId, courseType, CourseLevel, max and min Duration
	 * @Output: the set of results and clusters of type, location, courseType, courseLevel and Duration
	 * @TODO: check who uses this and try to use searchListingWithSponsor instead
	 */
	function searchListingKeywordCMS($request)
	{
		error_log("PANKY : in searchListingKeywordCMS");
		error_log_shiksha("Entering searchListingCMS");
		$parameters=$request->output_parameters();

		$keyw=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[1]))));
		$location=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[2]))));
		$start=trim($parameters[3]);
		$rows=trim($parameters[4]);
		$type=trim($parameters[5]);
		$sponseredOnTop=$parameters[6];
		$featuredOnTop=$parameters[7];
		$searchType=trim($parameters[8]);
		$userId=trim($parameters[9]);

		//use Pankaj method for url
		$searchEntityType = $type; //if type var is overwriting somewhere below
		$url = SOLR_SELECT_URL_BASE;
		$url=$this->search_lib->getSolrUrlByType($type,"select");
		$sponsorIdArray=array();
		$featuredIdArray=array();
		$temp_array=array();

		$this->getFeaturedSponsoredListingsFromDb($keyw, $location,$type,$searchType, $sponsorIdArray, $featuredIdArray);

		$numfound=0;
		$numSponser=0;
		$numFeatured=0;
		if(($sponseredOnTop==0) && ($featuredOnTop==0))
		{
			error_log("PANKY in if block of sponsored featured");
			$this->getSolrListingWithoutFeaturedSponsoredForCMS($url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,$temp_array);
		}
		else
		{
			error_log("PANKY : in else check");
			if($sponseredOnTop==1){
				$this->getSolrListingWithSponsoredOnTopForCMS($sponseredOnTop,$featuredOnTop,$searchEntityType,$url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,$temp_array);
			}
			if($featuredOnTop==1){
				$this->getSolrListingWithFeaturedOnTopForCMS($sponseredOnTop,$featuredOnTop,$searchEntityType,$url,$userId,$keyw,$location,$rows,$start,$type,$searchType,$sponsorIdArray,$featuredIdArray,$temp_array);
			}
		}
		$response=array(array('numOfRecords'=>array($numfound,'int'), 'results'=>array($temp_array,'array')),'struct');
		error_log("PANKY : final response " . print_r($response, true));
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getDocDetailCMS is a function that fetches the details of te elements from the XML
	 * @Input : the XML part of the listings, isSponsored, isSaveSearch
	 * @Output: the details of the document, isSponsored will set it as sponsored and highlightResult will highlight the searched Keyword
	 * @TODO: looks like this is a replica of getDocDetail, try to merge both
	 */
	function getDocDetailCMS($result,$sponsorArray,$featuredArray)
	{
		error_log("PANKY inside getDocDetailCMS");
		$Id=(string)$result->Id;
		$type=(string)$result->type;
		$title=(string)$result->title;
		$uniqueId=$type.$Id;
		$userId=(string)$result->userId;
		$isSponsored=0;
		if($sponsorArray[$Id]==$type)
		{
			$isSponsored=1;
		}
		if($featuredArray[$Id]==$type)
		{
			$isFeatured=1;
		}
		if($type=="course")
		{
			$instituteList=(string)$result->instituteList;
			$cityList=(string)$result->cityList;
			$cityArray=explode(" ",trim($cityList));
			$city=$cityArray[0];
			$countryList=(string)$result->countryList;
			$countryArray=explode(" ",trim($countryList));
			$country=$countryArray[0];
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'college'=>$instituteList,'city'=>$city,'country'=>$country,'userId'=>$userId,'isSponsored'=>$isSponsored,'isFeatured'=>$isFeatured),'struct'));
		}
		else if($type=="institute")
		{
			$cityList=(string)$result->cityList;
			$cityArray=explode(" ",trim($cityList));
			$city=$cityArray[0];
			$countryList=(string)$result->countryList;
			$countryArray=explode(" ",trim($countryList));
			$country=$countryArray[0];
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'city'=>$city,'country'=>$country,'userId'=>$userId,'isSponsored'=>$isSponsored,'isFeatured'=>$isFeatured),'struct'));
		}
		else if($type=="scholarship")
		{
			$value=(string)$result->value;
			$number=(string)$result->number;
			$eligibility=(string)$result->eligibility;
			$applicableTo=(string)$result->levels;
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'eligibility'=>$eligibility,'applicableTo'=>$applicableTo,'number'=>$number,'value'=>$value,'isSponsored'=>$isSponsored,'isFeatured'=>$isFeatured),'struct'));
		}
		else if($type=="notification")
		{
			$value=(string)$result->value;
			$number=(string)$result->number;
			$endDate=date('jS M, y, h:ia',strtotime($result->endDate));
			$instituteList=(string)$result->instituteList;
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'number'=>$number,'value'=>$value,'endDate'=>$endDate,'collegeInfo'=>$instituteList,'isSponsored'=>$isSponsored,'isFeatured'=>$isFeatured),'struct'));
		}
		else if($type=="Event")
		{
			$startDate=(string)$result->startDate;
			$endDate=(string)$result->endDate;
			$location=(string)$result->cityList."-".$result->countryList;
			return(array(array('typeId'=>$Id,'type'=>$type,'title'=>$title,'location'=>$location,'startDate'=>date('jS M, y, h:ia',strtotime( $startDate)),'endDate'=>date('jS M, y, h:ia',strtotime($endDate)),'isSponsored'=>$isSponsored,'isFeatured'=>$isFeatured),'struct'));

		}
	}

	/**
	 * addSponsorListingByKeyword is a webservice API that takes in a set of parameters to search such as keyword, location etc and a listingId and sets the listing a sponsored result for the specified search parameters
	 * @Input : keyword, location, searchType ,listingType, listingId, userid and sponsorType
	 * @Output: string to signify if the sponsor result has been set
	 */
	function addSponsorListingByKeyword($request)
	{
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$appId=$parameters['0'];
		$keyword=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[1]))));
		$location=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[2]))));
		$listingId=$parameters['3'];
		$type=$parameters['4'];
		$user_id=$parameters['5'];
		$searchType=trim($parameters['6']);
		$sponsorType=trim($parameters['7']);
		$this->load->library('alertconfig');
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();

		$dt=date("Y:m:d H:i:s");

		if($dbHandle == '')
		{
			log_message('error','addSponsorListingByKeyword can not create db handle');
		}
		$queryCmd="select count(*) count from tSponsoredListingByKeyword where keyword= ? and location= ? and searchType= ? and sponsorType= ?";
		$query=$dbHandle->query($queryCmd, array($keyword, $location, $searchType, $sponsorType));
		foreach($query->result() as $row)
		{
			$count=$row->count;
			if($count>=50)
			{
				$response = array(
						array(
							'result'=>1,
						     ),
						'struct');
				return $this->xmlrpc->send_response($response);
			}
		}
		$queryCmd="INSERT INTO tSponsoredListingByKeyword (keyword, location, setUserId, listingId, type, set_time, searchType,sponsorType) VALUES ( ?, ?, ?, ?, ?, now(), ?, ?)";
		$query=$dbHandle->query($queryCmd, array($keyword, $location, $user_id, $listingId, $type, $searchType, $sponsorType));
		$response = array(
				array(
					'result'=>0,
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * deleteSponsorListingByKeyword is a webservice API that takes in a set of parameters to search such as keyword, location etc and a listingId and deletes the listing a sponsored result for the specified search parameters
	 * @Input : keyword, location, searchType ,listingType, listingId, userid and sponsorType
	 * @Output: string to signify if the sponsor result has been removed as the listingType
	 */
	function deleteSponsorListingByKeyword($request)
	{
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$appId=$parameters['0'];
		$keyword=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[1]))));
		$location=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[2]))));
		$listingId=$parameters['3'];
		$type=$parameters['4'];
		$user_id=$parameters['5'];
		$searchType=trim($parameters['6']);
		$sponsorType=trim($parameters['7']);
		//$this->load->library('alertconfig');
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		
		$dbHandle = $this->getDbWriteHandle();

		$dt=date("Y:m:d H:i:s");

		if($dbHandle == '')
		{
			log_message('error','addSponsorListingByKeyword can not create db handle');
		}

		$queryCmd="UPDATE tSponsoredListingByKeyword SET isDeleted = 1, unsetUserId = ?, unset_time=now() WHERE keyword= ? and location= ? and listingId= ? and type= ? and searchType= ? and sponsorType= ?";
		$query=$dbHandle->query($queryCmd, array($user_id, $keyword, $location, $listingId, $type, $searchType, $sponsorType));
		$response = array(
				array(
					'result'=>0,
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getSponsorListingStatusByKeyword is a webservice API that takes in a set of parameters to search such as keyword, location etc and a listingId and deletes the listing a sponsored result for the specified search parameters
	 * @Input : keyword, location, searchType ,listingType, listingId, userid and sponsorType
	 * @Output: the array of listings set for the the above criteria
	 */
	function getSponsorListingStatusByKeyword($request)
	{
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$appId=$parameters['0'];
		$keyword=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[1]))));
		$location=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[2]))));
		$listingId=$parameters['3'];
		$type=$parameters['4'];
		$user_id=$parameters['5'];
		$searchType=trim($parameters['6']);
		$sponsorType=trim($parameters['7']);


		//$this->load->library('alertconfig');
		////connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		
		$dbHandle = $this->getDbReadHandle();

		$dt=date("Y:m:d H:i:s");

		$result=0;
		if($dbHandle == '')
		{
			log_message('error','addSponsorListingByKeyword can not create db handle');
		}
		$queryCmd="select * from tSponsoredListingByKeyword where keyword=? and location=? and listingId=? and type=? and searchType=? and isDeleted=0 and sponsorType=?";
		$query=$dbHandle->query($queryCmd, array($keyword, $location, $listingId, $type, $searchType, $sponsorType));
		foreach($query->result() as $row)
		{
			if(count($row)>0)
			{
				if($row->isDeleted==1)
					$result=0;
				else
					$result=1;
			}
			else
			{
				$result=0;
			}
		}
		$response = array($result,'int');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * getFeaturedImageUrls is a webservice API that takes in a set of parameters to search such as keyword, location etc and a listingId and deletes the listing a sponsored result for the specified search parameters
	 * @Input : keyword, location, searchType ,listingType, listingId, userid and sponsorType
	 * @Output: the urls of the featured listing that needs to shown in the featured panel
	 */
	function getFeaturedImageUrls($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$keyw=strtolower(trim($parameters[1]));
		$location=strtolower(trim($parameters[2]));
		$countryId=trim($parameters[3]);
		$categoryId=trim($parameters[4]);
		$type=trim($parameters[5]);
		$searchType=trim($parameters[6]);
		$this->load->helper(array('image'));
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == '')
		{
			log_message('error','getFeaturedImageUrls can not create db handle');
		}
		$queryCmd="select * from tSponsoredListingByKeyword, institute, listings_main where tSponsoredListingByKeyword.sponsorType='featured' and tSponsoredListingByKeyword.listingId =institute.institute_id and listings_main.listing_type_id=tSponsoredListingByKeyword.listingId and tSponsoredListingByKeyword.keyword=? and tSponsoredListingByKeyword.location=? and institute.featured_panel_link!='' and listings_main.status = 'live' and listings_main.listing_type='institute' and institute.status = 'live' and isDeleted=0 and tSponsoredListingByKeyword.set_time <= now() and tSponsoredListingByKeyword.unset_time >= now() group by tSponsoredListingByKeyword.listingId order by update_time,RAND() limit 6";
		$query=$dbHandle->query($queryCmd,array($keyw,""));
		error_log_shiksha($queryCmd);
		$temp_array=array();
		foreach($query->result() as $row)
		{
			$imageURL=$row->featured_panel_link;
			$url="/getListingDetail/".$row->listingId."/".$row->type;
			$data=array('count'=>($row->count+1));
			$queryCmd = $dbHandle->update_string('tSponsoredListingByKeyword',$data,"id='".$row->id."'");
			$query=$dbHandle->query($queryCmd);
			array_push($temp_array,array(array('imageUrl'=>$imageURL,'url'=>$url),'struct'));
		}
		$response=array(array('results'=>array($temp_array,'array')),'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * increaseSearchSnippetCount will increase the search snippet count of the listing
	 * the searchSnippet count is stored in the APC against each listingType, listingId
	 * the tags in the APC are searchSnippet_<id> and the id is a counter stored in searchSnippetCount
	 * Once the searchSnippetCount reaches 500 it is dumped to the DB and reset to 0
	 * @Input : lisintType and listing Id
	 * @Output: void
	 * @TODO: remove all the rest of the parameters that are being sent apart from the above mentioned
	 */
	function increaseSearchSnippetCount($id,$type,$listingType,$order,$start,$rows,$keyword,$location,$countryId,$categoryId,$searchType)
	{
		$this->init();
		$cacheTempKey = "searchSnippetCount";
		if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE') {
			$count = false;	
		} else {
			$count = $this->cacheLib->get($cacheTempKey);
		}
		
		if($count==false) {
			$this->cacheLib->store($cacheTempKey, 1);
			$cacheTempKey = "searchSnippetCount";
			if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE') {
				$count = false;	
			} else {
				$count = $this->cacheLib->get($cacheTempKey);
			}
		}
		if($count>500) {
			$i=1;
			$snippetArray=array();
			while($i<$count) {
				$cacheTempKey = "searchSnippet_".$i;
				if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE') {
					$rowArray = false;
				} else {
					$rowArray = $this->cacheLib->get($cacheTempKey);
				}
				array_push($snippetArray,array($rowArray,'struct'));
				$i++;
			}
			$ListingClientObj = new Listing_client();
			$searchResult = $ListingClientObj->updateSearchSnippetCount(12,$snippetArray);
			$this->cacheLib->store('searchSnippetCount',1);
			$cacheTempKey = "searchSnippetCount";
			if($this->cacheLib->get($cacheTempKey) == 'ERROR_READING_CACHE') {
				$count = false;	
			} else {
				$count = $this->cacheLib->get($cacheTempKey);
			}
		}
		$count++;
		$effectiveCountValue = $count - 1;
		$this->cacheLib->store('searchSnippet_'.$effectiveCountValue,array('typeId'=>$id,'type'=>$type));
		$this->cacheLib->store('searchSnippetCount',$count);
		return(1);
	}

	/**
	 * updateSearchSnippetCount will increase the search snippet count of the listing in the tSearchSnippetStatTemp
	 * updateSearchSnippetCount will take in the searchArray and will insert into the table
	 * the searchSnippet count on reaching 500, this function is called and thus the table is updated
	 * @Input : listingType, Listing Id array
	 * @Output: void
	 */
	function updateSearchSnippetCount($request)
	{
		return; /*
		$this->init();
		$parameters = $request->output_parameters();
		$searchArray = $parameters[1];
		error_log_shiksha(print_r($searchArray,true));
		$i=0;
		while($i<=count($searchArray))
		{
			$rowArray=$searchArray[$i];
			$tempRec="";
			if(sizeof($rowArray)==2)
			{
				$tempRec="(";
				foreach($rowArray as $value)
				{
					if($tempRec=="(")
					{
						$tempRec.="'".$value."'";
					}
					else
					{
						$tempRec.=",'".$value."'";
					}
				}
				$tempRec.=")";
			}
			if($insertStatement=="")
			{
				if($tempRec!="")
				{
					$insertStatement=$tempRec;
				}
			}
			else
			{
				if($tempRec!="")
				{
					$insertStatement.=",".$tempRec;
				}
			}
			$i++;
		}
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		if($dbHandle == '')
		{
			log_message('error','increaseSnippetCount can not create db handle');
		}
		$insertStatement='insert delayed into tSearchSnippetStatTemp (listingId,type) values '.$insertStatement.' on duplicate key update count=count+1;';
		error_log_shiksha($insertStatement);
		$query=$dbHandle->query($insertStatement);
		return($this->xmlrpc->send_response(array(1,'int')));
		*/
	}

	/**
	 * getSearchSnippetCount is a function to get the snippet shown count against the listing in the table
	 * @Input : listingType, Listing Id
	 * @Output: listingId, listingType and count
	 */
	function getSearchSnippetCount($request)
	{
		$this->init();
		$parameters=$request->output_parameters();
		//			error_log_shiksha(print_r($parameters,true));
		$AppId=strtolower(trim($parameters[0]));
		$type=strtolower(trim($parameters[1]));
		$typeId=trim($parameters[2]);
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->messageboardconfig->getDbConfig(1,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		if($dbHandle == '')
		{
			log_message('error','increaseSnippetCount can not create db handle');
		}
		$queryCmd="select listingId,type,count from tSearchSnippetStatTemp where listingId=? and type=?;";
		$query=$dbHandle->query($queryCmd, array($typeId, $type));
		$msgArray = array();
		foreach ($query->result_array() as $row)
		{
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * getCityCountryName is a function that takes in the cityCluster (set of Ids)
	 * and fetches the name of the respective cities and the countries of the respective cities
	 * @Input: array of cityId's
	 * @Output: Array of (cityId, cityName, countryName)
	 */
	function getCityCountryName($clusterCity)
	{
		//$cityCountryMap=array();
		$this->init();
		$ListingClientObj = new Listing_client();
		foreach($clusterCity as $key=>$value)
		{
			$cityCountry = '';
			/*
			if(!apc_fetch("city2Country_".$key))
			{
				$ListingClientObj->updateApcForSearch();
			}
			if(apc_fetch("city2Country_".$key))
			{
				$cityCountry=apc_fetch("city2Country_".$key);
			}
			*/
			$cacheTempKey = "city2Country_".$key;
			if($this->cacheLib->get($cacheTempKey)=='ERROR_READING_CACHE'){
				$ListingClientObj->updateApcForSearch();
			}
			$cacheKeyValue = $this->cacheLib->get($cacheTempKey);
			if($cacheKeyValue != 'ERROR_READING_CACHE') {
				$cityCountry = $cacheKeyValue;
			}
			
			if($cityCountry!='')
				$cityCountryMap[$key]=$cityCountry."";
		}
		return $cityCountryMap;
	}

	/**
	 * checkValidKeywordForListing checks whether the keyword is a valid for the listing to be set as sponsored/featured
	 * @Input: array of keywords, listingType, listingId
	 * @Output: Array of (keyword, flag{signifing isAllpowed})
	 */
	function checkValidKeywordForListing($request)
	{
		$parameters=$request->output_parameters();
		$listingId=$parameters[1];
		$listingType=$parameters[2];
		$keywordArray=$parameters[3];
		if(count($keywordArray)==0)
		{
			return $this->xmlrpc->send_response(array(array('output'=>1,'ErrorMsg'=>"Please enter a valid listingId, listingType and Keyword array."),'struct'));
		}
		if(count($keywordArray)>25)
		{
			return $this->xmlrpc->send_response(array(array('output'=>1,'ErrorMsg'=>"The current implementation supports not more than 25 keywords per-go. Please try again."),'struct'));
		}
		$uniqueId=$listingType.$listingId;
		$temp_array=array();
		foreach($keywordArray as $keyword)
		{
			// pankaj
			$url = SOLR_SELECT_URL_BASE;
			if($listingType == "institute" || $listingType == "course"){
				$url = SOLR_INSTI_SELECT_URL_BASE;
			}

			$url.="q=".urlencode($keyword)."&qt=shiksha&fq=uniqueId:".$uniqueId."&rows=0";
			error_log_shiksha($url);
			$xml_content=$this->search_lib->search_curl($url,$keyw,0,0,'shiksha');
			$xml = simplexml_load_string($xml_content, 'simple_xml_extended');
			$numfound = $this->getDocCount($xml);
			error_log_shiksha($numfound);
			if($numfound==0)
			{
				$validityDetail=array(array('keyword'=>$keyword,'isRelevant'=>0),'struct');
				array_push($temp_array,$validityDetail);
			}
			else
			{
				$validityDetail=array(array('keyword'=>$keyword,'isRelevant'=>1),'struct');
				array_push($temp_array,$validityDetail);
			}
		}
		$response = array($temp_array,'struct');
		return $this->xmlrpc->send_response(array(array('output'=>0,'result'=>$response),'struct'));
	}
	/**
	 * addSponsorListing adds the mentioned listing as sponsored/featured for the keyword/location/type between the mentioned time
	 * this function will check if the listing is already added for the given set of search parameters
	 * if the listing is not set for the given for the search keyword/location then it sets it for the mentioned period
	 * @Input: keyword,listingId, type, startDate, endDate, subscriptionId, sponsorType, location
	 * @Output: the id of the sponsorListing created
	 */
	function addSponsorListing($request)
	{
		return; /*
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		$appId=$parameters['0'];
		$keyword=strtolower(trim(str_replace("&","+",html_entity_decode($parameters[1]))));
		$listingId=$parameters['2'];
		$type=$parameters['3'];
		$startDate=$parameters['4'];
		$endDate=$parameters['5'];
		$subscriptionId=$parameters['6'];
		$sponsorType=trim($parameters['7']);
		$location = strtolower(trim(str_replace("&","+",html_entity_decode($parameters[8]))));
		//$this->load->library('alertconfig');
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		
		$dbHandle = $this->getDbWriteHandle();
		
		$dt=date("Y:m:d H:i:s");

		if($dbHandle == '')
		{
			log_message('error','addSponsorListingByKeyword can not create db handle');
		}
		error_log_shiksha("searchCI ".$queryCmd);

		$queryCmd="select count(*) count from tSponsoredListingByKeyword where keyword=? and location=? and searchType=? and sponsorType=? and set_time <= now() and unset_time >= now() and listingId=?";
		$query=$dbHandle->query($queryCmd, array($keyword, $location, $searchType, $sponsorType, $listingId));
		foreach($query->result() as $row)
		{
			$count=$row->count;
			if($count>=1)
			{
				$response = array(
						array(
							'result'=>-1,
							'error'=>$keyword
						     ),
						'struct');
				return $this->xmlrpc->send_response($response);
			}
		}
		$queryCmd="INSERT INTO tSponsoredListingByKeyword (keyword, listingId, type, set_time, unset_time, subscriptionId, searchType,sponsorType,location) VALUES ('".$keyword."','".$listingId."', '".$type."','".$startDate."','".$endDate."','".$subscriptionId."','','".$sponsorType."','".$location."')";
		error_log_shiksha("searchCI ".$queryCmd);
		$query=$dbHandle->query($queryCmd);
		$keywordId=$dbHandle->insert_id();
		$response = array(
				array(
					'result'=>$keywordId,
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);
		*/
	}
	/**
	 * cancelSubscription is a function that will delete all the sponsored/featured listing against a subscriptionId
	 * @Input: subscriptionId
	 * @Output: resultFlag(boolean)
	 */
	function cancelSubscription($request)
	{
		$this->init();
		$parameters = $request->output_parameters();
		error_log_shiksha(print_r($parameters,true));
		//$this->load->library('alertconfig');
		$appId=$parameters['0'];
		$subscriptionId=$parameters['1'];
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		
		$queryCmd="update tSponsoredListingByKeyword  set isDeleted=1 where subscriptionId=?";
		$query=$dbHandle->query($queryCmd, $subscriptionId);
		$response = array(
				array(
					'result'=>1,
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);


	}
	
	/**
	 * updateSponsorListingDetails is a function that updates the specified columns with the values provided.
	 * @Input: array of(key=>value)
	 * @Output: result=>1
	 */
	function updateSponsorListingDetails($request)
	{
		return; /*
		$this->init();
		$parameters=$request->output_parameters();
		$id=$parameters[0];
		//update Array is a key value pair with columnname as key and updated value as value like for extending expiry date {'unset_time'=>'2008-10-10'};
		$updateArray=$parameters[1];
		$update = "";
		$i=0;
		foreach($updateArray as $key=>$val)
		{
			if($i==0)
			{
				$update.=" ".$key."='".$val."'";
			}
			else
			{
				$update.=", ".$key."='".$val."'";
			}
			$i++;
		}
		//$this->load->library('alertconfig');
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbWriteHandle();
		$queryCmd="update tSponsoredListingByKeyword  set $update where Id=?";
		$query=$dbHandle->query($queryCmd, $id);
		$response = array(
				array(
					'result'=>1,
				     ),
				'struct');
		return $this->xmlrpc->send_response($response);
		*/
	}
	function cleanCSVField($str){
		$str=trim(preg_replace("/^,/","",trim($str)),"-");
		$tags=explode(",",$str);
		$tagList="";
		foreach($tags as $tag)
		{
			if(strlen(trim($tag))>0)
			{
				$tag=trim(preg_replace("/[^A-Za-z0-9,]/","-",trim($tag)),"-");
				$tagList=($tagList=="")?$tag:$tagList.",".trim($tag);
			}
			else
				$tagList=($tagList=="")?"Other":$tagList.",Other";
		}
		if($tagList!="")
		{
			return trim(preg_replace("/[^A-Za-z0-9,]/","-",trim($tagList)),"-");
		}
		else
		{
			return("Other");
		}
	}

	function getDataForGenerationOfSeoUrl($request) {
		return; /*
		$this->init();
		$parameters=$request->output_parameters();
		$appId=$parameters[0];
		$start=$parameters[1];
		$count=$parameters[2];

		//This is to make a Db connection that will be used in this function
		//$this->load->library('alertconfig');
		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->alertconfig->getDbConfig($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->getDbReadHandle();
		$queryCmd = "select SQL_CALC_FOUND_ROWS * from topSearches LIMIT $start , $count";
		$Result = $dbHandle->query($queryCmd);
		$mainArr = array();
		$mainArr['resultSet'] = $Result->result_array();
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr['totalRows'] = $totalRows;
		$resultSet = base64_encode(gzcompress(json_encode($mainArr)));
		$response = array(array($resultSet,'string'),'struct');
		return $this->xmlrpc->send_response($response);
		*/
	}

}
?>
