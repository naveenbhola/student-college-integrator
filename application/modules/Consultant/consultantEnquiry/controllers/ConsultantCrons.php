<?php
/*
 * controller class for consultant generation & related fuctionalities
 */ 
class consultantCrons extends MX_Controller
{
    // construct
    function __construct()
    {
        parent::__construct();
        $this->uploadClient = $this->load->library('common/upload_client');
    }
    
    function uploadRecording() {
        
        //error_log("::YES SaveMediaData::CONCRONS _FILES".print_r($_FILES,true));
        $res = $this->uploadClient->uploadFile(1,"recording",$_FILES,array(),"-1","consultantRecording","somefile");
        echo serialize($res);
        // $upload = $uploadClient->uploadFile($appId, 'image', $_FILES,array(), "-1", "cat_page_articles_widget", 'latestNewsPicture');
    }
    
    public function consultantMailerToUsers(){
        $date = date("Y-m-d");
        $consultantEnquiryLib = $this->load->library('ConsultantEnquiryLib');
        $consultantEnquiryLib->sendConsultantMailToUsers($date);
        echo 'SUCCESS';
        exit(0);
    }
    /*
     * This function is called by a background process that runs after one would :
     * case 1. assign region subscription :
     *      This function would check for a valid consultant
     *      university mapping (i.e. one in which the consultant has a valid subscription)
     * case 2. assign consultant subscription/ upgrade consultant:
     *      This function would check if the consultant is being assigned a subscription -
     *      a) for the first time. (based on whether the form was opened in add mode).
     *      b) because last one got expired. (for this it will check if last subscription was expired,
     *          If it did, then it will get those consultant-univ-region assignment that were added
     *          after the expiry of last subscription) 
     *
     * @param: $data = array(
     *                       regionAssignment => {consultant id, university id, region id[1+],mode},
     *                       OR
     *                       subscriptionAssignment => {consultant id, mapping id to determine mode in which form was opened},
     *                      ); [base64 & json encoded format]
     * @returnVal:  array(consultant id), array(university id), array(region id);
     *      case 1. array(consultant id[1]), array(university id[1]), array(region id[1]);
     *      case 2. 
     *          a) array(consultant id[1]), array(university id[0]), array(region id[0]);
     *          b) array(consultant id[1]), array(university id[1+]), array(region id[1+]);
     */
    public function callToPrepareConsultantMailersForUsers($data)
    {
        $mailString = $data;
        //$this->sendExecutionMailToTeam('Start', $mailString);

        if($data == "")
        {
            echo "\n Invalid Parameters";
        }
        else
        {
            $consultantEnquiryLib   = $this->load->library('consultantEnquiry/ConsultantEnquiryLib');
            $consultantPageLib      = $this->load->library('consultantProfile/ConsultantPageLib');
            $consultantPostingLib   = $this->load->library('consultantPosting/ConsultantPostingLib');
            
            $data = json_decode(base64_decode($data),true);
            // check if this is case 1. assign region subscription
            if(isset($data['regionAssignment']) && $data['regionAssignment']['mode'] == 'assignRegionForm')
            {
                //check if the consultant has an active subscription
                $consultantSubscription = $consultantPageLib->validateSubscriptionData($data['regionAssignment']['consultantId']);
                if($consultantSubscription === false)
                {   echo "\n Invalid Subscription/ no credits left";
                }
                else{
                    // send call to process this consultant,univ,region(s) combination
                    $consultantEnquiryLib->prepareConsultantMailersForUsers(
                                           array($data['regionAssignment']['consultantId']),
                                           array($data['regionAssignment']['universityId']),
                                           $data['regionAssignment']['regionId'] // this is itself an array as multiple regions can be assigned from the interface
                                           );
                }
            }
            else if(isset($data['subscriptionAssignment'])){
                // check the mode in which the form was opened
                if($data['subscriptionAssignment']['mappingId'] == 0 )
                {   // for add mode simply send the consultantId
                    $univsMapped = $consultantPostingLib->getConsultantUniversityCitySubscriptionDataForConsultant($data['subscriptionAssignment']['consultantId']);
                    if(count($univsMapped)>0){ // check if this consultant has any univs mapped to it, with regions assigned
                        $consultantEnquiryLib->prepareConsultantMailersForUsers(array($data['subscriptionAssignment']['consultantId']));
                    }
                }
                else{
                    // for edit mode get end date of last subscription using mapping id
                    $consultantSubscription = $consultantPostingLib->getConsultantSubscriptionByClientSubscription($data['subscriptionAssignment']['mappingId']);
                    $this->load->library('subscription_client');
                    $objSumsProduct =  new Subscription_client();
                    // get its subscription details ..
                    $subscriptionDetails = $objSumsProduct->getMultipleSubscriptionDetails(CONSULTANT_CLIENT_APP_ID,array($consultantSubscription['subscriptionId']));
                    // check if subscription has actually expired
                    if($subscriptionDetails[0]['SubscriptionEndDate'] != ''  &&
                       strtotime($subscriptionDetails[0]['SubscriptionEndDate']) < strtotime(date('Y-m-d H:i:s'))
                       )
                    {   $consultantIds = $universityIds = $regionIds = array();
                        // find new cons-univ-region assignments
                        $newConsultantRegionAssignments = $consultantPostingLib->getConsultantRegionAssignmentAfterDate($subscriptionDetails[0]['SubscriptionEndDate'],$data['subscriptionAssignment']['consultantId']);
                        foreach($newConsultantRegionAssignments as $k=>$row)
                        {
                            if(in_array($row['consultantId'],$consultantIds)== FALSE)
                            {
                                $consultantIds[] = $row['consultantId'];
                            }
                            if(in_array($row['universityId'],$universityIds)== FALSE)
                            {
                                $universityIds[] = $row['universityId'];
                            }
                            if(in_array($row['regionId'],$regionIds)== FALSE)
                            {
                                $regionIds[] = $row['regionId'];
                            }
                        }
                        // send data for mailer generation
                        $consultantEnquiryLib->prepareConsultantMailersForUsers(
                                           $consultantIds,
                                           $universityIds,
                                           $regionIds
                                           );
                    }
                }
            }
        }
        //$this->sendExecutionMailToTeam('End', $mailString);
    }
    public function prepareConsultantMailersForUsersOneTimeActivity($consultantId='',$universityId='',$regionId='') {
        $dayDurationForMail = -6;
        $date = date("Y-m-d", strtotime("+".$dayDurationForMail."month"));
        //echo 'date: '.$date;die;
        $consultantEnquiryLib = $this->load->library('ConsultantEnquiryLib');
        $consultantEnquiryLib->prepareConsultantMailersForUsers($consultantId,$universityId,$regionId,$date,'Y');
        echo 'SUCCESS';
        exit(0);
    }
    public function sendExecutionMailToTeam($status, $dataString='')
    {
        $data = json_decode(base64_decode($dataString),true);
        $lib = $this->load->library("alerts_client");
        $lib->externalQueueAdd( "12",
                                SA_ADMIN_EMAIL,
                                "amit.kuksal@shiksha.com",
                                "Background Process Execution : ".$status,
                                print_r($data,true),
                                "html",
                                '',
                                'n',
                                array(),
                                "saurabh.bhardwaj@shiksha.com",
                                "abhinav.pandey@shiksha.com");
        if($status == 'End')
        {
            exit(0);
        }
    }
}
