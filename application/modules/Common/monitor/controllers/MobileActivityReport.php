<?php

class MobileActivityReport extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->mobileActivityLib = $this->load->library('mobileActivityLib');
    }
    
    public function mobileActivityReport(){
        $data = $this->mobileActivityLib->prepareDataForMobileActivityReport();
        $graphData = array();
        foreach($data as $key=>$value){
            if($value['boomr_pageid']!='' && $value['boomr_pageid']!='0' && $value['boomr_pageid']!=null && $value['boomr_pageid']!='SA_PAGE_UNDEFINED')
            {
                $graphData[$value['boomr_pageid']]['View Count'][$value['date']]                   = ($value['TotalViewCount']);
                //$graphData[$value['boomr_pageid']]['Avg. Page Load Time'][$value['date']]          = round(($value['avgPageLoadTime']/1000),3);
                //$graphData[$value['boomr_pageid']]['Avg. Head Load Time'][$value['date']]          = round(($value['avgHeadLoadTime']/1000),3);
                $graphData[$value['boomr_pageid']]['Avg. Server Processing Time'][$value['date']]  = round(($value['avgServerProcessingTime']/1000),3);
            }
        }
        $this->config->load('mobileReportConfig');
        $config_page_name   = $this->config->item('page_name_title');
        $pdfData= array();
        foreach($graphData as $pageName=>$detailData)
        {
            $pageTitle = $config_page_name[$pageName];
            if($pageTitle ==''){
                $pageTitle = $pageName;
            }
            $norMalisedResult = $this->normalizationOfViewCount($detailData['View Count']);
            $viewCountAndNorMalzationString = "Page Views (x ".pow(10, $norMalisedResult['norMalzationFactor']).")";
            
            $detailData[$viewCountAndNorMalzationString] = $norMalisedResult['result'];
            foreach($detailData as $key=>$value){
                $pdfData[$pageTitle][$key] = reset(array_reverse($value));
            }
            unset($detailData['View Count']);
            $pdfData[$pageTitle]['imgPath'] = $this->mobileActivityLib->prepareGraphImage($pageName,$detailData);
        }

        $sortArray = array_flip(array_values($config_page_name));
        uksort($pdfData,function($a,$b) use ($sortArray){
            if(empty($sortArray[$a]) && !empty($sortArray[$b])){
                return 1;
            }elseif(empty($sortArray[$b]) && !empty($sortArray[$a])){
                return -1;
            }else{
            return $sortArray[$a] > $sortArray[$b]?1:-1;
            }
            });

        $body = $this->load->view('mobileReport/pdfContents.php',array("pdfData"=>$pdfData),true);
        $coverPageData = $this->mobileActivityLib->getCoverPageData();
        $header = $this->load->view("monitor/mobileReport/pdfCoverPage",$coverPageData,true);
        $footer = $this->load->view("monitor/mobileReport/pdfFooter",array(),true);
        $pdfUrl = $this->mobileActivityLib->preparePDF($header,$body,$footer);
        foreach($pdfData as $key=>$value){
            unlink($value['imgPath']);
        }
        $this->sendMailToPeople($pdfUrl);
    }
    
    public function normalizationOfViewCount($viewCountData){
        
        //$avgViewCount = ceil(array_sum($viewCountData)/count($viewCountData));
        $maxCount = $avgViewCount = max($viewCountData);
        if($avgViewCount >10)
        {
                $norMalzationFactor = 1;
                while(ceil($avgViewCount/10) >10){
                    $avgViewCount = ceil($avgViewCount/10);
                    $norMalzationFactor++;
                }
        }else{
            $norMalzationFactor = 0;
        }
       	 if($maxCount > 5 && $maxCount < 10){ $norMalzationFactor = 1;}
	else if($maxCount > 50 && $maxCount < 100){ $norMalzationFactor = 2;}	
    else if($maxCount > 500 && $maxCount < 1000){ $norMalzationFactor = 3;}
	else if($maxCount > 5000 && $maxCount < 10000){ $norMalzationFactor = 4;}
	else if($maxCount > 50000 && $maxCount < 100000){ $norMalzationFactor = 5;}
    
        $normalizedViewCount = array();
        foreach($viewCountData as $key=>$value){
            $normalizedViewCount[$key] = ($value/pow(10,$norMalzationFactor));
        }
        
       return array("norMalzationFactor"=>$norMalzationFactor,"result"=>$normalizedViewCount);
    }
    
    public function sendMailToPeople($pdfUrl){
        $lib = $this->load->library("alerts_client");
        echo $pdfUrl;
        $mailAttachmentContent = file_get_contents($pdfUrl);
        $filename = "report.pdf";
        $type = date('His').rand(1, 9999);
        $attachmentId = $lib->createAttachment("12", $type, 'guide', 'Guide', $mailAttachmentContent, $filename, 'pdf','false',$pdfUrl);
        $lib->externalQueueAdd( "12",
                                SA_ADMIN_EMAIL,
                                "satech@shiksha.com",
                                "Report: Mobile Activity Log",
                                "Hi,<br/>Please find the report pdf attached.<br/><br/>Regards,<br/>Team Shiksha",
                                "html",
                                '',
                                'y',
                                array($attachmentId),
                                "",
                                "");
    }
}
