<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/10/18
 * Time: 12:50 PM
 */

class clientActivationLib
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->clientActivationModel = $this->CI->load->model("dashboard/clientactivationmodel");
    }

    public function saveClientActivationFormData($formData){
        return $this->clientActivationModel->saveClientActivationFormData($formData);
    }

    public function getClientActivationData($univId){
        $result = $this->clientActivationModel->getClientActivationData($univId);
        if(!empty($result)) {
            return $result[0];
        }
        else{
            return $result;
        }
    }

    public function getClientActivationTableData($paginatorObj){
        $result = $this->clientActivationModel->getClientActivationTableData($paginatorObj);

        foreach ($result['data'] as $key => $val){
            $totalCommitment = json_decode($val['total_commitment'],true);
            $result['data'][$key]['total_commitment'] = $totalCommitment['totalCount'];
        }

        return $result;
    }

    //edit flag represents it is an edit form = false or add from = true
    public function validateUniversityAndGetCourses($univId,$editFlag){
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 				= new ListingBuilder;
        $abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $resultForUniversity = $this->clientActivationModel->getClientActivationData($univId);
        if(!empty($resultForUniversity) && $editFlag){
            //error represents entry already exists for this univId
            $response['error'] = 3;
            $response['data'] = array();
        }
        else {
            $result = $abroadUniversityRepository->getCoursesOfUniversities(array($univId), 'ALL');
            if($result==null){
                //no universtiy exist with this univId
                $response['error'] = 1;
            }
            elseif (empty($result[$univId]['course_title_list'])){
                //no course listed for this university
                $response['error'] = 2;
            }
            else {
                //some network error occurred
                $response['error'] = 0;
            }
            $response['data'] = $result[$univId]['course_title_list'];
        }

        return json_encode($response);
    }

    public function getAllMonthNameData(){
        $returnArr = array();
        for($i=1;$i<=12;$i++)
        {
            $d = date_create_from_format('n',$i);
            $returnArr[$i] = $d->format('M');
        }
        return $returnArr;

    }
    public function getYearArray(){
        $yearArr = array();
        $currYear = date("Y");
        $startYear = $currYear-3;
        $endYear = $currYear+3;
        for($year=$startYear; $year<=$endYear; $year++){
            array_push($yearArr,$year);
        }
        return $yearArr;
    }

}

?>