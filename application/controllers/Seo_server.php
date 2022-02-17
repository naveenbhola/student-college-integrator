<?php

class Seo_server extends MX_Controller
{
	var $CI = NULL;
	var $seo_url_date_limit = NULL;

	function index()
	{
		$this->CI = & get_instance();
		$this->seo_url_date_limit = $this->CI->config->item('seo_url_date_limit');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('listingconfig');
		$this->load->helper('url');

	        $this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('Seo');

		$config['functions']['getSeoUrlNewSchema'] = array('function' => 'Seo_server.getSeoUrlNewSchema');
		$config['functions']['getTitleFromId'] = array('function' => 'Seo_server.getTitleFromId');
		$config['functions']['getEventLocationAndOthers'] = array('function' => 'Seo_server.getEventLocationAndOthers');
		$config['functions']['getTitleAndLocationForInstitute'] = array('function' => 'Seo_server.getTitleAndLocationForInstitute');
		$config['functions']['getTitleAndLocationForCourse'] = array('function' => 'Seo_server.getTitleAndLocationForCourse');
		$config['functions']['getURLFromDB'] = array('function' => 'Seo_server.getURLFromDB');
		$config['functions']['getURLForSitemap'] = array('function' => 'Seo_server.getURLForSitemap');

		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
    }

    private function loadDatabaseHandle($operation='read'){
                if($operation=='read'){
                        $dbHandle = $this->dbLibObj->getReadHandle();
                }
                else{
                        $dbHandle = $this->dbLibObj->getWriteHandle();
                }

                if($dbHandle == ''){
			log_message('error','can not create db handle');
     		        return false;
                }
	        return $dbHandle;
    }

    function getSeoUrlNewSchema($request)
    {
		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		
		if(isset($_COOKIE['latestThreadId']) && $_COOKIE['latestThreadId']>0 && $_COOKIE['latestThreadId']==$id){
			$dbHandle = $this->loadDatabaseHandle('write');
		}
		else{
			$dbHandle = $this->loadDatabaseHandle();
		}
		
		$fromDate = $this->seo_url_date_limit;
		if ($type == 'question')
		{
				//$queryCmd = "SELECT DATEDIFF( '$fromDate', creationDate) AS datediff ,msgTxt AS title FROM messageTable WHERE msgId ='$id'";
				$queryCmd = "SELECT DATEDIFF( '$fromDate', creationDate) AS datediff , m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 WHERE m1.msgId =?";
				$query = $dbHandle->query($queryCmd, array($id));
				$row = $query->row();
				$datediff = $row->datediff;
				//If this is discussion/announcement, then get the Title from the next msgId
				if($row->fromOthers == 'discussion' || $row->fromOthers == 'announcement'){
				    $queryCmd = "SELECT msgTxt AS title FROM messageTable WHERE threadId =? and mainAnswerId = 0 LIMIT 1";
				    $query = $dbHandle->query($queryCmd, array($id));
				    foreach ($query->result_array() as $row)
				    {
					$title	= $row['title'];
				    }
				}
				else{
				    if($this->check_legacy_seo_update($row->creationDate,$row->descriptionD)){
					$title = $row->descriptionD;
				    }
				    else{
					$title = $row->title;
				    }
				}
		}
		else if ($type == 'discussion' || $type == 'announcement')
		{
				$queryCmd = "SELECT DATEDIFF( '$fromDate', creationDate) AS datediff ,msgTxt AS title FROM messageTable WHERE threadId =? and mainAnswerId = 0";
		}
		else if ($type=='blog')
		{
				$dbHandle = $this->loadDatabaseHandle('write');
				$queryCmd = "SELECT DATEDIFF( '$fromDate', creationDate) AS datediff ,blogTitle AS title FROM blogTable WHERE blogId =?";
		}
		else if ($type=='event')
		{
				$queryCmd = "SELECT DATEDIFF( '$fromDate', creationDate) AS datediff ,event_title AS title FROM event WHERE event_id =?";
		}
		if ($type!='question'){
			if(is_numeric($id)){
				$query = $dbHandle->query($queryCmd, array($id));
				foreach ($query->result_array() as $row)
				{
				    $datediff	=	$row['datediff'];
				    $title	= $row['title'];
				}
			}
		}
		$urlFlag='false';
		if( $datediff > 0 )
		{
				$urlFlag = 'true';
		}
		$response = array($urlFlag, $title);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function getTitleFromId($request)
	{
		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		if(isset($_COOKIE['latestThreadId']) && $_COOKIE['latestThreadId']>0 && $_COOKIE['latestThreadId']==$id){
			$dbHandle = $this->loadDatabaseHandle('write');
		}
		else{
			$dbHandle = $this->loadDatabaseHandle();
		}		//In case this is a AnA Call, please check two things
		// 1. If this is discussion/announcement, we will have to get the title from the next msgId
		// 2. If this is question, then if it Old question and a title has been added to it, then we will have to get the title from the messageDiscussion table
		if ($type == 'question')	
		{
			$queryCmd = "SELECT m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 WHERE m1.msgId =?";
			$query = $dbHandle->query($queryCmd, array($id));
			$row = $query->row();
			//If this is discussion/announcement, then get the Title from the next msgId
			if($row->fromOthers == 'discussion' || $row->fromOthers == 'announcement'){
			    $queryCmd = "SELECT msgTxt AS title FROM messageTable WHERE threadId =? and mainAnswerId = 0";
			    $query = $dbHandle->query($queryCmd, array($id));
			    foreach ($query->result_array() as $row)
			    {
				$result	= $row['title'];
			    }
			}
			else{
			    if($this->check_legacy_seo_update($row->creationDate,$row->descriptionD))
				$result = $row->descriptionD;
			    else
				$result = $row->title;
			}
		}
		else if ($type=='blog')
		{
			$queryCmd = "SELECT blogTitle AS title FROM blogTable WHERE blogId =?";
			$query = $dbHandle->query($queryCmd, array($id));
			foreach ($query->result_array() as $row)
			{
			    $result = $row['title'];
			}
		}
		else if ($type=='event')
		{
			$queryCmd = "SELECT event_title AS title FROM event WHERE event_id =?";
			$query = $dbHandle->query($queryCmd, array($id));
			foreach ($query->result_array() as $row)
			{
			    $result = $row['title'];
			}
		}
		error_log("LSEO" . $queryCmd);
		$response = array('title'=>$result);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function check_legacy_seo_update($creationDate,$description){
		$start = strtotime($creationDate);
		$end = strtotime('2011-03-18');
		//If the creation date is before 18 March i.e this is a legacy question and the title is available for this question, then we will return true
		if( $end-$start > 0 && $description!='' )
		    return true;	//Depicts that we have to create the URL from the description
		else
		    return false;	//Depicts that we have to create the URL from the Title
	}

	function getEventLocationAndOthers($request){
		$parameters = $request->output_parameters();
		$event_id = $parameters['0'];
		$type = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();
        	$optionalArgs = array();
		$queryCmd = 'select ci.*, co.*,e.fromOthers from event e,event_venue_mapping evm ,event_venue v, countryCityTable ci , countryTable co where evm.event_id = ? and e.event_id = ? and evm.venue_id = v.venue_id and co.countryId = v.country  and ci.city_id = v.city ';
		error_log_shiksha("performance $queryCmd",'events');
		$query = $dbHandle->query($queryCmd, array($event_id,$event_id));
		$i  = 0;
		foreach ($query->result() as $row){
			$optionalArgs['location'][$i] = $row->city_name."-".$row->name;
			$i++;
			$optionalArgs['fromOthers'] = $row->fromOthers;
		}
		$response = array($optionalArgs);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function getTitleAndLocationForInstitute($request){
		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();

                $this->load->library('cacheLib');
                $cacheLibObj = new cacheLib();

		if($dbHandle == ''){
			log_message('error','can not create db handle');
		}
        	$optionalArgs = array();
		$queryCmd = 'select listing_title as title from listings_main where status="live" and listing_type="institute" and listing_type_id=?';
		$query = $dbHandle->query($queryCmd, array($id));
		foreach ($query->result() as $row){
			$optionalArgs['title'] = $row->title;
		}

		$queryCmd = 'SELECT city_id, country_id from institute_location_table where institute_id = ? and status ="live" order by institute_location_id asc ';
		$query = $dbHandle->query($queryCmd, array($id));
		$i  = 0;
		foreach ($query->result() as $row){
			$optionalArgs['location'][$i] = $cacheLibObj->get("city_".$row->city_id)."-".$cacheLibObj->get("country_".$row->country_id);
			$i++;
		}
		$response = array($optionalArgs);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function getTitleAndLocationForCourse($request){
                $this->load->library('cacheLib');
                $cacheLibObj = new cacheLib();

		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();
        	$optionalArgs = array();
		$query = 'select course_details.institute_id,course_details.courseTitle,institute.institute_name,ilt.* from course_details , institute, institute_location_table ilt where course_details.status = "live" and course_id in (?) and course_details.institute_id = institute.institute_id and institute.status = "live" and ilt.institute_id = course_details.institute_id and ilt.status = "live"';
		$query = $dbHandle->query($query, array($id));
		$course_details = $query->result_array();
		foreach($course_details as $row1)
		{
			$locationArrayTemp = array();
			$cityName = array($cacheLibObj->get("city_".$row1['city_id']),'string');
			$countryName = array($cacheLibObj->get("country_".$row1['country_id']),'string');
			$locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
			$optionalArgs['title'] = $row1['courseTitle'];
			$optionalArgs['location'] = $locationArrayTemp;
			$optionalArgs['institute'] = $row1['institute_name'];
		}
		$response = array($optionalArgs);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function getURLFromDB($request)
	{
		$parameters = $request->output_parameters();
		$id = $parameters['0'];
		$type = $parameters['1'];
		$dbHandle = $this->loadDatabaseHandle();
		if ($type=='blog')
		{
			$queryCmd = "SELECT url AS URL FROM blogTable WHERE blogId =?";
			$query = $dbHandle->query($queryCmd, array($id));
			foreach ($query->result_array() as $row)
			{
			    $result = $row['URL'];
			}
		}
		$response = array('URL'=>$result);
		return $this->xmlrpc->send_response(json_encode($response));
	}

	function getURLForSitemap($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$type = $parameters['1'];
		$start = $parameters['2'];
		$count = $parameters['3'];
		$noOfDays = $parameters['4'];
		$typeSitemap = $parameters['5'];
		$dbHandle = $this->loadDatabaseHandle();
		$fromDate = $this->seo_url_date_limit;
		$result = array();
		$i = 0;

		$date = date("Y-m-d");
		$date = strtotime("-".$noOfDays." days",strtotime($date));
		$date = date ( 'Y-m-j' , $date );
		switch($type){
			case 'blog':	//Simply get the URLs from the DB and return
				if($typeSitemap=='inc')
				    $queryCmd = "SELECT url AS URL FROM blogTable where creationDate >= ? and status != 'deleted' and blogType != 'news' LIMIT $start, $count";
				else
				    $queryCmd = "SELECT url AS URL FROM blogTable where creationDate < ? and status != 'deleted' and blogType != 'news' LIMIT $start, $count";
				$query = $dbHandle->query($queryCmd, array($date));
				foreach ($query->result_array() as $row)
				{
				    $result[$i] = SHIKSHA_HOME.$row['URL'];
				    $i++;
				}
				break;
			case 'saarticles':    //Simply get the URLs from the DB and return
				if($typeSitemap=='inc')
				    $queryCmd = "SELECT content_url AS URL FROM  sa_content where created_on >= ? and type in ('article','guide') and status = '".ENT_SA_PRE_LIVE_STATUS."' LIMIT $start, $count";
				else
				    $queryCmd = "SELECT content_url AS URL FROM  sa_content where created_on < ? and type in ('article','guide') and status = '".ENT_SA_PRE_LIVE_STATUS."' LIMIT $start, $count";
				$query = $dbHandle->query($queryCmd, array($date));
				foreach ($query->result_array() as $row)
				{
				    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row['URL'];
				    $i++;
				}
        break;

			case 'abroadRankingPage':
				
                                $queryCmd = "select ranking_page_id from study_abroad_ranking_pages where status = 'live'";
                                $query = $dbHandle->query($queryCmd);
                                foreach ($query->result_array() as $row)
                                {
                                    $rankingResults[] = $row['ranking_page_id'];
                                }				
				
				if(!count($rankingResults)) {
					$result = array();
				} else {
					
					$this->load->builder('RankingPageBuilder', 'abroadRanking');
					$this->rankingPageBuilder = new RankingPageBuilder;				
					$this->rankingLib 	= $this->rankingPageBuilder->getRankingLib();				
					$this->rankingPageRepository = $this->rankingPageBuilder->getRankingPageRepository($this->rankingLib);
					$rankingPageObject = $this->rankingPageRepository->find($rankingResults);
					// _p($rankingPageObject); die;
					foreach($rankingPageObject as $key => $rankingPage) {
						$result[$i] = $this->rankingLib->getRankingUrl($rankingPage);
						$i++;
					}
				}
				// _p($result); die;
				
                                break;
			
			case 'abroadExamPage':
				
				$sql = "select content_url as contentURL from sa_content where type='examContent' and status='live'";
				$resultSet = $dbHandle->query($sql)->result_array();
				$result = array();
				if(empty($resultSet)){
					break;
				}
				foreach($resultSet as $row){
					$result[] = SHIKSHA_STUDYABROAD_HOME.$row['contentURL'];
				}
				//echo "abroadexampage:: ";_p($result);die;
				break;
			
			case 'applyContent':				
				
				$date = date("Y-m-d");
				$date = strtotime("-".$noOfDays." days",strtotime($date));
				$date = date ('Y-m-j' , $date );
				$dbHandle = $this->loadDatabaseHandle('read');
				if($typeSitemap=='inc'){
				    $queryCmd = "SELECT content_url as contentURL from sa_content where type='applyContent' and status='live' and created_on >='".$date." 23:59:59'";
				    $query = $dbHandle->query($queryCmd);
				}else{
				    $queryCmd = "SELECT content_url as contentURL from sa_content where type='applyContent' and status='live'";
				    $query = $dbHandle->query($queryCmd);
				}
				//_p($dbHandle->last_query());
				foreach ($query->result_array() as $row)
				{
				    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row['contentURL'];
				    $i++;
				}
				break;

			case 'SAscholarships':				
				$date = date("Y-m-d");
				$date = strtotime("-".$noOfDays." days",strtotime($date));
				$date = date ('Y-m-j' , $date );
				$dbHandle = $this->loadDatabaseHandle('read');

				if($typeSitemap=='inc'){
				    $queryCmd = "SELECT seoUrl from scholarshipBaseTable where status='live' and modifiedAt >='".$date." 23:59:59'";
				    $query = $dbHandle->query($queryCmd);
				}else{
				    $queryCmd = "SELECT seoUrl from scholarshipBaseTable where status='live'";
				    $query = $dbHandle->query($queryCmd);
				}
				foreach ($query->result_array() as $row)
				{
				    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row['seoUrl'];
				    $i++;
				}
				if($typeSitemap=='full'){
					$this->scholarshipCategoryPageUrlLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageUrlLib');
        			$result = array_merge($result,$this->scholarshipCategoryPageUrlLib->prepareScholarsipURLsForSiteMap());
				}
				break;
			
			case 'countryHome':
			    $queryCmd = "select distinct( acpd.country_id),ct.name as name from countryTable ct join abroadCategoryPageData acpd force index for join (`ldbCourse_status`) on acpd.country_id = ct.countryId where acpd.status = 'live' and acpd.ldb_course_id IN (1508, 1509, 1510)";
				$query = $dbHandle->query($queryCmd);

				$abroadCategoryPageLib = $this->load->library('categoryList/AbroadCategoryPageLib');
				$abroadCountriesData = $abroadCategoryPageLib->getCountriesHavingUniversities();
				$lib 	= $this->load->library('countryHome/CountryHomeLib');
				$abroadCountryObject = $lib->getCountry('all');
				$result[] = $lib->getCountryHomeUrl($abroadCountryObject);
				foreach ($query->result_array() as $row)
				{
				    $abroadCountryObject = $lib->getCountry(strtolower($row['name']));
				   $result[] = $lib->getCountryHomeUrl($abroadCountryObject);
				}				

				break;
			
			case 'news':	//Simply get the URLs from the DB and return
		                $date = date("Y-m-d");
                		$date = strtotime("-2 days",strtotime($date));
		                $date = date ( 'Y-m-j' , $date );
				if($typeSitemap=='inc'){
				    $dbHandle = $this->loadDatabaseHandle('write');
				    $queryCmd = "SELECT url AS URL, blogTitle, creationDate FROM blogTable where unix_timestamp(now()) - unix_timestamp(creationDate) <= 172800 and status != 'deleted' and blogType = 'news' LIMIT $start, $count";
				    $query = $dbHandle->query($queryCmd);
				}else{
				    $queryCmd = "SELECT url AS URL, blogTitle, creationDate FROM blogTable where creationDate < ? and status != 'deleted' and blogType = 'news' LIMIT $start, $count";
				    $query = $dbHandle->query($queryCmd, array($date));
				}
				foreach ($query->result_array() as $row)
				{
				    $result[$i]['URL'] = addingDomainNameToUrl(array('url' => $row['URL'],'domainName' => SHIKSHA_HOME));
				    $result[$i]['blogTitle'] = $row['blogTitle'];
				    $result[$i]['creationDate'] = $row['creationDate'];
				    $i++;
				}
				$dbHandle = $this->loadDatabaseHandle();
				break;
			case 'abroadinstitute':	//Get the Institute URL, Institute Id, Institute Name and Institute Location from the DB
				// for abroad institutes
				if($typeSitemap=='inc') {

				    $query =  " SELECT DISTINCT lm.listing_type_id as institute_id, lm.listing_title as institute_name, lm.listing_seo_url as url FROM  `listings_main` lm 
						inner join institute i
						on(lm.listing_type_id = i.institute_id and i.status = 'live' )
						WHERE submit_date >= ? AND  lm.status = 'live' AND  lm.listing_type = 'institute' and i.institute_type = 'Department'
						LIMIT ".$start.", ".$count;

                                } else {
				    $query =  " SELECT DISTINCT lm.listing_type_id as institute_id, lm.listing_title as institute_name, lm.listing_seo_url as url FROM  `listings_main` lm 
						inner join institute i
						on(lm.listing_type_id = i.institute_id and i.status = 'live' )
						WHERE submit_date < ? AND  lm.status = 'live' AND  lm.listing_type = 'institute' and i.institute_type = 'Department'
						LIMIT ".$start.", ".$count;
							    }
				
				$queryTemp   = $dbHandle->query($query, array($date, $listingMainStatus['live']));
				$ins_details = $queryTemp->result_array();
				foreach($ins_details as $row1)
				{
					//If Institute URL is not empty or null, get the URL from DB itself
					if(!empty($row1['url'])){
					    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row1['url'];
					    $i++;
					}
				}
				break;

			case 'institute':	//Get the Institute URL, Institute Id, Institute Name and Institute Location from the DB

				// for national institutes
				$this->load->config('nationalInstitute/instituteSectionConfig');
				$listingMainStatus = $this->config->item('listingMainStatus');
				if($typeSitemap=='inc') {

						$query = "SELECT DISTINCT lm.listing_type_id as institute_id, lm.listing_seo_url as url, i.listing_type FROM  `listings_main` lm FORCE INDEX(submitDateIndex) 
							INNER JOIN shiksha_institutes i
							ON(lm.listing_type_id = i.listing_id and i.status = 'live')
							WHERE submit_date >= ? AND lm.status = ? AND  lm.listing_type = 'institute' 
							LIMIT ".$start.", ".$count;

                                } else {

						$query = "SELECT DISTINCT lm.listing_type_id as institute_id, lm.listing_seo_url as url, i.listing_type FROM  `listings_main` lm FORCE INDEX(submitDateIndex) 
							INNER JOIN shiksha_institutes i
							ON(lm.listing_type_id = i.listing_id and i.status = 'live')
							WHERE submit_date < ? AND lm.status = ? AND  lm.listing_type = 'institute' 
							LIMIT ".$start.", ".$count;

                                }
				
				$queryTemp   = $dbHandle->query($query, array($date, $listingMainStatus['live']));				
				$ins_details = $queryTemp->result_array();
				foreach($ins_details as $row1)
				{
					//If the Institute URL is empty, then create the URL using the Id, Name and Location
					if($row1['url']=='' || $row1['url']==NULL){
					    $listing_id = $row1['institute_id'];
					    $optionalArgs = array();
					    $url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type']));
					    $urlWithDomain  = addingDomainNameToUrl(array('url' => $url , 'domainName' =>SHIKSHA_HOME));
					    $result[$i] = $urlWithDomain;
					    $i++;
					}
					else{	//If Institute URL is not empty or null, get the URL from DB itself
						$urlWithDomain  = addingDomainNameToUrl(array('url' => $row1['url'] , 'domainName' =>SHIKSHA_HOME));
					    $result[$i] = $urlWithDomain;
					    $i++;
					}
				}
				// //We also need to add those institutes in the incremental sitemap for which AnA Related Question widget has been updated
				// if($typeSitemap=='inc') {
				// 	$query = "SELECT DISTINCT listing_type_id as institute_id, listing_title as institute_name, listing_seo_url as url FROM  listings_main l, institute_related_question_table r WHERE r.lastUpdatedTime >= DATE_SUB(NOW(), INTERVAL $noOfDays DAY) AND l.submit_date < ? AND  l.status =  '".$listingMainStatus['live']."' AND  l.listing_type =  'institute' and r.institute_id = l.listing_type_id LIMIT ".$start.", ".$count;
				// 	$queryTemp = $dbHandle->query($query, array($date));
				// 	$ins_details = $queryTemp->result_array();
				// 	foreach($ins_details as $row1)
				// 	{
				// 		//If the Institute URL is empty, then create the URL using the Id, Name and Location
				// 		if($row1['url']=='' || $row1['url']==NULL){
				// 		    $listing_id = $row1['institute_id'];
				// 		    $optionalArgs = array();
				// 		    $url = getSeoUrl($listing_id,'institute',$row1['institute_name'],$optionalArgs,'old');
				// 		    $result[$i] = $url;
				// 		    $i++;
				// 		}
				// 		else{	//If Institute URL is not empty or null, get the URL from DB itself
				// 		    $result[$i] = $row1['url'];
				// 		    $i++;
				// 		}
				// 	}
    //             }

				break;
			case 'all_content_pages'://get all content page urls for institute and university listing
				$this->load->config('nationalInstitute/instituteSectionConfig');
				$listingMainStatus = $this->config->item('listingMainStatus');


				//for national institutes and universities
				if($typeSitemap=='inc') {

						$query = "SELECT DISTINCT lm.listing_type_id as institute_id, sc.primary_type as listing_type, listing_seo_url as url FROM  `listings_main` lm FORCE INDEX(submitDateIndex) 
							Inner join shiksha_courses sc  
							ON(lm.listing_type_id = sc.primary_id and sc.status='live')
							WHERE submit_date >= ? AND lm.status = ? AND  lm.listing_type IN ('institute','university_national')
							LIMIT ".$start.", ".$count;

                                } else {

						$query = "SELECT DISTINCT lm.listing_type_id as institute_id, lm.listing_seo_url as url, sc.primary_type as listing_type FROM  `listings_main` lm FORCE INDEX(submitDateIndex)
							Inner join shiksha_courses sc  
							ON(lm.listing_type_id = sc.primary_id and sc.status='live')
							WHERE submit_date < ? AND lm.status = ? AND  lm.listing_type IN ('institute','university_national')
							LIMIT ".$start.", ".$count;

                                }
				
				$queryTemp   = $dbHandle->query($query, array($date, $listingMainStatus['live']));
				$ins_details = $queryTemp->result_array();
				foreach($ins_details as $row1)
				{
					//get all content page url for institute and university listings 
					$admission_url = '';
					if($row1['url']=='' || $row1['url']==NULL){
					    $listing_id = $row1['institute_id'];
					    $optionalArgs = array();
					    $all_questions_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'questions'));
					    $all_reviews_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'reviews'));
					    $all_articles_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'articles'));
					    $all_courses_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'courses'));
					    $admission_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'admission'));
					    $scholarships_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'scholarships'));
					    $cutoff_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'cutoff'));
					    $placement_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfListing'=>$row1['listing_type'],'typeOfPage'=> 'placement'));
					}
					else
					{
						$all_questions_url = $row1['url'].'/questions';
						$all_reviews_url   = $row1['url'].'/reviews';
						$all_articles_url  = $row1['url'].'/articles';
						$all_courses_url   = $row1['url'].'/courses';
					    $admission_url = $row1['url'].'/admission';
					    $scholarships_url = $row1['url'].'/scholarships';
					    $cutoff_url = $row1['url'].'/cutoff';
					    $placement_url = $row1['url'].'/placement';
					}
				    $result[$i++] = addingDomainNameToUrl(array('url' => $all_questions_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $all_reviews_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $all_articles_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $all_courses_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $scholarships_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $cutoff_url , 'domainName' =>SHIKSHA_HOME));
				    $result[$i++] = addingDomainNameToUrl(array('url' => $placement_url , 'domainName' =>SHIKSHA_HOME));
				    if(!empty($admission_url))
				    {
				    	$result[$i++] = addingDomainNameToUrl(array('url' => $admission_url , 'domainName' =>SHIKSHA_HOME));
				    }
				}
				
				// Add by Akash Gupta 

				// Add data for cutOff page URLs

				if($typeSitemap !='inc'){
					$this->CI->load->config('nationalInstitute/CollegeCutoffConfig',True);
					$collegesData = $this->CI->config->item('colleges','CollegeCutoffConfig');
					$parentListingsIdsData = $this->CI->config->item('parentListingIds','CollegeCutoffConfig');
					
					$sql = "select distinct primary_id , parent_id from shiksha_courses sc join CollegePredictor_BranchInformation cbi on (sc.course_id = cbi. shikshaCourseId)join CollegePredictor_Colleges cc on(cc.id = cbi.clmId) where cc.exams ='DU' and cc.status='live' and cbi.status='live' and sc.status='live'";
					
					$query = $dbHandle->query($sql)->result_array();
					foreach ($query as $key => $value) {
						$cutoffUrls[$value['primary_id']]=  $value['primary_id']; 
						$cutoffUrls[$value['parent_id']] =  $value['parent_id'];
					}
					foreach($parentListingsIdsData as $parentListingId){
						$cutoffUrls[$parentListingId] = $parentListingId;	
					}
					$cutoffUrls = array_keys($cutoffUrls);
					$sql = "select listing_type_id as institute_id ,listing_seo_url as url from listings_main where listing_type_id in (?) and listing_type in ('institute','university_national') and status = 'live'";
					$query = $dbHandle->query($sql,array($cutoffUrls))->result_array();
					foreach($query as $row1)
					{
						//get all content page url for institute and university listings 
						if($row1['url']=='' || $row1['url']==NULL){
						    $listing_id = $row1['institute_id'];
						    $college_cutOff_url = getSeoUrl($listing_id,'all_content_pages','',array('typeOfPage'=> 'cutoff'));
						}
						else
						{
							$college_cutOff_url = $row1['url'].'/cutoff';
						}
					    $result[$i++] = addingDomainNameToUrl(array('url' => $college_cutOff_url , 'domainName' =>SHIKSHA_HOME));
					}	
				}
				break;

			case 'course':	//Get the Course URL, Course Id, Course Name and Institute Location from the DB
				if($typeSitemap=='inc') {
				    // $query = 'select DISTINCT course_details.course_id,course_details.institute_id,course_details.courseTitle,institute.institute_name,lm.listing_seo_url as url,ilt.* from course_details , institute, institute_location_table ilt, listings_main lm , countryCityTable ci , countryTable co where lm.submit_date >= "'.$date.'" and course_details.status = "live" and course_details.institute_id = institute.institute_id and institute.status = "live" and ilt.institute_id = course_details.institute_id and ilt.status = "live" and lm.status = "live" and lm.listing_type = "course" and lm.listing_type_id = course_details.course_id and ilt.city_id = ci.city_id and ilt.country_id = co.countryId GROUP BY ilt.institute_id LIMIT '.$start.','.$count;
                    $query = 'select DISTINCT lm.listing_type_id as course_id, course_details.institute_id, course_details.courseTitle, institute.institute_name, lm.listing_seo_url as url from course_details, institute, listings_main lm where lm.submit_date >= ? and lm.listing_type = "course" and lm.listing_type_id = course_details.course_id and course_details.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") and course_details.institute_id = institute.institute_id and institute.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") and lm.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") LIMIT '.$start.','.$count;
                } else {
    				// $query = 'select DISTINCT course_details.course_id,course_details.institute_id,course_details.courseTitle,institute.institute_name,lm.listing_seo_url as url,ilt.* from course_details , institute, institute_location_table ilt, listings_main lm, countryCityTable ci , countryTable co where lm.submit_date < "'.$date.'" and course_details.status = "live" and course_details.institute_id = institute.institute_id and institute.status = "live" and ilt.institute_id = course_details.institute_id and ilt.status = "live" and lm.status = "live" and lm.listing_type = "course" and lm.listing_type_id = course_details.course_id and ilt.city_id = ci.city_id and ilt.country_id = co.countryId GROUP BY ilt.institute_id LIMIT '.$start.','.$count;
                    $query = 'select DISTINCT lm.listing_type_id as course_id, course_details.institute_id, course_details.courseTitle, institute.institute_name, lm.listing_seo_url as url from course_details, institute, listings_main lm where lm.submit_date < ? and lm.listing_type = "course" and lm.listing_type_id = course_details.course_id and course_details.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") and course_details.institute_id = institute.institute_id and institute.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") and lm.status IN ("live","'.ENT_SA_PRE_LIVE_STATUS.'") LIMIT '.$start.','.$count;
                }
  				
				$queryTemp = $dbHandle->query($query, array($date));
				$course_details = $queryTemp->result_array();
				$national_course_lib = $this->load->library('listing/NationalCourseLib');
				foreach($course_details as $row1)
				{
					//If the Course URL is empty, then create the URL using the Id, Name and Location
					if($row1['url']=='' || $row1['url']==NULL){
					    $course_id = $row1['course_id'];
					    
					    //create directory structure url, LF-3253/SEO-73
					    $dominantSubCat = $national_course_lib->getDominantSubCategoryForCourse($row1['course_id']);
					    $seoUrlCategoryName = '';
					    if(!empty($dominantSubCat['dominant'])) {
						    $this->load->builder('CategoryBuilder','categoryList');
							$categoryBuilder = new CategoryBuilder;
							$categoryRepository = $categoryBuilder->getCategoryRepository();
							$dominantSubCatObj = $categoryRepository->find($dominantSubCat['dominant']);
							$seoUrlCategoryName = $dominantSubCatObj->getSeoUrlDirectoryName();
						}

					    $optionalArgs = array();
					    $optionalArgs['institute'] = $row1['institute_name'];
					    if(!empty($seoUrlCategoryName)) {
					    	$optionalArgs['dominantSubcat'] = $seoUrlCategoryName;
					    }
					    $url = getSeoUrl($course_id,'course',$row1['courseTitle'],$optionalArgs,'old');
					    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$url;
					    $i++;
					}
					else{	//If Course URL is not empty or null, get the URL from DB itself
					    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row1['url'];
					    $i++;
					}
				}
				break;
			case 'national_course':	//Get the Course URL, Course Id, Course Name

				$this->load->config('nationalInstitute/instituteSectionConfig');
				$listingMainStatus = $this->config->item('listingMainStatus');
				if($typeSitemap=='inc') {
					$query = "SELECT DISTINCT lm.listing_type_id as course_id,lm.listing_title as course_name, ".
							 "lm.listing_seo_url as url ".
							 "FROM `listings_main` lm FORCE INDEX(submitDateIndex) ".
							 "INNER JOIN shiksha_courses c ".
							 "ON(lm.listing_type_id = c.course_id and c.status = 'live') ".
							 "WHERE submit_date >= ? AND lm.status = ? AND  lm.listing_type = 'course' ".
							 "LIMIT ".$start.", ".$count;					
				    //$query = "SELECT DISTINCT listing_type_id as course_id, listing_title as course_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` >= ? AND  `status` = '".$listingMainStatus['live']."' AND  `listing_type` = 'course' LIMIT ".$start.", ".$count;
                } else {

                	$query = "SELECT DISTINCT lm.listing_type_id as course_id,lm.listing_title as course_name, ".
							 "lm.listing_seo_url as url ".
							 "FROM `listings_main` lm FORCE INDEX(submitDateIndex) ".
							 "INNER JOIN shiksha_courses c ".
							 "ON(lm.listing_type_id = c.course_id and c.status = 'live') ".
							 "WHERE submit_date < ? AND lm.status = ? AND  lm.listing_type = 'course' ".
							 "LIMIT ".$start.", ".$count;					
                	
                    //$query = "SELECT DISTINCT listing_type_id as course_id, listing_title as course_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` < ? AND  `status` = '".$listingMainStatus['live']."' AND  `listing_type` = 'course' LIMIT ".$start.", ".$count;
                }			  
				$queryTemp = $dbHandle->query($query, array($date,$listingMainStatus['live']));
				$courseList = $queryTemp->result_array();
				foreach($courseList as $row1)
				{
					//If Course URL is not empty or null, get the URL from DB itself
					if($row1['url'] !='' && $row1['url'] != NULL){
						$urlWithDomain  = addingDomainNameToUrl(array('url' => $row1['url'] , 'domainName' =>SHIKSHA_HOME));
					    $result[$i] = $urlWithDomain;
					    $i++;
					}
				}
				break;
				case 'abroad_course':	//Get the Course URL, Course Id, Course Name	
				
				if($typeSitemap=='inc') {
					$query = "SELECT DISTINCT lm.listing_type_id as course_id, ".
							 "lm.listing_seo_url as url ".
							 "FROM `listings_main` lm ".
							 "JOIN abroadCategoryPageData ac ".
							 "ON lm.listing_type_id = ac.course_id and lm.listing_type = 'course' ".
							 "WHERE lm.submit_date >= ? AND lm.status = ? AND  ac.status = ? ".
							 "LIMIT ".$start.", ".$count;					
                } else {

                	$query = "SELECT DISTINCT lm.listing_type_id as course_id, ".
							 "lm.listing_seo_url as url ".
							 "FROM `listings_main` lm ".
							 "JOIN abroadCategoryPageData ac ".
							 "ON lm.listing_type_id = ac.course_id and lm.listing_type = 'course' ".
							 "WHERE submit_date < ? AND lm.status = ? AND  ac.status = ? ".
							 "LIMIT ".$start.", ".$count;					
                	
                }			  
				$queryTemp = $dbHandle->query($query, array($date,'live','live'));
				$courseList = $queryTemp->result_array();
				foreach($courseList as $row1)
				{
					//If Course URL is not empty or null, get the URL from DB itself
					if($row1['url'] !='' && $row1['url'] != NULL){
					    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row1['url'];
					    $i++;
					}
				}
				break;

			case 'university':	//Get the University URL, University Id, University Name
				if($typeSitemap=='inc') {
				    $query = "SELECT DISTINCT listing_type_id as university_id, listing_title as university_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` >= ? AND  `status` = '".ENT_SA_PRE_LIVE_STATUS."' AND  `listing_type` = 'university' LIMIT ".$start.", ".$count;
                                } else {
                                    $query = "SELECT DISTINCT listing_type_id as university_id, listing_title as university_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` < ? AND  `status` = '".ENT_SA_PRE_LIVE_STATUS."' AND  `listing_type` = 'university' LIMIT ".$start.", ".$count;
                                }			  
				$queryTemp = $dbHandle->query($query, array($date));
				$course_details = $queryTemp->result_array();
				foreach($course_details as $row1)
				{
					//If Course URL is not empty or null, get the URL from DB itself
					if($row1['url'] !='' && $row1['url'] != NULL){
					    $result[$i] = SHIKSHA_STUDYABROAD_HOME.$row1['url'];
					    $i++;
					}
				}
				break;

			case 'national_university':	//Get the University URL, University Id, University Name

				$this->load->config('nationalInstitute/instituteSectionConfig');
				$listingMainStatus = $this->config->item('listingMainStatus');
				if($typeSitemap=='inc') {
				    $query = "SELECT DISTINCT listing_type_id as university_id, listing_title as university_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` >= ? AND  `status` = '".$listingMainStatus['live']."' AND  `listing_type` = 'university_national' LIMIT ".$start.", ".$count;
                                } else {
                                    $query = "SELECT DISTINCT listing_type_id as university_id, listing_title as university_name, listing_seo_url as url FROM  `listings_main` WHERE `submit_date` < ? AND  `status` = '".$listingMainStatus['live']."' AND  `listing_type` = 'university_national' LIMIT ".$start.", ".$count;
                                }			  
				$queryTemp = $dbHandle->query($query, array($date));
				$course_details = $queryTemp->result_array();
				foreach($course_details as $row1)
				{
					//If Course URL is not empty or null, get the URL from DB itself
					if($row1['url'] !='' && $row1['url'] != NULL){
					    $urlWithDomain  = addingDomainNameToUrl(array('url' => $row1['url'] , 'domainName' =>SHIKSHA_HOME));
					    $result[$i] = $urlWithDomain;
					    $i++;
					}
				}
				break;
			
			case 'question':
				//First, get the data for all the questions, discussion
				if($typeSitemap=='inc') {
				    $queryCmd = "SELECT m1.msgId,DATEDIFF( '$fromDate', creationDate) AS datediff , m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 LEFT JOIN questions_listing_response as qlr on m1.msgId = qlr.messageId WHERE m1.creationDate >= ? and m1.parentId = 0 and fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and qlr.includeInSitemap = 1 and m1.listingTypeId > 0 ".
								 " UNION ".	
								 "SELECT m1.msgId,DATEDIFF( '$fromDate', creationDate) AS datediff , m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 WHERE m1.creationDate >= ? and m1.parentId = 0 and fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and m1.listingTypeId = 0 LIMIT ".$start.",".$count;
				}
				else
				    $queryCmd = "SELECT m1.msgId,DATEDIFF( '$fromDate', creationDate) AS datediff , m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 LEFT JOIN questions_listing_response as qlr on m1.msgId = qlr.messageId WHERE m1.creationDate < ? and m1.parentId = 0 and fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and qlr.includeInSitemap = 1 and m1.listingTypeId > 0 ".
								" UNION ".
								"SELECT m1.msgId,DATEDIFF( '$fromDate', creationDate) AS datediff , m1.creationDate, m1.msgTxt AS title, m1.fromOthers, ifnull((select mmd.description from messageDiscussion mmd where mmd.threadId = m1.msgId LIMIT 1),'') descriptionD FROM messageTable m1 WHERE m1.creationDate < ? and m1.parentId = 0 and fromOthers IN ('user','discussion') and m1.status IN ('live','closed') and m1.listingTypeId = 0 LIMIT ".$start.",".$count;
				
				$query = $dbHandle->query($queryCmd, array($date,$date));
				foreach ($query->result_array() as $row)
				{
				    $datediff = $row['datediff'];
				    $creationDate = $row['creationDate'];
				    $id = $row['msgId'];
				    //If this is discussion/announcement, then get the Title from the next msgId
				    if($row['fromOthers'] == 'discussion' || $row['fromOthers'] == 'announcement'){
					$queryCmdD = "SELECT msgTxt AS title FROM messageTable WHERE threadId =? and mainAnswerId = 0";
					$queryD = $dbHandle->query($queryCmdD, array($id));
					foreach ($queryD->result_array() as $rowD)
					{
					    $title = $rowD['title'];
					}
				    }
				    else{	//If this is a question,check if the title for the URL should be the msgTxt or the description of the question
					if($this->check_legacy_seo_update($creationDate,$row['descriptionD'])){
					    $title = $row['descriptionD'];
					}
					else{
					    $title = $row['title'];
					}
				    }
				    if($title!=''){
                                        $displayString = $row['fromOthers'];
                                        if($displayString == 'user')
                                                $displayString = 'question';
					
				    	$result[$i] = getSeoUrl($id, $displayString, $title, '', '', $creationDate);
				    	$i++;
				    }
				}
				break;
			case 'user':	//Get the displaynames of all the users. Then create URLs from these display names and return
				if($typeSitemap=='inc')
				    $queryCmd = "SELECT displayname FROM tuser where usercreationDate >= ? LIMIT $start, $count";
				else
				    $queryCmd = "SELECT displayname FROM tuser where usercreationDate < ? LIMIT $start, $count";
				$query = $dbHandle->query($queryCmd, array($date));
				foreach ($query->result_array() as $row)
				{
				    //If the display name is Blank or if it contains characters like *, {}, encode the name
				    if($row['displayname']!='' && strpos($row['displayname'],"\\")===false && strpos($row['displayname'],"(")===false && strpos($row['displayname'],")")===false && strpos($row['displayname'],"\"")===false && strpos($row['displayname'],"\'")===false && strpos($row['displayname'],"\n")===false){
					$result[$i] = "https://www.shiksha.com/getUserProfile/".rawurlencode($row['displayname']);
					$i++;
				    }
				}
				break;
			case 'event':	//Get the Event Id, title, creation date, from Others and location. Then create URLs from these parameters
				if($typeSitemap=='inc')
				    $queryCmd = 'select ci.*, co.*,e.event_title as title,e.event_id,e.fromOthers,e.creationDate from event e,event_venue_mapping evm ,event_venue v, countryCityTable ci , countryTable co where e.creationDate >= ? and (e.status_id is NULL) and evm.event_id = e.event_id and evm.venue_id = v.venue_id and co.countryId = v.country  and ci.city_id = v.city GROUP BY evm.event_id LIMIT '.$start.','.$count;
				else
				    $queryCmd = 'select ci.*, co.*,e.event_title as title,e.event_id,e.fromOthers,e.creationDate from event e,event_venue_mapping evm ,event_venue v, countryCityTable ci , countryTable co where e.creationDate < ? and (e.status_id is NULL) and evm.event_id = e.event_id and evm.venue_id = v.venue_id and co.countryId = v.country  and ci.city_id = v.city GROUP BY evm.event_id LIMIT '.$start.','.$count;
				error_log_shiksha("performance $queryCmd",'events');
				$query = $dbHandle->query($queryCmd, array($date));
				$j  = 0;
				foreach ($query->result() as $row){
					$optionalArgs = array();
					$optionalArgs['location'][$j] = $row->city_name."-".$row->name;
					$creationDate = $row->creationDate;
					$j++;
					$optionalArgs['fromOthers'] = $row->fromOthers;
					$event_id = $row->event_id;
					$event_title = $row->title;
					$result[$i] = getSeoUrl($event_id,'event',$event_title,$optionalArgs,'',$creationDate);
					$i++;
				}
				break;
            case 'career':
                if($typeSitemap=='inc')
                     $queryCmd = "SELECT careerId,name FROM CP_CareerTable WHERE status = 'live' AND madeLiveDate >= ? LIMIT $start, $count";
                else
                     $queryCmd = "SELECT careerId,name FROM CP_CareerTable WHERE status = 'live' AND madeLiveDate < ? LIMIT $start, $count";
                $query = $dbHandle->query($queryCmd, array($date));
                foreach ($query->result_array() as $row){
                     if($row['careerId']!='' && $row['name']!=''){
                         $result[$i] = getSeoUrl($row['careerId'],'careerproduct',$row['name']);
                         $i++;
                     }
                }
                break;
			case 'collegepredictor':
				$res['COMEDK'] = array();
				$sql = "select distinct cpc.id, cpc.collegeName, cpl.cityName, cpc.exams from CollegePredictor_Colleges cpc, CollegePredictor_LocationTable cpl where cpl.status='live' and cpl.id=cpc.locId and cpc.status='live' LIMIT $start, $count";
				$query = $dbHandle->query($sql);
				
				foreach ($query->result_array() as $row){
					if($row['exams']!='MAHCET')
					{
						if($row['id']!='' && $row['collegeName']!='' ){
							$examName[$row['exams']] = $row['exams'];
						}
					}
					else{
						$examName[$row['exams']] = $row['exams'];	
					}
				}
				foreach($examName as $key=>$value){
					if($value=='JEE-Mains' || $value=='KCET' || $value=='COMEDK' || $value=='KEAM' || $value=='WBJEE' || $value=='MPPET' || $value=='CGPET' || $value=='TNEA' || $value == 'PTU' || $value == 'UPSEE' || $value == 'MHCET' || $value == 'HSTES' || $value == 'AP-EAMCET' || $value == 'TS-EAMCET' || $value == 'OJEE' || $value == 'BITSAT' || $value == 'GGSIPU' || $value == 'GUJCET' || $value == 'NIFT' || $value == 'NCHMCT' || $value  =='MAHCET' || $value=='CLAT'){

						$res[$value][$i++] = getSeoUrl('','collegepredictor','college predictor',array('examName'=>$value,'cityName'=>''));
						if($value!='MAHCET')
						{
							$res[$value][$i++] = getSeoUrl('','collegepredictor','cut off predictor',array('examName'=>$value,'cityName'=>''));
						}
						//Removing these since these URLs are no longer required for JEE-Mains Exam
						//$res[$value][$i+3] = getSeoUrl('','collegepredictor-'.$value,'NIT',array('examName'=>$value,'cityName'=>''));
						//$res[$value][$i+4] = getSeoUrl('','collegepredictor-'.$value,'BIT',array('examName'=>$value,'cityName'=>''));
						//$res[$value][$i+5] = getSeoUrl('','collegepredictor-'.$value,'IIIT',array('examName'=>$value,'cityName'=>''));
					}
				}
				$result =array();
				foreach($examName as $key=>$value)
				{
					if(count($res[$value])>0)
					{
						$result = array_merge($result,$res[$value]);
					}
				}
				break;
		    case 'rankpredictor':
                                if($start>0){
                                        break;
                                }
                                // get the Rank Predictor URLs from Config
				$result = array();
		                $this->load->config('RP/RankPredictorConfig',TRUE);
                		$settingsRankPredictor = $this->config->item('settings','RankPredictorConfig');
				$exams = $settingsRankPredictor['RPEXAMS'];
				foreach ($exams as $exam){
					$result[] = SHIKSHA_HOME.'/b-tech/resources/'.$exam['url'];
				}
                                break;
	            case 'comparepage':
				$queryCmd = "SELECT course1_id,course1_name,course1_location,course2_id,course2_name,course2_location,course3_id,course3_name,course3_location,course4_id,course4_name,course4_location FROM Popular_Courses_Comparision where status='live' LIMIT $start, $count";
                		$query = $dbHandle->query($queryCmd);
	        	        foreach ($query->result_array() as $row){
					//Case 1: If all 4 courses are defined
					if($row['course1_id']!='' && $row['course2_id']!='' && $row['course3_id']!=0 && $row['course4_id']!=0){
                                                 if($row['course1_location']!=''){
                                                        $course1Location = "-".str_replace(' ','-',strtolower($row['course1_location']));
                                                 }
                                                 if($row['course2_location']!=''){
                                                        $course2Location = "-".str_replace(' ','-',strtolower($row['course2_location']));
                                                 }
                                                 if($row['course3_location']!=''){
                                                        $course3Location = "-".str_replace(' ','-',strtolower($row['course3_location']));
                                                 }
                                                 if($row['course4_location']!=''){
                                                        $course4Location = "-".str_replace(' ','-',strtolower($row['course4_location']));
                                                 }
	                                         $result[$i] = SHIKSHA_HOME.'/resources/college-comparison'.'-'.str_replace(' ','-',strtolower($row['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course2_name'])).$course2Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course3_name'])).$course3Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course4_name'])).$course4Location.'-'.$row['course1_id'].'-'.$row['course2_id'].'-'.$row['course3_id'].'-'.$row['course4_id'];
					}
					else if($row['course1_id']!='' && $row['course2_id']!='' && $row['course3_id']!=0){
                                                 if($row['course1_location']!=''){
                                                        $course1Location = "-".str_replace(' ','-',strtolower($row['course1_location']));
                                                 }
                                                 if($row['course2_location']!=''){
                                                        $course2Location = "-".str_replace(' ','-',strtolower($row['course2_location']));
                                                 }
                                                 if($row['course3_location']!=''){
                                                        $course3Location = "-".str_replace(' ','-',strtolower($row['course3_location']));
                                                 }
                                                 $result[$i] = SHIKSHA_HOME.'/resources/college-comparison'.'-'.str_replace(' ','-',strtolower($row['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course2_name'])).$course2Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course3_name'])).$course3Location.'-'.$row['course1_id'].'-'.$row['course2_id'].'-'.$row['course3_id'];
					}
					else if($row['course1_id']!='' && $row['course2_id']!=''){
					 	 if($row['course1_location']!=''){
							$course1Location = "-".str_replace(' ','-',strtolower($row['course1_location']));
						 }
        	                                 if($row['course2_location']!=''){
                	                                $course2Location = "-".str_replace(' ','-',strtolower($row['course2_location']));
                        	                 }
						 $result[$i] = SHIKSHA_HOME.'/resources/college-comparison'.'-'.str_replace(' ','-',strtolower($row['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($row['course2_name'])).$course2Location.'-'.$row['course1_id'].'-'.$row['course2_id'];
        	        	        }
					$i++;
                		}
		                break;
			
			case 'exampages' :
				// get the exam pages urls
				$result = $this->_getExamPagesUrls();
				break;
			case 'collegereview':
				$result = array();
				if($start>0){
					break;
				}
				$x = 0;
				//Get URLs for Homepage
				$url = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW;
				$result[$x] = $url;
				$x++;
				
				//Now, fetch the Paginated URLs for Homepage. For this, we will require the total number of Published Reviews
				global $managementStreamMR;
				global $defaultSubstream;
				global $mbaBaseCourse;
				global $fullTimeEdType;

				$queryCmd = "
					SELECT crmtsi.courseId,lm.pack_type as packType, count(distinct (crmt.id)) as RevCount 	
	    			FROM CollegeReview_MainTable crmt 
	    			JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON (crmt.id= crmtsi.reviewId)  
	    			join listings_main lm on lm.listing_type_id = crmtsi.courseId
	    			join shiksha_courses sc on sc.course_id = crmtsi.courseId
	    			join shiksha_courses_type_information scti on crmtsi.courseId = scti.course_id
	    			where 
	    				crmt.status='published' and scti.status = 'live' and scti.stream_id = ?
	    				and scti.substream_id = ? and scti.base_course = ?
	    				and lm.listing_type = 'course' and lm.status = 'live' and sc.status = 'live'
	    				and sc.education_type = ?
					GROUP BY crmtsi.courseId"
					;

				$query = $dbHandle->query($queryCmd, array($managementStreamMR, $defaultSubstream, $mbaBaseCourse,$fullTimeEdType));
				$data = $query->result_array();
				$totalReviews = $this->getCollegeReviewsByCriteria($data);
				
				$numberOfPages = ceil($totalReviews/10);
				for($i=2;$i<=$numberOfPages;$i++){
					$result[$x] = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW."/".$i;
					$x++;
				}
				
				//Now, fetch the URLs for all Tiles
				$queryCmd = 'select * from CollegeReview_Tile where status="live" and seoUrl!=""';
				$query = $dbHandle->query($queryCmd);
				foreach ($query->result() as $row){
					$urlMainTile = $row->seoUrl;
					$result[$x] = SHIKSHA_HOME.$urlMainTile;
					$x++;
					
					//Also, find the Paginated URLs for Tiles. For this, we will have to find the number of reviews in each tile
					$courseIdList = $row->courseIds;
					$courseIdList = explode(',', $courseIdList);
					$numberOfTilePages = ceil(count($courseIdList)/10);
					for($index=2;$index<=$numberOfTilePages;$index++){
						$result[$x] = SHIKSHA_HOME.$urlMainTile.'/'.$index;
						$x++;
					}
				}

				$url = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW;
				$result[$x] = $url;
				$x++;
				
				//Now, fetch the Paginated URLs for Homepage. For this, we will require the total number of Published Reviews
				global $engineeringtStreamMR;
				global $btechBaseCourse;
				$queryCmd = "
					SELECT crmtsi.courseId,lm.pack_type as packType, count(distinct (crmt.id)) as RevCount
	    			FROM CollegeReview_MainTable crmt 
	    			JOIN CollegeReview_MappingToShikshaInstitute crmtsi ON (crmt.id= crmtsi.reviewId)  
	    			join listings_main lm on lm.listing_type_id = crmtsi.courseId
	    			join shiksha_courses sc on sc.course_id = crmtsi.courseId
	    			join shiksha_courses_type_information scti on crmtsi.courseId = scti.course_id
	    			where 
	    				crmt.status='published' and scti.status = 'live' and scti.stream_id =?
	    				and scti.substream_id = ? and scti.base_course = ?
	    				and lm.listing_type = 'course' and lm.status = 'live' and sc.status = 'live'
	    				and sc.education_type = ?
					GROUP BY crmtsi.courseId";

				$query = $dbHandle->query($queryCmd, array($engineeringtStreamMR, $defaultSubstream, $btechBaseCourse,$fullTimeEdType));
				$data = $query->result_array();
				$totalReviews = $this->getCollegeReviewsByCriteria($data);

				$numberOfPages = ceil($totalReviews/10);
				for($i=2;$i<=$numberOfPages;$i++){
					$result[$x] = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW."/".$i;
					$x++;
				}
				break;
			case 'careercompass' :
				if($start>0){
					break;
				}
				// get the career compass pages urls
				$result = array(
						SHIKSHA_HOME.'/mba/resources/best-mba-sales-colleges-based-on-mba-alumni-data',
						SHIKSHA_HOME.'/mba/resources/best-mba-banking-colleges-based-on-mba-alumni-data',
						SHIKSHA_HOME.'/mba/resources/best-mba-finance-colleges-based-on-mba-alumni-data',
						SHIKSHA_HOME.'/mba/resources/best-mba-marketing-colleges-based-on-mba-alumni-data',
						SHIKSHA_HOME.'/mba/resources/best-mba-hr-colleges-based-on-mba-alumni-data',
						SHIKSHA_HOME.'/mba/resources/best-mba-it-colleges-based-on-mba-alumni-data'
						);
				break;
			case 'campusconnect' :
				$result = array();
                                if($start>0){
                                        break;
                                }
                                $CcBaseLib = $this->load->library("CA/CcBaseLib");
                                $campusconnectdata = $CcBaseLib->getProgramIdMappingDetails('all');
                                foreach ($campusconnectdata as $key=>$value){
                                        $result[] = $value['url'];
                                }
                                break;

		}
		$mainArr  = array();
		if($type=='news'){
			foreach($result as $key=>$value){
				$mainArr['URL'][] = $value['URL'].'::::'.seo_title_for_news($value['blogTitle']).'::::'.date('Y-m-d',(strtotime($value['creationDate'])));
			}
		}else{
		$mainArr['URL'] = $result;
		}
		$responseString = base64_encode(gzcompress(json_encode($mainArr)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	

	 function getCollegeReviewsByCriteria($reviewsData,$countCriteria4PaidCourse = 3,$countCriteria4FreeCourse = 1){

	 	$i = 0;
		foreach($reviewsData as $key=>$value )
		{	
			if($value['packType'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $value['packType'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $value['packType'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID){
				
				// case for paid course
				if($value['RevCount'] >= $countCriteria4PaidCourse)
				{	
					$i++;
				}
			} else {
				// case for free course
				if($value['RevCount'] >= $countCriteria4FreeCourse)
				{
					$i++;
					
				}
			}
			
		}

		return $i;
	 }
	 





	/** 
	* Purpose : Get all Exam pages URLs(homepage, syllabus, important dates)
	* Params  : none
	* Author  : Romil Goel
	* Note 	  : Add the section name identifier to $pageSections array to enable inclusion of a new section URL
	*/
	function _getExamPagesUrls()
	{
		// load resources
		$this->load->library("examPages/ExamPageRequest");
		$examPageRequest = new ExamPageRequest();
		$dbHandle = $this->loadDatabaseHandle();
		
		// get the exam names that have exampage in live status
		$queryCmd	= "SELECT distinct em.id as examId FROM exampage_main em where em.status='live' order by em.id asc"; 
		$query 		= $dbHandle->query($queryCmd);
		$results 	= $query->result_array();		
		
		//$examPageRequest->setCategoryName("Engineering");
		$urls 		= array();
		//$pageSections 	= array("home", "syllabus", "imp_dates");
		
		// get the urls

		$this->load->builder('ExamBuilder','examPages');
    	$examBuilder          = new ExamBuilder();
      	$examRepository = $examBuilder->getExamRepository();

		foreach($results as $exam)
		{
			if(!empty($exam['examId']))
			{
				$examObject = $examRepository->find($exam['examId']);
				if(!empty($examObject))
				{
					$examName = $examObject->getName();

					$examPageRequest->setExamName($examName);

					$primaryGroup   = $examObject->getPrimaryGroup();	
					$primaryGroupId = $primaryGroup['id'];

					if(empty($primaryGroupId))
					{
						continue;
					}

					$examContent    = $examRepository->findContent($primaryGroupId, 'all');

					if(empty($examContent))
					{
						continue;
					}
					$sections = $examContent['sectionname'];
					foreach ($sections as $secKey => $secVal) {
						$urls[] = $examPageRequest->getUrl($secVal,true);
					}
				}
			}
		}

		return $urls;
	}

}
