<?php
class ReviewHomepageTiles extends MX_Controller {
    public $getTabsContentByCategory = array();
    function __construct(){
	$this->init();
    }
    
    function init() {
	$this->load->helper(array('form', 'url','date','image','shikshaUtility'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','Upload_client'));
        $this->userStatus = $this->checkUserValidation();
	$this->getTabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
	$this->load->model('CollegeReviewForm/collegereviewmodel');
	$this->crmodel = new CollegeReviewModel();
	$this->load->library('CollegeReviewForm/CollegeReviewLib'); 
	$this->CollegeReviewLib = new CollegeReviewLib();
    }
    
    public function reviewHomepageTiles($param = '') {
		$prodId = 901;
		$maxSize = 500 * 1024;

		if(isset($_POST['cmsAction'])){
	    	if($this->input->post('cmsAction') == 'addTile'){
				$this->_addNewTile($prodId,$maxSize);
		    
		    }else if($this->input->post('cmsAction') == 'editTile' && $this->input->post('tileId')!=''){
				$this->_editTileCMS($prodId,$maxSize);
		    
		    }else {
		    	$cmsAction = $this->input->post('cmsAction');
		    	$tileId = $this->input->post('tileId');
		    	$this->_tileCMSAction($cmsAction,$tileId);
		    }
		}
		$streamId = $this->input->post('stream');
		$baseCourseId = $this->input->post('baseCourse');
		$educationType = $this->input->post('educationType');
		$substream = $this->input->post('substream');
		$deliveryMethod = $this->input->post('deliveryMethod');

		if($streamId == ""){
			global $managementStreamMR;
			$streamId = $managementStreamMR;
		}

		if($baseCourseId == ""){
			global $mbaBaseCourse;
        	$baseCourseId = $mbaBaseCourse;
		}

		$cmsPageArr = array();
		$cmsUserInfo = $this->cmsUserValidation();
		$cmsPageArr['defaultSelectedStr'] = $streamId;
		$cmsPageArr['defaultSelectedBaseCourse'] = $baseCourseId;
		$cmsPageArr['educationType'] = ($educationType > 0)?$educationType:20;
		$cmsPageArr['substream'] = ($substream > 0)?$substream:0;
		$cmsPageArr['deliveryMethod'] = ($deliveryMethod > 0)?$deliveryMethod:'';
		$cmsPageArr['prodId'] = $prodId;
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];	
		$cmsPageArr['tilesData'] = array();
		$tiles = $this->crmodel->getCMSTileData($streamId,$baseCourseId);
		foreach ($tiles as $key => $value) {
			$displayNumbers[$value['tileId']] = $value['displayNumbers'];
		}
		$cmsPageArr['tilesData'] = $tiles;
		$temp = array();
		foreach($tiles as $key=>$tile)
		{
		    $tile['description']        = str_replace("\\", "@!@", $tile['description']);
		    $tile['seoPageDescription'] = str_replace("\\", "@!@", $tile['seoPageDescription']);

		    $tile['description']        = htmlentities($tile['description']);
		    $tile['seoPageDescription'] = htmlentities($tile['seoPageDescription']);
		        
		    $temp[$tile['tileId']] = $tile;
		}
		$cmsPageArr['tilesDataJson'] = json_encode($temp, JSON_HEX_APOS);
		$cmsPageArr['tilesDataJson'] = str_replace('\r\n', "<br>", $cmsPageArr['tilesDataJson']);
		$cmsPageArr['tilesDataJson'] = str_replace('\n',   "<br>", $cmsPageArr['tilesDataJson']);
		$cmsPageArr['tilesDataJson'] = str_replace('\r',   "<br>", $cmsPageArr['tilesDataJson']);
		$formattedTiles = $this->CollegeReviewLib->formatTileData($tiles);
		
		$cmsPageArr['formattedTilesData'] = $formattedTiles;
		$cmsPageArr['displayNumbers'] = json_encode($displayNumbers);
		$this->load->view('ReviewHomepageTiles/reviewHomepageTiles', $cmsPageArr);
    }

    private function _addNewTile($prodId,$maxSize){
    	$upload = array();
	    $uploadClient = new Upload_client();
    	$upload_error = array();
		if($_FILES['mobileTileImage']['tmp_name'][0] != '' && $_FILES['mobileTileImage']['size'][0] > $maxSize){
    		$upload_error['mobile'] = 'Image size exceeds '.($maxSize/1024).'KB.';
		}
	
		if($_FILES['desktopTileImage']['size'][0] > $maxSize){
    		$upload_error['desktop'] = 'Image size exceeds '.($maxSize/1024).'KB.';
		}

		if(!empty($upload_error)){
    		echo json_encode($upload_error);
    		exit;
		}
	
		$upload[] = $uploadClient->uploadFile($appId, 'image', $_FILES, array('mobile image', 'desktop image'), $prodId, "user", 'desktopTileImage');
	
		if($_FILES['mobileTileImage']['tmp_name'][0] != ''){
    		$upload[] = $uploadClient->uploadFile($appId, 'image', $_FILES, array('mobile image', 'desktop image'), $prodId, "user", 'mobileTileImage');
		}else{
	    	$upload[] = array();
		}

		$dbArr = array();
		$dbArr['title'] = ($this->input->post('tileTitle'));
		$dbArr['description'] = str_replace("\r\n", "<br>", trim($this->input->post('tileDescription'), "\r\n\t"));
		$dbArr['description'] = str_replace("\n", "<br>", $dbArr['description']);
		$dbArr['description'] = str_replace("\r", "<br>", $dbArr['description']);
		$dbArr['tilePlacement'] = $this->input->post('tilePlacement');
		$dbArr['tileOrder'] = $this->input->post('tilePosition');
		$dbArr['displayNumbers'] = $this->input->post('displayNumbers');
		
		if($this->input->post('tileType') == 'courseList'){
    
    		$dbArr['courseIds'] = $this->input->post('tileTypeText');
    		$tempSeoUrl = parse_url($this->input->post('tileSeoUrl'));
    		$dbArr['seoUrl'] = $tempSeoUrl['path'];
    		$dbArr['seoPageTitle'] = ($this->input->post('tileSeoTitle'));
    		$dbArr['seoPageDescription'] = str_replace("\r\n", "<br>", trim($this->input->post('tileSeoDescription'), "\r\n\t"));
    		$dbArr['seoPageDescription'] = str_replace("\n",   "<br>", $dbArr['seoPageDescription']);
    		$dbArr['seoPageDescription'] = str_replace("\r",   "<br>", $dbArr['seoPageDescription']);
	
		}else if($this->input->post('tileType') == 'url'){
    
    		$dbArr['url'] = $this->input->post('tileTypeText');
		}
	
		$streamId = $this->input->post('tileStream');
		$baseCourseId = $this->input->post('tileBaseCourse');
		$substreamId = $this->input->post('tileSubstream');
		$educationType = $this->input->post('tileEducationType');
		$dbArr['streamId'] = $streamId;
		$dbArr['baseCourseId'] = $baseCourseId;
		$dbArr['substreamId'] = $substreamId;
		$dbArr['educationType'] = $educationType;
		$tempDImage = parse_url($upload[0][0]['imageurl']);
		$dbArr['dImage'] = $tempDImage['path'];
		$tempMImage = ($upload[1][0]['imageurl']=='')?$dbArr['dImage']:$upload[1][0]['imageurl'];
		$tempMImage = parse_url($tempMImage);
		$dbArr['mImage'] = $tempMImage['path'];
		$dbArr['publishDateTime'] = date('Y-m-d H:i:s');
		$dbArr['status'] = 'live';
		
		if($dbArr['mImage']!='' && $dbArr['dImage']!='')
	    	$this->crmodel->addCMSTileData($dbArr);
		else
	    	error_log('CollegeReviewHomepageTileCMSImageUploadError');
		exit;
    }

    private function _editTileCMS($prodId,$maxSize){
    	$upload = array();
	    $uploadClient = new Upload_client();
    	$dbArr = array();
		$upload_error = array();
	
		if($_FILES['mobileTileImage']['tmp_name'][0] != '' && $_FILES['mobileTileImage']['size'][0] > $maxSize){
	    	$upload_error['mobile'] = 'Image size exceeds '.($maxSize/1024).'KB.';
		}
	
		if($_FILES['desktopTileImage']['tmp_name'][0] && $_FILES['desktopTileImage']['size'][0] > $maxSize){
	    	$upload_error['desktop'] = 'Image size exceeds '.($maxSize/1024).'KB.';
		}
	
		if(!empty($upload_error)){
	    	echo json_encode($upload_error);
	    	exit;
		}		
	
		if($_FILES['desktopTileImage']['tmp_name'][0] != ''){
	    	$upload = $uploadClient->uploadFile($appId, 'image', $_FILES, array('mobile image', 'desktop image'), $prodId, "user", 'desktopTileImage');
	    	$dImage = parse_url($upload[0]['imageurl']);
	    	$dbArr['dImage'] = $dbArr['mImage'] = $dImage['path'];
		}
	
		if($_FILES['mobileTileImage']['tmp_name'][0] != ''){
	    	$upload = $uploadClient->uploadFile($appId, 'image', $_FILES, array('mobile image', 'desktop image'), $prodId, "user", 'mobileTileImage');
	    	$mImage = parse_url($upload[0]['imageurl']);
	    	$dbArr['mImage'] = $mImage['path'];
		}
	
		$dbArr['title'] = ($this->input->post('tileTitle'));
		$dbArr['description'] = str_replace("\r\n", "<br>", trim($this->input->post('tileDescription'), "\r\n\t"));
		$dbArr['description'] = str_replace("\n", "<br>", $dbArr['description']);
		$dbArr['description'] = str_replace("\r", "<br>", $dbArr['description']);
		$dbArr['tilePlacement'] = $this->input->post('tilePlacement');
		$dbArr['tileOrder'] = $this->input->post('tilePosition');
		$dbArr['displayNumbers'] = $this->input->post('displayNumbers');
	
		if($this->input->post('tileType') == 'courseList'){
	    	$dbArr['courseIds'] = $this->input->post('tileTypeText');
	    	$tempSeoUrl = parse_url($this->input->post('tileSeoUrl'));
    		$dbArr['seoUrl'] = $tempSeoUrl['path'];
	    	$dbArr['seoPageTitle'] = ($this->input->post('tileSeoTitle'));
	    	$dbArr['seoPageDescription'] = str_replace("\r\n", "<br>", trim($this->input->post('tileSeoDescription'), "\r\n\t"));
	    	$dbArr['seoPageDescription'] = str_replace("\n",   "<br>", $dbArr['seoPageDescription']);
	    	$dbArr['seoPageDescription'] = str_replace("\r",   "<br>", $dbArr['seoPageDescription']);
	    	$dbArr['url'] = '';
		
		}else if($this->input->post('tileType') == 'url'){
	    	$dbArr['url'] = $this->input->post('tileTypeText');
	    	$dbArr['seoUrl'] = '';
	    	$dbArr['seoPageTitle'] = '';
	    	$dbArr['seoPageDescription'] = '';
	    	$dbArr['courseIds'] = '';
		}
	
		$dbArr['publishDateTime'] = date('Y-m-d H:i:s');
		$tileId = $this->input->post('tileId');
		$this->crmodel->updateCMSTileData($tileId, $dbArr);
		exit;
    }

    private function _tileCMSAction($cmsAction, $tileId){
    	$dbArr = array();
		$dbArr['publishDateTime'] = date('Y-m-d H:i:s');
		if($cmsAction == 'removeTile'){
			
			$dbArr['status'] = 'deleted';
		}else if($cmsAction == 'deactivateTile'){
			
			$dbArr['subStatus'] = 'deactivated';
		}else if($cmsAction == 'activateTile'){
			
			$dbArr['subStatus'] = 'activated';
		}
		
		$this->crmodel->updateCMSTileData($tileId, $dbArr);
		die('done');
    }
    
    function reOrderCMSTiles()
    {
	if($this->input->is_ajax_request())
	{
	    $newOrderStr = (isset($_POST['newOrderStr']) && $_POST['newOrderStr']!='')?$this->input->post('newOrderStr'):'';
	    if($newOrderStr!='')
	    {
		$newOrderArr = explode(',', $newOrderStr);
		$dbArr['publishDateTime'] = date('Y-m-d H:i:s');
		foreach($newOrderArr as $key=>$tileId)
		{
		    $dbArr['tileOrder'] = $key+1;
		    $this->crmodel->updateCMSTileData($tileId, $dbArr);
		}
		echo 'done';
	    }
	    else
	    {
		echo 'Some error with data.';
	    }
	}
    }
}
?>
