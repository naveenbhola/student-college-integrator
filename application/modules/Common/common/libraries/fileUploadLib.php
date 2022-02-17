<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-09-10 07:14:44 $:  Date of last commit

CacheLib to proivide library method to the clients

$Id: CacheLib.php,v 1.12 2010-09-10 07:14:44 build Exp $:

*/

class fileUploadLib{
    function __construct(){}
    
    /*
     * $fileUrl : This should be the exact filename on disk
     * $desiredFileName : This is the name that you want on MediaData
     * $serverUrl : The server where the curl call will give the file
    */
    function uploadFileViaCurl($fileUrl,$serverUrl,$fileType=''){
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimetype = $finfo->file($fileUrl);     
        if (function_exists('curl_file_create')) { // php 5.5+
          $cFile = curl_file_create($fileUrl,$mimetype);
        } else { 
          $cFile = '@' . realpath($fileUrl);
        }
        $post = array('somefile[]'=> $cFile);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$serverUrl);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res=curl_exec($ch);
        curl_close ($ch);
        
        //$res = exec('curl -i -k -X POST -H "Content-Type: multipart/form-data  " -F "somefile[]=new CURLFile('.$fileUrl.');'.$fileType.'" '.$serverUrl);
        return unserialize($res);
    }
}
