<?php

/**
* Class ListingFeedModel extends the Model class for the shiksha
*
* @package ListingFeedModel
* @author shiksha team
*/

class ListingFeedModel extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    
    /**
    * @param object $appId default 1
    * @param object listingconfig
    * @return object
    */
    
    function getDbHandle() {
        // Reserved Keyword for SHIKSHA Tech. will be used later
        $appId = 1; 
        // $obj =& get_instance();
        $this->load->library('listingconfig');
        $dbConfig = array( 'hostname'=>'localhost');
        $this->listingconfig->getDbConfig_test($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        if($dbHandle == ''){
            error_log_shiksha('error occurred...can not create db handle');
        }
        return $dbHandle;
    }
    
    /**
    * @param object $dbHandle
    * @param string $type
    * @param string $timestamp
    * @param string $limit
    * @return array
    */
   
    function get_data_listing_xml_feed($dbHandle,$type,$timestamp,$flag,$queryCmd_offset,$queryCmd_rows)
    {
        
        //$this->benchmark->mark('code_start');
        $result = array();
        if ($type == 'institute') {
            $queryCmdMain = 'select * from  institute,listings_main LEFT JOIN listing_contact_details ON (listings_main.contact_details_id = listing_contact_details.contact_details_id) where listings_main.listing_type_id =institute.institute_id and listings_main.listing_type = "institute" and listings_main.submit_date >= ?  and  listings_main.status="live" and institute.status = "live" ';
            $queryCmdMain .= "  LIMIT ? , ? ";
						
            $querymain = $dbHandle->query($queryCmdMain,array($timestamp, $queryCmd_offset, $queryCmd_rows));
            $i = 0;
            foreach ($querymain->result() as $rowmain) {
                $institute_id                 = $rowmain->institute_id;
                $result[$i]['recordID']       = $rowmain->institute_id;
                $result[$i]['Email']          = htmlspecialchars($rowmain->contact_email);
                $result[$i]['Description']    = htmlspecialchars($rowmain->institute_name);
                $result[$i]['Websiteaddress'] = htmlspecialchars($rowmain->website);
                $result[$i]['Affilitaedto']   = htmlspecialchars($rowmain->certification);
                $result[$i]['ContactNumber']  = htmlspecialchars($rowmain->contact_cell);
				
				//paid user.
				$paidPackTypeArray = array(1,2,7);
                if (in_array($rowmain->pack_type,$paidPackTypeArray)) {
                    $pack_type = 'Paid'; 
                } else {
                    $pack_type = 'UnPaid'; 
                }
                $result[$i]['PackType'] = $pack_type;
                $queryCmd ='select countryCityTable.city_name as City, countryTable.name , ilt.address_1, ilt.address_2, ilt.address_3 from countryCityTable,countryTable,institute_location_table ilt,listings_main lm where lm.listing_type_id = ilt.institute_id and lm.status in ("live") and ilt.status in ("live") and lm.listing_type="institute" and ilt.country_id = countryTable.countryId and countryCityTable.city_id=ilt.city_id and ilt.institute_id=?';
                $queryTemp = $dbHandle->query($queryCmd,$institute_id);
                $locationArrayTemp = array();
                $j=0;
                foreach ($queryTemp->result() as $rowTemp) {
                    $locationArrayTemp[$j]['CompleteAddress'] = htmlspecialchars($rowTemp->address_1).' '.htmlspecialchars($rowTemp->address_2).' '.htmlspecialchars($rowTemp->address_3);
                    $locationArrayTemp[$j]['City']            = htmlspecialchars($rowTemp->City);
                    $locationArrayTemp[$j]['Country']         = htmlspecialchars($rowTemp->name);
                $j++;    
                }
                $result[$i]['location'][] = $locationArrayTemp;
                unset($locationArrayTemp);
                $sqlcourse = 'select course_details.*,listing_contact_details.*, group_concat(distinct(REPLACE(c1.name,",","-"))) as CourseCategory,group_concat(REPLACE(c2.name,",","-")) as CourseSubCategory from course_details,listing_category_table lct,institute_courses_mapping_table, listings_main LEFT JOIN listing_contact_details ON (listings_main.contact_details_id = listing_contact_details.contact_details_id) ,categoryBoardTable c1 left join categoryBoardTable c2 on c1.boardId=c2.parentId where listings_main.listing_type_id = course_details.course_id and listings_main.listing_type = "course" and listings_main.status="live" and institute_courses_mapping_table.course_id = course_details.course_id and c1.parentId=1 and c2.boardId = lct.category_id and institute_courses_mapping_table.course_id= lct.listing_type_id and lct.listing_type = "course" and lct.status in ("live") and institute_courses_mapping_table.institute_id = ? and course_details.status in ("live") group by course_id';
                $resultcourse = array();
                
                $querycourse = $dbHandle->query($sqlcourse,$institute_id);
                    $k=0;
                    foreach ($querycourse->result() as $rowcourse) {
                        $cid= htmlspecialchars($rowcourse->course_id);

                        $sqlAff="select attribute, value from course_attributes where status='live' and attribute IN ('AICTEStatus','UGCStatus','DECStatus','AffiliatedTo','AffiliatedToName','AffiliatedToIndianUni','AffiliatedToIndianUniName','AffiliatedToForeignUni','AffiliatedToForeignUniName','AffiliatedToDeemedUni','AffiliatedToAutonomous') and course_id= ?";
                        $queryAff=$dbHandle->query($sqlAff,$cid);
                        $affiliated='Affiliated to ';
                        $approvedby='';
                        foreach($queryAff->result() as $rowAff)
                        {
                            if( strpos($rowAff->attribute,'Name') !== false)
                            $affiliated.=$rowAff->value;

                            switch($rowAff->attribute)
                            {
                                case 'AffiliatedToDeemedUni' :
                                                        $affiliated='Deemed University';
                                                        break;
                                case 'AffiliatedToAutonomous' :
                                                        $affiliated='Autonomous University';
                                                        break;
                            }

                            if( strpos($rowAff->attribute,'UGC') !== false)
                            $approvedby='UGC';

                            if( strpos($rowAff->attribute,'AICTE') !== false)
                            $approvedby='AICTE';

                            if( strpos($rowAff->attribute,'DEC') !== false)
                            $approvedby='DEC';


                        }
                        if($affiliated == 'Affiliated to ')
                        $affiliated='';


                        $sqlElig="select bt.blogTitle as Title from blogTable bt, listingExamMap lem where lem.type='course' and lem.typeId= ? and lem.examId = bt.blogId and lem.status='live' and bt.status = 'live'";
                        $queryElig = $dbHandle->query($sqlElig, $cid);

                            $elig='';
                            $ek=0;
                            foreach($queryElig->result() as $rowElig)
                            {
                                if($ek == 0)
                                $elig.=$rowElig->Title;
                                else
                                $elig.=', '.$rowElig->Title;

                                $ek++;
                            }

                        $resultcourse[$k]['CourseName'] = htmlspecialchars($rowcourse->courseTitle);
                        $resultcourse[$k]['recordID']   = htmlspecialchars($rowcourse->course_id);
                        $resultcourse[$k]['Duration']   = htmlspecialchars($rowcourse->duration_value." ".$rowcourse->duration_unit);
						if ($rowcourse->fees_value > 0) {
                        	$resultcourse[$k]['CourseFee'] = htmlspecialchars($rowcourse->fees_unit." ".$rowcourse->fees_value);
						} else {
							$resultcourse[$k]['CourseFee'] = "";
						}
						
                        $resultcourse[$k]['STARTDATE'] = htmlspecialchars($rowcourse->date_course_comencement);
						$start_date_course             = $rowcourse->date_course_comencement;
						$timestampForStartDate         = strtotime($start_date_course);
						if(($timestampForStartDate != "") && ($timestampForStartDate != 0)){
							if(($rowcourse->duration_unit == 'Years') || ($rowcourse->duration_unit == 'Year')){
								$end_date = strtotime(date("Y-m-d", strtotime($start_date_course)) . " +".$rowcourse->duration_value." year");
							}else if($rowcourse->duration_unit == 'Weeks'){
								$end_date = strtotime(date("Y-m-d", strtotime($start_date_course)) . " +".$rowcourse->duration_value." week");
							}else if($rowcourse->duration_unit == 'Months'){
								$end_date = strtotime(date("Y-m-d", strtotime($start_date_course)) . " +".$rowcourse->duration_value." months");
							}else if($rowcourse->duration_unit == 'Days'){
								$end_date = strtotime(date("Y-m-d", strtotime($start_date_course)) . " +".$rowcourse->duration_value." days");
							}else if($rowcourse->duration_unit == 'Hours'){
								$end_date = strtotime(date("Y-m-d", strtotime($start_date_course)) . " +".$rowcourse->duration_value." hours");
							}
							$resultcourse[$k]['ENDDATE'] = htmlspecialchars(date('Y-m-d H:i:s',$end_date));
						}else{
							$resultcourse[$k]['STARTDATE'] = "";
							$resultcourse[$k]['ENDDATE']   = "";	
						}
						/* Extra Params aded by Bhuvnesh Pratap; 23 Mar 2011 */
                                                $tempfsd= explode('-',htmlspecialchars($rowcourse->date_form_submission));
                                                if( intval($tempfsd[0]) != 0)
                                                $resultcourse[$k]['formSubmissionDate']       = htmlspecialchars($rowcourse->date_form_submission);
                                                elseif(intval($tempfsd[0]) == 0)
                                                $resultcourse[$k]['formSubmissionDate']       = '';

                                                $temprdd=explode('-',htmlspecialchars($rowcourse->date_result_declaration));
                                                if(intval($temprdd[0]) != 0)
                                                $resultcourse[$k]['resultDeclarationDate']    = htmlspecialchars($rowcourse->date_result_declaration);
                                                elseif(intval($temprdd[0]) == 0)
                                                $resultcourse[$k]['resultDeclarationDate']    = '';

                                                $resultcourse[$k]['Eligibility'] = $elig;
                                                $resultcourse[$k]['Affiliation'] = $affiliated;
                                                $resultcourse[$k]['ApprovedBy'] = $approvedby;

                                                 /*  Changes ENd; 22 Mar 2011 */

                                                $resultcourse[$k]['ContactInfo']       = htmlspecialchars($rowcourse->contact_cell);
						$resultcourse[$k]['Level']             = htmlspecialchars($rowcourse->course_level);
						$resultcourse[$k]['Type']              = htmlspecialchars($rowcourse->course_type);
						$resultcourse[$k]['CourseSubCategory'] = htmlspecialchars($rowcourse->CourseSubCategory);
						$resultcourse[$k]['CourseCategory']    = htmlspecialchars($rowcourse->CourseCategory);
						$k++;
                    }
                $result[$i]['courses'][] = $resultcourse;
                unset($resultcourse);
                $i++;
                mysql_free_result($querycourse->result_id);
                mysql_free_result($querymain->result_id);
                mysql_free_result($queryTemp->result_id);  
            }
        }
        //$this->benchmark->mark('code_end');
        //error_log('get_data_listing_xml_feed',$this->benchmark->elapsed_time('code_start', 'code_end').'|'.$this->benchmark->memory_usage());
        return $result;
    }
    
    /**
    * @param object $dbHandle
    * @param string $type
    * @param string $timestamp
    * @param string $limit
    * @return array
    */
    
    function getEventDetails($dbHandle,$type,$timestamp,$flag,$queryCmd_offset,$queryCmd_rows)
    {
        //$this->benchmark->mark('code_start1');
        $result = array();
        if ($type == 'event') {
            $queryCmd = 'select v.venue_name,v.Address_Line1,v.Address_Line2,v.phone,v.mobile,v.contact_person,e.event_id,e.event_title,ect.boardId,e.description,v.country, e.fromOthers , e.listingType, e.listing_type_id, d.start_date,d.end_date,v.city,co.name as country, ci.city_name , group_concat(distinct c1.name SEPARATOR "|") as CourseCategory, group_concat(distinct REPLACE(c2.name,",","-") SEPARATOR ",") as CourseSubCategory from event e, event_date d, event_venue v, countryTable co, countryCityTable ci, event_venue_mapping evm,eventCategoryTable ect,categoryBoardTable c1 inner join categoryBoardTable c2 on c1.boardId=c2.parentId where ect.eventId=e.event_id and (ect.boardId = c2.boardId) and evm.event_id = e.event_id and e.event_id=d.event_id and co.countryId = v.country  and ci.city_id = v.city and evm.event_id = e.event_id and evm.venue_id=v.venue_id and e.status_id is NULL and c1.parentId=1 and e.creationDate>= ? group by event_id';
            $queryCmd .= "  LIMIT ? , ? ";
            $query = $dbHandle->query($queryCmd, array($timestamp, $queryCmd_offset, $queryCmd_rows));
            $i = 0;
            foreach ($query->result_array() as $row) {
                $result[$i]['event_id']=htmlspecialchars($row['event_id']);
                $result[$i]['event_title']=htmlspecialchars($row['event_title']);
                $result[$i]['description']=htmlspecialchars($row['description']);
                $result[$i]['start_date']=htmlspecialchars($row['start_date']);
                $result[$i]['end_date']=htmlspecialchars($row['end_date']);
                $result[$i]['city_name']=htmlspecialchars($row['city_name']);
                $result[$i]['country_name']=htmlspecialchars($row['country']);
                $result[$i]['venue_name']=htmlspecialchars($row['venue_name']);
                $result[$i]['Address_Line1']=htmlspecialchars($row['Address_Line1']);
                $result[$i]['Address_Line2']=htmlspecialchars($row['Address_Line2']);
                $result[$i]['phone']=htmlspecialchars($row['phone']);
                $result[$i]['mobile']=htmlspecialchars($row['mobile']);
                $result[$i]['contact_person']=htmlspecialchars($row['contact_person']);
                $result[$i]['CourseSubCategory'] = htmlspecialchars($row['CourseSubCategory']);
                $result[$i]['CourseCategory'] = htmlspecialchars($row['CourseCategory']);
            $i++;
            }
            mysql_free_result($query->result_id);
            unset($queryCmd);
        }    
        //$this->benchmark->mark('code_end1');
        //error_log('getEventDetails',$this->benchmark->elapsed_time('code_start1', 'code_end1').'|'.$this->benchmark->memory_usage());
        return $result;
    }
}

?>
