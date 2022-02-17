<?php
class eventCalendarCronLib {
	private $CI;

	public function __construct(){
    $this->CI = & get_instance();
    $this->CI->load->model('event/eventandexammodel');
    $this->CI->model = new eventandexammodel;
    $this->CI->chunkSize = 100;
    $this->CI->load->config('event/eventAutoSubscribeConfig');
    
    $this->CI->groupIdToStreamMap = $this->CI->config->item('groupIdToStreamMap');
    $this->CI->otherExamList = $this->CI->config->item('otherExamList');
  }

  public function subscribeExamResponsesToOtherExamsAcrossLocations($responseData = array(), $steamId = 2, $examGroupId = 113, $selfSubscribe = false, $onlySelfSubscribe = false){
    if(empty($responseData)){
      echo 'Insufficient Data';
      return;
    }
    if(!in_array($steamId, array(1,2))){
      echo 'Invalid Inputs';
      return;
    }
    if(empty($this->CI->groupIdToStreamMap[$examGroupId])){
      echo 'No exam info available in config';
      return;
    }
    $responses = array();
    foreach ($responseData as $value) {
      $responses['cities'][$value['userCity']] = $value['userCity'];
      $responses['data'][$value['userCity']][] = $value;
      $responses['userIds'][$value['userId']] = $value['userId'];
      $responses['ctaIds'][$value['userId']] = $value['tracking_keyid'];
    }
    $curr_time = date('Y-m-d H:i:s');

    if($onlySelfSubscribe == true){
      $batchInsertData = array();
      $alreadySubscribed = $this->CI->model->checkIfExamAlreadySubscribed($responses['userIds'], $this->CI->groupIdToStreamMap[$examGroupId]['name'], $steamId);
      $newSubscribe = array_diff($responses['userIds'], $alreadySubscribed);
      foreach ($newSubscribe as $userId) {
        $batch = array();
        $batch['userId'] = $userId;
        $batch['exam_name'] = $this->CI->groupIdToStreamMap[$examGroupId]['name'];
        $batch['category_name'] = $this->CI->groupIdToStreamMap[$examGroupId]['label'];
        $batch['status'] = 'live';
        $batch['date_created'] = $curr_time;
        $batch['streamId'] = $this->CI->groupIdToStreamMap[$examGroupId]['st'];
        $batch['courseId'] = $this->CI->groupIdToStreamMap[$examGroupId]['bc'];
        $batch['educationTypeId'] = $this->CI->groupIdToStreamMap[$examGroupId]['et'];
        $batch['tracking_keyid'] = 190;
        if(!empty($responses['ctaIds'][$userId]) && $responses['ctaIds'][$userId] > 0){
          $batch['tracking_keyid'] = $responses['ctaIds'][$userId];
        }
        $batchInsertData[] = $batch;
      }
      $insertDataChunks = array_chunk($batchInsertData, $this->CI->chunkSize);
      if(!empty($insertDataChunks)){
        foreach ($insertDataChunks as $chunk) {
          $this->CI->model->autoSubscriberUsers($chunk);
        }
      }
      echo 'Only Self Subscribed';
      return;
    }
    if($selfSubscribe == true){
      $batchInsertData = array();
      $alreadySubscribed = $this->CI->model->checkIfExamAlreadySubscribed($responses['userIds'], $this->CI->groupIdToStreamMap[$examGroupId]['name'], $steamId);
      $newSubscribe = array_diff($responses['userIds'], $alreadySubscribed);
      foreach ($newSubscribe as $userId) {
        $batch = array();
        $batch['userId'] = $userId;
        $batch['exam_name'] = $this->CI->groupIdToStreamMap[$examGroupId]['name'];
        $batch['category_name'] = $this->CI->groupIdToStreamMap[$examGroupId]['label'];
        $batch['status'] = 'live';
        $batch['date_created'] = $curr_time;
        $batch['streamId'] = $this->CI->groupIdToStreamMap[$examGroupId]['st'];
        $batch['courseId'] = $this->CI->groupIdToStreamMap[$examGroupId]['bc'];
        $batch['educationTypeId'] = $this->CI->groupIdToStreamMap[$examGroupId]['et'];
        $batch['tracking_keyid'] = 190;
        if(!empty($responses['ctaIds'][$userId]) && $responses['ctaIds'][$userId] > 0){
          $batch['tracking_keyid'] = $responses['ctaIds'][$userId];
        }
        $batchInsertData[] = $batch;
      }
      $insertDataChunks = array_chunk($batchInsertData, $this->CI->chunkSize);
      if(!empty($insertDataChunks)){
        foreach ($insertDataChunks as $chunk) {
          $this->CI->model->autoSubscriberUsers($chunk);
        }
      }
    }
    $this->subscribeExamUsersToOtherExams($responses, $steamId, $examGroupId);
  }

  public function subscribeExamUsersToOtherExams($responses, $steamId, $examGroupId, $groupData){
    $batchInsertData = array();
    $curr_time = date('Y-m-d H:i:s');
    
    $stateToExamMapping = $this->CI->otherExamList[$examGroupId];
    if(empty($stateToExamMapping)){
      echo 'Invalid Inputs';
      return;
    }

    //Subscribe to JoSAA for JEE Main case
    if($examGroupId == 113){
      $JoSAAGroupId = 1547;
      $alreadySubscribed = $this->CI->model->checkIfExamAlreadySubscribed($responses['userIds'], $JoSAAGroupId, $steamId);
      $newSubscribe = array_diff($responses['userIds'], $alreadySubscribed);
      foreach ($newSubscribe as $userId) {
        $batch = array();
        $batch['userId'] = $userId;
        $batch['exam_name'] = 'JoSAA';
        $batch['exam_group_id'] = $JoSAAGroupId;
        $batch['category_name'] = 'Engineering';
        $batch['status'] = 'live';
        $batch['date_created'] = $curr_time;
        $batch['streamId'] = $steamId;
        $batch['courseId'] = $groupData['bc'];
        $batch['educationTypeId'] = $groupData['et'];
        $batch['tracking_keyid'] = 190;
        if(!empty($responses['ctaIds'][$userId]) && $responses['ctaIds'][$userId] > 0){
          $batch['tracking_keyid'] = $responses['ctaIds'][$userId];
        }
        $batchInsertData[] = $batch;
      }
    }

    $statesToConsider = array_keys($stateToExamMapping);
    $delhiNcrCities = $this->CI->model->getDelhiNcrCities();
    if(!empty($responses['cities'])){
      $cityState = $this->CI->model->getStatesFromCities($responses['cities'], $statesToConsider);
    }
    $cities = array_keys($cityState);
    $stateWiseData = array();
    foreach ($cities as $city) {
      $stateWiseData[$cityState[$city]['state_id']][] = $responses['data'][$city];
      if(in_array($city, $delhiNcrCities) && $city != 74){
        $stateWiseData[128][] = $responses['data'][$city];
      }
    }

    $stateWiseUsers = array();
    foreach ($stateWiseData as $stateId => $cityWiseData) {
      foreach ($cityWiseData as $userData) {
        foreach ($userData as $value) {
          $stateWiseUsers[$stateId][] = $value['userId'];
        }
      }
    }
    foreach ($stateWiseUsers as $stateId => $users) {
      $alreadySubscribed = $this->CI->model->checkIfExamAlreadySubscribed($users, $stateToExamMapping[$stateId]['groupId'], $steamId);
      $newSubscribe = array_diff($users, $alreadySubscribed);
      foreach ($newSubscribe as $userId) {
        if(empty($stateToExamMapping[$stateId]['name'])){
          continue;
        }
        $batch = array();
        $batch['userId'] = $userId;
        $batch['exam_name'] = $stateToExamMapping[$stateId]['name'];
        $batch['exam_group_id'] = $stateToExamMapping[$stateId]['groupId'];
        $batch['category_name'] = $this->CI->groupIdToStreamMap[$examGroupId]['label'];
        $batch['status'] = 'live';
        $batch['date_created'] = $curr_time;
        $batch['streamId'] = $steamId;
        $batch['courseId'] = $this->CI->groupIdToStreamMap[$examGroupId]['bc'];
        $batch['educationTypeId'] = $this->CI->groupIdToStreamMap[$examGroupId]['et'];
        $batch['tracking_keyid'] = 190;
        if(!empty($responses['ctaIds'][$userId]) && $responses['ctaIds'][$userId] > 0){
          $batch['tracking_keyid'] = $responses['ctaIds'][$userId];
        }
        $batchInsertData[] = $batch;
      }
    }
    $insertDataChunks = array_chunk($batchInsertData, $this->CI->chunkSize);
    if(!empty($insertDataChunks)){
      foreach ($insertDataChunks as $chunk) {
        $this->CI->model->autoSubscriberUsers($chunk);
      }
    }
    echo 'Success';
  }
}
?>
