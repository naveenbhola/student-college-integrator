<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sectionpageredirectmodel
 *
 * @author yathartha
 */
class sectionpageredirectmodel extends MY_Model {

    private $dbHandle = '';
    private $dbHandleMode = '';

    function __construct() {
        parent::__construct('SAContent');
    }

    private function initiateModel($mode = "write") {
        if ($this->dbHandle && $this->dbHandleMode == 'write')
            return;
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    public function getUnMigratedExamContentID() {
        echo "Exam pages are retired. and this is a migration function anyways so returned false";return false;
        /*$this->initiateModel('read');
        $this->dbHandle->select('sac.content_id AS contentID,ale.exam  AS examName,
        sac.exam_type ');
        $this->dbHandle->from('study_abroad_content sac');
        $this->dbHandle->join('abroadListingsExamsMasterTable ale', 'ale.examId = sac.exam_type', 'inner');
        $this->dbHandle->where('sac.type', 'exampage');
        $this->dbHandle->where_in('sac.status', array('deleted', 'live'));
        $query_res = $this->dbHandle->get()->result();
        $contentIDDataHash = array();
        foreach ($query_res as $examContentToBeMigratedRow) {
            $contentIDDataHash[$examContentToBeMigratedRow->contentID] = $examContentToBeMigratedRow;
        }
        return $contentIDDataHash;
        */
    }

    public function getHomeContentIDOfAllExamsSectionPage() {
        $this->initiateModel('read');
        $this->dbHandle->select('  t.exam_id AS examID,
  t.content_id AS contentID ');
        $this->dbHandle->from(' sa_content t ');
        $this->dbHandle->where('t.type', 'examContent');
        $this->dbHandle->where('t.is_homepage', 1);
        $this->dbHandle->where('t.status', 'live');
        $query_res = $this->dbHandle->get()->result();
        $examIDDataHash = array();
        foreach ($query_res as $examHomePageContentIDRow) {
            $examIDDataHash[$examHomePageContentIDRow->examID] = $examHomePageContentIDRow->contentID;
        }
        return $examIDDataHash;
    }

    public function insertToAbroadRedirectTableForRedirection($oldSectionUrlContentHomeUrlList, $currentRedirectionData) {
        $this->initiateModel('write');
        $batchDataToBeInserted = array();
        $currentDateTime = date("2016-04-27 00:00:00");
        foreach ($oldSectionUrlContentHomeUrlList as $oldSectionUrlContent) {
            if (in_array($oldSectionUrlContent->sectionURL, $currentRedirectionData)) {
                continue;
            }
            $singleRowDataToBeInserted = array(
                'content_id' => $oldSectionUrlContent->contentID,
                'old_content_url' => $oldSectionUrlContent->sectionURL,
                'status' => 'live',
                'created_on' => $currentDateTime,
                'created_by' => 3284455
            );
            array_push($batchDataToBeInserted, $singleRowDataToBeInserted);
        }
        if(count($batchDataToBeInserted)==0){
            return;
        }
        $this->dbHandle->insert_batch('sa_content_redirection_mapping', $batchDataToBeInserted);
    }

    public function getCurrentDataFromRedirectionTable() {
        $this->initiateModel('read');
        $this->dbHandle->select('old_content_url');
        $this->dbHandle->from('sa_content_redirection_mapping');
        $this->dbHandle->where('status', 'live');
        $query_res = $this->dbHandle->get()->result();
        $currentUrls = array();
        foreach ($query_res as $row) {
            $currentUrls[] = $row->oldContentURL;
        }
        return $currentUrls;
    }

}
