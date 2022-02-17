<?php


class Consultant_server extends MX_Controller {

    /*
     *  index function to recieve the incoming request
     */

    function index()
    {
		$this->dbLibObj = DbLibCommon::getInstance('AnA');
		
        //load XML RPC Libs
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('consultants/consultantconfig');

        error_log_shiksha("Entering server");

        //Define the web services method

        $config['functions']['getConsultantList'] = array('function' => 'Consultant_server.getConsultantList');

        $config['functions']['getConsultantListByKeyword'] = array('function' => 'Consultant_server.getConsultantListByKeyword');

        $config['functions']['deleteConsultant'] = array('function' => 'Consultant_server.deleteConsultant');

        $config['functions']['getConsultantData'] = array('function' => 'Consultant_server.getConsultantData');

        $config['functions']['addConsultant'] = array('function' => 'Consultant_server.addConsultant');

        $config['functions']['editConsultant'] = array('function' => 'Consultant_server.editConsultant');

        $config['functions']['checkConsultantName'] = array('function' => 'Consultant_server.checkConsultantName');

        //initialize
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    function checkConsultantName($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $consultant_id = $parameters[0];
        $consultant_name = $parameters[1];
		
	$dbHandle = $this->_loadDatabaseHandle();

	$queryCmd = "select * from LMConsultantTable, listings_main where LMConsultantTable.consultant_id = listings_main.listing_type_id and listing_type = 'consultant' and LMConsultantTable.status='live' and listings_main.status='live' and listing_title=? ";

	if($consultant_id != '-1')
	{
		$queryCmd .= " and LMConsultantTable.consultant_id !=".$dbHandle->escape($consultant_id);
	}

	$query=$dbHandle->query($queryCmd,array($consultant_name));

	if(count($query->result())>0)
	{
		$response = array('exists','string');
	}
	else
	{
		$response = array('new', 'string');
	}
        return $this->xmlrpc->send_response($response);
    }


    function addConsultant($request)
    {
	$parameters = $request->output_parameters(FALSE,FALSE);
	error_log(print_r($parameters,true). "        SHIRISH");
	$consultant_name = $parameters[0];
	$consultant_email = $parameters[1];
	$consultant_mobile = $parameters[2];
	$consultant_address = $parameters[3];
	$consultant_branceOfficeCity = $parameters[4];
	$consultant_categories = $parameters[5];
	$consultant_countries = $parameters[6];
	$consultant_startDate = $parameters[7];
	$consultant_endDate = $parameters[8];
	$consultant_fundSource = $parameters[9];
	$userId = $parameters[10];

	$dbHandle = $this->_loadDatabaseHandle('write');


	$data=array('consultant_branceOfficeCity'=>$consultant_branceOfficeCity,'consultant_email'=>$consultant_email,'consultant_mobile'=>$consultant_mobile,'consultant_address'=>$consultant_address,'leadStartDate'=>$consultant_startDate,'leadEndDate'=>$consultant_endDate,'status'=>'live');
	$queryCmd = $dbHandle->insert_string('LMConsultantTable',$data);
	error_log("Shirish : ".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	$consultant_id = $dbHandle->insert_id();

	$data=array('listing_type'=>'consultant','listing_type_id'=>$consultant_id,'listing_title'=>$consultant_name,'username'=>$userId);
	$queryCmd = $dbHandle->insert_string('listings_main',$data);
	$query = $dbHandle->query($queryCmd);
	error_log("Shirish : ".$queryCmd);

	foreach($consultant_categories as $key)
	{
		$data=array('category_id'=>$key , 'consultant_id' => $consultant_id); 
		$queryCmd = $dbHandle->insert_string('LMConsultantCategoryTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	foreach($consultant_countries as $key)
	{
		$data=array('country_id'=>$key , 'consultant_id' => $consultant_id); 
		$queryCmd = $dbHandle->insert_string('LMConsultantCountryTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	foreach($consultant_fundSource as $key)
	{
		$data=array('FundSourceInterest'=>$key , 'consultant_id' => $consultant_id); 
		$queryCmd = $dbHandle->insert_string('LMConsultantFundSourceTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	$response = array('Added', 'string');
        return $this->xmlrpc->send_response($response);
    }

    function editConsultant($request)
    {
	$parameters = $request->output_parameters(FALSE,FALSE);
	error_log(print_r($parameters,true). "        SHIRISH");
	$consultant_name = $parameters[0];
	$consultant_email = $parameters[1];
	$consultant_mobile = $parameters[2];
	$consultant_address = $parameters[3];
	$consultant_branceOfficeCity = $parameters[4];
	$consultant_categories = $parameters[5];
	$consultant_countries = $parameters[6];
	$consultant_startDate = $parameters[7];
	$consultant_endDate = $parameters[8];
	$consultant_fundSource = $parameters[9];
	$userId = $parameters[10];
	$consultant_id = $parameters[11];

	$dbHandle = $this->_loadDatabaseHandle('write');

	$updateListingsMainQuery = "update listings_main set status='history' where listing_type_id=? and listing_type='consultant' and status='live'";
	error_log(" Shirish ".$updateListingsMainQuery);
	$query=$dbHandle->query($updateListingsMainQuery, array($consultant_id));

	$updateConsultantTable = "update LMConsultantTable set status='history' where consultant_id=? and status='live'";
	error_log(" Shirish ".$updateConsultantTable);
	$query=$dbHandle->query($updateConsultantTable, array($consultant_id));

	$versionQuery = "select max(version)+1 as version from LMConsultantTable where consultant_id=?;";
	$query=$dbHandle->query($versionQuery, array($consultant_id));
	$version = "1";	
	foreach($query->result_array() as $row)
	{
		// error_log(print_r($row,true). " SHIRISH");
		$version = $row['version'];
	}

	$data=array('consultant_id'=>$consultant_id,'consultant_branceOfficeCity'=>$consultant_branceOfficeCity,'consultant_email'=>$consultant_email,'consultant_mobile'=>$consultant_mobile,'consultant_address'=>$consultant_address,'leadStartDate'=>$consultant_startDate,'leadEndDate'=>$consultant_endDate,'status'=>'live','version'=>$version);
	$queryCmd = $dbHandle->insert_string('LMConsultantTable',$data);
	error_log("Shirish : ".$queryCmd);
	$query = $dbHandle->query($queryCmd);

	$data=array('listing_type'=>'consultant','listing_type_id'=>$consultant_id,'listing_title'=>$consultant_name,'username'=>$userId,'version'=>$version, 'status'=>'live');
	$queryCmd = $dbHandle->insert_string('listings_main',$data);
	$query = $dbHandle->query($queryCmd);
	error_log("Shirish : ".$queryCmd);

	foreach($consultant_categories as $key)
	{
		$data=array('category_id'=>$key , 'consultant_id' => $consultant_id,'version'=>$version); 
		$queryCmd = $dbHandle->insert_string('LMConsultantCategoryTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	foreach($consultant_countries as $key)
	{
		$data=array('country_id'=>$key , 'consultant_id' => $consultant_id, 'version'=>$version); 
		$queryCmd = $dbHandle->insert_string('LMConsultantCountryTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	foreach($consultant_fundSource as $key)
	{
		$data=array('FundSourceInterest'=>$key , 'consultant_id' => $consultant_id , 'version'=>$version); 
		$queryCmd = $dbHandle->insert_string('LMConsultantFundSourceTable',$data);
		error_log("Shirish : ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
	}

	$response = array('Added', 'string');
        return $this->xmlrpc->send_response($response);
    }


    function getConsultantData($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $consultant_id = $parameters[0];

	$dbHandle = $this->_loadDatabaseHandle();

	$consultantDetailQuery = 'select listings_main.listing_title, LMConsultantTable.consultant_id, LMConsultantTable.consultant_email , LMConsultantTable.consultant_mobile , LMConsultantTable.consultant_branceOfficeCity , LMConsultantTable.consultant_address, date(LMConsultantTable.leadStartDate) as leadStartDate , date(LMConsultantTable.leadEndDate) as leadEndDate,  group_concat(distinct LMConsultantCategoryTable.category_id) as categories, group_concat(distinct LMConsultantCountryTable.country_id) as countries, group_concat(distinct LMConsultantFundSourceTable.FundSourceInterest) as fundSources  from listings_main inner join LMConsultantTable on listings_main.listing_type_id = LMConsultantTable.consultant_id and listings_main.listing_type="consultant" left join LMConsultantCategoryTable on LMConsultantTable.consultant_id = LMConsultantCategoryTable.consultant_id and LMConsultantTable.version= LMConsultantCategoryTable.version left join  LMConsultantCountryTable on LMConsultantTable.consultant_id = LMConsultantCountryTable.consultant_id and LMConsultantCountryTable.version = LMConsultantTable.version left join LMConsultantFundSourceTable on LMConsultantFundSourceTable.consultant_id = LMConsultantTable.consultant_id and LMConsultantTable.version = LMConsultantFundSourceTable.version where LMConsultantTable.consultant_id=? and listings_main.status="live" and LMConsultantTable.status="live" group by LMConsultantTable.consultant_id';

	$query=$dbHandle->query($consultantDetailQuery, array($consultant_id));
	$msgArray = array();
	// error_log(count($query->result_array()));
        foreach($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');

        return $this->xmlrpc->send_response($response);
    }


    function deleteConsultant($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $consultant_id = $parameters[0];

	$dbHandle = $this->_loadDatabaseHandle('write');

	$updateListingsMainQuery = "update listings_main set status='deleted' where listing_type_id=? and listing_type='consultant' and status='live'";
	$query=$dbHandle->query($updateListingsMainQuery, array($consultant_id));

	$updateConsultantTable = "update LMConsultantTable set status='deleted' where consultant_id=? and status='live'";
	$query=$dbHandle->query($updateConsultantTable, array($consultant_id));

	$response = array('Deleted', 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getConsultantListByKeyword($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $keyword = $parameters[0];
        $startCount= $parameters[1];
        $endCount= $parameters[2];
        $sortField= $parameters[3];

	$dbHandle = $this->_loadDatabaseHandle();
 
        $query = "select LMConsultantTable.consultant_id as consultantId, date(leadStartDate) as startDate, date(leadEndDate) as endDate, listing_title as name, displayname, countryCityTable.city_name as cityName from LMConsultantTable inner join listings_main on LMConsultantTable.consultant_id = listings_main.listing_type_id inner join tuser on listings_main.username=tuser.userid inner join countryCityTable on LMConsultantTable.consultant_branceOfficeCity = countryCityTable.city_id ";
        $queryClause = " where listings_main.status='live' and listings_main.listing_type='consultant' and LMConsultantTable.status='live'";

        if($keyword != '+')
        {
		$queryClause.= " and listings_main.listing_title like \"%".$dbHandle->escape_like_str($keyword)."%\"";
        }

	if($sortField == '')
	{
		$sortClause = " order by modify_time desc ";
	}
	else
	{
		$sortClause = " order by ".$dbHandle->escape($sortField)." ";
	}


        $queryCmd = $query.$queryClause.$sortClause." limit ?,?";
        error_log("Shirish ".$queryCmd);
		$query=$dbHandle->query($queryCmd,array((int)$startCount,(int)$endCount));		
	$msgArray = array();
	// error_log(count($query->result_array()));
        foreach($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        // error_log(print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }


    function getConsultantList($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $cityId = $parameters[0];
        $countryId= $parameters[1];
        $categoryId= $parameters[2];
        $startCount= $parameters[3];
        $endCount= $parameters[4];
        $sortField= $parameters[5];

	$dbHandle = $this->_loadDatabaseHandle();
 
        $query = "select LMConsultantTable.consultant_id as consultantId, date(leadStartDate) as startDate, date(leadEndDate) as endDate, listing_title as name, displayname, countryCityTable.city_name as cityName from LMConsultantTable inner join listings_main on LMConsultantTable.consultant_id = listings_main.listing_type_id inner join tuser on listings_main.username=tuser.userid inner join countryCityTable on LMConsultantTable.consultant_branceOfficeCity = countryCityTable.city_id ";
        $queryClause = " where listings_main.status='live' and listings_main.listing_type='consultant' and LMConsultantTable.status='live' ";

        if($cityId != '-1')
        {
                $queryClause .= " and LMConsultantTable.consultant_branceOfficeCity=".$dbHandle->escape($cityId)." ";
        }

        if($countryId!= '-1')
        {
                $query .= " inner join LMConsultantCountryTable on LMConsultantTable.consultant_id = LMConsultantCountryTable.consultant_id and LMConsultantCountryTable.version=LMConsultantTable.version ";
                $queryClause .= " and LMConsultantCountryTable.country_id =".$dbHandle->escape($countryId)." ";
        }

        if($categoryId != '-1')
        {
                $query .= " inner join LMConsultantCategoryTable on LMConsultantTable.consultant_id = LMConsultantCategoryTable.consultant_id and LMConsultantCategoryTable.version = LMConsultantTable.version ";
                $queryClause .= " and LMConsultantCategoryTable.category_id=".$dbHandle->escape($categoryId)." ";
        }
	if($sortField == '')
	{
		$sortClause = " order by modify_time desc ";
	}
	else
	{
		$sortClause = " order by ".$dbHandle->escape($sortField)." ";
	}
	

        $queryCmd = $query.$queryClause.$sortClause." limit ?,?";
        error_log("Shirish ".$queryCmd);
	$query=$dbHandle->query($queryCmd,array((int)$startCount,(int)$endCount));
	$msgArray = array();
	error_log(count($query->result_array()));
        foreach($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        error_log(print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }
}

?>
