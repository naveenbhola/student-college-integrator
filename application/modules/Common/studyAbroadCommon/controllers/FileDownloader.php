<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 6/6/18
 * Time: 11:16 AM
 */

class FileDownloader extends MX_Controller
{

    private $usergroupAllowed;
    private $fileDownloaderLib;

    public function __construct()
    {
        parent::__construct();
        $this->usergroupAllowed = array('saAdmin','saShikshaApply','saCMSLead','saRMS','shikshaTracking','saAuditor');
        $this->fileDownloaderLib = $this->load->library('studyAbroadCommon/FileDownloaderLib');
    }

    private function disallowAccess(){
        header('location:/enterprise/Enterprise/disallowedAccess');
        exit();
    }

    private function downloadDocumentValidation($docOwnerId){
        $validity 		    = $this->checkUserValidation();
        $userid 		    = $validity[0]['userid'];
        $usergroup 		    = $validity[0]['usergroup'];

        if(($validity == "false" )||($validity == "")) {
            $this->disallowAccess();
        }
        else {
            if(!in_array($usergroup, $this->usergroupAllowed) && $userid != $docOwnerId) {
                $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
                if(!$saCMSToolsLib->checkIfSASalesMISUser($userid))
                {
                    $this->disallowAccess();
                }
            }
        }
    }

    public function downloadDocument($pageSource){
        if(empty($pageSource)){
            $this->disallowAccess();
        }
        $docId = $this->input->get('docId', true);
        $pageSource = $this->security->xss_clean($pageSource);
        $docData = $this->fileDownloaderLib->getDocumentData($docId, $pageSource);
        $this->downloadDocumentValidation($docData['userId']);
        if($pageSource == "pendingleads"){
            $docData['documentName'] = "Exam_Document";
        }

        if(strpos($docData['documentUrl'], '/PII/')!== false) {
            $this->curlCallForAwsDocumentDownload($docData['documentUrl'],$docData['documentName']);
        }
        else {
            $this->load->helper('download');
            $docData['documentUrl'] = MEDIAHOSTURL . $docData['documentUrl'];
            downloadFileInChunks($docData['documentUrl'], 400000, $docData['documentName']);
        }
    }

    public function curlCallForAwsDocumentDownload($url,$fileName){
        $this->load->helper('download');
        // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if (FALSE === strpos($url, '.')){
            return FALSE;
        }

        // Grab the file extension
        $x = explode('.', $url);
        $extension = strtolower(end($x));
        // Load the mime types
        @include(APPPATH.'config/mimes'.EXT);

        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension])){
            $mime = 'application/octet-stream';
        }else{
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        header('Content-Description: File Transfer');
        header('Content-Type: "'.$mime.'"');
        if(!empty($fileName)){
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = $fileName.'.'.$extension;
            header('Content-disposition: attachment; filename='.$fileName);
        }
        else{
            header('Content-disposition: attachment; filename='.basename($url));
        }
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        $apiUrl = SA_COUNSELLING_FACADE_AWS_DOWNLOAD_API.'?documentUrl='.$url;
        error_log($apiUrl);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$apiUrl);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($curl);
        print $result;
        curl_close($curl);
        if(curl_errno($curl))
        {
            error_log('Curl error: ' . curl_error($curl));
            return false;
        }
    }

}