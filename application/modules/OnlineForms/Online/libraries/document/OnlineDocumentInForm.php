<?php

class OnlineDocumentInForm
{
    private $onlineFormId;
    private $userId;
    private $document_title;
    
    private $documentDetails;
    
    private $error;
    
    function __construct($onlineFormId,$userId = 0,$document_title)
    {
        $this->onlineFormId = (int) $onlineFormId;
        $this->userId = (int) $userId;
        $this->document_title = $document_title;
        
        $this->ci = & get_instance();
    }
    
    private function _isValidDownload()
    {

        $this->ci->load->model('Online/onlineparentmodel');
        $this->documentModel = $this->ci->load->model('Online/documentmodel');
        $this->documentDetails = $this->documentModel->getAttachedDocumentsInForm($this->userId,$this->onlineFormId,$this->document_title);
        if(empty($this->documentDetails))
        {
            return false;
        }
        return true;
    }
    
    function download()
    {
        if(!$this->_isValidDownload()) {
            return false;
        }
        
	$fileURL = SITE_PROTOCOL.MEDIA_SERVER_IP.$this->documentDetails[0]['document_saved_path'];
        
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
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
