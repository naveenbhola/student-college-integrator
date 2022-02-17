<?php

class HierarchyWidget extends MX_Controller {
    
    public function __construct() {
	    $this->load->library('Interlinking/InterlinkingLibrary');
        
        $this->load->config("Interlinking/InterlinkingConfig");
	    $this->srpCity = $this->config->item('srpCity');		

        $this->load->builder("listingBase/ListingBaseBuilder");
        $baseCourseBuilder = new ListingBaseBuilder();
        $this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
    }

    public function getStreamWidget($entityIds, $pageType,$ampViewFlag=false) {
        $this->benchmark->mark('RHS_Stream_Interlinking_Widget_start');

        if(empty($entityIds['primaryHierarchy']['stream'])) {
            return;
        }

        $this->benchmark->mark('RHS_Stream_Interlinking_Static_Links_start');
        $staticLinks = $this->interlinkinglibrary->getStaticLinks($entityIds, 'stream');
        $this->benchmark->mark('RHS_Stream_Interlinking_Static_Links_end');

        $this->benchmark->mark('RHS_Stream_Interlinking_CTP_start');
        $data = $this->interlinkinglibrary->getWidgetCTPinfoForStream($entityIds);
        $this->benchmark->mark('RHS_Stream_Interlinking_CTP_end');

        //if not ctp link, show srp link
        if(count($data['allIndiaLink'])==0 || count($data['cityLinks'])==0){
            $customParamsSrp = array();
            $customParamsSrp['keyword']         = $staticLinks['widgetHeading'];
            $customParamsSrp['entityId']        = $entityIds['primaryHierarchy']['stream']; // always single
            $customParamsSrp['entityType']      = 'stream'; 
            $customParamsSrp['substream']  = array($entityIds['primaryHierarchy']['substream']); //never used
            $customParamsSrp['course']  = $entityIds['course']; 
            $customParamsSrp['education_type']  = $entityIds['education_type']; 
            $customParamsSrp['delivery_method']  = $entityIds['delivery_method']; 
            $customParamsSrp['city']            = $this->srpCity;
            $this->setWidgetType($customParamsSrp,$pageType);

            $this->benchmark->mark('RHS_Stream_Interlinking_SRP_start');
            $data = $this->interlinkinglibrary->getWidgetSRPinfo($customParamsSrp);
            $this->benchmark->mark('RHS_Stream_Interlinking_SRP_end');
        }

        if(!empty($staticLinks)) {
            $data = array_merge($data, $staticLinks);
        }

        $this->benchmark->mark('RHS_Stream_Interlinking_View_start');
        if(isMobileRequest()) {
            if($ampViewFlag){
                $this->load->view('Interlinking/ampHierarchyCardWidget',$data);
            }
            else{
                $this->load->view('Interlinking/mHierarchyCardWidget',$data);
            }
        }
        else {
            $this->load->view('Interlinking/hierarchyCardWidget',$data);
        }
        $this->benchmark->mark('RHS_Stream_Interlinking_View_end');

        $this->benchmark->mark('RHS_Stream_Interlinking_Widget_end');
    }

    private function setWidgetType(&$data,$pageType){
        switch ($pageType) {
            case 'articleDetailPage':
                $data['widgetType'] = 'ArticleInterLinking';
                break;
            
            default:
                break;
        }
    }

    public function getSubstreamWidget($entityIds, $pageType,$ampViewFlag=false) {
        $this->benchmark->mark('RHS_Substream_Interlinking_Widget_start');
        if(empty($entityIds['primaryHierarchy']['stream']) && empty($entityIds['primaryHierarchy']['substream'])) {
            return;
        }

        $staticLinks = $this->interlinkinglibrary->getStaticLinks($entityIds, 'substream');

        $this->benchmark->mark('RHS_Substream_Interlinking_getWidgetCTPinfoForSubstream_Widget_start');
        $data = $this->interlinkinglibrary->getWidgetCTPinfoForSubstream($entityIds);
        $this->benchmark->mark('RHS_Substream_Interlinking_getWidgetCTPinfoForSubstream_Widget_end');

        //if not ctp link, show srp link
        if(count($data['allIndiaLink'])==0 || count($data['cityLinks'])==0){
            $customParamsSrp = array();
            $customParamsSrp['keyword']         = $staticLinks['widgetHeading'];
            $customParamsSrp['entityId']        = $entityIds['primaryHierarchy']['substream']; // always single
            $customParamsSrp['entityType']      = 'substream'; 
            $customParamsSrp['stream']  = array($entityIds['primaryHierarchy']['stream']); 
            $customParamsSrp['course']  = $entityIds['course']; 
            $customParamsSrp['education_type']  = $entityIds['education_type']; 
            $customParamsSrp['delivery_method']  = $entityIds['delivery_method']; 
            $customParamsSrp['city']            = $this->srpCity;
            $this->setWidgetType($customParamsSrp,$pageType);
            $this->benchmark->mark('RHS_Substream_Interlinking_getWidgetSRPinfo_Widget_start');
            $data = $this->interlinkinglibrary->getWidgetSRPinfo($customParamsSrp);
            $this->benchmark->mark('RHS_Substream_Interlinking_getWidgetSRPinfo_Widget_end');
        }

        if(!empty($staticLinks)) {
            $data = array_merge($data, $staticLinks);
        }

        if(isMobileRequest()) {
            if($ampViewFlag){
                $this->load->view('Interlinking/ampHierarchyCardWidget',$data);
            }
            else{
                $this->load->view('Interlinking/mHierarchyCardWidget',$data);
            }
        }
        else {
            $this->load->view('Interlinking/hierarchyCardWidget',$data);
        }
        $this->benchmark->mark('RHS_Substream_Interlinking_Widget_end');
    }

    public function getPopularCourseWidget($entityIds, $pageType, $baseCourseObj,$ampViewFlag=false) {
        $this->benchmark->mark('RHS_Popular_Course_Interlinking_Widget_start');
        if(empty($entityIds['course'])){
            return array();
        }
        if(empty($baseCourseObj)) {
            $baseCourseObj = $this->baseCourseRepo->find($entityIds['course'][0]);
        }

        $staticLinks = $this->interlinkinglibrary->getStaticLinks($entityIds, 'course', array('baseCourseObj' => $baseCourseObj));

        $this->benchmark->mark('RHS_Popular_Course_Interlinking_getWidgetCTPinfoForPopularCourse_Widget_start');
        $data = $this->interlinkinglibrary->getWidgetCTPinfoForPopularCourse($entityIds);
        $this->benchmark->mark('RHS_Popular_Course_Interlinking_getWidgetCTPinfoForPopularCourse_Widget_end');

        //if not ctp link, show srp link
    	if(count($data['allIndiaLink'])==0 || count($data['cityLinks'])==0){
    		$customParamsSrp = array();
			$customParamsSrp['keyword']        = $staticLinks['widgetHeading'];
			$customParamsSrp['entityId']     = $entityIds['course'][0]; // always single
            $customParamsSrp['entityType']  = 'base_course'; 
            $customParamsSrp['stream']  = array($entityIds['primaryHierarchy']['stream']); 
            $customParamsSrp['substream']  = array($entityIds['primaryHierarchy']['substream']); 
            $customParamsSrp['education_type']  = $entityIds['education_type']; 
            $customParamsSrp['delivery_method']  = $entityIds['delivery_method']; 
			$customParamsSrp['city'] = $this->srpCity;
            $this->setWidgetType($customParamsSrp,$pageType);
            $this->benchmark->mark('RHS_Popular_Course_Interlinking_getWidgetSRPinfo_Widget_start');
    		$data = $this->interlinkinglibrary->getWidgetSRPinfo($customParamsSrp);
            $this->benchmark->mark('RHS_Popular_Course_Interlinking_getWidgetSRPinfo_Widget_end');
    	}
        
        if(!empty($staticLinks)) {
           $data = array_merge($data, $staticLinks);
        }

        if(isMobileRequest()) {
            if($ampViewFlag){
                $this->load->view('Interlinking/ampHierarchyCardWidget',$data);
            }
            else{
                $this->load->view('Interlinking/mHierarchyCardWidget',$data);
            }
        }    
        
        else {
            $this->load->view('Interlinking/hierarchyCardWidget',$data);
        }
        $this->benchmark->mark('RHS_Popular_Course_Interlinking_Widget_end');
    }
}
