<?php

class OnlineDocument
{
    private $ci;
    private $client;
    
    private $documentId;
    private $userId;
    
    private $documentDetails;
    
    private $error;
    
    function __construct($documentId,$userId = 0)
    {
        $this->documentId = (int) $documentId;
        $this->userId = (int) $userId;
        
        $this->ci = & get_instance();
        $this->ci->load->library('Online/Online_form_client');
        $this->client = new Online_form_client;
    }
    
    private function _isValidDownload()
    {
        if(!$this->documentId) {
            $this->setError('Invalid document');
            return false;
        }
        
        if(!$this->userId) {
            $this->setError('Invalid user');
            return false;
        }
        
        $sharingDetails = 1;
        $documentDetails = $this->client->getDocumentDetails($this->documentId,$sharingDetails);
        
        if(!is_array($documentDetails) || !$documentDetails['id']) {
            $this->setError('Invalid document');
            return false;
        }
        
        if(($documentDetails['userId'] == $this->userId) || (is_array($documentDetails['sharedWith']) &&  in_array($this->userId,$documentDetails['sharedWith']))) {
            $this->documentDetails = $documentDetails;
            return true;
        }
        else {
            $this->setError('Invalid access');
            return false;
        }
    }
    
    function download()
    {
        if(!$this->_isValidDownload()) {
            return false;
        }
        
	$fileURL = SITE_PROTOCOL.MEDIA_SERVER_IP.$this->documentDetails['document_saved_path'];
        
        if(!$fileURL) {
            $this->setError('File does not exist');
            return false;
        }
        
        $filename = end(explode('/',$fileURL));
        
        //$fileData = $this->client->downloadDocument($filename);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $fileURL);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $fileData = array();
        $fileData['fileData'] = curl_exec($curl);
        $fileData['filesize'] = strlen($fileData['fileData']);
        $fileData['fileData'] = base64_encode($fileData['fileData']);
        curl_close($curl);

        
        $filesize = $fileData['filesize'];
        
        $extension = strtolower(substr(strrchr($filename, '.'), 1));
        
        switch($extension) {
            case "doc": $mime="application/msword"; break;
            case "pdf": $mime="application/pdf"; break;
            case "rvt": $mime="application/octet-stream"; break;
            case "rft": $mime="application/octet-stream"; break;
            case "rfa": $mime="application/octet-stream"; break;
            case "xls": $mime="application/vnd.ms-excel"; break;
            case "xlsx": $mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;  
            case "dwg": $mime="application/acad"; break;
            case "dwf": $mime="application/acad"; break;
            case "gif": $mime="image/gif"; break;
            case "png": $mime="image/png"; break;
            case "jpeg":
            case "jpg": $mime="image/jpg"; break;
            default: $mime="application/octet-stream"; break;
        }
        // use this unless you want to find the mime type based on extension
        // $mime = array('application/octet-stream');
        
        header('Content-Description: File Transfer');
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename='.basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Content-Length: '.sprintf('%d', $filesize));
        
        // check for IE only headers
        if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
          header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
          header('Pragma: public');
        } else {
          header('Pragma: no-cache');
        }
        //ob_clean();
        //flush();
        //readfile($filepath);
        echo base64_decode($fileData['fileData']);
        return true;
    }
    
    function setError($error)
    {
        $this->error = $error;
    }
    
    function getError()
    {
        return $this->error;
    }
}
