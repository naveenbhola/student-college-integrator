<?php

class ListingDebugger extends MX_Controller
{
    function index()
    {
        $user = $this->checkUserValidation();
        if(is_array($user) && is_array($user[0]) && $user[0]['userid'] == 11) {
            $isValid = TRUE;
        }
        else {
            show_404();
        }
        
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle();
       
        $listingId = intval($_REQUEST['listingId']);
        $listingType = $_REQUEST['listingType'];
        
        $data = array();
        if($listingId && $listingType) {
            
            $data['listingId'] = $listingId;
            $data['listingType'] = $listingType;
            $data['tables'] = array();
            
            if($listingType == 'course') {
                $tables = array(
                                'listings_main' => array('listing_type_id','listing_type'),
                                'course_details' => array('course_id'),
                                'listing_contact_details' => array('listing_type_id','listing_type'),
                                'course_location_attribute' => array('course_id'),
                                'listing_attributes_table' => array('listing_type_id','listing_type'),
                                'listing_media_table' => array('type_id','type'),
                                'header_image' => array('listing_id','listing_type'),
                                'listingExamMap' => array('typeid','type'),
                                'othersExamTable' => array('listingTypeId','listingType'),
                                'clientCourseToLDBCourseMapping' => array('clientCourseID'),
                                'course_attributes' => array('course_id'),
                                'course_features' => array('listing_id'),
                                'company_logo_mapping' => array('listing_id','listing_type')
                            );
            }
            else if($listingType == 'institute') {
                $tables = array(
                            'listings_main' => array('listing_type_id','listing_type'),
                            'institute' => array('institute_id'),
                            'institute_location_table' => array('institute_id'),
                            'listing_contact_details' => array('listing_type_id','listing_type'),
                            'listing_attributes_table' => array('listing_type_id','listing_type'),
                            'listing_media_table' => array('type_id','type'),
                            'header_image' => array('listing_id','listing_type'),
                            'institute_join_reason' => array('institute_id')
                        );
            }
            
            foreach($tables as $tableName => $tableColumns) {
                $listingTypeIdColumnName = $tableColumns[0];
                $listingTypeColumnName = $tableColumns[1];
                $param = array();
                $param[] = $listingId;
                if($listingTypeColumnName) {
                    $param[] = $listingType;
                }
                $sql = "SELECT *
                        FROM $tableName
                        WHERE ".$listingTypeIdColumnName." = ?
                        ".($listingTypeColumnName ? " AND ".$listingTypeColumnName." = ? " : " ")."
                        ";
                $query = $dbHandle->query($sql, $param);
                $data['tables'][$tableName] = $query->result_array();
            }
        }
        $this->load->view('listing/debugger',$data);
    }
}
