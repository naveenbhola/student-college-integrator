<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class MediaPost extends AbstractListingPost
{
    private $mediaPostModel;
    
    function __construct()
    {
		parent::__construct();
		
        $this->load->model('listing/posting/mediapostmodel');
        $this->mediaPostModel = new MediaPostModel;
    }
    
	function uploadMedia($formId, $mediaType)
	{   $startTime = microtime(true);
		$fileCaption = $this->input->post('fileNameCaption');
		$instituteLocationId = $this->input->post('institute_location_id');
		$fileName = split("[/\\.]",$_FILES['mediaFile']['name'][0]);
		$fileExtension = $fileName[count($fileName) - 1];
		$fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
		$listingId = $this->input->post('listingId');
		$listingType = $this->input->post('listingType');
		
		switch($mediaType) {
			case 'photos':
				$mediaDataType = 'image';
				$listingMediaType = 'photos';
				$FILES = $_FILES;
				break;
			case 'videos':
				$mediaDataType = 'ytvideo';
				$listingMediaType = 'videos';
				$FILES = $_POST['mediaFile'];
				break;
			case 'documents':
				$mediaDataType = 'pdf';
				$listingMediaType = 'doc';
				$FILES = $_FILES;
				break;
		}

		/**
		 * Upload the media
		 */ 
		$this->load->library('upload_client');
		$uploadClient = new Upload_client();
		$uploadResponse = $uploadClient->uploadFile(1,$mediaDataType,$FILES,array($fileCaption),$listingId, $listingType,'mediaFile');
		
		$displayData = array();
		
		if(is_array($uploadResponse)) {
			if($uploadResponse['status'] == 1) {
				for($k = 0;$k < $uploadResponse['max'] ; $k++){
					//It will always be 1 :-). Added for future cases if multiple uploads will be asked in one go.
					$mediaData = array();
					$mediaData['mediaId'] = $uploadResponse[$k]['mediaid'];
					$mediaData['mediaUrl'] = $uploadResponse[$k]['imageurl'];
					$mediaData['mediaName'] = $uploadResponse[$k]['title'];
					$mediaData['mediaThumbUrl'] = $uploadResponse[$k]['thumburl'] ? $uploadResponse[$k]['thumburl'] : '';
					$mediaData['mediaType'] = $this->getMediaType($listingMediaType);
					$mediaData['institute_location_id'] = $instituteLocationId;
					$this->mediaPostModel->addMediaToListing($listingId,$listingType,$mediaData);
				}
			}
			$displayData['fileId'] = $mediaData['mediaId'];
			$displayData['fileName'] = $fileCaption;
			$displayData['mediaType'] = $mediaType;
			$displayData['fileUrl'] = $mediaData['mediaUrl'];
			$displayData['fileThumbUrl'] = $mediaData['mediaThumbUrl'];
		}
		else {
			$displayData['error'] = $upload_forms;
		}
		
		$displayData['formId'] = $formId;
		echo json_encode($displayData);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	function updateMediaField()
	{
		$startTime = microtime(true);
		$fieldName = $this->input->post('fieldName');
		
		if($fieldName == 'fileNameCaption') {
			$fieldName = 'name';
			$fieldValue = $this->input->post('fieldValue');
			$fileId = $this->input->post('fileId');
			$fileType = $this->input->post('fileType');
			$fileType = $this->getMediaType($fileType);
			$listingType = $this->input->post('listingType');
			$listingId = $this->input->post('listingId');	
			$this->mediaPostModel->updateListingMediaAttributes($listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue);
		}
		echo json_encode(array('status' => 'success'));
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	function deleteMedia($listingType, $listingId, $fileType, $fileId)
	{
		$startTime = microtime(true);
		$fileType = $this->getMediaType($fileType);
		$this->mediaPostModel->removeMediaForListing($listingType, $listingId, $fileType, $fileId);
		echo json_encode(array('status' => 'success'));
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function uploadHeaderImage($headerImagetype = 'thumb')
	{
		$startTime = microtime(true);
		$sizeSpec = array(
			'thumb' => array('width' => 124,'height' => 104),
			'large' => array('width' => 303,'height' => 210)
		);
		
		if($_FILES['myImage']['tmp_name'][0] == '') {
			echo "Please select a photo to upload";
		}
		else {
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile(1,'image',$_FILES,array(),$userId,"user", 'myImage');
			if(!is_array($upload)) {
				echo $upload;
			}
			else {
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				if(!($width == $sizeSpec[$headerImagetype]['width'] && $height == $sizeSpec[$headerImagetype]['height'])) {
					echo 'Image size must be '.$sizeSpec[$headerImagetype]['width'].'*'.$sizeSpec[$headerImagetype]['height'].' px';
                } else {
					echo $upload[0]['imageurl'];
                }
			}
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function mapHeader()
	{
		$startTime = microtime(true);
		$noHeader = $_POST['noHeader'];
		$thumbURL= $_POST['thumbURL'];
		$largeURL= $_POST['largeURL'];
		$listingType = $_POST['listingType'];
		$listingId = $_POST['listingId'];
		$order = $_POST['order'];
		$instituteId = $_POST['instituteId'];
		$name = $_POST['name'];

		if(empty($thumbURL) || empty($largeURL) || empty($listingType) || empty($listingId) || empty($instituteId) || empty($name)) {
			return;
		}
		
		if($noHeader == 1) {
			$thumbURL = explode(',',$thumbURL);
			$largeURL = explode(',',$largeURL);
			$listingType = explode(',',$listingType);
			$listingId = explode(',',$listingId);
			$order = explode(',',$order);
			$instituteId = explode(',',$instituteId);
			$name = explode(',',$name);
			
			$this->mediaPostModel->mapHeaderImages($thumbURL,$largeURL,$listingType,$listingId,$order,$instituteId,$name);
		}
		else {
			$this->mediaPostModel->removeHeaderImages('institute',$instituteId);
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	function mapTopRecruitingCompanies()
	{
		$startTime = microtime(true);
		$noCompany = $_POST['noCompany'];
		$companyId = $_POST['logoId'];
		$listingType = $_POST['listingType'];
		$listingId = $_POST['listingId'];
		$order = $_POST['order'];
		$instituteId = $_POST['instituteId'];
		
		if($noCompany == 1) {
			$companyId = explode(',',$companyId);
			$listingType = explode(',',$listingType);
			$listingId = explode(',',$listingId);
			$order = explode(',',$order);
			$instituteId = explode(',',$instituteId);
			
			$this->mediaPostModel->mapTopRecruitingCompanies($companyId,$listingType,$listingId,$order,$instituteId);
		}
		else {
			$this->mediaPostModel->removeTopRecruitingCompanies($instituteId);
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
    function getMediaType($mediaType)
	{
		$startTime = microtime(true);
		switch($mediaType) {
			case 'documents' : $mediaType = 'doc'; break;
			case 'photos' : $mediaType = 'photo'; break;
			case 'videos' : $mediaType = 'video'; break;
		}
		return $mediaType;
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	function post()
	{
		$startTime = microtime(true);
		$mediaAssociation = json_decode($this->input->post('mediaAssoc'),TRUE);
		$mediaAssociationData = array();
		
		foreach($mediaAssociation as $mediaType => $mediaTypeAssociations) {
			foreach($mediaTypeAssociations as $mediaId => $mediaIdAssociations) {
				foreach($mediaIdAssociations as $mediaIdAssociation) {
					
					$mediaType = $this->getMediaType($mediaType);
					$listingType = $mediaIdAssociation['entityName'];
					
					$listingId = $mediaIdAssociation['entityValue'];
					if($listingId > 0){
						$mediaAssociationData[$listingType][$listingId][$mediaType][$mediaId] = 'addition';
					}
					
					$listingId = $mediaIdAssociation['removedEntity'];
					if($listingId > 0){
						$mediaAssociationData[$listingType][$listingId][$mediaType][$mediaId] = 'removal';
					}
					
					$listingId = $mediaIdAssociation['modifiedEntity'];
					if($listingId > 0){
						$mediaAssociationData[$listingType][$listingId][$mediaType][$mediaId] = 'removal';
					}
				}
			}
		}
		
		$listingType = $this->input->post('listingType');
		$listingId = $this->input->post('listingId');
		
		/**
		 * Edit comments
		 */ 
		$commentData = array();
		$commentData['mandatory_comments'] = $this->input->post('mandatory_comments');
		$commentData['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$commentData['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$commentData['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');
		
		$data = array();
		$data['mediaAssociationData'] = $mediaAssociationData;
		$data['commentData'] = $commentData;
		
		$updateStatus = $this->mediaPostModel->saveMedia($data);
		
		if($updateStatus){
			header("location:/enterprise/ShowForms/showPreviewPage/".$listingId);
		}
		else{
			header("location:/enterprise/ShowForms/showMediaInstituteForm/institute/".$listingId);
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
}
