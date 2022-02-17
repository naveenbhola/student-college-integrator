<?php

class dashboard extends MX_Controller
{     
  private $salesOperationLib;
  private $appName;
  function __construct()
  {
        parent::__construct();
        $this->load->config('salesOpsConfig');
        $this->salesOperationLib = $this->load->library('splice/salesOperationLib');
        $this->appName = $this->config->item('appName');
  }

  public function login(){
    $user = $this->salesOperationLib->checkValidUser(true);
    if((integer)($user[0]['userid']) > 0){
          $this->salesOperationLib->updateLastLogin($user[0]['userid']);
          $this->load->config('salesOpsConfig');
          $leftMenuArray = $this->config->item("leftMenuArray");
          header("Location: ".$leftMenuArray[$user[0]['groupId']]['Dashboard']['children']['Home']);
          die;
    }else if($user === false){
          $data['appName'] = $this->appName;
          $this->load->view('splice/loginPage',$data);
          return true;
    }else{
          // What even happened?
          show_404_abroad();
          return false;
    }
  }

  public function unauthorizedUser(){
    $this->load->view('splice/unauthorizedUser');
  }

  private function _loadDependecies($metric,$pageName='') {
        $data['userDataArray'] = reset($this->salesOperationLib->checkValidUser());
        $leftMenuArray = $this->config->item("leftMenuArray");
        $data['leftMenuArray'] = $leftMenuArray[$data['userDataArray']['groupId']];
        $data['teamGroupIds'] = array(
          'salesTeam' => array(1,2,3),
          'salesOps' => array(4,5,6),
          'design' => array(7,8,9),
          'contentOps' => array(10,11,12,13,14),
          'admin' => array(15,16)
          );
        //_p($leftMenuArray);die;
        return $data;
  }

  public function addNewRequest($requestType = 'mailer'){
    $data = $this->_loadDependecies();
    
    $data['source'] = 'addNewRequest';
    $data['requestType'] = $requestType;
    $allowableGroupIds = array('1','2','3');
    if(in_array($data['userDataArray']['groupId'],$allowableGroupIds)){
      if($requestType == 'mailer'){
        $data['courseListForMailerRequest'] = $this->config->item("courseListForMailerRequest");        
        $data['mailerType'] = $this->config->item("mailerType");
      }else{
        $data['bannerSize'] = $this->config->item("bannerSize");
        $data['requestData']['bannerSize'] = count($data['bannerSize']);
        $data['campaignType'] = $this->config->item("campaignType");      
        $data['requestData']['campaignSizeCount'] = count($data['campaignType']);
      }
      $data['requestData']['requestFields'] = $this->config->item('requestFields');
      $data['requestDisplayName'] = $this->config->item("requestDisplayName");
      $data['keyName'] = $this->appName;
      $this->load->view('splice/salesOpsTemplate', $data);     
    }else{
      redirect('splice/dashboard/login', 'refresh');
    }
  }

  public function team($teamName){
    $data = $this->_loadDependecies();
    $data['source'] = 'dashboard';
    //_p($data);die;
    $data['topTiles'] = $this->_getTopTiles($data['userDataArray']['groupId'],$data['teamGroupIds']);
    $data['keyName'] = $this->appName;
    //_p($data['topTiles']);die;
    $this->load->view('splice/salesOpsTemplate', $data);
  }

  private function _getTopTiles($groupId,$teamGroupIds){
    $assignTaskFilterArray = array('allAssignedTask','assignedTaskTATExpired','pendingAssignedTask','assignedTaskTATToday','assignedTaskTATTomorrow');
    if($groupId > 0){
      if(in_array($groupId,$teamGroupIds['salesTeam'])){
        $topTilesData = array(
            'requestedCreated' => array(
                'topTileHeading'  => 'Request Created',
                'topTileDetails'  => 'Total Requests created by user.',
                'iconClass'       => 'fa-caret-square-o-right',
                'id'              => 'requestCreated',
                'href'            => SHIKSHA_HOME.'/splice/dashboard/viewRequest/allCreatedRequest'
              ),
              'pendingRequest' => array(
                'topTileHeading'  => 'Pending Tasks',
                'topTileDetails'  => 'Tasks that are not completed yet.',
                'iconClass'       => 'fa-comments-o',
                'id'              => 'pendingRequest',
                'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingCreatedTask'
              ),
              'pendingForClientApproval' => array(
                'topTileHeading'  => 'Pending For Client Approval',
                'topTileDetails'  => 'Tasks pending for Client Approval.',
                'iconClass'       => 'fa-sort-amount-desc',
                'id'              => 'pendingForClientApproval',
                'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingCreatedTaskForClientApproval',
              ),
              'TATExpired' => array(
                'topTileHeading'  => 'TAT Expired',
                'topTileDetails'  => 'TAT Date expired.',
                'iconClass'       => 'fa-check-square-o',
                'id'              => 'TATExpired',
                'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/createdTaskTATExpired',
              ),          
            );
      }else if(in_array($groupId,$teamGroupIds['admin'])){
        $topTilesData = array(
          'Created Requests Summary' => array(
            'requestedCreated' => array(
              'topTileHeading'  => 'Request Created',
              'topTileDetails'  => 'Total Requests created by user.',
              'iconClass'       => 'fa-caret-square-o-right',
              'id'              => 'requestCreated',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewRequest/allCreatedRequest'
            ),
            'pendingRequest' => array(
              'topTileHeading'  => 'Pending Tasks',
              'topTileDetails'  => 'Tasks that are not completed yet.',
              'iconClass'       => 'fa-comments-o',
              'id'              => 'pendingRequest',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingCreatedTask'
            ),
            'pendingForClientApproval' => array(
              'topTileHeading'  => 'Pending For Client Approval',
              'topTileDetails'  => 'Tasks pending for Client Approval.',
              'iconClass'       => 'fa-sort-amount-desc',
              'id'              => 'pendingForClientApproval',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingCreatedTaskForClientApproval',
            ),
            'TATExpired' => array(
              'topTileHeading'  => 'TAT Expired',
              'topTileDetails'  => 'TAT Date expired.',
              'iconClass'       => 'fa-check-square-o',
              'id'              => 'TATExpired',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/createdTaskTATExpired',
            ),          
          ),
          'Assigned Request Task Summary' => array(
            'taskAssigned' => array(
              'topTileHeading'  => 'Task Assigned',
              'topTileDetails'  => 'Total Tasks assigned to User(s).',
              'iconClass'       => 'fa-caret-square-o-right',
              'id'              => 'taskAssigned',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/allAssignedTask',
            ),
            'TATExpired' => array(
              'topTileHeading'  => 'TAT Expired',
              'topTileDetails'  => 'TAT Date expired.',
              'iconClass'       => 'fa-check-square-o',
              'id'              => 'TATExpiredForOtherTeam',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATExpired',
            ) ,
            'pending' => array(
              'topTileHeading'  => 'Pending Task',
              'topTileDetails'  => 'Tasks that are not completed yet.',
              'iconClass'       => 'fa-comments-o',
              'id'              => 'pendingTask',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingAssignedTask',
            ),
            'TATToday' => array(
              'topTileHeading'  => 'TAT Today',
              'topTileDetails'  => 'Tasks for which TAT Date ending Today.',
              'iconClass'       => 'fa-sort-amount-desc',
              'id'              => 'TATToday',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATToday',
            ),
            'TATTomorrow' => array(
              'topTileHeading'  => 'TAT Tomorrow',
              'topTileDetails'  => 'Tasks for which TAT Date ending Tomorrow.',
              'iconClass'       => 'fa-check-square-o',
              'id'              => 'TATTomorrow',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATTomorrow',
            ),
          ),          
        );
      }else{
        $topTilesData = array(
          'taskAssignedToUser' => array(
            'taskAssigned' => array(
              'topTileHeading'  => 'Task Assigned',
              'topTileDetails'  => 'Total Tasks assigned to User(s).',
              'iconClass'       => 'fa-caret-square-o-right',
              'id'              => 'taskAssigned',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/allAssignedTask',
            ),
            'TATExpired' => array(
              'topTileHeading'  => 'TAT Expired',
              'topTileDetails'  => 'TAT Date expired.',
              'iconClass'       => 'fa-check-square-o',
              'id'              => 'TATExpiredForOtherTeam',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATExpired',
            )
          ),
          'pendingTaskToUser'=> array(
            'pendingTask' => array(
              'topTileHeading'  => 'Pending Tasks',
              'topTileDetails'  => 'Tasks that are not completed yet.',
              'iconClass'       => 'fa-comments-o',
              'id'              => 'pendingTask',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/pendingAssignedTask',
            ),
            'TATToday' => array(
              'topTileHeading'  => 'TAT Today',
              'topTileDetails'  => 'Tasks for which TAT Date ending Today.',
              'iconClass'       => 'fa-sort-amount-desc',
              'id'              => 'TATToday',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATToday',
            ),
            'TATTomorrow' => array(
              'topTileHeading'  => 'TAT Tomorrow',
              'topTileDetails'  => 'Tasks for which TAT Date ending Tomorrow.',
              'iconClass'       => 'fa-check-square-o',
              'id'              => 'TATTomorrow',
              'href'            => SHIKSHA_HOME.'/splice/dashboard/viewTask/assignedTaskTATTomorrow',
            )
          )
        );
      }
    }
    //_p($topTilesData);die;
    return $topTilesData;
  }

  public function manageMember(){
    $data = $this->_loadDependecies();        
    $groupAccess = $this->config->item("groupAccess");
    $groupAccess = $groupAccess[$data['userDataArray']['groupId']];
    // For Adding New Member
    $groupIdsForSalesBranches = array('2','3','15','16');
    if(in_array($data['userDataArray']['groupId'], $groupIdsForSalesBranches)){
      $data['branchDetails'] = $this->config->item("salesBranches");      
    }
	asort($data['branchDetails']);
    if(count($groupAccess['addMember']) > 0){
          $groupDetails = $this->salesOperationLib->getGroupDetails($groupAccess['addMember']);
    }
    $data['groupDetails'] = $groupDetails;

    // View All Member Data                
    $viewMemberDetails['userType'] = $groupAccess['userType'];
    $viewMemberDetails['viewType'] = $groupAccess['viewMember'];
    //_p($viewMemberDetails);die;
    $membersDetails = $this->salesOperationLib->getUserDetailsForGroup($viewMemberDetails,$data['userDataArray']['userid']);
    
    //$data['membersDetails'] = $membersDetails;
    $data['source'] = 'manageMembers';
    
    // prepare Data Table
    $dataTable['heading'] = 'Members Detail';
    $dataTable['thead'] = array('User Name','Email Id','Added By','Added On','Last Login Date','isActive');
    $dataTable['coloumWidth'] = array("20","15","10","12","12","8");
    $dataTable['tbody'] = $membersDetails;
    //$dataTable['tbody'] = $membersDetails['tableDetails'];
    //$dataTable['showingTotalRows'] = $membersDetails['totalRows'];
    $data['dataTable'] = $dataTable;
    $data['dataTable']['isOnPageLoad'] = true;
    //_p($data['dataTable']);die;
    $data['keyName'] = $this->appName;
    $this->load->view('splice/salesOpsTemplate', $data);
  }

  public function checkIfExistUser($email){
    echo json_encode($this->salesOperationLib->checkIfExistUser($email));
  }

  public function checkIfListingIsValid($site,$listingId){
    echo json_encode($this->salesOperationLib->checkIfListingIsValid($site,$listingId));
  }

  public function checkIfLandingPageURLIsValid(){
    $landingPageURL = $this->input->post('landingPageURL');
    $site = $this->input->post('site');        
    echo $this->salesOperationLib->checkIfLandingPageURLIsValid($landingPageURL,$site);
  }

  private function _getInputData(){
    $inputArray = array();
    $loggedInUserDetails = $this->_loadDependecies();
    $inputArray['loggedInUserName'] = $loggedInUserDetails['userDataArray']['firstname'].' '.$loggedInUserDetails['userDataArray']['lastname'];
    $loggedInUserDetails = explode('|', $loggedInUserDetails['userDataArray']['cookiestr']);
    $inputArray['loggedInUserEmail'] = $loggedInUserDetails[0];
    //_p($inputArray['loggedInUserEmail']);die;
    $inputArray['newRequestType'] = $this->input->post('newRequestType');
    $inputArray['clientName'] = $this->input->post('clientName');
    $inputArray['salesOrderNo'] = $this->input->post('salesOrderNo');
    $inputArray['userId'] = $this->input->post('userId');
    $inputArray['campaignLiveDate'] = $this->input->post('campaignLiveDate');
    $inputArray['defaultAssignee'] = $this->config->item("defaultAssignee");
    if($inputArray['newRequestType'] == 'mailer'){
      $inputArray['requestTitle'] = $this->input->post('requestTitle');
      $inputArray['requestType'] = $this->input->post('requestType');
      $inputArray['site'] = $this->input->post('site');
      $inputArray['course'] = $this->input->post('course');
      $inputArray['description'] = $this->input->post('description');
      if($this->input->post('mailerType')){
        $inputArray['mailerType'] = $this->input->post('mailerType');
      }

      $inputArray['defaultAssigneeForRequest'] = $inputArray['defaultAssignee']['mailer']['userId'];
      $inputArray['emailId'] = $inputArray['defaultAssignee']['mailer']['email'];
      $inputArray['notificationMailId'] = $inputArray['defaultAssignee']['mailer']['notificationMailTo'];
    }else if($inputArray['newRequestType'] == 'other'){
      $inputArray['userIds'] = array();
      $isAnyRequestIsSelected = false;
      if($this->input->post('listingRequest')){
        $isAnyRequestIsSelected = true;
        $listingRequestParameter = json_decode($this->input->post('listingRequestParameter'));
        $listingRequestParameter = (array)$listingRequestParameter;
        $listingRequestParameter['description'] = urldecode(base64_decode($listingRequestParameter['description']));
        $inputArray['listingRequest'] = $listingRequestParameter;
        $inputArray['listingRequest']['defaultAssigneeForRequest'] = $inputArray['defaultAssignee']['listing'][$inputArray['listingRequest']['site']]['userId'];
        $inputArray['userIds'][] = $inputArray['listingRequest']['defaultAssigneeForRequest'];
        $inputArray['listingRequest']['emailId'] = $inputArray['defaultAssignee']['listing'][$inputArray['listingRequest']['site']]['email'];
        $inputArray['listingRequest']['notificationMailId'] = $inputArray['defaultAssignee']['listing'][$inputArray['listingRequest']['site']]['notificationMailTo'];
      }
      
      if($this->input->post('bannerRequest')){
        $isAnyRequestIsSelected = true;
        $bannerRequestParameter = json_decode($this->input->post('bannerRequestParameter'));
        $bannerRequestParameter = (array)$bannerRequestParameter;    
        $bannerRequestParameter['description'] = urldecode(base64_decode($bannerRequestParameter['description']));
        $inputArray['bannerRequest'] = $bannerRequestParameter;        
        $inputArray['bannerRequest']['defaultAssigneeForRequest'] = $inputArray['defaultAssignee']['banner']['userId'];
        $inputArray['userIds'][] = $inputArray['bannerRequest']['defaultAssigneeForRequest'];
        $inputArray['bannerRequest']['emailId'] = $inputArray['defaultAssignee']['banner']['email'];
        $inputArray['bannerRequest']['notificationMailId'] = $inputArray['defaultAssignee']['banner']['notificationMailTo'];
      }

      if($this->input->post('shoshkeleRequest')){
        $isAnyRequestIsSelected = true;
        $shoshkeleRequestParameter = json_decode($this->input->post('shoshkeleRequestParameter'));
        $shoshkeleRequestParameter = (array)$shoshkeleRequestParameter;
        $shoshkeleRequestParameter['description'] = urldecode(base64_decode($shoshkeleRequestParameter['description']));
        $inputArray['shoshkeleRequest'] = $shoshkeleRequestParameter;
        $inputArray['shoshkeleRequest']['defaultAssigneeForRequest'] = $inputArray['defaultAssignee']['shoshkele']['userId'];
        $inputArray['userIds'][] = $inputArray['shoshkeleRequest']['defaultAssigneeForRequest'];
        $inputArray['shoshkeleRequest']['emailId'] = $inputArray['defaultAssignee']['shoshkele']['email'];
        $inputArray['shoshkeleRequest']['notificationMailId'] = $inputArray['defaultAssignee']['shoshkele']['notificationMailTo'];
      }
    
      if($this->input->post('campaignActivationRequest')){
        $isAnyRequestIsSelected = true;
        $campaignActivationRequestParameter = json_decode($this->input->post('campaignActivationRequestParameter'));
        $campaignActivationRequestParameter = (array)$campaignActivationRequestParameter;
        $campaignActivationRequestParameter['description'] = urldecode(base64_decode($campaignActivationRequestParameter['description']));
        $inputArray['campaignActivationRequest'] = $campaignActivationRequestParameter;
        $inputArray['campaignActivationRequest']['defaultAssigneeForRequest'] = $inputArray['defaultAssignee']['campaignActivation']['userId'];
        $inputArray['userIds'][] = $inputArray['campaignActivationRequest']['defaultAssigneeForRequest'];
        $inputArray['campaignActivationRequest']['emailId'] = $inputArray['defaultAssignee']['campaignActivation']['email'];
        $inputArray['campaignActivationRequest']['notificationMailId'] = $inputArray['defaultAssignee']['campaignActivation']['notificationMailTo'];
      }

      if(!$isAnyRequestIsSelected){
        $inputArray['status'] = 0;
        $inputArray['error'] = 'Please select atLeast one task';
      }
    }
    return $inputArray;
  }

  public function addNewUserToInterface(){
    $this->_loadDependecies();
    $inputData = $this->input->post('inputData');
    $insertId = $this->salesOperationLib->addNewMemberToInterface($inputData);
    if($insertId > 0){
          $mailData['userName'] = $inputData['userName'];              
          $mailData['email'] = $inputData['email'];
          $mailData['to'] = array($inputData['email']);
          $response = $this->salesOperationLib->prepareMailData($mailData,$this->appName,'newMemberAdded');
    }
    echo json_encode($insertId);
  }

  public function submitNewRequest(){
    $this->_loadDependecies();
    $inputArray = $this->_getInputData();

    $isValidCampaignDate = $this->_validateCampaignData($inputArray['campaignLiveDate']);
    if($isValidCampaignDate == true){
    $exitFlag = false;
    $inputArray['defaultTAT'] = $this->config->item("defaultTAT");
    $inputArray['currentStatus'] = 'inProgress';
    $requestDisplayName = $this->config->item("requestDisplayName");
    //save new request
    if($inputArray['newRequestType'] == 'mailer'){
      $inputArray['mailerTypeForMailer'] = $this->config->item("mailerType");
      $inputArray['courseListForMailerRequest'] = $this->config->item("courseListForMailerRequest");

      // upload Attachment
      if(count($_FILES)){
        $uploadDocResponse = $this->uploadAttachment();
        if($uploadDocResponse['status'] == 0){
          echo json_encode($uploadDocResponse);
          exit;
        }else{
          $inputArray['attachmentURL'] = $uploadDocResponse[0]['imageurl'];          
          }
        }

      //save new request data to DB
      $response = $this->salesOperationLib->saveNewRequest($inputArray);
      if($response['status'] > 0){
        // get user details
        $userDetails = $this->salesOperationLib->getUserDetails($inputArray['defaultAssigneeForRequest']);
        $mailData['to'] = array($inputArray['defaultAssignee']['mailer']['email']); // $mailData['defaultAssignee']
        $mailData['cc'] = array($inputArray['defaultAssignee']['mailer']['notificationMailTo'],$inputArray['loggedInUserEmail']); //// groupEmail,email
        $mailData['userName'] =  $userDetails[$inputArray['defaultAssigneeForRequest']]['userName'];// get from database
        $mailData['requestId'] = $response['requestId'];//
        $mailData['requestTaskId'] = $response['mailerRequestTaskId'];;
        $mailData['salesOrderNo'] = $inputArray['salesOrderNo'];
        $mailData['requestTitle'] = $inputArray['requestTitle'];
        $mailData['campaignDate'] = $inputArray['campaignLiveDate'];
        $mailData['clientName'] = $inputArray['clientName'];
        $mailData['TATDate'] = $response['TATDate'];
        $mailData['loggedInUserName'] = $inputArray['loggedInUserName'];
        $mailData['taskCategory'] = $requestDisplayName['Mailer'];
        $mailResponse = $this->salesOperationLib->prepareMailData($mailData,$this->appName,'newRequestAdded');
      }

    }else if($inputArray['newRequestType'] == 'other'){
      // upload Attachment      
      if(count($_FILES)){
        $uploadDocResponse = $this->uploadAttachment();
        if($uploadDocResponse['status'] == 0){
          echo json_encode($uploadDocResponse);
          exit;
        }
      }

      unset($uploadDocResponse['status']);
      unset($uploadDocResponse['max']);
      foreach ($uploadDocResponse as $key => $value) {
        $inputArray['attachmentURLs'][$value['title']] = $value['imageurl'];
      }

      //save new request data to DB      
      $response = $this->salesOperationLib->saveNewRequest($inputArray);
      if($response['status'] > 0){
        // get user details
        $inputArray['userIds'] = array_unique($inputArray['userIds']);
        $userDetails = $this->salesOperationLib->getUserDetails($inputArray['userIds']);
        //_p($response);die;
        foreach ($response['taskDetails'] as $taskType => $taskDetails) {
          if($taskType == 'bannerRequest'){
            $to = $inputArray['defaultAssignee']['banner']['email'];
            $cc= $inputArray['defaultAssignee']['banner']['notificationMailTo'];
            $mailData['taskCategory'] = $requestDisplayName['Banner'];
          }else if($taskType == 'shoshkeleRequest'){
            $to = $inputArray['defaultAssignee']['shoshkele']['email'];
            $cc= $inputArray['defaultAssignee']['shoshkele']['notificationMailTo'];
            $mailData['taskCategory'] = $requestDisplayName['Shoshkele'];
          }else if($taskType == 'campaignActivationRequest'){
            $to = $inputArray['defaultAssignee']['campaignActivation']['email'];
            $cc= $inputArray['defaultAssignee']['campaignActivation']['notificationMailTo'];
            $mailData['taskCategory'] = $requestDisplayName['Campaign Activation'];
          }else if($taskType == 'listingRequest'){
            $to = $inputArray['defaultAssignee']['listing'][$inputArray['listingRequest']['site']]['email'];
            $cc= $inputArray['defaultAssignee']['listing'][$inputArray['listingRequest']['site']]['notificationMailTo'];
            $mailData['taskCategory'] = $requestDisplayName['Listing'];
            $mailData['listingId'] = $inputArray['listingRequest']['listingId'];
            $mailData['listingURLType'] = $inputArray['listingRequest']['listingURLType'];
            $mailData['site'] = $inputArray['listingRequest']['site'];
          }

          $mailData['to'] = array($to); // $mailData['defaultAssignee']
          $mailData['cc'] = array($cc,$inputArray['loggedInUserEmail']); //// groupEmail,email
          $mailData['userName'] =  $userDetails[$inputArray[$taskType]['defaultAssigneeForRequest']]['userName'];// get from database
          $mailData['requestId'] = $response['requestId'];//
          $mailData['requestTaskId'] = $taskDetails['requestTaskId'];
          $mailData['salesOrderNo'] = $inputArray['salesOrderNo'];
          $mailData['requestTitle'] = $inputArray[$taskType]['requestTitle'];
          $mailData['campaignDate'] = $inputArray['campaignLiveDate'];
          $mailData['clientName'] = $inputArray['clientName'];
          $mailData['TATDate'] = $taskDetails['TATDate'];
          $mailData['loggedInUserName'] = $inputArray['loggedInUserName'];
          $mailResponse = $this->salesOperationLib->prepareMailData($mailData,$this->appName,'newRequestAdded');
        }
      }      
      }
      echo json_encode($response);
    }else{
      $response = array("status"=>0,"error"=>"Campaign date should be greater than or equal to current date.");
      echo json_encode($response);
    }
    exit;    
  }

	public function _validateCampaignData($campaignLiveDate){
    $currentDate=date_create(date('Y-m-d'));
    $campaignActivationDate=date_create($campaignLiveDate);
    $diff=date_diff($campaignActivationDate,$currentDate);
    if($diff->days >0 && $diff->invert == 0){
      return false;
    }
    return true;
  }

  public function uploadAttachment(){
    $appId =1;          
    $this->load->library('upload_client');
    $uploadClient = new Upload_client();            

    if(isset($_FILES)) //check uploaded file
    {
      $uploadDocument = $uploadClient->uploadFile($appId,'spliceMedia',$_FILES,'spliceDocument',"-1",'spliceDocument','spliceMedia');
      if($uploadDocument['status'] == 1){
        $response = $uploadDocument;
      }else{
        $response['status'] = 0;
        $response['error'] = $uploadDocument;          
      }
      return $response;
    }
  }

  public function viewRequest($filter=''){
    $data = $this->_loadDependecies();
    if($filter){
      $data['leftMenuArray']['Dashboard']['children']['View Request'] .= '/'.$filter;
    }
    //_p($data);die;
    $groupIdsForViewingRequestTable = array_merge($data['teamGroupIds']['salesTeam'],$data['teamGroupIds']['salesOps'],$data['teamGroupIds']['admin']);
    if(in_array($data['userDataArray']['groupId'],$groupIdsForViewingRequestTable)){
      $groupAccess = $this->config->item("groupAccess");
      $groupAccess = $groupAccess[$data['userDataArray']['groupId']];
      $accessDetails['userType'] = $groupAccess['userType'];
      $accessDetails['viewType'] = $groupAccess['viewRequest'];
      $data['salesBranches'] = $this->config->item('salesBranches');
      $data['courseListForMailerRequest'] = $this->config->item('courseListForMailerRequest');
      $data['currentStatusFilter'] = array(
        'clientApprovedAndClosed' => 'Client Approved & Closed',
        'inProgress' => 'In Progress',
        'cancel' => 'Cancelled'
        );
      $data['isCheckForUserIds'] = false;
      if($filter != ''){
        if($filter == 'allCreatedRequest'){
          if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['admin'])){
            $requestIds = array();
          }else if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
            $accessDetails['viewType'] = $groupAccess['viewDashboard'];
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $result = $this->salesOperationLib->getRequestCreatedByUsers($userIds);
            $requestIds = array();
            foreach ($result as $key => $value) {
              $requestIds[] = $value['id'];
            }
            $data['isCheckForUserIds'] = true;
          }else{
            header("Location: ".$data['leftMenuArray']['Dashboard']['children']['Home']);
            die;
          }
        }else{
          header("Location: ".$data['leftMenuArray']['Dashboard']['children']['Home']);
          die;
        }
      }else{
        if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesOps'])){
          $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
          $data['isCheckForUserIds'] = true;
          if(count($userIds) > 0){
            $taskIds = $this->salesOperationLib->getTaskIdsForGivenUserIds($userIds);
            if(count($taskIds) >0){
              $requestIds = $this->salesOperationLib->getRequestIdForTaskIds($taskIds);  
            }else{
              $requestIds = array();
            }
          }else{
            $requestIds = array();
          }
            
          //_p($requestIds);die;
        }else{
          $viewAllRequestGroupIds = array(3,15,16);
          if(!in_array($data['userDataArray']['groupId'], $viewAllRequestGroupIds)){
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $data['isCheckForUserIds'] = true;
            if(count($userIds) > 0){
              $requestIds = $this->salesOperationLib->getRequestIdsForViewDashboardData($userIds);    
            }else{
              $requestIds = array();
            }
          }
        }
      }

      $dataTable['heading'] = 'Request Details';
      $dataTable['thead'] = array('S.No.','Request Id','Sales Order No','Campaign Date','Creation Date','Created By','Status');
      $dataTable['coloumWidth'] = array("3","5","45","10","10","15","13");
      //$dataTable['tbody'] = $requestData['requestDetails'];
      $data['dataTable'] = $dataTable;
      $data['dataTable']['userIds'] = $userIds;
      $data['dataTable']['requestIds'] = $requestIds;
      $data['source'] = 'viewRequest';
      $data['userType'] = $groupAccess['userType'];
      $data['team'] = $this->salesOperationLib->getTeamName($data['userDataArray']['groupId']);
      $data['requestTypeForViewDetails'] = 'request';
      $data['filter'] = $filter;
      //_p($data);die;
      $data['keyName'] = $this->appName;
      $this->load->view('splice/salesOpsTemplate', $data);
    }else{ // For 1- level
      redirect('splice/dashboard/login', 'refresh');
      die;
    }
  }

  public function viewTask($filter=''){
    $data = $this->_loadDependecies();
    if($filter){
      $data['leftMenuArray']['Dashboard']['children']['View Task'] .= '/'.$filter;
    }
    //if(!in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
    if($data['userDataArray']['groupId']){
      $groupAccess = $this->config->item("groupAccess");      
      $groupAccess = $groupAccess[$data['userDataArray']['groupId']];
      //_p($groupAccess);die;
      $accessDetails['userType'] = $groupAccess['userType'];
      $accessDetails['viewType'] = $groupAccess['viewTask'];
      $data['salesBranches'] = $this->config->item('salesBranches');
      $data['courseListForMailerRequest'] = $this->config->item('courseListForMailerRequest');
      $data['currentStatusFilter'] = $this->config->item('actions');
      unset($data['currentStatusFilter']['commentAdded']);
      
      $data['isCheckForUserIds'] = false;
      //if($filter != '' && $filter !='allAssignedTask'){
      if($filter != ''){
        $assignTaskFilterArray = array('allAssignedTask','assignedTaskTATExpired','pendingAssignedTask','assignedTaskTATToday','assignedTaskTATTomorrow');
        $createdTaskFilterArray = array('pendingCreatedTask','pendingCreatedTaskForClientApproval','createdTaskTATExpired');
        if(in_array($filter,$assignTaskFilterArray)){
          if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['admin'])){
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $userIds = array_unique($userIds);//_p($userIds);
            $salesTeamUserIds = $this->salesOperationLib->getUserIdsForGroupIds($data['teamGroupIds']['salesTeam']);
            $userIds = array_diff($userIds, $salesTeamUserIds);
          }else if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
            header("Location: ".$data['leftMenuArray']['Dashboard']['children']['Home']);
            die;
          }else{
            if($accessDetails['userType'] == 'manager'){
              $accessDetails['viewType'] = $groupAccess['viewDashboard'];

              $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            }else{
              $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            }
          }
          $data['isCheckForUserIds'] = true;
          switch ($filter) {
            case 'allAssignedTask':
              $taskIds = $this->salesOperationLib->prepareTaskIdForCurrentAssignee($userIds);
              break;
            case 'pendingAssignedTask':
            case 'assignedTaskTATToday':
            case 'assignedTaskTATTomorrow':
              $taskIds = $this->salesOperationLib->getTaskIdsForCurrentAssignee($userIds);
              $taskIds = $this->salesOperationLib->getTaskIdsForAssignedUserForInputFilter($taskIds,$filter,$userIds);
              break;

            case 'assignedTaskTATExpired':
              $taskIds = $this->salesOperationLib->getTaskIdsForAssignedUserForInputFilter(array(),$filter,$userIds);
              break;
          }
        }else if(in_array($filter,$createdTaskFilterArray)){
          if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['admin'])){
            $requestIds = array();
          }else if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $requestIds = $this->salesOperationLib->getRequestIdsForViewDashboardData($userIds);
          }else{
            header("Location: ".$data['leftMenuArray']['Dashboard']['children']['Home']);
            die;
          }
          switch ($filter){
            case 'pendingCreatedTask':
              $result = $this->salesOperationLib->getPendingRequestTask($requestIds);
              $taskIds = array();
              foreach($result as $key => $value) {
                $taskIds[] = $value['id'];
              }
              break;

            case 'pendingCreatedTaskForClientApproval':
              $result = $this->salesOperationLib->getPendingRequestTaskForClientApproval($requestIds);
              $taskIds = array();
              foreach($result as $key => $value) {
                $taskIds[] = $value['id'];
              }
              break;

            case 'createdTaskTATExpired':
              $result = $this->salesOperationLib->getTATExpiredDetails($requestIds);
              $taskIds = array();
              foreach($result as $key => $value) {
                $taskIds[] = $value['requestTaskId'];
              }
              break;
          }
        }
        //_p($taskIds);die;
      }else{
        if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['admin'])){
          $taskIds = array();
        }else if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
          if($accessDetails['userType'] != 'manager'){
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $data['isCheckForUserIds'] = true;
            if(count($userIds) > 0){
              $requestIds = $this->salesOperationLib->getRequestIdsForViewDashboardData($userIds);
              $taskIds = $this->salesOperationLib->getTaskIdsForRequestIds($requestIds);
            }else{
              $taskIds = array();
            }
          }else{
            $taskIds = array();
          }
        }else{
          if($accessDetails['userType'] != 'manager'){
            $userIds = $this->salesOperationLib->getUserIdsForViewDashboardData($data['userDataArray']['userid'],$accessDetails);
            $data['isCheckForUserIds'] = true;
            $taskIds = $this->salesOperationLib->getTaskIdsForGivenUserIds($userIds);
          }else{
              $taskIds = array();
          }
        }
      }
      
      /*if(!in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['admin'])){
        if(in_array($data['userDataArray']['groupId'],$data['teamGroupIds']['salesTeam'])){
          if($accessDetails['userType'] != 'manager'){
            // check request flow

          }else{
            $taskIds = array();
          }
        }else{
          
        }
      }else{
          $taskIds = array();
      }*/
      //_p($taskIds);die;
      $taskIds = array_unique($taskIds);
      $dataTable['heading'] = 'Task Details';
      $dataTable['thead'] = array('S.No.','Task Id','Title','Assigned On','Assigned By','TAT','Status');
      $dataTable['coloumWidth'] = array("5","10","40","5","15","10","15");
      //$dataTable['tbody'] = $requestData['requestDetails'];
      $data['dataTable'] = $dataTable;
      $data['dataTable']['userIds'] = $userIds;
      $data['dataTable']['requestIds'] = $taskIds;
      $data['source'] = 'viewRequest';
      $data['userType'] = $groupAccess['userType'];
      $data['team'] = $this->salesOperationLib->getTeamName($data['userDataArray']['groupId']);
      $data['requestTypeForViewDetails'] = 'task';
      $data['filter'] = $filter;
      $data['keyName'] = $this->appName;
      //_p($data);die;
      $this->load->view('splice/salesOpsTemplate', $data);
    }else{ // For 1- level
      redirect('splice/dashboard/login', 'refresh');
      die;
    }
  }

  public function getRequestDetailsForUser(){
    $data = $this->_loadDependecies();
    $inputArray = $this->input->post('inputArray');

    $inputArray['teamGroupIds'] = $data['teamGroupIds'];
    if(!empty($inputArray['requestIds'])){
      $inputArray['requestIds'] = json_decode($inputArray['requestIds']);
    }
    $requestData = $this->salesOperationLib->getRequestDataForGivenUserIds($inputArray);
    //_p($requestData);die;
    echo json_encode($requestData);
  }

  public function getRequestTasksDetails($requestId){
    $data = $this->_loadDependecies();
    $requestDisplayName = $this->config->item("requestDisplayName");
    $result = $this->salesOperationLib->getRequestTasksDetails($requestId,$requestDisplayName);
    echo json_encode($result);
    //$this->load->view('showTaskDetails',$result);
  }

  public function viewTaskDetails($requestTaskId){
    $requestTaskId = base64_decode($requestTaskId);
    //_p($requestTaskId);die;
    $data = $this->_loadDependecies();
    $data['leftMenuArray']['Dashboard']['children']['View Task Details'] =   SHIKSHA_HOME."/splice/dashboard/viewTaskDetails/".base64_encode($requestTaskId);
    $groupId = $data['userDataArray']['groupId'];
    $accessDetails = $this->config->item('groupAccess');
    $userType = $accessDetails[$groupId]['userType'];
    $team = $this->salesOperationLib->getTeamName($groupId);
    //_p($team);die;
    $flag = false;
    $flag = $this->salesOperationLib->checkIfUserValidToViewDetails($groupId,$data['userDataArray']['userid'],$requestTaskId,$userType,$team,$data['teamGroupIds']);
    if($flag){
      $data['source'] = 'requestTaskDetails';
      $data['appName'] = $this->appName;
      $requestTaskDetails = $this->_prepareDataForRequestTaskId($requestTaskId,$groupId,$userType);
      $attachmentDetails = array();
      if($requestTaskDetails['attachmentURL'] != ''){
        $attachmentDetails[] = array(
          'attachmentURL' => $requestTaskDetails['attachmentURL'],
          'addedBy' => $requestTaskDetails['assignedBy'],
          'addedOn' => $requestTaskDetails['imageAddedOn']
        );
      }
      //_p($requestTaskDetails);die;
      if($requestTaskDetails['taskCategory'] == 'Mailer'){
        if($requestTaskDetails['mailerType']){
          $mailerTypeForMailer = $this->config->item('mailerType');
          $requestTaskDetails['mailerType'] = $mailerTypeForMailer[$requestTaskDetails['mailerType']];
        }
      }else if($requestTaskDetails['taskCategory'] == 'Banner'){
        $diffBannerSize = $this->config->item('bannerSize');
        $diffBannerSizeHtml ='';
        $i=1;
        foreach ($requestTaskDetails['diffBannerSize'] as $key => $value) {
          $requestTaskDetails['diffBannerSize'][] = $diffBannerSize[$value];
          $diffBannerSizeHtml .= '<div class="col-md-5 col-sm-3 col-xs-12"  style="padding-left: 0px;margin-right: 20px;">'.$i++.'. '.$diffBannerSize[$value].'</div>';
          unset($requestTaskDetails['diffBannerSize'][$key]);
        }        
        $requestTaskDetails['diffBannerSize'] = $diffBannerSizeHtml;
        //_p($requestTaskDetails['diffBannerSize']);die;
      }else if($requestTaskDetails['taskCategory'] == 'Campaign Activation'){
        $diffCampaignType = $this->config->item('campaignType');                
        $diffCampaignTypes ='';
        $i=1;
        foreach ($requestTaskDetails['diffCampaignTypes'] as $key => $value) {
          $requestTaskDetails['diffCampaignTypes'][] = $diffCampaignType[$value];
          $diffCampaignTypes .= '<div class="col-md-3 col-sm-3 col-xs-12" >'.$i++.'. '.$diffCampaignType[$value].'</div>';
          unset($requestTaskDetails['diffCampaignTypes'][$key]);
        }
        $requestTaskDetails['diffCampaignTypes'] = $diffCampaignTypes;
        //_p($requestTaskDetails['diffCampaignTypes']);die;
      }
      //_p($requestTaskDetails);die;
      if($requestTaskDetails['changeType'] && ($requestTaskDetails['taskCategory']) == 'Mailer'){
        $changeTypeForMailer = $this->config->item('changeTypeForMailer');
        $requestTaskDetails['changeType'] = $changeTypeForMailer[$requestTaskDetails['changeType']];
      }

      $salesTeamGroupIds = array(1,2,3);
      if(in_array($groupId, $salesTeamGroupIds)){
        $requestTaskDetails['requestedByForMailer'] = array('Client','Sales Team');
        $requestTaskDetails['changeTypeForMailer'] = $this->config->item("changeTypeForMailer");
      }

      if(count($requestTaskDetails)>0){
        $viewTaskDetailHistory = $this->salesOperationLib->getTaskDetailHistory($requestTaskId,$attachmentDetails);
        //_p($attachmentDetails);die;
        $inputForStatusGeneration = array(
          'team' => $team,
          'userType' => $userType,
          'status' => $requestTaskDetails['status'],
          'taskAssignedTo' => $requestTaskDetails['taskAssignedTo'],
          'taskCategoty' => $requestTaskDetails['taskCategory'],
          'userId' => $data['userDataArray']['userid'],
          'groupId' =>$groupId,
          'taskType' => $requestTaskDetails['taskType'],
          'requestTaskId' => $requestTaskDetails['taskId'],
          'teamGroupIds' => $data['teamGroupIds']
           );
        //_p($inputForStatusGeneration);die;
        $statusArray = $this->_prepareStatusForUser($inputForStatusGeneration);
        $data['team'] = $team;
        $data['requestTaskDetails'] = $requestTaskDetails;
        $data['requestTaskDetails']['statusArray'] = $statusArray;
        $data['viewTaskDetailHistory'] = $viewTaskDetailHistory;
        $data['attachmentDetails'] = $attachmentDetails;
        $data['requestDisplayName'] = $this->config->item("requestDisplayName");
        $this->load->view('salesOpsTemplate',$data);
      }else{
        redirect('splice/dashboard/viewRequest');
      }       
    }else{
      redirect('splice/dashboard/team/'.lcfirst($team), 'refresh');
    }
  }

  private function _prepareStatusForUser($inputForStatusGeneration){
    return $this->salesOperationLib->prepareStatusForUser($inputForStatusGeneration);
  }

  private function _prepareDataForRequestTaskId($requestTaskId,$groupId,$userType){
    $taskDetails = $this->salesOperationLib->getTaskDataForViewTaskDetails($requestTaskId);
    return $taskDetails;
  }
  
  public function getTopTilesForDashboard($groupId,$userId){
    $data = $this->_loadDependecies();
    $teamGroupIds = $this->input->post('teamGroupIds');
    //_p($teamGroupIds);die;
    $groupAccess = $this->config->item("groupAccess");
    $groupAccess = $groupAccess[$groupId];
    $accessDetails['userType'] = $groupAccess['userType'];
    $accessDetails['viewType'] = $groupAccess['viewDashboard'];
    //_p($viewDashboardAccess);die;
    $result = $this->salesOperationLib->getTopTilesForDashboard($groupId,$userId,$accessDetails,$teamGroupIds);
    echo json_encode($result);
    exit;    
  }

  public function getManagerList($currentGroupId=''){
    $this->_loadDependecies();
    $managerGroupId = $this->salesOperationLib->getManagerGroupId($currentGroupId);
    //_p($managerGroupId);die;
    $managerDetails = $this->salesOperationLib->getManagerDetails($managerGroupId);
    //_p($managerDetails);die;
    echo json_encode($managerDetails);
  }

  public function getLeadList($type,$typeValue,$selectedGroupId){
    $this->_loadDependecies();
    echo json_encode($this->salesOperationLib->getLeadDetails($type,$typeValue,$selectedGroupId));
  }

  public function getDataForSelectedStatus(){
    $data = $this->_loadDependecies();
    $inputArray = $this->input->post('inputArray');
    $accessDetails = $this->config->item('groupAccess');
    $accessDetails = $accessDetails[$inputArray['groupId']];
    //_p($inputArray);die;
    $inputArray['userType'] = $accessDetails['userType'];
    $inputArray['viewType'] = $accessDetails['viewTask'];
    $inputArray['teamGroupIds'] = $data['teamGroupIds'];
    //_p($inputArray);die;
    $result = $this->salesOperationLib->prepareDataForSelectedStatus($inputArray);    
    echo json_encode($result);
    exit;
  }

  public function saveUpdatedStatus(){
    $data = $this->_loadDependecies();
    $inputArray['loggedInUserName'] = $data['userDataArray']['firstname'].' '.$data['userDataArray']['lastname'];
    $inputArray['commentData'] = $this->input->post('commentData');
    $inputArray['requestTaskTitle'] = $this->input->post('requestTaskTitle');
    $inputArray['taskCategory'] = $this->input->post('taskCategory');
    $inputArray['selectedStatus'] = $this->input->post('selectedStatus');
    $inputArray['userId'] = $this->input->post('userId');
    $inputArray['groupId'] = $this->input->post('groupId');
    $inputArray['requestId'] = $this->input->post('requestId');
    $inputArray['requestTaskId'] = $this->input->post('requestTaskId');
    $accessDetails = $this->config->item('groupAccess');
    $accessDetails = $accessDetails[$inputArray['groupId']];
    $inputArray['userType'] = $accessDetails['userType'];
    $inputArray['team'] = $this->salesOperationLib->getTeamName($inputArray['groupId']);
    $inputArray['currentStatus'] = $this->input->post('currentStatus');
    $inputArray['currentAssignee'] = $this->input->post('currentAssignee');
    $inputArray['lastUpdatedOn'] = $this->input->post('lastUpdatedOn');
    $inputArray['taskCategory'] = $this->input->post('taskCategory');
    $inputArray['TATDate'] = $this->input->post('TATDate');
    $inputArray['site'] = $this->input->post('site');
    $inputArray['teamGroupIds'] = $data['teamGroupIds'];
    if($this->input->post('changeType')){
      $inputArray['changeType'] = $this->input->post('changeType');
    }
    if($this->input->post('requestedBy')){
      $inputArray['requestedBy'] = $this->input->post('requestedBy');
    }
    //_p($inputArray);die;
    // check if task is updated in this duration
    $lastUpdatedOn = $this->salesOperationLib->getLastUpdatedOnForTask($inputArray['requestTaskId']);
    if((strtotime($inputArray['lastUpdatedOn']) - strtotime($lastUpdatedOn))<0){
      $errorDetails['status'] = 2;
      //$errorDetails['URL'] = SHIKSHA_HOME.'/splice/dashboard/viewTaskDetails/'.base64_encode($inputArray['requestTaskId']);
      echo json_encode($errorDetails);exit();
    }
    
    if($this->input->post('selectedUserId')){
      $inputArray['selectedUserId'] = $this->input->post('selectedUserId');
    }
    if($inputArray['selectedStatus'] == 'pushedBack'){
      if(empty($inputArray['selectedUserId']) || $inputArray['selectedUserId']<=0){
            mail("praveen.singhal@99acres.com", "Assignee null in Splice main", print_r($_POST,true));
        }
    }
    
    if(count($_FILES)){
      $uploadDocResponse = $this->uploadAttachment();
      if($uploadDocResponse['status'] == 0){
        echo json_encode($uploadDocResponse);
        exit;
      }else{
        $inputArray['attachmentURL'] = $uploadDocResponse[0]['imageurl'];          
      }
    }

    // get status for every task in the request
    $inputArray['defaultTAT'] = $this->config->item("defaultTAT");
    if(($inputArray['team'] == 'Sales')&&($inputArray['selectedStatus'] == 'pushedBack')){
      $inputArray['changeTypeForMailer'] = $this->config->item("changeTypeForMailer");
    }

    if(($inputArray['team'] == 'Sales')&&($inputArray['taskCategory'] != 'Mailer')){
      $inputArray['taskDetailsForCalculatingTAT'] = $this->salesOperationLib->getRequestTyeAndChangeType($inputArray['requestTaskId']);
    }

    // save updated status
    $tasksDetails = $this->salesOperationLib->saveUpdatedStatus($inputArray);
    $inputArray['updatedActionDetails'] = $tasksDetails;
    $defaultAssignee = $this->config->item('defaultAssignee');
    if($tasksDetails['newTATForCampaignActivation']){
      $inputArray['salesOpsGroupId'] = $defaultAssignee['campaignActivation']['notificationMailTo'];
    }
    $teamGroupMailId = $this->salesOperationLib->getGroupMailId($defaultAssignee);
    $inputArray['assigneeId'] = $tasksDetails['assignedUserId'];
    $inputArray['requestDisplayName'] = $this->config->item("requestDisplayName");
    $this->salesOperationLib->prepareMailDataForVariousAction($inputArray,$this->appName,$teamGroupMailId);
    if($inputArray['team'] == 'Sales'){
      $tasksDetails['URL'] = $data['leftMenuArray']['Dashboard']['children']['View Request'];
    }else{
      $tasksDetails['URL'] = $data['leftMenuArray']['Dashboard']['children']['View Task'];
    }
    
    echo json_encode($tasksDetails);exit();
  }

  public function prepareTATExpiredRequestTaskDetails(){
    $this->validateCron(); // prevent browser access
    $date = date("Y-m-d",strtotime("-1 days"));
    $defaultAssignee = $this->config->item('defaultAssignee');
    $teamGroupMailId = $this->salesOperationLib->getGroupMailId($defaultAssignee);
    
    $status = array('clientApprovedAndClosed','cancel','doneAndReviewed','partialDoneAndReviewed');
    $salesTeamGroupIds = array(1,2,3);
    $userIds = $this->salesOperationLib->getUserIdsForSalesTeam($salesTeamGroupIds);
    $requestDisplayName = $this->config->item('requestDisplayName');
    $this->salesOperationLib->getTATExpiredRequestTaskDetails($date,$status,$userIds,$teamGroupMailId,$this->appName,$requestDisplayName);
  }
}