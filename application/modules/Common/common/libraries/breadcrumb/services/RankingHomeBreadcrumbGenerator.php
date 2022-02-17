<?php
class RankingHomeBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	private $currentPageName= "Rankings";
	function __construct($options) {
		$this->subCategoryId = $options['subCategoryId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/CategoryPageRequest');
		$this->CategoryPageRequest = new CategoryPageRequest;
		$this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder    = new CategoryBuilder;
        $CategoryRepository = $categoryBuilder->getCategoryRepository();
        $this->CategoryObject = $CategoryRepository->find($this->subCategoryId);
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$directoryName = $this->CategoryObject->getSeoUrlDirectoryName();
		if(!empty($directoryName)) {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$this->CategoryPageRequest->setData(array("categoryId" => $this->CategoryObject->getParentId()));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);

			$this->Breadcrumbs->addItem($this->currentPageName, '');
			
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs(),true);
		}
	}

	function getBreadcrumbHtml($BreadCrumbs) {
		// die;
        $breadCrumbHtml = '<div class="breadcrumb2" style="width:100%;">';
        $totalBreadCrumbs = count($BreadCrumbs);
        $i = 0;
        foreach($BreadCrumbs as $SingleBreadcrumb) {
            if($i != ($totalBreadCrumbs -1)) {
                $breadCrumbHtml .= '<span itemscope itemtype="https://data-vocabulary.org/Breadcrumb">';
                $breadCrumbHtml .= '<a href="'.$SingleBreadcrumb->getUrl().'" itemprop="url"><span itemprop="title">'.  $SingleBreadcrumb->getText()   .'</span></a>';
                $breadCrumbHtml .= '</span>';
                $breadCrumbHtml .= '<span class="breadcrumb-arrow">&rsaquo;</span>';
            }
            else{
                $breadCrumbHtml .= '<span>'.  $SingleBreadcrumb->getText()   .'</span>';
            }
            $i++;
        }
        $breadCrumbHtml .= '</div>';
        return $breadCrumbHtml;
    }
}