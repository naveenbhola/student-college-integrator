<?php

class ListingReports extends MX_Controller
{
    public function showMBADashboard(){

        $displayData     = array();
        $this->usermodel = $this->load->model("user/usermodel");
        $validateuser    = $this->checkUserValidation();
        if(($validateuser == "false") || $validateuser[0]['usergroup'] != 'cms'){
            _p("Access Denied. You don't have enough permissions to access this page.");
            return false; exit(0);
        }

        $displayData['startDate'] = $this->input->post("fromDate");
        $displayData['endDate']   = $this->input->post("toDate");
        $displayData['isAjax']    = $this->input->post("isAjax");

        if(!empty($displayData['startDate']) && !empty($displayData['endDate']))
        {
            $lastYearStartDate        = date("d-m-Y", strtotime($displayData['startDate']." -1 year"));
            $lastYearEndDate          = date("d-m-Y", strtotime($displayData['endDate']." -1 year"));
            
            $startTime = microtime(true);
            $currentRegistrationData  = $this->usermodel->getMBARegistrationCount($displayData['startDate'],$displayData['endDate']);

            error_log("\nFor Period".$displayData['startDate']." - ".$displayData['endDate']." Stage 1 Time taken : ".(microtime(true) - $startTime).date("d-m-y h:i:s")."'),",3,"/tmp/mbaDashboardQuery.log");
            $startTime = microtime(true);

            $previousRegistrationData = $this->usermodel->getMBARegistrationCount($lastYearStartDate,$lastYearEndDate);
            error_log("\nFor Period".$displayData['startDate']." - ".$displayData['endDate']." Stage 2 Time taken : ".(microtime(true) - $startTime).date("d-m-y h:i:s")."'),",3,"/tmp/mbaDashboardQuery.log");
            $startTime = microtime(true);
            
            $currentResponseData      = $this->usermodel->getReponseData($displayData['startDate'],$displayData['endDate']);

            error_log("\nFor Period".$displayData['startDate']." - ".$displayData['endDate']." Stage 3 Time taken : ".(microtime(true) - $startTime).date("d-m-y h:i:s")."'),",3,"/tmp/mbaDashboardQuery.log");
            $startTime = microtime(true);

            $previousResponseData     = $this->usermodel->getReponseData($lastYearStartDate,$lastYearEndDate);

            error_log("\nFor Period".$displayData['startDate']." - ".$displayData['endDate']." Stage 4 Time taken : ".(microtime(true) - $startTime).date("d-m-y h:i:s")."'),",3,"/tmp/mbaDashboardQuery.log");
            $startTime = microtime(true);
            
            $displayData['reportData'] =  array( "reg_count_current" => $currentRegistrationData,
                                                "reg_count_previous" => $previousRegistrationData,
                                                "response_count_current" => $currentResponseData,
                                                "response_count_previous" => $previousResponseData);
            // _p($lastYearStartDate);
        }
        
        if($displayData['isAjax'])
            $this->load->view("reports/mbaDashboardContent", $displayData);
        else
            $this->load->view("reports/mbaDashboard", $displayData);
    }
}
