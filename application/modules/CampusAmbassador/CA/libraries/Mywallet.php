<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mywallet {

        
        private $userId;
        private $status;
        private $entityId; // answerId
        
        function __construct()
        {
                $this->CI=& get_instance();
                $this->CI->load->model('CA/mywalletmodel');
        }		
        
        public function getPaidAmountByUser($userId)
        {
                $this->userId = $userId;
                return $this->CI->mywalletmodel->getTotalPaidToUser($this->userId);
        }
        
        function getCurrentEarningByUser($userId)
        {
                $this->userId = $userId;
                $totalEarning = $this->CI->mywalletmodel->getTotalEarning($userId);

                
                if(count($totalEarning)>0)
			{
				foreach((object)$totalEarning as $earn)
				{
					if($earn->status == 'pending' && $earn->entityType == 'answer' && $earn->action == '')
					{       
						$totalPending = $totalPending + $earn->reward ;
						$countPending = $countPending +1;
					}
					if($earn->status == 'earned' && $earn->entityType == 'answer' && $earn->action == 'approvedAnswer')
					{
						$totalApproved = $totalApproved + $earn->reward ;
						$countApproved = $countApproved +1;
					}
					if($earn->status == 'earned' && $earn->entityType == 'answer' && $earn->action == 'featuredAnswer')
					{
						$totalFeatured = $totalFeatured + $earn->reward ;
						$countFeatured = $countFeatured +1;
					}
					if($earn->status == 'earned' && $earn->entityType == 'task' && $earn->action == 'task')
					{
						$totalTask = $totalTask + $earn->reward ;
					}
					if($earn->status == 'earned' && $earn->entityType == 'chat' && $earn->action == 'approvedChat')
					{
						$totalTask = $totalTask + $earn->reward ;
					}
					if($earn->status == 'pending' && $earn->entityType == 'chat' && $earn->action == '')
					{
						$totalPending = $totalPending + $earn->reward ;
						$countPending = $countPending +1;
					}
				}
			}
			$data['totalEarn'] = $totalApproved + $totalFeatured + $totalTask;
			$data['totalPending'] = $totalPending;
			$data['totalApproved'] = $totalApproved;
			$data['totalFeatured'] = $totalFeatured;
			$data['count'] = array('pendingCount'=>$countPending,'approvedCount'=>$countApproved,'featuredCount'=>$countFeatured);
                        return $data;

        }
        
        function getCreatedTaskByUser($userId)
        {
             $this->userId = $userId;
             return $this->CI->mywalletmodel->getCreatedTask($this->userId);
        }
        
        function paidCRCheque($userId,$paidAmount,$chequeNumber)
        {
             $this->userId = $userId;
             return $this->CI->mywalletmodel->addPaidAmount($this->userId,$paidAmount,$chequeNumber);   
        }
        
        function getAllCRHavingEarnings($position, $item_per_page)
        {
            $result = $this->CI->mywalletmodel->getAllCRForPaid($position, $item_per_page);

		$this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepository = $courseBuilder->getCourseRepository();
		
		if(count($result)>0)
		{
			foreach($result['result'] as $record)
			{
				$course = $this->courseRepository->find($record['mainCourseId']);
				if(is_object($course))
				{
				       $data[$record['userId']]['instituteName'] = html_escape($course->getInstituteName());	
				}
			}	
		}
	        $data['result'] = $result;
		return $data;
        }
	
	/***
	 * functionName : makeIncentive
	 * functionType : return type
	 * param        : userId;
	 * desciption   : check cr is mapped on MBA/B.tech, then prepare incentive as per his category(56/23).
	 * @author      : akhter
	 * @team        : UGC
	***/
	function makeIncentive($userId)
	{
		$this->userId = $userId;
		$userIncentive = array();
		$result = $this->CI->mywalletmodel->checkCrCategory($this->userId);
		foreach($result as $res){
			if($res['category_id'] == 56)
			{
				$userIncentive[$res['userId']] = 'Engineering';
			}else if($res['category_id'] == 23){
				$userIncentive[$res['userId']] = 'Mba';
			}
		}
		return $userIncentive;
	}
 	
 /***  desciption   : common function xls,xlsx and csv reader.
	 * @author      : akhter
	 * @team        : UGC
	***/
	function readCommonExcel(){

	$this->CI->load->library('common/reader');
	$this->CI->load->library('common/PHPExcel/IOFactory');
	//ini_set('memory_limit','400M');
	
	$inputFileName = $_FILES["datafile"]["tmp_name"];
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
	$objReader->setReadDataOnly(true);  
	/**  Load $inputFileName to a PHPExcel Object  **/  
	$objPHPExcel = $objReader->load($inputFileName);
	$count  =  1;
	for($i=0;$i<$count;$i++){
			$objWorksheet = $objPHPExcel->setActiveSheetIndex($i);
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
			$headingsArray = $headingsArray[1];

			$dataArray = array();
			$r=0;
			for ($row = 2; $row <= $highestRow; ++$row) {
			    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
			    if ((isset($dataRow[$row])) && ($dataRow[$row] > '')) {
				$j=0; 
				foreach($headingsArray as $columnKey => $columnHeading) {
					if($headingsArray[$columnKey]!=''){
						$dataArray[$r][$headingsArray[$columnKey]] = $dataRow[$row][$columnKey];
					}
					$j++;
				}
				$r++;
			    }
			}
			return $dataArray;
		}
    }

    function getCreditedAmount($userId){
    	$this->userId = $userId;
  		return $this->CI->mywalletmodel->getCreditedAmount($this->userId);  	
    }

    function addInWallet($data){
        return $this->CI->mywalletmodel->addInWallet($data); 
    }

    function insertNaukriData($tableName,$data){
        return $this->CI->mywalletmodel->updateNaukriData($tableName,$data); 
    }

}
?>