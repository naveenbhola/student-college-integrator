<?php
class ListingModel extends MY_Model {
    function __construct(){
		parent::__construct('Listing');
		$this->db = $this->getReadHandle();
    }

    private function getEntityRelations(){
        $tableName = '';
        $entityFieldName = '';
        $relations = array();
        $this->load->library('listingconfig');
        $dbConfig = array( 'hostname'=>'localhost');
        $this->listingconfig->getDbConfig_test($appId,$dbConfig);
        $this->load->database($dbConfig);
        return $relations;
    }

    function getDbHandle(){
        return $this->db;
    }

    function addWikiSections($dbHandle,$listing_type_id,$listing_type,$wikiSections,$version,$status){
        $query = "select * from  listing_fields_table where listing_type= ? order by listing_fields_table.formPageOrder";
        $fields = $dbHandle->query($query,array($listing_type));        
        foreach($fields->result() as $field)
        {
            if(isset($wikiSections[$field->key_name]) && strlen(trim($wikiSections[$field->key_name]))>0){
                $data =array();
                $data = array(
                        'listing_type'=>$listing_type,
                        'listing_type_id'=>$listing_type_id,
                        'caption'=>$field->caption,
                        'attributeValue'=>$wikiSections[$field->key_name],
                        'isPaid'=>$field->isPaid,
                        'keyId'=>$field->keyId,
                        'status'=>$status,
                        'version'=>$version
                        );
                $queryCmd = $dbHandle->insert_string('listing_attributes_table',$data);
                $query = $dbHandle->query($queryCmd);
                unset($wikiSections[$field->key_name]);
            }
        }
        if(count($wikiSections['user_fields']) > 0){
            foreach($wikiSections['user_fields'] as $tmpWiki ){
                if(strlen(trim($tmpWiki['caption'])) > 0 && strlen(trim($tmpWiki['value']))>0){
                    $data =array();
                    $data = array(
                        'listing_type'=>$listing_type,
                        'listing_type_id'=>$listing_type_id,
                        'caption'=>trim($tmpWiki['caption']),
                        'attributeValue'=>trim($tmpWiki['value']),
                        'isPaid'=>'yes',
                        'keyId'=>'-1',
                        'status'=>$status,
                        'version'=>$version
                    );
                    $queryCmd = $dbHandle->insert_string('listing_attributes_table',$data);
                    $query = $dbHandle->query($queryCmd);
                }
            }
        }
    }

    function getContactDetails($dbHandle, $contact_details_id){
        $queryCmd = "select * from listing_contact_details where contact_details_id = ? ";
        $query = $dbHandle->query($queryCmd,array($contact_details_id));
        foreach($query->result_array() as $row){
            return $row;
        }
    }

    function getLocationOnCourses($dbHandle, $institute_id) {
		$courseIdsQuery = 'SELECT DISTINCT course_id FROM course_details cd WHERE cd.status IN ("live", "draft") AND cd.institute_id = ? ';
		$query 			= $dbHandle->query($courseIdsQuery,array($institute_id));
        
		$courseIds = array();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$courseIds[] = $row->course_id;
			}
		}
		$instituteLocationIds = array();
		if(!empty($courseIds)){
			$locationQuery = "SELECT DISTINCT cla.institute_location_id FROM course_location_attribute cla WHERE cla.attribute_type = 'Head Office' AND cla.course_id IN (?) AND cla.status IN ('live', 'draft') ORDER BY institute_location_id asc";
			$query 			= $dbHandle->query($locationQuery,array($courseIds));
			$instituteLocationIds = array();
			if($query->num_rows() > 0){
				foreach ($query->result() as $row) {
					$instituteLocationIds[] = $row->institute_location_id;
				}
			}
		}
		if(!empty($instituteLocationIds)){
			return $instituteLocationIds;
		} else {
			return "";
		}
    }
	
function getLocationWiseContactDetails($dbHandle, $institute_location_ids, $listingType, $listingTypeID, $version){
		return ; // code not in use
        if($institute_location_ids == "") return;
        
	// $queryCmd = "select * from listing_contact_details where institute_location_id in (".$institute_location_ids.") AND listing_type = '".$listingType."' AND listing_type_id = '".$listingTypeID."' AND version = '".$version."' order by institute_location_id";
        $queryCmd = "select * from listing_contact_details where institute_location_id in ($institute_location_ids) AND listing_type = ? AND listing_type_id = ? AND version = ? order by FIELD(institute_location_id, $institute_location_ids) ";
        $query = $dbHandle->query($queryCmd,array($listingType,$listingTypeID,$version));
        
        $locationWiseContactDetails = array();
        foreach($query->result_array() as $rowTemp){
            $locationWiseContactDetails[] =
                            array('contact_details_id'=> $rowTemp['contact_details_id'],
                                'contact_person'=> $rowTemp['contact_person'],
                                'contact_email'=> $rowTemp['contact_email'],
                                'contact_main_phone'=> $rowTemp['contact_main_phone'],
                                'contact_cell'=> $rowTemp['contact_cell'],
                                'contact_alternate_phone'=> $rowTemp['contact_alternate_phone'],
                                'contact_fax'=> $rowTemp['contact_fax'],
                                'website'=> $rowTemp['website'],
                                'listing_type'=> $rowTemp['listing_type'],
                                'listing_type_id'=> $rowTemp['listing_type_id'],
                                'institute_location_id'=> $rowTemp['institute_location_id']
                            );
        }
        
        // error_log("\n\n Query : ".$queryCmd."\n\nlocationWiseContactDetails = ".print_r($locationWiseContactDetails,true),3,'/home/infoedge/Desktop/log.txt');

        return ($locationWiseContactDetails);
    }

    function getWikiDetailsForListing($dbHandle, $listing_type,$listing_type_id,$version ='1'){
        $queryCmd = "select listing_attributes_table.* , listing_fields_table.key_name from listing_attributes_table left join listing_fields_table on listing_attributes_table.keyId = listing_fields_table.keyId where listing_type_id = ? and listing_attributes_table.listing_type = ? and version = ? ";
        $query = $dbHandle->query($queryCmd,array($listing_type_id,$listing_type,$version));
        return $query->result_array();
    }

    function getParentCatId($dbHandle,$catId)
    {
        if($catId <= 1){
            return -1;
        }
        //connect DB
        if($dbHandle == ''){
            $dbHandle = $this->getDbHandle();
        }
        $queryCmd="select parentId from categoryBoardTable where boardId = ? ";
        // error_log_shiksha($queryCmd);
        $query=$dbHandle->query($queryCmd,array($catId));
        if(count($query->result_array())>0)
        {
            foreach($query->result_array() as $row){
                $parentId = $row['parentId'];
            }
        }
        if(strlen($parentId) > 0){
            return $parentId;
        }
        else{
            return -1;
        }
    }

    function publishCountrySelection($dbHandle,$params)
    {
        $countryid = $params[1];

        $querycmd = "select itemtype,priority from tSetIds where status = 'draft' and country = ?";
        // error_log($querycmd);
        $query =  $dbHandle->query($querycmd,array($countryid));

        $querycmd = "select itemtype,priority from tSetIds where status = 'live' and country = ?";
        // error_log($querycmd);
        $query1 =  $dbHandle->query($querycmd,array($countryid));
        if($query->num_rows() <= 0 && $query1->num_rows() > 0)
        {
            $errormsg = "Options are already published for the selection";
        }
        if($query->num_rows() <= 0 && $query1->num_rows() <= 0)
        {
            $errormsg = "Please add options for the country";
        }

        if($query->num_rows() > 0)
        {
            if($query1->num_rows() > 0)
            {
                $institutearray = array();
                $resarray = $query->result_array();
                $resarray1 = $query1->result_array();
                foreach($resarray as $row)
                {
                    foreach($resarray1 as $row1)
                    {
                        // error_log($row['itemtype'].'ITEMTYPE');
                        // error_log($row1['itemtype'].'ITEMTYPE');
                        // error_log($row['priority'].'ITEMTYPE');
                        // error_log($row1['priority'].'ITEMTYPE');
                        if($row['itemtype'] == $row1['itemtype'] && $row['priority'] == $row1['priority'])
                        {
                            $querycmd = "update tSetIds set status = 'deleted' where status = 'live' and country = ? and itemtype = ? and priority = ?";
                            // error_log($querycmd);
                            $query =  $dbHandle->query($querycmd,array($countryid,$row['itemtype'],$row['priority']));
                        }
                    }
                }

            }
            $queryCmd = "update tSetIds set status = 'live' where status = 'draft' and country = ?";
            // error_log($queryCmd);
            $query =  $dbHandle->query($queryCmd,array($countryid));
            $errormsg = "Published Successfully";
        }
        return $errormsg;
    }
	/*
	 * NOT IN USE
	 */
    function publishAll($dbHandle,$params)
    {
		return array();
		error_log('Code Usability Check:listingmodel: publishAll', 3, '/tmp/listing_server.log'); 
        $categoryid = $params[1];
        $institutearr = $params[2];
        // error_log(print_r($institutearr,true).'ARRAY');
        $instituteids = '';
        for($i = 1;$i <= 20;$i++)
        {
        if($instituteids == '')
        $instituteids = $institutearr[$i];
        else
        $instituteids .= ',' . $institutearr[$i];
            $status = json_decode($this->getMetaInfoForInstitutes($dbHandle,$institutearr[$i],$categoryid),true);
            if(isset($status['error']))
            {
              $errormsg['error'] = $status['error'];
              return $errormsg;
            }
        }

                $queryCmd = "select distinct institute_id from institute where institute_id in ($instituteids)";
                // error_log($queryCmd.'QUERY');
                $query =  $dbHandle->query($queryCmd);
                if($query->num_rows() < 20)
                {
                $errormsg['error'] = "Please select distinct institutes";
                }

                for($i = 1;$i <= 20;$i++)
                {
                    $queryCmd = "update topinstitutes set status = 'history' where priority = $i and status = 'draft' and categoryid = ?";
                    // error_log($queryCmd);
                    $query =  $dbHandle->query($queryCmd,array($categoryid));
                    $queryCmd = "update topinstitutes set status = 'deleted' where priority = $i and status = 'published' and categoryid = ?";
                    // error_log($queryCmd);
                    $query =  $dbHandle->query($queryCmd,array($categoryid));

                    $data = array(
                       'categoryid'  => $categoryid,
                       'instituteid' => $institutearr[$i],
                       'status'      => 'published',
                       'priority'    => $i,
                       'startdate'   => date('Y-m-d H:i:s')
                    );

                    $dbHandle->insert('topinstitutes', $data); 


                    // $queryCmd = "insert into topinstitutes values('',$categoryid,$institutearr[$i],'published',$i,now())";
                    // // error_log($queryCmd);
                    // $query =  $dbHandle->query($queryCmd);
                    $errormsg['msg'] = "Published Successfully";
                }
            return $errormsg;
    }
	/*
	 * NOT IN USE
	 */
    function saveCountryOption($dbHandle,$parameters)
    {
		return array();
		error_log('Code Usability Check:listingmodel: saveCountryOption', 3, '/tmp/listing_server.log'); 
        $reqarr = $parameters;

    // if add then check if same institute is present as draft on some other priority
    // check if the institute is a valid case
    // add institute

    // if update then check if same institute is present as draft on some priority
    // check if the institute is a valid case
    // deleted status for the institute on same priority
    // add institute

    //publish
//validity  and duplicacy checks
//update the previous published ones to deleted
    //for all institutes insert new entry as published status
       // error_log('REQARR'.print_r($reqarr,true));
       $itemtypeid = $reqarr['itemid'];
       $statusval = $reqarr['status'];
       $itemtype = $reqarr['itemtype'];
       // error_log($itemtypeid.'ITEMTYPEID');
       if(!is_numeric($itemtypeid))
       {
            $errormsg['error'] = "Please enter a valid ".$reqarr['itemtype'] ." id";
            return $errormsg;
       }

            // error_log($itemtype.'ITEMTYPE');
            $status = " and b.status in ('draft')";
            switch($reqarr['itemtype'])
            {
            case 'englishtestexam' :
            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and b.status='live' and a.itemtype = 'exam' and b.blogType = 'englishtestexam' $status and b.blogTypeValues = 'EnglishTest'";
            $itemtype = 'englishtestexam';
            break;

            case 'ugexam':

            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and b.status='live' and a.itemtype = 'exam' and b.blogType = 'ugexam' $status and b.blogTypeValues = 'UG'";
            $itemtype = 'ugexam';
            break;
            case 'pgexam':

            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and b.status='live' and a.itemtype = 'exam' and b.blogType = 'pgexam' $status and b.blogTypeValues = 'PG'";
            $itemtype = 'pgexam';
            break;

            case 'doctoralexam':

            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and a.itemtype = 'exam' and b.status='live' and b.blogType = 'doctoralexam' $status and b.blogTypeValues = 'Doctoral'";
            $itemtype = 'doctoralexam';
            break;
            case 'article':

            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and b.status='live' and a.itemtype = 'article' $status";
            break;
            case 'question':

            $tablename = "messageTable b";
            $clause = " a.itemid = b.msgId and a.itemtype = 'question' $status";
            break;
            case 'faq':

            $tablename = "blogTable b";
            $clause = " a.itemid = b.blogId and b.status = 'live' and a.itemtype = 'faq' $status and b.blogTypeValues = 'faq'";
            break;
            }

            // error_log($itemtype.'ITEMTYPE');
            $countryarr = explode(',',$reqarr['country']);
            for($j = 0;$j < count($countryarr);$j++)
            {
            $countryId = $countryarr[$j];

            $queryCmd = "select priority from tSetIds a, $tablename where $clause and a.itemid = ? and a.country = ?";
            // error_log($queryCmd.'ITEMTYPE');
            $query =  $dbHandle->query($queryCmd,array($itemtypeid,$countryId));
            $row = $query->row();
            if($query->num_rows() == 1)
            {
                $errormsg['error'] = $itemtype ." is already added at position " . $row->priority;
                return $errormsg;
            }

            $queryCmd = "select priority from tSetIds a, $tablename where $clause and a.country = ? and a.priority = ?";
            // error_log($queryCmd.'ITEMTYPE');
            $query =  $dbHandle->query($queryCmd,array($countryId,$reqarr['priority']));
            $row = $query->row();
            if($query->num_rows() == 1)
            {
                $errormsg['error'] = $itemtype ." is already added at position " . $row->priority;
                return $errormsg;
            }
/*
            $status = json_decode($this->getMetaInfoForInstitutes($dbHandle,$instituteid,$categoryid),true);
            if(isset($status['error']))
            {
              $errormsg['error'] = $status['error'];
              return $errormsg;
            }
*/
            if($statusval == 'update' || $statusval == "published")
            {
                $queryCmd = "update tSetIds set status = 'history' where priority = ? and status = 'draft' and category = ? and country = ? and pagename = 'country' and itemtype = ? ";
                // error_log($queryCmd);
                $errormsg['msg'] = $reqarr['itemtype'] . " updated successfully";
                $query =  $dbHandle->query($queryCmd,array($reqarr['priority'],$reqarr['category'],$countryId,$itemtype));
            }
            else
            {
                $errormsg['msg'] = $reqarr['itemtype']." added successfully";
            }

            if($statusval == "add" || $statusval == "update")
            $status = "draft";
            else
            $status = $statusval;
            if($reqarr['itemid'] != 0)
            {
                $data =array();
                $data = array(
                        'pagename'=>$reqarr['pagename'],
                        'category'=>$reqarr['category'],
                        'subcategory'=>$reqarr['subcategory'],
                        'country'=>$countryId,
                        'city'=>$reqarr['city'],
                        'itemid'=>$reqarr['itemid'],
                        'itemtype'=>$itemtype,
                        'status'=>$status,
                        'startdate'=> date("y.m.d:h:m:s"),
                        'priority'=>$reqarr['priority']
                        );
                $queryCmd = $dbHandle->insert_string('tSetIds',$data);
                // error_log($queryCmd);
                $query =  $dbHandle->query($queryCmd);
            }
            else
            {
                $errormsg['msg'] = $reqarr['itemtype']." removed successfully";
            }
            }
            return $errormsg;
    }
	/*
	 * NOT IN USE
	 */
    function saveTopOption($dbHandle,$parameters)
    {
		return array();
		error_log('Code Usability Check:listingmodel: saveTopOption', 3, '/tmp/listing_server.log'); 
        $categoryid = $parameters[1];
        $instituteid = $parameters[2];
        $statusval = $parameters[3];
        $priority = $parameters[4];

    // if add then check if same institute is present as draft on some other priority
    // check if the institute is a valid case
    // add institute

    // if update then check if same institute is present as draft on some priority
    // check if the institute is a valid case
    // deleted status for the institute on same priority
    // add institute

    //publish
//validity  and duplicacy checks
//update the previous published ones to deleted
    //for all institutes insert new entry as published status

       if(!is_numeric($instituteid))
       {
            $errormsg['error'] = "Please enter a valid institute id";
            return $errormsg;
       }
            $queryCmd = "select priority from topinstitutes a,institute b where a.instituteid = b.institute_id and a.status = 'draft' and a.categoryid = ? and instituteid = ? and b.status = 'live'";
            // error_log($queryCmd);
            $query =  $dbHandle->query($queryCmd,array($categoryid,$instituteid));
            $row = $query->row();
            if($query->num_rows() == 1)
            {
                $errormsg['error'] = "Institute is already added at position " . $row->priority;
                return $errormsg;
            }

            $status = json_decode($this->getMetaInfoForInstitutes($dbHandle,$instituteid,$categoryid),true);
            if(isset($status['error']))
            {
              $errormsg['error'] = $status['error'];
              return $errormsg;
            }

            if($statusval == 'update' || $statusval == "published")
            {
                $queryCmd = "update topinstitutes set status = 'history' where priority = ? and status = 'draft' and categoryid = ?";
            // error_log($queryCmd);
            $errormsg['msg'] = "Institute updated successfully";
                $query =  $dbHandle->query($queryCmd,array($priority,$categoryid));
            }
            else
            {
            $errormsg['msg'] = "Institute added successfully";
            }

            if($statusval == "add" || $statusval == "update")
            $status = "draft";
            else
            $status = $statusval;

            $data = array(
                       'categoryid'  => $categoryid,
                       'instituteid' => $instituteid,
                       'status'      => $status,
                       'priority'    => $priority,
                       'startdate'   => date('Y-m-d H:i:s')
                    );

            $dbHandle->insert('topinstitutes', $data); 

            // $queryCmd = "insert into topinstitutes values('',$categoryid,$instituteid,'$status',$priority,now())";
            // // error_log($queryCmd);
            // $query =  $dbHandle->query($queryCmd);
            return $errormsg;
    }

    function getCmsCountryOptions($dbHandle,$countryId)
    {
        $queryCmd = "select itemid,itemtype as itemtype,priority,a.status from tSetIds a where a.country = ? and a.status in ('draft','live') order by a.itemtype,a.priority asc";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($countryId));
        $institutearray = array();
        $resarray = $query->result_array();
        $i = 0;
        $priorityids = '';
        $j = 0;
        foreach($resarray as $row)
        {
                $valname = strtolower($row['itemtype']) . 'id' . $row['priority'] ;
                if($row['status'] == 'live')
                {
                    $valname = 'name' .strtolower($row['itemtype'].'id'.$row['priority']);

                }
                // error_log($valname);
                $arrval[$valname] = $row['itemid'];
        }
        return $arrval;
    }
	/*
	 * NOT IN USE
	 */
    function getCmsTopInstitutes($dbHandle,$categoryid)
    {
		return array();
		error_log('Code Usability Check:listingmodel: getCmsTopInstitutes', 3, '/tmp/listing_server.log'); 
        $queryCmd = "select instituteid,institute_name,priority from topinstitutes a,institute b where a.instituteid = b.institute_id and a.status = 'draft' and a.categoryid = ? and b.status = 'live' and priority between 1 and 20 order by priority asc";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($categoryid));
        $institutearray = array();
        $resarray = $query->result_array();
        $i = 0;
        $priorityids = '';
        foreach($resarray as $row)
        {
            $institutearray[$row['priority']] = $row;
            $priorityClause = '';
            if($query->num_rows() > 0)
            {
                if($priorityids == '')
                    $priorityids = $row['priority'];
                else
                    $priorityids = $priorityids . ',' . $row['priority'];

                $priorityClause = " and priority not in ($priorityids)";
            }
        }

        $queryCmd = "select instituteid,institute_name,priority from topinstitutes a,institute b where a.instituteid = b.institute_id and a.status = 'published' and a.categoryid = ? and b.status = 'live' and priority between 1 and 20 $priorityClause order by priority asc";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($categoryid));
        $resarray = $query->result_array();

        foreach($resarray as $row)
        {
            $institutearray[$row['priority']] = $row;
        }
        return $institutearray;
    }

	/*
	 * NOT IN USE
	 */
    function getMetaInfoForInstitutes($dbHandle,$instituteid,$categoryid){
		return array();
		error_log('Code Usability Check:listingmodel: getMetaInfoForInstitutes', 3, '/tmp/listing_server.log'); 
        $categoryid=$this->getChildIds($dbHandle,$categoryid);
        /* $institutes = split(',',$institutes);
           $numListings = count($institutes);
           $instituteids = '';
           for($i = 0; $i < $numListings ; $i++){
           if($instituteids == '')
           $instituteids = $institutes[$i];
           else
           $instituteids = $instituteids . ',' .$institutes[$i];
           }
         */
        //$queryCmd = "select institute_name from institute where status = 'live' and institute_id in($instituteids)";
        //Either the id doesn't belong to the india
        //Institute doesn't belong to the category (no course)
        //Or expired
        if(!is_numeric($instituteid))
        {
            $errormsg['error'] = "Please enter a valid institute id";
            return json_encode($errormsg);
        }
        $queryCmd = "select status,institute_name from institute where institute_id = ?";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($instituteid));

        $row1 = $query->row();
        $institute_name = $row1->institute_name;
        $errormsg = array();
        if($query->num_rows() == 0)
        {
            $errormsg['error'] = "Please enter a valid institute id";
            return json_encode($errormsg);
        }
        $queryCmd = "select city_id from institute_location_table where status = 'live' and institute_id = ? and country_id = 2";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($instituteid));
        if($query->num_rows() == 0)
        {
            $errormsg['error'] = "Institute doesn't belong to india";
            return json_encode($errormsg);
        }
        $queryCmd = "select institute_id from course_details,listing_category_table where course_details.status = 'live' and course_details.institute_id = ? and  listing_category_table.listing_type_id = course_details.course_id and listing_category_table.listing_type = 'course' and listing_category_table.category_id in($categoryid)";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($instituteid));
        if($query->num_rows() == 0)
        {
            $errormsg['error'] = "This Institute has no course in the selected category. Please enter another Institute ID";
            return json_encode($errormsg);
        }
        $queryCmd = "select status,institute_name from institute where institute_id = ? and status = 'live'";
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd,array($instituteid));
        $row1 = $query->row();
        $institute_name = $row1->institute_name;
        $errormsg = array();
        if($query->num_rows() == 0)
        {
            $errormsg['error'] = "The Institute listing for this Id has expired/disabled";
            return json_encode($errormsg);
        }
        $errormsg['institute_name'] = $institute_name;
        return json_encode($errormsg);
    }

    function getMetaInfo($dbHandle,$listings,$status){
		return ; // Code not in use
        // error_log(print_r($listings,true));
        $andClauses = array();
        $numListings = count($listings);
        for($i = 0; $i < $numListings ; $i++){
            $versionClause ="";
            if(isset($listings[$i]['version']) && is_int($listings[$i]['version'])){
                $versionClause = ' and version in ('.$listings[$i]['version'].') ';
            }
            $andClauses[$i] = ' ( listing_type = "'.$listings[$i]['type'].'" and listing_type_id = "'.$listings[$i]['typeId'].'" '.$versionClause.' ) ';
        }
        $finalClause =  implode(" OR ",$andClauses);
        if(isset($status) && $status!=""){
            $statusClause = " status in ($status) and ";
        }
        $queryCmd = 'select listing_type, listing_type_id, username as userId, status,  contact_details_id ,subscriptionId , version  from listings_main where '.$statusClause .'( '.$finalClause.' )';
        // error_log($queryCmd);
        $query =  $dbHandle->query($queryCmd);
        return $query->result_array();
    }

    function getCurentMediaForListing($dbHandle, $listing_type,$listing_type_id){
        $queryCmd = "select media_id , media_type from listing_media_table where version = (select max(version) from listing_media_table where type= ? and type_id = ? and ( status ='live' or status = 'draft' )) and type= ?  and type_id = ? ";
        $query = $dbHandle->query($queryCmd,array($listing_type,$listing_type_id,$listing_type,$listing_type_id));
        $retArr = array();
        foreach($query->result_array() as $mediaRow){
            $retArr[$mediaRow['media_type']][$mediaRow['media_id']] = 'addition';
        }
        return $retArr;
    }

    function getMediaForListingVersion($dbHandle, $listing_type,$listing_type_id,$version){
        $queryCmd = "select media_id , media_type from listing_media_table where version = ? and type= ? and type_id = ? ";
        $query = $dbHandle->query($queryCmd,array($version,$listing_type,$listing_type_id));
        $retArr = array();
        foreach($query->result_array() as $mediaRow){
            $retArr[$mediaRow['media_type']][$mediaRow['media_id']] = 'addition';
        }
        return $retArr;
    }

    function getMediaDetailsForListings($dbHandle, $listing_type,$listing_type_id,$version){
        if($listing_type == "course") {
            $queryCmd = "select * from listing_media_table, institute_uploaded_media, course_details where listing_media_table.version = ? and type= ? and type_id = ? and listing_media_table.media_type=institute_uploaded_media.media_type and listing_media_table.media_id=institute_uploaded_media.media_id and institute_uploaded_media.status !='deleted' and course_details.course_id= ? and course_details.institute_id=institute_uploaded_media.listing_type_id and course_details.version= ? ";
            $query = $dbHandle->query($queryCmd,array($version,$listing_type,$listing_type_id,$listing_type_id,$version));
        }else{
            $queryCmd = "select * from listing_media_table, institute_uploaded_media where version = ? and type= ? and type_id = ? and listing_media_table.media_type=institute_uploaded_media.media_type and listing_media_table.media_id=institute_uploaded_media.media_id and institute_uploaded_media.status !='deleted' and institute_uploaded_media.listing_type_id= ? ";
            $query = $dbHandle->query($queryCmd,array($version,$listing_type,$listing_type_id,$listing_type_id));
        }

        return $query->result_array();
    }

    function getCoursesForListing($dbHandle, $type ,$instituteId,$version,$status = "",$get_course_reach = "yes"){
        if($status == ""){
            $status = "'live','draft'";
        }
        $queryCmd = " select courseTitle,course_id,course_type, course_level , course_level_1, course_level_2, duration_value, duration_unit, course_details.approvedBy, group_concat(course_details.status) as status, viewCount,course_order,listing_seo_url, listings_main.pack_type as course_pack_type from course_details,listings_main where institute_id = ? and course_details.status in ($status) and course_details.version = listings_main.version and listings_main.listing_type='course' and listings_main.listing_type_id = course_id group by course_id order by  course_order";
        $query = $dbHandle->query($queryCmd,array($instituteId));
        $result_array = $query->result_array();
        foreach($result_array as $row) {
		$course_ids_array[] = $row['course_id'];	
        } 
        if(count($course_ids_array) >0 && $get_course_reach == 'yes') {
	$course_reach_array = $this->getCourseReachForCourses($course_ids_array,$status);
        foreach($result_array as $key=>$result) {
                //error_log('percentage_aditya '.print_r($course_reach_array,true));
		if(array_key_exists($result['course_id'],$course_reach_array)) {
			$result_array[$key]['CourseReach'] = $course_reach_array[$result['course_id']];
                }
        }
        }
        return $result_array;
    }
	/*
	 * NOT IN USE
	 */
    function getCourseReachForCourses($course_ids_array = array(),$status = 'live') {
		return array();
		error_log('Code Usability Check:listingmodel: getCourseReachForCourses', 3, '/tmp/listing_server.log'); 
        if(count($course_ids_array) == 0 || empty($status)) {
		return array();
	}
	$query_to_get_ldb_course_id = "SELECT clientCourseID,CourseReach FROM clientCourseToLDBCourseMapping, ".
					      "tCourseSpecializationMapping WHERE LDBCourseID=SpecializationId AND ".
                                              "clientCourseID IN (".implode(',',$course_ids_array).") AND ".
                                              "clientCourseToLDBCourseMapping.status IN ($status) ".
                                              "AND tCourseSpecializationMapping.status = 'live' GROUP BY clientCourseID";

        // error_log('percentage_adityaaaaaa '.$query_to_get_ldb_course_id);
        $query = $this->db->query($query_to_get_ldb_course_id);
        $course_reach_array = array();
	foreach($query->result_array() as $row) {
		$course_reach_array[$row['clientCourseID']] = $row['CourseReach'];
        }

	return $course_reach_array;
    }
    function addMediaElements($dbHandle,$listing_type,$listing_type_id,$newMedia,$version,$status){
        foreach($newMedia as $mediaType=>$mediaElem){
            foreach($mediaElem as $mediaId=>$action){
                $data =array();
                $data = array(
                        'type'=>$listing_type,
                        'type_id'=>$listing_type_id,
                        'media_type'=>$mediaType,
                        'media_id'=>$mediaId,
                        'version'=>$version,
                        'status'=>$status
                        );
                $queryCmd = $dbHandle->insert_string('listing_media_table',$data);
                $query = $dbHandle->query($queryCmd);
            }
        }
    }


function addTopCompany($dbHandle,$listing_type,$listing_type_id,$newMedia,$old_version,$version,$status,$update){

if ( $update == 1)
{

    foreach( $newMedia as $key => $value)
    {
        if( $key == 5)
        $temp = $value;
    }

    if($temp == "no")
    $update= 2;


}
//error_log(print_r("inside server",true),3,"/home/naukri/Desktop/nothing.log");

if( $update == 1)
{
            $instituteId=0;
            $logoo = array();
            $orderr= array();
            $ccount=0;
            foreach( $newMedia as $key => $value)
            {
                    if( $key == 0)
                    {
                            foreach( $value as $k => $v)
                            {
                                 $logoo[$k]= $v;
                                 $ccount++;

                            }
                    }
                    if( $key == 3)
                    {
                            foreach( $value as $k => $v)
                            {
                                $orderr[$k]= $v;
                            }
                    }

                    if( $key == 4)
                    {
                        foreach( $value as $k => $v)
                            {
                                $instituteId= $v;



                            }
                    }





            }
            for( $ik =0; $ik < $ccount; $ik++ )
            {
                 $data = array(
                            'listing_type'=>$listing_type,
                            'listing_id'=>$listing_type_id,
                            'company_order'=>$orderr[$ik],
                            'logo_id'=>$logoo[$ik],
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$instituteId,
                            'linked'=>"yes"
                        );
                $queryCmd = $dbHandle->insert_string('company_logo_mapping',$data);
                $query = $dbHandle->query($queryCmd);

             }



}// if update==1 ends
if ( $update == 0)
{
        // error_log(print_r("first",true),3,"/home/naukri/Desktop/head.log");
        $queryCmd = "select * from company_logo_mapping where version = ? and listing_type= ? and listing_id = ? ";
        $query = $dbHandle->query($queryCmd,array($old_version,$listing_type,$listing_type_id));
        foreach ($query->result() as $row){

                          $data = array(
                            'listing_type'=>$row->listing_type,
                            'listing_id'=>$row->listing_id,
                            'company_order'=>$row->company_order,
                            'logo_id'=>$row->logo_id,
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$row->institute_id,
                            'linked'=>"yes"
                            );
                $queryC = $dbHandle->insert_string('company_logo_mapping',$data);
                $queryy = $dbHandle->query($queryC);
                // error_log(print_r("second",true),3,"/home/naukri/Desktop/head.log");

                                            }


}

if ($update == 2)
    {

        $queryCmd = "select * from company_logo_mapping where version = ? and listing_type= ? and listing_id = ? ";
        $query = $dbHandle->query($queryCmd,array($old_version,$listing_type,$listing_type_id));
        foreach ($query->result() as $row){

                          $data = array(
                            'listing_type'=>$row->listing_type,
                            'listing_id'=>$row->listing_id,
                            'company_order'=>$row->company_order,
                            'logo_id'=>$row->logo_id,
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$row->institute_id,
                            'linked'=>"no"
                             );
                $queryC = $dbHandle->insert_string('company_logo_mapping',$data);
                $queryy = $dbHandle->query($queryC);
                // error_log(print_r("second",true),3,"/home/naukri/Desktop/head.log");

                                            }

    }

}

function addTopHeader($dbHandle,$listing_type,$listing_type_id,$newMedia,$old_version,$version,$status,$update){

if ( $update == 1)
{

    foreach( $newMedia as $key => $value)
    {
        if( $key == 7)
        $temp = $value;
    }

    if($temp == "no")
    $update= 2;


}

if( $update == 1)
{
            $instituteId=0;
            $thumbURL= array();
            $largeURL= array();
            $name= array();
            $orderr= array();
            $ccount=0;
            foreach( $newMedia as $key => $value)
            {

                    if( $key == 2)
                    {
                            foreach( $value as $k => $v)
                            {
                                $orderr[$k]= $v;
                                $ccount++;

                            }
                    }

                     if( $key == 3)
                    {
                            foreach( $value as $k => $v)
                            {
                                $thumbURL[$k]= $v;
                            }
                    }

                     if( $key == 4)
                    {
                            foreach( $value as $k => $v)
                            {
                                $largeURL[$k]= $v;
                            }
                    }

                     if( $key == 5)
                    {
                            foreach( $value as $k => $v)
                            {
                                $name[$k]= $v;
                            }
                    }



                    if( $key == 6)
                    {
                        foreach( $value as $k => $v)
                            {
                                $instituteId= $v;



                            }


                    }


            }
            for( $ik =0; $ik < $ccount; $ik++ )
            {
                 $data = array(
                            'name'=>$name[$ik],
                            'full_url'=>$largeURL[$ik],
                            'thumb_url'=>$thumbURL[$ik],
                            'header_order'=>$orderr[$ik],
                            'listing_id'=>$listing_type_id,
                            'listing_type'=>$listing_type,
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$instituteId,
                            'linked'=>"yes"
                        );
                $queryCmd = $dbHandle->insert_string('header_image',$data);
                $query = $dbHandle->query($queryCmd);

             }



}// if update==1 ends
if ( $update == 0)
{
        $queryCmd = "select * from header_image where version = ? and listing_type= ? and listing_id = ? ";
        $query = $dbHandle->query($queryCmd,array($old_version,$listing_type,$listing_type_id));
        foreach ($query->result() as $row){

                          $data = array(

                            'name'=>$row->name,
                            'full_url'=>$row->full_url,
                            'thumb_url'=>$row->thumb_url,
                            'header_order'=>$row->header_order,
                            'listing_id'=>$row->listing_id,
                            'listing_type'=>$row->listing_type,
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$row->institute_id,
                            'linked'=>$row->linked


                            );
                $queryC = $dbHandle->insert_string('header_image',$data);
                $queryy = $dbHandle->query($queryC);

                                            }


}
if( $update == 2)
{

         $queryCmd = "select * from header_image where version = ? and listing_type= ? and listing_id = ? ";
        $query = $dbHandle->query($queryCmd,array($old_version,$listing_type,$listing_type_id));
        foreach ($query->result() as $row){

                          $data = array(

                            'name'=>$row->name,
                            'full_url'=>$row->full_url,
                            'thumb_url'=>$row->thumb_url,
                            'header_order'=>$row->header_order,
                            'listing_id'=>$row->listing_id,
                            'listing_type'=>$row->listing_type,
                            'version'=>$version,
                            'status'=>$status,
                            'institute_id'=>$row->institute_id,
                            'linked'=>"no"


                            );
                $queryC = $dbHandle->insert_string('header_image',$data);
                $queryy = $dbHandle->query($queryC);

                                            }

}
}



function getMediaType($mediaType) {
		switch($mediaType) {
			case 'documents' : $mediaType = 'doc'; break;
			case 'photos' : $mediaType = 'photo'; break;
			case 'videos' : $mediaType = 'video'; break;
		}
		return $mediaType;
	}

	function addContactDetails($dbHandle,$formVal, $listing_type = "", $listing_type_id = "", $status = 'draft', $version = 1, $institute_location_id = 0,$old_version = 1){

		// gloabal contact details replication code from media tab
		if(array_key_exists('relicate_features',$formVal) && !array_key_exists('contact_name', $formVal)) {
		  $get_contact_details_query = "SELECT * FROM listing_contact_details WHERE ".
		                 "listing_type_id= ? AND listing_type = ? ".
		                 " AND institute_location_id = ? AND version = ?";
		
		    $query = $dbHandle->query($get_contact_details_query,array($listing_type_id,$listing_type,$institute_location_id,$old_version));
		    foreach($query->result_array() as $row) {
		        $formVal['contact_name'] = $row['contact_person'];
		        $formVal['contact_email'] = $row['contact_email'];
		        $formVal['contact_main_phone'] = $row['contact_main_phone'];
		        $formVal['contact_cell'] = $row['contact_cell'];
		        $formVal['contact_alternate_phone'] = $row['contact_alternate_phone'];
		        $formVal['contact_fax'] = $row['contact_fax'];
		        $formVal['url'] = $row['url'];
		    }
		}

		$data =array();
		$data = array(
		    'contact_person'=>$formVal['contact_name'],
		    'contact_email'=>$formVal['contact_email'],
		    'contact_main_phone'=>$formVal['contact_main_phone'],
		    'contact_cell'=>$formVal['contact_cell'],
		    'contact_alternate_phone'=>$formVal['contact_alternate_phone'],
		    'contact_fax'=>$formVal['contact_fax'],
		    'website'=>$formVal['url'],
		    'listing_type'=>$listing_type,
		    'listing_type_id'=>$listing_type_id,
		    'status'=>$status,
		    'version'=>$version,
		    'institute_location_id'=> $institute_location_id // Would be zero for the course listing as this would be the global Contact detail.
		);
		$queryCmd = $dbHandle->insert_string('listing_contact_details',$data);
		// error_log("\n Global contact query for course id - $listing_type_id : ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');

		$query = $dbHandle->query($queryCmd);
		$contact_details_id = $dbHandle->insert_id();
		return $contact_details_id;
	}

    function addCourseLocationAttributes($dbHandle, $courseID, $institute_location_ids, $location_fee_info, $head_ofc_id = "", $version="1",$important_date_info_location = "",$course_contact_details_locationwise ="") {
		return ;
        $institute_location_ids_array = explode(",", $institute_location_ids);
        $institute_location_ids_array_length = count($institute_location_ids_array);
        $valuesToBeInserted = ""; $feeValuesToBeInserted = ""; $feeUnitsToBeInserted = "";
        $date_values_to_be_inserted = "";
        $location_wise_contactdetails_tobe_inserted = "";
        
        if($important_date_info_location != "") {
        	$imp_date_array = explode("||++||", $important_date_info_location);
        	for ($i=0;$i<count($imp_date_array);$i++) {
        		$temp_array = explode("_", $imp_date_array[$i]);
        		$imp_date_array1[$temp_array[0]]['date_form_submission'] = $temp_array[1];
        		$imp_date_array1[$temp_array[0]]['date_result_declaration'] = $temp_array[2];
        		$imp_date_array1[$temp_array[0]]['date_course_comencement'] = $temp_array[3];	
        	}
        }
        
        if($course_contact_details_locationwise != "") {
        	$course_contact_details_array = explode("||++||", $course_contact_details_locationwise);
        	for ($i=0;$i<count($course_contact_details_array);$i++) {
        		$temp_array1 = explode("|=#=|", $course_contact_details_array[$i]);
                        $temp_array1[1] = trim($temp_array1[1]);
                        $temp_array1[2] = trim($temp_array1[2]);
                        $temp_array1[3] = trim($temp_array1[3]);
                        $temp_array1[4] = trim($temp_array1[4]);
                        // ceck if any data is set, prepare query data to be inserted
                        if((!empty($temp_array1[1]) || !empty($temp_array1[2]) || !empty($temp_array1[3]) || !empty($temp_array1[4]))) {
        			$course_contact_details_array1[$temp_array1[0]]['contact_name_location'] = $temp_array1[1];
        			$course_contact_details_array1[$temp_array1[0]]['contact_phone_location'] = $temp_array1[2];
        			$course_contact_details_array1[$temp_array1[0]]['contact_mobile_location'] = $temp_array1[3];	
                        	$course_contact_details_array1[$temp_array1[0]]['contact_email_location'] = $temp_array1[4];	
                }
        	}
        } 
        // error_log("aditya".print_r($institute_location_ids_array,true));
        // error_log("\n\n location_fee_info in addCourseLocationAttributes : ".print_r($location_fee_info, true),3,'/home/infoedge/Desktop/log.txt');

        $sendEmailFlag = false;
        $emailContent = "Hi,<BR><BR>Institute Location Id(s) seemed to be wrong here:<BR>";
        // Insert locations first..
        for($i = 0; $i < $institute_location_ids_array_length; $i++) {

            $ilID = trim($institute_location_ids_array[$i]);
            
            if($ilID == "" || $ilID == 0) {
                $sendEmailFlag = true;
            }

            if($head_ofc_id == $ilID || $head_ofc_id == "") {
                if($head_ofc_id == "")
                $head_ofc_id = $ilID;

                $attribute_value = "TRUE";
            } else {
                $attribute_value = "FALSE";
            }
            
            $valuesToBeInserted .= ($valuesToBeInserted == "" ? "" : ", ") . "('".$courseID."', '".$ilID."', 'Head Office', '".$attribute_value."', 'draft', '".$version."')";
            
            
            $fee_value = $location_fee_info[$ilID]['fee_value'];

            // error_log("\n Fee Value : $fee_value for loc id :".$ilID,3,'/home/infoedge/Desktop/log.txt');

            if($fee_value != "") {
                $feeValuesToBeInserted .= ($feeValuesToBeInserted == "" ? "" : ", ") . "('".$courseID."', '".$ilID."', 'Course Fee Value', '".$fee_value."', 'draft', '".$version."')";

                $fee_unit = $location_fee_info[$ilID]['fee_unit'];
                $feeUnitsToBeInserted .= ($feeUnitsToBeInserted == "" ? "" : ", ") . "('".$courseID."', '".$ilID."', 'Course Fee Unit', '".$fee_unit."', 'draft', '".$version."')";
            }

            if(array_key_exists($ilID, $imp_date_array1)) {
            	if(!empty($imp_date_array1[$ilID]['date_form_submission']) && !($imp_date_array1[$ilID]['date_form_submission'] == 'undefined')) {
            		$date_values_to_be_inserted .= ($date_values_to_be_inserted == "" ? "" : ", "). "('".addslashes($courseID)."', '".addslashes($ilID)."', 'date_form_submission', '".addslashes($imp_date_array1[$ilID]['date_form_submission'])."', 'draft', '".addslashes($version)."')";
            	}
          
        	if(!empty($imp_date_array1[$ilID]['date_result_declaration']) && !($imp_date_array1[$ilID]['date_result_declaration'] == 'undefined')) {
            	$date_values_to_be_inserted .= ($date_values_to_be_inserted == "" ? "" : ", ") . "('".addslashes($courseID)."', '".addslashes($ilID)."', 'date_result_declaration', '".addslashes($imp_date_array1[$ilID]['date_result_declaration'])."', 'draft', '".addslashes($version)."')";
                }

                if(!empty($imp_date_array1[$ilID]['date_course_comencement']) && !($imp_date_array1[$ilID]['date_course_comencement']  == 'undefined')) {
                    $date_values_to_be_inserted .= ($date_values_to_be_inserted == "" ? "" : ", ") . "('".addslashes($courseID)."', '".addslashes($ilID)."', 'date_course_comencement', '".addslashes($imp_date_array1[$ilID]['date_course_comencement'])."', 'draft', '".addslashes($version)."')";
                }

              }
              if(array_key_exists($ilID, $course_contact_details_array1)) {
                            $location_wise_contactdetails_tobe_inserted .= ($location_wise_contactdetails_tobe_inserted == "" ? "" : ", "). "('".addslashes($courseID)."', '".addslashes($ilID)."', '".addslashes($course_contact_details_array1[$ilID]['contact_name_location'])."', '".addslashes($course_contact_details_array1[$ilID]['contact_phone_location'])."', '".addslashes($course_contact_details_array1[$ilID]['contact_mobile_location'])."','".addslashes($course_contact_details_array1[$ilID]['contact_email_location'])."','draft', '".addslashes($version)."','course')";
              }
            }

        $query = "insert into course_location_attribute (course_id, institute_location_id, attribute_type, attribute_value, status, version)
                values ".$valuesToBeInserted;

        if($sendEmailFlag) {
            $emailContent .= "<BR><b>COURSE LOCATION QUERY:</b><HR>".$query."<HR>";
        }

        // error_log("\n\n Course Location Attribute query : ".$query,3,'/home/infoedge/Desktop/log.txt');
        $query =  $dbHandle->query($query);


        if($feeValuesToBeInserted != "") {

            $query = "insert into course_location_attribute (course_id, institute_location_id, attribute_type, attribute_value, status, version)
                    values ".$feeValuesToBeInserted;
            // error_log("\n\n Course Location FEE Attribute query : ".$query,3,'/home/infoedge/Desktop/log.txt');
            if($sendEmailFlag) {
                $emailContent .= "<BR><b>FEE VALUE QUERY:</b><HR>".$query."<HR>";
            }
            $query =  $dbHandle->query($query);


            $query = "insert into course_location_attribute (course_id, institute_location_id, attribute_type, attribute_value, status, version)
                    values ".$feeUnitsToBeInserted;
            if($sendEmailFlag) {
                $emailContent .= "<BR><b>FEE UNIT QUERY:</b><HR>".$query."<HR>";
            }

            // error_log("\n\n Course Location FEE UNIT Attribute query : ".$query,3,'/home/infoedge/Desktop/log.txt');
            $query =  $dbHandle->query($query);
        }
        
        if($date_values_to_be_inserted != "") {
                $query = "insert into course_location_attribute (course_id, institute_location_id, attribute_type, attribute_value, status, version)
            values ".$date_values_to_be_inserted;
                // error_log("\n\n Course Location FEE Attribute query : ".$query,3,'/home/infoedge/Desktop/log.txt');
                if($sendEmailFlag) {
                    $emailContent .= "<BR><b>IMPORTANT DATES QUERY:</b><HR>".$query."<HR>";
                }
                $query =  $dbHandle->query($query);
        }
        
        if($location_wise_contactdetails_tobe_inserted != "") {
                $query = "insert into listing_contact_details (listing_type_id, institute_location_id, 	contact_person, contact_main_phone, contact_cell,contact_email,status, version,listing_type)
            values ".$location_wise_contactdetails_tobe_inserted;
                if($sendEmailFlag) {
                    $emailContent .= "<BR><b>CONTACT DETAILS QUERY:</b><HR>".$query."<HR>";
                }

                // error_log("\n\n Course Location FEE Attribute query : ".$query,3,'/home/infoedge/Desktop/log.txt');
                $query =  $dbHandle->query($query);
        }

        if($sendEmailFlag) { // Send the alert email finaly..
                  $appId = 1;
                  $this->load->library('alerts_client');
		  $fromAddress="noreply@shiksha.com";
		  $subject = 'Location Issue alert for the Course ID: '.$courseID.' in DB insertion';
		  $userEmail = 'amit.kuksal@shiksha.com';
		  $AlertClientObj = new Alerts_client();
		  $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$emailContent,"html");
                  $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'aditya.roshan@shiksha.com',$subject,$emailContent,"html");
		  $alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,'sachin.singhal@brijj.com',$subject,$emailContent,"html");
        }
    }

    function getMaxInstituteLocationId($dbHandle) {
            //$getSql = "select max(institute_location_id) as new_institute_location_id from institute_location_table";
            //$queryCheckDup =  $dbHandle->query($getSql);
            //$row = $queryCheckDup->result_array();
            //// error_log("\n\n getSql query in addLocationContactInfoForInstitute : ".$getSql.", data : ".print_r($row, true),3,'/home/infoedge/Desktop/log.txt');
            //return($row[0]['new_institute_location_id']);
	
	    return Modules::run('common/IDGenerator/generateId','instituteLocation');
    }

    function addLocationContactInfoForInstitute($dbHandle, $locationInfo, $contactInfo, $instituteId, $version) {
            // error_log("\n\n In addLocationContactInfoForInstitute, location info : ".print_r($locationInfo, true)."\n\n Contact info : ".print_r($contactInfo, true),3,'/home/infoedge/Desktop/log.txt');           
            $institute_location_id = $this->getMaxInstituteLocationId($dbHandle);
            $infoCount = count($locationInfo); $contactValuesToBeInserted = "";

            for($i = 0; $i < $infoCount; $i++) {
                    $institute_location_id++;
                    $data =array();
                    $data = array(
                            'institute_location_id' => $institute_location_id,
                            'institute_id'=>$instituteId,
                            'address_2' => $locationInfo[$i]['address2'],
                            'locality_id' => $locationInfo[$i]['locality_id'],
                            'locality_name' => $locationInfo[$i]['locality_name'],
                            'city_id'=> $locationInfo[$i]['city_id'],
                            'country_id'=> $locationInfo[$i]['country_id'],
                            'address_1'=> $locationInfo[$i]['address1'],
                            'pincode'=> $locationInfo[$i]['pin_code'],
                            'zone'=> $locationInfo[$i]['zone_id'],
                            'status'=> 'draft',
                            'version'=> $version,
                            'city_name'=> $locationInfo[$i]['city_name']
                    );

                    $queryCmd = $dbHandle->insert_string('institute_location_table',$data);
                    // error_log("\n Location query for institute_location_id $institute_location_id : ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
                    $query = $dbHandle->query($queryCmd);
                    $contactValuesToBeInserted .= ($contactValuesToBeInserted == "" ? "" : ", ") . "('".addslashes($contactInfo[$i]['contact_person_name'])."', '".addslashes($contactInfo[$i]['contact_person_email'])."', '".addslashes($contactInfo[$i]['main_phone_number'])."', '".addslashes($contactInfo[$i]['mobile_number'])."', '".addslashes($contactInfo[$i]['alternate_phone_number'])."', '".addslashes($contactInfo[$i]['fax_number'])."', '".addslashes($contactInfo[$i]['website'])."', 'institute', '".addslashes($instituteId)."', '".addslashes($institute_location_id)."', 'draft', '".addslashes($version)."')";
        }

        $contactQuery = "insert into listing_contact_details (contact_person, contact_email, contact_main_phone, contact_cell, contact_alternate_phone, contact_fax, website, listing_type, listing_type_id, institute_location_id, status, version)
                values ".$contactValuesToBeInserted;
        // error_log("\n\n Contact query : ".$contactQuery,3,'/home/infoedge/Desktop/log.txt');
        $query =  $dbHandle->query($contactQuery);

        // Updating the contact details id in Listings_main table, need to comment it once Response viewer changes are done..
        $getSql = "select max(contact_details_id) as contact_details_id from listing_contact_details where listing_type = 'institute' AND listing_type_id = ? AND version = ?";
        $queryCheckDup =  $dbHandle->query($getSql,array($instituteId,$version));
        $row = $queryCheckDup->result_array();
        // error_log("\n\n getSql : ".$getSql.", data : ".print_r($row, true),3,'/home/infoedge/Desktop/log.txt');
        $contact_details_id = $row[0]['contact_details_id'];

        $sql = "update listings_main set contact_details_id = ? where listing_type = 'institute' AND listing_type_id = ? AND version = ? ";
        $dbHandle->query($sql,array($contact_details_id,$instituteId,$version));
        // error_log("\n\n update Sql : ".$sql,3,'/home/infoedge/Desktop/log.txt');
    }



    function replicateInstituteLocationContactData($dbHandle, $institute_id, $old_version, $new_version, $status, $formVal) {
            //error_log('aditya'.$institute_id."__".$old_version."__".$new_version."___".$status);
            $institute_location_id = $this->getMaxInstituteLocationId($dbHandle);
            
            $len = count($formVal['locationInfo']);

            for($i = 0; $i < $len; $i++) {
                    if(isset($formVal['locationInfo'][$i]['institute_location_id']) && $formVal['locationInfo'][$i]['institute_location_id'] != "") {
                        $ilid = $formVal['locationInfo'][$i]['institute_location_id'];
                    } else {
                        $ilid = ++$institute_location_id;
                    }

                    $data =array();
                    $data = array(
                            'institute_location_id'=>$ilid,
                            'institute_id'=>$institute_id,
                            'city_id'=>$formVal['locationInfo'][$i]['city_id'],
                            'country_id'=>$formVal['locationInfo'][$i]['country_id'],
                            'address_1'=>$formVal['locationInfo'][$i]['address1'],
                            'address_2'=>$formVal['locationInfo'][$i]['address2'],
                            'locality_id'=>$formVal['locationInfo'][$i]['locality_id'],
                            'zone'=>isset($formVal['locationInfo'][$i]['zone_id']) ? $formVal['locationInfo'][$i]['zone_id'] : 0,
                            'locality_name'=>$formVal['locationInfo'][$i]['locality_name'],
                            'city_name'=>isset($formVal['locationInfo'][$i]['city_name']) ? $formVal['locationInfo'][$i]['city_name'] : '',
                            'pincode'=>isset($formVal['locationInfo'][$i]['pin_code'])? $formVal['locationInfo'][$i]['pin_code'] : $formVal['locationInfo'][$i]['pincode'], // This is done because in some flow it is coming as pin_code and in some pinCode...:)
                            'status'=>$status,
                            'version'=>$new_version
                    );

                    $queryCmd = $dbHandle->insert_string('institute_location_table',$data);
                    // error_log("\n\n LOC query: ".$queryCmd."\n ".print_r($formVal['locationInfo'], true),3,'/home/infoedge/Desktop/log.txt');
                    $query = $dbHandle->query($queryCmd);

                    // Inserting data for listing_contact_details now..
                    $data =array();
                    $data = array(
                            'contact_details_id'=>'',
                            'contact_person'=>$formVal['contactInfo'][$i]['contact_person_name'],
                            'contact_email'=>$formVal['contactInfo'][$i]['contact_person_email'],
                            'contact_main_phone'=>$formVal['contactInfo'][$i]['main_phone_number'],
                            'contact_cell'=>$formVal['contactInfo'][$i]['mobile_number'],
                            'contact_alternate_phone'=>$formVal['contactInfo'][$i]['alternate_phone_number'],
                            'contact_fax'=>$formVal['contactInfo'][$i]['fax_number'],
                            'website'=>$formVal['contactInfo'][$i]['website'],
                            'listing_type'=>'institute',
                            'listing_type_id'=>$institute_id,
                            'institute_location_id' => $ilid,
                            'status'=>$status,
                            'version'=>$new_version
                    );

                    $queryCmd = $dbHandle->insert_string('listing_contact_details',$data);
                    // error_log("\n\n CONTACT query: ".$queryCmd."\n ".print_r($formVal['contactInfo'], true),3,'/home/infoedge/Desktop/log.txt');
                    $query = $dbHandle->query($queryCmd);

            }   // End of for($i = 0; $i < $len; $i++).

    }

    
    function getStateForCity($dbHandle,$cityId)
    {
        if($cityId <= 1){
            return -1;
        }
        //connect DB
        if($dbHandle == ''){
            $dbHandle = $this->getDbHandle();
        }
        $queryCmd="select state_id from countryCityTable where city_id = ? ";
        // error_log_shiksha($queryCmd);
        $query=$dbHandle->query($queryCmd,array($cityId));
        if(count($query->result_array())>0)
        {
            foreach($query->result_array() as $row){
                $stateId = $row['state_id'];
            }
        }
        if(strlen($stateId) > 0){
            return $stateId;
        }
        else{
            return -1;
        }
    }
    function getAllChildIdsOfParent($dbHandle, $boardId){
        if($boardId ==''){
            $boardId =1;
        }
        $queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = (select parentId from categoryBoardTable where boardId = ?)';
        $query = $dbHandle->query($queryCmd,array($boardId));
        foreach ($query->result() as $row){
            if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev1]=$row->lev1;
                $boardIdString .= $row->lev1;
            }
            if(!array_key_exists($row->lev2,$boardIdArray) && !empty($row->lev2)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev2]=$row->lev2;
                $boardIdString .= $row->lev2;
            }
            if(!array_key_exists($row->lev3,$boardIdArray) && !empty($row->lev3)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev3]=$row->lev3;
                $boardIdString .= $row->lev3;
            }
            if(!array_key_exists($row->lev4,$boardIdArray) && !empty($row->lev4)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev4]=$row->lev4;
                $boardIdString .= $row->lev4;
            }
        }
        if(strlen($boardIdString)==0){
            $boardIdString .= $boardId;
        }
        return $boardIdString;
    }

    function getChildIds($dbHandle, $boardId){
        if($boardId ==''){
            $boardId =1;
        }
        $queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';
        $query = $dbHandle->query($queryCmd,array($boardId));
        foreach ($query->result() as $row){
            if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev1]=$row->lev1;
                $boardIdString .= $row->lev1;
            }
            if(!array_key_exists($row->lev2,$boardIdArray) && !empty($row->lev2)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev2]=$row->lev2;
                $boardIdString .= $row->lev2;
            }
            if(!array_key_exists($row->lev3,$boardIdArray) && !empty($row->lev3)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev3]=$row->lev3;
                $boardIdString .= $row->lev3;
            }
            if(!array_key_exists($row->lev4,$boardIdArray) && !empty($row->lev4)){
                if(strlen($boardIdString)>0){
                    $boardIdString .= ' , ';
                }
                $boardIdArray[$row->lev4]=$row->lev4;
                $boardIdString .= $row->lev4;
            }
        }
        if(strlen($boardIdString)==0){
            $boardIdString .= $boardId;
        }
        return $boardIdString;
    }

    function getTotalInstituteCountForExams($dbHandle, $criteria =array()){
        $addGroupClause = '';
        $addSelectItem = ' ';
        if(strlen($criteria['typeOfInstitutes']) > 0){
            $addWhereClause = " and typeOfMap = '".addslashes($criteria['typeOfInstitutes'])."'";
        }
        if($criteria['examId'] == 0){
            $addGroupClause = ' group by t.parentId ';
        }
        else{
            $addGroupClause = ' group by t.parentId ';
            $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status ="live" and parentId =(SELECT blogId FROM blogTable WHERE blogId= ? AND parentId =0 AND blogType = "exam" AND status = "live") GROUP BY blogType ';
            $query = $dbHandle->query($queryCmd, array($blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
            $addWhereClause1 = " and listingExamMap.examId IN ('.$blogId.') ";
        }

        //$queryCmd = 'select count(distinct institute_id) as numListings ,parentId as criteria  from (select parentId, examId, listing_type_id as institute_id, typeOfMap from listings_main , institute_courses_mapping_table, listingExamMap , blogTable where blogTable.blogId = examId  and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and listing_type="institute"  '.$addWhereClause.$addWhereClause1.' and listings_main.status="live"  union select parentId, examId, listing_type_id as institute_id, typeOfMap from listings_main , institute_examinations_mapping_table , listingExamMap , blogTable where blogTable.blogId = examId and institute_examinations_mapping_table.institute_id  = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and listing_type="institute" '.$addWhereClause.$addWhereClause1.' and listings_main.status="live" ) as t '.$addGroupClause.' ';
        $queryCmd = 'select count(distinct institute_id) as numListings ,parentId as criteria  from (select parentId, examId, listing_type_id as institute_id, typeOfMap from listings_main , institute_courses_mapping_table, listingExamMap , blogTable where blogTable.blogId = examId  andblogTable.status = "live" and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and listing_type="institute"  '.$addWhereClause.$addWhereClause1.' and listings_main.status="live"  union select parentId, examId, listing_type_id as institute_id, typeOfMap from listings_main , institute_examinations_mapping_table , listingExamMap , blogTable where blogTable.blogId = examId and blogTable.status ="live" and institute_examinations_mapping_table.institute_id  = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and listing_type="institute" '.$addWhereClause.$addWhereClause1.' and listings_main.status="live"  and listingExamMap.status="live" ) as t '.$addGroupClause.' ';
        // error_log_shiksha("FAAD QUERY::".$queryCmd);
        $resultSet = $dbHandle->query($queryCmd);
        $response = array();
        if(strlen($addGroupClause)> 0){
            foreach($resultSet->result_array() as $result) {
                $response['numInstitutes'][$result['criteria']] = $result['numListings'];
            }
        }else{
            foreach($resultSet->result_array() as $result) {
                $response['numInstitutes'][0] = $result['numListings'];
            }
        }
        return $response;
    }

    function getTotalCoursesCountForExams($dbHandle, $criteria =array()){
        $addGroupClause = '';
        $addSelectItem = ' ';
        if(strlen($criteria['typeOfInstitutes']) > 0){
            $addWhereClause = " and typeOfMap = '".addslashes($criteria['typeOfInstitutes'])."'";
        }
        if($criteria['examId'] == 0){
            $addGroupClause = ' group by t.parentId ';
        }
        else{
            $addGroupClause = ' group by t.parentId ';
            $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status = "live" AND parentId =(SELECT blogId FROM blogTable WHERE blogId= ? AND parentId =0 AND blogType = "exam" AND status = "live") GROUP BY blogType ';
            // error_log_shiksha($queryCmd);
            $query = $dbHandle->query($queryCmd, array($blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
            $addWhereClause1 = " and listingExamMap.examId IN ('.$blogId.') ";
        }

//        $queryCmd = 'select count(distinct course_id) as numListings ,parentId as criteria  from (select parentId, examId, listing_type_id as course_id, typeOfMap from listings_main ,  listingExamMap , blogTable where blogTable.blogId = examId  and listings_main.status="live" and listing_type_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and listing_type="course"  '.$addWhereClause.$addWhereClause1.') as t '.$addGroupClause.' ';
        $queryCmd = 'select count(distinct course_id) as numListings ,parentId as criteria  from (select parentId, examId, listing_type_id as course_id, typeOfMap from listings_main ,  listingExamMap , blogTable where blogTable.blogId = examId  and listings_main.status="live" and blogTable.status = "live" and listing_type_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and listing_type="course"  '.$addWhereClause.$addWhereClause1.') as t '.$addGroupClause.' ';
        // error_log_shiksha("FAAD QUERY::".$queryCmd);
        $resultSet = $dbHandle->query($queryCmd);
        $response = array();
        if(strlen($addGroupClause)> 0){
            foreach($resultSet->result_array() as $result) {
                $response['numCourses'][$result['criteria']] = $result['numListings'];
            }
        }else{
            foreach($resultSet->result_array() as $result) {
                $response['numCourses'][0] = $result['numListings'];
            }
        }
        return $response;
    }

    function getCoursesForExams($blogId,$typeOfInstitutes,$start,$count,$relaxFlag = 1,$countryId = 1 , $cityId = 1, $pageKey = '')
    {
        $cacheLib = $this->load->library('cacheLib');
        
        $entityRelations = $this->getEntityRelations();
        if(count($entityRelations) < 0) {
            return false;
        }
        if(strlen($typeOfInstitutes) > 0){
            $addWhereClause = " and typeOfMap = '".addslashes($typeOfInstitutes)."' ";
        }
        $dbHandle = $this->getDbHandle();

        if($countryId != 1){
            $addCountryClause = ' and institute_location_table.country_id = '.addslashes($countryId).' ';
            if($cityId != 1){
                $addCityClause = ' and institute_location_table.city_id in ('.addslashes($cityId).') ';
            }
        }

        if($relaxFlag == 1){
             $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogId = (SELECT parentId FROM blogTable WHERE blogId =? AND blogType="exam" AND status ="live") AND blogType="exam" AND parentId!=0 AND status = "live" GROUP BY blogType UNION SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status="live" AND parentId =(SELECT blogId FROM blogTable WHERE blogId=? AND parentId =0 AND blogType = "exam" AND status = "live") GROUP BY blogType ';
            // error_log_shiksha($queryCmd);
            $query = $this->db->query($queryCmd,array($blogId,$blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
        }
        else{
            $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status = "live" AND parentId =(SELECT blogId FROM blogTable WHERE blogId=? AND parentId =0 AND blogType = "exam" AND status="live") GROUP BY blogType ';
            // error_log_shiksha($queryCmd);
            $query = $this->db->query($queryCmd,array($blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
        }

        //$queryCmd = 'select listing_title as courseTitle, listing_type_id as course_id, institute.institute_id as institute_id, institute.institute_name as institute_name , typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute where institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.course_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="course" '.$addCityClause.$addCountryClause.$addWhereClause.' and listings_main.status="live" group by course_id';
        $queryCmd = 'select listing_title as courseTitle, listing_type_id as course_id, institute.institute_id as institute_id, institute.institute_name as institute_name , typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute where institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.course_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="course" '.$addCityClause.$addCountryClause.$addWhereClause.' and listings_main.status="live"  and listingExamMap.status="live" group by course_id';
        $queryCmd = "select SQL_CALC_FOUND_ROWS * from ($queryCmd) as t group by t.course_id limit ?,? ";
        // error_log_shiksha("FAAD QUERY::".$queryCmd);
        $query = $this->db->query($queryCmd, array($start, $count));
        $counter = 0;
        $locArray = array();
        $msgArray = array();
        foreach ($query->result() as $row){
            $institute_id = $row->course_id;
            $institute_name = $row->course_name;
            $optionalArgs = array();
            $locQueryCmd = 'select * from institute_location_table where institute_id= ? order by institute_location_id asc ';
            $queryTemp = $dbHandle->query($locQueryCmd,array($row->institute_id));
            $locationArrayTemp = array();
            $l = 0;
            foreach ($queryTemp->result() as $rowTemp) {
                array_push($locationArrayTemp,array(
                    array(
                        'city_id'=>array($rowTemp->city_id,'string'),
                        'country_id'=>array($rowTemp->country_id,'string'),
                        'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                        'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                        'address'=>array(htmlspecialchars($rowTemp->address),'string')
                    ),'struct')
                );//close array_push
                $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                $l++;
            }

            $courseArrayTemp = array();
            $optionalArgs['institute'] = $row->institute_name;

            if($row->logo_link == '' || $row->logo_link==NULL){
                $logo_link = "/public/images/noPhoto.gif";
            }
            else{
                $logo_link = $row->logo_link;
            }

            $instituteUrl  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
            $courseUrl = getSeoUrl($row->course_id,'course',$row->courseTitle,$optionalArgs);
            if($row->pack_type > 0 && $row->pack_type !=7){
                $isSendQuery = 1;
            }else{
                $isSendQuery = 0;
            }
            if($row->logo_link == '' || $row->logo_link==NULL){
                $logo_link = "/public/images/noPhoto.gif";
            }
            else{
                $logo_link = $row->logo_link;
            }

            array_push($msgArray,array(
                array(
                    'title'=>array($row->courseTitle,'string'),
                    'id'=>array($row->course_id,'string'),
                    'logo_link'=>array($logo_link,'string'),
                    'url'=>array($courseUrl,'string'),
                    'institute_name'=>array($row->institute_name,'string'),
                    'instituteUrl'=>array($instituteUrl,'string'),
                    'isSendQuery'=>array($isSendQuery,'string'),
                    'locationArr'=>array($locationArrayTemp,'struct'),
                ),'struct')
            );
        }
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $this->db->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
            $totalRows = $row->totalRows;
        }
        $mainArr = array();
        array_push($mainArr,array(
                    array(
                        'courses'=>array($msgArray,'struct'),
                        'total'=>array($totalRows,'string'),
                        ),'struct')
                );//close array_push
        $response = array($mainArr,'struct');
        return $response;
    }

    function getListingsForExams($blogId,$typeOfInstitutes,$start,$count,$relaxFlag = 1,$countryId = 1 , $cityId = 1, $pageKey = 0)
    {
        $cacheLib = $this->load->library('cacheLib');
        
        $entityRelations = $this->getEntityRelations();
        if(count($entityRelations) < 0) {
            return false;
        }
        if(strlen($typeOfInstitutes) > 0){
            $addWhereClause = " and typeOfMap = '".addslashes($typeOfInstitutes)."' ";
        }
        $dbHandle = $this->getDbHandle();

        if($countryId != 1){
            $addCountryClause = ' and institute_location_table.country_id = '.addslashes($countryId).' ';
            if($cityId != 1){
                $addCityClause = ' and institute_location_table.city_id in ('.addslashes($cityId).') ';
                $newAddCityClause  = ' and virtualCityMapping.virtualCityId =  '.addslashes($cityId).' ';
            }
        }

        if(trim($typeOfInstitutes) == 'testprep'){
            $pageKey=$this->getKeyPageId($dbHandle,1,$countryId,$cityId,$blogId);
        }
        if($relaxFlag == 1){
            $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogId = (SELECT parentId FROM blogTable WHERE blogId = ? AND blogType="exam" and status ="live") AND blogType="exam" AND parentId!=0 AND status = "live" GROUP BY blogType UNION SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status="live" AND parentId =(SELECT blogId FROM blogTable WHERE blogId= ? AND parentId =0 AND blogType = "exam" AND status="live") GROUP BY blogType ';
            // error_log_shiksha($queryCmd);
            $query = $this->db->query($queryCmd,array($blogId,$blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
        }
        else{
            $queryCmd = 'SELECT GROUP_CONCAT(blogId) AS blogIds FROM blogTable WHERE blogType="exam" AND status = "live" AND parentId =(SELECT blogId FROM blogTable WHERE blogId= ? AND parentId =0 AND blogType = "exam" AND status="live") GROUP BY blogType ';
            // error_log_shiksha($queryCmd);
            $query = $this->db->query($queryCmd,array($blogId));
            $blogIds = '';
            foreach ($query->result() as $result){
                $blogIds = $result->blogIds;
            }
            $blogId .= $blogIds == '' ? '' : ','. $blogIds;
        }


        //$queryCmd = 'select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute, PageCollegeDb where PageCollegeDb.CollegeId = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate  and PageCollegeDb.status="live" and PageCollegeDb.KeyId in ('.$pageKey.') and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and listings_main.status="live" group by institute_id';
        //$queryCmd .= ' union select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link  , listings_main.pack_type as pack_type from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute, PageCollegeDb where  PageCollegeDb.CollegeId = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate   and PageCollegeDb.status="live" and PageCollegeDb.KeyId in ('.$pageKey.') and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and listingExamMap.status = "live" and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and listings_main.status="live" group by institute_id';
        $queryCmd = "select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute, PageCollegeDb where PageCollegeDb.listing_type='institute' and PageCollegeDb.listing_type_id = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate  and PageCollegeDb.status='live' and PageCollegeDb.KeyId in (".$pageKey.") and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = 'course' and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN (".$blogId.") and listingExamMap.status='live' and  listing_type='institute'  and listings_main.status='live' group by institute_id";
        $queryCmd .= " union select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link  , listings_main.pack_type as pack_type from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute, PageCollegeDb where PageCollegeDb.listing_type='institute' and  PageCollegeDb.listing_type_id = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate   and PageCollegeDb.status='live' and PageCollegeDb.KeyId in (".$pageKey.") and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = 'notification' and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN (".$blogId.") and listingExamMap.status='live' and listing_type='institute'  and listings_main.status='live' group by institute_id";

        $queryCmd = "select SQL_CALC_FOUND_ROWS * from ($queryCmd) as t group by t.institute_id";
        // error_log_shiksha("CMS TEST PREP FAAD QUERY::".$queryCmd);

        $query = $this->db->query($queryCmd);
        //what are CMS Rows
        $cmsRows=$query->num_rows();

        $counter = 0;
        $instituteIds = ' -1 ';
        $msgArray = array();
        $end = $start + $count;
        $resultsFetched = 0;

        if($cmsRows > $start) {
            foreach ($query->result() as $row){
                $instituteIds .= " , ".$row->institute_id." ";
                if($counter >= $start && $counter < $end){
                    $resultsFetched += 1;
                    $institute_id = $row->institute_id;
                    $institute_name = $row->institute_name;
                    $optionalArgs = array();
                    $locQueryCmd = 'select * from institute_location_table, virtualCityMapping where institute_id=? and virtualCityMapping.city_id = institute_location_table.city_id order by institute_location_id asc ';
                    $queryTemp = $dbHandle->query($locQueryCmd, array($row->institute_id));
                    $locationArrayTemp = array();
                    $tempLocationArray = array();
                    $onlyOneCityFlag = 0;
                    $l = 0;
                    foreach ($queryTemp->result() as $rowTemp) {
                        if((!isset($cityId) ||  $cityId =='' || $cityId ==1 || $cityId == $rowTemp->virtualCityId) && $onlyOneCityFlag == 0){
                            $location  = $cacheLib->get("city_".$rowTemp->city_id).",".$cacheLib->get("country_".$rowTemp->country_id);
                            array_push($locationArrayTemp,array(
                                array(
                                    'city_id'=>array($rowTemp->virtualCityId,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                    'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                ),'struct')
                            );//close array_push
                            $onlyOneCityFlag = 1;
                        }
                        if(!isset($tempLocationArray[$row->institute_location_id]) || $tempLocationArray[$row->institute_location_id] != 1)
                        {
                            $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                            $l++;
                            $tempLocationArray[$row->institute_location_id] = 1;
                        }
                    }

                    $detailurl  = getSeoUrl($institute_id,"institute",$institute_name, $optionalArgs);

                    if($row->pack_type > 0 && $row->pack_type !=7){
                        $isSendQuery = 1;
                    }else{
                        $isSendQuery = 0;
                    }

                    array_push($msgArray,array(
                        array(
                            'instituteName'=>array($institute_name,'string'),
                            'instituteId'=>array($institute_id,'string'),
                            'detailUrl'=>array($detailurl,'string'),
                            'typeOfMap'=>array($row->typeOfMap,'string'),
                            'imageUrl'=>array($row->logo_link,'string'),
                            'isSendQuery'=>array($isSendQuery,'string'),
                            'isSponsored'=>array('true','string'),
                            'location'=>array($location,'string')
                        ),'struct')
                    );
                }
                $counter++;
            }
        }
        else{
            foreach ($query->result() as $row){
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= " $row->institute_id ";
            }
        }

        if($count > $resultsFetched){
            $mainListingCount = $count - $resultsFetched;
            if(($resultsFetched == 0) && ($start >= $cmsRows)){
                $mainListingStart = $start - $cmsRows;
            }
            else{
                $mainListingStart = 0;
            }

            //$queryCmd = 'select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute, virtualCityMapping  where  institute.institute_id not in ('.$instituteIds.') and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute" and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.' and listings_main.status="live" group by institute_id';
            //$queryCmd .= ' union select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link  , listings_main.pack_type as pack_type from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute , virtualCityMapping where institute.institute_id not in ('.$instituteIds.') and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and listingExamMap.status = "live" and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.'  and listings_main.status="live" group by institute_id';
            $queryCmd = 'select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link , listings_main.pack_type as pack_type from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute, virtualCityMapping  where  institute.institute_id not in ('.$instituteIds.') and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute" and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.' and listingExamMap.status="live" and listings_main.status="live" group by institute_id';
            $queryCmd .= ' union select listing_title as institute_name, listing_type_id as institute_id, typeOfMap,logo_link  , listings_main.pack_type as pack_type from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute , virtualCityMapping where institute.institute_id not in ('.$instituteIds.') and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.'  and listings_main.status="live"  and listingExamMap.status="live" group by institute_id';
            $queryCmd = "select SQL_CALC_FOUND_ROWS * from ($queryCmd) as t group by t.institute_id limit ?,? ";
            // error_log_shiksha("FAAD QUERY::".$queryCmd);
            $query = $this->db->query($queryCmd, array($mainListingStart, $mainListingCount));
            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $this->db->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }
            $totalRows += $cmsRows;

            foreach ($query->result() as $row){
                $resultsFetched += 1;
                $institute_id = $row->institute_id;
                $institute_name = $row->institute_name;
                $optionalArgs = array();
                $locQueryCmd = 'select * from institute_location_table, virtualCityMapping where institute_id= ? and virtualCityMapping.city_id = institute_location_table.city_id order by institute_location_id asc ';
                $queryTemp = $dbHandle->query($locQueryCmd,array($row->institute_id));
                $locationArrayTemp = array();
                $tempLocationArray = array();
                $onlyOneCityFlag = 0;
                $l = 0;
                foreach ($queryTemp->result() as $rowTemp) {
                    if((!isset($cityId) ||  $cityId =='' || $cityId ==1 || $cityId == $rowTemp->virtualCityId) && $onlyOneCityFlag == 0){
                        $location  = $cacheLib->get("city_".$rowTemp->city_id).",".$cacheLib->get("country_".$rowTemp->country_id);
                        array_push($locationArrayTemp,array(
                                    array(
                                        'city_id'=>array($rowTemp->virtualCityId,'string'),
                                        'country_id'=>array($rowTemp->country_id,'string'),
                                        'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                        'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                        'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                        ),'struct')
                                );//close array_push
                        $onlyOneCityFlag = 1;
                    }
                    if(!isset($tempLocationArray[$row->institute_location_id]) || $tempLocationArray[$row->institute_location_id] != 1)
                    {
                        $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                        $l++;
                        $tempLocationArray[$row->institute_location_id] = 1;
                    }
                }

                $detailurl  = getSeoUrl($institute_id,"institute",$institute_name, $optionalArgs);

                if($row->pack_type > 0 && $row->pack_type !=7){
                    $isSendQuery = 1;
                }else{
                    $isSendQuery = 0;
                }
                array_push($msgArray,array(
                    array(
                        'instituteName'=>array($institute_name,'string'),
                        'instituteId'=>array($institute_id,'string'),
                        'detailUrl'=>array($detailurl,'string'),
                        'typeOfMap'=>array($row->typeOfMap,'string'),
                        'imageUrl'=>array($row->logo_link,'string'),
                        'isSendQuery'=>array($isSendQuery,'string'),
                        'location'=>array($location,'string')
                    ),'struct')
                );
            }
        }
        else{
            //$queryCmd = 'select listing_type_id as institute_id from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute where  institute.institute_id not in ('.$instituteIds.') and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and listingExamMap.status = "live" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.' and listings_main.status="live" group by institute_id';
            //$queryCmd .= ' union select listing_type_id as institute_id from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute where institute.institute_id not in ('.$instituteIds.') and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and listingExamMap.status = "live" and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id  '.$newAddCityClause.$addCountryClause.$addWhereClause.'  and listings_main.status="live" group by institute_id';
            $queryCmd = 'select listing_type_id as institute_id from listings_main , institute_courses_mapping_table, listingExamMap , institute_location_table,institute, virtualCityMapping  where  institute.institute_id not in ('.$instituteIds.') and institute.institute_id = institute_courses_mapping_table.institute_id and institute_courses_mapping_table.institute_id  = listing_type_id and institute_courses_mapping_table.course_id = listingExamMap.typeId and listingExamMap.type = "course" and  institute_courses_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id '.$newAddCityClause.$addCountryClause.$addWhereClause.' and listings_main.status="live"  and listingExamMap.status="live" group by institute_id';
            $queryCmd .= ' union select listing_type_id as institute_id from listings_main , institute_examinations_mapping_table , listingExamMap , institute_location_table,institute , virtualCityMapping where institute.institute_id not in ('.$instituteIds.') and institute_examinations_mapping_table.institute_id  = listing_type_id and institute.institute_id = listing_type_id and institute_examinations_mapping_table.admission_notification_id = listingExamMap.typeId and listingExamMap.type = "notification" and  institute_examinations_mapping_table.institute_id = institute_location_table.institute_id and listingExamMap.examId IN ('.$blogId.') and listing_type="institute"  and institute_location_table.city_id = virtualCityMapping.city_id  '.$newAddCityClause.$addCountryClause.$addWhereClause.'  and listings_main.status="live"  and listingExamMap.status="live" group by institute_id';
            $queryCmd = "select SQL_CALC_FOUND_ROWS * from ($queryCmd) as t group by t.institute_id limit 0,1";
            // error_log_shiksha("FAAD QUERY::".$queryCmd);
            $query = $this->db->query($queryCmd);
            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $this->db->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }
            $totalRows += $cmsRows;
        }

        $mainArr = array();
        array_push($mainArr,array(
                    array(
                        'results'=>array($msgArray,'struct'),
                        'total'=>array($totalRows,'string'),
                        ),'struct')
                );//close array_push

        $response = array($mainArr,'struct');
        return $response;
    }

    function getTotalListingCountForCriteria($dbHandle, $criteria = array()) {

        switch ($criteria['listingType']){
            case 'institute':
                $data = $this->getTotalInstituteCountForCriteria($dbHandle, $criteria);
                break;
            case 'course':
                $data = $this->getTotalCourseCountForCriteria($dbHandle, $criteria);
                break;
            case 'scholarship':
                $data = $this->getTotalScholarshipCountForCriteria($dbHandle, $criteria);
                break;
        }
        return $data;
    }

    function getTotalInstituteCountForCriteria($dbHandle, $criteria =array()){
        if(isset($criteria['testprep'])){
            return $this->getTotalInstituteCountForExams($dbHandle,$criteria);
        }

        if(isset($criteria['countryId']) && $criteria['countryId'] != 1){
           // $countryId = $criteria['countryId'];
            $addCountryClause = ' and institute_location_table.country_id in ('.addslashes($criteria['countryId']).')';
        }
        else{
           // $countryId = 'select countryId from countryTable';
            $addCountryClause = ' ';
        }

        if(isset($criteria['cityId']) &&  $criteria['cityId'] !='' && $criteria['cityId'] != 1)
        {
            $addCityClause  = ' and institute_location_table.city_id in ('.addslashes($cityId).') ';
        }
        else{
            $addCityClause  = '  ';
        }

        $categoryId=$this->getChildIds($dbHandle, $criteria['categoryId']);

        $addGroupClause = '';
        $addSelectItem = ' ';
        if(isset($criteria['groupBy']) && strlen($criteria['groupBy']) > 0){
            switch (strtolower($criteria['groupBy'])){
                case 'category':
                    $addGroupClause = ' group by categoryBoardTable.parentId ';
                    $addSelectItem = ', categoryBoardTable.parentId as criteria ';
                    break;
                case 'country':
                    $addGroupClause = ' group by institute_location_table.country_id ';
                    $addSelectItem = ', institute_location_table.country_id as criteria ';
                    break;
            }
        }


        $query = "select count(distinct institute.institute_id)  as numListings $addSelectItem  from institute, listing_category_table,listings_main, institute_location_table, categoryBoardTable where category_id = boardId and category_id in ($categoryId) and listing_category_table.institute_id=institute.institute_id   and listings_main.status='live' and listings_main.listing_type = 'institute' and listing_category_table.status='live' and listing_category_table.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause $addCityClause $addGroupClause ";
        // error_log_shiksha("$query");
        $resultSet = $dbHandle->query($query);
        $response = array();
        if(strlen($addGroupClause)> 0){
            foreach($resultSet->result_array() as $result) {
                $response['numInstitutes'][$result['criteria']] = $result['numListings'];
            }
        }else{
            foreach($resultSet->result_array() as $result) {
                $response['numInstitutes'][0] = $result['numListings'];
            }

        }
        return $response;
    }

    function getTotalCourseCountForCriteria($dbHandle, $criteria =array()){
        if(isset($criteria['testprep'])){
            return $this->getTotalCoursesCountForExams($dbHandle,$criteria);
        }


        if(isset($criteria['countryId']) && $criteria['countryId'] != 1){
//            $countryId = $criteria['countryId'];
            $addCountryClause = ' and institute_location_table.country_id in ('.addslashes($criteria['countryId']).')';
        }
        else{
//            $countryId = 'select countryId from countryTable';
            $addCountryClause = ' ';
        }

        if(isset($criteria['cityId']) &&  $criteria['cityId'] !='' && $criteria['cityId'] != 1)
        {
            $addCityClause  = ' and institute_location_table.city_id in ('.addslashes($cityId).') ';
        }
        else{
            $addCityClause  = '  ';
        }

        $categoryId=$this->getChildIds($dbHandle, $criteria['categoryId']);

        $addGroupClause = '';
        $addSelectItem = ' ';
        if(isset($criteria['groupBy']) && strlen($criteria['groupBy']) > 0){
            switch (strtolower($criteria['groupBy'])){
                case 'category':
                    $addGroupClause = ' group by categoryBoardTable.parentId ';
                    $addSelectItem = ', categoryBoardTable.parentId as criteria ';
                    break;
                case 'country':
                    $addGroupClause = ' group by institute_location_table.country_id ';
                    $addSelectItem = ', institute_location_table.country_id as criteria ';
                    break;
            }
        }



        $query = "select count(distinct course_details.course_id) as numListings $addSelectItem from course_details, listing_category_table, listings_main, institute_location_table,categoryBoardTable  where category_id = boardId and category_id in ($categoryId) and listing_category_table.listing_type_id = course_details.course_id and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and institute_location_table.institute_id = course_details.institute_id  $addCountryClause  $addCityClause  $addGroupClause ";
        $resultSet = $dbHandle->query($query);
        $response = array();
        if(strlen($addGroupClause)> 0){
            foreach($resultSet->result_array() as $result) {
                $response['numCourses'][$result['criteria']] = $result['numListings'];
            }
        }else{
            foreach($resultSet->result_array() as $result) {
                $response['numCourses'][0] = $result['numListings'];
            }

        }
        return $response;
    }

    function getTotalScholarshipCountForCriteria($dbHandle, $criteria =array()){
        if(!isset($criteria['countryId']) ||  $criteria['countryId'] == 1 || $criteria['countryId'] =='')
        {
            $countryArr   = array();
            $countryWhere = '';
        }
        else{
            $countryArr   = explode(',', $criteria['countryId']);
            $countryWhere = " and scholarship.country_id in (?)";
        }
        $categoryId=$this->getChildIds($dbHandle, $criteria['categoryId']);
        $addGroupClause = '';
        $addSelectItem = ' ';
        if(isset($criteria['groupBy']) && strlen($criteria['groupBy']) > 0){
            switch (strtolower($criteria['groupBy'])){
                case 'category':
                    $addGroupClause = ' group by categoryBoardTable.parentId ';
                    $addSelectItem = ', categoryBoardTable.parentId as criteria ';
                    break;
                case 'country':
                    $addGroupClause = ' group by scholarship.country_id ';
                    $addSelectItem = ', scholarship.country_id as criteria ';
                    break;
            }
        }


        $query = "select  count(distinct scholarship.scholarship_id) as numListings $addSelectItem from scholarship, scholarship_category_table,listings_main ,categoryBoardTable  where category_id =  boardId and  category_id in ($categoryId) and scholarship_category_table.scholarship_id=scholarship.scholarship_id  $countryWhere and listings_main.listing_type = 'scholarship' and listings_main.status='live' and listings_main.listing_type_id = scholarship.scholarship_id $addGroupClause ";
        // error_log_shiksha($query);
        $resultSet = $dbHandle->query($query, array($countryArr));
        $response = array();
        if(strlen($addGroupClause)> 0){
            foreach($resultSet->result_array() as $result) {
                $response['numScholarships'][$result['criteria']] = $result['numListings'];
            }
        }else{
            foreach($resultSet->result_array() as $result) {
                $response['numScholarships'][0] = $result['numListings'];
            }

        }
        return $response;
    }


    function getCoursesForHomePageS($dbHandle, $parameters){

        $cacheLib = $this->load->library('cacheLib');
        
        // error_log_shiksha("LISTING:".print_r($parameters,true));
        $appId=$parameters['0'];
        $askedCategoryId = $parameters['1'];
        $categoryId=$this->getChildIds($dbHandle, $parameters['1']);
        $countryId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $pageKey=$parameters['5'];
        $cityId=$parameters['6']==""?1:$parameters['6'];
        $relaxFlag =$parameters['7'];

        $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,$askedCategoryId);
        $qryArr = array();
        if($countryId == 1 || $countryId =='')
        {
            $addCountryClause = ' ';
        }
        else{
            $qryArr[] = explode(',', $countryid);
            $addCountryClause = ' and institute_location_table.country_id in (?)';
        }

        if(isset($cityId) &&  $cityId !='' && $cityId !=1)
        {
            $qryArr[] = explode(',', $cityId);
            $addCityClause  = ' and institute_location_table.city_id in (?) ';
        }
        else{
            $addCityClause  = '  ';
        }

        $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id , course_details.course_id,course_details.courseTitle,institute.logo_link, listings_main.pack_type as pack_type from course_details, PageCourseDb, institute, listing_category_table, institute_location_table, listings_main where PageCourseDb.CourseId = course_details.course_id and listing_category_table.listing_type_id = course_details.course_id and listing_category_table.category_id in ($categoryId) and PageCourseDb.KeyId in ($pageKey) and course_details.institute_id =  institute.institute_id and institute_location_table.institute_id = course_details.institute_id $addCountryClause $addCityClause and CURDATE() >= PageCourseDb.StartDate and CURDATE() <= PageCourseDb.EndDate and listings_main.listing_type='course' and listings_main.listing_type_id = course_details.course_id and listings_main.status='live' and listing_category_table.listing_type='course' and listing_category_table.listing_type_id = course_details.course_id and listing_category_table.status='live' group by course_details.course_id order by listings_main.viewCount DESC";

        // error_log_shiksha("LISTING: CMS QUERY:".$queryCmd);
        $query = $dbHandle->query($queryCmd, $qryArr);
        //what are CMS Rows
        $cmsRows=$query->num_rows();
        //init the response array
        $courseArray = array();
        $courseIds = ' -1 ';
        $end = $start + $count;
        $resultsFetched = 0;
        $counter = 0;
        if($cmsRows > $start) {
            foreach ($query->result() as $row){
                if(strlen($courseIds)>0){
                    $courseIds .= ' , ';
                }
                $courseIds .= "$row->course_id";
                if($counter >= $start && $counter < $end){
                    $resultsFetched += 1;
                    $optionalArgs = array();
                    $locQueryCmd = 'select * from institute_location_table where institute_id=? order by institute_location_id asc ';
                    $queryTemp = $dbHandle->query($locQueryCmd, array($row->institute_id));
                    $locationArrayTemp = array();
                    $l = 0;
                    foreach ($queryTemp->result() as $rowTemp) {
                        array_push($locationArrayTemp,array(
                                    array(
                                        'city_id'=>array($rowTemp->city_id,'string'),
                                        'country_id'=>array($rowTemp->country_id,'string'),
                                        'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                        'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                        'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                        ),'struct')
                                );//close array_push
                                $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                                $l++;
                    }

                    $courseArrayTemp = array();
                    $optionalArgs['institute'] = $row->institute_name;

                    if($row->logo_link == '' || $row->logo_link==NULL){
                        $logo_link = "/public/images/noPhoto.gif";
                    }
                    else{
                        $logo_link = $row->logo_link;
                    }

                    $instituteUrl  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                    $courseUrl = getSeoUrl($row->course_id,'course',$row->courseTitle,$optionalArgs);

                    if($row->pack_type > 0 && $row->pack_type !=7){
                        $isSendQuery = 1;
                    }else{
                        $isSendQuery = 0;
                    }

                    array_push($courseArray,array(
                                array(
                                    'title'=>array($row->courseTitle,'string'),
                                    'id'=>array($row->course_id,'string'),
                                    'logo_link'=>array($logo_link,'string'),
                                    'url'=>array($courseUrl,'string'),
                                    'institute_name'=>array($row->institute_name,'string'),
                                    'instituteUrl'=>array($instituteUrl,'string'),
                                    'isSponsored'=>array('true','string'),
                                    'isSendQuery'=>array($isSendQuery,'string'),
                                    'locationArr'=>array($locationArrayTemp,'struct'),
                                    ),'struct')
                            );//close array_push
                }
                $counter++;
            }
        }
        else{
            foreach ($query->result() as $row){
                if(strlen($courseIds)>0){
                    $courseIds .= ' , ';
                }
                $courseIds .= "$row->course_id";
            }
        }

        if($count > $resultsFetched){
            $mainListingCount = $count - $resultsFetched;
            if(($resultsFetched == 0) && ($start >= $cmsRows)){
                $mainListingStart = $start - $cmsRows;
            }
            else{
                $mainListingStart = 0;
            }
            if($pageKey == 1){
                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, course_details.course_id, course_details.courseTitle, institute.institute_id,institute.institute_name,institute.logo_link , listings_main.pack_type as pack_type from course_details, institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp  where category_id in ($categoryId) and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id  and institute_location_table.institute_id = course_details.institute_id and course_details.institute_id = institute.institute_id $addCountryClause  $addCityClause and course_details.course_id not in (".$courseIds.") and tSearchSnippetStatTemp.type='course' and tSearchSnippetStatTemp.listingId = course_details.course_id group by course_details.course_id order by tSearchSnippetStatTemp.count  DESC LIMIT $mainListingStart, $mainListingCount ";
            }else{
                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count,course_details.course_id, course_details.courseTitle,  institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from course_details, institute, listing_category_table,listings_main, institute_location_table where category_id in ($categoryId)  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id  and institute_location_table.institute_id = course_details.institute_id and course_details.institute_id = institute.institute_id $addCountryClause  $addCityClause and course_details.course_id not in (".$courseIds.")  group by course_details.course_id order by listings_main.viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
            }

            // error_log_shiksha("LISTING: MAIN QUERY :".$queryCmd);
            $query = $dbHandle->query($queryCmd, $qryArr);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }
            $totalRows += $cmsRows;
            foreach ($query->result() as $row){
                $optionalArgs = array();
                if(strlen($courseIds)>0){
                    $courseIds .= ' , ';
                }
                $courseIds .= "$row->course_id";

                $resultsFetched += 1;
                $locQueryCmd = 'select * from institute_location_table where institute_id=? order by institute_location_id asc ';
                $queryTemp = $dbHandle->query($locQueryCmd, array($row->institute_id));
                $locationArrayTemp = array();
                $l = 0;
                foreach ($queryTemp->result() as $rowTemp) {
                    array_push($locationArrayTemp,array(
                                array(
                                    'city_id'=>array($rowTemp->city_id,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                    'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                    ),'struct')
                            );//close array_push
                    $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                    $l++;
                }
                if($row->logo_link == '' || $row->logo_link==NULL){
                    $logo_link = "/public/images/noPhoto.gif";
                }
                else{
                    $logo_link = $row->logo_link;
                }

                $courseArrayTemp = array();
                $optionalArgs['institute'] = $row->institute_name;
                if($row->pack_type > 0 && $row->pack_type !=7){
                    $isSendQuery = 1;
                }else{
                    $isSendQuery = 0;
                }

                $instituteUrl  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                $courseUrl = getSeoUrl($row->course_id,'course',$row->courseTitle,$optionalArgs);
                array_push($courseArray,array(
                            array(
                                'title'=>array($row->courseTitle,'string'),
                                'id'=>array($row->course_id,'string'),
                                'logo_link'=>array($logo_link,'string'),
                                'url'=>array($courseUrl,'string'),
                                'institute_name'=>array($row->institute_name,'string'),
                                'isSendQuery'=>array($isSendQuery,'string'),
                                'instituteUrl'=>array($instituteUrl,'string'),
                                'locationArr'=>array($locationArrayTemp,'struct'),
                                ),'struct')
                        );//close array_push
            }
            if(($count > $resultsFetched) && $relaxFlag == 1){
                $mainListingCount = $count - $resultsFetched;
                if(($resultsFetched == 0) && ($start >= $cmsRows)){
                    $mainListingStart = $start - $cmsRows;
                }
                else{
                    $mainListingStart = 0;
                }
                $newCategoryId = $this->getAllChildIdsOfParent($dbHandle,$askedCategoryId);
                $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id , course_details.course_id,course_details.courseTitle,institute.logo_link, listings_main.pack_type as pack_type from course_details, PageCourseDb, institute, listing_category_table, institute_location_table, listings_main where PageCourseDb.CourseId = course_details.course_id and listing_category_table.category_id in ($newCategoryId) and course_details.institute_id =  institute.institute_id and institute_location_table.institute_id = course_details.institute_id $addCountryClause $addCityClause and CURDATE() >= PageCourseDb.StartDate and CURDATE() <= PageCourseDb.EndDate  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id and listings_main.listing_type='course' and listings_main.listing_type_id = course_details.course_id and listings_main.status='live' and course_details.course_id not in (".$courseIds.") group by course_details.course_id order by listings_main.viewCount DESC limit $mainListingStart, $mainListingCount";


                // error_log_shiksha("LISTING: CMSEXTENDED QUERY:".$queryCmd);
                foreach ($query->result() as $row){
                    if(strlen($courseIds)>0){
                        $courseIds .= ' , ';
                    }
                    $courseIds .= "$row->course_id";
                    $resultsFetched += 1;
                    $optionalArgs = array();
                    $locQueryCmd = 'select * from institute_location_table where institute_id='.$row->institute_id.' order by institute_location_id asc ';
                    $queryTemp = $dbHandle->query($locQueryCmd);
                    $locationArrayTemp = array();
                    $l = 0;
                    foreach ($queryTemp->result() as $rowTemp) {
                        array_push($locationArrayTemp,array(
                                    array(
                                        'city_id'=>array($rowTemp->city_id,'string'),
                                        'country_id'=>array($rowTemp->country_id,'string'),
                                        'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                        'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                        'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                        ),'struct')
                                );//close array_push
                        $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                        $l++;
                    }

                    $courseArrayTemp = array();
                    $optionalArgs['institute'] = $row->institute_name;

                    if($row->logo_link == '' || $row->logo_link==NULL){
                        $logo_link = "/public/images/noPhoto.gif";
                    }
                    else{
                        $logo_link = $row->logo_link;
                    }

                    if($row->pack_type > 0 && $row->pack_type !=7){
                        $isSendQuery = 1;
                    }else{
                        $isSendQuery = 0;
                    }

                    $instituteUrl  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                    $courseUrl = getSeoUrl($row->course_id,'course',$row->courseTitle,$optionalArgs);
                    array_push($courseArray,array(
                                array(
                                    'title'=>array($row->courseTitle,'string'),
                                    'id'=>array($row->course_id,'string'),
                                    'logo_link'=>array($logo_link,'string'),
                                    'url'=>array($courseUrl,'string'),
                                    'institute_name'=>array($row->institute_name,'string'),
                                    'instituteUrl'=>array($instituteUrl,'string'),
                                    'isSponsored'=>array('true','string'),
                                    'isSendQuery'=>array($isSendQuery,'string'),
                                    'locationArr'=>array($locationArrayTemp,'struct'),
                                    ),'struct')
                            );//close array_push
                }

                if($count > $resultsFetched){
                    $mainListingCount = $count - $resultsFetched;
                    if(($resultsFetched == 0) && ($start >= $cmsRows)){
                        $mainListingStart = $start - $cmsRows;
                    }
                    else{
                        $mainListingStart = 0;
                    }
                    if($pageKey == 1){
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, course_details.course_id, course_details.courseTitle, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from course_details, institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp  where category_id in ($newCategoryId)  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id  and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id  and institute_location_table.institute_id = course_details.institute_id and course_details.institute_id = institute.institute_id $addCountryClause  $addCityClause and course_details.course_id not in (".$courseIds.") and tSearchSnippetStatTemp.type='course' and tSearchSnippetStatTemp.listingId = course_details.course_id group by course_details.course_id order by tSearchSnippetStatTemp.count  DESC LIMIT $mainListingStart, $mainListingCount ";
                    }else{
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count,course_details.course_id, course_details.courseTitle,  institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from course_details, institute, listing_category_table,listings_main, institute_location_table where category_id in ($newCategoryId)  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id  and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id  and institute_location_table.institute_id = course_details.institute_id and course_details.institute_id = institute.institute_id $addCountryClause  $addCityClause and course_details.course_id not in (".$courseIds.")  group by course_details.course_id order by listings_main.viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
                    }

                    // error_log_shiksha("LISTING: MAIN QUERY hu:".$queryCmd);
                    $query = $dbHandle->query($queryCmd, $qryArr);

                    foreach ($query->result() as $row){
                        $optionalArgs = array();
                        if(strlen($courseIds)>0){
                            $courseIds .= ' , ';
                        }
                        $courseIds .= "$row->course_id";

                        $resultsFetched += 1;
                        $locQueryCmd = 'select * from institute_location_table where institute_id='.$row->institute_id.' order by institute_location_id asc ';
                        $queryTemp = $dbHandle->query($locQueryCmd);
                        $locationArrayTemp = array();
                        $l = 0;
                        foreach ($queryTemp->result() as $rowTemp) {
                            array_push($locationArrayTemp,array(
                                array(
                                    'city_id'=>array($rowTemp->city_id,'string'),
                                    'country_id'=>array($rowTemp->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                    'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                ),'struct')
                            );//close array_push
                            $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                            $l++;
                        }
                        if($row->logo_link == '' || $row->logo_link==NULL){
                            $logo_link = "/public/images/noPhoto.gif";
                        }
                        else{
                            $logo_link = $row->logo_link;
                        }
                        $courseArrayTemp = array();
                        $optionalArgs['institute'] = $row->institute_name;

                        if($row->pack_type > 0 && $row->pack_type !=7){
                            $isSendQuery = 1;
                        }else{
                            $isSendQuery = 0;
                        }

                        $instituteUrl  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                        $courseUrl = getSeoUrl($row->course_id,'course',$row->courseTitle,$optionalArgs);
                        array_push($courseArray,array(
                            array(
                                'title'=>array($row->courseTitle,'string'),
                                'id'=>array($row->course_id,'string'),
                                'logo_link'=>array($logo_link,'string'),
                                'url'=>array($courseUrl,'string'),
                                'institute_name'=>array($row->institute_name,'string'),
                                'instituteUrl'=>array($instituteUrl,'string'),
                                'isSendQuery'=>array($isSendQuery,'string'),
                                'locationArr'=>array($locationArrayTemp,'struct'),
                            ),'struct')
                        );//close array_push
                    }
        }
            }
        }
        else{
            if($pageKey == 1){
                $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count from course_details, institute, listing_category_table,listings_main, institute_location_table, tSearchSnippetStatTemp  where category_id in ($categoryId)  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id  and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id  and institute_location_table.institute_id = course_details.institute_id and institute.institute_id = course_details.institute_id  $addCountryClause $addCityClause   and tSearchSnippetStatTemp.listingId = course_details.course_id and tSearchSnippetStatTemp.type = 'course'  group by course_details.course_id ";
            }else{
                $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count from course_details, institute, listing_category_table,listings_main, institute_location_table where category_id in ($categoryId)  and listing_category_table.status='live' and listing_category_table.listing_type = 'course' and listing_category_table.listing_type_id = course_details.course_id and listings_main.status='live' and listings_main.listing_type = 'course' and listings_main.listing_type_id = course_details.course_id and course_details.institute_id =  institute.institute_id and institute_location_table.institute_id = course_details.institute_id $addCountryClause $addCityClause   group by course_details.course_id ";
            }
            // error_log_shiksha("LISTING: TO CALC:".$queryCmd);
            $query = $dbHandle->query($queryCmd, $qryArr);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }
        }
        $finalResultArray = array();
        array_push($finalResultArray,array(
                    array(
                        'total'=>array($totalRows,'string'),
                        'courses'=>array($courseArray,'struct')
                        ),'struct'));//close array_push
        $response = array($finalResultArray,'struct');
        return $response;
    }

    function getRelevantCourse($dbHandle,$instituteId, $categoryId,  $optionalArgs){
        $courseArrayTemp = array();
//        $queryCourse = ' select course_details.courseTitle as title, course_details.course_id from institute_courses_mapping_table,course_details, listing_category_table, listings_main where listing_category_table.listing_type="course" and listings_main.listing_type="course" and listings_main.listing_type_id =  course_details.course_id and listings_main.status = "live" and category_id in ('.$categoryId.')  and listing_category_table.listing_type_id = course_details.course_id and institute_courses_mapping_table.course_id = course_details.course_id and institute_courses_mapping_table.institute_id= '.$instituteId.' and listings_main.version = course_details.version and listings_main.version =  listing_category_table.version LIMIT 1';
        $categoryIdArr = explode(',', $categoryId);
        $queryCourse = "select course_details.courseTitle as title, course_details.course_type as type, course_details.duration_unit as duration_unit, course_details.duration_value as duration_value, course_details.course_id from course_details, listing_category_table where listing_category_table.listing_type='course' and category_id in (?)  and listing_category_table.listing_type_id = course_details.course_id and course_details.status = 'live' and course_details.institute_id= ? and course_details.version= listing_category_table.version LIMIT 1 ";
        // error_log_shiksha("LISTING:".$queryCourse);
        $queryTemp = $dbHandle->query($queryCourse,array($categoryIdArr, $instituteId));
        $recognitionStatus = array();
        $courseEligibility = array();
        if($queryTemp->num_rows() > 0){
            foreach ($queryTemp->result() as $rowTemp) {
                $courseUrl = getSeoUrl($rowTemp->course_id,'course',$rowTemp->title,$optionalArgs);
                $courseFee = $this->getFeeForCourse($dbHandle,$rowTemp->course_id);
                $courseEligibility = $this->getEligibilityForCourse($dbHandle,$rowTemp->course_id);
                $courseSalientFeatures  = $this->getSalientFeaturesForCourse($dbHandle,$rowTemp->course_id);
                $recognitionStatus = $this->getRecognitionStatusForCourse($dbHandle,$rowTemp->course_id);
                //// error_log(print_r($recognitionStatus,true),3,'/home/aakash/Desktop/aakash.log');
                array_push($courseArrayTemp,array(
                    array(
                        'course_id'=>array($rowTemp->course_id,'string'),
                        'title'=>array(htmlspecialchars($rowTemp->title),'string'),
                        'type'=>array(htmlspecialchars($rowTemp->type),'string'),
                        'duration_value'=>array(htmlspecialchars($rowTemp->duration_value),'string'),
                        'duration_unit'=>array(htmlspecialchars($rowTemp->duration_unit),'string'),
                        'url'=>array($courseUrl,'string'),
                        'fee'=>array($courseFee,'string'),
                        'eligibility'=>array($courseEligibility,'struct'),
                        'salient'=>array($courseSalientFeatures,'string'),
                        'recognitionStatus'=>array($recognitionStatus,'struct')
                    ),'struct'));//close array_push
            }
        }
        else{
            $queryCourse = ' select course_details.courseTitle as title, course_details.course_id from course_details where status = "live" and institute_id= ? LIMIT 1';
            // error_log_shiksha("LISTING:".$queryCourse);
            $queryTemp = $dbHandle->query($queryCourse,array($instituteId));
            foreach ($queryTemp->result() as $rowTemp) {
                $courseUrl = getSeoUrl($rowTemp->course_id,'course',$rowTemp->title,$optionalArgs);
                $courseFee = $this->getFeeForCourse($dbHandle,$rowTemp->course_id);
                $courseEligibility = $this->getEligibilityForCourse($dbHandle,$rowTemp->course_id);
                $courseSalientFeatures  = $this->getSalientFeaturesForCourse($dbHandle,$rowTemp->course_id);
                $recognitionStatus = $this->getRecognitionStatusForCourse($dbHandle,$rowTemp->course_id);
                //error_log(print_r($recognitionStatus,true),3,'/home/aakash/Desktop/aakash.log');
                array_push($courseArrayTemp,array(
                    array(
                        'course_id'=>array($rowTemp->course_id,'string'),
                        'title'=>array(htmlspecialchars($rowTemp->title),'string'),
                        'type'=>array(htmlspecialchars($rowTemp->type),'string'),
                        'duration_value'=>array(htmlspecialchars($rowTemp->duration_value),'string'),
                        'duration_unit'=>array(htmlspecialchars($rowTemp->duration_unit),'string'),
                        'url'=>array($courseUrl,'string'),
                        'fee'=>array($courseFee,'string'),
                        'eligibility'=>array($courseEligibility,'struct'),
                        'salient'=>array($courseSalientFeatures,'string'),
                        'recognitionStatus'=>array($recognitionStatus,'struct')
                    ),'struct'));//close array_push
            }
        }
        return $courseArrayTemp;
    }

    function getRecognitionStatusForCourse($dbHandle,$course_id){
        $queryCmd = "SELECT * FROM course_attributes WHERE attribute IN('AICTEStatus', 'UGCStatus','DECStatus') AND course_id = ? AND status = 'live'";
        $query = $dbHandle->query($queryCmd,array($course_id));
        $recognition = array();
        foreach($query->result_array() as $row){
            array_push($recognition,array(
            'attribute' => $row['attribute'],
             'amount'=> $row['value']));
        }
        return $recognition;
    }



    function getFeeForCourse($dbHandle,$course_id){
    $queryCmd = "SELECT fees_value,fees_unit FROM course_details WHERE course_id = ? AND status = 'live'";
    $query = $dbHandle->query($queryCmd,array($course_id));
    $fee= array();
    foreach($query->result() as $row){
        $fee['value'] = $row->fees_value;
        $fee['unit'] = $row->fees_unit;

    }
      return $fee;
    }

    function getEligibilityForCourse($dbHandle,$course_id){
   $queryCmd = "SELECT id,typeId,examId,typeOfMap,marks,marks_type,valueIfAny,blogTitle,bt.acronym from listingExamMap lem LEFT JOIN blogTable bt ON lem.examId = bt.blogId WHERE lem.typeId = ? AND lem.status = 'live' AND bt.status = 'live' ";
   $queryTemp = $dbHandle->query($queryCmd,array($course_id));
   $courseExamArray = array();

   foreach ($queryTemp->result() as $rowTemp) {
				array_push($courseExamArray,array(
                                    'id'=>$rowTemp->id,
				    'examId'=>$rowTemp->examId,
                                    'marks'=>$rowTemp->marks,
				    'marks_type'=>$rowTemp->marks_type,
				    'practiceTestsOffered'=>$rowTemp->valueIfAny,
                                    'exam_name'=>$rowTemp->blogTitle,
                                    'acronym'=>$rowTemp->acronym
				)
				);
			}

    return $courseExamArray ;

    }

     function getSalientFeaturesForCourse($dbHandle,$course_id){
        $queryCmd = "SELECT max(version) as version FROM course_features WHERE listing_id = ?";
        $query = $dbHandle->query($queryCmd,array($course_id));
        $version = $row->version;
        if($version!= NULL){
        $queryCmd = "select cf.listing_id,cf.salient_feature_id,sf.feature_name,sf.value from course_features cf JOIN salient_features sf
            			on cf.salient_feature_id = sf.feature_id where cf.listing_id = ? and version = ? ";
        $query = $dbHandle->query($queryCmd,array($course_id,$version));
        }else{
        $queryCmd = "select cf.listing_id,cf.salient_feature_id,sf.feature_name,sf.value from course_features cf JOIN salient_features sf
            			on cf.salient_feature_id = sf.feature_id where cf.listing_id = ? and cf.status = 'live'";
        $query = $dbHandle->query($queryCmd,array($course_id));
        }
        
        $salient = array();
        foreach($query->result_array() as $row){
            $salient[] = $row;
        }
        return $salient;
    }
    
    function getMediaDataForMultipleInstitutes($dbHandle, $instituteIds){
        $retArr = array();
        if(trim($instituteIds) == ""){
            return $retArr;
        }
        $instituteIdArr = explode(',', $instituteIds);
        $locQueryCmd = "select photo_count, video_count, alumni_rating,institute_id from institute_mediacount_rating_info where institute_id in(?)";
        
        $queryTemp = $dbHandle->query($locQueryCmd, array($instituteIdArr));
        
        foreach ($queryTemp->result() as $rowTemp) {
            $retArr[$rowTemp->institute_id]['photo'] = $rowTemp->photo_count;
            $retArr[$rowTemp->institute_id]['video'] = $rowTemp->video_count;
            $retArr[$rowTemp->institute_id]['rating'] = $rowTemp->alumni_rating;
        }
        //error_log("photo_count".print_r($retArr,true));
        return $retArr;
    }

    function getInstitutesForMultipleCourses($dbHandle, $courseIds){
        $courseIdArr = explode(',', $courseIds);
        $locQueryCmd = "SELECT institute_id, course_id FROM course_details WHERE course_id IN (?) AND status = 'live'";
        // error_log($locQueryCmd.'LISTING');
        $queryTemp = $dbHandle->query($locQueryCmd, array($courseIdArr));
        $retArr = array();
        if($queryTemp->num_rows() == 0)
        {
               return $retArr;
        }
        foreach ($queryTemp->result() as $rowTemp) {
            $retArr[$rowTemp->course_id] = $rowTemp->institute_id;
        }
        return $retArr;
    }


    function getMediaData($dbHandle, $instituteId){
                $locQueryCmd = "select photo_count, video_count, alumni_rating from institute_mediacount_rating_info where institute_id = ?";
                $queryTemp = $dbHandle->query($locQueryCmd,array($instituteId));
                $retArr = array();

                $rowTemp = $queryTemp->row();

                $retArr['photo'] = $rowTemp->photo_count;
                $retArr['video'] = $rowTemp->video_count;
                $retArr['rating'] = $rowTemp->alumni_rating;

                return $retArr;
    }

    /*
     * NOT IN USE
     */
    function getLocationsInOrder($dbHandle, $instituteId, $addCountryClause, $newAddCityClause, $cityId){
        return array();
error_log('Code Usability Check:listingmodel: getLocationsInOrder', 3, '/tmp/listing_server.log');
                $cacheLib = $this->load->library('cacheLib');

                $locQueryCmd = 'select * from institute_location_table, virtualCityMapping where institute_id= ? and virtualCityMapping.city_id = institute_location_table.city_id '.$addCountryClause.' '.$newAddCityClause .' and institute_location_table.status = "live" order by institute_location_id asc ';
                $queryTemp = $dbHandle->query($locQueryCmd,array($instituteId));
                $locationArrayTemp = array();
                $onlyOneCityFlag = 0;
                $tempLocationArray = array();
                $l = 0;
                foreach ($queryTemp->result() as $rowTemp) {
                    if((!isset($cityId) ||  $cityId =='' || $cityId ==1 || $cityId == $rowTemp->virtualCityId) && $onlyOneCityFlag == 0){
                        array_push($locationArrayTemp,array(
                            array(
                                'city_id'=>array($rowTemp->virtualCityId,'string'),
                                'country_id'=>array($rowTemp->country_id,'string'),
                                'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                'address'=>array(htmlspecialchars($rowTemp->address),'string'),
                                'locality'=>array(htmlspecialchars($rowTemp->locality_name),'string')
                            ),'struct')
                        );//close array_push
                        $onlyOneCityFlag = 1;
                    }
                    if(!isset($tempLocationArray[$rowTemp->institute_location_id]) || $tempLocationArray[$rowTemp->institute_location_id] != 1)
                    {
                        $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                        $l++;
                        $tempLocationArray[$rowTemp->institute_location_id] = 1;
                    }
                }
                $retArr['optionalArgs'] = $optionalArgs;
                $retArr['onlyOneCityFlag'] = $onlyOneCityFlag;
                $retArr['locationArrayTemp'] = $locationArrayTemp;
                return $retArr;
    }

/*  This function is defined by Amit Kuksal on 8th March 2011
 *  Purspose: Category Page Revamp Changes
 *  It takes fields to be selected (comma sepearated list), listing id, and listing type on the basis of which we get the header image information.
 */
    function getHeaderImageInfo($dbHandle, $fieldsToBeSelected = "thumb_url", $listingID, $listingType = "institute") {
error_log('Code Usability Check:listingmodel: getHeaderImageInfo', 3, '/tmp/listing_server.log');
        $queryHeaderImage = 'SELECT '.$fieldsToBeSelected.' FROM header_image WHERE listing_id = ? AND listing_type = ? AND status = "live" LIMIT 0, 1';
        $rsHeaderImage = $dbHandle->query($queryHeaderImage,array($listingID,$listingType));
        $rowHeaderImage = $rsHeaderImage->result();
        // error_log("\n\n Amitc HeaderImage for ins id ".$listingID."= ".print_r($rowHeaderImage[0]->thumb_url,true),3,'/home/infoedge/Desktop/log.txt');
        return ($rowHeaderImage);

    }   // End of function getHeaderImageInfo($fieldsToBeSelected = "thumb_url", $listingID, $listingType = "institute").

    function getInstituteLogo($logo_link){

        if($logo_link == '' || $logo_link == NULL){
            $logo_link = "/public/images/noPhoto.gif";
        }
        else{
            $logo_link = $logo_link;
        }
        return $logo_link;
    }

    function getInterestedInstitutes($dbHandle, $categoryId, $subcategoryId, $countryId, $cityId, $start, $count){

        $this->load->library('cacheLib');
		$cacheLib = new cacheLib();
                //$cacheLib->clearCache();
        $key = md5('getInterestedInstitutes'.$subcategoryId.$cityId);
        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
            $parameters[0] = "1";
            $parameters[1] = $subcategoryId;
            $parameters[2] = $countryId;
            $parameters[3] = $start;
            $parameters[4] = $count;
            $parameters[5] = "-1";
            $parameters[6] = $cityId;
            $parameters[7] = "-1";
            $parameters[8] = "-1";
            $parameters[9] = "0";// free institutes
            $parameters[10] = "0"; // state institutes
            $instituteList = $this->getInstitutesBasicDetails($dbHandle, $parameters);
            // error_log(count($instituteList));
            if(count($instituteList) >= $count){
                //nothing to do
            }
            else{
                $parentId =  $this->getParentCatId($dbHandle,$subcategoryId);
                $instituteIds = '-1';
                for($i = 0 ; $i < count($instituteList) ; $i++){
                    if(strlen($instituteIds)>0){
                        $instituteIds .= ' , ';
                    }
                    $instituteIds .= $instituteList[$i]['id'];
                }
                $parameters[1] = $parentId;
                $parameters[4] = $count-count($institutesStrongMatch);
                $parameters[8] = $instituteIds;
                $institutesWeakMatch = $this->getInstitutesBasicDetails($dbHandle, $parameters);
                $instituteList =  array_merge($instituteList, $institutesWeakMatch);
                // error_log(count($instituteList));
                if(count($instituteList) < $count){
                    //3rd condition
                    $instituteIds = '-1';
                    for($i = 0 ; $i < count($instituteList) ; $i++){
                        if(strlen($instituteIds)>0){
                            $instituteIds .= ' , ';
                        }
                        $instituteIds .= $instituteList[$i]['id'];
                    }
                    $parameters[1] = $subcategoryId;
                    $parameters[4] = $count-count($instituteList);
                    $parameters[6] = 1;
                    $parameters[8] = $instituteIds;
                    $institutesWeakMatch = $this->getInstitutesBasicDetails($dbHandle, $parameters);
                    $instituteList =  array_merge($instituteList, $institutesWeakMatch);

                    // error_log(count($instituteList));
                    if(count($instituteList) < $count){
                        //4th condition
                        $instituteIds = '-1';
                        for($i = 0 ; $i < count($instituteList) ; $i++){
                            if(strlen($instituteIds)>0){
                                $instituteIds .= ' , ';
                            }
                            $instituteIds .= $instituteList[$i]['id'];
                        }
                        $parameters[1] = $parentId;
                        $parameters[4] = $count-count($instituteList);
                        $parameters[6] = 1;
                        $parameters[8] = $instituteIds;
                        $institutesWeakMatch = $this->getInstitutesBasicDetails($dbHandle, $parameters);
                        $instituteList =  array_merge($instituteList, $institutesWeakMatch);

                    }
                }
            }
            // error_log(count($instituteList));
            if(count($instituteList) < $count){
                //get free listings with strong match
                $instituteIds = '-1';
                for($i = 0 ; $i < count($instituteList) ; $i++){
                    if(strlen($instituteIds)>0){
                        $instituteIds .= ' , ';
                    }
                    $instituteIds .= $instituteList[$i]['id'];
                }
                $parameters[1] = $subcategoryId;
                $parameters[2] = $countryId;
                $parameters[4] = $count-count($instituteList);
                $parameters[6] = $cityId;
                $parameters[8] = $instituteIds;

                $institutesStrongMatch = $this->getFreeInstitutesBasicDetails($dbHandle, $parameters);
                $instituteList =  array_merge($instituteList, $institutesStrongMatch);
            }
            $response = array();
            array_push($response,array(
                array(
                    'institutes'=>array(base64_encode(serialize($instituteList)),'string')
                ),'struct'));//close array_push
            $response = array($response,'struct');
            $cacheLib->store($key,$response,86400);
            return $response;
        }
        else{

            return $cacheLib->get($key);
        }
    }

    function getFreeInstitutesBasicDetails($dbHandle, $parameters){
        $appId=$parameters['0'];
        $askedCategoryId = $parameters['1'];
        if($askedCategoryId == 1){
            return array();
        }
        $categoryId=$this->getChildIds($dbHandle,$parameters['1']);
        $countryId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $origPageKey=$parameters['5'];
        $cityId=$parameters['6']==""?1:$parameters['6'];
        $relaxFlag = $parameters['7'];
        $notTheseInstitutes = isset($parameters['8'])?$parameters['8']:'-1';
        $freeInstitutes =(isset($parameters['9']) && $parameters['9']!="")?$parameters['9']:1;
        $stateInstitutes =(isset($parameters['10']) && $parameters['10']!="")?$parameters['10']:1;

        if($countryId == 1 || $countryId ==''){
            $addCountryClause = ' ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }else{
            $addCountryClause = ' and institute_location_table.country_id in ('.addslashes($countryId).')';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }

        if(isset($cityId) &&  $cityId !='' && $cityId !=1){
            $addCityClause  = ' and institute_location_table.city_id in ('.addslashes($cityId).') ';
            $newAddCityClause  = ' and institute_location_table.city_id = virtualCityMapping.city_id and virtualCityMapping.virtualCityId =  '.addslashes($cityId).' ';
        }else{
            $addCityClause  = '  ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }

        $dbHandle = $this->getDbHandle();

        $instituteIds = "-1";
        //fetch paid institutes
        $mainListingCount = $count - $resultsFetched;
        if(($resultsFetched == 0) && ($start >= $cmsRows)){
            $mainListingStart = $start - $cmsRows;
        }
        else{
            $mainListingStart = 0;
        }
        $queryCmd =  "select * from ( select  institute.institute_id,institute.institute_name,institute.logo_link , listings_main.pack_type as pack_type from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in ($categoryId) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (".$instituteIds.")   and institute.institute_id not in ($notTheseInstitutes) and listings_main.pack_type != 1 and listings_main.pack_type != 2 and listings_main.version= institute.version and listings_main.version=institute_location_table.version group by institute.institute_id order by listings_main.viewCount  DESC limit 0,50 ) as t order by rand() LIMIT ?,? ";

        // error_log_shiksha("LISTING:MAIN QUERY:".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($mainListingStart, $mainListingCount));
        $instituteArray = array();
        foreach ($query->result() as $row){
            // error_log("puneet LISTING:MAIN QUERY:".$queryCmd);
            $resultsFetched += 1;
            $optionalArgs = array();
            $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
            $locationArrayTemp = serialize($locations['locationArrayTemp']);
            $optionalArgs = $locations['optionalArgs'];
            $onlyOneCityFlag = $locations['onlyOneCityFlag'];
            $optionalArgs['institute'] = $row->institute_name;
            $courseArrayTemp =  serialize($this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs));
            $instituteType = $this->getInstituteType($dbHandle,$row->institute_id);
            $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
            $logo_link =  $this->getInstituteLogo($row->logo_link);
            $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
            $headerImageUrl = $this->getHeaderImageUrl($dbHandle,$row->institute_id);
            $aimaRating = $this->getAimaRating($dbHandle,$row->institute_id);
            $overallRating = $this->getOverallRating($dbHandle,$row->institute_id);

                    array_push($instituteArray,array(
                                    'title'=>$row->institute_name,
                                    'id'=>$row->institute_id,
                                    'logo_link'=>$logo_link,
                                    'url'=>$url,
                                    'isSendQuery'=>$isSendQuery,
                                    'locationArr'=>$locationArrayTemp,
                                    'courseArr'=>$courseArrayTemp,
                                    'headerImageUrl'=>$headerImageUrl,
                                    'aimaRating'=>$aimaRating,
                                    'instituteType'=>$instituteType,
                                    'overallRating'=>$overallRating));        }
        return $instituteArray;
    }

    /*
     * NOT IN USE
     */
    function getInstitutesBasicDetails($dbHandle, $parameters){
        return array();
error_log('Code Usability Check:listingmodel: getInstitutesBasicDetails', 3, '/tmp/listing_server.log');
        $appId=$parameters['0'];
        $askedCategoryId = $parameters['1'];
        if($askedCategoryId == 1){
            return array();
        }
        $categoryId=$this->getChildIds($dbHandle,$parameters['1']);
        $countryId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $origPageKey=$parameters['5'];
        $cityId=$parameters['6']==""?1:$parameters['6'];
        $relaxFlag = $parameters['7'];
        $notTheseInstitutes = isset($parameters['8'])?$parameters['8']:'-1';
        $freeInstitutes =(isset($parameters['9']) && $parameters['9']!="")?$parameters['9']:1;
        $stateInstitutes =(isset($parameters['10']) && $parameters['10']!="")?$parameters['10']:1;

        $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,$askedCategoryId);
        if($countryId == 1 || $countryId ==''){
            $addCountryClause = ' ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }else{
            $addCountryClause = ' and institute_location_table.country_id in ('.$countryId.')';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }

        if(isset($cityId) &&  $cityId !='' && $cityId !=1){
            $addCityClause  = ' and institute_location_table.city_id in ('.$cityId.') ';
            $newAddCityClause  = ' and institute_location_table.city_id = virtualCityMapping.city_id and virtualCityMapping.virtualCityId =  '.$cityId.' ';
        }else{
            $addCityClause  = '  ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }

        $paidInstituteClause = ' and (listings_main.pack_type = 1 OR listings_main.pack_type = 2) ';
        $dbHandle = $this->getDbHandle();
        //Fetching results for ticked institutes (set by CMS)
        $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from PageCollegeDb, institute, listing_category_table,institute_location_table, listings_main where listing_category_table.listing_type='institute' and PageCollegeDb.listing_type_id = institute.institute_id and listing_category_table.listing_type_id = institute.institute_id and listing_category_table.category_id in ($categoryId) and PageCollegeDb.KeyId in ($pageKey) and institute_location_table.institute_id = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate and PageCollegeDb.status='live' and listings_main.listing_type='institute' and listings_main.listing_type_id = institute.institute_id and listings_main.status='live' and institute.institute_id not in ($notTheseInstitutes) and listings_main.version = institute.version and listings_main.version=institute_location_table.version group by institute_id order by listings_main.viewCount DESC ";
        // error_log("puneet LISTING:CMS QUERY:".$queryCmd);
        $query = $dbHandle->query($queryCmd);
        //what are CMS Rows
        $cmsRows=$query->num_rows();
        //init the response array
        $instituteArray = array();
        $instituteIds = ' -1 ';
        $end = $start + $count;
        $resultsFetched = 0;
        $counter = 0;
        if($cmsRows > $start) {
            foreach ($query->result() as $row){
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= "$row->institute_id";
                if($counter >= $start && $counter < $end){
                    $resultsFetched += 1;
                    $optionalArgs = array();
                    $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
                    $locationArrayTemp = serialize($locations['locationArrayTemp']);
                    $optionalArgs = $locations['optionalArgs'];
                    $onlyOneCityFlag = $locations['onlyOneCityFlag'];
                    $optionalArgs['institute'] = $row->institute_name;
                    $courseArrayTemp =  serialize($this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs));
                    $instituteType = $this->getInstituteType($dbHandle,$row->institute_id);
                    $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                    $logo_link =  $this->getInstituteLogo($row->logo_link);
                    $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                    $headerImageUrl = $this->getHeaderImageUrl($dbHandle,$row->institute_id);
                    $aimaRating = $this->getAimaRating($dbHandle,$row->institute_id);
                    $overallRating = $this->getOverallRating($dbHandle,$row->institute_id);

                    array_push($instituteArray,array(
                                    'title'=>$row->institute_name,
                                    'id'=>$row->institute_id,
                                    'logo_link'=>$logo_link,
                                    'url'=>$url,
                                    'isSponsored'=>'true',
                                    'isSendQuery'=>$isSendQuery,
                                    'locationArr'=>$locationArrayTemp,
                                    'courseArr'=>$courseArrayTemp,
                                    'headerImageUrl'=>$headerImageUrl,
                                    'aimaRating'=>$aimaRating,
                                    'instituteType'=>$instituteType,
                                    'overallRating'=>$overallRating));
                }
                $counter++;
            }
        }
        else{
            foreach ($query->result() as $row){
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= "$row->institute_id";
            }
        }

        if($count > $resultsFetched){
            //fetch paid institutes
            $mainListingCount = $count - $resultsFetched;
            if(($resultsFetched == 0) && ($start >= $cmsRows)){
                $mainListingStart = $start - $cmsRows;
            }
            else{
                $mainListingStart = 0;
            }
            if($origPageKey == 1){
                $queryCmd =  " select  institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type='institute' and category_id in ($categoryId) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (".$instituteIds.") and tSearchSnippetStatTemp.type='institute' and tSearchSnippetStatTemp.listingId = institute.institute_id  and institute.institute_id not in ($notTheseInstitutes) $paidInstituteClause and listings_main.version= institute.version and listings_main.version= institute_location_table.version group by institute.institute_id order by tSearchSnippetStatTemp.count  DESC LIMIT $mainListingStart, $mainListingCount ";
            }else{
                $queryCmd =  " select  institute.institute_id,institute.institute_name,institute.logo_link , listings_main.pack_type as pack_type from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in ($categoryId) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (".$instituteIds.")   and institute.institute_id not in ($notTheseInstitutes) $paidInstituteClause and listings_main.version = institute.version and listings_main.version= institute_location_table.version group by institute.institute_id order by rand() , listings_main.viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
            }

            // error_log_shiksha("LISTING:MAIN QUERY:".$queryCmd);
            // error_log("puneet LISTING:MAIN QUERY:".$queryCmd);
            $query = $dbHandle->query($queryCmd);
            foreach ($query->result() as $row){
                $resultsFetched += 1;
                $optionalArgs = array();
                $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
                $locationArrayTemp = serialize($locations['locationArrayTemp']);
                $optionalArgs = $locations['optionalArgs'];
                $onlyOneCityFlag = $locations['onlyOneCityFlag'];
                $optionalArgs['institute'] = $row->institute_name;
                $courseArrayTemp =  serialize($this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs));
                $instituteType = $this->getInstituteType($dbHandle,$row->institute_id);
                $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                $logo_link =  $this->getInstituteLogo($row->logo_link);
                $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                $headerImageUrl = $this->getHeaderImageUrl($dbHandle,$row->institute_id);
                $aimaRating = $this->getAimaRating($dbHandle,$row->institute_id);
                $overallRating = $this->getOverallRating($dbHandle,$row->institute_id);
                array_push($instituteArray,array(
                    'title'=>$row->institute_name,
                    'id'=>$row->institute_id,
                    'logo_link'=>$logo_link,
                    'url'=>$url,
                    'isSendQuery'=>$isSendQuery,
                    'locationArr'=>$locationArrayTemp,
                    'courseArr'=>$courseArrayTemp,
                    'headerImageUrl'=>$headerImageUrl,
                    'aimaRating'=>$aimaRating,
                    'instituteType'=>$instituteType,
                    'overallRating'=>$overallRating));
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= "$row->institute_id";
            }
        }
        return $instituteArray;
    }
function getInstituteType($dbHandle,$institute_id){
    $queryCmd = "SELECT institute_type From institute WHERE institute_id = ? AND status = 'live'";
    $query = $dbHandle->query($queryCmd,array($institute_id));
    $instituteType = '';
    foreach($query->result_array() as $row){
        $instituteType = $row['institute_type'];
    }
    return $instituteType;
}
function getHeaderImageUrl($dbHandle,$institute_id){

    $queryCmd = "SELECT thumb_url FROM header_image WHERE institute_id = ? LIMIT 1";
    $query = $dbHandle->query($queryCmd,array($institute_id));
    $url;

    foreach($query->result() as $row){
        $url = urlencode($row->thumb_url);
    }
    return $url;
}

function getAimaRating($dbHandle,$institute_id){

    $queryCmd = "SELECT aima_rating FROM institute WHERE institute_id = ? AND status = 'live'";
    $query = $dbHandle->query($queryCmd,array($institute_id));
    $rating= 0;
    foreach($query->result()as $row)
    {
        $rating =$row->aima_rating;
    }
    return $rating;

}

function getOverallRating($dbHandle,$institute_id){
    //$institute_id = '26465';
    $queryCmd = "SELECT criteria_rating FROM talumnus_feedback_rating WHERE institute_id= ? AND criteria_id = '4' AND status = 'published'";
    //error_log(print_r($queryCmd,true),3,'/home/aakash/Desktop/aakash.log');
    $query = $dbHandle->query($queryCmd,array($institute_id));
    $rating = array();
    foreach($query->result_array() as $row){
        $rating[] = $row;
    }
    //error_log(print_r($rating,true),3,'/home/aakash/Desktop/aakash.log');
    return $rating;

}

    function getCourseLevelForCluster($courselevel,$courselevel1)
    {

        //Get the course level (Hack method)
        // Hack for Diploma (For diploma course level is diploma and courselevel1 is also diploma for all others course level1 shows under graduate/post graduate values)

//		$newstring = str_replace("Under Graduate","UG",$resultrow);
        if(strpos($courselevel1,"Under Graduate") !== false)
        {
            $courselevel1 = 'UG' ;
        }
        else
        {
            if(strpos($courselevel1,"Post Graduate") !== false)
                $courselevel1 = 'PG';
        }


        if($courselevel1 == 'NULL' || trim($courselevel1) == trim($courselevel))
            $courseLevel = trim($courselevel);
        else
            $courseLevel = trim($courselevel1).' '.trim($courselevel);
            return trim($courseLevel);
    }


    /*
     * NOT IN USE
     */
    function getInstitutesForSelection($dbHandle,$data)
    {
        return array();
        error_log('Code Usability Check:listingmodel: getInstitutesForSelection', 3, '/tmp/listing_server.log');
        // error_log("\nAmit Calling",3,'/home/infoedge/Desktop/log.txt');
        $cacheLib = $this->load->library('cacheLib');
        
        $starttime = microtime(true);
        $type = $data['type'];
        $type1 = $type;
        $pageKey = $data['pagekey'];
        $courseLevelClause = $data['courselevelclause'];
        $courseTypeClause = $data['coursetypeclause'];
        $notTheseInstitutes = $data['nottheseinstitutes'];
        $orderbyclause = $data['orderbyclause'];
        $subcategoryId = $data['subcategoryId'];
        $countryId = $data['countryId'];
        $start = $data['start'];
        $count = $data['count'];
        $cityId = $data['cityId'];
        $seocoursename = $data['seocoursename'];
        $typename = $data['typename'];
        $seoinstitname = $data['seoinstiname'];
        $resultsFetched = $data['resultsFetched'];
        $limitflag = 1;
        $courseinclusion = $data['courseinclusion'];
        $countarr = array('main'=>2,'paid'=>'2','free'=>1);
        $countval = $countarr[$type];
        $pagename = $data['pagename'];
        $degree_course_info = $data['degree_course_info'];

        $optionalCategoryId = $data['optionalCategoryId'];

        $mainInstitutes = array();
        $mainInstituteIds = array();
        $mainids = array();
        $response = array();
        $selectCond = '';

        /*-------------------------------------------------------------------------------------------------------
         *  NEW CATEGORY PAGES CODE (optimization + revamp) STARTS HERE
         *  This is added by Amit Kuksal on 20th April 2011 as now we will have the denormalized table strucutre
         *  in the DB and then fetch the data from there only..
         * -----------------------------------------------------------------------------------------------------*/

                // We will join with the course table only in case when we have to get the course grouping
                if((strpos($typename,'course') !== false) || ($courseinclusion == 1)){
                   // $selectionlist = ' , SUBSTRING_INDEX(group_concat( distinct categoryPageData.course_id ORDER BY categoryPageData.course_order), ",", 4) as courseids'; // Updated by Amit Kuksal on 14th Feb 2011 to show the courses by the already set course order.
                    $selectionlist = ' , group_concat( distinct categoryPageData.course_id ORDER BY categoryPageData.course_order) as courseids'; // Updated by Amit Kuksal on 14th Feb 2011 to show the courses by the already set course order.
                }
                else{
                  $selectionlist = '';
                }

                $selectclause = "SELECT SQL_CALC_FOUND_ROWS (count(*)-1) as count $selectionlist, categoryPageData.course_id, categoryPageData.institute_id, categoryPageData.pack_type, categoryPageData.city_id, categoryPageData.country_id ";

                $fromClause = ' FROM categoryPageData';

                switch($type)
                {
                    case 'main' : $fromClause .= ' , PageCollegeDb ';
                                  break;
                    case 'paid' :
                                  if(!($cityId == 'All' || $cityId == 0 || $cityId == ''))
                                  $fromClause .= ' , virtualCityMapping ';
                                  break;
                    case 'free' :
                                  if(!($cityId == 'All' || $cityId == 0 || $cityId == ''))
                                      $fromClause .= ' , virtualCityMapping ';
                                  break;
                    default :
                                  if(!($cityId == 'All' || $cityId == 0 || $cityId == ''))
                                      $fromClause .= ' , virtualCityMapping ';
                                  break;

                }   // End of switch($type) clause.

                //  In case $optionalCategoryId has the categories IDs as value then we have to get the records for these categories
                if($optionalCategoryId != "")
                    $whereClause .= " WHERE categoryPageData.category_id in ($optionalCategoryId) AND categoryPageData.status = 'live' AND categoryPageData.institute_id not in ($notTheseInstitutes)  ";

                //Amit Singhal: Changes in case the category ids contain 1 = Parent category_id check not required
                elseif(in_array(1, $subcategoryId)){
                    $whereClause .= "categoryPageData.status = 'live' AND categoryPageData.institute_id not in ($notTheseInstitutes)  ";
                }else{
                    $whereClause .= " WHERE categoryPageData.category_id in ($subcategoryId) AND categoryPageData.status = 'live' AND categoryPageData.institute_id not in ($notTheseInstitutes)  ";
                }
                //Amit Singhal : End changes

                // Taking Country table in this join also to get the Country name..
                $additionalWhereClause = "";
                $selectclause .= ", countryTable.name as countryName ";
                $fromClause .= ', countryTable';
                $additionalWhereClause = " and countryTable.countryId = categoryPageData.country_id ";

                $whereClause .= $additionalWhereClause.$courseTypeClause. $courseLevelClause;

                if($type == 'main')
                {
                    $whereClause .= " and PageCollegeDb.KeyId in ($pageKey) and PageCollegeDb.listing_type_id = categoryPageData.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate and PageCollegeDb.status='live' and PageCollegeDb.listing_type='institute' ";
                    $limitClause = '';
                }
                else
                {
                    if(!($cityId == 'All' || $cityId == 0 || $cityId == ''))
                    {
                        $whereClause .= " and categoryPageData.city_id = virtualCityMapping.city_id and virtualCityMapping.virtualCityId = $cityId";
                    }
                    else
                    {
                        $whereClause .= ' and categoryPageData.country_id in ('.$countryId.')';
                    }
                    if($type == 'paid')
                    {
                        $whereClause .= " and (pack_type = 1 OR pack_type = 2)";
                    }
                    if($type == 'free')
                        $whereClause .= ' and pack_type != 1 and pack_type !=2';

                    $limitClause = ' limit '.$start.','.$count;
                }

                $whereClause .= " GROUP BY categoryPageData.institute_id ";
                $orderbyclause = "ORDER BY categoryPageData.course_order";
                $whereClause .= " $orderbyclause $limitClause";

                $queryCmd = $selectclause.$fromClause.$whereClause;

               // error_log("\n\n Amit queryCmd = : ".print_r($queryCmd,true),3,'/home/infoedge/Desktop/log.txt');

        /*-------------------------------------------------------------------------------------------------------
         *  NEW CATEGORY PAGES CODE (optimization + revamp) END HERE
         * -----------------------------------------------------------------------------------------------------*/

        $coursearray = array();
        $coursemappingarray = array();
        $query = $dbHandle->query($queryCmd);
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        $totalRows = 0;
        $allcoursesids = '';

        foreach ($queryTotal->result() as $rowTotal)
        {
            $totalRows = $rowTotal->totalRows;
        }
        $endtime = microtime(true);
        $mainRows = $query->num_rows();
        $checkcondition = 1;
        if($type == 'main')
        {
            $checkcondition = ($mainRows > $start) ? 1 : 0;
        }
        else
        {
            $checkcondition = ($mainRows > 0) ? 1:0;
        }

        $instituteIds = ' -1 ';
        $resultsFetched = 0;
        $counter = 0;
        $i = 0;
        $type2 = $type;
        $institutesInformation = array();

        // error_log("\n\n type1 : ".print_r($pagename,true)." , type = ".print_r($type1,true),3,'/home/infoedge/Desktop/log.txt');
        $instLocation = array();
        if($checkcondition) {
             if($pagename == categorypages)
             $countval = 4; // Get the info for 4 courses..

            $insIdString = '';
            $firstCounter=0;
            foreach ($query->result() as $row){

                // new identifiers to cpature all header image info in one go
                $firstCounter++;
                if($firstCounter > 1)
                $insIdString.=",".$row->institute_id;
                else
                $insIdString.=$row->institute_id;
                $instLocation[$row->institute_id]['city'] = $row->city_id;
                $instLocation[$row->institute_id]['country'] = $row->country_id;

                // In case of "coursegrouping" i.e. naukrishiksha page and All filters, we will place one more join to get the additional Course Info..
                if($typename == 'coursegrouping') {
                    $query_script = "SELECT course_id, course_type, if(course_level = 'Dual Degree', 'Degree', course_level) as courselevel, course_level_1, course_level_2, courseTitle, duration_value, duration_unit FROM course_details WHERE course_id = $row->course_id and status = 'live'";
                    $query_course_info =  $dbHandle->query($query_script);
                    $course_info_row = $query_course_info->row();
                    // error_log("\n\n ".$query_script." \n institute_info_row : ".print_r($course_info_row,true),3,'/home/infoedge/Desktop/log.txt');
                    $row->course_type = $course_info_row->course_type;
                    $row->courselevel = $course_info_row->courselevel;
                    $row->course_level_1 = $course_info_row->course_level_1;
                    $row->course_level_2 = $course_info_row->course_level_2;
                    $row->courseTitle = $course_info_row->courseTitle;
                    $row->duration_value = $course_info_row->duration_value;
                    $row->duration_unit = $course_info_row->duration_unit;
                } //*/

                if($type == 'main')
                {
                    if($counter < $start)
                    {
                        continue;
                    }
                }
                if($type1 == 'all')
                {

                    $sql = "select KeyId from PageCollegeDb where KeyId in($pageKey) and listing_type_id = $row->institute_id and CURDATE() >= StartDate and CURDATE() <= EndDate and listing_type='institute' and status = 'live'";
                    $query1 = $dbHandle->query($sql);

                    $sql = "select instituteid from categoryselector where instituteid = $row->institute_id and CURDATE() >= startdate and CURDATE() <= enddate";

                    $query2 = $dbHandle->query($sql);
                    if($query2->num_rows() > 0)
                    {
                        $type2 = 'category';
                        $countval = 2;
                    }
                    else
                    {
                        if($query1->num_rows() > 0)
                        {
                            $type2 = 'main';
                            $countval = 2;
                        }
                        else
                        {
                            if($row->pack_type == 1 || $row->pack_type == 2)
                            {
                                $type2 = 'paid';
                                $countval = 2;
                            }

                            if($row->pack_type != 1 && $row->pack_type != 2)
                            {
                                $type2 = 'free';
                                $countval = 1;
                            }
                        }
                    }
                }


                // error_log("\n\n countval : ".print_r($countval,true)." , type = ".print_r($type1,true),3,'/home/infoedge/Desktop/log.txt');
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= "$row->institute_id";
                $resultsFetched += 1;
                $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
		//Added by Ankur on 25th Oct to add the Institute location to the URL
		$locationArrayTemp = array();
		$cityName = array($cacheLib->get("city_".$row->city_id),'string');
		$countryName = array($cacheLib->get("country_".$row->country_id),'string');
		$locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
		$optionalArgs = array();
		$optionalArgs['location'] = $locationArrayTemp;

                $institutesInformation[$row->institute_id]['optionalArgs'] = $optionalArgs; //---------------------
                $cityName  = $cacheLib->get("city_".$row->city_id);

                $mainInstitutes[$counter]['countryName'] = $row->countryName;
                $mainInstitutes[$counter]['id'] = $row->institute_id;

                $mainInstitutes[$counter]['url']=$url;

                if($type2 == 'main')
                {
                    $mainInstitutes[$counter]['isSponsored']='true';
                }
                $mainInstitutes[$counter]['coursecount']=$row->count+1;;
                $mainInstitutes[$counter]['isSendQuery']=$isSendQuery;
                $mainInstitutes[$counter]['flagname'] = $type2;
                $mainInstitutes[$counter]['city'] = $cityName;
                // $mainInstitutes[$counter]['type']=$type;

                $courseidsarr = array();
                // error_log("\n\n3017, Type name : ".$typename.", page name : ".$pagename,3,'/home/infoedge/Desktop/log.txt');
                if(strpos($typename,'course') === false)
                {
                    // error_log("\n\n Type name : ".$typename." , courseinclusion : ".$courseinclusion,3,'/home/infoedge/Desktop/log.txt');

                    $coursearray = array_merge($coursearray,$course_details['course']);
			//Reason: In case of no course inclusion, get the course id's for each institute with the added where clause
			if($courseinclusion!=1){
			    $queryCmdCourse = "select group_concat(distinct course_id ORDER BY `course_order`) as courseids from course_details , institute where course_details.institute_id = institute.institute_id and course_details.status = 'live' and institute.institute_id IN ('$row->institute_id') group by institute.institute_id ";  // Updated by Amit Kuksal on 14th Feb 2011 to show the courses by the already set course order.

			    $queryCourse = $dbHandle->query($queryCmdCourse);
			    $courseidsarr = array();
			    if($queryCourse->num_rows() > 0){
				$rowCourse = $queryCourse->result();
				$courseidsarr = explode(',',$rowCourse[0]->courseids);
			    }
                            // error_log("\n\n Amit 3012 Inside : ".$row->institute_id."=  ".print_r($courseidsarr,true),3,'/home/infoedge/Desktop/log1.txt');
			}
			else{
                            // error_log("\n\n Amit  Ins id : ".$row->institute_id."=  ".print_r($row->courseids,true),3,'/home/infoedge/Desktop/log.txt');
			    $courseidsarr = explode(',',$row->courseids);
			}

                    // error_log("\n courseidsarr : ".print_r($courseidsarr, true),3,'/home/infoedge/Desktop/log.txt');

                    $mainInstitutes[$counter]['coursecount']=$row->count + 1;

                    $mainInstitutes[$counter]['coursesIds'] = $courseidsarr;

                }
                else // For Naukri Shiksha page if course type is "All"
                {

                    if($row->course_level_1 == 'NULL' || trim($row->course_level_1) == trim($row->courselevel))
                        $courseLevel_1 = trim($row->courselevel);
                    else
                        $courseLevel_1 = trim($row->course_level_1).' '.trim($row->courselevel);
                    if($row->course_level_1 == "Post Graduate Diploma")
                        $courseLevel_1 = trim($row->course_level_1);
			//Added by Ankur on 25th Oct to add the Institute location to the URL
			$locationArrayTemp = array();
			$cityName = array($cacheLib->get("city_".$row->city_id),'string');
			$countryName = array($cacheLib->get("country_".$row->country_id),'string');
			$locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
			$optionalArgs = array();
			$optionalArgs['location'] = $locationArrayTemp;
			// $optionalArgs['institute'] = $institute_info_row->institute_Name;  //---------------------
			//End Modifications by Ankur
                        // error_log("\n\n  institute_info_row : ".print_r($locationArrayTemp,true),3,'/home/infoedge/Desktop/log.txt');
                        $myCoursearray[$row->institute_id]['optionalArgs'] = $optionalArgs;  //---------------------
                        $myCoursearray[$row->institute_id]['URL'] = $row->URL;                //---------------------

                        $myCoursearray[$row->institute_id]['course_id'] = $row->course_id;
                        $myCoursearray[$row->institute_id]['course_title'] = $row->courseTitle;
                        $myCoursearray[$row->institute_id]['course_level'] = $courseLevel_1;
                        $myCoursearray[$row->institute_id]['course_level_1'] = $row->courselevel;
                        $myCoursearray[$row->institute_id]['course_level_2'] = $row->course_level_1;
                        $myCoursearray[$row->institute_id]['course_type'] = $row->course_type;
                        $myCoursearray[$row->institute_id]['duration'] = $row->duration_value.' '.$row->duration_unit;
                        // $myCoursearray[$row->institute_id]['courseurl'] = $courseUrl;  //---------------------
                        $myCoursearray[$row->institute_id]['institute_id'] = $row->institute_id;

                        $mainInstitutes[$counter]['coursesIds'] = $row->course_id;
                }
                $counter++;

            } // End of foreach ($query->result() as $row).

            ///------------------------------------------------
                //*
                // Need to get the Institutes' info now ..
                $query_script = "SELECT institute_id, institute_name, abbreviation, aima_rating, usp, logo_link, listings_main.listing_seo_url as URL , viewCount as views FROM `institute`, listings_main WHERE institute_id IN (".$insIdString.") and institute_id = listing_type_id and listing_type = 'institute' and listings_main.status = 'live' and institute.status = 'live'";
                $query_institute_info =  $dbHandle->query($query_script);
                // error_log("\n\n ".$pagename." \n institute_info_row : ".print_r($query_script,true),3,'/home/infoedge/Desktop/log.txt');

                // For Most Viewed TAB the the sorting based on the no. of views would be changed. || Cat Page Phase 2 changes || 03 Aug 2011 by Amit K.
                if($pagename == "categorymostviewed") {
                    $tempInstituteArray = array();
                    $query_view_count = "select sum(no_Of_Views) as totalViews, listing_id from view_Count_Details where listing_id IN (".$insIdString.") AND `listingType` IN ('institute_free', 'institute_paid') AND `is_Deleted` = 0 group by listing_id";
                    $query_view_count_info =  $dbHandle->query($query_view_count);
                    foreach ($query_view_count_info->result() as $query_view_count_row) {
                        $tempInstituteArray[$query_view_count_row->listing_id]['views'] = $query_view_count_row->totalViews;
                    }

                    // error_log("\n\n count: ".count($tempInstituteArray).", Main ins count : ".count()." \n query_view_count : ".print_r($tempInstituteArray,true),3,'/home/infoedge/Desktop/log.txt');
                    
                }   // End of if($pagename == "categorymostviewed").

                foreach ($query_institute_info->result() as $institute_info_row)
                {
                    if(isset($institute_info_row->URL) && (string)$institute_info_row->URL != ''){
                        $url = $institute_info_row->URL;
                    }
                    else{
                        $url  = getSeoUrl($institute_info_row->institute_id, 'institute', $institute_info_row->institute_name, $institutesInformation[$institute_info_row->institute_id]['optionalArgs'], 'old');
                    }

                    $institutesInformation[$institute_info_row->institute_id]["URL"] = $url;
                    $institutesInformation[$institute_info_row->institute_id]["institute_name"] = $institute_info_row->institute_name;
                    $institutesInformation[$institute_info_row->institute_id]["logo_link"] = $this->getInstituteLogo($institute_info_row->logo_link);
                    if($pagename == "categorymostviewed")
                        $institutesInformation[$institute_info_row->institute_id]["views"] = $tempInstituteArray[$institute_info_row->institute_id]['views'];
                    else
                        $institutesInformation[$institute_info_row->institute_id]["views"] = $institute_info_row->views;
                    
                    $institutesInformation[$institute_info_row->institute_id]["aima_rating"] = $institute_info_row->aima_rating;
                    $institutesInformation[$institute_info_row->institute_id]["usp"] = $institute_info_row->usp;
                    $institutesInformation[$institute_info_row->institute_id]["abbreviation"] = $institute_info_row->abbreviation;


                    if(strpos($typename,'course') !== false) {
                            // error_log("\n\n AMIT : ".$query_script." \n institute_info_row : ".print_r($query_script,true),3,'/home/infoedge/Desktop/log.txt');
                          $myCoursearray[$institute_info_row->institute_id]['optionalArgs']['institute'] = $institute_info_row->institute_Name;

                          if(isset($myCoursearray[$institute_info_row->institute_id]['URL']) && (string)$myCoursearray[$institute_info_row->institute_id]['URL'] != ''){
                                    $courseUrl = $myCoursearray[$institute_info_row->institute_id]['URL'];
                          }else{
                                    $courseUrl = getSeoUrl($myCoursearray[$institute_info_row->institute_id]['course_id'],'course',$myCoursearray[$institute_info_row->institute_id]['course_title'],$myCoursearray[$institute_info_row->institute_id]['optionalArgs'],'old');
                          }

                          $myCoursearray[$institute_info_row->institute_id]['courseurl'] = $courseUrl;

                          unset($myCoursearray[$institute_info_row->institute_id]['URL']);
                          unset($myCoursearray[$institute_info_row->institute_id]['optionalArgs']);
                    }

                }   // End of foreach ($query_institute_info->result() as $institute_info_row).
                // error_log("\n\n count: ".count($tempInstituteArray).", Main ins count : ".count($institutesInformation)." \n query_view_count : ".print_r($tempInstituteArray,true),3,'/home/infoedge/Desktop/log.txt');
            //----------------------------------------------------*/


           // get all header image info; Bhuvnesh
           $qryHeader = "SELECT listing_id, thumb_url FROM header_image WHERE listing_id IN (".$insIdString.") AND listing_type = 'institute' AND status = 'live' group by listing_id";
           $queryH =  $dbHandle->query($qryHeader);
           $headIns= array();
           $headThumb= array();
           foreach ($queryH->result() as $ro)
           {
                array_push($headIns,$ro->listing_id);
                array_push($headThumb,$ro->thumb_url);
           }
            //error_log("\n\n query = ".print_r($headIns,true),3,'/home/infoedge/Desktop/log.txt');
 
           // Amit Singhal : Get Media data and alumia rating
           if($pagename != 'naukrishiksha'){
            $qryMedia = "select photo_count, video_count, alumni_rating,institute_id from institute_mediacount_rating_info where institute_id IN (".$insIdString.")";
            $queryMed =  $dbHandle->query($qryMedia);
            $photoIns = array();
            $videoIns = array();
            $aluIns = array();
            $mediaIns = array();
            foreach ($queryMed->result() as $ro1)
            {
                 array_push($photoIns,$ro1->photo_count);
                 array_push($videoIns,$ro1->video_count);
                 array_push($aluIns,$ro1->alumni_rating);
                 array_push($mediaIns,$ro1->institute_id);
            }
           }
           // Amit Singhal : Get Media data and aluminai rating
           
           $newCounter=0;
           foreach ($query->result() as $row)
           {
               // The following code added by Amit Kuksal on 17th May 2011 to collect the institute info...
                $mainInstitutes[$newCounter]['url']=$institutesInformation[$row->institute_id]["URL"];
                $mainInstitutes[$newCounter]['institute_Name'] = $institutesInformation[$row->institute_id]["institute_name"];
                $mainInstitutes[$newCounter]['logo_link'] = $institutesInformation[$row->institute_id]["logo_link"];
                $mainInstitutes[$newCounter]['views']=$institutesInformation[$row->institute_id]["views"];
                $mainInstitutes[$newCounter]['aima_rating']=$institutesInformation[$row->institute_id]["aima_rating"];
                $mainInstitutes[$newCounter]['usp']=$institutesInformation[$row->institute_id]["usp"];
                $mainInstitutes[$newCounter]['abbreviation']=$institutesInformation[$row->institute_id]["abbreviation"];
               // End code by Amit Kuksal.


               if(in_array($row->institute_id, $headIns))
                {
                    $indX= array_keys($headIns,$row->institute_id);
                    $mainInstitutes[$newCounter]['headerImageUrl'] = $headThumb[$indX[0]];
                }
                else
                {
                    $mainInstitutes[$newCounter]['headerImageUrl'] = '';
                }
                // Amit Singhal : Get Media data and aluminai rating
                if(in_array($row->institute_id, $mediaIns) && ($pagename != 'naukrishiksha')){
                    $indX= array_keys($mediaIns,$row->institute_id);
                    // $mainInstitutes[$newCounter]['mediadata'] = array();
                    $mainInstitutes[$newCounter]['mediadata']['photo'] = $photoIns[$indX[0]];
                    $mainInstitutes[$newCounter]['mediadata']['video'] = $videoIns[$indX[0]];
                    $mainInstitutes[$newCounter]['mediadata']['rating'] = $aluIns[$indX[0]];
                    $mainInstitutes[$newCounter]['alumin_rating'] = $mainInstitutes[$newCounter]['mediadata']['rating'];
                }
                // Amit Singhal : Get Media data and aluminai rating
                $newCounter++;
           }
           // get all header image info End ; Bhuvnesh


        }
        else
        {
            foreach ($query->result() as $row){
                if(strlen($instituteIds)>0){
                    $instituteIds .= ' , ';
                }
                $instituteIds .= "$row->institute_id";
                $instLocation[$row->institute_id]['city'] = $row->city_id;
                $instLocation[$row->institute_id]['country'] = $row->country_id;
            }
        } // End of if($checkcondition).


            if(count($mainInstitutes) > 0) {

                        $allcoursesids = ''; $mainInstituteIds = array(); $mainids = array();
                        $mainInstitutes = $this->subval_sort($mainInstitutes,'views');
                        // error_log("\n\n Amit Main ins =  ".print_r($mainInstitutes,true),3,'/home/infoedge/Desktop/log.txt');
                        $mainInstitutesCount = count ($mainInstitutes);

                        for($countVar = 0; $countVar < $mainInstitutesCount ; $countVar++) {
                            $mainids[$countVar] = $mainInstitutes[$countVar]['id'];

                            $collectedCourseIds = "";

                            if(strpos($typename,'course') === false){
                                $allcoursesids .= $allcoursesids == ""? "": ",";
                                $collectedCourseIds .= $collectedCourseIds == ""? "": ",";
                                if($pagename == "countrypage") {
                                    $mainInstituteIds[$countVar]["'".$mainInstitutes[$countVar]['id']."'"] = $mainInstitutes[$countVar]['coursesIds'];
                                    $collectedCourseIds .= $mainInstitutes[$countVar]['coursesIds'][0]; // Only one in this case..
                                } else {
                                    $mainInstituteIds[$countVar]["'".$mainInstitutes[$countVar]['id']."'"] = $mainInstitutes[$countVar]['coursesIds'];
                                    $collectedCourseIds .= implode(',', $mainInstitutes[$countVar]['coursesIds']);
                                }
                            } else { // i.e. naukri shiksha page.. and mode is "All"
                                $mainInstituteIds[$countVar]["'".$mainInstitutes[$countVar]['id']."'"][0] = $mainInstitutes[$countVar]['coursesIds'];
                                $coursearray[$countVar] = $myCoursearray[$mainInstitutes[$countVar]['id']];
                            }
                             unset($mainInstitutes[$countVar]['coursesIds']);

                             $instituteCourseArray[] = array("0" => $mainInstitutes[$countVar]['id'], "1" => $collectedCourseIds);

                             $allcoursesids .= $collectedCourseIds;

                        }   // End of for($countVar = 0; $countVar < $count ; $countVar++).


                    // error_log("\n\n Amit allcoursesids =  ".print_r($allcoursesids,true),3,'/home/infoedge/Desktop/log.txt');

                    if($allcoursesids != '')  // Checking if we need to get the Courses' info..
                    {
                        // error_log("\n\n $allcoursesids, instLocation  =  ".print_r($instLocation,true),3,'/home/infoedge/Desktop/log1.txt');
                        $coursearray = $this->getCourseDetailsForInstitutes($dbHandle,$allcoursesids,$seocoursename, $instLocation, $degree_course_info);
                    }



		if($pagename == "categorypages" || $pagename == "categorymostviewed" ) { // Will run for category pages only..

                        // Need to call the function here to get the Salient Features and Exam data by passing $instituteCourseArray

                       $reponseDataArray = $this->getExamDataSalientFeaturesForRichSnippets($dbHandle, $instituteCourseArray);

                       // Lets collect the information now..
                       $coursearrayCount = count($coursearray);
                       $reponseDataArrayCount = count($reponseDataArray);

                       for($courseArraycountVariable = 0; $courseArraycountVariable < $coursearrayCount; $courseArraycountVariable++) {

                           for($responseDatacountVariable = 0; $responseDatacountVariable < $reponseDataArrayCount; $responseDatacountVariable++) {

                                $courseCount = count($reponseDataArray[$responseDatacountVariable]["courses"]);

                                for($cnt = 0; $cnt < $courseCount; $cnt++) {
                                     if($coursearray[$courseArraycountVariable]["course_id"] == $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["course_id"]) {
                                        $coursearray[$courseArraycountVariable]["approved_granted_by"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["approved_granted_by"];
                                        $coursearray[$courseArraycountVariable]["affiliated_to"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["affiliated_to"];
                                        $coursearray[$courseArraycountVariable]["eligibility_exams"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["eligibility_exams"];
                                        $coursearray[$courseArraycountVariable]["salient_features_ids"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["salientFeaturesIDs"];

                                    }

                                }   // End of for($cnt = 0; $cnt < $courseCount; $cnt++).

                           }    // End of for($countVariable = 0; $countVariable < $j; $countVariable++).

                       }    // End of for($courseArraycountVariable = 0; $courseArraycountVariable < $coursearrayCount; $courseArraycountVariable++).*/

                       // error_log("\n\n array = ".print_r($reponseDataArray,true),3,'/home/infoedge/Desktop/log.txt');

		 } // End of if($pagename == "categorypages").

                       // error_log("\n\n array upto $j = ".print_r($coursearray,true),3,'/home/infoedge/Desktop/log1.txt');

            } //  End of if(count($mainInstitutes) > 0).

       // error_log("\n\n Amit allcoursesids =  ".print_r($allcoursesids,true),3,'/home/infoedge/Desktop/log.txt');
       // error_log("\n\n Amit mainCourses =  ".print_r($coursearray,true),3,'/home/infoedge/Desktop/log.txt');
       // error_log("\n\n Amit mainInstituteIds =  ".print_r($mainInstituteIds,true),3,'/home/infoedge/Desktop/log.txt');

        $response['mainInstituteIds'] = $mainInstituteIds;
        $response['mainInstitutes'] = $mainInstitutes;
        $response['mainCourses'] = $coursearray;
        $response['notInstitutes'] = $instituteIds;
        $response['totalRecords'] = $totalRows;
        $response['mainids'] = $mainids;
        // error_log(print_r($response,true).'RESPONSE12');
        return $response;
    }


function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	arsort($b);
	foreach($b as $k=>$v) {
		$c[] = $a[$k];
	}
	return $c;
}



    /*
     * NOT IN USE
     */
    function getClustersAccordingtoSelection($dbHandle,$courseLevelClause,$courselevelorder,$subcategoryId,$cityClause,$cityCondition,$courseTypeClause,$course_level_1,$course_level, $optionalCategoryId = "", $pageRecordsSortingOrder = "")
    {
        return array();
        error_log('Code Usability Check:listingmodel: getClustersAccordingtoSelection', 3, '/tmp/listing_server.log');
        $countArray = array();
        $starttime = microtime(true);

        // $queryCmd = "select count(distinct(a.institute_id)) as count,if(course_level = 'Dual Degree','Degree',course_level) as courselevel,course_type,course_level_1,course_level_2 from course_details a inner join listing_category_table b on a.course_id = b.listing_type_id $cityClause where b.listing_type = 'course' $cityCondition and b.category_id in(". ($optionalCategoryId == "" ? $subcategoryId : $optionalCategoryId).")" .$courseLevelClause.$courseTypeClause." and a.status = 'live' and b.status = 'live' group by courselevel,course_type,course_level_1 ".$courselevelorder;

        $queryCmd = "select count(distinct(a.institute_id)) as count,if(course_level = 'Dual Degree','Degree',course_level) as courselevel,course_type,course_level_1,course_level_2 from categoryPageData a $cityClause where  $cityCondition a.category_id in(". ($optionalCategoryId == "" ? $subcategoryId : $optionalCategoryId).")" .$courseLevelClause.$courseTypeClause." and a.status = 'live' group by courselevel,course_type,course_level_1 ".$courselevelorder;

error_log('KKIV: '.$queryCmd);

        //  error_log("\nAmit ".print_r($queryCmd,true),3,'/home/infoedge/Desktop/log.txt');
        $query = $dbHandle->query($queryCmd);
       $endtime = microtime(true);
        if($query->num_rows() == 0)
        {
                    return ($countArray);
        }

        $row1 = $query->result();
        $courseLevel = $this->getCourseLevelForCluster($row1[0]->courselevel,$row1[0]->course_level_1);

        $courseType = trim($row1[0]->course_type);
        $countArray = array();
        $countArray['All'][$courseType] = 0;
        $countArray[$courseLevel][$courseType] = $row1[0]->count;

        $selectedCluster = '';
        foreach ($query->result() as $row){
            $courseLevel_1 = $this->getCourseLevelForCluster($row->courselevel,$row->course_level_1);

            if($courseLevel == $courseLevel_1){
                $countArray[$courseLevel][trim($row->course_type)] = $row->count;
            }
            else{
                $courseLevel = $courseLevel_1;
                $countArray[$courseLevel][trim($row->course_type)] = $row->count;
            }

            if(trim($course_level_1).trim($course_level) == trim($row->course_level_1).trim($row->courselevel))
            {
                $selectedCluster = $courseLevel_1;
            }

            //Make clusters for All
            $newtype = 1;
            //Search for the course type(if already present add to the course type else create one
            foreach($countArray['All'] as $type=>$value)
            {
                if(strtolower(trim($type)) == strtolower(trim($row->course_type)))
                {
                    $newtype = 0;
                    break;
                }
            }
            if($newtype == 0)
            {
                $countArray['All'][trim($type)] += $row->count;
            }
            else
            {
                $countArray['All'][trim($row->course_type)] = $row->count;
            }

        }
        if($selectedCluster == '')
        {
            $selectedCluster = 'All';
            if($firstcall == 1)
                $course_level = 'All';
        }

        // The following code has been added by Amit Kuksal on 18th March 2011 to sort the records within the array instead of by the query
        // For Performnace Optimizationn purpose.

        if(is_array($pageRecordsSortingOrder)) {
            $tmpArray = $countArray;
            unset($countArray);

            $course_level_count = count($pageRecordsSortingOrder["course_level_order"]);
            $course_type_count = count($pageRecordsSortingOrder["course_type_order"]);

            for($countVar = 0; $countVar < $course_level_count; $countVar++) {

                for($courseTypeCountVar = 0; $courseTypeCountVar < $course_type_count; $courseTypeCountVar++) {
                    // error_log("\n ".print_r($pageRecordsSortingOrder["course_level_order"][$countVar], true)."-".print_r($pageRecordsSortingOrder["course_type_order"][$courseTypeCountVar], true)." : ",3,'/home/infoedge/Desktop/log.txt');
                    // error_log(print_r($tmpArray['All']['E-learning'], true),3,'/home/infoedge/Desktop/log.txt');

                    if($tmpArray[$pageRecordsSortingOrder["course_level_order"][$countVar]][$pageRecordsSortingOrder["course_type_order"][$courseTypeCountVar]] == "")
                    continue;

                    $countArray[$pageRecordsSortingOrder["course_level_order"][$countVar]][$pageRecordsSortingOrder["course_type_order"][$courseTypeCountVar]] = $tmpArray[$pageRecordsSortingOrder["course_level_order"][$countVar]][$pageRecordsSortingOrder["course_type_order"][$courseTypeCountVar]];

                }

            }   // End of for($countVar = 0; $countVar < $course_level_count; $countVar++).


        } // End of if(is_array($pageRecordsSortingOrder)). //*/

        $countArray['selectedCluster'] = $selectedCluster;

        // error_log("\n\nAmit ".print_r($countArray,true),3,'/home/infoedge/Desktop/log.txt');

        return $countArray;

    }

    function getListingSponsorDetails($dbHandle,$clientId,$sort)
    {
        error_log('Code Usability Check:listingmodel: getListingSponsorDetails', 3, '/tmp/listing_server.log');
       // error_log('sdfsd');
       $sql = "select userId from tuser where userId = ?  and usergroup in ('cms','enterprise','privileged','sums');";
       // error_log($sql);
       $query = $dbHandle->query($sql,array($clientId));
       if($query->num_rows() == 0)
       {
           return 'Please enter a valid client id';
       }
	$sort = (strtolower($sort) ==='asc'?'asc':'desc');
       // error_log('sdfsd');
	   // enddate ='0000-00-00 00:00:00' check is for the abroad listings because we dont have subscription details now
       $sql =   "select listing_type_id as institute_id, listing_type, if(a.name is NULL,concat(b.name,'(Sub-Category)'),a.name) as name,subscriptionId,listingsubsid, course_level , cityid , stateid , countryid
                from
                tlistingsubscription
                left join categoryBoardTable a on (tlistingsubscription.categoryid = a.boardId)
                left join categoryBoardTable b on (tlistingsubscription.subcategory = b.boardId)
                where clientId = ?
                and status in ('live','".ENT_SA_PRE_LIVE_STATUS."')
		and listing_type in ('institute','university')
                and tlistingsubscription.pagename!='testprep'
                and (enddate > now() OR (enddate ='0000-00-00 00:00:00' and startdate='0000-00-00 00:00:00' and subscriptionid=0))
                order by institute_id ".$sort; // asc/desc need not be escaped

        //error_log("query for getlistingsponsordetails : ".$sql);
        $query = $dbHandle->query($sql,array($clientId));
        $arrforbanners = array();
        $i = 0;
        foreach($query->result() as $row)
        {
            $arrforbanners[$i] = $row;
            $i++;
        }

        $sql = "select t.listing_type_id as institute_id,b.blogTitle as name,t.subscriptionId,t.listingsubsid, t.course_level from tlistingsubscription t left join blogTable b on (t.categoryid = b.blogId) where clientId = ? and t.listing_type in ('institute','university') and t.status in ('live','".ENT_SA_PRE_LIVE_STATUS."') and t.pagename='testprep' and b.status = 'live' and enddate > now() order by institute_id ".$sort;

        // error_log($sql);
        $query = $dbHandle->query($sql,array($clientId));
        $testprep_arrforbanners = array();
        $i = 0;
        foreach($query->result() as $row)
        {
            $testprep_arrforbanners[$i] = $row;
            $i++;
    }
        return array_merge($arrforbanners, $testprep_arrforbanners);
    }

    function changeCouplingStatus($dbHandle,$listingsubsid,$bannerlinkid,$keyword)
    {
       //get the coupled listings and banners for the selected client and category and location
       if($keyword == 'couple')
       {
           $sql = "select * from tcoupling where listingsubsid = ? and bannerlinkid = ? and status = 'coupled'";
           $query =  $dbHandle->query($sql,array($listingsubsid,$bannerlinkid));
           if($query->num_rows() > 0)
           {
               return '-1';
           }

            $data = array(
               'listingsubsid' => $listingsubsid ,
               'bannerlinkid'  => $bannerlinkid ,
               'status'        => 'coupled',
               'comment'       => '',
            );
           $dbHandle->insert('tcoupling', $data);
		   //As per new Product requirement We will not use subscription on add sticky listing interface for abroad so we will copy enddate and start date from shoshkele at time of couple 
		   $sql = "select * from tlistingsubscription where listingsubsid = ? and status = 'live'";
           $query =  $dbHandle->query($sql,array($listingsubsid));
           if($query->num_rows() > 0)
           {
			$result= reset($query->result_array());
				if($result['startdate']=='0000-00-00 00:00:00' && $result['enddate']=='0000-00-00 00:00:00' && $result['subscriptionid']==0)
				{
					$sql = "select * from tbannerlinks where bannerlinkid = ? and status = 'live'";
					$query = $dbHandle->query($sql,array($bannerlinkid));
					if($query->num_rows() > 0)
					{
						$bannerDetails= reset($query->result_array());
						$sql = "update tlistingsubscription set startdate = ? ,enddate=?,subscriptionid=? where listingsubsid = ? and status = 'live'";
						$query =  $dbHandle->query($sql,array($bannerDetails['startdate'],$bannerDetails['enddate'],$bannerDetails['subscriptionid'],$listingsubsid)); 
					}
					
					
				}
           }
       }
       else{
           $sql = "update tcoupling set status = 'decoupled' where listingsubsid = ? and bannerlinkid = ?";
           $query =  $dbHandle->query($sql,array($listingsubsid,$bannerlinkid));
        }
       return 'Coupling done';
    }

    function getListingndBannersForCoupling($dbHandle,$clientId,$countryId,$cityid,$stateid,$categoryid,$subcategoryid,$cat_type,$courseLevel)
    {
	//get the coupled listings and banners for the selected client and category and location
    	$sql = "select
    	a.couplingid,c.bannerlinkid,b.listingsubsid,d.bannerid,d.bannername,d.bannerurl,b.listing_type_id as institute_id
    	from
    	tcoupling a
    	inner join tlistingsubscription b on a.listingsubsid = b.listingsubsid
    	inner join tbannerlinks c on c.bannerlinkid = a.bannerlinkid
    	inner join tbanners d on d.bannerid = c.bannerid
    	where
    	b.enddate > now()
    	and a.status = 'coupled'
    	and b.status = 'live'
    	and c.status = 'live'
    	and d.clientId = ?
    	and b.clientId = ?
    	and c.categoryid = ?
    	and b.categoryid = ?
    	and c.subcategoryid = ?
    	and b.subcategory = ?
    	and c.course_level = ?
    	and b.course_level = ?
    	and c.countryid = ?
    	and b.countryId = ?
    	and c.stateid = ?
    	and b.stateId = ?
    	and c.cityid = ?
    	and b.cityid = ?
    	and d.status = 'live'
    	and c.enddate > now()";
	
        $query = $dbHandle->query($sql,array($clientId,$clientId,$categoryid,$categoryid,$subcategoryid,$subcategoryid,$courseLevel,$courseLevel,$countryId,$countryId,$stateid,$stateid,$cityid,$cityid));
        $bannerlinkids = '-1';
        $listingsubsids = '-1';
        $arrforcoupledlistings = array();
        $i = 0;
        foreach($query->result() as $row)
        {
            $bannerlinkids .= ','.$row->bannerlinkid;
            $listingsubsids .= ','.$row->listingsubsid;
            $arrforcoupledlistings[$i] = $row;
            $i++;
        }
	
	    //exclude the coupled listings
        $testprep_clause = ($cat_type == 'testprep' ? "and pagename='testprep'" : "");
        $sql = "select a.listingsubsid,a.listing_type_id as institute_id
        from tlistingsubscription a where (a.enddate > now()".
		"or (a.enddate ='0000-00-00 00:00:00' and a.startdate='0000-00-00 00:00:00' and subscriptionid=0)".
		") 
        and a.status = 'live' and a.countryid = ?
        and a.cityid = ?
        and a.stateid = ?
        and a.categoryid = ? 
        and a.subcategory = ?
    	and a.course_level = ?
    	and a.listingsubsid NOT IN(".$listingsubsids.")
        and a.clientid = ? ".$testprep_clause;
        error_log("getListingndBannersForCoupling sql : ".$sql);
        $query = $dbHandle->query($sql,array($countryId,$cityid,$stateid,$categoryid,$subcategoryid,$courseLevel,$clientId));
        $arrforlistings = array();
        $i = 0;
        foreach($query->result() as $row)
        {
            $arrforlistings[$i] = $row;
            $i++;
        }

       //exclude the coupled banners
       $sql  = "select a.bannerlinkid,a.bannerid,b.bannerurl,b.bannername from tbannerlinks a inner join tbanners b on a.bannerid = b.bannerid
           where a.enddate > now()
           and a.status = 'live'
           and a.subcategoryid = ?
           and a.categoryid = ?
           and a.course_level = ?
           and a.countryid  = ?
           and a.cityid = ?
           and a.stateid = ?
           and b.clientId = ?
           and b.status = 'live'
           and a.bannerlinkid NOT IN(".$bannerlinkids.")";
        $query = $dbHandle->query($sql,array($subcategoryid,$categoryid,$courseLevel,$countryId,$cityid,$stateid,$clientId));
        $arrforbanners = array();
        $i = 0;
        foreach($query->result() as $row){
            $arrforbanners[$i] = $row;
            $i++;
        }
        $allarr = array();
        $allarr['coupledarray'] = $arrforcoupledlistings;
        $allarr['listingsarray'] = $arrforlistings;
        $allarr['bannerarray'] = $arrforbanners;
        return $allarr;
    }

    function getShoshkeleDetails($dbHandle,$clientId,$sort)
    {
       // error_log('sdfsd');
       $dbHandle = $this->getWriteHandle();
       $sql = "select userId from tuser where userId = ? and usergroup in ('cms','enterprise','privileged','sums');";
       // error_log($sql);
       $query = $dbHandle->query($sql,array($clientId));
       if($query->num_rows() == 0)
       {
           return 'Please enter a valid client id';
       }
       if($sort!='asc'){
            $sort = 'desc';
       }
       $sql = "select a.bannerid,a.bannername as shoshkeleName,a.bannerurl from tbanners a where a.status in ('live','".ENT_SA_PRE_LIVE_STATUS."') and a.clientid = ? order by a.bannername $sort";
       // error_log($sql);
       $query = $dbHandle->query($sql,array($clientId));
       $arrforbanners = array();
       $i = 0;
       foreach($query->result() as $row)
       {
           $j = 0;
           $arrforbanners[$i][$j] = $row;
           $sql1 = "select
                        a.bannerid,a.bannername as shoshkeleName,
                        a.bannerurl,b.bannerlinkid,b.categoryid,b.course_level,
                        if(c.name is NULL,concat(g.name,'(Sub-Category)'),c.name) as category,
                        d.name,
                        if(e.city_name is NULL,if(f.state_name is NULL,d.name,concat(f.state_name,'(State),',d.name)),concat(e.city_name,'(City),',d.name)) as location,
                        b.subscriptionid as subscriptionId
                    from
                        tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid
                        left join categoryBoardTable c on b.categoryid = c.boardId
                        inner join countryTable d on d.countryId = b.countryId
                        left join countryCityTable e on e.city_id = b.cityid
                        left join stateTable f on f.state_id = b.stateid
                        left join categoryBoardTable g on b.subcategoryid = g.boardId
                    where
                        a.clientid = $clientId and a.status = 'live'
                        and a.bannerid = ?
                        and b.status in ('live','".ENT_SA_PRE_LIVE_STATUS."')
                        and b.enddate > now()
                        and (b.product = 'category'
                        or b.product = 'country')
                        order by a.bannername $sort";
	     // error_log($sql1.'SQL');
           $query1 = $dbHandle->query($sql1,array($row->bannerid));
           foreach($query1->result() as $row1)
           {
               $j++;
               $arrforbanners[$i][$j] = $row1;
           }

           $sql2 = "select tb.bannerid, tb.bannername as shoshkeleName, tb.bannerurl, t.bannerlinkid, t.categoryid, t.course_level, b.blogTitle as category, c.city_name as location, t.subscriptionid as subscriptionId from tbannerlinks t join tbanners tb on t.bannerid = tb.bannerid left join blogTable b on t.categoryid = b.blogId left join countryCityTable c on t.cityid = c.city_id where tb.clientid = $clientId and t.product='testprep' and b.status ='live' and t.status='live' and tb.status='live'
 and t.enddate > now() and tb.bannerid = ? order by tb.bannername $sort";
            // error_log($sql2.'SQL');
           $query2 = $dbHandle->query($sql2,array($row->bannerid));
           foreach($query2->result() as $row2)
           {
               $j++;
               $arrforbanners[$i][$j] = $row2;
           }

           $sql3 = "select tb.bannerid, tb.bannername as shoshkeleName, tb.bannerurl, t.bannerlinkid, t.categoryid, t.course_level, b.blogTitle as category, 'Online Test National Product' as location, t.subscriptionid as subscriptionId from tbannerlinks t join tbanners tb on t.bannerid = tb.bannerid left join blogTable b on t.categoryid = b.blogId where tb.clientid = $clientId and t.product='onlinetest' and t.status='live' and b.status = 'live' and tb.status='live'
 and t.enddate > now() and tb.bannerid = ? order by tb.bannername $sort";
            // error_log($sql3.'SQL');
           $query3 = $dbHandle->query($sql3,array($row->bannerid));
           foreach($query3->result() as $row3)
           {
               $j++;
               $arrforbanners[$i][$j] = $row3;
           }



           $i++;
       }

        return $arrforbanners;
    }

    function selectnduseshoshkele($dbHandle,$arr)
    {
        $dbHandle = $this->getWriteHandle();
        if($arr['product'] == 'onlinetest') $arr['cityid'] = 0;
        $sql = "select a.bannerid from tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid where a.status = 'live' and b.status in ('live') and b.enddate > now() and b.categoryid = ? and b.countryid = ? and b.cityid = ? and a.bannerid = ? and b.stateid = ? and b.course_level = ? ";
        $query = $dbHandle->query($sql,array($arr['categoryid'],$arr['countryid'],$arr['cityid'],$arr['bannerid'],$arr['stateid'],$arr['course_level']));
        if($query->num_rows() > 0)
        {
            return -1;
        }


        $sql = "select ifnull(max(bannerlinkid),0) + 1 as bannerlinkid from tbannerlinks";
        $query = $dbHandle->query($sql);
        $row = $query->row();
        $bannerlinkid = $row->bannerlinkid;

        // error_log(print_r($arr,true));
        $arr['bannerlinkid'] = $bannerlinkid;
        list($year, $month,$date) = explode('-', $arr['enddate']);
        $arr['enddate'] = (date("Y-m-d H:i:s",mktime(23,59,59,$month,$date,$year)));
        $queryCmd = $dbHandle->insert_string('tbannerlinks',$arr);
        // error_log($queryCmd);
        $query = $dbHandle->query($queryCmd);
        $bannerid = $dbHandle->insert_id();
        return $bannerid;
    }

    function cmsaddstickylisting($dbHandle,$arr)
    {
        $dbHandle = $this->getWriteHandle();
	if($arr['listing_type'] == 'institute'){
        // error_log(print_r($arr,true).'ARRAY');
	    $sql = "select a.listingsubsid from tlistingsubscription a where a.status = 'live' and a.enddate > now() and a.categoryid = ? and a.subcategory = ? and a.countryid = ? and a.cityid = ?  and  a.stateid = ? and a.listing_type_id = ? and a.listing_type = ? and a.course_level = ? ";
	    $query = $dbHandle->query($sql,array($arr['categoryid'],$arr['subcategoryid'],$arr['countryid'],$arr['cityid'],$arr['stateid'],$arr['listingid'],$arr['listing_type'],$arr['course_level']));
	    // error_log($sql);
	    if($query->num_rows() > 0)
	    {
		return -1;
	    }
	    $data =array();
	    list($year, $month,$date) = explode('-', $arr['enddate']);
	    $arr['enddate'] = (date("Y-m-d H:i:s",mktime(23,59,59,$month,$date,$year)));
	    $data = array(
		    'clientid'=>$arr['clientid'],
		    'listing_type_id'=>$arr['listingid'],
		    'subscriptionid'=>$arr['subscriptionid'],
		    'categoryid'=>$arr['categoryid'],
		    'countryid'=>$arr['countryid'],
		    'subcategory'=>$arr['subcategoryid'],
		    'cityid'=>$arr['cityid'],
		    'stateid'=>$arr['stateid'],
		    'startdate'=>$arr['startdate'],
		    'enddate'=>$arr['enddate'],
		    'pagename'=>$arr['pagename'],
		    'status'=>$arr['status'],
		    'listing_type' => $arr['listing_type'],
		    'course_level' =>'All'
		    );
	    $queryCmd = $dbHandle->insert_string('tlistingsubscription',$data);
	    $query = $dbHandle->query($queryCmd);
	    $listingsubsid = $dbHandle->insert_id();
	    return $listingsubsid;
	}
	else if($arr['listing_type'] == 'university'){
        // error_log(print_r($arr,true).'ARRAY');
	    $sql = "select a.listingsubsid from tlistingsubscription a where a.status = 'live' and a.enddate > now() and a.categoryid = ? and a.countryid = ? and a.listing_type_id = ? and a.listing_type = ? and a.course_level = ? ";
	    $query = $dbHandle->query($sql,array($arr['categoryid'],$arr['countryid'],$arr['listingid'],$arr['listing_type'],$arr['course_level']));
	    // error_log($sql);
	    if($query->num_rows() > 0)
	    {
		return -1;
	    }
	    $data =array();
		if($arr['enddate'] !='0000-00-00 00:00:00')
		{
			list($year, $month,$date) = explode('-', $arr['enddate']);	
			$arr['enddate'] = (date("Y-m-d H:i:s",mktime(23,59,59,$month,$date,$year)));
		}
	    if($arr['listing_type'] == "university") {
		$status = ENT_SA_PRE_LIVE_STATUS;
	    } else {
		$status = $arr['status'];
	    }
	    $data = array(
		    'clientid'=>$arr['clientid'],
		    'listing_type_id'=>$arr['listingid'],
		    'subscriptionid'=>$arr['subscriptionid'],
		    'categoryid'=>$arr['categoryid'],
		    'countryid'=>$arr['countryid'],
		    'subcategory'=>$arr['subcategoryid'],
		    'cityid'=>$arr['cityid'],
		    'stateid'=>$arr['stateid'],
		    'startdate'=>$arr['startdate'],
		    'enddate'=>$arr['enddate'],
		    'pagename'=>$arr['pagename'],
		    'status'=>$status,
		    'listing_type'=> $arr['listing_type'],
			'comment' =>(isset($arr['comment'])?$arr['comment']:''),
		    'course_level' => $arr['course_level']
		    );
	    $queryCmd = $dbHandle->insert_string('tlistingsubscription',$data);
	    //error_log(print_r($queryCmd,true));
	    $query = $dbHandle->query($queryCmd);
	    $listingsubsid = $dbHandle->insert_id();
	    return $listingsubsid;
	}
	else{
	    return -2;
	}
    }

    function insertbannerdetails($dbHandle,$clientid,$bannerurl,$bannername)
    {

        $sql = "select bannerid from tbanners where trim(bannername) = ? and status = 'live'";
        // error_log($sql);
        $query = $dbHandle->query($sql,array($bannername));
        $row = $query->row();
        if($query->num_rows() > 0)
            return 'Banner with same banner name already exists. Please choose a different banner name';


        $sql = "select ifnull(max(bannerid),0) + 1 as bannerid from tbanners";
        $query = $dbHandle->query($sql);
        $row = $query->row();
        $bannerid = $row->bannerid;
        $data =array();
        $data = array(
                'clientid'=>$clientid,
                'bannerid'=>$bannerid,
                'bannerurl'=>$bannerurl,
                'status'=>'live',
                'bannername'=>$bannername
                );
        $queryCmd = $dbHandle->insert_string('tbanners',$data);
        // error_log($queryCmd);
        $query = $dbHandle->query($queryCmd);
        $bannerid = $dbHandle->insert_id();
        return $bannerid;
    }

    function updatebannerdetails($dbHandle,$clientid,$bannerid,$bannerurl,$bannername,$keyword)
    {
        if($keyword == "all")
        {
             $sql = "select bannerid from tbanners where trim(bannername) = ? and status = 'live' and bannerid <> $bannerid";
        }
        else
        {
          $sql = "select bannerid from tbanners where trim(bannername) = ? and status = 'live'";
        }
        // error_log($sql);
        $query = $dbHandle->query($sql,array($bannername));
        $row = $query->row();
        if($query->num_rows() > 0)
        return 'Banner with same banner name already exists. Please choose a different banner name';
        // error_log($keyword.'KEYWORD');
        if($keyword == "all")
        {
            //if the master is edited den delete the existing shoshkele entry and create a new one with same banner id
            $data =array();
            $data = array(
                    'status'=>'deleted'
                    );
            $dbHandle->where('bannerid', $bannerid);
            $dbHandle->update('tbanners',$data);

            $data = array(
                    'bannerid'=>$bannerid,
                    'clientid'=>$clientid,
                    'bannername'=>$bannername,
                    'status'=>'live',
                    'bannerurl'=>$bannerurl
                    );
            $queryCmd = $dbHandle->insert_string('tbanners',$data);
            $query = $dbHandle->query($queryCmd);
            $queryCmd = "update tbannerlinks set lastModificationDate = now() where bannerid = ? and status = 'live'";
            $query = $dbHandle->query($queryCmd,array($bannerid));
            // error_log($queryCmd);
            // error_log($dbHandle);
        }
        else
        {
            //if master is not edited den create a new entry for shoshkele and the older link to this should be deleted and new link(with same bannerlinkid) pointing to new banner to be made
        $sql = "select max(bannerid) + 1 as bannerid from tbanners";
        $query = $dbHandle->query($sql);
        $row = $query->row();
        $newbannerid = $row->bannerid;
            $data = array(
                    'bannerid'=>$newbannerid,
                    'clientid'=>$clientid,
                    'bannername'=>$bannername,
                    'status'=>'live',
                    'bannerurl'=>$bannerurl
                    );
            $queryCmd = $dbHandle->insert_string('tbanners',$data);
            // error_log($queryCmd);
            $query = $dbHandle->query($queryCmd);

            $sql = "select b.bannerlinkid,b.startdate,b.enddate,b.categoryid,b.subcategoryid,b.countryid,b.cityid,b.subscriptionid from tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid where a.status = 'live' and b.status = 'live' and b.enddate > now() and b.bannerlinkid = ?";
            // error_log($sql.'SQL');
            $query = $dbHandle->query($sql,array($bannerid));
            foreach($query->result() as $row)
            {
                $bannerlinkid = $row->bannerlinkid;
                $categoryid = $row->categoryid;
                $subcategoryid = $row->subcategoryid;
                $countryId = $row->countryid;
                $cityid = $row->cityid;
                $subscriptionId = $row->subscriptionid;
                $startdate = $row->startdate;
                $enddate = $row->enddate;
                // error_log($bannerlinkid.'    '.$startdate.'    '.$enddate.'   '.$categoryid.'    '.$subcategoryid.'       '.$countryId.'   '.$cityid.'   '.$subscriptionId);
            }

            $data = array(
                    'status'=>'deleted'
                    );
            $dbHandle->where('bannerlinkid', $bannerlinkid);
            $dbHandle->update('tbannerlinks',$data);

            $data = array(
                    'bannerlinkid'=>$bannerid,
                    'bannerid'=>$newbannerid,
                    'startdate'=>$startdate,
                    'enddate'=>$enddate,
                    'categoryid'=>$categoryid,
                    'subcategoryid'=>$subcategoryid,
                    'countryid'=>$countryId,
                    'cityid'=>$cityid,
                    'subscriptionid'=>$subscriptionId,
                    'status'=>'live'
                    );
            $queryCmd = $dbHandle->insert_string('tbannerlinks',$data);

            $query = $dbHandle->query($queryCmd);
            // error_log($queryCmd);
            // error_log($dbHandle);
        }
        return $bannerid;
    }

    function getCategorySelector($dbHandle,$categoryId,$countryId,$cityId,$pageKey, $additionalParams)
    {
        $subcategoryId = $this->getChildIds($dbHandle,$categoryId);

        if($countryId != 2)
        $categoryId = 0;

        $additionalParams["categoryLevelClause"] = " AND categoryPageData.category_id in (".$subcategoryId.")";

        //Take out the banners and institutes and check if the courses exist for the institutes
        //Get all the coupled ids

        if(empty($categoryId) || empty($countryId) || empty($cityId)) {
            return array();
        }      

        if(!is_array($categoryId)){
            $$categoryId = explode(',', $categoryId);
        }
        if(!is_array($countryId)){
            $$countryId = explode(',', $countryId);
        }
        if(!is_array($cityId)){
            $$cityId = explode(',', $cityId);
        }

        $sql = "select a.bannername as bannername,a.bannerurl as bannerurl,c.listingsubsid,d.listing_type_id as institute_id,a.bannerid,b.bannerlinkid from tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid inner join tcoupling c on c.bannerlinkid = b.bannerlinkid inner join tlistingsubscription d on d.listingsubsid = c.listingsubsid where b.categoryid in (?) and b.countryid in (?) and b.cityid in (?) and b.status = 'live' and c.status = 'coupled' and a.status = 'live' and now() between b.startdate and b.enddate and now() between d.startdate and d.enddate";

        $query = $dbHandle->query($sql,array($categoryId,$countryId,$cityId));
        $i = 0;
        $j = 0;
        $insarr = array();
        $bannerids = -1;
        $instituteids = -1;
        $arr = array();
        $mainInstituteIds = array();
        $mainInstitutes = array();
        $maincourse = array();
        $maincoursemapping = array();
        $coursearray = array();
        $catinsarr = array();
        $totalRecords = 0;
  //      $totalRecords = $query->num_rows();
 
        // The following code has been added by Amit Kuksal on 20th May 2011 in reference to ticket ID: 318
        $institutesIdsTempVar = "";
        $notTheseBannerids = -1;
        $notTheseInstituteids = -1;
        $k = 0;
        foreach($query->result() as $row)
        {
            $coupledCategorySponsorResults[$row->institute_id]['bannername'] = $row->bannername;
            $coupledCategorySponsorResults[$row->institute_id]['bannerurl'] = $row->bannerurl;
            $coupledCategorySponsorResults[$row->institute_id]['bannerid'] = $row->bannerid;
            $coupledCategorySponsorResults[$row->institute_id]['bannerlinkid'] = $row->bannerlinkid;
            $notTheseBannerids .= ','. $row->bannerid;
            $notTheseInstituteids .= ','. $row->institute_id;

            if($k++ == 0)
                $institutesIdsTempVar = $row->institute_id;
            else
                $institutesIdsTempVar .= ','. $row->institute_id;
        }
        //error_log("\n ids in step 1 coupledCategorySponsorResults : ".print_r($coupledCategorySponsorResults,true),3,'/home/infoedge/Desktop/log.txt');
        // error_log("\n Temp ins ids : ".print_r($institutesIdsTempVar,true),3,'/home/infoedge/Desktop/log.txt');
        
        // Now checking and getting back the institutes ids which have the matching courses..
        if($institutesIdsTempVar != "") {
                $institutesIdsTempArray = $this->getMatchedInstituteIds($dbHandle, $institutesIdsTempVar, $additionalParams);
                // error_log("\n Matched institutesIdsTempArray : ".print_r($institutesIdsTempArray,true),3,'/home/infoedge/Desktop/log.txt');
                if($institutesIdsTempArray != "") {
                    // error_log("\n Insdie Matched institutesIdsTempArray : ".print_r($institutesIdsTempArray,true),3,'/home/infoedge/Desktop/log.txt');
                    foreach($institutesIdsTempArray as $key => $institute_id)
                    {
                        // error_log("\n\nAmit ids : ".print_r($institute_id,true),3,'/home/infoedge/Desktop/log.txt');
                        $arr[$i]['bannername'] = $coupledCategorySponsorResults[$institute_id]['bannername'];
                        $arr[$i]['bannerurl'] = $coupledCategorySponsorResults[$institute_id]['bannerurl'];
                        $arr[$i]['bannerid'] = $coupledCategorySponsorResults[$institute_id]['bannerid'];
                        $arr[$i]['bannerlinkid'] = $coupledCategorySponsorResults[$institute_id]['bannerlinkid'];
                        $arr[$i]['institute_id'] = $institute_id;
                        $insarr[$j]['institute_id'] = $institute_id;
                        // $bannerids .= ','. $coupledCategorySponsorResults[$institute_id]['bannerid'];
                        $instituteids .= ','.$institute_id;
                        $i++;
                        $j++;
                    }
                } // End of if($institutesIdsTempArray != "").

        } // End of if($institutesIdsTempVar != "").

        // End code by Amit in reference to ticket ID: 318 */


        //Take rest of the banners apart from the coupled ones
        // $sql = "select a.bannername as bannername,a.bannerurl as bannerurl,a.bannerid as bannerid from tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid where a.status = 'live' and b.status = 'live' and a.bannerid not in ($bannerids) and b.categoryid in ($categoryId) and countryid in ($countryId) and cityid in ($cityId) and now() between b.startdate and b.enddate";
        $sql = "select a.bannername as bannername,a.bannerurl as bannerurl,a.bannerid as bannerid from tbanners a inner join tbannerlinks b on a.bannerid = b.bannerid where a.status = 'live' and b.status = 'live' and a.bannerid not in ($notTheseBannerids) and b.categoryid in (?) and countryid in (?) and cityid in (?) and now() between b.startdate and b.enddate";

        // error_log("\n\nAmit ".print_r($sql,true),3,'/home/infoedge/Desktop/log.txt');

        // error_log($sql.'SQL');
        $query = $dbHandle->query($sql,array($categoryId,$countryId,$cityId));
        foreach($query->result() as $row)
        {
            $arr[$i]['bannername'] = $row->bannername;
            $arr[$i]['bannerurl'] = $row->bannerurl;
            $arr[$i]['bannerid'] = $row->bannerid;
            $arr[$i]['institute_id'] = -1;
            $bannerids .= ','. $row->bannerid;
            $i++;
            $bannerurl = $arr[0]['bannerurl'];
        }

        //Take the institute ids apart from the ones already present
        // $sql = "select a.institute_id as institute_id from tlistingsubscription a where a.status = 'live' and a.institute_id not in  ($instituteids) and a.categoryid in ($categoryId) and a.countryid in ($countryId) and a.cityid in ($cityId) and now() between a.startdate and a.enddate" ;
        $sql = "select a.listing_type_id as institute_id from tlistingsubscription a where a.status = 'live' and a.listing_type_id not in  ($notTheseInstituteids) and a.categoryid in (?) and a.countryid in (?) and a.cityid in (?) and now() between a.startdate and a.enddate" ;
        // error_log("\n\nAmit ".print_r($sql,true),3,'/home/infoedge/Desktop/log.txt');

        $query = $dbHandle->query($sql,array($categoryId,$countryId,$cityId));

        // The following code has been added by Amit Kuksal on 20th May 2011 in reference to ticket ID: 318
        $institutesIdsTempVar = "";
        $k = 0;
        foreach($query->result() as $row)
        {
            if($k++ == 0)
                $institutesIdsTempVar = $row->institute_id;
            else
                $institutesIdsTempVar .= ','. $row->institute_id;
        }
        // error_log("\n Temp ins ids step 2 : ".print_r($institutesIdsTempVar,true),3,'/home/infoedge/Desktop/log.txt');
        // Now checking and getting back the institutes ids which have the matching courses..
        if($institutesIdsTempVar != "") {
                $institutesIdsTempArray = $this->getMatchedInstituteIds($dbHandle, $institutesIdsTempVar, $additionalParams);

                foreach($institutesIdsTempArray as $key => $institute_id)
                {
                    // error_log("\n\nAmit ids : ".print_r($institute_id,true),3,'/home/infoedge/Desktop/log.txt');
                    $insarr[$j]['institute_id'] = $institute_id;
                    $instituteids .= ','. $institute_id;
                    $j++;
                }
                    // error_log("\n courseLevelClause : ".print_r($additionalWhereCluase,true),3,'/home/infoedge/Desktop/log.txt');
        } // End of if($institutesIdsTempVar != "").

        $degree_course_info = $additionalParams["degree_course_info"]; // To be used in Dual Degree course case..
        // error_log("\n\n degree_course_info : ".print_r($degree_course_info,true),3,'/home/infoedge/Desktop/log.txt');
        $additionalWhereCluase = str_replace("categoryPageData", "course_details", $additionalParams["courseLevelClause"]) . str_replace("categoryPageData", "course_details", $additionalParams["courseTypeClause"]);
        
        // End code by Amit in reference to ticket ID: 318 */
        
        
        $counter = 0;
        $i = 0;
        // error_log("\n\n ids in last : ".print_r($instituteids,true),3,'/home/infoedge/Desktop/log.txt');
        $institutesarr = explode(',',$instituteids);
        // error_log("\n ids arr : ".print_r($institutesarr,true),3,'/home/infoedge/Desktop/log.txt');
        // error_log(count($institutesarr).'INSTITUTEIDS');
        if(count($institutesarr) > 0)
        {
            // error_log(print_r($query->result(),true).'CATEGORYSELECTOR');
            $x = 0;
            for ($k = 0; $k < count($institutesarr);$k++)
            {
                $instituteId = $institutesarr[$k];
                    $selectclause = "listing_category_table e,";
                    $whereclause = "e.listing_type_id = d.institute_id and e.listing_type = 'institute' and e.category_id in ($subcategoryId) and e.status = 'live' and";

		//BPSCHANGE
                $sql = "select c.institute_id as institute_id,c.usp,c.aima_rating,c.abbreviation,c.logo_link as logo_link,b.viewCount as views,d.city_id as city_id,d.country_id as country_id,b.pack_type,c.institute_Name,f.name as countryName from listings_main b,institute c,institute_location_table d,$selectclause countryTable f where b.listing_type_id = c.institute_id and f.countryId = d.country_id and b.listing_type = 'institute' and b.status = 'live' and c.status = 'live' and c.institute_id = d.institute_id and d.institute_id = ? and d.status = 'live' and $whereclause d.country_id in (?)  limit 1";

                // error_log($sql.'SQL123');
                $query = $dbHandle->query($sql,array($instituteId,$countryId));
                $row = $query->row();
                $numrows = $query->num_rows();
                if($numrows > 0)// BPSCHANGE
                {
                    $totalRecords += 1;
                    // error_log($instituteId.'INSTITUTEID');
                    $sql = "select * from  PageCollegeDb where PageCollegeDb.KeyId in (?) and PageCollegeDb.listing_type_id = ? and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate and PageCollegeDb.listing_type='institute' and PageCollegeDb.status='live'";
                    // error_log($sql.'SQL');
                    // error_log($sql.'NEHAMAIN');
                    $query = $dbHandle->query($sql,array($pageKey,$instituteId));
                    if($query->num_rows() > 0)
                        $catinsarr[$x]['isSponsored']='true';
                    else
                        $catinsarr[$x]['isSponsored']='false';

                    if(strlen($instituteIds)>0)
                    {
                        $instituteIds .= ' , ';
                    }
                    // error_log(print_r($row,true).'ROW');
                    $instituteIds .= "$row->institute_id";
                    $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
		    //Added by Ankur on 25th Oct to add the Institute location to the URL
		    $locationArrayTemp = array();
		    $cityName = array($cacheLib->get("city_".$row->city_id),'string');
		    $countryName = array($cacheLib->get("country_".$row->country_id),'string');
		    $locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
		    $optionalArgs = array();
		    $optionalArgs['location'] = $locationArrayTemp;
                    $optionalArgs['institute'] = $row->institute_Name;
		    //End Modifications by Ankur
                    $url  = getSeoUrl($row->institute_id,'institute',$row->institute_Name,$optionalArgs);
                    $cityName  = $cacheLib->get("city_".$row->city_id);
                    $arrayids[$x] = $row->institute_id;
                    $catinsarr[$x]['institute_Name'] = $row->institute_Name;
                    $catinsarr[$x]['usp'] = $row->usp;
                    $catinsarr[$x]['aima_rating'] = $row->aima_rating;
                    $catinsarr[$x]['abbreviation'] = $row->abbreviation;
                    $catinsarr[$x]['countryName'] = $row->countryName;
                    $catinsarr[$x]['id'] = $row->institute_id;
                    $catinsarr[$x]['logo_link'] = $row->logo_link;
                    if($logo_link == '')
                    {
                        $logo_link = $row->logo_link;
                    }

                    $catinsarr[$x]['logo_link'] = $this->getInstituteLogo($logo_link);
                    $media = $this->getMediaData($dbHandle,$row->institute_id);
                    $catinsarr[$x]['mediadata'] = $media;
                    $catinsarr[$x]['alumin_rating'] = $media['rating'];
                    $catinsarr[$x]['url'] = $url;
                    $catinsarr[$x]['views'] = $row->views;
                    $catinsarr[$x]['isSendQuery']=$isSendQuery;
                    $catinsarr[$x]['flagname'] = 'category';
                    $catinsarr[$x]['city']=$cityName;

                    //Header Image retrival
                    $qryHeader = "SELECT listing_id,thumb_url FROM header_image WHERE listing_id = ? AND listing_type = 'institute' AND status = 'live' limit 1";
                    $queryH =  $dbHandle->query($qryHeader,array($row->institute_id));
                    foreach ($queryH->result() as $ro)
                    {
                        $catinsarr[$x]['headerImageUrl']=$ro->thumb_url;
                    }
                    //Header Image Stuf Ends here

                    // $groupbyclause = 'group by course_details.course_id';

                    //     $selectclause1 = "listing_category_table ";
                    //     $whereclause1 = " listing_category_table.listing_type_id = course_id and listing_category_table.listing_type = 'course' and category_id in ($subcategoryId) and listing_category_table.status = 'live' ";

                    //BPSCHANGE
                    $query = 'select straight_join SQL_CALC_FOUND_ROWS (count(*)) totalRows, course_details.institute_id,course_details.courseTitle,course_details.course_type,course_details.duration_value,course_details.duration_unit,course_details.fees_value,course_details.fees_unit,course_details.course_order,course_details.course_id,course_details.course_level,if(course_details.course_level = "Dual Degree","Degree",course_level) as courselevel,course_details.course_level_1,course_details.course_level_2 from course_details,listings_main,listing_category_table where course_details.status = "live" and course_details.institute_id = ? and listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and listings_main.status = "live" '.$additionalWhereCluase.' and  listing_category_table.listing_type_id = course_id and listing_category_table.listing_type = "course" and category_id in (?) and listing_category_table.status = "live" group by course_details.course_id order by course_details.course_order';

                    // error_log("\n\nAmit query : ".print_r($query,true),3,'/home/infoedge/Desktop/log.txt');
                    if(!is_array($subcategoryId)){
                        $subcategoryId = explode(',', $subcategoryId);
                    }
                    $queryResult = $dbHandle->query($query,array($instituteId,$subcategoryId));
                    $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                    $queryTotal = $dbHandle->query($queryCmdTotal);
                    $totalRows = 0;
                    foreach ($queryTotal->result() as $rowTotal)
                    {
                        $totalRows = $rowTotal->totalRows;
                    }
                    $catinsarr[$x]['coursecount'] = $totalRows;
                    $course_details = $queryResult->result_array();
                    $j=0;
                    foreach($course_details as $row1)
                    {
                        if($row1['course_level_1'] == 'NULL' || trim($row1['course_level_1']) == trim($row1['courselevel']))
                            $courseLevel_1 = trim($row1['courselevel']);
                        else
                            $courseLevel_1 = trim($row1['course_level_1']).' '.trim($row1['courselevel']);
                        if($row1->course_level_1 == "Post Graduate Diploma")
                            $courseLevel_1 = trim($row1['course_level_1']);
                        $courseUrl = getSeoUrl($row1['course_id'],'course',$row1['courseTitle'],$optionalArgs);
                        $maincourse[$i]['course_id'] = $row1['course_id'];
                        $maincourse[$i]['course_title'] = $row1['courseTitle'];
                        // error_log("\n\nAmit var course_level_1 : ".print_r($degree_course_info["course_level_1"],true). ", courseLevel_1 : ".print_r($courseLevel_1,true),3,'/home/infoedge/Desktop/log.txt');
                        // Added by Amit Kuksal in reference to ticket #318
                        if(is_array($degree_course_info) && $degree_course_info["degree"] == "Degree") {
                            if(strcmp(trim($degree_course_info["course_level_1"]), trim($row1['course_level_1'])) == 0) {
                                $maincourse[$i]['course_level'] = $courseLevel_1;                                
                            } else {
                                $maincourse[$i]['course_level'] = $row1['course_level_2'];                                
                            }

                            $maincourse[$i]['course_level_1'] = $row1['course_level'];

                        } else {
                            $maincourse[$i]['course_level'] = $courseLevel_1;
                            $maincourse[$i]['course_level_1'] = $row1['course_level'];

                        }   // End of if(is_array($degree_course_info) && $degree_course_info["degree"] == "Degree").

                        // End code by Amit.*/

                        // $maincourse[$i]['course_level'] = $courseLevel_1;
                        // $maincourse[$i]['course_level_1'] = $row1['course_level'];
                        $maincourse[$i]['course_level_2'] = $row1['course_level_2'];
                        $maincourse[$i]['course_type'] = $row1['course_type'];
                        $maincourse[$i]['duration'] = $row1['duration_value'].' '.$row1['duration_unit'];
                        $maincourse[$i]['fees_value'] = $row1['fees_value'];
                        $maincourse[$i]['course_order'] = $row1['course_order'];
                        $maincourse[$i]['fees_unit'] = $row1['fees_unit'];
                        $maincourse[$i]['courseurl'] = $courseUrl;
                        $maincourse[$i]['institute_id'] = $row1['institute_id'];
                        $maincoursemapping[$row->institute_id][$i] = $row1['course_id'];
                        $mainInstituteIds[$counter]["'".$row->institute_id."'"][$j] = $row1['course_id'];
                        $i++;
                        $j++;
                    }

                    $coursearray['coursemapping'] = $maincoursemapping;
                    $coursearray['totalRows'] = $totalRows;
                    $counter++;
                    $x++;
                }
            }
        }

        $endtime = microtime(true);

        //BPSCHANGE
        $bringCourseDetails= array();
        $outerCounter=0;
        $listInsId='';
        foreach($mainInstituteIds as $key => $ins)
        {

            foreach($ins as $insId => $courseList)
            {
                $courseId='';
                $counter=0;
                foreach($courseList as $k => $cId)
                {
                    if($counter == 0)
                        $courseId.= $cId;
                    else
                        $courseId.= ', '.$cId;

                    $counter++;
                }
                $bringCourseDetails[$outerCounter]= array(0=> intval(trim(str_replace('\'','',$insId))),1=>$courseId);
            }
            $outerCounter++;
        }

        $reponseDataArray = $this->getExamDataSalientFeaturesForRichSnippets($dbHandle,$bringCourseDetails);


        $indexCid= array();
        foreach($maincourse as $k => $v)
            $indexCid[$k]= $v['course_id'];


        foreach($reponseDataArray as $key => $indiv)
        {
            foreach($indiv as $k => $value)
            {
                if($k == 'courses')
                {
                    foreach($value as $in => $courseDetails)
                    {
                        $indX= array_keys($indexCid,$courseDetails['course_id']);
                        if($maincourse[$indX[0]]['course_id'] == $courseDetails['course_id'])
                        {
                            $maincourse[$indX[0]]['approved_granted_by']=$courseDetails['approved_granted_by'];
                            $maincourse[$indX[0]]['affiliated_to']=$courseDetails['affiliated_to'];
                            $maincourse[$indX[0]]['eligibility_exams']=$courseDetails['eligibility_exams'];
                            $maincourse[$indX[0]]['salient_features_ids']=$courseDetails['salientFeaturesIDs'];

                        }
                    }

                }
            }

        }
        // BPSCHANGE


        $response['mainInstituteIds'] = $mainInstituteIds;
        $response['mainInstitutes'] = $catinsarr;
        $response['bannerarr'] = $arr;
        $response['mainCourses'] = $maincourse;
        $response['notInstitutes'] = $instituteids;
        $response['totalRecords'] = $totalRecords;
        $response['logo_link'] = $bannerurl;
        $response['ids'] = $arrayids;
        // error_log(print_r($response,true).'RESPONSE1234');
        return $response;
    }

function getMatchedInstituteIds ($dbHandle, $instituteIDs, $additionalParams) {

        if(!is_array($instituteIDs)){
            $instituteIDs = explode(',', $instituteIDs);
        }
        $query = "select distinct institute_id from categoryPageData where institute_id in (?) ".$additionalParams["courseLevelClause"].$additionalParams["courseTypeClause"].$additionalParams["categoryLevelClause"]." AND status = 'live'";

        $queryRS = $dbHandle->query($query,array($instituteIDs));        
        $k = 0;
        $instituteIDs = "";
        foreach($queryRS->result() as $row)
        {      if($k++ == 0)
                    $instituteIDs = $row->institute_id;
               else
                    $instituteIDs .= ','. $row->institute_id;
        }
        if($instituteIDs == "") {
            return "";
        } else {
            $institutesIdsTempArray = split(",", $instituteIDs);
            return ($institutesIdsTempArray);
        }
}

/*  This function is defined by Amit Kuksal on 1st June 2011
 *  Purspose: To get the course info (used in case we are not getting the info from Cache while fetching the records)
 *  It takes Course ids and pagename as arguments.
 */
    function getCoursesInformation($dbHandle,$parameters) {
        $appId = $parameters['0'];
        $courseIds = $parameters['1'];
        $pagename = $parameters['2'];        
        // $instLocation = $parameters['2'];

        if($courseIds == "") return;

        $cids = $courseIds;
        if(!is_array($cids)){
            $cids = explode(',', $cids);
        }
        error_log('APC READ MISSED Course Ids on '. date('l jS \of F Y h:i:s A').' for "'.$pagename.'" are : '.$cids);

        $sql = "select distinct course_id, institute_id, city_id, country_id from categoryPageData where course_id in (?) AND status = 'live'";

        // error_log("\n\nAmit sql : ".print_r($sql,true),3,'/home/infoedge/Desktop/log.txt');
        $query = $dbHandle->query($sql,array($cids));
        $instituteIdArray = array();
        foreach($query->result() as $row)
        {
            
            if(in_array($row->institute_id, $instituteIdArray)) {

                // Need to concatenate the course ids here..
                $arraykey = array_search($row->institute_id, $instituteIdArray);
                $instituteCourseArray[$arraykey]["1"] .= ", ".$row->course_id;

            } else {

                $instituteIdArray[] = $row->institute_id;
                $instLocation[$row->institute_id] = array("city"=> $row->city_id, "country" => $row->country_id);
                $instituteCourseArray[] = array("0" => $row->institute_id, "1" => $row->course_id);
            }
            
        }   // End of foreach($query->result() as $row).

        // error_log("\n\nAmit LOCation array : ".print_r($instLocation,true),3,'/home/infoedge/Desktop/log.txt');

        // error_log("\n\n id array to return : ".print_r($instLocation,true),3,'/home/infoedge/Desktop/log.txt');
        $seocoursename = "course";
        
        $coursearray = $this->getCourseDetailsForInstitutes($dbHandle, $courseIds, $seocoursename, $instLocation);
        
        // error_log("\n\n id array to return : ".print_r($coursearray,true),3,'/home/infoedge/Desktop/log1.txt');

        if($pagename == "categorypages" || $pagename == "categorymostviewed" ) { // Will run for category pages only..

                // Need to call the function here to get the Salient Features and Exam data by passing $instituteCourseArray

               $reponseDataArray = $this->getExamDataSalientFeaturesForRichSnippets($dbHandle, $instituteCourseArray);

               // Lets collect the information now..
               $coursearrayCount = count($coursearray);
               $reponseDataArrayCount = count($reponseDataArray);

               for($courseArraycountVariable = 0; $courseArraycountVariable < $coursearrayCount; $courseArraycountVariable++) {

                   for($responseDatacountVariable = 0; $responseDatacountVariable < $reponseDataArrayCount; $responseDatacountVariable++) {

                        $courseCount = count($reponseDataArray[$responseDatacountVariable]["courses"]);

                        for($cnt = 0; $cnt < $courseCount; $cnt++) {
                             if($coursearray[$courseArraycountVariable]["course_id"] == $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["course_id"]) {
                                $coursearray[$courseArraycountVariable]["approved_granted_by"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["approved_granted_by"];
                                $coursearray[$courseArraycountVariable]["affiliated_to"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["affiliated_to"];
                                $coursearray[$courseArraycountVariable]["eligibility_exams"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["eligibility_exams"];
                                $coursearray[$courseArraycountVariable]["salient_features_ids"] = $reponseDataArray[$responseDatacountVariable]["courses"][$cnt]["salientFeaturesIDs"];

                            }

                        }   // End of for($cnt = 0; $cnt < $courseCount; $cnt++).

                   }    // End of for($countVariable = 0; $countVariable < $j; $countVariable++).

               }    // End of for($courseArraycountVariable = 0; $courseArraycountVariable < $coursearrayCount; $courseArraycountVariable++).*/

              //   error_log("\n\n array = ".print_r(base64_encode(json_encode($coursearray)),true),3,'/home/infoedge/Desktop/log.txt');

         } // End of if($pagename == "categorypages" || $pagename == "categorymostviewed" ).

         return base64_encode(json_encode($coursearray));

    }   // End of function getCoursesInformation($dbHandle,$parameters).


    function getListingsForNaukriShiksha($dbHandle,$parameters)
    {
        $starttime = microtime(true);
        // Get all the main Institutes info in first call . If institute count is remaining then take the paid/free case
        $appId = $parameters['0'];
        $categoryId = $parameters['1'];
        $subcategoryId = $parameters['2'] == 0 ? $this->getChildIds($dbHandle,$parameters['1']) : $parameters['2']; // Amit subcategory impact..
        $countryId = $parameters['3'];
        $cityId = $parameters['4'];
        $course_level = $parameters['5'];
        $course_level_1 = $parameters['6'];
        $course_type = $parameters['7'];
        $start = $parameters['8'];
        $count = $parameters['9'];
        $pagename = $parameters['10'];
        $firstcall = $parameters['11'];
        $optionalCategoryId = $parameters['12'];
        // $tmp = implode($parameters[5], ",");
        // error_log("\n\nAmit IN LISTING MODEL 3773 \n".print_r($parameters,true),3,'/home/infoedge/Desktop/log.txt');
        // error_log("Amit ".print_r($course_level,true)." \n\n levele 1 : ".print_r($course_level_1,true)."\n\n type : ".print_r($course_type,true) ,3,'/home/infoedge/Desktop/log.txt');
        $countArray = array();
        $mainInstitutes = array();
        $mainInstituteIds = array();
        $response = array();

        $orderbyclause = 'order by listings_main.viewCount DESC';
        $cityClause = '';
        $cityCondition = '';
        if($cityId != '' && $cityId != 'All' && $cityId != 0)
        {
            // $cityClause = "inner join institute_location_table c on a.institute_id = c.institute_id inner join virtualCityMapping d on d.city_id = c.city_id ";
            // $cityCondition = " and c.status = 'live' and d.virtualCityId = ".$cityId;
            $cityClause = " inner join virtualCityMapping d on d.city_id = a.city_id  ";
            $cityCondition = " d.virtualCityId = ".$cityId. " and ";
        }
        else
        {
            $cityId = 0;
        }
        $typename = 'institutegrouping';
        //Course level clusters defined
        $clustering = 1;

        $courseLevelClauseForCoursegrouping = "";
        switch($pagename)
        {
            case 'naukrishiksha':

                $courseLevelClause = " and ((course_level in('Degree', 'Dual Degree') and ( course_level_1 in('Under Graduate','Post Graduate') or course_level_2 in('Under Graduate','Post Graduate') ) ) or (course_level in('Diploma') and course_level_1 in('Diploma','Post Graduate Diploma')) or (course_level in('Dual Degree') and course_level_1 in('Under Graduate','Post Graduate') and course_level_2 in('Under Graduate','Post Graduate')) or (course_level in('Certification') and (course_level_1 = '')) or (course_level in('Vocational') and (course_level_1 = '')))";

                // $courselevelorder =  " order by FIELD(course_level_1,'Under Graduate','Diploma','Post Graduate','Post Graduate Diploma',''),FIELD(2,'Certification','Vocational'),FIELD(course_type,'Full time','Part time','Correspondence','E-learning')";

               // Added by Amit on 18th March 2011 for sorting the records (Performnace optimization)..
               $courselevelorder = " order by course_level_1, course_level, course_type";
               $pageRecordsSortingOrder = array(
                                                "course_level_order" =>  array("All", "UG Degree", "Diploma", "PG Degree", "PG Diploma", "Certification", "Vocational"),
                                                "course_type_order" =>  array("Full Time", "Part Time", "Correspondence", "E-learning")
                                            );

                $seocoursename = 'naukrishikshacourse';
                $seoinstitname = 'naukrishikshainstitute';
                if($course_level == 'All' || $course_type == 'All')
                {
                    $typename = 'coursegrouping';
                    $courseLevelClauseForCoursegrouping = " and ((categoryPageData.course_level in('Degree', 'Dual Degree') and (course_level_1 in('Under Graduate','Post Graduate') or course_level_2 in('Under Graduate','Post Graduate'))) or (categoryPageData.course_level in('Diploma') and categoryPageData.course_level_1 in('Diploma','Post Graduate Diploma')) or (categoryPageData.course_level in('Dual Degree') and categoryPageData.course_level_1 in('Under Graduate','Post Graduate') and categoryPageData.course_level_2 in('Under Graduate','Post Graduate')) or (categoryPageData.course_level in('Certification') and (categoryPageData.course_level_1 = '')) or (categoryPageData.course_level in('Vocational') and (categoryPageData.course_level_1 = '')))";
                }

                break;
            case 'categorymostviewed':
            case 'categorypages':
                $courseLevelClause = " and ((course_level in('Degree', 'Dual Degree') and (course_level_1 in('Under Graduate','Post Graduate') or course_level_2 in('Under Graduate','Post Graduate'))) or (course_level in('Diploma') and course_level_1 in('Diploma','Post Graduate Diploma')) or (course_level in('Dual Degree') and course_level_1 in('Under Graduate','Post Graduate') and course_level_2 in('Under Graduate','Post Graduate')) or (course_level in('Certification') and (course_level_1 = '')))";

                // $courselevelorder = " order by FIELD(course_level_1,'Under Graduate','Diploma','Post Graduate','Post Graduate Diploma',''),FIELD(2,'Certification'),FIELD(course_type,'Full time','Part time','Correspondence','E-learning')";

                // Added by Amit on 18th March 2011 for sorting the records (Performnace optimization)..
                $courselevelorder = " order by course_level_1, course_level, course_type";
                $pageRecordsSortingOrder = array(
                                                "course_level_order" =>  array("All", "UG Degree", "Diploma", "PG Degree", "PG Diploma", "Certification"),
                                                "course_type_order" =>  array("Full Time", "Part Time", "Correspondence", "E-learning")
                                            );


                $seocoursename = 'course';
                $seoinstitname = 'institute';
                // Getting the Top 20 Institutes here..
                $sql = "select c.city_id,b.institute_name,b.institute_id from topinstitutes a ,institute b ,institute_location_table c where a.instituteid = b.institute_id and b.status = 'live' and a.status = 'published' and b.institute_id = c.institute_id and c.status = 'live' and a.categoryid = ?";
                $queryResult = $dbHandle->query($sql,array($categoryId));
                $showtoptab = 'false';
                if($queryResult->num_rows() > 0)
                {
                    $showtoptab = 'true';

                }
                break;
            case 'countrypage' : $clustering = 0;
                                 break;
        };
        
        
        
        // error_log("\n\nAmit pagename : ".print_r($pagename,true),3,'/home/infoedge/Desktop/log.txt');
        if($pagename == "countrypage")
        {
            $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,0);
        }
        else
        {
            $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,$categoryId);
        }

        if($clustering == 1)
        {
            if($course_level != '' && $course_level != 'All')
            {
                // Amit course level 1 impact.
                if(strcmp($course_level,'Diploma') == 0 && strcmp($course_level_1,'Post Graduate') == 0)
                {
                    $course_level_1 = 'Post Graduate Diploma';
                }
                if($course_level_1 == 'NULL')
                    $course_level_1 = '';
            }
            $courseTypeClause = " and course_type in ('Full time','Part time','E-learning','Correspondence') ";

error_log("KKIV: ".$courseLevelClause.$courselevelorder.$subcategoryId.$cityClause.$cityCondition.$courseTypeClause.$course_level_1.$course_level.$optionalCategoryId.$pageRecordsSortingOrder);

            $countArray = $this->getClustersAccordingtoSelection($dbHandle,$courseLevelClause,$courselevelorder,$subcategoryId,$cityClause,$cityCondition,$courseTypeClause,$course_level_1,$course_level, $optionalCategoryId, $pageRecordsSortingOrder);
            // error_log("\nAmit ".print_r($countArray,true),3,'/home/infoedge/Desktop/log.txt');

            // Now re-assigning the $courseLevelClause as now we have to get data from categoryPageData table..
            if(!empty($courseLevelClauseForCoursegrouping))
            $courseLevelClause = $courseLevelClauseForCoursegrouping;

            if(empty($countArray))
            {
                //return json_encode($countArray);
                
                $countArray['showtoptab'] = $showtoptab;
                $countArray['totalcount'] = 0;
                $response['countArray'] = $countArray;
                
                //return base64_encode(json_encode($response));
            }
            
            // error_log("in 4191 course_level : ".print_r($course_level,true),3,'/home/infoedge/Desktop/log.txt'); error_log(", course_type : ".print_r($course_type,true),3,'/home/infoedge/Desktop/log.txt');
            if($course_level != '' && $course_level != 'All')
            {
                // Now getting the formatted Course Level value.. (By Amit Kuksal on 21 Feb 2011 for Category Page Revamp)..
                // $courseLevelClause = $this->getCourseLevelClause($course_level, $course_level_1);
                $responseDataCourseLevelClause = $this->getCourseLevelClause($course_level, $course_level_1);                
                $courseLevelClause = $responseDataCourseLevelClause["courseLevelClause"];
                $degree_course_info = $responseDataCourseLevelClause["degree_course_info"];
                // error_log("in degree_course_info : ".print_r($degree_course_info,true),3,'/home/infoedge/Desktop/log.txt');
            }


            if($course_type != '' && $course_type != 'All') {
                // Now getting the formatted Course Type value.. (By Amit Kuksal on 22 Feb 2011 for Category Page Revamp)..
                $courseTypeClause = $this->getCourseTypeClause($course_type);
            } else {
                // $courseTypeClause = " and course_details.course_type in ('Full time','Part time','E-learning','Correspondence') ";
                $courseTypeClause = " and categoryPageData.course_type in ('Full time','Part time','E-learning','Correspondence') ";
            }
        }
        
        // error_log("\n\n 4191 course_level clause : ".print_r($courseLevelClause,true),3,'/home/infoedge/Desktop/log.txt');   error_log("\n\n course_type caluse : ".print_r($courseTypeClause,true),3,'/home/infoedge/Desktop/log.txt');

        $endtime = microtime(true);

        //Get Main Institutes
        $notTheseInstitutes = -1;
        if($course_level == 'All' || $course_type == 'All' && $pagename == 'naukrishiksha')
        {
            $courselevelgrouping = 1;
        }
        $response['InstituteIds'] = array();
        $response['Institutes'] = array();
        $response['Courses'] = array();
        $response['mainInstituteIds'] = array();
        $response['mainInstitutes'] = array();
        $response['mainCourses'] = array();
        $response['paidInstituteIds'] = array();
        $response['paidInstitutes'] = array();
        $response['paidCourses'] = array();
        $response['freeInstituteIds'] = array();
        $response['freeInstitutes'] = array();
        $response['freeCourses'] = array();
        $response['categoryInstituteIds'] = array();
        $response['categoryInstitutes'] = array();
        $response['categoryCourses'] = array();
        $Icon = '';
        $totalRecords = 0;

        if(($pagename == "categorypages" || $pagename == "countrypage") && $start == 0)
        {
            $additionalParams = array("courseLevelClause" => $courseLevelClause, "courseTypeClause" => $courseTypeClause, "degree_course_info" => $degree_course_info);

            $categoryselector = $this->getCategorySelector($dbHandle,$categoryId,$countryId,$cityId,$pageKey, $additionalParams);
            // error_log("\nAmit ".print_r($categoryselector,true),3,'/home/infoedge/Desktop/log.txt');
            $endtime = microtime(true);
            $timetaken = ($endtime - $starttime);
            // error_log($timetaken.'CATEGORYTIMETAKEN');
            // error_log(print_r($categoryselector,true).'CATEGORYSELECTOR');

            $countArray['categoryselector'] = $categoryselector['ids'];
            $response['categoryInstitutes'] = $categoryselector['mainInstitutes'];
            $response['bannerarr'] = $categoryselector['bannerarr'];
            $response['categoryInstituteIds'] = $categoryselector['mainInstituteIds'];
            $response['categoryCourses'] = $categoryselector['mainCourses'];
            $totalRecords += $categoryselector['totalRecords'];
            $Icon = $categoryselector['logo_link'];
            $notTheseInstitutes = $categoryselector['notInstitutes'];
            if($start > 0)
                $start -= $categoryselector['totalRecords'];
            if($start == 0)
                $count -= $categoryselector['totalRecords'];
        }

        $callarr = array('main','paid','free');
        if($pagename == 'categorymostviewed')
        {
            $callarr = array('all');
        }

        $resultsFetched = 0;
        $data['pagekey'] = $pageKey ;
        $data['courselevelclause'] = $courseLevelClause;
        $data['coursetypeclause'] = $courseTypeClause ;
        $data['nottheseinstitutes'] = $notTheseInstitutes ;
        $data['subcategoryId'] = $subcategoryId ; // Amit subcategory impact..
        $data['cityId'] = $cityId ;
        $data['start'] = $start ;
        $data['count'] = $count ;
        $data['seocoursename'] = $seocoursename ;
        $data['countryId'] = $countryId ;
        $data['resultsFetched'] = $resultsFetched;
        $data['seoinstiname'] = $seoinstitname ;
        $data['pagename'] = $pagename ;
        $data['typename'] = $typename ;
        $data['courseinclusion'] = $clustering;
        $data['optionalCategoryId'] = $optionalCategoryId;
        $data['degree_course_info'] = $degree_course_info;
        $callArrayCount =  count($callarr);
        // error_log("\n Amit $pagename: ".print_r($callarr,true),3,'/home/infoedge/Desktop/log.txt');
        for($i = 0;$i < $callArrayCount; $i++)
        {
            $starttime1 = microtime(true);
            $data['type'] = $callarr[$i];
            if(($pagename == "categorypages" || $pagename == "naukrishiksha" || $pagename == "countrypage") && ($callarr[$i] != 'free'))
            {
                $orderbyclause = ' order by listings_main.viewCount desc';
            }
            $data['orderbyclause'] = $orderbyclause;

            // error_log("\n Amit $i: ".print_r($data,true),3,'/home/infoedge/Desktop/log.txt');

            $data['pagename'] = $pagename ;
            $methodName = $this->getInstitutesForSelection($dbHandle,$data);
            // error_log("\nAmit  ".$callarr[$i]." : -------------------------------------------------------------- ".print_r($methodName,true),3,'/home/infoedge/Desktop/log.txt');

            $endtime = microtime(true);
            $timetaken = ($endtime - $starttime1);

            if($methodName['notInstitutes'] != '')
            {
                $data['nottheseinstitutes'] .= ','.$methodName['notInstitutes'];
            }
            $resultsFetched += count($methodName['mainInstitutes']);
            $tobsub = count($methodName['mainInstitutes']);
            if($type != 'main')
            {
                $tobsub = $methodName['totalRecords'];
            }
            $totalRecords += $methodName['totalRecords'];
            $count1 = $count - $resultsFetched;
            if($start > 0)
                $start = $start - $tobsub;
            if($count <= $resultsFetched)
                $count1 = $start = 0;
            if($start < 0)
                $start = 0;
            $data['count'] = $count1;
            $data['start'] = $start;
            $data['resultsFetched'] = $resultsFetched;
            if($callarr[$i] == 'main')
            {
                $countArray['mainids'] = $methodName['mainids'];
            }
            if($callarr[$i] == 'all')
            {

                $callarr[$i] = 'free';
            }
            $response[$callarr[$i].'Institutes'] = $methodName['mainInstitutes'];
            $response[$callarr[$i].'InstituteIds'] = $methodName['mainInstituteIds'];
            $response[$callarr[$i].'Courses'] = $methodName['mainCourses'];
            // error_log("\nAmit response = ".print_r($methodName['mainCourses'],true),3,'/home/infoedge/Desktop/log.txt');
        }
        // error_log("\nAmit response = ".print_r($response,true),3,'/home/infoedge/Desktop/log.txt');
        $countArray['totalcount'] = $totalRecords;
        $countArray['selectedIcon'] = $Icon;
        $countArray['showtoptab'] = $showtoptab;
        $response['countArray'] = $countArray;

        

        // error_log("\nAmit response = ".print_r($response,true),3,'/home/infoedge/Desktop/log.txt');

        // error_log(print_r($response,true).'RESPONSE1');
        $endtime = microtime(true);
        $timetaken = ($endtime - $starttime);
        return base64_encode(json_encode($response));
    }


/*  This function is defined by Amit Kuksal on 21st Feb 2011
 *  Purspose: Category Page Revamp Changes
 *  It takes Course Level and Course Level1 as arguments (both can be either array or string) on the basis of which we define the Course Level clause.
 */
function getCourseLevelClause($course_level, $course_level_1) {
            
                // error_log("\n\n Course Level : ".print_r($course_level,true)." \n\n course level 1 : ".print_r($course_level_1, true),3,'/home/infoedge/Desktop/log.txt');
                $degree_course_info = array();

                $course_level_str = "";
                $courseLevelClause = " AND ( ";
                // error_log("\n\n Course Level ".print_r($course_level,true)." \n\n course level 1 : ".print_r($course_level_1, true),3,'/home/infoedge/Desktop/log.txt');
                // // error_log("\n\n Amit queryCmd = : ".print_r($queryCmd,true),3,'/var/www/html/testlog.txt');
                // Checking if the Course Level is an array (i.e. it has multiple arguments)..
                if(is_array($course_level)) {
                    $course_level_count = count($course_level);

                    for($countVar = 0; $countVar < $course_level_count; $countVar++) {
                        // $course_level_str = "";
                        if(strcmp(trim(strtolower($course_level[$countVar])),'degree') == 0)
                        {
                          $course_level_str = '"Degree", "Dual Degree"';
                          //error_log("\n\n Course Level str in main if :  ".print_r($course_level_str,true),3,'/home/infoedge/Desktop/log.txt');
                          $degree_course_info["degree"]= "Degree";

                        } else {
                          $course_level_str = '"'.trim($course_level[$countVar]).'"';
                          //error_log("\n\n Course Level str in Else :  ".print_r($course_level_str,true),3,'/home/infoedge/Desktop/log.txt');
                        }   // End of if(strcmp($course_level[$countVar],'Degree') == 0).

                        if(strcmp(trim(strtolower($course_level[$countVar])),'diploma') == 0 && strcmp($course_level_1[$countVar],'Under Graduate') == 0) {
                                $course_level_1[$countVar] = "Diploma";
                        } elseif(strcmp(trim($course_level[$countVar]),'diploma') == 0 && strcmp($course_level_1[$countVar],'Post Graduate') == 0) {
                                $course_level_1[$countVar] = 'Post Graduate Diploma';
                        }

                        if($countVar == 0)
                            // $courseLevelClause .= ' (  course_details.course_level in ('.$course_level_str.') ';
                            $courseLevelClause .= ' (  categoryPageData.course_level in ('.$course_level_str.') ';
                        else
                            // $courseLevelClause .= ' OR (  course_details.course_level in ('.$course_level_str.') ';
                            $courseLevelClause .= ' OR (  categoryPageData.course_level in ('.$course_level_str.') ';

                        if($course_level_1[$countVar] != 'NULL' AND $course_level_1[$countVar] != '') {
                           // $courseLevelClause .= ' and categoryPageData.course_level_1 in ("'.$course_level_1[$countVar].'")';

                           if(strpos($course_level_str, "Dual Degree") !== false) {
                                // error_log("\n\n Amit yes in 1: ",3,'/home/infoedge/Desktop/log.txt');
                                $courseLevelClause .= ' and ( categoryPageData.course_level_1 in ("'.$course_level_1[$countVar].'")  OR categoryPageData.course_level_2 in ("'.$course_level_1[$countVar].'"))';
                                $degree_course_info["course_level_1"]= $course_level_1[$countVar];
                           } else {
                               $courseLevelClause .= ' and categoryPageData.course_level_1 in ("'.$course_level_1[$countVar].'")';
                           }
                        }

                        $courseLevelClause .= ' )';

                    }   // End of for($countVar = 0; $countVar < $course_level_count; $countVar++).

                } else {

                    if(strcmp(trim($course_level),'Degree') == 0)
                    {
                        $course_level_str = $course_level = '"Degree","Dual Degree"';
                        $degree_course_info["degree"]= "Degree";
                    } else {
                        $course_level_str = '"'.$course_level.'"';
                    }

                    if(strcmp(trim($course_level),'Diploma') == 0 && strcmp($course_level_1,'Post Graduate') == 0) {
                                $course_level_1 = 'Post Graduate Diploma';
                     }

                    // $courseLevelClause .= ' course_details.course_level in ('.$course_level_str.') ';
                    $courseLevelClause .= ' categoryPageData.course_level in ('.$course_level_str.') ';

                    if($course_level_1 != 'NULL' AND $course_level_1 != '') {
                        // $courseLevelClause .= ' and categoryPageData.course_level_1 in ("'.$course_level_1.'")';

                            if(strpos($course_level_str, "Dual Degree") !== false) {
                                // error_log("\n\n Amit yes in 1: ",3,'/home/infoedge/Desktop/log.txt');
                                $courseLevelClause .= ' and ( categoryPageData.course_level_1 in ("'.$course_level_1.'")  OR categoryPageData.course_level_2 in ("'.$course_level_1.'"))';
                                $degree_course_info["course_level_1"] = $course_level_1;
                           } else {
                               $courseLevelClause .= ' and categoryPageData.course_level_1 in ("'.$course_level_1.'")';
                           }
                    }

                   // error_log("\n\n Amit yes in 2 ",3,'/home/infoedge/Desktop/log.txt');

                } // End of if(is_array($course_level)).

         $courseLevelClause .= " ) ";

         // error_log("\n\n Final degree course info : ".print_r($degree_course_info,true),3,'/home/infoedge/Desktop/log.txt');
         $responseData = array("courseLevelClause" => $courseLevelClause, "degree_course_info" => $degree_course_info);

    return ($responseData);

}   // End of function getCourseLevelClause($course_level, $course_level_1).


/*  This function is defined by Amit Kuksal on 22nd Feb 2011
 *  Purspose: Category Page Revamp Changes
 *  It takes Course Type as an argument (it can be either array or string) on the basis of which we define the Course Type clause.
 */
function getCourseTypeClause($course_type) {
               if(is_array($course_type)) {
                    // $courseTypeClause = " and course_details.course_type in ('".implode("','", $course_type)."') ";
                   $courseTypeClause = " and categoryPageData.course_type in ('".implode("','", $course_type)."') ";
                } else {
                    // $courseTypeClause = ' and course_details.course_type = "'.$course_type.'"';
                    $courseTypeClause = ' and categoryPageData.course_type = "'.$course_type.'"';
                }
         // error_log("\n\n Amit QUERY : ".print_r($courseTypeClause,true),3,'/home/infoedge/Desktop/log.txt');
         return ($courseTypeClause);

}   // End of function getCourseTypeClause($course_type).


function getTopInstitutes($dbHandle,$parameters)
{
    $appId = $parameters['0'];
    $categoryid = $parameters['1'];
    //error_log(print_r($parameters,true).'PARAMS');

    $subcategoryid=$this->getChildIds($dbHandle,$categoryid);

    // This query has been updated by Amit Kuksal on 16th Feb 2011 for implementing the Category Page revamp changes (Added Aima Rating and USP in query)..
    $sql = "select c.city_id,c.country_id,b.institute_name, b.institute_id, b.aima_rating, b.usp from topinstitutes a ,institute b ,institute_location_table c where a.instituteid = b.institute_id and b.status = 'live' and a.status = 'published' and b.institute_id = c.institute_id and c.status = 'live' and a.categoryid = ? order by institute_name asc";

    // $coursearray[$j]['institute']['aima_rating']= $row->aima_rating;
    $queryResult = $dbHandle->query($sql,array($categoryid));
    $originalQuery= $queryResult;
    $j = 0;

    $insIdString='';
    $firstCounter=0;
    foreach ($queryResult->result() as $row)
    {
        // new identifiers to cpature all header image info in one go
        $firstCounter++;
        if($firstCounter > 1)
            $insIdString.=",".$row->institute_id;
        else
            $insIdString.=$row->institute_id;



        $institute_id = $row->institute_id;
        //BPSCHANGE
        $query = 'select SQL_CALC_FOUND_ROWS (count(*)) totalRows, course_details.institute_id,course_details.fees_value,course_details.fees_unit,course_details.courseTitle,course_details.course_type,course_details.duration_value,course_details.duration_unit,course_details.course_id,course_details.course_level,course_details.course_level_1,course_details.course_level_2, course_details.version from course_details,listing_category_table where course_details.status = "live" and course_details.institute_id = ? and listing_category_table.listing_type_id = course_details.course_id and listing_category_table.listing_type = "course" and listing_category_table.status = "live" and listing_category_table.category_id in('.$subcategoryid.') group by course_id ORDER BY course_order limit 4';
        //error_log($query);
        // error_log("\n\n query = ".print_r($query,true),3,'/home/infoedge/Desktop/log.txt');
        $queryResult = $dbHandle->query($query,array($institute_id));
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        $totalRows = 0;
        $course_details = $queryResult->result_array();
        foreach ($queryTotal->result() as $rowTotal) {
            $totalRows = $rowTotal->totalRows;
        }
        $i = 0;
        $maincourse = array();
	//Added by Ankur on 25th Oct to add the Institute location to the URL
	$locationArrayTemp = array();
	$cityName = array($cacheLib->get("city_".$row->city_id),'string');
	$countryName = array($cacheLib->get("country_".$row->country_id),'string');
	$locationArrayTemp[0] = $cityName[0]."-".$countryName[0];
	$optionalArgs = array();
	$optionalArgs['location'] = $locationArrayTemp;
	$optionalArgs['institute'] = $row->institute_name;
	//End Modifications by Ankur

        $collectedCourseIds = "";

        foreach($course_details as $row1)
        {

            if($i==0)
                $collectedCourseIds = $row1['course_id'];
            else
                $collectedCourseIds .= ", ".$row1['course_id'];


            $courseUrl = getSeoUrl($row1['course_id'],'course',$row1['courseTitle'],$optionalArgs);
            $maincourse[$i]['course_level_1']= $row1['course_level'];
            $maincourse[$i]['course_level']= $row1['course_level_1'];
            $maincourse[$i]['course_type']= $row1['course_type'];
            $maincourse[$i]['duration']= $row1['duration_value']." ".$row1['duration_unit'];
            //BPSCHANGE
            $maincourse[$i]['fees_value']= $row1['fees_value'];
            $maincourse[$i]['fees_unit']= $row1['fees_unit'];


            $maincourse[$i]['course_id'] = $row1['course_id'];
            $maincourse[$i]['course_title'] = $row1['courseTitle'];
            $maincourse[$i]['courseurl'] = $courseUrl;
            $maincourse[$i]['institute_id'] = $row1['institute_id'];

            $i++;
        }

        $instituteCourseArray[] = array("0" => $row1['institute_id'], "1" => $collectedCourseIds);

        $coursearray[$j]['institute']['id'] = $row->institute_id;
        $coursearray[$j]['institute']['institute_Name'] = $row->institute_name;
        $optionalArgs['institute'] = $row->institute_name;
        $url  = getSeoUrl($row->institute_id,'institute',$row->institute_name,$optionalArgs);
        $cityName  = $cacheLib->get("city_".$row->city_id);

        $coursearray[$j]['institute']['url']=$url;
        $coursearray[$j]['institute']['city']=$cityName;

        //BPSCHANGE
        $coursearray[$j]['institute']['countryName']= trim($cacheLib->get("country_".$row->country_id));
        $mediaDetails = $this->getMediaData($dbHandle,$row->institute_id);
        $coursearray[$j]['institute']['mediadata']= $mediaDetails;
        $coursearray[$j]['institute']['alumin_rating']= $mediaDetails['rating'];

        $coursearray[$j]['institute']['aima_rating']= $row->aima_rating;
        $coursearray[$j]['institute']['usp']= $row->usp;

        $coursearray[$j]['courses']=$maincourse;
        $j++;
    }

    // Need to call the function here to get the Salient Features and Exam data by passing $instituteCourseArray

   $reponseDataArray = $this->getExamDataSalientFeaturesForRichSnippets($dbHandle, $instituteCourseArray);

   // Lets collect the information now..
   for($countVariable = 0; $countVariable < $j; $countVariable++) {
        $courseCount = count($coursearray[$countVariable]["courses"]);
        for($cnt = 0; $cnt < $courseCount; $cnt++) {
            if($coursearray[$countVariable]["courses"][$cnt]["course_id"] == $reponseDataArray[$countVariable]["courses"][$cnt]["course_id"]) {
                $coursearray[$countVariable]["courses"][$cnt]["approved_granted_by"] = $reponseDataArray[$countVariable]["courses"][$cnt]["approved_granted_by"];
                $coursearray[$countVariable]["courses"][$cnt]["affiliated_to"] = $reponseDataArray[$countVariable]["courses"][$cnt]["affiliated_to"];
                $coursearray[$countVariable]["courses"][$cnt]["eligibility_exams"] = $reponseDataArray[$countVariable]["courses"][$cnt]["eligibility_exams"];
                $coursearray[$countVariable]["courses"][$cnt]["salient_features_ids"] = $reponseDataArray[$countVariable]["courses"][$cnt]["salientFeaturesIDs"];
            }

        }   // End of for($cnt = 0; $cnt < $courseCount; $cnt++).

   }    // End of for($countVariable = 0; $countVariable < $j; $countVariable++).

    // error_log("\n\n array = ".print_r($reponseDataArray,true),3,'/home/infoedge/Desktop/log.txt');

   // error_log("\n\n array upto $j = ".print_r($coursearray,true),3,'/home/infoedge/Desktop/log1.txt');

    if(!is_array($insIdString)){
        $insIdString = explode(',', $insIdString);
    }

    // get all header image info; Bhuvnesh
    $qryHeader = "SELECT listing_id,thumb_url FROM header_image WHERE listing_id IN (?) AND listing_type = 'institute' AND status = 'live' GROUP BY listing_id";

    $queryH =  $dbHandle->query($qryHeader,array($insIdString));
    $headIns= array();
    $headThumb= array();
    foreach ($queryH->result() as $ro)
    {
        array_push($headIns,$ro->listing_id);
        array_push($headThumb,$ro->thumb_url);
    }
    $newCounter=0;

    foreach ($originalQuery->result() as $row)
    {

         if(in_array($row->institute_id, $headIns))
         {
             $indX= array_keys($headIns,$row->institute_id);
             $coursearray[$newCounter]['institute']['headerImageUrl'] = $headThumb[$indX[0]];
         }
         else
         {
                    $coursearray[$newCounter]['institute']['headerImageUrl']= '';
         }
         $newCounter++;
    }
    // get all header image info End ; Bhuvnesh

    //error_log(print_r($coursearray,true).'COURSEARRAY');
    $mainInstitutes = array();
    $mainInstitutes['institutesarr'] = $coursearray;
    return json_encode($mainInstitutes);

}

    function getCourseDetailsForInstitutes1($institute_id,$categoryids,$courseLevelClause,$courseTypeClause,$dbHandle,$limitflag,$seocoursename)
    {
        $limitval = '';
        $groupbyclause = 'group by course_details.course_id,8,course_level_1,course_type';
        if($limitflag > 0)
        {
        $limitval = ' limit ' . $limitflag;
        $groupbyclause = 'group by course_details.course_id';
        }

       // Courses to be taken for the selection of course type,course level
       // Ordering on the basis of view count of course(should the grouping be on the basis of
       // limit 5

        if(!is_array($categoryids)){
            $categoryids = explode(',', $categoryids);
        }
        $query = 'select SQL_CALC_FOUND_ROWS (count(*)) totalRows, course_details.institute_id,course_details.courseTitle,course_details.course_type,course_details.duration_value,course_details.duration_unit,course_details.course_id,course_details.course_level,if(course_details.course_level = "Dual Degree","Degree",course_level) as courselevel,course_details.course_level_1,course_details.course_level_2 from course_details,listings_main,listing_category_table where course_details.status = "live" '. $courseTypeClause . $courseLevelClause .' and course_details.institute_id = ? and listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and listings_main.status = "live" and listing_category_table.listing_type_id = course_details.course_id and listing_category_table.listing_type = "course" and listing_category_table.status = "live" and listing_category_table.category_id in(?) '. $groupbyclause .' order by listings_main.viewCount desc '.$limitval;
        // error_log($query);
        $queryResult = $dbHandle->query($query,array($institute_id,$categoryids));
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        $totalRows = 0;
        foreach ($queryTotal->result() as $rowTotal) {
            $totalRows = $rowTotal->totalRows;
        }
        $course_details = $queryResult->result_array();
        $maincourse = array();
        $maincoursemapping = array();
        $coursearray = array();
        $i = 0;
        foreach($course_details as $row1)
        {
            if($row1['course_level_1'] == 'NULL' || trim($row1['course_level_1']) == trim($row1['courselevel']))
                $courseLevel_1 = trim($row1['courselevel']);
            else
                $courseLevel_1 = trim($row1['course_level_1']).' '.trim($row1['courselevel']);
            if($row1->course_level_1 == "Post Graduate Diploma")
                $courseLevel_1 = trim($row1['course_level_1']);
            $courseUrl = getSeoUrl($row1['course_id'],$seocoursename,$row1['courseTitle'],$optionalArgs);
            $maincourse[$i]['course_id'] = $row1['course_id'];
            $maincourse[$i]['course_title'] = $row1['courseTitle'];
            $maincourse[$i]['course_level'] = $courseLevel_1;
            $maincourse[$i]['course_level_1'] = $row1['course_level'];
            $maincourse[$i]['course_level_2'] = $row1['course_level_1'];
            $maincourse[$i]['course_type'] = $row1['course_type'];
            $maincourse[$i]['duration'] = $row1['duration_value'].' '.$row1['duration_unit'];
            $maincourse[$i]['courseurl'] = $courseUrl;
            $maincourse[$i]['institute_id'] = $row1['institute_id'];
            $maincoursemapping[$institute_id][$i] = $row1['course_id'];
            $i++;
        }
        $coursearray['course'] = $maincourse;
        $coursearray['coursemapping'] = $maincoursemapping;
        $coursearray['totalRows'] = $totalRows;
        return $coursearray;
    }
    function getCourseDetailsForInstitutes($dbHandle,$allcoursesids,$seocoursename,$instLocation, $degree_course_info)
    {
        // error_log("\nAmit degree_course_info: ".print_r($degree_course_info,true),3,'/home/infoedge/Desktop/log.txt');
        $limitval = '';
        $groupbyclause = 'group by course_details.course_id,8,course_level_1,course_type';
        if($limitflag > 0)
        {
            $limitval = ' limit ' . $limitflag;
            $groupbyclause = 'group by course_details.course_id';
        }
        if(!is_array($allcoursesids)){
            $allcoursesids = explode(',', $allcoursesids);
        }

        // This query has been updated by Amit Kuksal on 14th Feb 2011 for implementing the Category Page revamp changes..
        $query = 'select course_details.institute_id,course_details.courseTitle,course_details.course_type,course_details.duration_value,course_details.duration_unit,course_details.course_id,course_details.course_level,if(course_details.course_level = "Dual Degree","Degree",course_level) as courselevel,course_details.course_level_1,course_details.course_level_2, course_details.fees_value, course_details.fees_unit, course_details.version, institute.institute_name, course_details.course_order from course_details, listings_main, institute where course_details.status = "live" and listings_main.status = "live" and listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and course_id in (?) and course_details.institute_id = institute.institute_id and institute.status = "live" order by institute_id, course_details.course_order, listings_main.viewCount';
        
        $queryResult = $dbHandle->query($query,array($allcoursesids));
        // error_log(print_r($queryResult,true).'MAINCOURSE');
        $course_details = $queryResult->result_array();
        // error_log(print_r($course_details,true).'MAINCOURSE');
        $maincourse = array();
        $i = 0;
        foreach($course_details as $row1)
        {
            if($row1['course_level_1'] == 'NULL' || trim($row1['course_level_1']) == trim($row1['courselevel']))
                $courseLevel_1 = trim($row1['courselevel']);
            else
                $courseLevel_1 = trim($row1['course_level_1']).' '.trim($row1['courselevel']);
            if($row1->course_level_1 == "Post Graduate Diploma")
                $courseLevel_1 = trim($row1['course_level_1']);

            //Amit Singhal : New Method to fetch city and country
            $locationArrayTempcity = array();
            $cityName = array($cacheLib->get("city_".$instLocation[$row1['institute_id']]['city']),'string');
            $countryName = array($cacheLib->get("country_".$instLocation[$row1['institute_id']]['country']),'string');
            $locationArrayTempcity[0] = $cityName[0]."-".$countryName[0];
            //Amit Singhal : End Changes

            $optionalArgs = array();
	    $optionalArgs['location'] = $locationArrayTempcity;
            $optionalArgs['institute'] = $row1['institute_name'];	    

	    //Added by Ankur Gupta on 19 April to make the Seo Url call efficiently
	    if(isset($row1['URL']) && (string)$row1['URL'] != ''){
		$courseUrl = $row1['URL'];
	    }
	    else{
		$courseUrl = getSeoUrl($row1['course_id'],'course',$row1['courseTitle'],$optionalArgs,'old');
	    }
	    //End Modifications
            
            $maincourse[$i]['course_id'] = $row1['course_id'];
            $maincourse[$i]['course_title'] = $row1['courseTitle'];

            //*
            if(is_array($degree_course_info) && $degree_course_info["degree"] == "Degree") {
                if(strcmp(trim($degree_course_info["course_level_1"]), trim($row1['course_level_1'])) == 0) {
                    $maincourse[$i]['course_level'] = $courseLevel_1;
                    // error_log("\n 1 Course level 1: $courseLevel_1 & dgree course array info :  ".print_r($degree_course_info["course_level_1"],true),3,'/home/infoedge/Desktop/log.txt');
                } else {
                    $maincourse[$i]['course_level'] = $row1['course_level_2'];
                    // error_log("\n 2 Course level 1: $courseLevel_1 & dgree course array info :  ".print_r($degree_course_info["course_level_1"],true),3,'/home/infoedge/Desktop/log.txt');
                }

                $maincourse[$i]['course_level_1'] = $row1['course_level'];
                
            } else {
                $maincourse[$i]['course_level'] = $courseLevel_1;
                $maincourse[$i]['course_level_1'] = $row1['course_level'];
            }             
            
            //$degree_course_info
            $maincourse[$i]['course_level_2'] = $row1['course_level_1'];

            // The following code has been added by Amit Kuksal on 14th Feb 2011 for implementing the Category Page revamp changes..
            $maincourse[$i]['fees_value'] = $row1['fees_value'];
            $maincourse[$i]['fees_unit'] = $row1['fees_unit'];
            //$maincourse[$i]['approved_granted_by'] = $gatheredData['approved_granted_by'];
            // $maincourse[$i]['eligibility_exams'] = $gatheredData['eligibility_exams'];
            //$maincourse[$i]['salient_features_ids'] = $gatheredData['salientFeaturesIDs'];
            // $maincourse[$i]['affiliated_to'] = $gatheredData['affiliated_to'];

            $maincourse[$i]['course_type'] = $row1['course_type'];
            $maincourse[$i]['duration'] = $row1['duration_value'].' '.$row1['duration_unit'];
            $maincourse[$i]['courseurl'] = $courseUrl;
            $maincourse[$i]['institute_id'] = $row1['institute_id'];
            $maincourse[$i]['course_order'] = $row1['course_order'];

            $i++;
        }
        // error_log("\n\n mn : :  ".print_r($maincourse,true),3,'/home/infoedge/Desktop/log.txt');
        return $maincourse;
    }


function getExamDataSalientFeaturesForRichSnippets($dbHandle, $globalCourseArr) {

            // Caution: 4440 Volts !!
            $totalCourses; // comma seperated list of all acourses across all the institutes; required for the query IN clause
            $resultSet= array();// final result to be returned
            $cId= array();// array of all acourses across all the institutes
            $cidCounter=0;
            $approved= array();// tamp array holding approved_by value for each courses
            $affiliated= array();//tamp array holding affiliated to value for each courses
            $afforder= array();//to maintain the order of affiliated values
            $exam= array();// one variable cracks all exams right from JEE to CAT even the IAS , it works !!
            $salient= array();


            for($i=0; $i <count($globalCourseArr) ; $i++)
            {
                if($i > 0)
                $totalCourses.=",".$globalCourseArr[$i][1];
                else
                $totalCourses.=$globalCourseArr[$i][1];

                $courseTempArr= explode(',',$globalCourseArr[$i][1]);
                $courseTempRep = array();
                $tempCounter=0;
                foreach($courseTempArr as $k=> $v)
                {
                     $courseTempRep[$tempCounter++]= array("course_id"=>$v,"approved_granted_by"=>'',"affiliated_to"=>'',"eligibility_exams"=>'',"salientFeaturesIDs"=>'');
                     $cId[$cidCounter]= $v;
                     $approved[$cidCounter]='';
                     $afforder[$cidCounter]=0;
                     $exam[$cidCounter]='';
                     $salient[$cidCounter]='';
                     $affiliated[$cidCounter++]='';
                }
                $resultSet[$i]=array("institute_id"=>$globalCourseArr[$i][0],"courses"=>$courseTempRep);
            }
     if($totalCourses != ''){
            if(!is_array($totalCourses)){
                $totalCourses = explode(',', $totalCourses);
            }
            // Beware !! Course attribute Bakar begins here
            $queryCourseAttributes = "SELECT  ca.course_id, ca.attribute, ca.value FROM course_attributes ca WHERE ca.course_id IN (?) and ca.status = 'live' AND ca.attribute in ('UGCStatus', 'AICTEStatus', 'DECStatus', 'AffiliatedToIndianUniName', 'AffiliatedToForeignUniName', 'AffiliatedToDeemedUni', 'AffiliatedToAutonomous') ";
            $queryCourseAttributesRS = $dbHandle->query($queryCourseAttributes,array($totalCourses));
            foreach ($queryCourseAttributesRS->result() as $rowCourseAttributes) {


                    $indX= array_keys($cId,$rowCourseAttributes->course_id);


                    if($rowCourseAttributes->attribute == "AICTEStatus") {

                        if($approved[$indX[0]] == '')
                            $approved[$indX[0]]= "AICTE Approved";
                        else
                            $approved[$indX[0]].= ", AICTE Approved";
                    }
                    if($rowCourseAttributes->attribute == "UGCStatus") {


                        if($approved[$indX[0]] == '')
                            $approved[$indX[0]]= "UGC Recognised";
                        else
                            $approved[$indX[0]].= ", UGC Recognised";
                   }

                    if($rowCourseAttributes->attribute == "DECStatus") {

                        if($approved[$indX[0]] == '')
                            $approved[$indX[0]]= "DEC Approved";
                        else
                            $approved[$indX[0]].= ", DEC Approved";

                    }

                    // Collecting "Affiliated to" information now, As per the discussion with Stuti on 15th Feb 2011, only 1 affiliation (the first one) needs to be shown..
                    if($rowCourseAttributes->attribute == "AffiliatedToIndianUniName") {

                        if($afforder[$indX[0]] < 4)
                        {
                            $afforder[$indX[0]] =4;
                            $affiliated[$indX[0]]= "Affiliated to ".$rowCourseAttributes->value;
                        }

                    }

                    if($rowCourseAttributes->attribute == "AffiliatedToForeignUniName") {

                        if($afforder[$indX[0]] < 3)
                        {
                            $afforder[$indX[0]] =3;
                            $affiliated[$indX[0]]= "Affiliated to ".$rowCourseAttributes->value;
                        }

                    }

                    if($rowCourseAttributes->attribute == "AffiliatedToDeemedUni") {

                       if($afforder[$indX[0]] < 2)
                        {
                            $afforder[$indX[0]] =2;
                            $affiliated[$indX[0]]= "Affiliated to Deemed University";
                        }

                    }

                    if($rowCourseAttributes->attribute == "AffiliatedToAutonomous") {

                        if($afforder[$indX[0]] < 1)
                        {
                            $afforder[$indX[0]] =1;
                            $affiliated[$indX[0]]= "(Autonomous Program)";
                        }

                    }

            }   // Sorry Mario !! your princess is another castle probabaly with Bhuvnesh Pratap ;)


            // eligiblity exam bakar begins here !!
            $queryCourseExams = "select  b.acronym, l.typeId from  listingExamMap l join blogTable b on l.examId = b.blogId where type = 'course' and l.typeId IN (?) and l.status ='live' and b.status = 'live' ";
            $queryCourseExamsRS = $dbHandle->query($queryCourseExams,array($totalCourses));
            foreach ($queryCourseExamsRS->result() as $rowCourseExamsRS) {

                    $indX= array_keys($cId,$rowCourseExamsRS->typeId);
                    if($exam[$indX[0]] == '')
                        $exam[$indX[0]] = $rowCourseExamsRS->acronym;
                    else
                        $exam[$indX[0]] .=", ".$rowCourseExamsRS->acronym;
            }
            // exam stuff ends ...

            // salient bc begins
            $querySalientFeatures = "SELECT SUBSTRING_INDEX(group_concat( cf.salient_feature_id order by sf.display_order), ',', 4) as salient_feature_id, cf.listing_id FROM course_features cf, salient_features sf WHERE cf.salient_feature_id = sf.feature_id AND cf.listing_id IN (?) AND cf.status =  'live' AND sf.value != 'no' group by cf.listing_id";
            $querySalientFeaturesRS = $dbHandle->query($querySalientFeatures,array($totalCourses));
            foreach($querySalientFeaturesRS->result() as $rowSal)
            {
	 	  $indX= array_keys($cId,$rowSal->listing_id);
	 	  $salient[$indX[0]]=$rowSal->salient_feature_id;
            }
            // salient bc ends...

            foreach($resultSet as $insIndex => &$insAll)
            {

                foreach($insAll as $outerKey => &$courseVal)
                {

                    if($outerKey=='courses')
                    {
                        foreach($courseVal as $key=> &$value)
                        {
                            $indX= array_keys($cId,$value['course_id']);
                            $value['approved_granted_by']=$approved[$indX[0]];
                            $value['affiliated_to']=$affiliated[$indX[0]];
                            $value['eligibility_exams']= $exam[$indX[0]];
                            $value['salientFeaturesIDs']= $salient[$indX[0]];
                        }

                    }

                }

            }
        }
           // error_log("\n\n Bhuvan results:  =  ".print_r($resultSet,true),3,'/home/infoedge/Desktop/log.txt');
            return ($resultSet);
}

function getNumberOfMainInstiS($dbHandle, $parameters){
        // error_log("DODO In getNumberOfMainInstiS" );
        $appId=$parameters['0'];
        $askedCategoryId = $parameters['1'];
        $categoryId=$this->getChildIds($dbHandle,$parameters['1']);
        $countryId=$parameters['2'];
        $origPageKey=$parameters['3'];
        $cityId=$parameters['4']==""?1:$parameters['4'];
        $relaxFlag = $parameters['5'];
        $notTheseInstitutes = isset($parameters['6'])?$parameters['6']:'-1';
        $freeInstitutes =(isset($parameters['7']) && $parameters['7']!="")?$parameters['7']:1;
        $stateInstitutes =(isset($parameters['8']) && $parameters['10']!="")?$parameters['8']:1;
        $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,$askedCategoryId);
        $queryParams = array();
        if(!is_array($categoryId)){
            $categoryId = explode(',', $categoryId);   
        }
        $queryParams[] = $categoryId;
        if($countryId == 1 || $countryId ==''){
            $addCountryClause = ' ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }else{
            $addCountryClause = ' and institute_location_table.country_id in (?)';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
            if(!is_array($countryId)){
                $countryId = explode(',', $countryId);   
            }
            $queryParams[] = $countryId;
        }

        if(isset($cityId) &&  $cityId !='' && $cityId !=1){
            $addCityClause  = ' and institute_location_table.city_id in ('.$cityId.') ';
            $newAddCityClause  = ' and institute_location_table.city_id = virtualCityMapping.city_id and virtualCityMapping.virtualCityId =  ?';
            $queryParams[] = $cityId;
        }else{
            $addCityClause  = '  ';
            $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
        }

        $paidInstituteClause = ' and (listings_main.pack_type = 1 OR listings_main.pack_type = 2) ';
        $dbHandle = $this->getDbHandle();
        if($origPageKey == 1){

        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause $paidInstituteClause and listings_main.version =  institute.version and listings_main.version =  institute_location_table.version group by institute.institute_id";
        }else{
        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause $paidInstituteClause and listings_main.version =  institute.version and listings_main.version = institute_location_table.version group by institute.institute_id";
        }

        $query = $dbHandle->query($queryCmd,$queryParams);

        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        $totalRows = 0;
        // error_log("puneet $totalRows");
        foreach ($queryTotal->result() as $rowTotal) {
            $totalRows = $rowTotal->totalRows;
            // error_log("puneet $totalRows");
        }
        $response = array();
        array_push($response,array(
                    array(
                        'count'=>array($totalRows,'string'),
                        ),'struct'));//close array_push
        $response = array($response,'struct');
        return $response;


}

function getInstitutesForHomePageS($dbHandle, $parameters)
{
    $cacheLib = $this->load->library('cacheLib');
    
    // error_log_shiksha("LISTING:".print_r($parameters,true));
    $appId=$parameters['0'];
    $askedCategoryId = $parameters['1'];
    $categoryId=$this->getChildIds($dbHandle,$parameters['1']);
    $countryId=$parameters['2'];
    $start=$parameters['3'];
    $count=$parameters['4'];
    $origPageKey=$parameters['5'];
    $cityId=$parameters['6']==""?1:$parameters['6'];
    $relaxFlag = $parameters['7'];
    $notTheseInstitutes = isset($parameters['8'])?$parameters['8']:'-1';
    $freeInstitutes =(isset($parameters['9']) && $parameters['9']!="")?$parameters['9']:1;
    $stateInstitutes =(isset($parameters['10']) && $parameters['10']!="")?$parameters['10']:1;
    $pageKey=$this->getKeyPageId($dbHandle,0,$countryId,$cityId,$askedCategoryId);
    $catIdArr = $categoryId;
    if(!is_array($catIdArr)){
        $catIdArr = explode(',', $catIdArr);   
    }
    if($countryId == 1 || $countryId == ''){
        $addCountryClause = ' ';
        $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
    }else{
        $addCountryClause = ' and institute_location_table.country_id in ('.$countryId.')';
                $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
                }

                if(isset($cityId) &&  $cityId !='' && $cityId !=1){
                $addCityClause  = ' and institute_location_table.city_id in ('.$cityId.') ';
                $newAddCityClause  = ' and institute_location_table.city_id = virtualCityMapping.city_id and virtualCityMapping.virtualCityId =  '.$cityId.' ';
                }else{
                $addCityClause  = '  ';
                $newAddCityClause  = ' and institute_location_table.city_id  = virtualCityMapping.city_id ';
                }

                $paidInstituteClause = ' and (listings_main.pack_type = 1 OR listings_main.pack_type = 2) ';
                $dbHandle = $this->getDbHandle();
                //Fetching results for ticked institutes (set by CMS)
                $queryCmd = "select SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type,'main' as typeofinsti from PageCollegeDb, institute, listing_category_table ,institute_location_table, listings_main where listing_category_table.listing_type='institute' and  PageCollegeDb.listing_type_id = institute.institute_id and listing_category_table.listing_type_id = institute.institute_id and listing_category_table.category_id in (?) and PageCollegeDb.KeyId in ($pageKey) and institute_location_table.institute_id = institute.institute_id and CURDATE() >= PageCollegeDb.StartDate and CURDATE() <= PageCollegeDb.EndDate and PageCollegeDb.listing_type='institute' and PageCollegeDb.status='live' and listings_main.listing_type='institute' and listings_main.listing_type_id = institute.institute_id and listings_main.status='live' and institute.institute_id not in ($notTheseInstitutes) and listings_main.version =  institute.version and listings_main.version=institute_location_table.version group by institute_id order by listings_main.viewCount DESC ";
                // error_log("NEHA LISTING:CMS QUERY:".$queryCmd);
                $query = $dbHandle->query($queryCmd,array($catIdArr));
                //what are CMS Rows
                $cmsRows=$query->num_rows();
                //init the response array
                $instituteArray = array();
                $instituteIds = ' -1 ';
                $end = $start + $count;
                $resultsFetched = 0;
                $counter = 0;
                if($cmsRows > $start)
                {
                    foreach ($query->result() as $row){
                        if(strlen($instituteIds)>0)
                        {
                            $instituteIds .= ' , ';
                        }
                        $instituteIds .= "$row->institute_id";
                        if($counter >= $start && $counter < $end)
                        {
                            $resultsFetched += 1;
                            $optionalArgs = array();
                            $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
                            $media = $this->getMediaData($dbHandle,$row->institute_id);
                            $locationArrayTemp = $locations['locationArrayTemp'];
                            $optionalArgs = $locations['optionalArgs'];
                            $onlyOneCityFlag = $locations['onlyOneCityFlag'];
                            $optionalArgs['institute'] = $row->institute_name;
                            $courseArrayTemp =  $this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs);
                            $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                            $logo_link =  $this->getInstituteLogo($row->logo_link);
                            $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                            array_push($instituteArray,array(
                                        array(
                                            'title'=>array($row->institute_name,'string'),
                                            'id'=>array($row->institute_id,'string'),
                                            'logo_link'=>array($logo_link,'string'),
                                            'url'=>array($url,'string'),
                                            'isSponsored'=>array('true','string'),
                                            'typeofinsti'=>array($row->typeofinsti,'string'),
                                            'isSendQuery'=>array($isSendQuery,'string'),
                                            'locationArr'=>array($locationArrayTemp,'struct'),
                                            'mediadata'=>array($media,'struct'),
                                            'courseArr'=>array($courseArrayTemp,'struct')
                                            ),'struct')
                                    );//close array_push
                        }
                        $counter++;
                    }
                }
                else{
                    foreach ($query->result() as $row){
                        if(strlen($instituteIds)>0){
                            $instituteIds .= ' , ';
                        }
                        $instituteIds .= "$row->institute_id";
                    }
                }

                if($count > $resultsFetched){
                    //fetch paid institutes
                    $mainListingCount = $count - $resultsFetched;
                    if(($resultsFetched == 0) && ($start >= $cmsRows)){
                        $mainListingStart = $start - $cmsRows;
                    }
                    else
                    {
                        $mainListingStart = 0;
                    }
                    if($origPageKey == 1)
                    {
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type,'paid' as typeofinsti from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?) and tSearchSnippetStatTemp.type='institute' and tSearchSnippetStatTemp.listingId = institute.institute_id  and institute.institute_id not in ($notTheseInstitutes) $paidInstituteClause and listings_main.version =  institute.version and listings_main.version =  institute_location_table.version group by institute.institute_id order by tSearchSnippetStatTemp.count  DESC LIMIT $mainListingStart, $mainListingCount ";
                    }
                    else
                    {
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link , listings_main.pack_type as pack_type,'paid' as typeofinsti from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?)   and institute.institute_id not in ($notTheseInstitutes) $paidInstituteClause and listings_main.version =  institute.version and listings_main.version = institute_location_table.version group by institute.institute_id order by rand() , listings_main.viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
                    }

                    // error_log_shiksha("LISTING:MAIN QUERY:".$queryCmd);
                    // error_log("puneet LISTING:MAIN QUERY:".$queryCmd);
                    $instIds = $instituteIds;
                    if(!is_array($instIds)){
                        $instIds = explode(',', $instituteIds);   
                    }
                    $query = $dbHandle->query($queryCmd,array($catIdArr,$instIds));

                    $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                    $queryTotal = $dbHandle->query($queryCmdTotal);
                    $totalRows = 0;
                    // error_log("puneet $totalRows");
                    foreach ($queryTotal->result() as $rowTotal)
                    {
                        $totalRows = $rowTotal->totalRows;
                        // error_log("puneet $totalRows");
                        // error_log_shiksha("LISTING::total rows:".$totalRows);
                    }
                    $totalRows += $cmsRows;
                    // error_log("puneet $totalRows");
                    foreach ($query->result() as $row)
                    {
                        $resultsFetched += 1;
                        $optionalArgs = array();
                        $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
                        $media = $this->getMediaData($dbHandle,$row->institute_id);
                        $locationArrayTemp = $locations['locationArrayTemp'];
                        $optionalArgs = $locations['optionalArgs'];
                        $onlyOneCityFlag = $locations['onlyOneCityFlag'];
                        $optionalArgs['institute'] = $row->institute_name;
                        $courseArrayTemp =  $this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs);
                        $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                        $logo_link =  $this->getInstituteLogo($row->logo_link);
                        $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                        array_push($instituteArray,array(
                                    array(
                                        'title'=>array($row->institute_name,'string'),
                                        'id'=>array($row->institute_id,'string'),
                                        'logo_link'=>array($logo_link,'string'),
                                        'url'=>array($url,'string'),
                                        'isSendQuery'=>array($isSendQuery,'string'),
                                        'locationArr'=>array($locationArrayTemp,'struct'),
                                        'typeofinsti'=>array($row->typeofinsti,'string'),
                                        'mediadata'=>array($media,'struct'),
                                        'courseArr'=>array($courseArrayTemp,'struct')
                                        ),'struct')
                                );//close array_push
                        if(strlen($instituteIds)>0){
                            $instituteIds .= ' , ';
                        }
                        $instituteIds .= "$row->institute_id";
                    }

                    // error_log("puneet $totalRows");
                    if($freeInstitutes != 0)
                    {
                        if($count > $resultsFetched){
                            //fetch free institutes
                            $mainListingCount = $count - $resultsFetched;
                            if(($resultsFetched == 0) && ($start >= $cmsRows)){
                                $mainListingStart = $start - $totalRows;
                            }
                            else{
                                $mainListingStart = 0;
                            }
                            if($origPageKey == 1){
                                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type,'free' as typeofinsti from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?) and tSearchSnippetStatTemp.type='institute' and tSearchSnippetStatTemp.listingId = institute.institute_id  and institute.institute_id not in ($notTheseInstitutes)   and pack_type != 1 and pack_type !=2 and listings_main.version =  institute.version and listings_main.version =  institute_location_table.version group by institute.institute_id order by tSearchSnippetStatTemp.count  DESC LIMIT $mainListingStart, $mainListingCount ";
                            }else{
                                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link , listings_main.pack_type as pack_type,'free' as typeofinsti from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and  category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?)   and institute.institute_id not in ($notTheseInstitutes) and pack_type != 1 and pack_type !=2 and listings_main.version = institute.version and listings_main.version = institute_location_table.version group by institute.institute_id order by listings_main.viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
                            }
                            // error_log_shiksha("LISTING:MAIN QUERY:".$queryCmd);
                            // error_log("puneet LISTING:MAIN QUERY2:".$queryCmd);
                            $instIds = $instituteIds;
                            if(!is_array($instIds)){
                                $instIds = explode(',', $instituteIds);   
                            }
                            $query = $dbHandle->query($queryCmd,array($catIdArr,$instIds));
                            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                            $queryTotal = $dbHandle->query($queryCmdTotal);
                            foreach ($queryTotal->result() as $rowTotal) {
                                $totalRows += $rowTotal->totalRows;
                                // error_log_shiksha("LISTING::total rows:".$totalRows);
                            }
                            foreach ($query->result() as $row){
                                $resultsFetched += 1;
                                $optionalArgs = array();
                                $locations = $this->getLocationsInOrder($dbHandle,$row->institute_id, $addCountryClause, $newAddCityClause, $cityId);
                                $locationArrayTemp = $locations['locationArrayTemp'];
                                $optionalArgs = $locations['optionalArgs'];
                                $onlyOneCityFlag = $locations['onlyOneCityFlag'];
                                $optionalArgs['institute'] = $row->institute_name;
                                $courseArrayTemp =  $this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs);
                                $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                                $media = $this->getMediaData($dbHandle,$row->institute_id);
                                $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                                $logo_link =  $this->getInstituteLogo($row->logo_link);

                                array_push($instituteArray,array(
                                            array(
                                                'title'=>array($row->institute_name,'string'),
                                                'id'=>array($row->institute_id,'string'),
                                                'logo_link'=>array($logo_link,'string'),
                                                'url'=>array($url,'string'),
                                                'isSendQuery'=>array($isSendQuery,'string'),
                                                'locationArr'=>array($locationArrayTemp,'struct'),
                                                'mediadata'=>array($media,'struct'),
                                                'typeofinsti'=>array($row->typeofinsti,'string'),
                                                'courseArr'=>array($courseArrayTemp,'struct')
                                                ),'struct')
                                        );//close array_push
                                if(strlen($instituteIds)>0){
                                    $instituteIds .= ' , ';
                                }
                                $instituteIds .= "$row->institute_id";
                            }
                        }
                        else{
                            if($origPageKey == 1){
                                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?) and tSearchSnippetStatTemp.type='institute' and tSearchSnippetStatTemp.listingId = institute.institute_id   and pack_type != 1 and pack_type !=2 and listings_main.version =  institute.version and listings_main.version =  institute_location_table.version group by institute.institute_id ";
                            }else{
                                $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?)  and pack_type != 1 and pack_type !=2 and listings_main.version  = institute.version and listings_main.version = institute_location_table.version  group by institute.institute_id ";
                            }
                            // error_log_shiksha("LISTING: TO CALC:".$queryCmd);
                            // error_log("LISTING: puneet TO CALC:".$queryCmd);
                            $instIds = $instituteIds;
                            if(!is_array($instIds)){
                                $instIds = explode(',', $instituteIds);   
                            }
                            $query = $dbHandle->query($queryCmd,array($catIdArr,$instIds));

                            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                            $queryTotal = $dbHandle->query($queryCmdTotal);
                            foreach ($queryTotal->result() as $rowTotal) {
                                $totalRows += $rowTotal->totalRows;
                                // error_log("puneet $totalRows");
                                // error_log_shiksha("LISTING::total rows:".$totalRows);
                            }
                        }
                    }

                    //Worst Case scenario fetching institutes from state - relaxing city filter - is done to reduce bounce rate from category pages after city overlay push
                    if($stateInstitutes != 0 && $count > $resultsFetched && $totalRows <= $resultsFetched){
                        $mainListingCount = $count - $resultsFetched;
                        $mainListingStart = 0;
                        $stateId = $this->getStateForCity($dbHandle,$cityId);
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count, institute.institute_id,institute.institute_name,institute.logo_link, listings_main.pack_type as pack_type from institute, listing_category_table,listings_main, institute_location_table, countryCityTable  where listing_category_table.listing_type = 'institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  and institute_location_table.city_id = countryCityTable.city_id and countryCityTable.state_id != -1 and countryCityTable.state_id = $stateId and institute.institute_id not in (?) and listings_main.version = institute.version and listings_main.version = institute_location_table.version group by institute.institute_id order by listings_main.pack_type desc , viewCount  DESC LIMIT $mainListingStart, $mainListingCount ";
                        // error_log_shiksha("LISTING: MAIN EXTENDED QUERY:".$queryCmd);
                        // error_log("puneet LISTING:MAIN EXTENDED QUERY:".$queryCmd);
                        $instIds = $instituteIds;
                        if(!is_array($instIds)){
                            $instIds = explode(',', $instituteIds);   
                        }
                        $query = $dbHandle->query($queryCmd,array($catIdArr,$instIds));
                        foreach ($query->result() as $row){
                            $resultsFetched += 1;
                            $optionalArgs = array();
                            $locQueryCmd = 'select * from institute_location_table where institute_id= ? order by institute_location_id asc ';
                            $queryTemp = $dbHandle->query($locQueryCmd,array($row->institute_id));
                            $locationArrayTemp = array();
                            $l = 0;
                            foreach ($queryTemp->result() as $rowTemp) {
                                array_push($locationArrayTemp,array(
                                            array(
                                                'city_id'=>array($rowTemp->city_id,'string'),
                                                'country_id'=>array($rowTemp->country_id,'string'),
                                                'city_name'=>array($cacheLib->get("city_".$rowTemp->city_id),'string'),
                                                'country_name'=>array($cacheLib->get("country_".$rowTemp->country_id),'string'),
                                                'address'=>array(htmlspecialchars($rowTemp->address),'string')
                                                ),'struct')
                                        );//close array_push
                                $optionalArgs['location'][$l]  = $cacheLib->get("city_".$rowTemp->city_id)."-".$cacheLib->get("country_".$rowTemp->country_id);
                                $l++;
                            }
                            $optionalArgs['institute'] = $row->institute_name;
                            $courseArrayTemp =  $this->getRelevantCourse($dbHandle,$row->institute_id,$categoryId, $optionalArgs);
                            $isSendQuery =  $this->getSendQueryFlag($row->pack_type);
                            $logo_link =  $this->getInstituteLogo($row->logo_link);
                            $media = $this->getMediaData($dbHandle,$row->institute_id);
                            $url  = getSeoUrl($row->institute_id,"institute",$row->institute_name,$optionalArgs);
                            array_push($instituteArray,array(
                                        array(
                                            'title'=>array($row->institute_name,'string'),
                                            'id'=>array($row->institute_id,'string'),
                                            'logo_link'=>array($logo_link,'string'),
                                            'url'=>array($url,'string'),
                                            'isSendQuery'=>array($isSendQuery,'string'),
                                            'locationArr'=>array($locationArrayTemp,'struct'),
                                            'mediadata'=>array($media,'struct'),
                                            'courseArr'=>array($courseArrayTemp,'struct')
                                            ),'struct')
                                    );//close array_push
                        }
                    }
                }
                else{
                    if($origPageKey == 1){
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table , tSearchSnippetStatTemp, virtualCityMapping  where listing_category_table.listing_type = 'institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?) and tSearchSnippetStatTemp.type='institute' and tSearchSnippetStatTemp.listingId = institute.institute_id and listings_main.version = institute.version and listings_main.version = institute_location_table.version group by institute.institute_id ";
                    }else{
                        $queryCmd =  " select  SQL_CALC_FOUND_ROWS (count(*)-1) count from institute, listing_category_table,listings_main, institute_location_table, virtualCityMapping where listing_category_table.listing_type='institute' and category_id in (?) and listing_category_table.listing_type_id=institute.institute_id  and listings_main.status='live' and listings_main.listing_type = 'institute' and listings_main.listing_type_id = institute.institute_id  and institute_location_table.institute_id = institute.institute_id $addCountryClause  $newAddCityClause and institute.institute_id not in (?) and listings_main.version = institute.version and listings_main.version  =  institute_location_table.version group by institute.institute_id ";
                    }
                    // error_log_shiksha("LISTING: TO CALC:".$queryCmd);
                    // error_log("LISTING:puneet11 TO CALC:".$queryCmd);
                    $instIds = $instituteIds;
                    if(!is_array($instIds)){
                        $instIds = explode(',', $instituteIds);   
                    }
                    $query = $dbHandle->query($queryCmd,array($catIdArr,$instIds));

                    $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                    $queryTotal = $dbHandle->query($queryCmdTotal);
                    $totalRows = 0;
                    foreach ($queryTotal->result() as $rowTotal) {
                        $totalRows = $rowTotal->totalRows;
                        // error_log_shiksha("LISTING::total rows:".$totalRows);
                    }
                    $totalRows += $cmsRows;
                }

                $queryCmd2 = "select logourl from categoryselector where pagename = 'country' and now() between startdate and enddate and countryid in (?) order by sno desc limit 1";
                // error_log($queryCmd2.'ROW345');
                $logourl = '';
                if(!is_array($countryId)){
                    $countryId = explode(',', $countryId);   
                }
                $query = $dbHandle->query($queryCmd2,array($countryId));
                // error_log($dbHandle->affected_rows().'AFFECTEDROWS');
                if($dbHandle->affected_rows() > 0)
                {
                    $row = $query->row();
                    // error_log(print_r($row,true).'ROW345');
                    $logourl = $row->logourl;
                    // error_log($logourl.'LOGOURL');
                }
                $response = array();
                array_push($response,array(
                            array(
                                'total'=>array($totalRows,'string'),
                                'logourl'=>array($logourl,'string'),
                                'institutes'=>array($instituteArray,'struct')
                                ),'struct'));//close array_push
                $response = array($response,'struct');
                // error_log('LISTINGROWRESPONSE'.print_r($response,true));
                return $response;
}

    function getSendQueryFlag($pack_type){
        if($pack_type > 0 && $pack_type !=7){
            $isSendQuery = 1;
        }else{
            $isSendQuery = 0;
        }
        return $isSendQuery;
    }

    function getScholarshipsForHomePageS($dbHandle,$parameters){

        $cacheLib = $this->load->library('cacheLib');
        
        // error_log_shiksha("LISTING:".print_r($parameters,true));
        $appId=$parameters['0'];
        $askedCategoryId = $parameters['1'];
        $categoryId=$this->getChildIds($dbHandle,$parameters['1']);
        $countryId=$parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        $pageKey=$parameters['5'];
        $relaxFlag =$parameters['6'];
        $cityId =$parameters['7'];
        $queryParams=array();
        if(!is_array($categoryId)){
            $categoryId = explode(',', $categoryId);   
        } 
        $queryParams[] = $categoryId;

        if($countryId == 1 || $countryId =='')
        {
            $countryWhere = '';
        }
        else{
            $countryWhere = " and scholarship.country_id in (?)";
            if(!is_array($countryId)){
                $countryId = explode(',', $countryId);   
            } 
            $queryParams[] = $countryId;
        }

        if(isset($cityId) &&  $cityId !='' && $cityId !=1)
        {
            $addCityClause  = ' and scholarship.city_id in (?) ';
            if(!is_array($cityId)){
                $cityId = explode(',', $cityId);   
            } 
            $queryParams[] = $cityId;
        }
        else{
            $addCityClause  = '  ';
        }

        $dbHandle = $this->getDbHandle();
        if(!is_array($pageKey)){
            $pageKey = explode(',', $pageKey);   
        } 
        $queryParams[] = $pageKey;

//        $countryWhere = '';
        $queryCmd = "select  SQL_CALC_FOUND_ROWS (count(*)-1) count ,scholarship.scholarship_id,scholarship.scholarship_name, scholarship.levels , scholarship.value,  scholarship.city_id, scholarship.country_id, scholarship.address_line1, scholarship.address_line2 from PageScholDb,scholarship, scholarship_category_table, listings_main where  category_id in (?) and PageScholDb.ScholId = scholarship.scholarship_id and scholarship_category_table.scholarship_id =scholarship.scholarship_id $countryWhere $addCityClause and PageScholDb.KeyId in (?) and CURDATE() >= PageScholDb.StartDate and CURDATE() <= PageScholDb.EndDate and listings_main.listing_type='scholarship' and listing_type_id=scholarship.scholarship_id  and listings_main.status='live'  group by scholarship.scholarship_id order by listings_main.viewCount DESC";

        // error_log_shiksha("LISTING: CMS QUERY:".$queryCmd);
        $query = $dbHandle->query($queryCmd,$queryParams);
        //what are CMS Rows
        $cmsRows=$query->num_rows();
        //init the response array

        $scholarshipArray = array();
        $scholarshipIds = ' -1 ';
        $end = $start + $count;
        $resultsFetched = 0;
        $counter = 0;

        if($cmsRows > $start) {
            foreach ($query->result() as $row){
                if(strlen($scholarshipIds)>0){
                    $scholarshipIds .= ' , ';
                }
                $scholarshipIds .= "$row->scholarship_id";

                if($counter >=$start && $counter < $end){
                    $queryCmdElg = 'select * from scholarship_eligibility_table where scholarship_id= ? ';
                    $queryTemp = $dbHandle->query($queryCmdElg,array($row->scholarship_id));
                    $elgArrayTemp = array();
                    foreach ($queryTemp->result() as $rowTemp) {
                        array_push($elgArrayTemp,array(
                                    array(
                                        'criteria'=>array($rowTemp->eligibility_criteria,'string'),
                                        'value'=>array($rowTemp->eligibility_criteria_values,'string')
                                        ),'struct')
                                );//close array_push
                    }


                    $url = getSeoUrl($row->scholarship_id,"scholarship",$row->scholarship_name,$optionalArgs);
                    $optionalArgs =array();
                    array_push($scholarshipArray,array(
                                array(
                                    'title'=>array($row->scholarship_name,'string'),
                                    'id'=>array($row->scholarship_id,'string'),
                                    'applicableTo'=>array($row->levels,'string'),
                                    'value'=>array($row->value,'string'),
                                    'url'=>array($url,'string'),
                                    'city_id'=>array($row->city_id,'string'),
                                    'country_id'=>array($row->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$row->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$row->country_id),'string'),
                                    'isSponsored'=>array('true','string'),
                                    'address'=>array(htmlspecialchars($row->address1),'string'),
                                    'eligibility'=>array($elgArrayTemp,'struct')

                                    ),'struct')
                            );//close array_push

                    $resultsFetched += 1;
                }
                $counter++;
            }
        }
        else{
            foreach ($query->result() as $row){
                if(strlen($scholarshipIds)>0){
                    $scholarshipIds .= ' , ';
                }
                $scholarshipIds .= "$row->scholarship_id";
            }
        }

        if($count > $resultsFetched){
            $mainListingCount = $count - $resultsFetched;
            if(($resultsFetched == 0) && ($start >= $cmsRows)){
                $mainListingStart = $start - $cmsRows;
            }
            else{
                $mainListingStart = 0;
            }

            $queryCmd = "select  SQL_CALC_FOUND_ROWS (count(*)-1) count, scholarship.scholarship_id,scholarship.scholarship_name, scholarship.levels,scholarship.value, scholarship.city_id, scholarship.country_id,  scholarship.address_line1, scholarship.address_line2  from scholarship, scholarship_category_table,listings_main  where category_id in (?) and scholarship_category_table.scholarship_id=scholarship.scholarship_id  $countryWhere  $addCityClause and listings_main.status='live' and listings_main.listing_type = 'scholarship' and listings_main.listing_type_id = scholarship.scholarship_id and scholarship.scholarship_id not in (".$scholarshipIds.") group by scholarship.scholarship_id order by listings_main.pack_type desc ,listings_main.viewCount DESC LIMIT $mainListingStart, $mainListingCount ";
            // error_log_shiksha("LISTING: MAIN QUERY:".$queryCmd);
            $query = $dbHandle->query($queryCmd,$queryParams);

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }

            $totalRows += $cmsRows;
            foreach ($query->result() as $row){
                $queryCmd = 'select * from scholarship_eligibility_table where scholarship_id= ? ';
                $queryTemp = $dbHandle->query($queryCmd,array($row->scholarship_id));
                $elgArrayTemp = array();
                foreach ($queryTemp->result() as $rowTemp) {
                    array_push($elgArrayTemp,array(
                                array(
                                    'criteria'=>array($rowTemp->eligibility_criteria,'string'),
                                    'value'=>array($rowTemp->eligibility_criteria_values,'string')
                                    ),'struct')
                            );//close array_push
                }
                $url = getSeoUrl($row->scholarship_id,"scholarship",$row->scholarship_name,$optionalArgs);
                array_push($scholarshipArray,array(
                            array(
                                'title'=>array($row->scholarship_name,'string'),
                                'id'=>array($row->scholarship_id,'string'),
                                'applicableTo'=>array($row->levels,'string'),
                                'value'=>array($row->value,'string'),
                                'url'=>array($url,'string'),
                                'city_id'=>array($row->city_id,'string'),
                                'country_id'=>array($row->country_id,'string'),
                                'city_name'=>array($cacheLib->get("city_".$row->city_id),'string'),
                                'country_name'=>array($cacheLib->get("country_".$row->country_id),'string'),
                                'address'=>array(htmlspecialchars($row->address1),'string'),
                                'eligibility'=>array($elgArrayTemp,'struct')

                                ),'struct')
                        );//close array_push
                $resultsFetched++;
                if(strlen($scholarshipIds)>0){
                    $scholarshipIds .= ' , ';
                }
                $scholarshipIds .= "$row->scholarship_id";

            }
            if(($count > $resultsFetched) && $relaxFlag == 1){
                $newCategoryId=$this->getAllChildIdsOfParent($dbHandle,$askedCategoryId);
                if(!is_array($newCategoryId)){
                    $newCategoryId = explode(',', $newCategoryId);   
                } 
                $queryParams[0] = $newCategoryId;
                $queryCmd = "select  SQL_CALC_FOUND_ROWS (count(*)-1) count ,scholarship.scholarship_id,scholarship.scholarship_name, scholarship.levels , scholarship.value,  scholarship.city_id, scholarship.country_id, scholarship.address_line1, scholarship.address_line2 from PageScholDb,scholarship, scholarship_category_table, listings_main where  category_id in (?) and PageScholDb.ScholId = scholarship.scholarship_id and scholarship_category_table.scholarship_id =scholarship.scholarship_id $countryWhere   $addCityClause and CURDATE() >= PageScholDb.StartDate and CURDATE() <= PageScholDb.EndDate and listings_main.listing_type='scholarship' and listing_type_id=scholarship.scholarship_id  and listings_main.status='live' and scholarship.scholarship_id not in  (".$scholarshipIds.")  group by scholarship.scholarship_id order by listings_main.viewCount DESC";
                // error_log_shiksha("LISTING: CMS extended relaxflag QUERY:".$queryCmd);
                $query = $dbHandle->query($queryCmd,$queryParams);

                foreach ($query->result() as $row){
                    $queryCmd = 'select * from scholarship_eligibility_table where scholarship_id= ? ';
                    $queryTemp = $dbHandle->query($queryCmd,array($row->scholarship_id));
                    $elgArrayTemp = array();
                    foreach ($queryTemp->result() as $rowTemp) {
                        array_push($elgArrayTemp,array(
                                    array(
                                        'criteria'=>array($rowTemp->eligibility_criteria,'string'),
                                        'value'=>array($rowTemp->eligibility_criteria_values,'string')
                                        ),'struct')
                                );//close array_push
                    }
                    $url = getSeoUrl($row->scholarship_id,"scholarship",$row->scholarship_name,$optionalArgs);
                    array_push($scholarshipArray,array(
                                array(
                                    'title'=>array($row->scholarship_name,'string'),
                                    'id'=>array($row->scholarship_id,'string'),
                                    'applicableTo'=>array($row->levels,'string'),
                                    'value'=>array($row->value,'string'),
                                    'url'=>array($url,'string'),
                                    'city_id'=>array($row->city_id,'string'),
                                    'country_id'=>array($row->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$row->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$row->country_id),'string'),
                                    'isSponsored'=>array('true','string'),
                                    'address'=>array(htmlspecialchars($row->address1),'string'),
                                    'eligibility'=>array($elgArrayTemp,'struct')

                                    ),'struct')
                            );//close array_push
                    $resultsFetched++;
                    if(strlen($scholarshipIds)>0){
                        $scholarshipIds .= ' , ';
                    }
                    $scholarshipIds .= "$row->scholarship_id";

                }
                if($count > $resultsFetched){
                    $mainListingCount = $count - $resultsFetched;
                    if(($resultsFetched == 0) && ($start >= $cmsRows)){
                        $mainListingStart = $start - $cmsRows;
                    }
                    else{
                        $mainListingStart = 0;
                    }

                    $queryCmd = "select  SQL_CALC_FOUND_ROWS (count(*)-1) count, scholarship.scholarship_id,scholarship.scholarship_name, scholarship.levels,scholarship.value, scholarship.city_id, scholarship.country_id,  scholarship.address_line1, scholarship.address_line2  from scholarship, scholarship_category_table,listings_main  where category_id in (?) and scholarship_category_table.scholarship_id=scholarship.scholarship_id  $countryWhere  $addCityClause and listings_main.status='live' and listings_main.listing_type = 'scholarship' and listings_main.listing_type_id = scholarship.scholarship_id and scholarship.scholarship_id not in (".$scholarshipIds.") group by scholarship.scholarship_id order by listings_main.pack_type desc ,listings_main.viewCount DESC LIMIT $mainListingStart, $mainListingCount ";
                // error_log_shiksha("LISTING: MAIN QUERY:".$queryCmd);
                $query = $dbHandle->query($queryCmd,$queryParams);

                foreach ($query->result() as $row){
                    $queryCmd = 'select * from scholarship_eligibility_table where scholarship_id= ? ';
                    $queryTemp = $dbHandle->query($queryCmd,array($row->scholarship_id));
                    $elgArrayTemp = array();
                    foreach ($queryTemp->result() as $rowTemp) {
                        array_push($elgArrayTemp,array(
                                    array(
                                        'criteria'=>array($rowTemp->eligibility_criteria,'string'),
                                        'value'=>array($rowTemp->eligibility_criteria_values,'string')
                                        ),'struct')
                                );//close array_push
                    }
                    $url = getSeoUrl($row->scholarship_id,"scholarship",$row->scholarship_name,$optionalArgs);
                    array_push($scholarshipArray,array(
                                array(
                                    'title'=>array($row->scholarship_name,'string'),
                                    'id'=>array($row->scholarship_id,'string'),
                                    'applicableTo'=>array($row->levels,'string'),
                                    'value'=>array($row->value,'string'),
                                    'url'=>array($url,'string'),
                                    'city_id'=>array($row->city_id,'string'),
                                    'country_id'=>array($row->country_id,'string'),
                                    'city_name'=>array($cacheLib->get("city_".$row->city_id),'string'),
                                    'country_name'=>array($cacheLib->get("country_".$row->country_id),'string'),
                                    'address'=>array(htmlspecialchars($row->address1),'string'),
                                    'eligibility'=>array($elgArrayTemp,'struct')

                                    ),'struct')
                            );//close array_push
                    $resultsFetched++;
                        if(strlen($scholarshipIds)>0){
                            $scholarshipIds .= ' , ';
                        }
                        $scholarshipIds .= "$row->scholarship_id";

                    }


                }
            }
        }
        else{
            $queryCmd = "select  SQL_CALC_FOUND_ROWS (count(*)-1) count  from scholarship, scholarship_category_table,listings_main  where category_id in (?) and scholarship_category_table.scholarship_id=scholarship.scholarship_id  $countryWhere  $addCityClause and listings_main.listing_type = 'scholarship' and listings_main.listing_type_id = scholarship.scholarship_id group by scholarship.scholarship_id ";
            // error_log_shiksha("LISTING: TO CALC:".$queryCmd);
            $query = $dbHandle->query($queryCmd,$queryParams);
            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            $totalRows = 0;
            foreach ($queryTotal->result() as $rowTotal) {
                $totalRows = $rowTotal->totalRows;
                // error_log_shiksha("LISTING::total rows:".$totalRows);
            }
        }

        $finalResultArray = array();
        array_push($finalResultArray,array(
                    array(
                        'total'=>array($totalRows,'string'),
                        'scholarhips'=>array($scholarshipArray,'struct')
                        ),'struct'));//close array_push
        $response = array($finalResultArray,'struct');
        return $response;
    }

    function getKeyPageId($dbHandle,$testprepCat,$countryId=1,$cityId=1,$categoryId=1)
    {
        $keyId=0;
        $flag='shiksha';
        if($testprepCat!=0)
        {
            $flag='testprep';
        }
        if($cityId == 1){
            $cityId = 0;
        }

        if($categoryId == 1||!$categoryId){
            $categoryId = 0;
        }

        //connect DB
        if($dbHandle == ''){
            error_log('error can not get db handle');
        }
        if(!$categoryId){
            $categoryId = 0;
        }

        if($countryId == "")
           $countryId = 1;   

        if(!is_array($countryId)){
            $countryId = explode(',', $countryId);   
        }                                                  

        $queryCmd="select group_concat(tPageKeyCriteriaMapping.keyPageId)  as keyPageId from tPageKeyCriteriaMapping where countryId in(?) and cityId = ? and categoryId= ? and tPageKeyCriteriaMapping.flag= ? ";
        // error_log("\nAmit ".print_r($queryCmd,true),3,'/home/infoedge/Desktop/log.txt');

        $query=$dbHandle->query($queryCmd,array($countryId,$cityId,$categoryId,$flag));

        if(count($query->result_array())>0)
        {
            foreach($query->result_array() as $row){
                $keyId = $row['keyPageId'];
            }
        }
        if(strlen($keyId) > 0){
            return $keyId;
        }
        else{
            if($flag=='shiksha'){
                $queryCmd="select group_concat(tPageKeyCriteriaMapping.keyPageId)  as keyPageId from tPageKeyCriteriaMapping, categoryBoardTable  where countryId in (?) and cityId = ? and parentId=categoryId and tPageKeyCriteriaMapping.flag='shiksha' and  categoryBoardTable.boardId = ? and parentId > 1 ";
                // error_log_shiksha($queryCmd);

                $query=$dbHandle->query($queryCmd,array($countryId,$cityId,$categoryId));

                if(count($query->result_array())>0)
                {
                    foreach($query->result_array() as $row){
                        $keyId = $row['keyPageId'];
                    }
                }
                if(strlen($keyId) > 0){
                    return $keyId;
                }
                else{
                    return 0;
                }
            }
            else{
                return 0;
            }
        }
    }

    function incrementCountOfSubscription($deleteStatus){
        $response = unserialize(base64_decode($deleteStatus));
        if($response['status'] > 0){
            $subScriptionArr = array();
            for($i=0; $i<count($response['info']); $i++){
                if($response['info'][$i]['subscriptionId'] > 0){
                    $subScriptionArr[$response['info'][$i]['subscriptionId']]++;
                }
            }

            $this->load->library('Subscription_client');
            $subsObj = new Subscription_client();
            foreach($subScriptionArr as $subscriptionId=>$increment){
                //call shashwat's ws
                $data['subscriptionId']=$subscriptionId;
                $data['count']=$increment;
                // error_log("puneetrocks huuhuhu ".print_r($data,true));
                $features = $subsObj->incrementPseudoBaseQuantForSubscription($appId,$data);
                // error_log("puneetrocks".print_r($features,true));
            }
        }
        else{
            return "Some Issues while deleting draft";
        }
    }

function cmsremoveshoshkele($dbHandle,$bannerid,$tablename)
{
    if($tablename == "tbanners")
    {
        $columnname = "bannerid";
        $sql = "SELECT group_concat(bannerlinkid) as bannerlinkids FROM `tbannerlinks` WHERE `bannerid`= ? and `status`='live'";
        $query=$dbHandle->query($sql,array($bannerid));
        $numrows = $query->num_rows();
        if($numrows > 0)
        {
            $row = $query->row();
            $bannerlinkids = $row->bannerlinkids;
        }
        if(!$bannerlinkids)
        {
           $bannerlinkids = "0";
        }
        $columnname2 = "bannerlinkid";
        $data = array(
            'status'=>'deleted'
        );
        $dbHandle->where_in($columnname2, $bannerlinkids);
        $dbHandle->update('tbannerlinks',$data);
        
    }
    if($tablename == "tbannerlinks")
    {
        $columnname = "bannerlinkid";
        $columnname2 = "bannerlinkid";
        $bannerlinkids = $bannerid;
    }
    if($tablename == "tlistingsubscription")
    {
        $columnname = "listingsubsid";
        $columnname2 = "listingsubsid";
        $bannerlinkids = $bannerid;
    }
    $data = array(
            'status'=>'deleted'
    );
    $dbHandle->where($columnname, $bannerid);
    $dbHandle->update($tablename,$data);
    $data = array(
        'status'=>'decoupled'
    );
    $tablename = 'tcoupling';
    $dbHandle->where_in($columnname2, $bannerlinkids);
    $dbHandle->update($tablename,$data);
    error_log("AMITS".$dbHandle->last_query());
    return 'success';
}

function cmsgetlistingdetails($dbHandle,$listingid)
{
$listingid = trim($listingid);
if(empty($listingid) || intval($listingid) <= 0) {
	return 0;
}
//    $sql = "select username,group_concat(category_id) as categoryids,country_id,city_id from listings_main a inner join listing_category_table b on a.listing_type_id = b.listing_type_id inner join institute_location_table c on c.institute_id = b.listing_type_id where a.listing_type = 'institute' and b.listing_type = 'institute' and a.status = 'live' and b.status = 'live' and c.status = 'live' and c.institute_id = $listingid group by username";
    $arr = array();
    $sql = "select username,group_concat(distinct d.parentid) as categoryids,group_concat(distinct e.boardId) as subcategoryids
    from listings_main a
    inner join listing_category_table b
    on a.listing_type_id = b.listing_type_id
    inner join categoryBoardTable d
    on d.boardId = b.category_id
    inner join categoryBoardTable e
    on e.boardId = b.category_id
    where a.listing_type = 'institute'
    and b.listing_type = 'institute'
    and a.status = 'live'
    and b.status = 'live'
    and a.listing_type_id = ? group by a.username";

    // error_log($sql);
    $query=$dbHandle->query($sql,array($listingid));

    $numrows = $query->num_rows();
    if($numrows > 0)
    {
        $row = $query->row();
        $arr['username']= $row->username;
        $arr['categoryids'] = $row->categoryids;
        $arr['subcategoryids'] = $row->subcategoryids;
    }
    else
    {
        $arr['username']= '';
        $arr['categoryids'] = '';
        $arr['subcategoryids'] = '';
    }

    $sql = "select group_concat(distinct bp.acronym) as blogids
    from listings_main l
    join course_details c
    on l.listing_type_id = c.institute_id
    join listingExamMap le
    on c.course_id = le.typeId
    join blogTable b
    ON le.examId = b.blogId
    join blogTable bp
    ON b.parentId = bp.blogId
    where l.listing_type_id= ?
    and l.listing_type='institute'
    and le.typeOfMap='testprep'
    and le.type='course'
    and c.status='live'
    and le.status='live'
    and bp.status='live'
    and b.status='live'
    and l.status='live'";
    // error_log($sql);

    $query=$dbHandle->query($sql,array($listingid));
    if($query->num_rows() > 0)
    {
        $row = $query->row();
        $arr['blogids'] = $row->blogids;
        if ($arr['blogids'] == NULL) $arr['blogids'] = '';
    }
    else
    {
        $arr['blogids']= '';
    }

    $sql = "select country_id,
    if(b.virtualCityId is NULL,c.city_id,concat(c.city_id,',',b.virtualCityId)) as city_id,d.state_id as state_id
    from
    institute_location_table c
    left join virtualCityMapping b on c.city_id = b.city_id and b.city_id <> b.virtualCityId
    left join countryCityTable d on d.city_id = c.city_id
    where
    c.status = 'live' and c.institute_id = ?";

    // error_log($sql);
    $query=$dbHandle->query($sql,array($listingid));
    if($query->num_rows() > 0)
    {
        foreach($query->result_array() as $row) {
        	$temp['country_id'][]= $row['country_id'];
        	$temp['city_id'][] = $row['city_id'];
        	$temp['state_id'][] = $row['state_id'];
        }
        $arr['country_id'] = implode(',',array_unique($temp['country_id']));
        $arr['city_id'] = implode(',',array_unique($temp['city_id']));
        $arr['state_id'] = implode(',',array_unique($temp['state_id']));
    }
    else
    {
        $arr['country_id']= '';
        $arr['city_id'] = '';
        $arr['state_id'] = '';

    }
        $sql = "select username from listings_main where listing_type_id = ? and listing_type = 'institute' and status <> 'live'";
    // error_log($sql);
    $query=$dbHandle->query($sql,array($listingid));
    if($query->num_rows() > 0 && $numrows <= 0)
    {
        $arr['username']= 0;
    }

    // error_log(print_r($arr,true).'ROW');
    return $arr;
}

    /************ TEST PREP METHODS *************************/

    public function get_testprep_free_listing_ids($blog_id, $city_id = -1, $course_type = 'All')
    {
        $blog_ids = array();
        if($this->is_top_level($blog_id)){
            $child_blogs = $this->child_blogs($blog_id);
            // error_log("pointer33 :: ".print_r($child_blogs, true));
            foreach($child_blogs as $blog){
                array_push($blog_ids, $blog['blogId']);
}
        }
        else{
            array_push($blog_ids, $blog_id);
        }

        $city_clause = "";
        if ($city_id != -1) {
            $city_clause = " AND v.virtualCityId=$city_id ";
        }

        $course_type_clause = "";
        if($course_type !='All'){
            $course_type_clause = " AND c.course_type = '$course_type' ";
        }

        $results = $this->run_query("select l.listing_type_id as institute_id, 'free' as type, cc.city_name as city_name,
            GROUP_CONCAT(distinct c.course_id order by lm.viewCount desc) as course_ids, l.viewCount as view_count
            from listings_main l
            inner join institute i
            on l.listing_type_id = i.institute_id
            inner join institute_location_table it
            on i.institute_id = it.institute_id
            inner join course_details c
            on i.institute_id = c.institute_id
            inner join listingExamMap le
            on c.course_id = le.typeId
            inner join countryCityTable cc
            on it.city_id = cc.city_id
            inner join virtualCityMapping v
            on cc.city_id = v.city_id
            left join listings_main lm
            on c.course_id = lm.listing_type_id
            where le.examId in (".implode(",",$blog_ids).")
            and le.typeOfMap = 'testprep'
            and lm.listing_type = 'course'
            and lm.status='live'
            and l.listing_type='institute'
            ".$city_clause.$course_type_clause."
            and l.status = 'live'
            and c.status = 'live'
            and i.status = 'live'
            and it.status = 'live'
            and le.status = 'live'
            and l.pack_type in (-10,-5,0,7)
            group by l.listing_type_id
            order by l.listing_type_id");
        return $results;
    }

    public function get_testprep_paid_listing_ids($blog_id, $city_id = -1, $course_type = 'All')
    {
        $blog_ids = array();
        if($this->is_top_level($blog_id)){
            $child_blogs = $this->child_blogs($blog_id);
            foreach($child_blogs as $blog){
                array_push($blog_ids, $blog['blogId']);
            }
        }
        else{
            array_push($blog_ids, $blog_id);
        }

        $city_clause = "";
        if ($city_id != -1) {
            $city_clause = " AND v.virtualCityId=$city_id ";
        }

        $course_type_clause = "";
        if($course_type != 'All'){
            $course_type_clause = " AND c.course_type = '$course_type' ";
        }

        $results = $this->run_query("select l.listing_type_id as institute_id, 'paid' as type, cc.city_name as city_name, GROUP_CONCAT(distinct c.course_id order by lm.viewCount desc) as course_ids, l.viewCount as view_count
            from listings_main l
            inner join institute i
            on l.listing_type_id = i.institute_id
            inner join institute_location_table it
            on i.institute_id = it.institute_id
            inner join course_details c
            on i.institute_id = c.institute_id
            inner join listingExamMap le
            on c.course_id = le.typeId
            inner join countryCityTable cc
            on it.city_id = cc.city_id
            inner join virtualCityMapping v
            on cc.city_id = v.city_id
            left join listings_main lm
            on c.course_id = lm.listing_type_id
            where le.examId in (".implode(",",$blog_ids).")
            and le.typeOfMap = 'testprep'
            and l.listing_type='institute'
            ".$city_clause.$course_type_clause."
            and c.status = 'live'
            and lm.status = 'live'
            and lm.listing_type='course'
            and i.status = 'live'
            and it.status = 'live'
            and le.status = 'live'
            and l.status = 'live'
            and l.pack_type in (1,2)
            group by l.listing_type_id
            order by l.listing_type_id");
        return $results;

    }

    public function get_testprep_paid_minus_main_listing_ids($blog_id, $city_id = -1, $course_type = 'All')
    {
        $blog_ids = array();
        if($this->is_top_level($blog_id)){
            $child_blogs = $this->child_blogs($blog_id);
            foreach($child_blogs as $blog){
                array_push($blog_ids, $blog['blogId']);
            }
        }
        else{
            array_push($blog_ids, $blog_id);
        }

        $city_clause = "";
        if ($city_id != -1) {
            $city_clause = " AND v.virtualCityId=$city_id ";
        }

        $course_type_clause = "";
        if($course_type != 'All'){
            $course_type_clause = " AND c.course_type = '$course_type' ";
        }

        $this->load->library('util');
        //$util = new Util();
        $main_listing_ids = $this->util->change_array($this->get_testprep_main_listing_ids($blog_id, $city_id), "institute_id");
        $main_listing_clause = "";
        if(count($main_listing_ids) != 0)
        {
          $main_listing_clause = "and l.listing_type_id not in (".implode($main_listing_ids,',').")";

        }
        $results = $this->run_query("select l.listing_type_id as institute_id, 'paid_minus_main' as type, cc.city_name as city_name, GROUP_CONCAT(distinct c.course_id order by lm.viewCount desc) as course_ids, l.viewCount as view_count
            from listings_main l
            inner join institute i
            on l.listing_type_id = i.institute_id
            inner join institute_location_table it
            on i.institute_id = it.institute_id
            inner join course_details c
            on i.institute_id = c.institute_id
            inner join listingExamMap le
            on c.course_id = le.typeId
            inner join countryCityTable cc
            on it.city_id = cc.city_id
            inner join virtualCityMapping v
            on cc.city_id = v.city_id
            left join listings_main lm
            on c.course_id = lm.listing_type_id
            where le.examId in (".implode(",",$blog_ids).")
            and le.typeOfMap = 'testprep'
            and lm.listing_type = 'course'
            and lm.status='live'
            and it.status = 'live'
            and le.status = 'live'
            and l.listing_type='institute'
            ".$city_clause.$course_type_clause."
            and l.status = 'live'
            and l.pack_type in (1,2)
            ".$main_listing_clause."
            and c.status = 'live'
            and i.status = 'live'
            group by l.listing_type_id
            order by l.listing_type_id");
        return $results;

    }

    function get_testprep_cat_sponser_listing_ids($blog_id, $city_id = -1, $course_type = 'All')
    {
        $this->load->library('util');
        //$util = new Util();
        $city_clause = "";
        if ($city_id != -1) {
            $city_clause = " AND v.virtualCityId=$city_id ";
        }

        $course_type_clause = "";
        if($course_type != 'All'){
            $course_type_clause = " AND c.course_type = '$course_type' ";
        }

        $institute_id_clause = "";
        $parent_blog_id = 0;
        if ($this->is_top_level($blog_id)) {
            $parent_blog_id = $blog_id;
            $results = $this->child_blogs($blog_id);
            // error_log(print_r($results, true));
            $child_blog_ids = $this->util->change_array($results,"blogId");
            // error_log(print_r($child_blog_ids, true));
        } else
        {
           $child_blog_ids = array($blog_id);
           $parent_blog_id = $this->parent_blog_id($blog_id);
        }

        //find all category sponsers
        $listings = $this->run_query("SELECT i.institute_id as institute_id, 'sponsered' as type, cc.city_name as city_name, GROUP_CONCAT(distinct c.course_id order by lm.viewCount desc) as course_ids, l.viewCount as view_count
                FROM tlistingsubscription t
                INNER JOIN virtualCityMapping v
                ON t.cityid = v.city_id
                LEFT JOIN institute i
                ON t.listing_type_id = i.institute_id
                INNER JOIN listings_main l
                ON l.listing_type_id = i.institute_id
                INNER JOIN course_details c
                ON c.institute_id = i.institute_id
                INNER JOIN listingExamMap le
                ON c.course_id = le.typeId
                INNER JOIN institute_location_table ilt
                ON i.institute_id = ilt.institute_id
                INNER JOIN countryCityTable cc
                ON ilt.city_id = cc.city_id
                left join listings_main lm
                on c.course_id = lm.listing_type_id
                WHERE i.status = 'live'
                AND t.pagename='testprep'
                and lm.listing_type = 'course'
                and lm.status='live'
                AND l.status='live'
                and ilt.status = 'live'
                and t.status ='live'
		and t.listing_type = 'institute'
                and le.status = 'live'
                AND t.categoryid=$parent_blog_id
                AND le.typeOfMap = 'testprep'
                AND le.examId in (".implode(",", $child_blog_ids).") ".
                $city_clause.
                $course_type_clause.
                "AND c.status = 'live'
                AND l.listing_type ='institute'
                AND now() between t.startdate and t.enddate
                GROUP BY i.institute_id
                ORDER BY l.listing_type_id");


        $banner_city_clause = "";
        if($city_id != -1){
            $banner_city_clause = "AND tb.cityid = $city_id";
        }

        //find all banners along with institutes coupled with them
        $banners = $this->run_query("SELECT tb.bannerid as banner_id, tbn.bannerurl, tl.listing_type_id as institute_id, tc.status as coupling_status, tl.status as instt_status
            FROM tbannerlinks tb
            LEFT JOIN tcoupling tc
            ON tb.bannerlinkid = tc.bannerlinkid
            LEFT JOIN tlistingsubscription tl
            ON tc.listingsubsid = tl.listingsubsid
            INNER JOIN tbanners tbn
            ON tb.bannerid = tbn.bannerid
            WHERE tb.product = 'testprep'
            AND tb.categoryid = $parent_blog_id "
            .$banner_city_clause."
            AND tb.status = 'live'
            AND (tl.status = 'live' or isnull(tl.status))
            AND tbn.status = 'live'
            AND now() between tb.startdate and tb.enddate
            AND (tc.status='coupled' or isnull(tc.status))
            ");


        return array('listings' => $listings, 'banners' => $banners);
    }

    /**
     * Method to get main institutes corresponding to a given blog id.
     * If the blog_id is a broad category it will return combined main
     * listings of all sub categories.
     *
     * city_id can be an id of a virtual city say Delhi/NCR.
     * To get result for all cities city_id should be -1
     */
    public function get_testprep_main_listing_ids($blog_id, $city_id = -1, $course_type = 'All') {
        $this->load->library('util');
        //$util = new Util();
        $city_clause = "";
        if ($city_id != -1) {
            $city_clause = " AND v.virtualCityId=$city_id ";
        }
        $course_type_clause = "";
        if($course_type != 'All'){
            $course_type_clause = " AND c.course_type = '$course_type' ";
        }

        $institute_id_clause = "";
        $parent_blog_id = 0;
        if ($this->is_top_level($blog_id)) {
            $parent_blog_id = $blog_id;
            $results = $this->child_blogs($blog_id);
            //error_log(print_r($results, true));
            $child_blog_ids = $this->util->change_array($results,"blogId");
            //error_log(print_r($child_blog_ids, true));
        } else
        {
           $child_blog_ids = array($blog_id);
           $parent_blog_id = $this->parent_blog_id($blog_id);
        }

        //find all main institute listings
        $results = $this->run_query("SELECT i.institute_id as institute_id, 'main' as type, cc.city_name as city_name, GROUP_CONCAT(distinct c.course_id order by l.viewCount) as course_ids, l.viewCount as view_count
                FROM tPageKeyCriteriaMapping t
                INNER JOIN virtualCityMapping v
                ON t.cityId = v.city_id
                INNER JOIN PageCollegeDb p
                ON t.keyPageId = p.keyId
                LEFT JOIN institute i
                ON p.listing_type_id = i.institute_id
                INNER JOIN listings_main l
                ON l.listing_type_id = i.institute_id
                INNER JOIN course_details c
                ON c.institute_id = i.institute_id
                INNER JOIN listingExamMap le
                ON c.course_id = le.typeId
                INNER JOIN institute_location_table ilt
                ON i.institute_id = ilt.institute_id
                INNER JOIN countryCityTable cc
                ON ilt.city_id = cc.city_id
                left join listings_main lm
                on c.course_id = lm.listing_type_id
                WHERE i.status = 'live'
                AND t.flag='testprep'
                AND l.status='live'
                AND p.status = 'live'
		AND p.listing_type = 'institute'
                AND le.status = 'live'
                AND ilt.status = 'live'
                and lm.listing_type = 'course'
                and lm.status='live'
                AND t.categoryId=$parent_blog_id
                AND le.typeOfMap = 'testprep'
                AND CURDATE() >= p.StartDate 
                AND CURDATE() <= p.EndDate
                AND le.examId in (".implode(",", $child_blog_ids).") ".
                $city_clause.
                $course_type_clause.
                "AND c.status = 'live'
                AND i.status = 'live'
                GROUP BY i.institute_id
                order by l.listing_type_id");

        return $results;

    }

    public function get_exams_for_blog($blog_id)
    {
        $query_command="SELECT blogId,blogTitle,acronym FROM blogTable WHERE blogType='exam' AND parentId = ? and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        return $query->result_array();
    }

    public function get_institute_name($institute_id)
    {
        $query_command="select institute_name from institute where institute_id = ? and status = 'live'";
        $query = $this->db->query($query_command,array($institute_id));
        $results = $query->result_array();
        return $results[0]['institute_name'];
    }

    public function get_course_name($course_id)
    {
        $query_command="select courseTitle from course_details where course_id = ? and status = 'live'";
        $query = $this->db->query($query_command,array($course_id));
        $results = $query->result_array();
        return $results[0]['courseTitle'];
    }

    private function run_query($query_command){
        // error_log($query_command);
        
        $query = $this->db->query($query_command);
        return $query->result_array();
    }

    public function is_top_level($blog_id)
    {
        // Find out if the blog signifies a top level exam category
        $query_command="select parentId from blogTable where blogId = ? and blogType='exam' and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        $results = $query->result_array();
        $result = $results[0];
        //error_log("pointer66".print_r($result, true));
        return ($result['parentId'] == 0);
    }

    public function child_blogs($blog_id)
    {
        $query_command="select blogId from blogTable where blogType='exam' AND parentId = ?  and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        return $query->result_array();
    }

    public function parent_blog_id($blog_id)
    {
        $query_command="select parentId from blogTable where blogId = ? and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        $results = $query->result_array();
        return $results[0]['parentId'];
    }

    public function get_blog_title($blog_id)
    {
        $query_command="SELECT blogTitle FROM blogTable where blogId = ? and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        $results = $query->result_array();
        return $results[0]['blogTitle'];
    }

    public function get_blog_acronym($blog_id)
    {
        $query_command="SELECT acronym FROM blogTable where blogId = ? and status='live'";
        $query = $this->db->query($query_command,array($blog_id));
        $results = $query->result_array();
        return $results[0]['acronym'];
    }

    function get_online_test_banner($blog_id) {
        $query_command = "SELECT t.bannerurl
        FROM tbannerlinks as tb
        JOIN tbanners t
        ON tb.bannerid = t.bannerid
        WHERE tb.categoryid = ?
        AND tb.status = 'live'
        AND tb.product = 'onlinetest'
        AND tb.countryid = 2
        AND now() between tb.startdate and tb.enddate
        ORDER BY t.bannerid
        ";
        $query = $this->db->query($query_command,array($blog_id));
        $banners = $query->result_array();
        $count = count($banners);
        if($count == 0) return -1;
        $keys = $this->run_query("
        SELECT value
        FROM round_robin_keys
        WHERE rkey='current_online_test'
        ");
        $key = $keys[0]['value'];
        $next = ($key + 1) % $count;
        $this->db->query("
        UPDATE round_robin_keys
        SET value=$next
        WHERE rkey='current_online_test'");
        return $banners[$next]['bannerurl'];
    }

  
 /***********for mobile site establish year and total seats for corresponding institute ***************/ 
   function getEstablishYearAndSeats($dbHandle,$institute_id){
		$queryCmd = "SELECT DISTINCT i.establish_year, cd.seats_total , cd.course_id
			FROM course_details cd
			JOIN institute i ON ( i.institute_id = cd.institute_id
				AND cd.status = 'live'
				AND i.status = 'live' ) WHERE
			i.institute_id= ?";
	
        $query = $dbHandle->query($queryCmd,array($institute_id));
	$result=array();
        foreach($query->result_array() as $row){
		$result[]=$row['establish_year'];
		$result[]=$row['seats_total'];
		$result[]=$row['course_id'];	
	}
        return $result;
    }
    
    // this API returns lisiting owner's information
    public function getListingClientInfo($request) {
    	$getDbHandle = $this->getDbHandle();
    	$listing_type_id = $request['listing_type_id'];
    	$listing_type = $request['listing_type'];
    	if(!is_array($listing_type_id)) {
    		$listing_type_id = array($listing_type_id);
    	}

    	$query = "SELECT usr.userid,usr.displayname,usr.email,usr.mobile,usr.firstname,usr.lastname,lmn.listing_type_id,lmn.expiry_date ".
    			 "FROM tuser usr,listings_main lmn where lmn.username=usr.userid AND ".
    			 "lmn.listing_type_id in (?) AND lmn.listing_type= ? AND lmn.status='live'";
    	error_log("adityaquery".$query);

    	$query = $getDbHandle->query($query,array($listing_type_id,$listing_type));
    	$result = array();
    	$temp_array = $query->result_array();
    	if(count($temp_array) == 1) {
    		$result = $temp_array[0];
    	} else {
    		foreach ($temp_array as $row) {
    			$result[$row['listing_type_id']] = $row;
    		}
    	}

        return $result;
    }


    // this api updates the owner info for related group of listings

    public function updateOwnerInfoForRelatedListings($course_id,$owner_id = "") {
    	// if course_id is empty do nothing
    	if(empty($course_id)) {
    		return true;
    	}
    	//Get related listings ids
    	$query1 = "SELECT cd.institute_id, group_concat(course_id) as courses from ".
    			 "(select cd1.institute_id from course_details cd1 where cd1.status = 'live' ". 
                 "AND cd1.course_id= ? ) X JOIN course_details cd ON ".
    	         "(cd.institute_id =  X.institute_id) where cd.status='live' group by cd.institute_id";
    	// query db and get ids
    	$getDbHandle = $this->getWriteHandle();
    	$query = $getDbHandle->query($query1,array($course_id));
    	$result_string = "";
        $institute_id = "";
        error_log("select_query".$query1);
    	foreach($query->result() as $row) {
                $institute_id =  $row->institute_id;
    		$result_string = $row->courses;
    	}
    	//if ids of listings are not found do nothing
    	if($result_string == "" || $owner_id == "" || empty($owner_id) || empty($institute_id)) {
    		return true;
    	}
        // update ownership info in listings_main table for institute
        $result_string = explode(',', $result_string);
        $update_query_institute = "update listings_main set username= ? ".
    	"where listing_type_id= ? and status in ('draft','live') and listing_type='institute'";
    	error_log("update_query".$update_query_institute);
    	$query = $getDbHandle->query($update_query_institute,array($owner_id,$institute_id));
    	// update ownership info in listings_main table for related listings courses
    	$update_query = "update listings_main set username= ? ".
    	"where listing_type_id in (?) and status in ('draft','live') and listing_type='course'";
    	error_log("update_query".$update_query);
    	$query = $getDbHandle->query($update_query,array($owner_id,$result_string));
    }

    /**
	Check status of listings
    */
    public function checkStatusOfListings($listings_ids = array(),$listing_type = '') {
        // error handling
        if(count($listings_ids) == 0 || $listing_type == '') {
        	return array();
	}
        // select query from listings_main
    	$select_query = "select listing_type_id from listings_main where listing_type= ? and status='live' ".
                        "and listing_type_id in (?)"; 
        
        error_log("aditya".$select_query);
        // get db handle and query db
        $getDbHandle = $this->getDbHandle();
        $query = $getDbHandle->query($select_query,array($listing_type,$listings_ids));
        $result_array = array();
        // create result array
        foreach($query->result() as $row) {
    		$result_array[$row->listing_type_id] = "valid";
    	}
        return $result_array;
   }

    public function getListingMaxVersionInfo($listingId, $listingType="institute") {
        $queryCmd = 'select * from listings_main where listing_type = ? and listing_type_id = ? order by version desc limit 1';
        $query =  $this->db->query($queryCmd,array($listingType,$listingId));
        return $query->result_array();
    }

    public function getListingMaxVersion($litingIds, $listingType="institute") {
        // $queryCmd = 'select max(version) as version, listing_type_id, listing_title from listings_main where listing_type = "'.$listingType.'" and listing_type_id in ('.$litingIds.') GROUP BY listing_type_id';

        if(!is_array($litingIds)){
            $litingIds = explode(',', $litingIds);
        }
        if(count($litingIds)==0){
            return;
        }
        $queryCmd = 'SELECT lm.listing_id, lm.version, lm.listing_type_id, lm.listing_title from listings_main lm
                    INNER JOIN (
                            select max(version) as LMversion, listing_type_id, listing_type from listings_main
                            where listing_type = ? AND listing_type_id in (?)
                            GROUP BY listing_type_id ) recent
                    ON
                             recent.LMversion = lm.version AND
                             recent.listing_type_id = lm.listing_type_id AND
                             recent.listing_type = lm.listing_type
                    WHERE 
                            lm.listing_type = ? AND lm.listing_type_id in (?)';

        // error_log("\n\n version QUERY:\n ".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
        
        $query =  $this->db->query($queryCmd,array($listingType,$litingIds,$listingType,$litingIds));
        foreach ($query->result() as $row){
                $version[$row->listing_type_id]['version'] = $row->version;
                $version[$row->listing_type_id]['listing_title'] = $row->listing_title;
        }
        return $version;
    }
    
   public function getInstituteLocationContactDetails($instituteId) {
        if($instituteId == '') {
                return array();
	}

        $versionArray = $this->getListingMaxVersionInfo($instituteId, "institute");
        $institueMaxVersion = $versionArray[0]['version'];

        // Query to get all locations and respective contacts details of the institute..
        $queryCmd = 'select
                        distinct ilt.institute_location_id, lcm.localityName as locality_name, cct.city_name as city_name,
                        ilt.locality_name as alternate_locality, lcd.`contact_details_id`, lcd.`contact_person`, 
                        lcd.`contact_email`, lcd.`contact_main_phone`, lcd.`contact_cell`, ilt.country_id
                    from
                        countryCityTable cct,
                        listing_contact_details lcd,
                        institute_location_table ilt
                        left join localityCityMapping lcm on lcm.localityId = ilt.locality_id
                    where
                        ilt.version=1 and
                        lcd.version=1 and
                        institute_id=? and
                        ilt.city_id = cct.city_id and
                        lcd.institute_location_id = ilt.institute_location_id and
                        lcd.listing_type = "institute" and
                        lcd.listing_type_id = ilt.institute_id
                    order by
                        city_name, locality_name';

        // Get db handle and query db
        $getDbHandle = $this->getDbHandle();
        $query = $getDbHandle->query($queryCmd,array($instituteId));
        $instituteArray = array();
        $instituteName = $versionArray[0]["listing_title"];
        $i = 0;
        foreach($query->result() as $row) {
                if($row->locality_name != "" && $row->locality_name != NULL) {
                    $locality_name = $row->locality_name;
                } else if($row->alternate_locality != "" && $row->alternate_locality != NULL) {
                    $locality_name = $row->alternate_locality;
                } else {
                    $locality_name = "";
                }
                
				$instituteArray[$i]['institute_location_id'] = $row->institute_location_id;
                $instituteArray[$i]['locality_name'] = $locality_name;
                $instituteArray[$i]['city_name'] = $row->city_name;
                $instituteArray[$i]['contact_details_id'] = $row->contact_details_id;
                $instituteArray[$i]['contact_person'] = $row->contact_person;
                $instituteArray[$i]['contact_email'] = $row->contact_email;
                $instituteArray[$i]['contact_main_phone'] = $row->contact_main_phone;
                $instituteArray[$i]['contact_cell'] = $row->contact_cell;
				$instituteArray[$i++]['country_id'] = $row->country_id;
    	}

        // Now lets get all the live courses id of this instiute..
        $courseSql = "SELECT `course_id` FROM `course_details` WHERE `institute_id` = ? AND `status` = 'live' order by courseTitle";
        $courseQuery = $getDbHandle->query($courseSql,array($instituteId));
        $courseIdsArray = array();
        foreach($courseQuery->result() as $row) {
            $courseIdsArray[] = $row->course_id;
        }

        $courseVersionArray = $this->getListingMaxVersion($courseIdsArray, "course");

        $courseIdsArrayLength = count($courseIdsArray);
        $courseArray = array();
        for($j = 0; $j < $courseIdsArrayLength; $j++) {
                $currentCourseId = $courseIdsArray[$j];
                $courseNameArray[$currentCourseId] = $courseVersionArray[$currentCourseId]["listing_title"];

                $courseArray[$currentCourseId][0] = $this->getGlobalContactDetailsOfCourse($currentCourseId, $courseVersionArray[$currentCourseId]['version']);

                // Query to get all locations and their respective Contact Details of the course..
                $queryCmd = 'select
                                    ilt.institute_location_id, lcm.localityName as locality_name, cct.city_name as city_name,
                                    ilt.locality_name as alternate_locality, lcd.`contact_details_id`, lcd.`contact_person`,
                                    lcd.`contact_email`, lcd.`contact_main_phone`, lcd.`contact_cell`
                            from
                                    countryCityTable cct,
                                    course_location_attribute cla left join listing_contact_details lcd on
                                        (lcd.institute_location_id = cla.institute_location_id and lcd.listing_type = "course" and lcd.listing_type_id = cla.course_id and lcd.version = 1),
                                    institute_location_table ilt left join localityCityMapping lcm on
                                        lcm.localityId = ilt.locality_id
                            where
                                    ilt.version=1 and
                                    cla.version=1 and
                                    cla.course_id = ? and
                                    cla.institute_location_id = ilt.institute_location_id and 
                                    cla.attribute_type = "Head Office" and
                                    ilt.city_id = cct.city_id
                            order by
                                    city_name, locality_name';

                $query = $getDbHandle->query($queryCmd,array($currentCourseId));
                if($courseArray[$currentCourseId][0] == "") {
                    $i = 0;
                } else {
                    $i = 1;
                }

                foreach($query->result() as $row) {
                        if($row->locality_name != "" && $row->locality_name != NULL) {
                            $locality_name = $row->locality_name;
                        } else if($row->alternate_locality != "" && $row->alternate_locality != NULL) {
                            $locality_name = $row->alternate_locality;
                        } else {
                            $locality_name = "";
                        }

                        $courseArray[$currentCourseId][$i]['institute_location_id'] = $row->institute_location_id;
                        $courseArray[$currentCourseId][$i]['locality_name'] = $locality_name;
                        $courseArray[$currentCourseId][$i]['city_name'] = $row->city_name;
                        $courseArray[$currentCourseId][$i]['contact_details_id'] = $row->contact_details_id;
                        $courseArray[$currentCourseId][$i]['contact_person'] = $row->contact_person;
                        $courseArray[$currentCourseId][$i]['contact_email'] = $row->contact_email;
                        $courseArray[$currentCourseId][$i]['contact_main_phone'] = $row->contact_main_phone;
                        $courseArray[$currentCourseId][$i++]['contact_cell'] = $row->contact_cell;
                }

        }   // End of for($j = 0; $j < $courseIdsArrayLength; $j++).
        
        $result_array[] = $instituteName;
        $result_array[] = $instituteArray;
        $result_array[] = $courseNameArray;
        $result_array[] = $courseArray;

        return $result_array;
   }

   function getGlobalContactDetailsOfCourse($currentCourseId, $version) {
            // Get db handle and query db
            $getDbHandle = $this->getDbHandle();
            // Query to get Global contact details of the course..
            $queryCmd = "SELECT `contact_details_id`, `contact_person`, `contact_email`, `contact_main_phone`, `contact_cell` FROM `listing_contact_details` WHERE `listing_type` = 'course' AND `listing_type_id` = ? AND `institute_location_id` = 0 AND `status` = ?";
            $query = $getDbHandle->query($queryCmd,array($currentCourseId,'draft'));
			if($query->num_rows() <= 0) {
				$query = $getDbHandle->query($queryCmd,array($currentCourseId,'live'));
			}
            $courseArray = array();
            // Create result array
            foreach($query->result() as $row) {
                    $courseArray['institute_location_id'] = 0;
                    $courseArray['locality_name'] = '';
                    $courseArray['city_name'] = '';
                    $courseArray['contact_details_id'] = $row->contact_details_id;
                    $courseArray['contact_person'] = $row->contact_person;
                    $courseArray['contact_email'] = $row->contact_email;
                    $courseArray['contact_main_phone'] = $row->contact_main_phone;
                    $courseArray['contact_cell'] = $row->contact_cell;
            }

            return $courseArray;
   } 

   function unsetMainInstitute($idsToUnset) {
        if($idsToUnset == "") {
            return;
        }
        if(!is_array($idsToUnset)){
            $idsToUnset = explode(',', $idsToUnset);
        }
        $getDbHandle = $this->getWriteHandle();
        $update_query = "update PageCollegeDb set status = 'deleted', lastModificationDate = now() where id in (?)";
        $query = $getDbHandle->query($update_query,array($idsToUnset));
        return;
   }

    /**
	Check status of listings
    */
    public function insertDeletedMapping($listing_type,$deleted_listing_id,$deleted_listing_redirect_id,$deleted_listing_qna_id,$deleted_listing_alumni_id,$updated_institute_name) {
        // error handling
        if(empty($deleted_listing_id) || empty($listing_type) || $deleted_listing_id == 0 ||
			empty($deleted_listing_alumni_id)) {
        	return "success";
	}
        // select query from listings_main
        
        $data = array(
           'listing_type'                       => $listing_type ,
           'listing_type_id'                    => $deleted_listing_id ,
           'replacement_lisiting_type_id'       => $deleted_listing_redirect_id,
           'qnareplacement_lisiting_type_id'    => $deleted_listing_qna_id,
           'alumnireplacement_lisiting_type_id' => $deleted_listing_alumni_id,
        );
         // get db handle and query db
        $getDbHandle = $this->getWriteHandle();
        $getDbHandle->insert('deleted_listings_mapping_table', $data); 

        // case for handling cascade delete || do the same behaviour
	$update_casecade_delete = "UPDATE deleted_listings_mapping_table SET replacement_lisiting_type_id= ? ".
                          "WHERE replacement_lisiting_type_id= ? AND listing_type= ? ";
	$query = $getDbHandle->query($update_casecade_delete,array($deleted_listing_redirect_id,$deleted_listing_id,$listing_type));

	$update_casecade_delete = "UPDATE deleted_listings_mapping_table SET qnareplacement_lisiting_type_id= ? ".
                          "WHERE qnareplacement_lisiting_type_id= ? AND listing_type= ? ";
	$query = $getDbHandle->query($update_casecade_delete,array($deleted_listing_qna_id,$deleted_listing_id,$listing_type));

	$update_casecade_delete = "UPDATE deleted_listings_mapping_table SET alumnireplacement_lisiting_type_id= ? ".
                          "WHERE replacement_lisiting_type_id= ? AND listing_type= ? ";
	$query = $getDbHandle->query($update_casecade_delete,array($deleted_listing_alumni_id,$deleted_listing_id,$listing_type));

        // update message table
        if($deleted_listing_qna_id > 0 && $deleted_listing_id > 0) {
		$update_query = "UPDATE messageTable set listingTypeId= ? WHERE listingTypeId= ? ".
                                " AND status='live' AND listingType='institute'";
        	$query = $getDbHandle->query($update_query,array($deleted_listing_qna_id,$deleted_listing_id));          
        }
	
  	// update talumns_feedback_rating
        if($deleted_listing_alumni_id > 0 && $deleted_listing_id > 0 )
        {
		$update_query = "UPDATE talumnus_feedback_rating set institute_id= ? WHERE institute_id = ? ";
		$query = $getDbHandle->query($update_query,array($deleted_listing_alumni_id,$deleted_listing_id));
	        $update_query_2 = "UPDATE talumnus_details set institute_name = ?, institute_id = ?  WHERE institute_id = ? ";
	        $query = $getDbHandle->query($update_query_2,array($updated_institute_name,$deleted_listing_alumni_id,$deleted_listing_id));
	    
	    	$calQ= "select round(avg(criteria_rating),1) as rating,count(*) as num,criteria_id from talumnus_feedback_rating where institute_id = ? and status='published'  and criteria_rating <> 0 group by criteria_id";
            	$rating = null;
            	$qu= $getDbHandle->query($calQ,array($deleted_listing_alumni_id));
            	$rating_array = array();
            	foreach ($qu->result_array() as $row){
               		$rating_array[$row["criteria_id"]]["n"] = $row["num"];
              		$rating_array[$row["criteria_id"]]["r"] = $row["rating"];
               		if($row["criteria_id"]==4)
                    		$rating= $row["rating"];
            	}
            	$ratings_json = json_encode($rating_array);
            	// $ratings_update_clause = isset($rating)?"alumni_rating = $rating":"";
                $queryParams = array();
                if(isset($rating)){
                    $upQ= "Update institute_mediacount_rating_info set alumni_rating = ?, ratings_json='$ratings_json' where institute_id= ?";
                    $queryParams[] = $rating;
                }else{
                    $upQ= "Update institute_mediacount_rating_info set ratings_json='$ratings_json' where institute_id= ?";
                }
                $queryParams[] = $deleted_listing_alumni_id;
            	$que= $getDbHandle->query($upQ,$queryParams);
		
	}
        
        return "success";
   }
   /**
	get details of location
   */
  public function getLocationAndContactInfoForListing($dbHandle,$listing_type_id,$listing_type,$version,&$data) {
        if($listing_type == 'institute') {
  		$get_query = "select ilt.institute_location_id,ilt.pincode,ilt.city_name, ".
                             "ilt.locality_name,ilt.address_2,ilt.address_1,ilt.locality_id, ".
                             "ilt.zone,ilt.city_id,ilt.country_id, ".
                             "lcd.contact_person,lcd.contact_main_phone,lcd.contact_cell, ".
                             "lcd.contact_alternate_phone,lcd.contact_fax,lcd.contact_email, ".
                             "lcd.website from institute_location_table ilt,listing_contact_details lcd ".
                             "where lcd.version = ? AND ilt.version = ? ".
                             "AND ilt.institute_location_id = lcd.institute_location_id AND ilt.institute_id = ? ".
                             "AND lcd.listing_type_id= ? AND lcd.listing_type = ?";
                $query = $dbHandle->query($get_query,array($version,$version,$listing_type_id,$listing_type_id,$listing_type));
                error_log('aditya'.$get_query);
                if(count($query->result_array()) > 0) {
                        $i=0;
			foreach($query->result_array() as $row) {
                        // contact info
			$data['contactInfo'][$i]['contact_person_name'] = $row['contact_person'];
			$data['contactInfo'][$i]['main_phone_number'] = $row['contact_main_phone'];
			$data['contactInfo'][$i]['mobile_number'] = $row['contact_cell'];
			$data['contactInfo'][$i]['alternate_phone_number'] = $row['contact_alternate_phone'];
			$data['contactInfo'][$i]['fax_number'] = $row['contact_fax'];
			$data['contactInfo'][$i]['contact_person_email'] = $row['contact_email'];
			$data['contactInfo'][$i]['website'] = $row['website'];
                        $data['contactInfo'][$i]['institute_location_id'] = $row['institute_location_id'];
                        // location info
                        $data['locationInfo'][$i]['country_id'] = $row['country_id'];
			$data['locationInfo'][$i]['city_id'] = $row['city_id'];
			$data['locationInfo'][$i]['zone_id'] = $row['zone'];
			$data['locationInfo'][$i]['locality_id'] = $row['locality_id'];
			$data['locationInfo'][$i]['address1'] = $row['address_1'];
			$data['locationInfo'][$i]['address2'] = $row['address_2'];
			$data['locationInfo'][$i]['locality_name'] = $row['locality_name'];
			$data['locationInfo'][$i]['city_name'] = $row['city_name'];
			$data['locationInfo'][$i]['pincode'] = $row['pincode'];
                        $data['locationInfo'][$i]['institute_location_id'] = $row['institute_location_id'];
                        $i++;
                        }
                }
                //error_log('aditya'.print_r($data,true));
        	return $data;
        } else if($listing_type == 'course') {
			$sql_to_get_course_location_id = "SELECT institute_location_id,attribute_value from course_location_attribute ".
                        "WHERE version= ? and attribute_type = 'Head Office' and course_id= ?";
                        $query = $dbHandle->query($sql_to_get_course_location_id,array($version,$listing_type_id));
               	if(count($query->result_array()) > 0) {
			foreach($query->result_array() as $row) {
				$result_array[] = $row['institute_location_id'];
                                if($row['attribute_value'] == 'TRUE') {
					$headoffice_id = $row['institute_location_id'];
                                }
                        }
                        $data['institute_location_ids'] = implode(",",$result_array);
                        $data['head_ofc_location_id'] = $headoffice_id;
                }
                        $sql_to_get_course_location_fee = "SELECT institute_location_id,attribute_type,attribute_value ".
                        "FROM course_location_attribute where version= ? and attribute_type in('Course Fee Value','Course Fee Unit') ".
                        "and course_id= ? ";
                         $query = $dbHandle->query($sql_to_get_course_location_fee,array($version,$listing_type_id));
                if(count($query->result_array()) > 0) {
                        $variable = "";
			foreach($query->result_array() as $row) {
                                if($row['attribute_type'] == 'Course Fee Value') {
					$variable = 'fee_value';
                                } else if($row['attribute_type'] == 'Course Fee Unit') {
					$variable = 'fee_unit';
                                }
				$result_array_fees[$row['institute_location_id']][$variable] = $row['attribute_value'];
                        }
                        $data['locationFeeInfo'] = $result_array_fees;
                } 
                        $sql_to_get_important_date_info = "SELECT institute_location_id,GROUP_CONCAT(attribute_value SEPARATOR '_') as data_str ".
                        "FROM course_location_attribute where version= ? and ".
                        "attribute_type in('date_form_submission','date_result_declaration','date_course_comencement') ".
                        "and course_id= ? group by institute_location_id";
                        $query = $dbHandle->query($sql_to_get_important_date_info,array($version,$listing_type_id));
               if(count($query->result_array()) > 0) {
			foreach($query->result_array() as $row) {
                        	$imp_date_array[] = $row['institute_location_id']."_".$row['data_str'];
                        }
                        $data['important_date_info_location'] = implode("||++||",$imp_date_array);
               }

                        $sql_to_get_contact_details_info = "SELECT institute_location_id,contact_person,contact_main_phone, ".
                        "contact_cell,contact_email from listing_contact_details where listing_type_id= ? ".
                        "and listing_type='course' and version= ? and institute_location_id is not null";
                        $query = $dbHandle->query($sql_to_get_contact_details_info,array($listing_type_id,$version));
              if(count($query->result_array()) > 0) {
	      		foreach($query->result_array() as $row) {                                
				$contact_details_array[] = implode("|=#=|",$row);
                        }

                        $data['course_contact_details_locationwise'] = implode("||++||",$contact_details_array);
              }
              return $data;
        }
 }
 
	public function recordCallWidgetLoad($instituteId,$courseId,$widget){
    	$dbHandle = $this->getWriteHandle();
    	$query = "INSERT INTO `callPatchingPilot` (
				`date` ,
				`type` ,
				`instituteId` ,
				`courseId` ,
				`sessionId`
				)
				VALUES (
				CURRENT_TIMESTAMP , ?, ?, ?, ?
				)";
    	$query = $dbHandle->query($query,array($widget,$instituteId,$courseId,sessionId())); 
    }
        function getActiveLisitingsForagroupOfOwner($owner_string = '') {
                if(empty($owner_string)) return array();
                if(!is_array($owner_string)){
                    $owner_string = explode(',', $owner_string);
                }
                $response_array = array();
		$select_query = "SELECT listing_type_id,listing_type,pack_type,listing_title FROM listings_main,tuser WHERE status='live' ".
                                "AND username in (?) AND listing_type in('university','institute','course','university_national') ".
                                "AND username=userid";
                error_log('aditya_log'.$select_query);
                 // get db handle and query db
        	$getDbHandle = $this->getDbHandle();
        	$query = $getDbHandle->query($select_query,array($owner_string));
                return $query->result_array(); 
        }
        function getInstitutesHavingEbrochureUploadedForagroupOfOwner($owner_string = '') {
                if(empty($owner_string)) return array();
                if(!is_array($owner_string)){
                    $owner_string = explode(',', $owner_string);
                }
                $response_array = array();
		$select_query = "SELECT count(distinct lm.listing_type_id) as institute_brochure_count FROM listings_main lm,tuser tu,institute inst WHERE lm.status='live' ".
                                "AND lm.username in (?) AND lm.listing_type = 'institute' AND inst.status='live' ".
                                "AND lm.username=tu.userid AND lm.listing_type_id = inst.institute_id AND ".
                                "inst.institute_request_brochure_link IS NOT NULL AND inst.institute_request_brochure_link !=''";
                error_log('aditya_log'.$select_query);
                 // get db handle and query db
        	$getDbHandle = $this->getDbHandle();
        	$query = $getDbHandle->query($select_query,array($owner_string));
                return $query->result_array(); 
        }
        function getPaidCouresHavingEbrochureUploadedForagroupOfOwner($owner_string = '') {
                if(empty($owner_string)) return array();
                if(!is_array($owner_string)){
                    $owner_string = explode(',', $owner_string);
                }
                $response_array = array();
		$select_query = "SELECT count(distinct lm.listing_type_id) as course_brochure_count FROM listings_main lm,tuser tu,course_details cd WHERE lm.status='live' ".
                                "AND lm.username in (?) AND lm.listing_type = 'course' AND cd.status='live' ".
                                "AND lm.username=tu.userid AND lm.listing_type_id = cd.course_id AND ".
                                "cd.course_request_brochure_link IS NOT NULL AND cd.course_request_brochure_link !='' ".
                                "AND pack_type IN ".
                                "(".GOLD_SL_LISTINGS_BASE_PRODUCT_ID.",".SILVER_LISTINGS_BASE_PRODUCT_ID.",".GOLD_ML_LISTINGS_BASE_PRODUCT_ID.")";
                error_log('aditya_log'.$select_query);
                 // get db handle and query db
        	$getDbHandle = $this->getDbHandle();
        	$query = $getDbHandle->query($select_query,array($owner_string));
                return $query->result_array(); 
        } 
        
		/**
		* Increase count of Contact details  for a listing
		* @param  listingIds : listingId whose count to be increased.
		* @param  listing_type : type of listingIds('course','institute').
		* @param  trakcing_field : can be one of 'Bottom_first','Bottom_second','Top_first','Top_second'
		* @return success on successful increase in count in db.
		*/
        public function increaseContactCountOfListing($listing_id,$listing_type,$tracking_field) {
		
		if((!$listing_id) || $listing_type == '' || empty($tracking_field)) {
			return ;
		}

		$dbHandle = $this->getWriteHandle();

	    $select_query = "SELECT no_of_times as noOfTimes FROM listing_contact_count WHERE listing_type_id = ? AND listing_type = ? AND tracking_field = ? ";
		$query = $dbHandle->query($select_query,array($listing_id,$listing_type,$tracking_field));
		
		if(count($query->result_array()) > 0) {
			$update_query = "update listing_contact_count set no_of_times = no_of_times+1 where listing_type_id = ? and listing_type= ? and tracking_field = ? ";
			$query = $dbHandle->query($update_query,array($listing_id,$listing_type,$tracking_field));
			
			return "success";
		}	
		else{
			//insert query
             $data = array(
               'listing_type'    => $listing_type ,
               'listing_type_id' => $listing_id ,
               'no_of_times'     => 1,
               'tracking_field'  => $tracking_field
            );

			// get db handle and query db
			$getDbHandle = $this->getWriteHandle();
            $getDbHandle->insert('listing_contact_count', $data); 
			
			return "success";
		}        // get db handle and query db    
	}    
    
    
    /**
     * Get total number of Contact details count for multiple listings
     * @param  listingIds : array of listingIds
     * @param  type : type of listingIds('course','institute')
     * @return empty array if no result found
     * @return Array of total_contact_count of the listings indexed on Institute Id. 
     */
    function getContactCountForListings($listingIds,$type) {
		if(empty($listingIds)) return array();
        if(!is_array($listingIds)){
            $listingIds = explode(',', $listingIds);
        }
		$response_array = array();
		$select_query = "select sum(no_of_times)  as total_contact_count,listing_type_id from listing_contact_count where listing_type_id in (?) and listing_type = ? group by listing_type_id ";
		// get db handle and query db
		$getDbHandle = $this->getDbHandle();
		$query = $getDbHandle->query($select_query,array($listingIds,$type));
		
		$contact_contact_details_array = array();
		if(count($query->result_array()) > 0) {
			foreach($query->result_array() as $row) {   
				$contact_contact_details_array[$row['listing_type_id']] = $row['total_contact_count'];
			}
		}
		return $contact_contact_details_array; 
	}   

	 function updateInstituteColumns($institute_id,$profile_percentage_completion) {
		if(empty($profile_percentage_completion)) return false; 
			$query_to_append = ""; 
			$update_sql = "UPDATE institute SET profile_percentage_completion=? WHERE status='live' AND institute_id=?"; 
                        
			// get db handle and query db 
			$getDbHandle = $this->getWriteHandle(); 
			$query = $getDbHandle->query($update_sql,array($profile_percentage_completion,$institute_id)); 
		}      

    function updateBatchProfileCompletionInstitutes($input_array){
        if(empty($input_array)){
            return false;
        }
        $getDbHandle = $this->getWriteHandle();
        $getDbHandle->where(array('status' => 'live'));
        $getDbHandle->update_batch('institute', $input_array, 'institute_id'); 
//        $sqlQuery =  $getDbHandle->last_query();
    }    	

    function updateInstituteRefreshTime($institute_id) {
	if($institute_id>0){
	    $update_sql = "UPDATE institute_related_question_table SET lastUpdatedTime = now() WHERE institute_id= ? "; 
	    // get db handle and query db 
	    $getDbHandle = $this->getWriteHandle(); 
	    $query = $getDbHandle->query($update_sql,array($institute_id));
	}
    }
	
	/*
	 *Temporary function, should be avoided.
	*/
	public function getListingContactNumbersForValidation($offset = 0, $limit = 100, $contactNumberType = NULL){
		$dbHandle = $this->getDbHandle();
		$contactNumberTypeCheckSQL = "";
		if(!empty($contactNumberType)){
			if($contactNumberType == "mobile"){
				$contactNumberTypeCheckSQL = " AND contact_cell != '' ";
			}
		}
		$queryCmd ="SELECT *
					FROM
					listing_contact_details
					WHERE
					status = 'live'
					$contactNumberTypeCheckSQL
					ORDER BY contact_details_id asc
					LIMIT ?,?
					";
		$query = $dbHandle->query($queryCmd,array($offset, $limit));
		$data = array();
		foreach($query->result() as $row) {
			$data[] = (array)$row;
		}
		return $data;
	}
	
	/*
	 *Temporary function, should be avoided.
	*/
	public function checkIfValidationAlreadyDone($contactNumber = NULL, $instituteId = NULL, $contactNumberType = NULL){
		$dbHandle = $this->getWriteHandle();
		$return = false;
		if(empty($contactNumber) || empty($instituteId) || empty($contactNumberType)){
			return false;
		}
		$queryCmd ="SELECT *
					FROM
					listing_contact_number_validation
					WHERE
					contact_number = ? AND
					contact_number_type = ? AND
					parent_listing_id = ? AND
					parent_listing_type = 'institute'
					order by id asc
					limit 1
					";
		$query = $dbHandle->query($queryCmd,array($contactNumber,$contactNumberType,$instituteId));
		$data = array();
		foreach($query->result() as $row) {
			$data = (array)$row;
		}
		return $data;
    }
	
	/*
	 *Temporary function, should be avoided.
	*/
	public function insertMobileValidationRecord($modelParams = array()){
		$dbHandle = $this->getWriteHandle();
		$return = false;
		if(empty($modelParams)){
			return false;
		}
		$data = array (
					"contact_details_id"		=> 	$modelParams['contact_details_id'],
           			"contact_number"			=>	$modelParams['contact_number'],
           			"contact_number_type"		=>	$modelParams['contact_number_type'],
					"listing_id"				=>	$modelParams['listing_id'],
					"listing_type"				=>	$modelParams['listing_type'],
           			"parent_listing_id"			=>	$modelParams['parent_listing_id'],
           			"parent_listing_type"		=>	$modelParams['parent_listing_type'],
           			"response"					=>	$modelParams['response'],
					"verfied_via_contact_details_id" =>	$modelParams['verfied_via_contact_details_id'],
				);
		
		$queryCmd 			= $dbHandle->insert_string('listing_contact_number_validation', $data);
		$query 				= $dbHandle->query($queryCmd); 
        $unique_insert_id 	= $dbHandle->insert_id();
		return $unique_insert_id;
    }
    /*function to get LDB course name for ldn course id*/
    function getLDBCourseName($ldbCourseId){
		$dbHandle = $this->getReadHandle();
		$queryCmd ="SELECT *
					FROM
					tCourseSpecializationMapping
					WHERE
					SpecializationId = ? AND
					Status = 'live'
					limit 1
					";
		$query = $dbHandle->query($queryCmd,array($ldbCourseId));
		$data = array();
		foreach($query->result() as $row) {
			$data = (array)$row;
		}
		return $data;
    }

	/* Function to get the Shiksha creates E-Brochure for any Listing */
        public function getShikshaUploadedRequestEBrochureData($appId, $listing_type, $listing_type_id) {
                if($listing_type == "" || $listing_type_id == "") {
                        error_log("LISTINGS_EBROCHURE_ISSUE: Empty params in getListingEBrochureInfo!");
                        return ;
                }

                $dbHandle = $this->getReadHandle();
                $sql = "select * from `listings_ebrochures` WHERE `listingType` = ? AND `listingTypeId` = ? AND `status` = 'live'";
                $rs = $dbHandle->query($sql,array($listing_type,$listing_type_id));
                $rs = $rs->result_array();
                if(!empty($rs[0]['ebrochureUrl'])){
                    $rs[0]['ebrochureUrl'] = MEDIAHOSTURL.$rs[0]['ebrochureUrl'];
                }
                
                return $rs;
        }
    
    /**
    * Function to check for reponse made for the given user,course for last 24 hours
    * changelog (17-02-14):listing_subscription_type='paid' removed, since now both free & paid are considered
    * changelog (04-03-15):optional parameter $dbHandleType added to acquire write handle if required for prevention of 500 Int.Ser.Err. (This is useful in the case of response creation code where a low priority response has been generated on a course and moments later a higher priority response gets captured. Due to the minor read write delay this would cause below function to give no result if we use a read handle.)
    **/
    public function getLastDayLead( $userId, $listing_type_id, $listing_type, $dbHandleType = 'read' )
    {
	    if(!($listing_type_id > 0 && $userId > 0)) {
		return array();
	    }
	
	    $queryCmd 	= "select id,action from tempLMSTable where userId = ? and listing_type_id = ? and listing_type = ? and (UNIX_TIMESTAMP(now())- UNIX_TIMESTAMP(submit_date))<86400";
	    if($dbHandleType == 'write'){
		$dbHandle 	= $this->getWriteHandle();
	    }
	    else{
		$dbHandle 	= $this->getReadHandle();
	    }
		
	    $Result 	= $dbHandle->query($queryCmd, array((int) $userId, (int) $listing_type_id, $listing_type));
	    $row 	= $Result->row_array();
	
	    return $row;
    }
    
    public function getListingEditDetails($listingType, $listingId)
    {
	    $queryCmd = "SELECT listings_main.last_modify_date, COALESCE( CONCAT(NULLIF( TRIM( tuser.firstname ) , '' ), ' ' , NULLIF( TRIM( tuser.lastname ) , '' ) ) , NULLIF( TRIM( tuser.displayname ) , '' ), tuser.email ) AS name from listings_main, tuser WHERE listings_main.listing_type = ? AND listings_main.listing_type_id = ? AND listings_main.editedBy = tuser.userid AND listings_main.status = 'live'";
	    $dbHandle = $this->getReadHandle();
	    $result   = $dbHandle->query($queryCmd,array($listingType,$listingId));
	    
	    $editedData = array();
	    if($result->num_rows() > 0) {
		$row      = $result->row();
		$editedData['modifiedDate'] = $row->last_modify_date;
		$editedData['modifiedBy'] = $row->name;
	    }
	    
	    return $editedData;
    }
    
    
    public function getInaccurateContactNoReportedListings($listingType = 'course', $pastDays = 7) {
    	
    	
    	$sql = "SELECT listing_type_id,reported_number,report_time
    			FROM `listing_reported_contact_numbers` 
    			WHERE `listing_type` = ?
    			AND `report_time` 
    			BETWEEN DATE_SUB( CURDATE( ) , INTERVAL ? DAY ) AND CURDATE( )";
    	$dbHandle = $this->getReadHandle();
    	$resultData['contactReported'] = $dbHandle->query($sql,array($listingType,$pastDays))->result_array();
    	$resultData['listingIds'] = $this->getColumnArray($resultData['contactReported'],'listing_type_id');
    	
    	
    	if($listingType == 'course' && count($resultData['listingIds']) > 0) {
    	$sql = "SELECT group_concat(distinct `category_id`) as subcatId,course_id FROM `categoryPageData` WHERE status = 'live' and course_id IN (?) group by `course_id`";
    	$resultData['subCatIds'] = $dbHandle->query($sql,array($resultData['listingIds']))->result_array();
    	$totalSubCatIdsString;
    	foreach($resultData['subCatIds'] as $row) {
    		if(isset($row['course_id'])) {
    			$indexedSubCatData[$row['course_id']] = $row;
    			$totalSubCatIdsString = empty($totalSubCatIdsString) ? $row['subcatId'] : $totalSubCatIdsString.",".$row['subcatId'];
    		}
    	}
    	$resultData['subCatIds'] = explode(',',$totalSubCatIdsString);
    
       	$resultData['subCatIdsIndexedWithCourseId'] = $indexedSubCatData;
    	
    	}
    	
    	return $resultData;
	    
    }

    /**
    * Purpose       : Method to get the reponses of the courses for a given period
    * Params        : period array containing start and end time
    */
    function getReponseDataForPeriod($period)
    {
	$dbHandle = $this->getReadHandle();
	$query = "  SELECT 
		    tlt.listing_type,
		    tlt.listing_type_id ,
		    count(*) as response_count,
		    tlt.action,
		    tlt.listing_subscription_type
		    FROM tempLMSTable tlt 
		    WHERE 1
		    and date(tlt.submit_date) >= ?
		    and date(tlt.submit_date) <= ?
		    and tlt.listing_type = 'course'
		    group by tlt.action,tlt.listing_type_id,tlt.listing_subscription_type
		    order by response_count desc ";
		    
	$responseData = $dbHandle->query($query,array($period["start"],$period["end"]))->result_array();
	$dbHandle->close();
	
	return $responseData;
    }
    
    /**
    * Purpose       : Method to get the sub-categories of the given courses
    * Params        : course ids(array)
    */    
    function getSubCatOfCourses($courseIds)
    {
        if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
        }
        $dbHandle = $this->getReadHandle();
        $query = "  SELECT 
		    lct.listing_type_id,
		    lct.category_id as subcategoryid,
		    cbt.name
		    FROM `listing_category_table` lct
		    inner join categoryBoardTable cbt 
		    on(cbt.boardId = lct.category_id)
		    WHERE 1
		    and lct.listing_type = 'course'
		    and lct.listing_type_id in (?)
		    and lct.status = 'live' ";
		    
	$responseData = $dbHandle->query($query,array($courseIds))->result_array();
	$dbHandle->close();
	
	return $responseData;
    }
    
    /**
    * Purpose       : Method to get the head office location of the given courses
    * Params        : course ids(array)
    */    
    function getHeadOfficeLocationOfCourses($courses)
    {
        if(!(is_array($courses)&&count($courses)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = " SELECT 
		   cla.course_id as course_id,
		   city.city_name as city_name
		   FROM 
		   institute_location_table loc
		   inner join course_location_attribute cla
		   on(loc.institute_location_id = cla.institute_location_id and loc.status='live')
		   LEFT JOIN countryCityTable city ON city.city_id = loc.city_id 
		   WHERE 1
		   and cla.status='live'
		   AND cla.attribute_type = 'Head Office' 
		   AND cla.attribute_value = 'TRUE'
		   AND cla.course_id IN (?)
		   and cla.status='live'";

	$responseData = $dbHandle->query($query,array($courses))->result_array();
	$dbHandle->close();
	
	return $responseData;
    }
    
    /**
    * Purpose       : Method to get the course name, institute name, client-id of the courses
    * Params        : course ids(array)
    */
    function getCourseNameAndInstituteNameOfCourses($courses)
    {
        if(!(is_array($courses)&&count($courses)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = " SELECT 
		   cd.course_id as course_id,
		   cd.courseTitle as courseTitle,
		   inst.institute_name as institute_name,
		   inst.institute_type as institute_type,
		   lm.username as username
		   FROM course_details cd
		   inner join institute inst
		   on(inst.institute_id = cd.institute_id and inst.status='live')
		   left join listings_main lm
		   on(lm.listing_type_id = cd.course_id and lm.listing_type = 'course' and lm.status ='live')
		   WHERE 1
		   and cd.status = 'live'
		   and cd.course_id in (?)";

	$responseData = $dbHandle->query($query,array($courses))->result_array();
	$dbHandle->close();
	
	return $responseData;
    }
    
    function getEnggCourses()
    {
	$dbHandle = $this->getReadHandle();

	$query = " SELECT 
		   lct.listing_type_id
		   FROM 
		   `listing_category_table` lct
		   inner join 
		   categoryBoardTable cbt
		   on(lct.`category_id` = cbt.boardId and cbt.parentId = 2)
		   WHERE 1
		   and lct.status = 'live'
		   and lct.listing_type = 'course'
		    ";
	
	$orderByClause = " ";
	$groupByClause = " group by listing_type_id ";
	
	
	$query = $query.$groupByClause.$orderByClause;

	$responseData = $dbHandle->query($query)->result_array();
	
	return $responseData;
    }
    
    function getCoursesCatCount($courseIds)
    {
	$dbHandle = $this->getReadHandle();

    if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
    }
	$query = " SELECT 
		   lct.listing_type_id,
		   count(distinct(lct.category_id)) as subcatCount
		   FROM 
		   `listing_category_table` lct
		   inner join 
		   categoryBoardTable cbt
		   on(lct.`category_id` = cbt.boardId)
		   WHERE 1
		   and lct.status = 'live'
		   and lct.listing_type = 'course'
		   and lct.listing_type_id in (?)
		    ";
	
	$orderByClause = " order by subcatCount desc ";
	$groupByClause = " group by listing_type_id ";
	
	
	$query = $query.$groupByClause.$orderByClause;

	$responseData = $dbHandle->query($query,array($courseIds))->result_array();
	return $responseData;
    }
    
    function getEnggCoursesMappedToOtherCategories($courseIds)
    {
        if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = "SELECT 
		  lct.listing_type_id as courseIds
		  FROM 
		  `listing_category_table` lct
		  inner join 
		  categoryBoardTable cbt
		  on(lct.`category_id` = cbt.boardId)
		  WHERE 1
		  and lct.status = 'live'
		  and lct.listing_type = 'course'
		  and cbt.parentId != 2
		  and listing_type_id in (?) ";

	$responseData = $dbHandle->query($query,array($courseIds))->result_array();
	return $responseData;
    }
    
    function getSubCategoriesOfCourses($courseIds)
    {
        if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = "SELECT 
		  lct.listing_type_id,
		  lct.category_id,
		  cbt.parentId
		  FROM 
		  `listing_category_table` lct
		  inner join 
		  categoryBoardTable cbt
		  on(lct.`category_id` = cbt.boardId)
		  WHERE 1
		  and lct.status = 'live'
		  and lct.listing_type = 'course'
		  and lct.listing_type_id in (?)";

	$responseData = $dbHandle->query($query,array($courseIds))->result_array();
	return $responseData;
    }
    
    function getCoursesAndInstInfo($courseIds)
    {
        if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = "SELECT 
		  cd.course_id,
		  cd.courseTitle,
          inst.institute_id,
		  inst.institute_name,
		  inst.abbreviation 
		  FROM 
		  course_details cd
		  inner join 
		  institute inst
		  on(cd.institute_id = inst.institute_id and inst.status = 'live')
		  WHERE 1
		  and cd.status = 'live'
		  and cd.course_id in (?) ";

	
	$responseData = $dbHandle->query($query,array($courseIds))->result_array();
	return $responseData;
    }
    
    function updateCourseURL($urls)
    {
	$dbHandle = $this->getWriteHandle();

	$i = 0;

	$data = array();
	foreach($urls as $courseId=>$url)
	{
	    $i++;
	    $data[] = array('listing_type_id' => $courseId, 'listing_seo_url' => $url);
	    //$update_query = "update listings_main set listing_seo_url = ? where listing_type_id = ? and listing_type = ? and status = ? ";
	    //$rs 	  = $dbHandle->query($update_query, array($url, $courseId, 'course', 'live'));
	}
	
	$dbHandle->where('listing_type', 'course');
	$dbHandle->where_in('status', array('live','draft'));
	$rs = $dbHandle->update_batch('listings_main', $data, 'listing_type_id');

	return TRUE;
    }
    
    function getLDBCoursesForClientCourse($courseId) {
	$dbHandle = $this->getReadHandle();
	
	$query = "SELECT DISTINCT LDBCourseID FROM `clientCourseToLDBCourseMapping` WHERE `clientCourseID` = ? AND `status` = 'live'";
	
	$responseData = $dbHandle->query($query,array($courseId))->result_array();
	$mappedLDBCourseIds = array();
	if(!empty($responseData)) {
	    foreach($responseData as $data) {
		$mappedLDBCourseIds[] = $data['LDBCourseID'];
	    }
	}
	return $mappedLDBCourseIds;
    }
    
    function getLDBCoursesCountForSubCategory($ldbCourses = array(), $subCatId, $parentId) {
        if(!(is_array($ldbCourses)&&count($ldbCourses)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	
	$query = "SELECT count(*)
		FROM `LDBCoursesToSubcategoryMapping` l, `tCourseSpecializationMapping` s
		WHERE l.ldbCourseID = s.SpecializationId
		AND l.ldbCourseID in (?) 
		AND l.categoryID = ?
		AND s.parentid = ?
		AND l.status = 'live'";
	
	$responseData = $dbHandle->query($query,array($ldbCourses,$subCatId,$parentId))->result_array();
	if(!empty($responseData)) {
	    foreach($responseData as $data) {
		$count = $data['count(*)'];
	    }
	}
	return $count;
    }
    
    function findCourseReviewsData($courseIds) {
        Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');
	
        $dbHandle = $this->getReadHandle();
	$query = "SELECT
                    CollegeReview_MainTable.id,
                    CollegeReview_MainTable.reviewDescription,
                    CollegeReview_MainTable.placementDescription,
                    CollegeReview_MainTable.infraDescription,
                    CollegeReview_MainTable.facultyDescription,
                    CollegeReview_MainTable.modificationDate as postedDate,
                    CollegeReview_MainTable.moneyRating,
                    CollegeReview_MainTable.crowdCampusRating,
                    CollegeReview_MainTable.avgSalaryPlacementRating,
                    CollegeReview_MainTable.campusFacilitiesRating,
                    CollegeReview_MainTable.facultyRating,
                    CollegeReview_MainTable.review_seo_url,
                    CollegeReview_MainTable.review_seo_title,
                    CollegeReview_MainTable.creationDate,
                    CollegeReview_MainTable.recommendCollegeFlag,
                    CollegeReview_MainTable.anonymousFlag,
                    CollegeReview_MainTable.averageRating,
                    CollegeReview_MappingToShikshaInstitute.yearOfGraduation,
                    CollegeReview_MappingToShikshaInstitute.courseId,
                    listings_main.pack_type,
                    (CASE WHEN ISNULL(CollegeReview_PersonalInformation.firstname) THEN CONCAT(tuser.firstname,' ',tuser.lastname) ELSE CONCAT(CollegeReview_PersonalInformation.firstname,' ',CollegeReview_PersonalInformation.lastname) END) as username
                  FROM CollegeReview_MainTable
                    LEFT JOIN CollegeReview_MappingToShikshaInstitute
                      ON CollegeReview_MappingToShikshaInstitute.reviewId = CollegeReview_MainTable.id
                    LEFT JOIN tuser
                      ON tuser.userid = CollegeReview_MainTable.userId
                    LEFT JOIN CollegeReview_PersonalInformation
                      ON CollegeReview_PersonalInformation.id = CollegeReview_MainTable.reviewerId
                    LEFT JOIN listings_main
                      ON listings_main.listing_type_id = CollegeReview_MappingToShikshaInstitute.courseId
                  WHERE CollegeReview_MappingToShikshaInstitute.courseId IN (?) 
                      AND CollegeReview_MainTable.status ='published' 
                      AND listings_main.listing_type = 'course'
                      AND listings_main.status = 'live'
                  order by CollegeReview_MainTable.creationDate desc";
	
	$result = $dbHandle->query($query,array($courseIds))->result_array();
    /* code to get institute reply on review - start */
	$reviewIds = array();
	foreach($result as $val)
	{
	    $reviewIds[] = $val['id'];
	}
	if(!empty($reviewIds))
	{
	    $replyData = $this->findInstituteReplies(implode(',', $reviewIds));
	}
	$formatReplyData = array();
	foreach($replyData as $val)
	{
	    $formatReplyData[$val['reviewId']] = $val['replyTxt'];
	}
	$newResult = $result;
	foreach($result as $key=>$val)
	{
	    $newResult[$key]['instituteReply'] = $formatReplyData[$val['id']];
	}
	/* code to get institute reply on review - end */
	return $newResult;
    }
    
    function findInstituteReplies($reviewIds)
    {
        if(!(is_array($reviewIds)&&count($reviewIds)>=1)){
            return;
        }
	$dbHandle = $this->getReadHandle();
	$query = "select reviewId, replyTxt from CollegeReview_InstituteReply where status='live' and reviewId in (?)";
	return $dbHandle->query($query,array($reviewIds))->result_array();
    }
    
    function findCourseIdsWithReviews() {
	
        $dbHandle = $this->getReadHandle();
	$query = "SELECT
                    CollegeReview_MappingToShikshaInstitute.courseId
                  FROM CollegeReview_MainTable
                    LEFT JOIN CollegeReview_MappingToShikshaInstitute
                      ON CollegeReview_MappingToShikshaInstitute.reviewId = CollegeReview_MainTable.id
                  WHERE CollegeReview_MappingToShikshaInstitute.courseId != 'NULL' 
                        AND CollegeReview_MainTable.status                ='published' 
                        GROUP BY CollegeReview_MappingToShikshaInstitute.courseId
                        HAVING COUNT(CollegeReview_MainTable.id) >=1";
	
	return $dbHandle->query($query)->result_array();
    }
    
    function checkIfShosheleIsLiveOrNot($bannerId) {
    	$dbHandle = $this->getWriteHandle();
    	$query = "SELECT `bannerid` FROM `tbanners` WHERE `bannerid` = ? and status='live'";
    	
    	return $dbHandle->query($query,$bannerId)->result_array();
    }

    function findCourseIdsBySubCategoryId($subCatId){

        if(empty($subCatId)){
            return false;
        }

        $dbHandle = $this->getReadHandle();
        $query = "SELECT listing_type_id".
                 " FROM listing_category_table".
                 " WHERE category_id = ?".
                 " AND status ='live'".
                 " AND listing_type ='course'".
                 " GROUP BY listing_type_id";

        return $dbHandle->query($query,$subCatId)->result_array();
    }

	function getCourseLevels($courseIds) {
        Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');
        $dbHandle = $this->getReadHandle();
        $query = "SELECT course_level, course_level_1, course_level_2, course_id FROM course_details WHERE course_details.course_id IN(?) AND course_details.status = 'live';";
        return $dbHandle->query($query,array($courseIds))->result_array();
    }

    function getCoursesWithEnggUrls(){
        
        $dbHandle = $this->getReadHandle();
        $query   =  "SELECT listing_type_id , listing_seo_url ".
                    "FROM  `listings_main` ".
                    "WHERE listing_type = 'course' ".
                    "AND status in ('live','draft') ".
                    "AND  `listing_seo_url` LIKE  'http://engineering.%'";

        return $dbHandle->query($query)->result_array();
    }

    function getAllSubCategoryIdsOfCourse($courseId) {
        if(!empty($courseId)) {
            $dbHandle = $this->getReadHandle();
            $courseIdsQuery = "SELECT DISTINCT category_id FROM categoryPageData WHERE course_id = ? AND status = 'live';";
            $query          = $dbHandle->query($courseIdsQuery,$courseId);
            $categoryIds = array();
            if($query->num_rows() > 0){
                foreach ($query->result() as $row) {
                    $categoryIds[] = $row->category_id;
                }
            }
            return $categoryIds;
        }
    }

    function getAllSubCategoryIdsOfInstitute($instituteId) {
        if(!empty($instituteId)) {
            $dbHandle = $this->getReadHandle();
            $courseIdsQuery = "SELECT DISTINCT category_id FROM categoryPageData WHERE institute_id = ? AND status = 'live';";
            $query          = $dbHandle->query($courseIdsQuery,$instituteId);
            $categoryIds = array();
            if($query->num_rows() > 0){
                foreach ($query->result() as $row) {
                    $categoryIds[] = $row->category_id;
                }
            }
            return $categoryIds;
        }
    }

	function getCourseListingsLastUpdatedDate($type="course"){
        
        $dbHandle = $this->getReadHandle();

        $listing_type = "";
        if($type == 'course')
            $listing_type = 'course';
	elseif($type == 'university')
            $listing_type = 'university';    
        else
            $listing_type = 'institute';

        $query    =  " SELECT listing_type_id,IF(max(last_modify_date) = '0000-00-00 00:00:00', CURDATE(),max(last_modify_date)) as latest_date FROM `listings_main` WHERE ".
                     "listing_type = ? ".
                     "and status in ('live','draft','deleted') ".
                     "group by listing_type_id order by listing_type_id asc";

        return $dbHandle->query($query,array($listing_type))->result_array();
    }

    function updateListingsDate($tableName, $whereKey, $data, $whereClauseArr){
        if(!empty($data)){
	    
	$dbHandle = $this->getWriteHandle();
        
        //$dbHandle->where_in('status', array('live','draft'));

        $affectedRows = 0;

        $data = array_chunk($data, 10000);

        foreach ($data as $key=>$dataChunk) {
            error_log("\n".date("d-m-Y h:i:s")."Updating ".$tableName." Batch No.  ".$key, 3,'/tmp/updateDateColumn.log');

            foreach ($whereClauseArr as $value) {
                $dbHandle->where($value[0], $value[1]);
            }
        
            $dbHandle->update_batch($tableName, $dataChunk, $whereKey);

            $affectedRows += $dbHandle->affected_rows();
        }

        

        // complete the transaction : commit
        // $dbHandle->trans_complete();
        
        // if ($dbHandle->trans_status() === FALSE) {
        //     throw new Exception('Transaction Failed');
        // }

        return $affectedRows;
	}
    }
	
	function getApplicationForUrl($courseIds) {
		$dbHandle = $this->getReadHandle();
		if(!(is_array($courseIds)&&count($courseIds)>=1)){
            return;
        }
		$query = "SELECT course_id, application_form_url from course_details where course_id IN (?) AND status = 'live'";
		$queryResult = $dbHandle->query($query,array($courseIds))->result_array();
		foreach($queryResult as $row) {
			$result[$row['course_id']] = $row['application_form_url'];
		}
		
		return $result;
	}
	
    /***
     * functionName : getOnlineFormAllCourses
     * functionType : return an array
     * desciption   : this code is used to make online form config for other course's
     * @author      : akhter
     * @team        : UGC
    ***/
    function getOnlineFormAllCourses()
    {
	$dbHandle = $this->getReadHandle();
	$query = "SELECT `instituteId`, `courseId`, `otherCourses` FROM OF_InstituteDetails where last_date >= DATE(now()) AND status = 'live'";
	return $dbHandle->query($query)->result_array();
    }

    function updateCategoryBoardName($previousName, $finalName, $subcategoryId, $specializationId) {
        $dbHandle = $this->getWriteHandle();
        $dbHandle->trans_start();
        //updating category board table
        $query = "UPDATE categoryBoardTable SET NAME = ? WHERE boardId = ?";
        $dbHandle->query($query, array($finalName, $subcategoryId));
        //update tcourse specialiazation mapping table
        $query = "UPDATE tCourseSpecializationMapping SET CourseName = ? WHERE SpecializationId = ? AND scope = 'india'";
        $dbHandle->query($query, array($finalName, $specializationId));
        $dbHandle->trans_complete();
        
        $query = "SELECT boardId, parentId FROM categoryBoardTable WHERE boardId = ?";
        $subCategoryData = $dbHandle->query($query, $subcategoryId)->result_array();

        if ($dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return array('boardId' => $subCategoryData[0]['boardId'], 
                     'parentId' => $subCategoryData[0]['parentId']);
    }

    public function getAlsoRegisteredCountriesData($startPoint,$chunkSize){
	$dbHandle = $this->getReadHandle();
	$dbHandle->select("tl.UserId, tl.CountryId, ts.DesiredCourse");
	$dbHandle->from("tUserLocationPref tl");
	$dbHandle->join("tUserPref ts","tl.UserId = ts.UserId and ts.PrefId = tl.PrefId","inner");
	$dbHandle->where("tl.CountryId > 2",null,false);
	$dbHandle->where("tl.SubmitDate >",date("Y-m-d",strtotime("-6 month")));
	$dbHandle->where("ts.ExtraFlag","studyabroad");
	$dbHandle->where("tl.Status","live");
	$dbHandle->where("ts.Status","live");
	$dbHandle->limit($chunkSize,$startPoint);
	$resultData = $dbHandle->get()->result_array();
	return $resultData;
    }
    
    public function saveAlsoRegisteredCountriesData($pushArray){
		$dbHandle = $this->getWriteHandle();
		$dbHandle->trans_start();
		$query = "update countriesCoregistrationData set count = 0";
		$dbHandle->query($query);
		$valuesString = array();
		foreach($pushArray as $pushVal){
			$valuesString[] ="('".$pushVal['specializationId']."','".$pushVal['parentCountry']."','".$pushVal['relatedCountry']."','".$pushVal['count']."')";
		}
		$sql="insert into countriesCoregistrationData (specializationId,parentCountry,relatedCountry,count) values ";
		$sql.= implode(',',$valuesString);
		$sql.= " on duplicate key update count = values(count)";
		$dbHandle->query($sql,array());
		$dbHandle->trans_complete();
		if($dbHandle->trans_status() === FALSE){
			throw new Exception("Transaction Failed");
		}
    }

    public function getAllCoursesCategoryWise($categoryIds) {
        if(!(is_array($categoryIds)&&count($categoryIds)>=1)){
            return;
        }
        
        $dbHandle = $this->getReadHandle();
        $query = "SELECT lct.listing_type_id as courseId, cbt.boardId as subcategoryId, cbt.parentId as categoryId, cbt.seoUrlDirectoryName as categoryUrlName, cd.courseTitle as courseName, i.institute_name as instituteName ".
                "FROM listing_category_table lct ".
                "INNER JOIN categoryBoardTable cbt ON (lct.category_id = cbt.boardId and cbt.parentId IN (?)) ".
                "INNER JOIN course_details cd ON (cd.course_id = lct.listing_type_id AND cd.status IN ('live','draft')) ".
                "INNER JOIN institute i ON (i.institute_id = cd.institute_id AND i.status IN ('live','draft') AND i.institute_type NOT IN ('Department', 'Department_Virtual')) ".
                "WHERE lct.status IN ('live','draft') AND lct.listing_type = 'course' AND cbt.seoUrlDirectoryName IS NOT NULL ";
                //"LIMIT 50";
        
        $responseData = $dbHandle->query($query,array($categoryIds))->result_array();
        return $responseData;
    }

    public function checkDuplicateListingEntries($type){
        if(empty($type)){
            return;
        }
        $dbHandle = $this->getReadHandle();

        $query = "SELECT ".
                 "listing_type_id , listing_title, count(listing_type_id) as listingCount ".  
                 "FROM listings_main ".
                 "WHERE ".
                 "listing_type = ? ".
                 "AND status = 'live' ".
                 "GROUP BY listing_type_id ".
                 "HAVING listingCount > 1";
        
        $data = $dbHandle->query($query,array($type))->result_array();
        return $data;

    }
	
	public function getAllUpgradedAbroadCourses(){
		$dbHandle = $this->getReadHandle();
	
		$dbHandle->select('distinct(course_id)');
		$dbHandle->from("abroadCategoryPageData");
		$dbHandle->where_in('pack_type',array(1,7));
		$result = $dbHandle->get()->result_array();
		$courseIDs = array_map(function($a,$b){ return $a['course_id'];},$result);
		//_p($courseIDs);
		
		$dbHandle->select('listing_id,listing_type_id,approve_date,username,pack_type,subscriptionId');
		$dbHandle->from("listings_main");
		$dbHandle->where('listing_type','course');
		$dbHandle->where_in('pack_type',array(1,7));
		$dbHandle->where_in('listing_type_id',$courseIDs);
		$dbHandle->order_by('listing_type_id,listing_id','ASC');
		$result = $dbHandle->get()->result_array();
		//_p($result);
		
        return array('courseId'=>$courseIDs,'result'=>$result);
	}
	
	public function getListingCMSUserTracking($courseIDs){
		$dbHandle = $this->getReadHandle();
		$dbHandle->select('userId,listingId,updatedAt');
		$dbHandle->from("listingCMSUserTracking");
		$dbHandle->where('tabUpdated','listingUpgrade');
		$dbHandle->where_in('listingId',$courseIDs);
		$dbHandle->order_by('listingId,updatedAt','ASC');
		$result = $dbHandle->get()->result_array();
		return $result;
	}
	
	public function subscriptionHistoricalDetails($approve_date,$courseId,$packType,$subscriptionId,$clientId,$subscriptionStartDate,$subscriptionExpiryDate,$addedBy)
	{
		//return;
		$dbHandle = $this->getReadHandle();
		if($addedBy==0){
			$addedBy = 29; //29 is the userID for yaseen@naukri.com
		}
		//update old record with any subscription or packtype as end date today
		$udata = array(
		   'endedOnDate'		=>date('Y-m-d',strtotime($approve_date)),
		   'endedOnTime'		=>date('H:i:s',strtotime($approve_date)),
		   'updatedBy'			=>$addedBy,
		);
		$dbHandle->where('courseId', $courseId);
		$dbHandle->where('endedOnDate', '0000-00-00');
		$dbHandle->where('endedOnTime', '00:00:00');
		$dbHandle->update('courseSubscriptionHistoricalDetails', $udata);
		
		$upgradeHistoryData = array(
								'courseId'				=>$courseId,
								'packType'				=>$packType,
								'subscriptionId'		=>$subscriptionId,
								'clientId'				=>$clientId,
								'subscriptionStartDate' =>$subscriptionStartDate,
								'subscriptionExpiryDate'=>$subscriptionExpiryDate,
								'addedOnDate'			=>date('Y-m-d',strtotime($approve_date)),
								'addedOnTime'			=>date('H:i:s',strtotime($approve_date)),
								'addedBy'				=>$addedBy,
								'source'				=>'abroad'
								);
		$dbHandle->insert('courseSubscriptionHistoricalDetails', $upgradeHistoryData);
		
	}
	
    public function changeNonHeadOfficeLocationStatus($instituteId, $action) {
        $this->logFileName = 'log_remove_non_head_office_location_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
        
        $dbHandle = $this->getWriteHandle();
        
        $sql = "select course_id from course_details where institute_id=? and status = 'live'";
        $data = $dbHandle->query($sql, $instituteId)->result_array();
        foreach ($data as $value) {
            $courseIds[] = $value['course_id'];
        }
        
        $dbHandle->trans_start();
        switch ($action) {
            case 'compress':
                //Mark all locations(except head office location) as staging
                $sql = "UPDATE course_location_attribute set status = 'staging' where course_id in (".implode(',', $courseIds).") and status = 'live' and attribute_type = 'Head Office' and attribute_value='false'";
                $data = $dbHandle->query($sql);
                error_log("Query 1 executed: ".$sql."\n", 3, $this->logFilePath);
                
                $sql = "UPDATE institute_location_table set status = 'staging' where institute_id=? and status='live' and institute_location_id not in (select distinct institute_location_id from course_location_attribute where course_id in (".implode(',', $courseIds).") and status = 'live' and attribute_type = 'Head Office' and attribute_value='true')";
                $data = $dbHandle->query($sql, $instituteId);
                error_log("Query 2 executed: ".$sql."\n", 3, $this->logFilePath);
                break;
            
            case 'restore':
                $sql = "UPDATE course_location_attribute set status = 'live' where status = 'staging' and attribute_type = 'Head Office' and attribute_value='false'";
                $data = $dbHandle->query($sql);
                error_log("Query 1 executed: ".$sql."\n", 3, $this->logFilePath);

                $sql = "UPDATE institute_location_table set status = 'live' where status = 'staging' and institute_id=".$instituteId;
                $data = $dbHandle->query($sql, $instituteId);
                error_log("Query 2 executed: ".$sql."\n", 3, $this->logFilePath);
                break;
            
            default:
                break;
        }
        $dbHandle->trans_complete();

        return $courseIds;
    }

    public function getClientCourseIds($clientId){
         $course_Ids = array();   
         $dbHandle = $this->getReadHandle();
         $sql = "SELECT distinct listing_type_id from listings_main where username = ? and listing_type = 'course' and status = 'live' ";
         $data = $dbHandle->query($sql, array($clientId))->result_array();
         foreach ($data as $value) {
             $course_Ids[] = $value['listing_type_id'];
         }
         return $course_Ids;
     }
}
