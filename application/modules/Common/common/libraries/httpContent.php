<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class httpContent
{	
	private $CI;
    public $module;
	function __construct(){
        $this->CI =  & get_instance();
    }
  
    /* 
     * Desc     : Find shiksha.com url from content/url and update it with https
	 * @params  : $primaryColumnName = content Id (primary key) column name

	              $contentColumnName = content column name

                  $status = array()

				  $isTag  = default true. If true then will update href and src only. if false then will update all content included tags (href, src) also.

	 * @return  : no
     * @uther   : akhter
     *
	 */
    function findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status, $isTag=true, $findStr, $isAllHttp=false){

    	if(empty($tableName) || empty($primaryColumnName) || empty($contentColumnName)){
    		echo 'Field name couldn\'t be blank';return;
    	}

        ini_set('memory_limit','8000M');
        ini_set("max_execution_time",-1);

        // Step 1- get total content Id having http
    	$this->CI->load->model('common/httpscriptmodel','modelObj');
        $this->CI->modelObj->module = $this->module;
    	$contentId = $this->CI->modelObj->getHttpContentId($tableName, $primaryColumnName, $contentColumnName, $status, $findStr);

    	$totalContent = count($contentId);
    	
    	if($totalContent<=0){
    		echo 'Sorry, content couldn\'t found.';return;
    	}

        foreach ($contentId as $key => $row) {
                $contentIdArr[] = $row[$primaryColumnName];
        }

		// step 2- split content id into chunks
    	$num       = $totalContent;
        $threshold = ($num == 5000) ? ($num - 1000) : 5000; // default 5000
        $remainder = $num % $threshold;
        $number    = explode('.',($num / $threshold));
        $range     = $number[0];
        $pages     = ceil($num/$threshold);
        $pages     = $pages - 1;

        for($i=0; $i <= $range ; $i++){
                $start = $i*$threshold;
                if($i == $pages){
                        $contentIds = array_slice($contentIdArr, $start, $remainder);
                        $limit =  " limit $start , $remainder"; //limit 0,5000
                        echo '<br>'.$limit.'<br>';
                        // step 3- update content in chunks
                        $this->updateContent($tableName, $primaryColumnName, $contentColumnName, $contentIds, $isTag, $isAllHttp);
                }else{
                        $contentIds = array_slice($contentIdArr, $start, $threshold);
                        $limit = " limit $start , $threshold";
                        echo '<br>'.$limit.'<br>';
                        $this->updateContent($tableName, $primaryColumnName, $contentColumnName, $contentIds, $isTag, $isAllHttp);
                }
        }
    }

    function updateContent($tableName, $primaryColumnName, $contentColumnName, $contentIds, $isTag, $isAllHttp){
    	$content = $this->CI->modelObj->getContentHavingHttp($tableName, $primaryColumnName, $contentColumnName, $contentIds);
        $contentArr = array();
    	foreach ($content as $key => $value) {
            $data[$primaryColumnName] = $value[$primaryColumnName];
            //Step 4 -parse url from content and resolve with https
            $data[$contentColumnName] = parseUrlFromContent($value[$contentColumnName], $isTag, $isAllHttp);
            $contentArr[] = $data;
    	}
        
        //Step 5 -Update content 
        $response = $this->CI->modelObj->updateContentWithHttps($tableName, $primaryColumnName, $contentArr); 
        unset($content, $contentArr, $contentIds);
        if($response){
            echo '<br>Content have been successfully updated.<br>';
        }else{
            error_log('Failed HTTP content :: '.$tableName." = ".print_r(implode(',', $contentIds),true));
        }
    }

    function replaceAbsolutePathWithRelativepath($tableName, $columnName, $domainToBeRepaced = array(),$statusCheck = 0 ){

        if(empty($tableName) || empty($columnName)){
            echo 'Table name and Field name couldn\'t be blank';exit();
        }

        if(empty($domainToBeRepaced)){
            $domainToBeRepaced = array('http://images.shiksha.com','http://www.shiksha.com','http://studyabroad.shiksha.com');
        }else if(!is_array($domainToBeRepaced)){
            $domainToBeRepaced = array($domainToBeRepaced);
        }

        if(ENVIRONMENT == 'production' ){
            // validate domain
            $invalidDomians = array();
            foreach ($domainToBeRepaced as $domain) {
                $isValidDomain = $this->_validateDomain($domain);
                if($isValidDomain == false){
                    $invalidDomians[] = $domain;
                }
            }
            if(count($invalidDomians) > 0){
                if(count($invalidDomians) == count($domainToBeRepaced)){
                    echo 'All domains are invalid.<br>Invalid domains list :<br><br>';
                    echo implode($invalidDomians, '<br>');exit();
                }else{
                    echo 'Some domains are invalid.<br>Invalid domains are :<br><br>';
                    echo implode($invalidDomians, '<br>');exit();
                }
            }
        }

        // check if table and coloum exist in that table.
        $modelObj = $this->CI->load->model('common/httpscriptmodel');
        $isTableExist = $modelObj->checkIfTableExist($tableName);
        if($isTableExist == false){
            echo 'Table : '.$tableName.' doesn\'t exist in shiksha database';exit();
        }else{
            // ckeck if column exists.
            $isColumnExistInTable = $modelObj->checkIfColumnExistInTable($tableName , $columnName);
            if($isColumnExistInTable == false){
                echo 'Column : '.$columnName.' doesn\'t exist in '.$tableName.' table';exit();
            }
        }

        // Now replace absolute path with relative path
        foreach ($domainToBeRepaced as $domain) {
            echo 'Replacing absolute path with relativa path for domain : '.$domain.'<br>';
            $response = $modelObj->replaceAbsolutePathWithRelativepath($tableName, $columnName, $domain, $statusCheck);
            if($response == true){
                echo 'Replacing absolute path with relativa path for domain : '.$domain.' is successfully done.'.'<br>';
            }else{
                echo 'Something wrong happened while replacing absolute path with relativa path for domain : '.$domain.'<br>';exit();
            }
        }
    }

    private function _validateDomain($domain = ''){
        if(empty($domain)){
            return false;
        }
        if(preg_match( '/http:\/\/[a-zA-Z]+.shiksha.com$/' ,$domain)){
            return true;
        }else{
            return false;
        }
    }
}
?>
