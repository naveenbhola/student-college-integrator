<?php

class ExamWidget extends MX_Controller {

    public function __construct(){
        $this->interlinkingLibrary = $this->load->library('Interlinking/InterlinkingLibrary');

        $this->examPageModel = $this->load->model('examPages/exampagemodel');

        $this->load->helper(array('image'));
    }

    /*  Input Format -
        entityIds['exam'] => array of unique exam ids
        entityIds['course'] => array of unique base course ids
        entityIds['university'] => array of unique university ids
        entityIds['college'] => array of unique college ids

        Output -
        View of widget
     */
    function getRelatedExamWidget($entityIds, $pageType , $ampViewFlag=false) {
        /*$entityIds['exam'] = array('3275', '306', '307', '309', '327', '9211');
        $entityIds['course'] = array('101', '26', '30', '10', '102');
        $entityIds['university'] = array('20576', '24642', '24752', '4026');
        $entityIds['college'] = array('19333', '843', '23539', '25138');
        */

        $this->benchmark->mark('Bottom_Exam_Interlinking_Widget_start');

        if(empty($entityIds)) {
            echo '';
        }
        
        $data['examIds'] = $this->interlinkingLibrary->getRelatedExams($entityIds);
        $formattedData = $this->formatData($data, $pageType);

        if(!empty($data)) {
            //View load mobile
            if(isMobileRequest()) {
                if($ampViewFlag) {
                    $this->load->view('Interlinking/ampRelatedExamsWidget',$formattedData);
                }
                else{
                    $this->load->view('Interlinking/mRelatedExamsWidget',$formattedData);
                }

            }
            else { //View load desktop
                $this->load->view('Interlinking/RelatedExamsWidget',$formattedData);
            }
        }
        $this->benchmark->mark('Bottom_Exam_Interlinking_Widget_end');
    }

    private function formatData($data,$pageType) {
        $formattedData = array();
        
        //exam object
        if(!empty($data['examIds'])) {
            //below line is used because of exampage revamp. In new cms, description fiels is not availble => contact App team
            //$examData = $this->examPageModel->getExamSectionDescription(implode(',', $data['examIds']));

            //getting Exam Detail from Exam Object
            $this->load->builder('examPages/ExamBuilder');
            $examBuilder    = new ExamBuilder();
            $examRepository = $examBuilder->getExamRepository();
            $examBaiscObject = $examRepository->findMultiple($data['examIds']);
            foreach ($examBaiscObject as $examObject) {
		$groupYear = '';
                $examGroups = $examObject->getGroupMappedToExam();
                foreach ($examGroups as $groupKey => $groupValue) {
                      if($groupValue['primaryGroup'] == 1)
                      {
                              $groupObj  = $examRepository->findGroup($groupValue['id']);
                              if(!empty($groupObj) && is_object($groupObj)){
                                  $mapping   = $groupObj->getEntitiesMappedToGroup();
                                  $groupYear = $mapping['year'][0];
                              }
                      }
                }
		
                $formattedData['recommendedExams']['data'][] = array('exam_id' => $examObject->getId(), 'exam_name' => $examObject->getName(), 'exam_url' => $examObject->getUrl(), 'description' => '', 'full_name' => $examObject->getFullName(), 'year' => $groupYear);		
            }
            $this->setWidgetHeading($formattedData['recommendedExams'],$pageType,'exam');
        }

        $formattedData['GA_PageType'] = ucfirst($pageType);
        $formattedData['pageType'] = $pageType;

        //Mobile only
        if(isMobileRequest()) {
            $formattedData['GA_Device'] = 'Mobile';
        } 

        //Desktop only
        else {
            $formattedData['GA_Device'] = 'Desktop';
        }

        return $formattedData;
    }

    private function setTrackingKeyIds(&$data,$pageType){
        if(isMobileRequest()){
            switch ($pageType) {
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1266;
                    break;
            }
        }
        else{
            switch($pageType){
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1265;
                    break;
            }
        }
    }

    private function setWidgetHeading(&$data,$pageType,$widgetType){
        switch ($pageType) {
            case 'articleDetailPage':
                $widgetHeading = 'Exams you may be interested in';
                break;
        }
        $data['widgetHeading'] = $widgetHeading;
    }

    function getUpcomingExamDatesWidget($entityIds,$pageType,$ampViewFlag=false){
        $entityIdMapping = $this->interlinkingLibrary->getUpcomingExamDatesEntityId($entityIds);
        if(!empty($entityIdMapping['entityId']) && !empty($entityIdMapping['entityType'])){
            $upcomingLibrary = $this->load->library('examPages/UpcomingExamsLib');
            $widgetData = $upcomingLibrary->getUpcomingExams($entityIdMapping['entityId'],$entityIdMapping['entityType']); 
            $displayData['widgetData'] = $widgetData['examTuples'];
            $displayData['allExamUrl'] = $widgetData['allExamUrl'];
            $displayData['pageType'] = $pageType;
            $displayData['upcomingExamHeading'] = $entityIdMapping['upcomingExamHeading'];

            if(!empty($displayData['allExamUrl'])){
                $displayData['allExamUrl'] = addingDomainNameToUrl(array('url' =>$displayData['allExamUrl'],'domainName' => SHIKSHA_HOME));
            }            
            if(!empty($widgetData)){
		if(isMobileRequest() || $pageType=='articleDetailPageDesktop'){
		    if($pageType=='articleDetailPageDesktop'){
                        $displayData['gaDevice'] = 'DESK';
                    }else{
                         $displayData['gaDevice'] = 'MOB';
                    }
                    $displayData['isAmp'] = $ampViewFlag;
                    $this->load->view('Interlinking/upcomingExamDatesWidget',$displayData);
                }else{
                    $displayData['gaDevice'] = 'DESK';

                }
            }   
            
        }
        
    }
}
