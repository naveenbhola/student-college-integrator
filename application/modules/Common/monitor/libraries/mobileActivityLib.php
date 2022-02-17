<?php

class mobileActivityLib  {
        var $CI = '';

    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }
    
    private function _setDependecies()
    {
        $this->mobileActivityModel     = $this->CI->load->model('mobileactivitymodel');
        //_p($this->mobileActivityModel);
    }
        
    public function prepareDataForMobileActivityReport(){
    
    return $this->mobileActivityModel->getDataForMobileActivityReport();
    
    }
    
    public function prepareGraphImage($pageName,$graphData){
        
        $pcharter = $this->CI->load->library('Pchart');
        
        $this->CI->config->load('mobileReportConfig');
        $palette_color      = $this->CI->config->item('palette_color');
        $config_page_name   = $this->CI->config->item('page_name_title');
        
        if(array_key_exists($pageName,$config_page_name)){
            $pageNameTitle = $config_page_name[$pageName];
        }else{
            $pageNameTitle = $pageName;
        }
        /* Add data in your dataset */
        $myData = $pcharter->pData();
        $dateArray = array();
        $count=0;
        foreach($graphData as $key=>$value)
        {
           $myData->addPoints(array_values($value)  ,$key);
           $dateArray = array_keys($value);
           $myData->setPalette($key,$palette_color[$count]);
           $count++;
        }
        $myData->setAxisName(1,"Date");
        $myData->addPoints($dateArray,'Labels');
        $myData->setAbscissa("Labels");
    
        /* create image object*/
        $myImage = $pcharter->pImage(860, 220, $myData);
        /* choose font */
        $myImage->setFontProperties(array(
                                    "FontName" => APPPATH.'third_party/chart/fonts/verdana.ttf',
                                    "FontSize" => 8));
        
        //CODE FOR GRADIENT AND GRID IN IMAGE
        $Settings = array("R"=>179, "G"=>217, "B"=>91, "Dash"=>1, "DashR"=>199, "DashG"=>237, "DashB"=>111);
        $myImage->drawFilledRectangle(0,0,860,220,$Settings);
        $Settings = array("StartR"=>194, "StartG"=>231, "StartB"=>44, "EndR"=>43, "EndG"=>107, "EndB"=>58, "Alpha"=>50);
        $myImage->drawGradientArea(0,0,860,220,DIRECTION_VERTICAL,$Settings);
        $myImage->drawGradientArea(0,0,860,0,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
              
        
        /* Define the boundaries of the graph area */
        $myImage->setGraphArea(80,20, 665,190);
        /* Draw the scale, keep everything automatic */ 
        $myImage->drawScale();
        /* Draw the graph */ 
        $myImage->drawLineChart();
        /* lable text */
        $pageNameTitle = '';
        $myImage->drawText(300,50,$pageNameTitle,array("R" => 0, "G" => 64, "B" => 255, "FontSize" => 9));
        $myImage->drawLegend(665,50, 
                                array("R" => 220, "G" => 220, "B" => 220,
                                      "FontR" => 0, "FontG" => 64, "FontB" => 255,
                                      "BorderR" => 80, "BorderG" => 80, "BorderB" => 80,
                                      "FontSize" => 9, "Family" => LEGEND_FAMILY_CIRCLE));
          
        /* set header for image */
        //header("Content-Type: image/png");
        //$myImage->Render(null);
        $path = "static_mediadata/listingsBrochures/reports/".$pageName.".png";
        $myImage->render($path);
        return $path;
    }
    
        public function getCoverPageData(){
                $yesterday = date('Y-m-d',time()-60*60*24);
                $data = $this->mobileActivityModel->getCoverPageData($yesterday);
                // Total visits is the total number of distinct sessionIds
                // Page Views is the sum of count(*)s
                // Pages/Visit is a function of the above two
                $processedData = array();
                foreach($data as $row){
                        if(empty($processedData[$row['sourceSite']]['pageViews'])){
                                $processedData[$row['sourceSite']]['pageViews'] = 0;
                                $processedData[$row['sourceSite']]['totalVisits'] = 0;
                        }
                        $processedData[$row['sourceSite']]['pageViews'] += $row['vCount'];
                        $processedData[$row['sourceSite']]['totalVisits']+=1;
                }
                $domestic['totalVisits'] = $processedData['national']['totalVisits'];
                $domestic['pageViews'] = $processedData['national']['pageViews'];
                $domestic['pagesPerVisit'] = $domestic['pageViews']/$domestic['totalVisits'];
                
                $abroad['totalVisits'] = $processedData['abroad']['totalVisits'];
                $abroad['pageViews'] = $processedData['abroad']['pageViews'];
                $abroad['pagesPerVisit'] = $abroad['pageViews']/$abroad['totalVisits'];
                $fData = array('domestic'=>$domestic,'abroad'=>$abroad);
                return $fData;
        }
    
        public function preparePDF($header,$body,$footer){
            $filesFolder = "/var/www/html/shiksha/static_mediadata/listingsBrochures/reports/";
            $footerAddress = $filesFolder."footer.html";
            $bodyAddress = $filesFolder."main.html";
            $pdfAddress = $filesFolder."report.pdf";
            if(file_exists($pdfAddress)){
                unlink($pdfAddress);
            }
            
            $this->writeInFile($footerAddress,$footer);
            $this->writeInFile($bodyAddress,$header.$body);
            shell_exec("/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 0 --margin-bottom 19 --footer-html $footerAddress $bodyAddress $pdfAddress");
            unlink($footerAddress);
            unlink($bodyAddress);
            return MEDIADATA_INTERNAL_DOMAIN."static_mediadata/listingsBrochures/reports/report.pdf";
        }
    
        function writeInFile($file, $content) {
                if($file == "") {
                        return ;
                }
                $fp = fopen($file, 'w');
                fwrite($fp, $content);
                fclose($fp);
        }
}
?>
