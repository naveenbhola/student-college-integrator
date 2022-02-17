<?php 
class CollegePredictorScript extends MX_Controller
{
	private $userStatus;
	private $cpscriptmodel;
	public function __construct(){
		parent::__construct();
		$this->userStatus = $this->checkUserValidation();
		$this->cpscriptmodel = $this->load->model('cpscriptmodel');
	}

	public function removeOldDataOfCollegePredictor($examName){
		if($this->userStatus == 'false'){
			die('Session error');
		}else if(is_array($this->userStatus[0]) && $this->userStatus[0]['usergroup'] != 'cms'){
			die('Access denied');
		}
		$examName = $this->security->xss_clean($examName);

		//get all college ids from CollegePredictor_Colleges table
		$collegeIds = $this->cpscriptmodel->getAllCollegeIdsForExam($examName);

		//get all the branch ids from CollegePredictor_BranchInformation table using college ids
		$branchIds = $this->cpscriptmodel->getAllBranchesForColleges($collegeIds);

		//update CollegePredictor_CategoryRoundRankMapping table using branch ids
		$this->cpscriptmodel->removeAllRoundInfoForCollegePredictor($branchIds);

		//update CollegePredictor_BranchInformation table using college ids
		$this->cpscriptmodel->removeAllBranchOfCollegePredictor($collegeIds);

		//update CollegePredictor_Colleges table using exam name
		$this->cpscriptmodel->removeAllCollegesForCollegePredictor($examName);

		echo 'Data removed for  - '.$examName.' College Predictor';
	}
}