<?php

class AbroadExamSectionPageURLRedirect extends MX_Controller {

    private $abroadExamPageCommonLib;
    private $model;

    public function __construct() {
        parent::__construct();
        $this->abroadExamPageCommonLib = $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
        $this->model = $this->load->model('sectionpageredirectmodel');
    }

    public function redirectExamSectionPageToHomePageOfExam() {
        $unmigratedExamContentIDList = $this->_getUnMigratedContentList();
        $sectionURLList = $this->_getExamSectionURLListOfAllExams($unmigratedExamContentIDList);
        $examPageHomeContentIDs = $this->_getHomeContentIDsOfAllExams();
        $oldSectionUrlContentHomeUrlList = $this->_generateMappingOfOldSectionURLToExamHomePage($sectionURLList, $examPageHomeContentIDs, $unmigratedExamContentIDList);
        $currentRedirectionData = $this->model->getCurrentDataFromRedirectionTable();
        $this->model->insertToAbroadRedirectTableForRedirection($oldSectionUrlContentHomeUrlList, $currentRedirectionData);
    }

    private function _getUnMigratedContentList() {
        $unmigratedExamContentIDList = $this->model->getUnMigratedExamContentID();
        return $unmigratedExamContentIDList;
    }

    public function _getExamSectionURLListOfAllExams($unmigratedExamContentIDList) {
        $examPageIDs = array();
        $modifiedSectionUrlList = array();
        foreach ($unmigratedExamContentIDList as $rowObject) {
            $examPageIDs[$rowObject->examName] = $rowObject->contentID;
            $sectionURLList = $this->abroadExamPageCommonLib->prepareSectionUrls($examPageIDs);
            $modifiedSectionUrlList[$rowObject->contentID] = $sectionURLList[$rowObject->examName];
        }
        return $modifiedSectionUrlList;
    }

    public function _getHomeContentIDsOfAllExams() {
        $examIDToContentDataHash = $this->model->getHomeContentIDOfAllExamsSectionPage();
        return $examIDToContentDataHash;
    }

    public function _generateMappingOfOldSectionURLToExamHomePage($sectionUrlList, $examHomePageContentID, $unmigratedExamContentIDList) {
        $sectionURLToExamHomePageList = array();
        foreach ($sectionUrlList as $contentID => $sectionDetailsArray) {
            $examIDOfCurrentSection = (int) $unmigratedExamContentIDList[$contentID]->exam_type;
            $homePageContentID = $examHomePageContentID[$examIDOfCurrentSection];
            if (!isset($homePageContentID)) {
                continue;
            }
            foreach ($sectionDetailsArray as $sectionDetailsHash) {
                $sectionRedirectDetails = new stdClass();
                $sectionRedirectDetails->sectionURL = $sectionDetailsHash['url'];
                $sectionRedirectDetails->contentID = $homePageContentID;
                array_push($sectionURLToExamHomePageList, $sectionRedirectDetails);
            }
        }
        return $sectionURLToExamHomePageList;
    }

}
