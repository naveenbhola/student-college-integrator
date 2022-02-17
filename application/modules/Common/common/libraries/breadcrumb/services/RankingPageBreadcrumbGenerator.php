<?php
class RankingPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	private $currentPageName= "Rankings";
	private $CategoryObject;
	private $rankingPageRequest;
	private $rankingURLManager;
	private $request;
	function __construct($options) {
		// _p($options); die;
		$this->rankingPageRequest = $options['rankingPageRequest'];
		$this->rankingURLManager = $options['rankingURLManager'];
		// _p($this->rankingURLManager); die;
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
		$directoryName = $this->CategoryObject->getSeoUrlDirectoryName($this->subCategoryId);
		if(!empty($directoryName)) {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$this->CategoryPageRequest->setData(array("categoryId" => $this->CategoryObject->getParentId()));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);

			$this->Breadcrumbs->addItem($this->currentPageName, SHIKSHA_HOME.'/'.strtolower($directoryName).'/ranking');
			
			if($this->rankingPageRequest->getSpecializationId() >= 0){
				$rankingrequestclone = $this->rankingURLManager->getRankingPageRequest($this->rankingPageRequest->getPageId().'-2-0-0-0');
				$this->Breadcrumbs->addItem($this->rankingPageRequest->getPageName(), $this->rankingURLManager->buildURL($rankingrequestclone));
			}

			if($this->rankingPageRequest->getCityId() > 0 || $this->rankingPageRequest->getStateId() > 0 || $this->rankingPageRequest->getCountryId() > 0){
				if($this->rankingPageRequest->getCityId() > 0){
					$paramsclone = $this->rankingPageRequest->getPageId().'-0-0-'.$this->rankingPageRequest->getCityId().'-0';
					$loc = $this->rankingPageRequest->getCityName();
				}
				elseif($this->rankingPageRequest->getStateId() > 0){
					$paramsclone = $this->rankingPageRequest->getPageId().'-0-'.$this->rankingPageRequest->getStateId().'-0-0';
					$loc = $this->rankingPageRequest->getStateName();
				}
				else{
					$paramsclone = $this->rankingPageRequest->getPageId().'-'.$this->rankingPageRequest->getCountryId().'-0-0-0';
					$loc = $this->rankingPageRequest->getCountryName();
				}

				$rankingrequestclone = $this->rankingURLManager->getRankingPageRequest($paramsclone);
				$this->Breadcrumbs->addItem(ucfirst($loc), $this->rankingURLManager->buildURL($rankingrequestclone));
			}

			if($this->rankingPageRequest->getExamId() > 0){
				$this->Breadcrumbs->addItem(ucfirst($this->rankingPageRequest->getExamName()), $this->rankingURLManager->buildURL($rankingPageRequest));
			}
			
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs(),true);
		}
	}

	function getBreadcrumbHtml($BreadCrumbs) {
		// die;
        $breadCrumbHtml = '<div class="breadcrumb2">';
        $totalBreadCrumbs = count($BreadCrumbs);
        $i = 0;
        foreach($BreadCrumbs as $SingleBreadcrumb) {
            if($i != ($totalBreadCrumbs -1)) {
                $breadCrumbHtml .= '<span itemscope itemtype="https://data-vocabulary.org/Breadcrumb">';
                $breadCrumbHtml .= '<a href="'.$SingleBreadcrumb->getUrl().'" itemprop="url"><span itemprop="title">'. htmlspecialchars($SingleBreadcrumb->getText())   .'</span></a>';
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