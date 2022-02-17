<?php

class BaseEntitiesBuilder{
    private $CI;
    private static $instance;
    private $repoArr = array();
    
    private function __construct() {
        $this->CI = & get_instance();    
        $this->model  = $this->CI->load->model('listingBase/listingbasemodel');
        $this->CI->load->library('listingBase/cache/ListingBaseCache');
        $this->CI->load->repository('ListingBaseRepository','listingBase');
    }

    static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new BaseEntitiesBuilder();
        }
        return self::$instance;
    }

    public function getHierarchyRepository() {
        if(empty($this->repoArr['hierarchy'])){
            $this->CI->load->repository('HierarchyRepository','listingBase');
            $hierarchymodel = $this->CI->load->model('listingBase/hierarchymodel');
            
            $hierarchyRepo      =  new HierarchyRepository(new ListingBaseCache(), $hierarchymodel);
            
            $streamRepo         = $this->getStreamRepository();
            $subStreamRepo      = $this->getSubstreamRepository();
            $specRepo           = $this->getSpecializationRepository();
            $this->repoArr['hierarchy'] = $hierarchyRepo->_setDependencies($streamRepo, $subStreamRepo, $specRepo);
        }
        return $this->repoArr['hierarchy'];
    }

    public function getStreamRepository() {
        if(empty($this->repoArr['stream'])){
            $this->CI->load->repository('StreamRepository','listingBase');
            $model = $this->CI->load->model('listingBase/streammodel');
            $this->repoArr['stream'] = new StreamRepository(new ListingBaseCache(), $model);
        }
        return $this->repoArr['stream'];
    }

    public function getSubstreamRepository() {
        if(empty($this->repoArr['substream'])){
            $this->CI->load->repository('SubstreamRepository','listingBase');
            $model = $this->CI->load->model('listingBase/substreammodel');
            $this->repoArr['substream'] = new SubstreamRepository(new ListingBaseCache(), $model);
        }
        return $this->repoArr['substream'];
    }

    public function getSpecializationRepository() {
        if(empty($this->repoArr['specialization'])){
            $this->CI->load->repository('SpecializationRepository','listingBase');
            $model = $this->CI->load->model('listingBase/specializationmodel');
            $this->repoArr['specialization'] = new SpecializationRepository(new ListingBaseCache(), $model);
        }
        return $this->repoArr['specialization'];
    }

    public function getCertificateProviderRepository(){
        if(empty($this->repoArr['certificateProvider'])){
            $this->CI->load->repository('CertificateProviderRepository','listingBase');
            $model = $this->CI->load->model('listingBase/certificatemodel');
            $hierarchyRepo      = $this->getHierarchyRepository();
            $courseRepo         = $this->getBaseCourseRepository();
            $certificateRepo    =  new CertificateProviderRepository(new ListingBaseCache(), $model);
            $this->repoArr['certificateProvider'] = $certificateRepo->_setDependencies($hierarchyRepo,$courseRepo);
        }
        return $this->repoArr['certificateProvider'];
    }

    public function getPopularGroupRepository(){
        if(empty($this->repoArr['popularGroup'])){
            $this->CI->load->repository('PopularGroupRepository','listingBase');
            $model = $this->CI->load->model('listingBase/populargroupmodel');
            $hierarchyRepo      = $this->getHierarchyRepository();
            $popularGroupRepo    =  new PopularGroupRepository(new ListingBaseCache(), $model);
            $this->repoArr['popularGroup'] =  $popularGroupRepo->_setDependencies($hierarchyRepo);
        }
        return $this->repoArr['popularGroup'];
    }


    public function getBaseCourseRepository() {
        if(empty($this->repoArr['baseCourse'])){
            $this->CI->load->repository('BaseCourseRepository','listingBase');
            $model = $this->CI->load->model('listingBase/basecoursemodel');
            $BaseCourseRepository = new BaseCourseRepository(new ListingBaseCache(),$model);
            $this->repoArr['baseCourse'] = $BaseCourseRepository->_setDependencies($this->getHierarchyRepository());
        }
        return $this->repoArr['baseCourse'];
    }
}

class ListingBaseBuilder{
    private $builderInstance;

    function __construct(){
        $this->builderInstance = BaseEntitiesBuilder::getInstance();
    }

    public function __call($method,$arguments){
        return call_user_func_array(array($this->builderInstance,$method),$arguments);
    }
}