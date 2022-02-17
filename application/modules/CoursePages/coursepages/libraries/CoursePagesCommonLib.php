<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoursePagesCommonLib
 *
 * @author yathartha
 */
class CoursePagesCommonLib {

    private $_CI;

    public function __construct() {
        $this->_CI = &get_instance();
    }

    public function getCourseHomePageDictionary($returnOldFormat = true) {
        $courseCmsModel = $this->_CI->load->model('coursepages/coursepagecmsmodel');
        $coursePageCache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
        if ($coursePageCache->isCPGSCachingOn() && $courseHomePageDictionary = $coursePageCache->getCourseHomePageDictionary()) {
            
        } else {
            $courseHomePageDictionary = $courseCmsModel->getCourseHomePageBaseData();
            $coursePageCache->setCourseHomePageDictionary($courseHomePageDictionary);
        }
        $requiredCourseHomeDictFormat = array();
        $courseKeyToBeUsed = ($returnOldFormat == true ? 'oldsbCatId' : 'courseHomeId');
        foreach ($courseHomePageDictionary as $courseHomeDetail) {
            $requiredCourseHomeDictFormat[$courseHomeDetail[$courseKeyToBeUsed]] = $courseHomeDetail;
        }
        return $requiredCourseHomeDictFormat;
    }

    public function getFaqQuestionsOnHomePageByCourseHomePageId($model, $courseHomePageId, $coursePageUrlRequestObject, $courseHomePageDictionary) {
        $faq_data = $model->getFaqQuestionsOnHomePageByCourseHomePageId($courseHomePageId);
        foreach ($faq_data as $key => $faqData) {
            $faq_data[$key]['questionUrl'] = $coursePageUrlRequestObject->getQuestionUrlByQuestionIdAndCourseHomePageId($courseHomePageId, $faqData['id'], $courseHomePageDictionary);
        }
        return $faq_data;
    }

    public function getFaqQuestionsListSortedBySectionAndQuestionOrder($model, $courseHomePageId, $coursePageUrlRequestObject, $courseHomePageDictionary) {
        $faq_data = $model->getFaqQuestionsListSortedBySectionAndQuestionOrder($courseHomePageId);
        foreach ($faq_data as $sectionId => $sectionDetails) {
            foreach ($sectionDetails as $questionId => $questionDetails) {
                $faq_data[$sectionId][$questionId]['questionUrl'] = $coursePageUrlRequestObject->getQuestionUrlByQuestionIdAndCourseHomePageId($courseHomePageId, $questionId, $courseHomePageDictionary);
            }
        }
        return $faq_data;
    }

}
