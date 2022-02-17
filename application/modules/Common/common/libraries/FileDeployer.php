<?php

class FileDeployer
{
    private $_filename;
    private $_filepath;
    private $_filenameWithoutExtension;
    private $_fileExtension;
    
    private $_tmp_dir = '/var/www/html/shiksha/phpTempFiles/';
    private $_isJS = FALSE;
    
    private $_svnPath;
    
    function __construct($file='',$svnPath = '')
    {
        $this->setFile($file,$svnPath);
    }
    
    public function setFile($file,$svnPath)
    {
        $fileArr = explode('/',$file);
        $this->_filename = end($fileArr);
        $this->_filepath = implode('/',array_slice($fileArr,0,-1));
        
        $filenameArr = explode('.',$this->_filename);
        $this->_fileExtension = end($filenameArr);
        $this->_filenameWithoutExtension = implode('.',array_slice($filenameArr,0,-1));
    
        if(strtolower(substr($this->_filename,-3)) == '.js') {
            $this->_isJS = TRUE;
        }
        
        $this->_svnPath = $svnPath;
    }
    
    public function deploy($content)
    {
        $this->_checkValidFile();
        $this->_createBackup();
        $this->_createTempFile($content);
        return true;        
        if($this->_svnPath) {        
            $this->_checkInSVN();
        }
        
        /*
         * If versioning is to be applied, get the new version
         */
        
        if($this->_isJS) {
            $this->_updateVersion();
        }
        
        if(!copy($this->_getTempFilePath(),$this->_getActualFilePath())) {
            throw new Exception("Unable to copy file ".$this->_getTempFilePath()." to ".$newFilePath);
        }
    }
    
    private function _checkValidFile()
    {
        $actualFilePath = $this->_getActualFilePath();
        if(!file_exists($actualFilePath)) {
            throw new Exception("File ".$actualFilePath." does not exist");
        }
    }
    
    private function _createBackup()
    {
        copy($this->_getActualFilePath(),$this->_tmp_dir.$this->_getActualFileName().'.BACKUP.'.date('Y.m.d.H.i.s'));
    }
    
    private function _updateVersion()
    {
        global $js_revisions;   
        $newVersions = array();
        
        foreach($js_revisions as $filename => $version) {
                
            if($filename == $this->_filenameWithoutExtension) {
                $newVersions[$filename] = $version + 1;
            }
            else {
                $newVersions[$filename] = $version;
            }
        }
        
        if(!isset($newVersions[$this->_filenameWithoutExtension])) {
            $newVersions[$this->_filenameWithoutExtension] = 1;
        }
        
        $str = "<?php\n\$js_revisions = array();\n";
        foreach($newVersions as $filename => $version) {
            $str .= "\$js_revisions['$filename'] = $version;\n";
        }
        
        $jsVersionFile = FCPATH."globalconfig/js_revisions.php";
        $tempJsVersionFile = $this->_tmp_dir.'js_revisions.php';
        
        /*
         * First copy current file to temp location as backup
         */ 
        copy($jsVersionFile,$tempJsVersionFile);
        
        /*
         * Open the file and write new versions
         */ 
        if(!$fp = fopen(FCPATH."globalconfig/js_revisions.php","w")) {
            throw new Exception("Unable to open JS revisions file: ".$jsVersionFile);            
        }
        
        if(!fwrite($fp, $str)) {
            
            /*
             * If unable to write, first restore the backup file from temp location
             */ 
            copy($tempJsVersionFile,$jsVersionFile);
            throw new Exception("Unable to write to JS revisions file: ".$jsVersionFile.". Content to be written: ".$str);            
        }
        fclose($fp);
    }
    
    private function _checkInSVN()
    {
        $cwd = getcwd();
        chdir(SVN_PATH);
        
        $fullSVNPath = SVN_PATH."/".$this->_svnPath;
        
        $svnUpOutput = trim(shell_exec("svn up ".$this->_svnPath." --non-interactive --username amishra --password 238184"));
        
        if(strpos($svnUpOutput,'At revision') === FALSE && strpos($svnUpOutput,'Updated to revision') === FALSE) {
            throw new Exception("Unable to svn up file: ".$fullSVNPath);
        }
        
        shell_exec("rm -f ".$fullSVNPath);
        
        if(!copy($this->_getTempFilePath(),$fullSVNPath)) {
            throw new Exception("Unable to copy file ".$this->_getTempFilePath()." to ".$fullSVNPath);
        }
        
        shell_exec("chmod 777 ".$fullSVNPath);
        
        $svnCiOutput = trim(shell_exec("svn ci -m 'Auto check-in file ".$this->_filename."' ".$this->_svnPath." --non-interactive --username amishra --password 238184"));
        if($svnCiOutput && strpos($svnCiOutput,'Committed revision') === FALSE) {
            throw new Exception("Unable to svn check-in file: ".$fullSVNPath);
        }
        
        chdir($cwd);
    }
    
    private function _createTempFile($content)
    {
        if(!$fp = fopen($this->_getTempFilePath(), 'w')) {
            throw new Exception('Unable to create temp file-'.$this->_getTempFilePath());    
        }
        
        fwrite($fp, $content);
        fclose($fp);
        return TRUE;
    }
    
    private function _getTempFilePath()
    {
        return $this->_tmp_dir.$this->_filename;
    }
    
    private function _getActualFilePath()
    {
        return FCPATH.$this->_filepath.'/'.$this->_getActualFileName();
    }
    
    private function _getActualFileName()
    {
        return $this->_isJS ? getJSWithVersion($this->_filenameWithoutExtension) : $this->_filename;
    }
}