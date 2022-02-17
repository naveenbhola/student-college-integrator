<?php
exit();
class Network_server extends MX_Controller
{
	function index()
	{

		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('user/userconfig');
		$this->load->library('user/schoolconfig'); 
		$this->load->helper('url'); 
		$this->dbLibObj = DbLibCommon::getInstance('Misc');

		$config['functions']['collegenetwork'] = array('function' => 'network_server.collegenetwork');
		$config['functions']['showSchools'] = array('function' => 'network_server.showSchools');
		$config['functions']['totalCollegesandUsers'] = array('function' => 'Network_server.totalCollegesandUsers');
		$config['functions']['totalUsersCollegesinUserNet'] = array('function' => 'Network_server.totalUsersCollegesinUserNet');
		$config['functions']['showUserNetworkList'] = array('function' => 'network_server.showUserNetworkList');
		$config['functions']['getAlertContent'] = array('function' => 'network_server.getAlertContent');
		$config['functions']['getCollegesByCategory'] = array('function' => 'network_server.getCollegesByCategory');
		$config['functions']['deleteUserFromNetwork'] = array('function' => 'network_server.deleteUserFromNetwork');
		$config['functions']['showCollegeNetworkList'] = array('function' => 'network_server.showCollegeNetworkList');
		$config['functions']['getCollegeCount'] = array('function' => 'network_server.getCollegeCount');
		$config['functions']['getSchoolCount'] = array('function' => 'network_server.getSchoolCount');
		$config['functions']['showRequestResponse'] = array('function' => 'network_server.showRequestResponse');
		$config['functions']['addUserRequest'] = array('function' => 'network_server.addUserRequest');
		$config['functions']['addtoNetwork'] = array('function' => 'network_server.addtoNetwork');
		$config['functions']['addtoSchoolNetwork'] = array('function' => 'network_server.addtoSchoolNetwork');
		$config['functions']['showNewRequests'] = array('function' => 'network_server.showNewRequests');
		$config['functions']['getNetworkForHomePage'] = array('function' => 'network_server.getNetworkForHomePage');
		$config['functions']['getCollegeNetworkCount'] = array('function' => 'network_server.getCollegeNetworkCount');
		$config['functions']['showuserCollegeNetwork'] = array('function' => 'network_server.showuserCollegeNetwork');
		$config['functions']['showuserSchoolNetwork'] = array('function' => 'network_server.showuserSchoolNetwork');
		$config['functions']['getThreadId'] = array('function' => 'network_server.getThreadId');
		$config['functions']['getThreadIdforSchool'] = array('function' => 'network_server.getThreadIdforSchool');
		$config['functions']['showSchoolMembersCount'] = array('function' => 'network_server.showSchoolMembersCount');
		$config['functions']['getcitiesbyCountry'] = array('function' => 'network_server.getcitiesbyCountry');
		$config['functions']['getBoardChilds'] = array('function' => 'network_server.getBoardChilds');
		$config['functions']['getNoofRequestsforUser'] = array('function' => 'network_server.getNoofRequestsforUser');
		$config['functions']['checkifmember'] = array('function' => 'network_server.checkifmember');
		$config['functions']['sgetSchoolsForIndex'] = array('function' => 'network_server.sgetSchoolsForIndex');
		$config['functions']['sgetTestPrepGroupForIndex'] = array('function' => 'network_server.sgetTestPrepGroupForIndex');
		$config['functions']['sgetMemberCountForSearch'] = array('function' => 'network_server.sgetMemberCountForSearch');
		$config['functions']['sgetRecentlyAddedMembers'] = array('function' => 'network_server.sgetRecentlyAddedMembers');
		$config['functions']['getCategoryIdforCategoryName'] = array('function' => 'network_server.getCategoryIdforCategoryName');
		$config['functions']['updateStatistics'] = array('function' => 'network_server.updateStatistics');
		$config['functions']['updatealertstatus'] = array('function' => 'network_server.updatealertstatus');
		$config['functions']['insertgroupnews'] = array('function' => 'network_server.insertgroupnews');
		$config['functions']['getgroupnews'] = array('function' => 'network_server.getgroupnews');
		$config['functions']['getTestPrepForCategoryPage'] = array('function' => 'network_server.getTestPrepForCategoryPage');
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	function deleteUserFromNetwork($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];
		$collegeId = $parameters['2'];
		$grouptype = $parameters['3'];
		$deleteRes = '';
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
        if($grouptype == "testprep")
            $grouptype = "TestPreparation";
        if($grouptype == "collegegroup")
            $grouptype = "group";
        if($grouptype == "schoolgroup")
        {
            $query = "delete from tschoolNetwork where schoolId = ? and userId = ?";
            $result = $dbHandle->query($query, array($collegeId,$userId));
        }
        else
        {
            $query = "delete from tcollegeNetwork where collegeId = ? and userId = ? and grouptype = ?";
            $result = $dbHandle->query($query, array($collegeId,$userId,$grouptype));
        }
		error_log_shiksha("Result of college Delete is ::".$result);
		if($result)
		{
			if($grouptype != "schoolgroup")
            {
			$this->load->library('network/Network_client');
			$NetworkClient = new Network_client();
			$response = $NetworkClient->updateStatistics('membercount',$collegeId,-1,'group');
		}
			$deleteRes = 'deleted';
			$response = array(
					array(
						'Result'=>array($deleteRes)),
					'struct');
		}
		else
		{
			$deleteRes = 'Unable to delete';
			$response = array(
					array(
						'Result'=>array($deleteRes)),
					'struct');
		}
		return $this->xmlrpc->send_response($response);
	}

	function getCollegesByCategory($request){


		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$category = $parameters['1'];
		if(!is_numeric($category))
			$parentId = $this->getCategoryIdforCategoryName($category);
		//$parentId = $category;
		error_log_shiksha($parentId);
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select categoryId,membercount,collegecount from tcategorygroupStatistics ";

		if(!is_numeric($category))
			$queryCmd .= " where categoryId in( ".$parentId .")";
		$query = $dbHandle->query($queryCmd);
		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function getCategoryIdforCategoryName($categoryName)
	{

		$dbHandle = $this->_loadDatabaseHandle();
		/*		$queryCmd = ' select boardId from categoryBoardTable where name ="'.$categoryName .'"' ;
				error_log_shiksha('QUERY'.$queryCmd);
				$query = $dbHandle->query($queryCmd);
				$row = $query->row();
		//if($categoryName == "")
		 */
		if($categoryName == 'Medical' )
		{
			return '5';
		}

		if($categoryName == 'Engineering & Computers' )
		{
			return '2,10';
		}
		if($categoryName == 'Management' )
		{
			return '3,4,11';
		}
		if($categoryName == 'Hospitality' )
		{
			return '6';
		}
		if($categoryName == 'Media & Entertainment' )
		{
			return '7,12';
		}
		if($categoryName == 'Arts & Humanities' )
		{
			return '9';
		}
		if($categoryName == 'Miscellaneous')
		{
			return '149';
		}
		if($categoryName == 'Study Abroad')
		{
			return '1';
		}
		if($categoryName == 'TestPreparation')
		{
			return '-1';
		}
		//		return $row->boardId;

	}

	function collegenetwork($request){
                $this->load->library('cacheLib');
                $cacheLibObj = new cacheLib();

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$alphabet = $parameters['1'];
		$country = $parameters['2'];
		$startfrom = $parameters['3'];
		$count = $parameters['4'];
		$city = $parameters['5'];
		$category = $parameters['6'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();



		$parentId = -1;
		if($category != "TestPreparation")
		{
			$parentId = $this->getCategoryIdforCategoryName($category);
			if($parentId != 1)
			{
				$queryCmd = "select boardId from categoryBoardTable where parentId in(".$parentId.")";
				$query = $dbHandle->query($queryCmd);
				$categories = '';
				foreach ($query->result_array() as $row){
					if($categories == '')
						$categories = $row['boardId'];
					else
						$categories .= ','.$row['boardId'];
				}

				$queryCmd2 = " select SQL_CALC_FOUND_ROWS distinct(a.institute_id) as institute, trim(a.institute_name) as institutename, a.logo_link as collegeurl from institute a, listing_category_table b, institute_location_table ilt where b.listing_type='institute' and a.institute_id = ilt.institute_id and a.institute_id = b.listing_type_id and ilt.country_id = ".$country." and b.category_id in (".$categories.") and a.status = 'live' and ilt.status = 'live'";
				$queryCmd = "select institute, institutename, collegeurl, members, city_id from institute_location_table,( select distinct(a.institute_id) as institute,trim(a.institute_name) as institutename, a.logo_link as collegeurl,count(distinct(tcn.mappingId)) as members  from institute a LEFT JOIN tcollegeNetwork tcn on a.institute_id = tcn.collegeId and tcn.grouptype = 'group' INNER JOIN listing_category_table b  ON  a.institute_id = b.listing_type_id INNER JOIN institute_location_table ilt ON a.institute_id = ilt.institute_id where b.listing_type='institute' and  ilt.country_id = ".$country." and b.category_id in (".$categories.") and a.status = 'live' and ilt.status = 'live'"; 
			}
			else
			{
				$queryCmd2 = "select SQL_CALC_FOUND_ROWS distinct(a.institute_id) as institute,trim(a.institute_name) as institutename,a.logo_link as collegeurl from institute a INNER JOIN institute_location_table ilt On(ilt.institute_id = a.institute_id) where a.status = 'live' and ilt.status = 'live' and ilt.country_Id = ".$country ." and ilt.country_Id <> 2";
				$queryCmd = "select institute, institutename, collegeurl, members, city_id from institute_location_table,( select distinct(a.institute_id) as institute,trim(a.institute_name) as institutename, a.logo_link as collegeurl,count(distinct(tcn.mappingId)) as members  from institute a LEFT JOIN tcollegeNetwork tcn on a.institute_id = tcn.collegeId and tcn.grouptype = 'group' INNER JOIN institute_location_table ilt ON a.institute_id = ilt.institute_id where ilt.country_id = ".$country." and a.status = 'live' and ilt.status = 'live'"; 

			}

			if($alphabet != 'All')
			{
				$queryCmd .= ' and trim(a.institute_name) like \''.$alphabet.'%\'';
				$queryCmd2 .= ' and trim(a.institute_name) like \''.$alphabet.'%\'';
			}
			if($city != "All")
			{
				$queryCmd .= ' and ilt.city_id = '.$city;
				$queryCmd2 .= ' and ilt.city_id = '.$city;
			}
			$queryCmd .= ' group by a.institute_id  order by trim(a.institute_name) asc limit '.$startfrom.','.$count.')t where t.institute = institute_location_table.institute_id and institute_location_table.status = "live"';
			$queryCmd2 .= ' group by a.institute_id  order by trim(a.institute_name) asc limit '.$startfrom.','.$count;
			error_log_shiksha('QUERY'.$queryCmd);
			log_message('debug','getUser Details query cmd is ' . $queryCmd);

			$query = $dbHandle->query($queryCmd);
			$query2 = $dbHandle->query($queryCmd2);

			$queryCmd1 = "select FOUND_ROWS() as totalRows";
			$query1 = $dbHandle->query($queryCmd1);
			$row1 = $query1->row();
			$totalRows = $row1->totalRows;	
			$msgArray = array();
			$cities = 0;
			$i = 0;
			$results_Array = $query->result_array();
			$cityName = 0;
			$results_Array = $query->result_array();
			error_log_shiksha($totalRows);
			foreach ($query->result_array() as $row){
				$nextrow = next($results_Array);
				$nextcollegeId = $nextrow['institute'];
				$collegeId = $row['institute'];
				if(is_numeric($cityName))
					$cityName = $cacheLibObj->get("city_".$row['city_id']);
				else
					$cityName = $cityName . ',' . $cacheLibObj->get("city_".$row['city_id']);
				if($row['institute'] != $nextcollegeId)
				{
					$url = getSeoUrl($row['institute'],"collegegroup",$row['institutename']);
					array_push($msgArray,array(
								array(
									'institute'=>array($row['institute'],'int'),
									'cityName'=>array($cityName,'string'),
									'country'=>array($cacheLibObj->get("country_".$country),'string'),
									'countryId'=>array($country,'int'),
									'collegeurl'=>array($row['collegeurl'],'string'),
									'url'=>array($url,'string'),
									'count'=>array($row['members'],'string'),
									'totalRows'=>array($totalRows,'string'),
									'institutename'=>array($row['institutename'],'string')
								     ),'struct'));
					$cityName = 0;

				}

			}
		}
		else
		{

            //			$queryCmd2 = 'select SQL_CALC_FOUND_ROWS a.blogId as institute,trim(a.blogTitle) as institutename,a.blogImageURL as collegeurl ,count(b.mappingId) as members from (select blogId,blogTitle,acronym,parentId,blogImageURL from blogTable where parentId in (select blogId from blogTable where blogType="exam" and parentId = 0 and status <> "deleted") or parentId = 0 and blogType="exam" and status <> "deleted")a LEFT JOIN tcollegeNetwork b on a.blogId = b.collegeId and b.grouptype = "TestPreparation"';
            $queryCmd2 = 'select SQL_CALC_FOUND_ROWS a.blogId as institute,trim(a.blogTitle) as institutename,a.blogImageURL as collegeurl,count(b.mappingId) as members from (select blogId,blogTitle,acronym,parentId,blogImageURL from blogTable where blogType = "exam" and status <> "deleted" and (parentId in (select blogId from blogTable where blogType="exam" and parentId = 0 and status <> "deleted") or parentId = 0) )a LEFT JOIN tcollegeNetwork b on a.blogId = b.collegeId and b.grouptype = "TestPreparation"';


			if($alphabet != 'All')
			{
				$queryCmd2 .= ' where trim(a.blogTitle) like \''.$alphabet.'%\'';
			}
			$queryCmd2 .= ' group by a.blogId  order by trim(a.blogTitle) asc limit '.$startfrom.','.$count;
			error_log($queryCmd2);	
			$query = $dbHandle->query($queryCmd2);

			$queryCmd1 = "select FOUND_ROWS() as totalRows";
			$query1 = $dbHandle->query($queryCmd1);
			$row1 = $query1->row();
			$totalRows = $row1->totalRows;	
			$msgArray = array();
			foreach ($query->result_array() as $row){
				$collegeId = $row['institute'];
				$url = getSeoUrl($row['institute'],"collegegroup",$row['institutename']);
				array_push($msgArray,array(
							array(
								'institute'=>array($row['institute'],'int'),
								'collegeurl'=>array($row['collegeurl'],'string'),
								'url'=>array($url,'string'),
								'count'=>array($row['members'],'string'),
								'totalRows'=>array($totalRows,'string'),
								'institutename'=>array($row['institutename'],'string')
							     ),'struct'));

			}

		}


		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}


	function sgetMemberCountForSearch($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$schoolIdsarray=$parameters['1'];
		$collegeIdsarray=$parameters['2'];
		$testgrpIdsarray=$parameters['3'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$response = array();
		$msgArray = array();
		$msgArray1 = array();
		$msgArray2 = array();

		if(count($collegeIdsarray) != 0)
		{
			$i = 0;
			$collegeCount = 0;
			while($i < count($collegeIdsarray))	
			{
				if($i == 0)
					$collegeIds = $collegeIdsarray[$i];
				else
					$collegeIds .= "," . $collegeIdsarray[$i];
				$i += 1;
			}
			$queryCmd = "select institute_id as collegeId,count(userId) as count from tcollegeNetwork cn RIGHT OUTER JOIN institute i On(i.institute_id = cn.collegeId) where institute_id in(".$collegeIds.") and i.status = 'live' and cn.grouptype = 'group' group by institute_id";
			error_log_shiksha($queryCmd);
			$query = $dbHandle->query($queryCmd);
			foreach ($query->result_array() as $row){		
				array_push($msgArray1,array(
							array(
								'typeId'=>array($row['collegeId'],'int'),
								'membercount'=>array($row['count'],'int')
							     ),'struct'));	
				$collegeCount += 1;
			}
			array_push($response,array(array('collegegroups'=>array($msgArray1,'struct')),'struct'));
		}

		if(count($testgrpIdsarray) != 0)
		{
			$i = 0;
			$collegeCount = 0;
			while($i < count($testgrpIdsarray))	
			{
				if($i == 0)
					$collegeIds = $testgrpIdsarray[$i];
				else
					$collegeIds .= "," . $testgrpIdsarray[$i];
				$i += 1;
			}
			$queryCmd = "select blogId as collegeId,count(cn.userId) as count from tcollegeNetwork cn RIGHT OUTER JOIN blogTable i On(i.blogid = cn.collegeId) where blogId in(".$collegeIds.") and cn.grouptype = 'TestPreparation' group by blogId";
			error_log_shiksha($queryCmd);
			$query = $dbHandle->query($queryCmd);
			foreach ($query->result_array() as $row){		
				array_push($msgArray2,array(
							array(
								'typeId'=>array($row['collegeId'],'int'),
								'membercount'=>array($row['count'],'int')
							     ),'struct'));	
				$collegeCount += 1;
			}
			array_push($response,array(array('examgroup'=>array($msgArray2,'struct')),'struct'));
		}


		if(count($schoolIdsarray)!= 0)
		{

			$i = 0;
			$schoolCount = 0;
			while($i < count($schoolIdsarray))	
			{
				if($i == 0)
					$schoolIds = $schoolIdsarray[$i];
				else
					$schoolIds .= "," . $schoolIdsarray[$i];
				$i += 1;
			}
			$queryCmd = "select nw.schoolId,count(userId) as count from tschoolNetwork sn RIGHT OUTER JOIN NW_SCHOOLLIST nw ON(nw.schoolId = sn.schoolId) where nw.schoolId in(".$schoolIds.") group by nw.schoolId";
			error_log_shiksha($queryCmd);
			$query = $dbHandle->query($queryCmd);
			foreach ($query->result_array() as $row){	
				/*			    $response['schoolgroups'][$schoolCount]['typeId'] = $row['schoolId'];
							    $response['schoolgroups'][$schoolCount]['membercount'] = $row['count'];*/
				array_push($msgArray,array(
							array(
								'typeId'=>array($row['schoolId'],'int'),
								'membercount'=>array($row['count'],'int')
							     ),'struct'));	
				$schoolCount += 1;
			}
			array_push($response,array(array('schoolgroups'=>array($msgArray,'struct')),'struct'));
		}
		$responseArray = array($response,'struct');
		return $this->xmlrpc->send_response($responseArray);	

	}

	function showSchools($request){


		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$alphabet = $parameters['1'];
		$city = $parameters['2'];
		$startfrom = $parameters['3'];
		$count = $parameters['4'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select sl.SchoolId,sl.SCHOOL,(select count(userid) from tschoolNetwork where sl.schoolid=schoolId) as count from NW_SCHOOLLIST sl INNER JOIN  countryCityTable nc ON(nc.city_id = sl.cityid) where 1=1";

		if($alphabet != 'All')
		{
			$queryCmd .= ' and sl.school like \''.$alphabet.'%\'';
		}

		if($city != 'All')
		{
			$queryCmd .= ' and sl.cityid = \''.$city.'\'';
		}
		//        $queryCmd .= ' group by sl.school';
		$queryCmd .= ' order by SCHOOL limit '.$startfrom.','.$count;


		error_log_shiksha('QUERY'.$queryCmd);
		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){		
			$row['url'] = getSeoUrl($row['SchoolId'],"schoolgroups",$row['SCHOOL']);
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');



		return $this->xmlrpc->send_response($response);	
	}

	function getBoardChilds($categoryId){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$categoryIdArray = array();
		$categoryIdString='';

		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
			'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
			'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
			'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = \''.$categoryId.'\'';

		error_log_shiksha($queryCmd);
		log_message('debug', 'get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$categoryIdArray) && !empty($row->lev1)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev1]=$row->lev1;
				$categoryIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$categoryIdArray) && !empty($row->lev2)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev2]=$row->lev2;
				$categoryIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$categoryIdArray) && !empty($row->lev3)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev3]=$row->lev3;
				$categoryIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$categoryIdArray) && !empty($row->lev4)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev4]=$row->lev4;
				$categoryIdString .= $row->lev4;

			}
		}
		if(strlen($categoryIdString)==0){
			$categoryIdString .= $categoryId;
		}
		return $categoryIdString;
	}


	function getTestPrepForCategoryPage($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$examId = $parameters['1'];

		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select b.blogId,b.blogTitle,count(distinct a.mappingId) as members,count(distinct c.msgId) as messages from blogTable b left join tcollegeNetwork a on (a.collegeId = b.blogId and a.grouptype = 'TestPreparation') left join messageTable c on (b.blogId = c.listingtypeId and c.fromOthers = 'TestPreparation' and c.listingType = 'TestPreparation') where b.parentId = $examId and b.status='live' and c.status <> 'deleted' group by b.blogId";
		error_log($queryCmd);
		$msgArray = array();
		$response = array();
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $row){		
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	

	}


	function getNetworkForHomePage($request)
	{
		error_log_shiksha('here');
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$categoryId = $parameters['1'];
		$countryId = $parameters['2'];		
		$city = $parameters['3'];
		$startfrom = $parameters['4'];
		$count = $parameters['5'];
		$key = $parameters['6'];
		error_log_shiksha($categoryId.'CATEGORY');
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($categoryId != 1)
			$categoryId = $this->getBoardChilds($categoryId);
		error_log_shiksha($categoryId);


		$queryCmd = 'select i.institute_id as college,i.logo_link as imageurl,i.institute_name as name, (select count(userid) from tcollegeNetwork cn where cn.collegeId = college and grouptype = "group") as noofusers,true as isSponsored from PageNetworkDb pdb INNER JOIN institute i ON(pdb.CollegeId = i.institute_id)'; 
		if(($countryId != 1 && $countryId != '') || ($city != 1 && $city != ''))
			$queryCmd .= " inner JOIN institute_location_table ilot ON(ilot.institute_id = i.institute_id)";
		if($city != 1 && $city != '')
			$queryCmd .=' inner join virtualCityMapping vcp on (vcp.city_id = ilot.city_id and vcp.virtualCityId in ('.$city.'))';
		$queryCmd .= " where CURDATE() between StartDate and EndDate and i.status = 'live' and ilot.status = 'live' and keyId = ".$key ;
		if($countryId != 1 && $countryId != '')
			$queryCmd .=' and ilot.country_Id in ('.$countryId.')';
					$queryCmd .=' group by i.institute_id order by noofusers desc limit '.$startfrom .','.$count ;
					error_log_shiksha('QUERY'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					error_log_shiksha($query->num_rows());
					$msgArray = array();
					$noofrows = $query->num_rows();

					if($noofrows > 0)
					{
					foreach ($query->result_array() as $row){		
					$row['url'] = getSeoUrl($row['college'],"collegegroup",$row['name']);
					array_push($msgArray,array($row,'struct'));

					}
					}

					$newCount = $count - $noofrows;

					$queryCmd = 'select SQL_CALC_FOUND_ROWS i.institute_id as college,i.logo_link as imageurl,i.institute_name as name, (select count(userid) from tcollegeNetwork cn where cn.collegeId = college and grouptype = "group") as noofusers from listings_main lm INNER JOIN institute i ON(lm.listing_type_id = i.institute_id)'; 
					if($categoryId != 1)
						$queryCmd .= " INNER JOIN listing_category_table ic ON (ic.listing_type_id = i.institute_id and ic.listing_type='institute') ";
					if(($countryId != 1 && $countryId != '') || ($city != 1 && $city != ''))
						$queryCmd .= " inner JOIN institute_location_table ilot on(ilot.institute_id = i.institute_id) ";
					if($city != 1 && $city != '')
						$queryCmd .=' inner join virtualCityMapping vcp on (vcp.city_id = ilot.city_id and vcp.virtualCityId in ('.$city.'))';
					$queryCmd .= ' where lm.listing_type="institute" and i.status = "live" and ilot.status = "live" and lm.status = "live"';

					if($key == 3)
					{
						$queryCmd  .= ' and lm.tags like "%Exam Preparation%"';
					}
					if($countryId != 1 && $countryId != '')
						$queryCmd .= ' and ilot.country_Id in ('.$countryId .')' ;
								if($categoryId != 1)
								$queryCmd .= " and ic.category_id in (".$categoryId.")";
								$queryCmd .= ' group by i.institute_id order by noofusers desc limit '.$startfrom.','.$newCount;

								error_log_shiksha('QUERY'.$queryCmd);
								log_message('debug','getUser Details query cmd is ' . $queryCmd);

								$query = $dbHandle->query($queryCmd);
								foreach ($query->result_array() as $row){		
								$row['url'] = getSeoUrl($row['college'],"collegegroup",$row['name']);
								$row['url'] = $row['url'] .'/0';
								array_push($msgArray,array($row,'struct'));
								}
								$sqlQuery = "select FOUND_ROWS() as totalRows";
								$query1 = $dbHandle->query($sqlQuery);
								$row1 = $query1->row();
								$totalRows = $row1->totalRows;
								$response = array();
								array_push($response,array(
										'results'=>array($msgArray,'struct'),
										'totalCount'=>array($totalRows,'string')
										),'struct');

								return $this->xmlrpc->send_response($response);	
	}


	function getSchoolCount($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$alphabet = $parameters['1'];
		$country = $parameters['2'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select count(sl.SchoolId) as count from NW_SCHOOLLIST sl INNER JOIN countryCityTable ci ON(ci.city_id = sl.cityid) where 1=1';
		if($alphabet != 'All')
		{
			$queryCmd .= ' and sl.SCHOOL like \''.$alphabet.'%\'';
		}
		if($country != 'All')
		{
			$queryCmd .= ' and ci.city_id = \''.$country.'\'';

		}

		error_log_shiksha('COUNT'.$queryCmd);

		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$row = $query->row();
		error_log_shiksha('COUNT'.$row->count);
		$response = $row->count;
		return $this->xmlrpc->send_response($response);	
	}


	function getNewUserContent($appID,$todaysdate,$diffdate,$institute)
	{
		$dbHandle = $this->_loadDatabaseHandle();
		if($institute == 1)
		{
            //			$queryCmd = "select a.institute_name as collegename,b.displayname as username,a.institute_id as collegeId,b.userId as userid from institute a INNER JOIN (select collegeId,userId from tcollegeNetwork where joiningDate between (now() - INTERVAL ".$diffdate." day) and now() group by collegeId,userid)c on a.institute_id = c.collegeId INNER JOIN tuser b On c.userId = b.userId where a.status <> 'deleted' order by a.institute_id asc";
            $queryCmd = "select a.institute_name as collegename,b.displayname as username,a.institute_id as collegeId,b.userId as userid,'group' as grouptype from institute a INNER JOIN (select collegeId,userId,grouptype from tcollegeNetwork where grouptype = 'group' and joiningDate between (now() - INTERVAL ".$diffdate." day) and now() group by collegeId,userid)c on a.institute_id = c.collegeId INNER JOIN tuser b On c.userId = b.userId where a.status = 'live' UNION select a.blogTitle,b.displayname as username,a.blogId as collegeId,b.userId,'TestPreparation' as grouptype from blogTable a inner join (select collegeId,userId,grouptype from tcollegeNetwork where grouptype = 'TestPreparation' and joiningDate between (now() - INTERVAL ".$diffdate." day) and now() group by collegeId,userid)c on a.blogId = c.collegeId inner join tuser b on c.userId = b.userId where a.status <> 'deleted' order by collegeId";			
		}
		else
		{
			$queryCmd = "select a.school as collegename,b.displayname as username,a.schoolId as collegeId,b.userId as userid from NW_SCHOOLLIST a INNER JOIN (select schoolId,userId from tschoolNetwork where joiningDate between (now() - INTERVAL ".$diffdate." day) and now() group by schoolId,userid)c on a.schoolid = c.schoolId INNER JOIN tuser b On c.userId = b.userId order by a.schoolId asc";
		}

		error_log_shiksha('COUNT'.$queryCmd);

		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$collegeId = -1;
		$userIds = 0;
		$results_Array = $query->result_array();
		foreach ($query->result_array() as $row){
			$nextrow = next($results_Array);
			$nextcollegeId = $nextrow['collegeId'];
			$nextgroup = $nextrow['grouptype'];
			$collegeId = $row['collegeId'];
			$group = $row['grouptype'];
			if($userIds == 0)
			{
				$userIds = $row['userid'];
				$userNames = $row['username'];
				$count = 1;
			}
			else
			{
				$userIds = $userIds . "," .$row['userid'];
				$userNames = $userNames . "," .$row['username'];
				$count = $count + 1;
			}
			if($row['collegeId'] != $nextcollegeId && $row['grouptype'] != $nextgroup)
			{
				error_log_shiksha($userNames);
				if($institute == 1)
				{
					$queryCmd = "select distinct(b.displayname),a.collegeId,b.email,a.userId from tcollegeNetwork a INNER JOIN tuser b ON a.userid = b.userId where collegeId in(".$collegeId.")  and grouptype = '".$group."' and a.alertmail = 'subscribed'" ;
				}
				else
				{
					$queryCmd = "select distinct(b.displayname),a.schoolId as collegeId,b.email,a.userId from tschoolNetwork a INNER JOIN tuser b ON a.userid = b.userId where schoolId in(".$collegeId.") and a.alertmail = 'subscribed'" ;
				}
				error_log_shiksha($queryCmd);
				$query1 = $dbHandle->query($queryCmd);
				$numrows = $query1->num_rows();
				$this->load->library('alerts/Alerts_client');
				$alertClient = new Alerts_client();
				//for all the above users send mail about userIds csv i.e users added
				if($numrows > 1)
				{
					foreach($query1->result_array() as $row2)
					{
						$useremail = $row2['email'];
						if($count == 1 && $row2['userId'] == $userIds)
						{
						}
						else
						{
							if($count == 1)
								$subject = $userNames ." has joined ".$row['collegename'] ." group ";
							else
								$subject = $count . " new Shiksha Members joined ". $row['collegename'] . " group";
							$data['usernameemail'] = $useremail;
							if($institute == 1)
							{
								if($group == "TestPreparation")

									$data['resetlink'] =  getSeoUrl($row['collegeId'],"collegegroup",$row['collegename']) . '/0/TestPreparation';
								else
									$data['resetlink'] =  getSeoUrl($row['collegeId'],"collegegroup",$row['collegename']);
							}
							else
								$data['resetlink'] =  getSeoUrl($row['collegeId'],"schoolgroups",$row['collegename']);
							$data['noofmembers'] = $count;
							$data['collegename'] = $row['collegename'];
							$data['displayname'] = $row2['displayname'];
							$data['newuserName'] = $userNames;
							if($institute == 1)
								$groupname = "college";
							else
								$groupname = "school";
							if($count == 1)
							{
								$data['content'] = "A new Shiksha member,".$userNames." joined ".$row['collegename']." group yesterday. To know more about ".$userNames." logon to your ".$groupname." group ";
							}
							else
							{
								$data['content'] = $count . " new Shiksha members have joined ".$row['collegename']." group yesterday. To know more about new group members logon to your ".$groupname." group ";
							}
							$content = $this->load->view('network/alertMail',$data,true);
							$response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$useremail,$subject,$content,$contentType="html");
						}
					}
				}
				error_log_shiksha($response);
				$userIds = 0;


			}
		}

	}

	function getNewMessagesContent($appID,$todaysdate,$diffdate,$institute)
	{

		$dbHandle = $this->_loadDatabaseHandle();
		if($institute == 1)
		{
			//	$queryCmd = "select a.institute_name as collegename,b.displayname as username,a.institute_id as collegeId,b.userId as userid from institute a INNER JOIN(select b.listing_type_id,a.msgId,a.userId from messageTable a INNER JOIN listings_main b ON(a.threadId = b.threadId) and b.listing_type = 'institute' where a.creationDate between (now() - INTERVAL ".$diffdate." day) and now() group by b.listing_type_id) c ON(a.institute_id = c.listing_type_id) INNER JOIN tuser b On c.userId = b.userId where a.status <> 'deleted' order by a.institute_id asc";
			$queryCmd = "select c.*,b.displayname as username from (select b.institute_name as collegename,b.institute_id as collegeId,a.userId as userid from messageTable a INNER JOIN institute b ON(a.listingtypeId = b.institute_Id) where a.creationDate between (now() - INTERVAL 1 day) and now() and a.parentId <> 0 and a.status <> 'deleted' and b.status = 'live' group by b.institute_id)c INNER JOIN tuser b On c.userId = b.userId order by c.collegeId asc";
		}
		else
		{
			$queryCmd = "select c.school as collegename,b.displayname as username,c.schoolid as collegeId,b.userId as userid from (select b.school,b.schoolId,a.msgId,a.userId from messageTable a INNER JOIN NW_SCHOOLLIST b ON(a.threadId = b.threadId) where a.creationDate between (now() - INTERVAL ".$diffdate." day) and now() and b.status <> 'deleted' group by b.schoolid) c INNER JOIN tuser b On c.userId = b.userId order by c.schoolId asc";
		}


		error_log_shiksha('COUNT'.$queryCmd);

		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$collegeId = -1;
		$userIds = 0;
		$results_Array = $query->result_array();
		foreach ($query->result_array() as $row){
			$nextrow = next($results_Array);
			$nextcollegeId = $nextrow['collegeId'];
			$nextuserId = $nextrow['userid'];
			$collegeId = $row['collegeId'];
			if($userIds == 0)
			{
				$userIds = $row['userid'];
				$userNames = $row['username'];
				$count = 1;
			}
			else
			{
				$userIds = $userIds . "," .$row['userid'];
				//	$userNames = $userNames . "," .$row['username'];
				$count = $count + 1;
			}
			if($row['collegeId'] != $nextcollegeId)
			{
				error_log_shiksha($userNames);
				if($institute == 1)
				{
					$queryCmd = "select distinct(b.displayname),b.displayname,b.email,b.userId from tcollegeNetwork a INNER JOIN tuser b ON a.userid = b.userId where collegeId in(".$collegeId.") and a.alertmail = 'subscribed'";
				}
				else
				{
					$queryCmd = "select distinct(b.displayname),a.schoolId as collegeId,b.email,b.userId from tschoolNetwork a INNER JOIN tuser b ON a.userid = b.userId where schoolId in(".$collegeId.") and a.alertmail = 'subscribed'";
				}
				error_log_shiksha($queryCmd);
				$query1 = $dbHandle->query($queryCmd);
				$numrows = $query1->num_rows() ;
				$this->load->library('alerts/Alerts_client');
				$alertClient = new Alerts_client();
				//for all the above users send mail about userIds csv i.e users added
				if($numrows > 1)
				{
					foreach($query1->result_array() as $row2)
					{
						$useremail = $row2['email'];
						if($count == 1 && $row2['userId'] == $userIds)
						{
						}
						else
						{
							if($count == 1)
								$subject = $userNames ." has posted new comment on the ".$row['collegename'] ." group discussion board";
							else
								$subject = "Lots of activity on ". $row['collegename'] . " group discussion board";
							$data['usernameemail'] = $useremail;
							if($institute == 1)
								$data['resetlink'] =  getSeoUrl($row['collegeId'],"collegegroup",$row['collegename']);
							else
								$data['resetlink'] =  getSeoUrl($row['collegeId'],"schoolgroups",$row['collegename']);
							$data['noofmembers'] = $count;
							$data['collegename'] = $row['collegename'];
							$data['displayname'] = $row2['displayname'];
							$data['newuserName'] = $userNames;
							if($institute == 1)
								$groupname = "college";
							else
								$groupname = "school";
							if($count == 1)
							{
								$data['content'] = $row['collegename']." group member, ".$userNames.", has posted new comment on the group discussion board. To view and reply to the comment, logon to your ".$groupname." group ";
							}
							else
							{
								$data['content'] = $row['collegename']." group members, have posted new comments on the group discussion board. To view and reply to the comment, logon to your ".$groupname." group ";
							}
							$content = $this->load->view('network/alertMail',$data,true);
							$response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$useremail,$subject,$content,$contentType="html");
						}
					}
				}
				error_log_shiksha($response);
				$userIds = 0;
			}
		}
	}
	function getNewTopicsContent($appID,$todaysdate,$diffdate,$institute)
	{
		$dbHandle = $this->_loadDatabaseHandle();
		if($institute == 1)
		{
			$queryCmd = "select c.*,b.displayname as username from (select b.institute_name as collegename,b.institute_id as collegeId,a.userId as userid from messageTable a INNER JOIN institute b ON(a.listingtypeId = b.institute_Id) where a.creationDate between (now() - INTERVAL 1 day) and now() and a.parentId = 0 and b.status = 'live' and a.status <> 'deleted' group by b.institute_id)c INNER JOIN tuser b On c.userId = b.userId order by c.collegeId asc";
		}

		error_log('COUNT'.$queryCmd);

		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$collegeId = -1;
		$userIds = 0;
		$results_Array = $query->result_array();
		foreach ($query->result_array() as $row){
			$nextrow = next($results_Array);
			$nextcollegeId = $nextrow['collegeId'];
			$nextuserId = $nextrow['userid'];
			$collegeId = $row['collegeId'];
			if($userIds == 0)
			{
				$userIds = $row['userid'];
				$userNames = $row['username'];
				$count = 1;
			}
			else
			{
				$userIds = $userIds . "," .$row['userid'];
				//	$userNames = $userNames . "," .$row['username'];
				$count = $count + 1;
			}
			if($row['collegeId'] != $nextcollegeId)
			{
				error_log($userNames);
				if($institute == 1)
				{
					$queryCmd = "select distinct(b.displayname),b.displayname,b.email,b.userId from tcollegeNetwork a INNER JOIN tuser b ON a.userid = b.userId where collegeId in(".$collegeId.") and a.alertmail = 'subscribed'";
				}
				error_log($queryCmd);
				$query1 = $dbHandle->query($queryCmd);
				$numrows = $query1->num_rows() ;
				$this->load->library('alerts/Alerts_client');
				$alertClient = new Alerts_client();
				//for all the above users send mail about userIds csv i.e users added
				if($numrows > 1)
				{
					foreach($query1->result_array() as $row2)
					{
						$useremail = $row2['email'];
						if($count == 1 && $row2['userId'] == $userIds)
						{
						}
						else
						{
							if($count == 1)
								$subject = $userNames ." has started new discussion on the ".$row['collegename'] ." group discussion board";
							else
								$subject = "Lots of activity on ". $row['collegename'] . " group discussion board";
							$data['usernameemail'] = $useremail;
							if($institute == 1)
								$data['resetlink'] =  getSeoUrl($row['collegeId'],"collegegroup",$row['collegename']);
							$data['noofmembers'] = $count;
							$data['collegename'] = $row['collegename'];
							$data['displayname'] = $row2['displayname'];
							$data['newuserName'] = $userNames;
							if($institute == 1)
								$groupname = "college";
							if($count == 1)
							{
								$data['content'] = $row['collegename']." group member, ".$userNames.", has started new discussion on the group discussion board. To view and post comment, logon to your ".$groupname." group ";
							}
							else
							{
								$data['content'] = $row['collegename']." group members, have started new discussions on the group discussion board. To view and post comment, logon to your ".$groupname." group ";
							}
							$content = $this->load->view('network/alertMail',$data,true);
							$response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$useremail,$subject,$content,$contentType="html");
						}
					}
				}
				error_log($response);
				$userIds = 0;


			}
		}

	}

	function getAlertContent($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$todaysdate = $parameters['1'];
		$diffdate = $parameters['2'];
		$institute = $parameters['3'];

		$response = $this->getNewUserContent($appID,$todaysdate,$diffdate,$institute);
		/*		if($institute == 1)
				$response2 = $this->getNewTopicsContent($appID,$todaysdate,$diffdate,$institute);
				$response1 = $this->getNewMessagesContent($appID,$todaysdate,$diffdate,$institute);*/
		//connect DB
		return $this->xmlrpc->send_response(1);	

	}


	function getCollegeCount($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$alphabet = $parameters['1'];
		$country = $parameters['2'];
		$city = $parameters['3'];
		$category = $parameters['4'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$parentId = $this->getCategoryIdforCategoryName($category);
		if($parentId != 1)
		{
			$queryCmd = "select count(*) as count from (select distinct(a.institute_id) as institute,trim(a.institute_name) as institutename,a.logo_link as collegeurl from institute a INNER JOIN listing_category_table b ON(a.institute_id = b.listing_type_id and b.listing_type='institute') INNER JOIN categoryBoardTable d ON(b.category_Id = d.BoardId) INNER JOIN institute_location_table ilt On(ilt.institute_id = a.institute_id) where a.status = 'live' and ilt.status = 'live' d.parentId in( ".$parentId.")";
		}
		else
		{
			$queryCmd = "select count(*) as count from (select distinct(a.institute_id) as institute,trim(a.institute_name) as institutename,a.logo_link as collegeurl from institute a INNER JOIN institute_location_table ilt On(ilt.institute_id = a.institute_id) where a.status = 'live' and ilt.country_Id = ".$country ." and ilt.country_Id <> 2 and ilt.status = 'live'";
		}
		if($country != 'All')
			$queryCmd .= " and ilt.country_Id = ".$country;

		if($city != 'All')
			$queryCmd .= " and ilt.city_Id = ".$city;
		if($alphabet != 'All')
			$queryCmd .= ' and trim(a.institute_name) like \''.$alphabet.'%\'';

		$queryCmd .= ")y";

		error_log_shiksha('COUNT'.$queryCmd);

		log_message('debug','getUser Details query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$row = $query->row();
		$response = $row->count;
		return $this->xmlrpc->send_response($response);	
	}


	function getCollegeNetworkCount($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userStatus = $parameters['1'];
		$collegeId = $parameters['2'];
		$graduationYear = $parameters['3'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select ins.institute_Id as instituteId,ins.logo_link as logo, ins.institute_name as name,count(cn.userId) as count from tcollegeNetwork cn RIGHT OUTER JOIN institute ins ON(ins.institute_id = cn.collegeid) where ins.institute_Id IN ('.$collegeId.') and ins.status = "live"';


				if($userStatus != "Faculty" && $userStatus != "All" && $graduationYear > 0)
				$queryCmd .= ' and cn.graduationYear = '.$graduationYear;

				if($userStatus != 'All')
				{
				$queryCmd .= ' and userStatus in(\''.$userStatus.'\')';
					}
					$queryCmd.= ' group by ins.institute_name';

					error_log_shiksha($queryCmd);

					log_message('debug','getUser Details query cmd is ' . $queryCmd);

					$query = $dbHandle->query($queryCmd);
					$row = $query->row();


					$row = $query->row();
					if($query->num_rows() > 0)
					{
					$msgArray = array();
					foreach ($query->result_array() as $row){		
					array_push($msgArray,array($row,'struct'));
					}
					$response = array($msgArray,'struct');
					}
					else
						$response = "False";		
					return $this->xmlrpc->send_response($response);	
	}


	function addtoNetwork($request)
	{
		error_log_shiksha('ADDTONETWORK');
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$userStatus = $parameters['1'];
		$courseId = $parameters['2'];
		$year = $parameters['3'];
		$userId = $parameters['4'];
		$collegeId = $parameters['5'];
		$cityid = $parameters['6'];
		$collegeName = $parameters['7'];
		$grouptype = $parameters['8'];
		$date = date("Y.m.d,H:i:s");
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "Select userId from tcollegeNetwork where userId = " .$userId ." and collegeid = ".$collegeId;

		if($userStatus == "Student")
		{				
			$queryCmd1 = "select distinct(collegeId) from tcollegeNetwork where userId = ".$userId." and userStatus = 'Student'";
			error_log_shiksha($queryCmd1);
			$query1 = $dbHandle->query($queryCmd1);

		}
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		if($query->num_rows() > 0)
		{ 
			$response = "false";
		}
		else
		{
			if($userStatus == "Student" && $query1->num_rows() >= 2)
			{
				$response = -1;

			}
			else
			{
				$queryCmd = "insert into tcollegeNetwork values('','$collegeId','$userId','$year','$userStatus','$courseId',now(),'$cityid','subscribed','$grouptype')";
				error_log_shiksha('INSERT1'.$queryCmd);
				$query = $dbHandle->query($queryCmd);

				$response = $dbHandle->affected_rows();
                		$collegeurl = getSeoUrl($collegeId,"collegegroup",$collegeName);
				if($response > 0)
				{

					$sql = "select displayname from tuser where userId = $userId";
					$query1 = $dbHandle->query($sql);
					$row = $query1->row();
					$displayname = $row->displayname;
					$this->load->library('network/Network_client');
					$NetworkClient = new Network_client();
					$response1 = $NetworkClient->updateStatistics('membercount',$collegeId,1,$grouptype);
					$sql = "select group_concat(distinct(a.collegeId)) as collegeId from tcollegeNetwork a  inner join institute b on a.collegeId = b.institute_id where grouptype = 'group' and b.status = 'live' and userId = $userId";
					$query1 = $dbHandle->query($sql);
					$row = $query1->row();
					$collegeId = $row->collegeId;

					$this->load->library('network/Network_client');
					$NetworkClient = new Network_client();
					$newstext = '<a href = "/getUserProfile/'.$displayname.'">' . $displayname . '</a> has joined <a href = "'.$collegeurl.'">'.$collegeName.'</a> college network';
					$respo = $NetworkClient->insertgroupnews(1,'group',$collegeId,'group','groupadd',$userId,$newstext);
                    
                    /* Insert into test group */
                    $sql = "select group_concat(distinct(a.collegeId)) as collegeId from tcollegeNetwork a  where grouptype = 'TestPreparation' and userId = $userId";
					$query1 = $dbHandle->query($sql);
					$row = $query1->row();
					$collegeId = $row->collegeId;
					$respo = $NetworkClient->insertgroupnews(1,'group',$collegeId,'TestPreparation','groupadd',$userId,$newstext);
                    
                    $queryCmd = "update userPointSystem set userPointValue = userPointValue + 10 where userid = ".$userId;
					$query = $dbHandle->query($queryCmd);
				}
			}

		}

		return $this->xmlrpc->send_response($response);	
	}

	function updateStatistics($request)
	{
		$parameters = $request->output_parameters();
		$type = $parameters['0'];
		$collegeId = $parameters['1'];
		$count = $parameters['2'];
		$grouptype = $parameters['3'];
		$dbHandle = $this->_loadDatabaseHandle('write');

		if($type == "membercount" || ($type == "collegecount" && $count < 0))
		{
			if($type == "collegecount" && $count < 0)
				$query2 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = 0";
			else
				$query2 = "update tcategorygroupStatistics set membercount = membercount + ".$count." where categoryId = 0";
			error_log($query2);
			$query = $dbHandle->query($query2);
			$testsql = "insert into tlog values('$type',$count,$collegeId,0,now())";
			error_log($testsql);
			$testquery = $dbHandle->query($testsql);
		}
		if($type == "collegecount")
		{
			$query1 = "update tcategorygroupStatistics set collegecount = collegecount + ".$count ." where categoryId = 0";
			$query = $dbHandle->query($query1);
			$testsql = "insert into tlog values('$type',$count,$collegeId,0,now())";
			$testquery = $dbHandle->query($testsql);
		}
		$count3 = 0;
		if($grouptype == "TestPreparation")
		{
			if($type == "collegecount")
				$query2 = "update tcategorygroupStatistics set collegecount = collegecount + ".$count ." where categoryId = -1";
			else
				$query2 = "update tcategorygroupStatistics set membercount = membercount + ".$count." where categoryId = -1";
			$query = $dbHandle->query($query2);
			error_log($query2);

		}
		if($grouptype != "TestPreparation")
		{
			$query1 = "select country_Id as country from institute_location_table where status = 'live' and institute_id = ".$collegeId;
			$query2 = $dbHandle->query($query1);
			foreach($query2->result_array() as $row1)
			{
				if($row1['country'] <> 2 && $count3 == 0)
				{
					$queryCmd = "update tcategorygroupStatistics set ".$type ." = ".$type." + ".$count." where categoryId = '1'";
					error_log_shiksha('updateStats'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					$response = $dbHandle->affected_rows();
					if($type == "collegecount" && $count < 0)
					{
						$queryCmd1 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = '1' ";
						$query1 = $dbHandle->query($queryCmd1);
					}
					$count3 = $count3 + 1;
				}

			}		
			$queryCmd = "select distinct parentId from categoryBoardTable where boardId in(select category_id from listing_category_table where listing_type='institute' and listing_type_id = ".$collegeId.")";

			$query = $dbHandle->query($queryCmd);
			$count4 = 0;
			$count1 = 0;
			$count2 = 0;
			foreach ($query->result_array() as $row){
				if(($row['parentId'] == 2 || $row['parentId'] == 10)&& $count4 == 0)
				{
					$queryCmd = "update tcategorygroupStatistics set ".$type ." = ".$type." + ".$count." where categoryId = '2,10'";
					if($type == "collegecount" && $count < 0)
					{
						$queryCmd1 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = '2,10' ";
						$query1 = $dbHandle->query($queryCmd1);
					}
					error_log_shiksha('updateStats'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					$response = $dbHandle->affected_rows();
					$count4 = $count4 + 1 ;
					$testsql = "insert into tlog values('$type',$count,$collegeId,".$row['parentId'].",now())";
					$testquery = $dbHandle->query($testsql);

				}

				if(($row['parentId'] == 3 || $row['parentId'] == 4 || $row['parentId'] == 11)&& $count1 == 0)
				{

					$queryCmd = "update tcategorygroupStatistics set ".$type ." = ".$type." + ".$count." where categoryId = '3,4,11'";
					error_log_shiksha('updateStats'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					$response = $dbHandle->affected_rows();
					$count1 = $count1 + 1 ;
					if($type == "collegecount" && $count < 0)
					{
						$queryCmd1 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = '3,4,11' ";
						$query1 = $dbHandle->query($queryCmd1);
					}
					$testsql = "insert into tlog values('$type',$count,$collegeId,".$row['parentId'].",now())";
					$testquery = $dbHandle->query($testsql);

				}

				if(($row['parentId'] == 7 || $row['parentId'] == 12)&& $count2 == 0)
				{
					$queryCmd = "update tcategorygroupStatistics set ".$type ." = ".$type." + ".$count." where categoryId = '7,12'";
					error_log_shiksha('updateStats'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					$response = $dbHandle->affected_rows();
					$count2 = $count2 + 1 ;
					if($type == "collegecount" && $count < 0)
					{
						$queryCmd1 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = '7,12' ";
						$query1 = $dbHandle->query($queryCmd1);
					}
					$testsql = "insert into tlog values('$type',$count,$collegeId,".$row['parentId'].",now())";
					$testquery = $dbHandle->query($testsql);

				}

				if($row['parentId'] == 5 || $row['parentId'] == 6 || $row['parentId'] == 8 || $row['parentId'] == 9 || $row['parentId'] == 149)
				{
					$queryCmd = "update tcategorygroupStatistics set ".$type ." = ".$type." + ".$count." where categoryId = ".$row['parentId'];
					error_log_shiksha('updateStats'.$queryCmd);
					$query = $dbHandle->query($queryCmd);
					$response = $dbHandle->affected_rows();
					if($type == "collegecount" && $count < 0)
					{
						$queryCmd1 = "update tcategorygroupStatistics set membercount = membercount - (select count(userId) as count from tcollegeNetwork where collegeId = ".$collegeId." and grouptype = '".$grouptype."') where categoryId = ".$row['parentId'];
						$query1 = $dbHandle->query($queryCmd1);
					}
					$testsql = "insert into tlog values('$type',$count,$collegeId,".$row['parentId'].",now())";
					$testquery = $dbHandle->query($testsql);

				}


			}
		}
		return $this->xmlrpc->send_response($response);	
	}

	function addUserRequest($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$senderId = $parameters['1'];
		$userId = $parameters['2'];
		$type = $parameters['3'];

		$date = date("Y.m.d,H.m.s");
		error_log_shiksha($date.'DATE');
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "select requestid,status from tuserNetworkRequest where senderuserid = ".$senderId." and userId = ".$userId ." order by requestdate desc limit 0,1";
		$query = $dbHandle->query($queryCmd);
		error_log_shiksha($queryCmd);
		error_log_shiksha($query->num_rows());
		$queryCmd1 = "select requestid,status from tuserNetworkRequest where userid = ".$senderId." and senderuserId = ".$userId ." order by requestdate desc limit 0,1";
		error_log_shiksha($queryCmd1);
		$query1 = $dbHandle->query($queryCmd1);

		if($query->num_rows() > 0 || $query1->num_rows() >0)
		{
			$row = $query->row();
			$row1 = $query1->row();


			if($row->status == "new")
			{
				$response = -1;
				return $this->xmlrpc->send_response($response);
			}

			if($row->status == "accept")
			{
				$response = -2;
				return $this->xmlrpc->send_response($response);
			}
			if($row->status == "block")
			{
				$response = -3;
				return $this->xmlrpc->send_response($response);

			}

			if($row1->status == "new")
			{
				$response = -4;
				return $this->xmlrpc->send_response($response);
			}
			if($row1->status == "accept")
			{
				$response = -5;
				return $this->xmlrpc->send_response($response);
			}
			if($row->status == "reject")
			{
				$queryCmd = "insert into tuserNetworkRequest values('','$senderId','$userId','$type',now(),'')";

				error_log_shiksha($queryCmd);
				$query = $dbHandle->query($queryCmd);

				$response = $dbHandle->affected_rows();
				return $this->xmlrpc->send_response($response);

			}
			if($row1->status == "reject")
			{
				$queryCmd = "insert into tuserNetworkRequest values('','$senderId','$userId','$type',now(),'')";

				error_log_shiksha($queryCmd);
				$query = $dbHandle->query($queryCmd);

				$response = $dbHandle->affected_rows();
				return $this->xmlrpc->send_response($response);

			}
			if($row1->status == "block")
			{
				$queryCmd = "insert into tuserNetworkRequest values('','$senderId','$userId','$type',now(),'')";

				error_log_shiksha($queryCmd);
				$query = $dbHandle->query($queryCmd);

				$response = $dbHandle->affected_rows();
				return $this->xmlrpc->send_response($response);

			}
		}             

		else
		{
			$queryCmd = "insert into tuserNetworkRequest values('','$senderId','$userId','$type',now(),'')";

			error_log_shiksha($queryCmd);
			$query = $dbHandle->query($queryCmd);

			$response = $dbHandle->affected_rows();
		}
		return $this->xmlrpc->send_response($response);	
	}

	function showUserNetworkList($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$start = $parameters['1'];
		$count = $parameters['2'];
		$userId = $parameters['3'];
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select u.lastlogintime,unr.senderuserId as senderuserid,u.displayname,u.avtarimageurl,unr.userid from tuserNetworkRequest unr INNER JOIN tuser u ON(u.userid = unr.senderuserId) where unr.userId = ".$userId." and unr.status = 'accept' UNION select u.lastlogintime,unr.userId as senderuserid,u.displayname,u.avtarimageurl,unr.senderuserid from  tuserNetworkRequest unr INNER JOIN tuser u ON(u.userid = unr.userId) where unr.senderuserId = ".$userId." and unr.status = 'accept' order by userid ";

		if($count != 0)
			$queryCmd = $queryCmd ." limit ".$start.",".$count;

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);

		if($query->num_rows() > 0)
		{
			$msgArray = array();
			foreach ($query->result_array() as $row){		
				array_push($msgArray,array($row,'struct'));
			}
			$response = array($msgArray,'struct');
		}
		else
			$response = "0" ;
		return $this->xmlrpc->send_response($response);	
	}

	function showCollegeNetworkList($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$start = $parameters['1'];
		$count = $parameters['2'];
		$userId = $parameters['3'];
		$institute = $parameters['4'];
		$dbHandle = $this->_loadDatabaseHandle();
		if($institute == 1)
		{	    
			$queryCmd = "select cn.collegeId,i.logo_link as logo,trim(i.institute_name) as name,cn.graduationYear ,cn.userStatus from tcollegeNetwork cn INNER JOIN institute i ON(i.institute_id = cn.collegeId) where cn.userId = ".$userId." and i.status = 'live' order by trim(i.institute_name) ";
			if($count > 0)
				$queryCmd .= " limit ".$start.",".$count;
		}

		if($institute == 2)
		{	    
			$queryCmd = "select cn.collegeId,i.logo_link as logo,trim(i.institute_name) as name,cn.graduationYear ,cn.userStatus from tcollegeNetwork cn INNER JOIN institute i ON(i.institute_id = cn.collegeId) where i.status = 'live' and cn.userId = ".$userId." order by trim(i.institute_name) ";
			if($count > 0)
				$queryCmd .= " limit ".$start.",".$count;
		}
		if($institute == 3)
		{	    
			$queryCmd = "select 'collegegroup', cn.collegeId as collegeId,i.logo_link as logo,trim(i.institute_name) as name,cn.graduationYear ,cn.userStatus from tcollegeNetwork cn INNER JOIN institute i ON(i.institute_id = cn.collegeId) where cn.userId = $userId and i.status = 'live' and cn.grouptype = 'group' group by cn.collegeId UNION select 'schoolgroup',sn.schoolId as collegeId , NULL as logo,trim(nw.school) as name,sn.passoutyear as graduationYear,sn.userStatus from tschoolNetwork sn INNER JOIN NW_SCHOOLLIST nw ON(nw.schoolId = sn.schoolId) where sn.userId = $userId group by sn.schoolId union select 'testgroup', cn.collegeId as collegeId,i.blogImageURL as logo,trim(i.blogTitle) as name,cn.graduationYear ,cn.userStatus from tcollegeNetwork cn INNER JOIN blogTable i ON(i.blogId = cn.collegeId) where cn.userId = $userId and i.status = 'live' and cn.grouptype = 'TestPreparation' group by cn.collegeId order by name desc";
			//			$queryCmd = " select 'collegegroup', cn.collegeId as collegeId,i.logo_link as logo,trim(i.institute_name) as name,cn.graduationYear ,cn.userStatus from tcollegeNetwork cn INNER JOIN institute i ON(i.institute_id = cn.collegeId) where cn.userId = ".$userId." and i.status <> 'deleted' group by cn.collegeId UNION select 'schoolgroup',sn.schoolId as collegeId , NULL as logo,trim(nw.school) as name,sn.passoutyear as graduationYear,sn.userStatus from tschoolNetwork sn INNER JOIN NW_SCHOOLLIST nw ON(nw.schoolId = sn.schoolId) where sn.userId = ".$userId ." group by sn.schoolId order by name desc";
			if($count > 0)
				$queryCmd .= " limit ".$start.",".$count;
		}

		$query = $dbHandle->query($queryCmd);

		if($query->num_rows() > 0)
		{
			$msgArray = array();
			foreach ($query->result_array() as $row){
				if($institute == 3)
				{
					if($row['collegegroup'] == 'collegegroup')
						$queryCmd = "select count(userId)  as count from tcollegeNetwork where collegeId = ".$row['collegeId'] ." and grouptype = 'group'";
					if($row['collegegroup'] == 'testgroup')
						$queryCmd = "select count(userId)  as count from tcollegeNetwork where collegeId = ".$row['collegeId'] ." and grouptype = 'TestPreparation'";
					if($row['collegegroup'] == 'schoolgroup')
						$queryCmd = "select count(userId)  as count from tschoolNetwork where schoolId = ".$row['collegeId'];

					if($row['collegegroup'] == 'collegegroup')
					{
						$queryCmd1 = "select count(msgId) as count from messageTable where listingTypeId = ".$row['collegeId']." and listingType = 'group' and fromOthers = 'group' and parentId = 0 and status <> 'deleted'";
						//				$queryCmd1 = " select count(msgId) as count from messageTable a INNER JOIN listings_main b ON(a.threadId = b.threadId) and listing_type_id = ".$row['collegeId']." and listing_type = 'institute' and a.parentId <> 0";
					}
					if($row['collegegroup'] == 'testgroup')
					{
						$queryCmd1 = "select count(msgId) as count from messageTable where listingTypeId = ".$row['collegeId']." and listingType = 'TestPreparation' and fromOthers = 'TestPreparation' and parentId = 0 and status <> 'deleted'";

					}
					if($row['collegegroup'] == 'schoolgroup')
						$queryCmd1 = " select count(msgId) as count from messageTable a INNER JOIN NW_SCHOOLLIST b ON(a.threadId = b.threadID) and b.schoolid = ".$row['collegeId']." and a.parentId <> 0 and status <> 'deleted'";
					$query1 = $dbHandle->query($queryCmd);
					$query2 = $dbHandle->query($queryCmd1);
					$row1 = $query1->row();
					$row2 = $query2->row();
					$row['membercount'] = $row1->count;
					$row['messagecount'] = $row2->count;
				}
				if($institute == 1)	    	
					$row['url'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']);
				if($institute == 3)
				{
					if($row['collegegroup'] == "collegegroup")
						$row['url'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']);
					if($row['collegegroup'] == "testgroup")
						$row['url'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']) . "/0/TestPreparation";
					if($row['collegegroup'] == 'schoolgroup')
						$row['url'] = getSeoUrl($row['collegeId'],"schoolgroups",$row['name']);
				}
				array_push($msgArray,array($row,'struct'));
			}
			$response = array($msgArray,'struct');
		}
		else
			$response = "0" ;

		return $this->xmlrpc->send_response($response);	
	}

	function showNewRequests($request)
	{

		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$userId = $parameters['1'];
		$type = $parameters['2'];
		$start = $parameters['3'];
		$count = $parameters['4'];
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select SQL_CALC_FOUND_ROWS unr.senderuserId as senderuserid,u.displayname,u.avtarimageurl,u.email from tuserNetworkRequest unr INNER JOIN tuser u ON(u.userid = unr.senderuserId) where unr.userId = ".$userId." and unr.status = 'new' limit ".$start.",".$count;
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		error_log_shiksha('NO OF ROWS'.$query->num_rows());
		$queryCmd1 = "select FOUND_ROWS() as totalRows";
		error_log_shiksha($queryCmd1);
		$query1 = $dbHandle->query($queryCmd1);
		$row1 = $query1->row();
		$totalRows = $row1->totalRows;	
		if($query->num_rows() > 0)
		{
			$msgArray = array();
			foreach ($query->result_array() as $row){		
				array_push($msgArray,array($row,'struct'));
			}
			$respArray = array();
			array_push($respArray,array(
							'network'=>array($msgArray,'struct'),
							'totalCount'=>array($totalRows,'string'),
						     ),'struct');
			$response = $respArray;
		}
		else
			$response = "0" ;

		return $this->xmlrpc->send_response($response);	
	}

	function showRequestResponse($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$senderid = $parameters['1'];
		$userid = $parameters['2'];
		$response = $parameters['3'];
		$date = date("Y.m.d");
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$queryCmd = 'update tuserNetworkRequest set status = "'.$response.'", replydate = "'.$date.'" where userid = '.$userid.' and senderuserid = '.$senderid.' and replydate = 0';
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);

		$response1 = $dbHandle->affected_rows();
		$sql = "select displayname from tuser where userId = $senderid";
		$query1 = $dbHandle->query($sql);
		$row = $query1->row();
		$sendername = $row->displayname;

		$sql = "select displayname from tuser where userId = $userid";
		$query1 = $dbHandle->query($sql);
		$row = $query1->row();
		$name = $row->displayname;



		if($response == "accept" && $response1 > 0)
		{	
			$this->load->library('network/Network_client');
			$NetworkClient = new Network_client();
			$newstext = '<a href = "/getUserProfile/'.$name.'">' . $name . '</a> and <a href = "/getUserProfile/'.$sendername.'">'.$sendername.'</a> are now connected';
			$sql = "select group_concat(collegeId) as collegeId from tcollegeNetwork where userId = $userid group by userId";
			error_log_shiksha($sql);
			$query = $dbHandle->query($sql);
			$row = $query->row();

			$respo = $NetworkClient->insertgroupnews(1,'groups',$row->collegeId,'group','personalnetworkadd',$userId,$newstext);

			error_log_shiksha($queryCmd);
		}
		return $this->xmlrpc->send_response($response1);	
	}		

	function getgroupnews($request)
	{

		$parameters = $request->output_parameters();
		$instituteId = $parameters['0'];
		$grouptype = $parameters['1'];
		$start = $parameters['2'];
		$count = $parameters['3'];
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select * from groupnews where groupId = $instituteId and grouptype = '$grouptype' order by insertiondate desc limit $start,$count";
		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);

		if($query->num_rows() > 0)
		{
			$msgArray = array();
			foreach ($query->result_array() as $row){		
				array_push($msgArray,array($row,'struct'));
			}
			$response = array($msgArray,'struct');
		}
		else
			$response = "False";

		return $this->xmlrpc->send_response($response);	
	}

	function showuserCollegeNetwork($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$startfrom = $parameters['1'];
		$count = $parameters['2'];
		$userStatus = $parameters['3'];
		$collegeId = $parameters['4'];
		$graduationYear = $parameters['5'];
		$grouptype = $parameters['6'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($userStatus == 'all' || $userStatus == 'All') {
			$queryCmd = "select SQL_CALC_FOUND_ROWS u.userId,u.email,u.displayname,u.profession, cn.collegeId, cn.userStatus, u.lastlogintime,u.avtarimageurl,cn.graduationYear,(select level from userPointLevel where minLimit<=ups.userPointValue limit 1) level,cn.joiningDate from tcollegeNetwork cn Inner Join userPointSystem ups ON(cn.userId = ups.userId) Inner Join tuser u ON(cn.userId = u.userId) where cn.collegeId IN ('$collegeId') and cn.grouptype = '$grouptype'";
		}
		else{
			$queryCmd = "select SQL_CALC_FOUND_ROWS u.userId, u.email,u.displayname,u.profession, cn.collegeId, cn.userStatus,u.lastlogintime, u.avtarimageurl,cn.graduationYear,(select level from userPointLevel where minLimit<=ups.userPointValue limit 1) level,cn.joiningDate from tcollegeNetwork cn Inner Join userPointSystem ups ON(cn.userId = ups.userId) Inner Join tuser u ON(cn.userId = u.userId) where cn.collegeId IN ('.$collegeId.') and cn.userStatus in(\''.$userStatus.'\') and cn.grouptype = '$grouptype'";
		}
		if($userStatus != "Faculty" && $userStatus != "All" && $userStatus != 'all' && $graduationYear > 0)
			$queryCmd .= ' and cn.graduationYear = '.$graduationYear;
		$queryCmd .= ' order by cn.joiningdate desc limit '.$startfrom.','.$count;
		error_log($queryCmd);
		$query = $dbHandle->query($queryCmd);

		$queryCmd1 = "select FOUND_ROWS() as totalRows";
		error_log_shiksha($queryCmd1);
		$query1 = $dbHandle->query($queryCmd1);
		$row1 = $query1->row();
		$totalRows = $row1->totalRows;	
		if($query->num_rows() > 0)
		{
			$msgArray = array();
			$respArray = array();
			foreach ($query->result_array() as $row){		
				array_push($msgArray,array($row,'struct'));
			}
			array_push($respArray,array(
						array(
							'network'=>array($msgArray,'struct'),
							'totalCount'=>array($totalRows,'string'),
						     ),'struct')
				  );

			$response = array($respArray,'struct');
		}
		else
			$response = "False";
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);	
	}

	function totalCollegesandUsers($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select count(a.institute_id) as count from institute a INNER JOIN institute_location_table ilt ON(a.institute_id = ilt.institute_id) INNER JOIN countryTable b ON(ilt.country_id = b.countryId)  where a.status = "live" and ilt.status = "live"';

		error_log_shiksha($queryCmd);
		$queryCmd1 = "select count(userId) as count from tcollegeNetwork a INNER JOIN institute b ON(a.collegeId = b.institute_id) where b.status = 'live'";
		$queryCmd2  = "Select count(SCHOOLID) as count from NW_SCHOOLLIST a INNER JOIN countryCityTable b On(a.cityId = b.city_Id)";
		$queryCmd3 = "select count(userId) as count from tschoolNetwork a INNER JOIN NW_SCHOOLLIST b ON (a.schoolId = b.schoolId)";
		$query = $dbHandle->query($queryCmd);
		$row = $query->row();
		$query1 = $dbHandle->query($queryCmd1);
		$row1 = $query1->row();


		$query2 = $dbHandle->query($queryCmd2);
		$row2 = $query2->row();

		$query3 = $dbHandle->query($queryCmd3);
		$row3 = $query3->row();

		$msgArray = array();

		array_push($msgArray,array(
					array(
						"colleges"=> $row->count,
						"schools"=> $row2->count,
						"users"=> $row1->count,
						"schoolusers"=> $row3->count,
					     ),'struct')
			  );
		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);	
	}

	function getThreadId($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$collegeId = $parameters['1'];
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select lm.threadId as thread_id,ic.category_id as category_id,c.country_id as countryId from listings_main lm INNER JOIN institute i ON(lm.listing_type_id = i.institute_id) INNER JOIN listing_category_table ic ON (ic.listing_type_id = i.institute_id) inner JOIN institute_location_table c ON(c.institute_id = i.institute_id) where ic.listing_type='institute' and lm.listing_type='institute' and i.status = 'live' and c.status = 'live' and lm.status = 'live' and i.institute_id = " .$collegeId." limit 0,1 ";

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$row = $query->row();
		$thread = $row->thread_id;
		$board = $row->category_id;
		error_log_shiksha($queryCmd1);
		$msgArray = array();
		array_push($msgArray,array(
					array(
						"threadid"=> $row->thread_id,
						"categoryid"=> $row->category_id,
						"countryid"=>$row->countryId,
					     ),'struct')
			  );
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($response,true));
		return $this->xmlrpc->send_response($response);	
	}

	function getThreadIdforSchool($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$schoolId = $parameters['1'];
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select threadId as thread_id from NW_SCHOOLLIST where schoolId = " .$schoolId;

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$row = $query->row();
		$thread = $row->thread_id;
		error_log_shiksha($queryCmd1);
		$msgArray = array();
		array_push($msgArray,array(
					array(
						"threadid"=> $row->thread_id,
					     ),'struct')
			  );
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function totalUsersCollegesinUserNet($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];
		$institute = $parameters['2'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($institute == 1)
		{
			$queryCmd = 'select count(distinct(collegeId)) as count from tcollegeNetwork a INNER JOIN institute b On(a.collegeId = b.institute_id) where b.status = "live" and userid = '.$userId;
		}
		if($institute == 2)
		{
			$queryCmd = 'select count(distinct(schoolId)) as count from tschoolNetwork where userid = '.$userId;
		}
		if($institute == 3)
		{

			$queryCmd = 'select sum(count) as count from (select count(distinct(collegeId)) as count from tcollegeNetwork a INNER JOIN institute b On(a.collegeId = b.institute_id) where b.status = "live" and userid = '.$userId . ' UNION select count(distinct(schoolId)) as count from tschoolNetwork where userid = '.$userId . ')x';
		}
		error_log_shiksha($queryCmd);
		$queryCmd1 = " select count(requestid) as count from tuserNetworkRequest where (senderuserid = ".$userId."  or userid = ".$userId." ) and status = 'accept'";
		$query = $dbHandle->query($queryCmd);
		error_log_shiksha($queryCmd1);
		$row = $query->row();
		$query1 = $dbHandle->query($queryCmd1);
		$row1 = $query1->row();
		error_log_shiksha('ROW'.$row->count);
		error_log_shiksha('ROW1'.$row1->count);
		$msgArray = array();
		array_push($msgArray,array(
					array(
						"colleges"=> $row->count,
						"users"=> $row1->count
					     ),'struct')
			  );
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*functions school network*/

	function showSchoolMembersCount($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userStatus = $parameters['1'];
		$schoolId = $parameters['2'];
		$graduationYear = $parameters['3'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select s.schoolId as schoolId,s.school as name,count(cn.userId) as count from tschoolNetwork cn RIGHT OUTER JOIN NW_SCHOOLLIST s ON(s.schoolid = cn.schoolid) where s.schoolId IN ('.$schoolId.')';


				if($userStatus != "Faculty" && $userStatus != "All" && $graduationYear > 0)
				$queryCmd .= ' and passoutyear = '.$graduationYear;

				if($userStatus != 'All')
				{
				$queryCmd .= ' and userStatus in(\''.$userStatus.'\')';
					}

					$queryCmd.= ' group by s.school';

					error_log_shiksha($queryCmd);

					log_message('debug','getUser Details query cmd is ' . $queryCmd);

					$query = $dbHandle->query($queryCmd);
					$row = $query->row();


					$row = $query->row();
					if($query->num_rows() > 0)
					{
					$msgArray = array();
					foreach ($query->result_array() as $row){		
					array_push($msgArray,array($row,'struct'));
					}
					$response = array($msgArray,'struct');
					}
					else
						$response = "False";	
					return $this->xmlrpc->send_response($response);	
	}


	function showuserSchoolNetwork($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$startfrom = $parameters['1'];
		$count = $parameters['2'];
		$userStatus = $parameters['3'];
		$collegeId = $parameters['4'];
		$graduationYear = $parameters['5'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$response = array();
		if($userStatus == 'all' || $userStatus == 'All') {
			$queryCmd = 'select u.userId, u.email,u.displayname,u.profession, cn.schoolId, cn.userStatus,cn.passoutyear as graduationYear, u.lastlogintime,u.avtarimageurl,(select level from userPointLevel where minLimit<=ups.userPointValue limit 1) level from tschoolNetwork cn Inner Join userPointSystem ups ON(cn.userId = ups.userId) Inner Join tuser u ON(cn.userId = u.userId) where cn.schoolId IN ('.$collegeId.')';
					}
					else{
					$queryCmd = 'select u.userId, u.email,u.displayname,u.profession, cn.schoolId, cn.userStatus,cn.passoutyear as graduationYear,u.lastlogintime, u.avtarimageurl,(select level from userPointLevel where minLimit<=ups.userPointValue limit 1) level from tschoolNetwork cn Inner Join userPointSystem ups ON(cn.userId = ups.userId) Inner Join tuser u ON(cn.userId = u.userId) where cn.schoolId IN ('.$collegeId.') and cn.userStatus in(\''.$userStatus.'\')';
						}
						if($userStatus != "Faculty" && $userStatus != "All" && $userStatus != 'all' && $graduationYear > 0)
						$queryCmd .= ' and cn.passoutyear = '.$graduationYear;
						$queryCmd .= ' order by cn.joiningdate desc limit '.$startfrom.','.$count;
						error_log_shiksha($queryCmd);
						$query = $dbHandle->query($queryCmd);
						$numRows = $query->num_rows();

						if($numRows > 0)
						{
						$msgArray = array();
						foreach ($query->result_array() as $row){
						array_push($msgArray,array($row,'struct'));	
						}
						$response = array($msgArray,'struct');
						}
						else
						$response = "False";
						return $this->xmlrpc->send_response($response);	
						}


	function addtoSchoolNetwork($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$userStatus = $parameters['1'];
		$year = $parameters['2'];
		$userId = $parameters['3'];
		$collegeId = $parameters['4'];
		$cityid = $parameters['5'];
		$date = date("Y.m.d,H:i:s");
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = "Select userId from tschoolNetwork where userId = " .$userId ." and schoolid = ".$collegeId;
		if($userStatus == "Student")
		{				
			$queryCmd1 = "select distinct(schoolId) from tschoolNetwork where userId = ".$userId." and userStatus = 'Student'";
			error_log_shiksha($queryCmd1);
			$query1 = $dbHandle->query($queryCmd1);
		}

		error_log_shiksha('HERE'.$queryCmd);
		$query = $dbHandle->query($queryCmd);
		if($query->num_rows() > 0)
		{ 
			$response = "false";
		}
		else
		{
			if($userStatus == "Student" && $query1->num_rows() >= 1)
			{
				$response = -1;
			}
			else
			{
				$queryCmd = "insert into tschoolNetwork values('','$collegeId','$userId','$year','$userStatus',now(),'$cityid','subscribed')";
				error_log_shiksha($queryCmd);
				$query = $dbHandle->query($queryCmd);

				$response = $dbHandle->affected_rows();
				if($response > 0)
				{
					$queryCmd = "update userPointSystem set userPointValue = userPointValue + 10 where userid = ".$userId;
					$query = $dbHandle->query($queryCmd);
				}
			}

		}

		return $this->xmlrpc->send_response($response);	
	}

	function getcitiesbyCountry($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$country = $parameters['1'];
		$start = $parameters['2'];
		$count = $parameters['3'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select a.cityId as city ,b.city as name from NW_SCHOOLLIST a INNER JOIN countryCityTable b ON(a.cityId = b.city_Id) group by a.cityid order by b.city ";


		if($count > 0)
			$queryCmd .= ' limit '.$start.','.$count;

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	/* Function school Network end*/
	function getNoofRequestsforUser($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['1'];
		$startdate = $parameters['2'];
		$enddate = $parameters['3'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select u.userid,u.displayname,u.email,u.avtarimageurl from tuserNetworkRequest unr INNER JOIN tuser u ON(u.userid = unr.senderuserid) where status = 'new' and unr.userid = '$userId' and requestdate between '$startdate' and '$enddate'";


		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function checkifmember($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId = $parameters['2'];
		$institute = $parameters['1'];
		$instituteId = $parameters['3'];
		$grouptype = $parameters['4'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($institute == "school")
			$queryCmd = "select alertMail from tschoolNetwork where userid = ".$userId." and schoolId = ".$instituteId;
		else

			$queryCmd = "select alertMail from tcollegeNetwork where userid = ".$userId." and collegeId = ".$instituteId." and grouptype = '".$grouptype."'";

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$noofrows = $query->num_rows();    
		$row = $query->row();                              
		$response = $row->alertMail;
		$msgArray = array('flag'=> $noofrows,
				'status'=>$row->alertMail,
				);
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function sgetSchoolsForIndex($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$schoolId = $parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select a.SchoolId as SchoolId,a.School as SchoolName,b.city_id,b.city_name from NW_SCHOOLLIST a,countryCityTable b where a.schoolId = ".$schoolId." and b.city_Id = a.cityId";


		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function sgetTestPrepGroupForIndex($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$groupId = $parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = "select blogId as Id, blogTitle as title, blogText as content, countryId, acronym as abbr from blogTable where (parentId in (select blogId from blogTable where blogType=\"exam\" and parentId = 0) or parentId = 0) and blogType=\"exam\" and blogId =".$groupId;

		error_log($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}


	function sgetRecentlyAddedMembers($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$instituteFlag = $parameters['1'];
		$count = $parameters['2'];
		$category = $parameters['3'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($category == "TestPreparation" && $instituteFlag == 1)
		{
			$queryCmd = 'select b.userId,c.displayname,c.avtarimageurl,a.blogId as collegeId,b.joiningDate,a.blogTitle as name from (select blogId,blogTitle,acronym,parentId,blogImageURL from blogTable where parentId in (select blogId from blogTable where blogType="exam" and parentId = 0) or parentId = 0 and blogType="exam")a INNER JOIN tcollegeNetwork b on a.blogId = b.collegeId inner join tuser c on c.userId = b.userid where b.grouptype = "TestPreparation" order by joiningDate desc limit '.$count;
		}
		if($instituteFlag == 1 && $category != "TestPreparation")
		{
			$parentId = $this->getCategoryIdforCategoryName($category);
			//$parentId = $category;
			error_log_shiksha($category);
			if($parentId != 1)
			{
				$queryCmd = "select tu.userId,tu.displayname,tu.avtarimageurl,a.collegeId,a.joiningDate,x.name from tcollegeNetwork a INNER JOIN (select distinct(a.institute_id) as institute,trim(a.institute_name) as name,a.logo_link from institute a INNER JOIN listing_category_table b ON(a.institute_id = b.listing_type_id) INNER JOIN categoryBoardTable d ON(b.category_Id = d.BoardId) where b.listing_type='institute' and a.status = 'live' and d.parentId in( ".$parentId."))x ON(a.collegeId = x.institute) INNER JOIN tuser tu ON(tu.userID = a.userId) order by a.joiningDate desc limit ".$count;
			}
			else
			{
				$queryCmd = "select tu.userId,tu.displayname,tu.avtarimageurl,a.collegeId,a.joiningDate,x.name from tcollegeNetwork a INNER JOIN (select distinct(a.institute_id) as institute,trim(a.institute_name) as name,a.logo_link from institute a INNER JOIN institute_location_table c ON(a.institute_id = c.institute_id) where a.status = 'live' and c.status = 'live' and c.country_Id <> 2 )x ON(a.collegeId = x.institute) INNER JOIN tuser tu ON(tu.userID = a.userId) order by a.joiningDate desc limit ".$count;
			}
		}
		if($instituteFlag == 2)
			$queryCmd = "select a.userId,c.displayname,b.schoolId,joiningDate,trim(school) as name,c.avtarimageurl from tschoolNetwork a INNER JOIN NW_SCHOOLLIST b ON(a.schoolId = b.schoolid) INNER JOIN tuser c ON(a.userid = c.userid) order by joiningDate desc limit ".$count;
		if($instituteFlag == 3)
			$queryCmd = "select 'college',a.userId,c.displayname,collegeId,joiningDate,trim(institute_name) as name,c.avtarimageurl from tcollegeNetwork a INNER JOIN institute b ON(a.collegeId = b.institute_id) INNER JOIN tuser c ON(a.userid = c.userid) where b.status = 'live' and a.grouptype = 'group' UNION select 'school',a.userId,c.displayname,b.schoolId as collegeId,joiningDate,trim(school) as name,c.avtarimageurl from tschoolNetwork a INNER JOIN NW_SCHOOLLIST b ON(a.schoolId = b.schoolid) INNER JOIN tuser c ON(a.userid = c.userid) UNION  select 'test',a.userId,c.displayname,blogId as collegeId,joiningDate,trim(blogTitle) as name,c.avtarimageurl from tcollegeNetwork a INNER JOIN blogTable b ON(a.collegeId = b.blogId) INNER JOIN tuser c ON(a.userid = c.userid) where b.status <> 'deleted' and a.grouptype = 'TestPreparation' order by joiningDate desc limit ".$count;

		error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result_array() as $row){
            if($instituteFlag == 1)
            {
                $row['collegeurl'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']);
                if($category == "TestPreparation")
                $row['collegeurl'] .= "/0/TestPreparation";
            }    
			if($instituteFlag == 2)
				$row['collegeurl'] = getSeoUrl($row['schoolId'],"schoolgroups",$row['name']);
			if($instituteFlag == 3)
			{
				if($row['college'] == "college")
					$row['collegeurl'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']) . '/0';
				if($row['college'] == "test")
					$row['collegeurl'] = getSeoUrl($row['collegeId'],"collegegroup",$row['name']) .'/0/TestPreparation' ;
				if($row['college'] == "school")
					$row['collegeurl'] = getSeoUrl($row['collegeId'],"schoolgroups",$row['name']) . '/0';

			}

			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);	
	}

	function updatealertstatus($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$product = $parameters['1'];
		$instituteId = $parameters['2'];
		$status = $parameters['3'];
		$userId = $parameters['4'];
		$grouptype = $parameters['5'];

		$dbHandle = $this->_loadDatabaseHandle('write');
		if($product == "collegegroup")
		{
			$table = "tcollegeNetwork";
			$column = "collegeId";
		$queryCmd = "update ". $table ." set alertMail = '".$status."' where userId = ".$userId ." and ".$column." = ".$instituteId." and grouptype = '".$grouptype ."'";
		}
		else
		{
			$table = "tschoolNetwork";
			$column = "schoolId";
		$queryCmd = "update ". $table ." set alertMail = '".$status."' where userId = ".$userId ." and ".$column." = ".$instituteId;
		}
		error_log_shiksha($product);
		$query = $dbHandle->query($queryCmd);
		$response = $dbHandle->affected_rows();
		error_log_shiksha($response);
		return $this->xmlrpc->send_response('true');	
	}

	function insertgroupnews($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$product = $parameters['1'];
		$instituteId = $parameters['2'];
		$institutetype = $parameters['3'];
		$newstype = $parameters['4'];
		$userId = $parameters['5'];
		$newstext = $parameters['6'];
		$dbHandle = $this->_loadDatabaseHandle('write');

		$college = explode(',',$instituteId);
		$numOfCollege = count($college);
		for($i = 0; $i < $numOfCollege ; $i++){
$queryCmd = "insert into groupnews values('',$college[$i],'$institutetype','$newstext','$product',now(),1,'live',$userId,'$newstype')";		
			$query = $dbHandle->query($queryCmd);
			$response = $dbHandle->affected_rows();
		}
		return $this->xmlrpc->send_response('true');	
	}

}
?>
