<?php
/**
 * Model for Homepage 3.0
 * Author: Virender Singh
 */
class HomepageModel extends MY_Model
{
	private $dbHandle = null;
    
    /**
     * Contructor for the class
     */
    function __construct(){
		parent::__construct();
    }

	/**
     * Method to create a DB handler
     */
    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

	/**
     * Fetch the featured college banner to show on the homepage.
     * Parameters : dbFlag (fetch data from DB or Cache)
     * Author: Virender Singh
     */
	public function getFeaturedCollegeBanners($dbFlag = false,$pageName='HomePageDesktop') {
		$bannerMaxLimit = 16;
		$freeBannerRows = 0;
		$finalData = array();
		$fromWhereQuery = " and banner.showOn = 'desktop'";
		if($pageName == 'HomePageDesktop'){
			$key = md5('homepageFeaturedWidgetData');
			$cacheLib = $this->load->library('cacheLib');
			$cacheData = $cacheLib->get($key);
		}
		elseif($pageName == 'HomePageMobile'){
			$bannerMaxLimit = 8;
			$fromWhereQuery = " and banner.showOn = 'mobile'";
		}
		if($cacheData != 'ERROR_READING_CACHE' && !$dbFlag) {
			$finalData = $cacheData;
		} else {
			$this->initiateModel('read');
			$curDate = ' CURDATE() ';
			//$curDate = ' "2016-03-16" ';
			$sql = "SELECT banner.id, banner.banner_id, banner.collegeName, banner.target_url, banner.image_url, banner.isDefault FROM homePageFeaturedCollegeBanner as banner where banner.status='live' and banner.start_date <= $curDate and banner.end_date >= $curDate and banner.isDefault='0' $fromWhereQuery order by banner.start_date limit $bannerMaxLimit";
			$sqlObj = $this->dbHandle->query($sql);
			$data = $sqlObj->result_array();
			foreach ($data as $value) {
				$finalData['paid'][] = $value; 
			}
			$rows = $sqlObj->num_rows();
			if($rows <= 8){
				$freeBannerRows = 8 - $rows;
			}else if($rows < $bannerMaxLimit){
				$freeBannerRows = $bannerMaxLimit - $rows;
			}
			if($freeBannerRows > 0)
			{
				$sql = "SELECT banner.id, banner.banner_id,  banner.collegeName, banner.target_url, banner.image_url, banner.isDefault FROM homePageFeaturedCollegeBanner as banner where banner.status='live' and banner.start_date <= $curDate and $curDate <= banner.end_date and banner.isDefault='1' $fromWhereQuery limit $freeBannerRows";
				$sqlObj = $this->dbHandle->query($sql);
				$data = $sqlObj->result_array();
				foreach ($data as $value) {
					$finalData['free'][] = $value; 
				}
			}
			if($pageName == 'HomePageDesktop'){
				$cacheLib->store($key, $finalData, 2*60*60);
			}
		}
		return $finalData;
	}

	/**
     * Fetch the featured articles to show on the homepage.
     * Parameters : dbFlag (fetch data from DB or Cache)
     * Author: Virender Singh
     */
	function getFeaturedArticles($dbFlag = false){
		$articleMaxLimit = 9;
		$finalData = array();
		$key = md5('homepageArticleWidgetData');
		$cacheLib = $this->load->library('cacheLib');
		$cacheData = $cacheLib->get($key);
		if($cacheData != 'ERROR_READING_CACHE' && !$dbFlag) {
			$finalData = $cacheData;
		} else {
			$this->initiateModel('read');
			$curDate = ' CURDATE() ';
			//$curDate = ' "2016-03-16" ';
			$sql = "SELECT b.blogId, b.blogTitle, b.homepageImgURL, b.creationDate blogCreationDate,b.lastModifiedDate blogModificationDate, b.url as blogUrl, h.id, h.position, h.article_id, h.start_date, h.end_date FROM blogTable b LEFT JOIN homePageFeaturedArticleCMS h ON (b.blogId = h.article_id AND $curDate <= h.end_date AND h.start_date <= $curDate AND h.status = 'live') WHERE b.homepageImgURL != '' AND b.status = 'live'AND b.blogType NOT IN ('exam','examstudyabroad') and b.countryId = '2' ORDER BY CASE WHEN h.id IS NULL THEN 1 ELSE 0 END, b.lastModifiedDate DESC LIMIT $articleMaxLimit";
			$sqlObj = $this->dbHandle->query($sql);
			$finalData = $sqlObj->result_array();
			$cacheLib->store($key, $finalData, 2*60*60);
		}
		return $finalData;
	}

	function getNationalCountStats(){
		
		$this->initiateModel();
	 	$dbHandleLocal = $this->dbHandle;

		//Get National Insitute Count
		$sql = 'select count(*) as instCount from shiksha_institutes where status = "live" ';
		$query = $dbHandleLocal->query($sql);
		$res['instCount'] = $query->row()->instCount;

		//Get Reviews Count
		$sql = "SELECT Count(*) as reviewCount FROM CollegeReview_MainTable  where status = 'published' ";
		$query = $dbHandleLocal->query($sql);
		$res['reviewsCount'] = $query->row()->reviewCount;

		//Get Answers Count
		$sql = "SELECT Count(*) as questionsAnswered FROM messageTable where mainAnswerId = -1 AND msgCount > 0 AND status = 'live' AND fromOthers = 'user'";
		$query = $dbHandleLocal->query($sql);
		$res['questionsAnsweredCount'] = $query->row()->questionsAnswered;

		//Get Career Count
		$sql = "SELECT COUNT(careerId) as careerCount FROM  CP_CareerTable where status = 'live'";
		$query = $dbHandleLocal->query($sql);
		$res['careerCount'] = $query->row()->careerCount;

        //Get Exam Count
        $sql = "SELECT COUNT(*) as examCount FROM exampage_main em JOIN exampage_groups eg ON (em.id = eg.examId AND eg.isPrimary = 1) JOIN exampage_master epm ON epm.groupId = eg.groupId WHERE em.status = 'live' AND eg.status = 'live' AND epm.status = 'live'";
        $query = $dbHandleLocal->query($sql);
        $res['examCount'] = $query->row()->examCount;

        $sql = "SELECT count(*) as baseCourseCount from base_courses where status='live'";
        $query = $dbHandleLocal->query($sql);
        $res['baseCourseCount'] = $query->row()->baseCourseCount;

        $sql = "SELECT count(*) as specializationCount from specializations where status='live'";
        $query = $dbHandleLocal->query($sql);
        $res['specializationCount'] = $query->row()->specializationCount;

        $sql = "SELECT count(*) as shikshaCourses from shiksha_courses where status='live'";
        $query = $dbHandleLocal->query($sql);
        $res['shikshaCourses'] = $query->row()->shikshaCourses;
		
		return $res;
	}
}
