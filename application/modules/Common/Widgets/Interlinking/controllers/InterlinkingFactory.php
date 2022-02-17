<?php

class InterlinkingFactory extends MX_Controller {
    public function __construct() {
        $this->load->builder("listingBase/ListingBaseBuilder");
        $baseCourseBuilder = new ListingBaseBuilder();
        $this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
    }

    /*  Input Format -
        entityIds['primaryHierarchy']['stream'] => stream id
        entityIds['primaryHierarchy']['substream'] => substream id
        entityIds['primaryHierarchy']['specialization'] => specialization id
        entityIds['exam'] => array of unique exam ids
        entityIds['course'] => array of unique base course ids
        entityIds['university'] => array of unique university ids
        entityIds['college'] => array of unique college ids

        Output -
        View of widget
     */
    public function getEntityRHSWidget($entityIds, $pageType, $cardLimit = 4, $countOfCoursesPerInstitute = 3,$ampViewFlag=false,$entityId = null,$entityType = null) {
        // $entityIds['primaryHierarchy']['stream'] = 1;
        // $entityIds['primaryHierarchy']['substream'] = 0;
        // $entityIds['primaryHierarchy']['specialization'] = 0;
        // $entityIds['course'] = array('101', '26', '30', '10', '102');
        // $entityIds['course'] = array('101');
        // $entityIds['university'] = array('20576', '24642', '24752', '4026');
        // $entityIds['college'] = array('19333', '843', '25138');
        // $entityIds['otherAttribute'] = array('20', '21');

        $this->benchmark->mark('RHS_Interlinking_Widget_Total_start');

        //Institute card case
        if(!empty($entityIds['university']) || !empty($entityIds['college']) || !empty($entityIds['exam'])) {
            echo Modules::run("Interlinking/InstituteWidget/getInstitutesWidget", $entityIds, $pageType, $cardLimit, $countOfCoursesPerInstitute,$ampViewFlag,$entityId,$entityType);
        }
        //Stream or popular course card case
        else {
            //show popular course card if there is one base course
            if(count($entityIds['course']) == 1 && $pageType != 'questionDetailPage') {
                $baseCourseObj = $this->baseCourseRepo->find($entityIds['course'][0]);
                $popularCourse = $baseCourseObj->getIsPopular();

                if($popularCourse) {
                   echo Modules::run("Interlinking/HierarchyWidget/getPopularCourseWidget", $entityIds, $pageType, $baseCourseObj,$ampViewFlag);
                }
            }

            if(!empty($entityIds['course']) && $pageType == 'questionDetailPage'){  
                echo Modules::run("Interlinking/HierarchyWidget/getPopularCourseWidget", $entityIds, $pageType, $baseCourseObj,$ampViewFlag);   
                $popularCourse = 1;             
            }else{
                if($pageType == 'questionDetailPage'){
                    $popularCourse = 0;
                }
            }
            //show stream + substream card
            if(!empty($entityIds['primaryHierarchy']['stream']) && !$popularCourse && $pageType != 'questionDetailPage') {
                if(!empty($entityIds['primaryHierarchy']['substream'])) {
                    echo Modules::run("Interlinking/HierarchyWidget/getSubstreamWidget", $entityIds, $pageType,$ampViewFlag);
                }
                else {
                    echo Modules::run("Interlinking/HierarchyWidget/getStreamWidget", $entityIds, $pageType,$ampViewFlag);
                }
            }

            if((!empty($entityIds['primaryHierarchy']['stream']) || !empty($entityIds['primaryHierarchy']['substream'])) && $popularCourse == 0 && $pageType == 'questionDetailPage') {
                if(!empty($entityIds['primaryHierarchy']['substream'])) {
                    echo Modules::run("Interlinking/HierarchyWidget/getSubstreamWidget", $entityIds, $pageType,$ampViewFlag);
                }
                else {
                    echo Modules::run("Interlinking/HierarchyWidget/getStreamWidget", $entityIds, $pageType,$ampViewFlag);
                }

            }

        }
        $this->benchmark->mark('RHS_Interlinking_Widget_Total_end');
    }
}