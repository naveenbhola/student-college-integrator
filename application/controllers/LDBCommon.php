<?php

class LDBCommon extends MX_Controller
{

    protected $commonModel;

    public function __construct()
    {
        $this->load->model('LDBCommonmodel');
        $this->commonModel = new LDBCommonModel();
    }

    private function _filterValues($source = array(), $key)
    {
        $returnArray = array();
        foreach ($source as $k => $v) {
            $returnArray[] = $v[$key];
        }

        return $returnArray;
    }

    public function updateTestUser()
    {
    	error_log("cron starts...");
        require FCPATH . 'globalconfig/testUserConfig.php';
        $userIds = $this->commonModel->getUserIdOfNonTestUserByMobileNumber($testUserMobileNumbers);

        $userIds = $this->_filterValues($userIds, 'userid');
        $this->commonModel->updateTestFlagByUserId($userIds);

        $indexingQueueBatch = array();
        foreach($userIds as $key=>$userId){
        	$indexingQueueBatch[] = array(
				'userId' => $userId,
				'queueTime' => date('Y-m-d H:i:s'),
				'status' => 'queued'
			);
        }

        unset($userIds);
        $this->commonModel->batchInsert('tuserIndexingQueue', $indexingQueueBatch);
    	error_log("cron Done...");
        echo 'Done!';
    }

    public function getDCFromMappingTable(){
        $desiredCourses = $this->commonModel->getDCFromMappingTable();
        return $this->_filterValues($desiredCourses, 'oldspecializationid');
    }

    public function getUsersWhoseMigrationIsRequired(){
       
        $desiredCourses = $this->getDCFromMappingTable();
        $userids = $this->commonModel->getUsersByDesiredCourse($desiredCourses);
        unset($desiredCourses);
        return $this->_filterValues($userids, 'UserId');
        
    }

    public function resetExplicitMigration(){
        ini_set("memory_limit",'2048M');
        ini_set("max_execution_time",-1);

        $currentChunk = 0;
        $chunkSize = 10000;
        $start = microtime(true);
        error_log("==shiksha== cron starts ");
        error_log("==shiksha== getting users from tuserpref ");
        
        $userids = $this->getUsersWhoseMigrationIsRequired();
        
        $totalCount = count($userids);

        error_log("==shiksha== users identified! count = ".$totalCount." = time = ".ceil((microtime(true)-$start))." sec");
        
        $rounds = ceil($totalCount/$chunkSize);
        error_log("==shiksha== process to mark user interest as history starts ....");
        while($totalCount > 0){

            $index = 0;
            $tempUsers = array();
            foreach($userids as $key=>$value){
                $tempUsers[] = $value;
                $index++;

                unset($userids[$key]);
                if($index >= $chunkSize){
                    break;
                }
            }
            $interestIds = array();
            $interestIds = $this->commonModel->getInterestidByUserid($tempUsers);
            // error_log("==shiksha== check ===".print_r($this->commonModel->checkuserInterestStatus($tempUsers), true));
            unset($tempUsers);
            $interestIds = $this->_filterValues($interestIds, 'interestId');
            $this->commonModel->changeUserInterestStatus($interestIds);
            unset($interestIds);

            $currentChunk++;
            $totalCount = $totalCount - $index;
            error_log("==shiksha== chunks remaining ==".($rounds - $currentChunk));
        }

        error_log("==shiksha== migration Done in ".ceil((microtime(true)-$start))." sec");
    }

    function startUserIndexing(){
        ini_set("memory_limit",'8048M');
        ini_set("max_execution_time",-1); 

        echo date('Y-m-d h:i:s').'========================= migration started';
        error_log(date('Y-m-d h:i:s').'========================= migration started');

        $users = $this->getUsersWhoseMigrationIsRequired();
        $chunkUser = array();

        foreach ($users as $userId) {
            $chunkUser[]  = $userId;

            if (count($chunkUser) == 1000){            
                Modules::run("user/UserIndexer/indexMultipleUsers", $chunkUser,true,'national');
                $chunkUser = array();
                echo date('Y-m-d h:i:s').'==================== done for userId->  '.$userId;
                error_log(date('Y-m-d h:i:s').'==================== done for userId->  '.$userId);
            }
        }

    }
}
