<?php 
class ResourcesHomeController extends MX_Controller{

	function index(){
		//to handle mba domain removal
		$currentUrl = getCurrentPageURL();
		$splitCurrentUrl   = explode('?',$currentUrl);
		if(isset($splitCurrentUrl[1]) && !empty($splitCurrentUrl[1])) {
			$redirectUrl = SHIKSHA_HOME.'/mba/resources'."?".$splitCurrentUrl[1];
		} else {
			$redirectUrl = SHIKSHA_HOME.'/mba/resources';	
		}
		if(strcmp($splitCurrentUrl[0], SHIKSHA_HOME.'/mba/resources') !== 0) {
			header("Location: $redirectUrl",TRUE,301);
			exit;
		}

		$subCategoryId = '';
		if(strpos(strtolower($_SERVER['REQUEST_URI']), 'mba') !== false){
			$subCategoryId = 23;
		}
		else{
			show_404();
		}
		$breadcrumbOptions = array('generatorType' 	=> 'ResourcesHome',
									'options' => array('courseId'	=>	MANAGEMENT_COURSE,'directoryName'=>'MBA'));
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);

		$data = array('title'=>'Resources Home','canonicalURL'=>SHIKSHA_HOME.$_SERVER['REQUEST_URI'],
			'metaDescription'=>'Find college reviews alumni data exam calender application forms discussions questions news and articles faq',
			'metaKeywords' => 'Find college reviews alumni data exam calender application forms discussions questions news and articles faq');
		$data['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
		$data['validateuser'] 		= $this->checkUserValidation();

		$this->load->view('common/ResourcesHomeView',$data);
	}
}

?>