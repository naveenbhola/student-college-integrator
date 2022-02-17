<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 6/6/18
 * Time: 11:22 AM
 */

class FileDownloaderLib
{
    private $CI;
    private $fileDownloaderModel;

    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }

    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('studyAbroadCommon/filedownloadermodel');
        $this->fileDownloaderModel = new filedownloadermodel();
    }

    public function getDocumentData($docId, $pageSource)
    {
        $docData = array();
       switch($pageSource) {
           case "candidateDocumentForm":
               $docData = $this->fileDownloaderModel->getDocumentData($docId);
               break;
           case "pendingleads" :
               $docData = $this->fileDownloaderModel->getStudentDocumentData($docId);
               break;
       }
//       $docData['documentUrl'] = $docData['documentUrl'];
       return $docData;
    }
}