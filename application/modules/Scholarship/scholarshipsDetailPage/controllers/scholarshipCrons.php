<?php

class scholarshipCrons extends MX_Controller {
    
    public function buildScholarshipCache(){
        error_log("Scholarship cache building started at ".date("h:i:sa"));
        $startTime = microtime(TRUE);
        $this->scholarshipmodel = $this->load->model('scholarshipsDetailPage/scholarshipcronsmodel');
        $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $this->scholarshipBuilder         = new scholarshipBuilder();
        $this->scholarshipRepository     = $this->scholarshipBuilder->getScholarshipRepository();
        $limit = 5000;
        $offset = 0;
        $this->scholarshipRepository->disableCaching();
        while(1){
            $scholarshipIds = $this->scholarshipmodel->getScholarshipIds($offset,$limit);
            if(!empty($scholarshipIds)){
                $this->scholarshipRepository->findMultiple($scholarshipIds);
            }
            
            if(count($scholarshipIds)<$limit){
                break;
            }
            $offset = $offset + $limit;
            error_log("Scholarship Cache build for $offset");
        }
        $endTime = microtime(TRUE);
        $timeTaken = $endTime-$startTime;
        error_log("Scholarship cache building completed at ".date("h:i:sa"));
        error_log("Success time taken in building scholarship cache: $timeTaken");
    }
}
