<?php
class EnterpriseDataGrid extends MX_Controller
{
	private $tab;
	private $fm; //Form  Manager
	
	function __construct(){
		//error_reporting(E_ALL);
		//ini_set('memory_limit', '3072M');
		parent::__construct();
		$this->load->model('onlineFormEnterprise/onlineformenterprise_model');
		$this->onlineModel = new OnlineFormEnterprise_model();
		$this->onlinesecurity = new \onlineFormEnterprise\libraries\OnlineFormSecurity();
	}
	
	private function _init($tabId){
		$this->tab = \onlineFormEnterprise\builders\TabBuilder::getTab($tabId);
		$this->fm = new \Online\managers\UserFormManager("EnterpriseDataGrid");
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$this->courseRepository = $listingBuilder->getCourseRepository();
		$this->load->library(array('listing/listing_client'));
		$this->ListingClientObj = new Listing_client();
		
	}
	
	public function showTab($courseId = 0,$tabId = 0,$page = 1,$limit = 10,$download = 0){
		
		$validity = $this->onlinesecurity->getUser();
		if(!$validity) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        if((isset($validity['usergroup']) && $validity['usergroup']!='enterprise')){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
		
		if(!$this->onlinesecurity->checkCourse($courseId)){
			header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}
		
		if($download){ //First Step of Download
			$this->load->view('onlineFormEnterprise/EnterpriseDataGridExportStart');
		}
		
		
		
		$this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1,$validity['usergroup'],$validity['userid']);
		$this->load->library('sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        $myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$validity['userid']));
		
		
		
		$this->url = "/onlineFormEnterprise/EnterpriseDataGrid/showTab/".$courseId."/<tabId>/<page>/".$limit;
		$this->homeUrl = str_replace("<page>",1,str_replace("<tabId>",0,$this->url));
		
		
		$this->_init($tabId);
	
		if(!$this->tab->populateTabData($courseId)){
			header('location:'.$this->homeUrl);
			exit;
		}
		
			
		if($this->tab->getName() == "Analytics"){
			$limit = 0;
		}
		
	
		$filterSevice = $this->tab->getFilterService();
		$exculsionService = $this->tab->getExclusionSevice();
		$sorterService = $this->tab->getSorterService();
		
		$forms = $this->fm->populateForms($courseId,$filterSevice,$sorterService,$exculsionService);
		
		if($download){//Second Step to download
			$time = ceil(count($forms)/150)+100;
			echo "<br> This can take a few seconds.<br/>Please Wait(Do not close this window)...";
			echo str_pad('',4096)."\n";
			ob_flush();
			flush();
			set_time_limit($time);
		}
		
		$formIds = $sorterService->sortForms($this->fm->getSortableForms(),$forms,$page,$limit,$download);
		$forms = $this->fm->getFormByIds($courseId,$formIds);
		
		if($sorterService->getNoOfForms() && $sorterService->getPages() < $page){
			header('location:'.str_replace("<page>",1,str_replace("<tabId>",$tabId,$this->url)));
			exit;
		}
		
		$displayData = array();
		
		$displayData['validateuser'] = array($validity);
		$displayData['headerTabs'] = $headerTabs;
		$displayData['usergroup'] = $validity['usergroup'];
		$displayData['myProducts'] = $myProductDetails;
		$displayData['prodId'] = 777;
		
		$displayData['filterSevice'] = $filterSevice;
		$displayData['exculsionService'] = $exculsionService;
		$displayData['sorterService'] = $sorterService;
		$displayData['forms'] = $forms;
		$displayData['sortedForms'] = $sortedForms;
		
		if($tabId == 0){
			$displayData['headings'] = $this->fm->getFields();
		}else{
			$displayData['headings'] =  $exculsionService->getDisplayableFields($this->fm->getFields());
		}
		
		$displayData['fields'] = $this->fm->getFields();
		$displayData['course'] = $this->courseRepository->find($courseId);
		$displayData['fm'] = $this->fm;
		$displayData['tab'] = $this->tab;
		$displayData['homeUrl'] =  $this->homeUrl;
		$displayData['url'] =  $this->url;
		$displayData['limit'] =  $limit;
		$displayData['page'] =  $page;
		$displayData['tabId'] =  $tabId;
		$displayData['tabs'] =  $this->_getAllTabData($courseId);
		$displayData['filterKey'] =  $this->tab->getKey();
		$displayData['institute'] = $this->instituteRepository->find($this->ListingClientObj->getInstituteIdForCourseId(1,$courseId));
		if(!$download){
			$this->load->view('onlineFormEnterprise/EnterpriseDataGrid',$displayData);
		}elseif($download){
			$this->_downloadData($displayData);
			ob_end_flush();
		}
		
	}
	
	public function downloadFile($fileName,$courseId){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		$fileName = base64_decode($fileName);
		$filecontents = file_get_contents('/tmp/'.$fileName);
		if($filecontents){
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename='.$fileName);
			echo $filecontents;
		}else{
			$this->load->view('onlineFormEnterprise/EnterpriseDataGridExportError');
		}
	}
	
	
	private function _downloadData($data){
		$fileName =  preg_replace("/[^a-z0-9_]+/i", "_",$data['tab']->getName())."_".date("Ymd").'_'.date("Gis").'.xls';
		$this->load->library('common/PHPExcel');
		$sheet = $this->phpexcel->getActiveSheet();
		$style_header = array(                  
			'font' => array(
				'bold' => true,
			)
		);
		$sheet->getStyle(1)->applyFromArray($style_header);
		$finalArray = array();
		$row = array();
		foreach($data['headings'] as $heading){
			$row[] = $heading['name'];
		}
		$finalArray[] = $row;
		foreach($data['forms'] as $key=>$form){
			$row = array();
			foreach($data['headings'] as $fieldId=>$field){	
				$row[] = $data['fm']->getFieldValue($form,$fieldId,true);
			}
			$finalArray[] = $row;
		}
		$sheet->fromArray($finalArray);
		$this->_autoFitColumnWidthToContent($sheet);
		$writer =  PHPExcel_IOFactory::createWriter($this->phpexcel, "Excel5");
		$writer->save('/tmp/'.$fileName);
		$displayData['fileName'] = $fileName;
		$displayData['courseId'] = $data['course']->getId();
		$this->load->view('onlineFormEnterprise/EnterpriseDataGridExport',$displayData);
	}
	
	private function _autoFitColumnWidthToContent($sheet, $fromCol = 'A', $toCol) {
        if (empty($toCol) ) {
            $toCol = $sheet->getColumnDimension($sheet->getHighestColumn())->getColumnIndex();
        }
        for($i = $fromCol; $i != $toCol; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }
        $sheet->calculateColumnWidths();
    }

	
	public function saveTab($courseId, $tabName){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->addTab($courseId, url_base64_decode($tabName));
	}
	
	
	private function _getAllTabData($courseId){
		return $this->onlineModel->getAllTabData($courseId);
	}
	
	
	public function deleteTab($courseId, $tabId){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->deleteTab($courseId, $tabId);
	}
	
	public function saveColumn($courseId, $columnName){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->addColumn($courseId, url_base64_decode($columnName));
	}
	
	
	public function saveField($courseId,$formId,$fieldId,$formType,$data){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->saveField($formId,$fieldId,$formType,url_base64_decode($data));
	}
	
	
	public function deleteForms($tabId,$courseId,$formsArray){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->deleteForms($tabId, json_decode(base64_decode($formsArray),true));
	}
	
	
	public function deleteColumn($tabId,$courseId,$field){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->deleteColumn($tabId, $field);
	}
	
	
	public function saveGraph($tabId,$courseId,$graphs){
		if(!$this->onlinesecurity->checkCourse($courseId)){
			exit();
		}
		echo $this->onlineModel->saveGraph($tabId, json_decode(base64_decode($graphs),true));
	}
	
}
