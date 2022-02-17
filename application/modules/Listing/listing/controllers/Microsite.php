<?php

/*
   $Rev:: $:  Revision of last commit
   $Author: amitj $:  Author of last commit
   $Date: 2009-03-16 14:18:55 $:  Date of last commit

   $Id: Microsite.php,v 1.2 2009-03-16 14:18:55 amitj Exp $:

*/

class Microsite extends MX_Controller {
    function init() {
        $this->load->helper(array('form', 'url', 'image_helper','date'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','keyPagesClient','lmsLib'));
        $this->userStatus = $this->checkUserValidation();
    }

    function getStudentForm(){
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        $this->init();
        $displayData = array();
        $displayData['validateuser'] = $this->userStatus;

        $displayData['requestedForm'] = '/fairs/studentsForm';
        $this->load->view('fairs/mainContainer',$displayData);
    }

    function getClientForm(){
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        $this->init();
        $displayData = array();
        $displayData['validateuser'] = $this->userStatus;

        $displayData['requestedForm'] = '/fairs/clientEnquiryForm';
        $this->load->view('fairs/mainContainer',$displayData);
    }



    function getConfirmationPage($regId){
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $data =  $LmsClientObj->getRegisteredData($regId);
//        echo "<pre>".print_r($data)."</pre>";
        if(strtolower(trim($data[0]['city'])) == 'delhi'){
            $venue = "ITPO, Pragati Maidan, Delhi";
        }
        else{
            $venue = "Koramangala Indoor Stadium, Bengaluru";
        }
        $userArray['venue'] = $venue;
        $userArray['date'] = $data[0]['date'];
        $userArray['regId'] = $regId;
        $this->load->view('fairs/confirmation.php',$userArray);
 
//        echo "<html><head><title>Confimation for Education fair</title></head><body>Dear Student,<br/><br/>Thank you for registering for the Shiksha.com Education Fair.<br/>Your unique ID (<b>$regId</b>) has been generated.Please  bring a print out of this ticket to gain entry to the event.<br/><br/>Date:<b>".$data[0]['date']."</b><br/><br/>Venue:<b>".$venue."</b><br/><br/>Regards<br/>Shiksha.com team<br/></body><script>window.load=window.print();</script></html>";


    }

    function registerClientForFair() {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $userarray = array();
        $userarray['appId'] = 1;
        $userarray['name'] = $this->input->post('reqInfoDispName');
        $userarray['email'] = $this->input->post('reqInfoEmail');
        $userarray['mobile'] = $this->input->post('reqInfoPhNumber');
        $userarray['city'] = $this->input->post('city');
        $userarray['instituteName'] = $this->input->post('instituteName');
        $userarray['informationRequired'] = $this->input->post('informationRequired');
        $regId = $LmsClientObj->registerClient(base64_encode(serialize($userarray)));
        echo $regId;
    }

    function registerStudentForFair() {
        $appId = 1;
        $this->init();
        $LmsClientObj = new LmsLib();
        $userarray = array();
        $userarray['appId'] = 1;
        $userarray['name'] = $this->input->post('reqInfoDispName');
        $userarray['email'] = $this->input->post('reqInfoEmail');
        $userarray['mobile'] = $this->input->post('reqInfoPhNumber');
        $userarray['educationLevel'] = $this->input->post('interestedIn');
        $userarray['categories'] = $this->input->post('board_id');
        $userarray['city'] = $this->input->post('city');
        $userarray['date'] = strtotime($this->input->post("date_".strtolower($userarray['city'])));
        $regId = $LmsClientObj->registerStudent(base64_encode(serialize($userarray)));
        if(strtolower(trim($userarray['city'])) == 'delhi'){
            $venue = "ITPO, Pragati Maidan, Delhi";
        }
        else{
            $venue = "Koramangala Indoor Stadium, Bengaluru";
        }
        $smsText = "Thank you for registering with Shiksha.com Education Fair.Your unique id is $regId Venue: ".$venue." Date: ".date('jS F',$userarray['date']);
        $alertsClientObj = new Alerts_client();
        $alertsClientObj->addSmsQueueRecord('1',$userarray['mobile'],$smsText,rand(-1000,-1));

        $userArray['venue'] = $venue;
        $userArray['regId'] = $regId;
        $userArray['date'] = $this->input->post("date_".strtolower($userarray['city']));

        $emailText = $this->load->view('fairs/confirmation',$userArray,true);
//        $emailText = "<html><body><b>Dear Student,</b><br/><br/>Thank you for registering for the Shiksha.com Education Fair.<br/>Your unique ID (<b>$regId</b>) has been generated.Please  bring a print out of this ticket to gain entry to the event.<br/><br/>Date:".$userarray['date']."<br/><br/>Venue:".$venue."<br/><br/>Regards<br/>Shiksha.com team<br/></body></html>";
        $alertsClientObj->externalQueueAdd('1','fairs@shiksha.com',$userarray['email'],'Confirmation for Education Fair Shiksha',$emailText,'html');

        echo $regId;
    }
}
?>
