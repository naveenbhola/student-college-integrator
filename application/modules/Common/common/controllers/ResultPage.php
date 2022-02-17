<?php
   /*

   Copyright 2017 Info Edge India Ltd
   This is file for Exams Result page

   */
class ResultPage extends MX_Controller {
        private $userStatus = 'false';
        private $mmpPageURL = 'http://www.shiksha.com/marketing/Marketing/form/pageID/2846';
        private $jeePageURL = 'http://jeemain.nic.in';

        function init(){
                $this->userStatus = $this->checkUserValidation();
        }

        function jeeResultPage(){
                $this->init();
                $appId = 12;
                $data = array();
                $data['validateuser'] = $this->userStatus;
                $data['jeeResultPageURL'] = $this->jeePageURL;

                if($data['validateuser'] == "false"){
                        //Redirect to MMP Page
                        $url = $this->mmpPageURL;
                        header("Location: $url",TRUE,302);
                        exit;
                }

                //below code used for beacon tracking
                $data['trackingpageIdentifier'] = 'jeeResultPage';
                $data['trackingcountryId']=2;
                $this->tracking=$this->load->library('common/trackingpages');
                $this->tracking->_pagetracking($data);

                //Remarketing Variables
                $data['gtmParams'] = array(
                        "pageType"        => "JEEResultPage",
                        "countryId"       => "2",
                        "baseCourseId"    => "10",
                        "stream"    => "2",
                        "educationType" => "20"
                );

                $this->load->view('jeeResultPage',$data);
        }

}
?>
