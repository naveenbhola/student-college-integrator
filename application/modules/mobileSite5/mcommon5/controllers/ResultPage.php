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
                $data['userStatus'] = $this->userStatus;
                $data['boomr_pageid'] = 'mobilesite_JeeResultPage';
                $data['jeeResultPageURL'] = $this->jeePageURL;

                if($data['userStatus'] == "false"){
                        //Redirect to MMP Page
                        $url = $this->mmpPageURL;
                        header("Location: $url",TRUE,302);
                        exit;
                }

                //Code used for Beacon tracking
                $data['beaconTrackData'] = array(
                                               'pageIdentifier' => 'jeeResultPage',
                                               'extraData' => array('url'=>get_full_url())
                                           );
                $data['beaconTrackData']['extraData']['countryId'] = 2;
                $data['trackForPages'] = true;

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
