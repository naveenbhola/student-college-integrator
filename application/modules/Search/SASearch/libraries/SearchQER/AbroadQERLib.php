<?php

class AbroadQERLib {

    public function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->helper('SASearch/SearchUtility');
        $this->_ci->config->load('search_abroad_config');
    }
    
    
    /**
     * This function calls qer and searches for 
     * the entities in the keyword which can then be searched in solr
     * @param type $keyword
     * @param type $userAgent
     * @return type
     */
    public function parseQERResultToGetEntities($keyword, $debugging = 0) {
        $curlObject=$this->_ci->load->library('common/CustomCurl');
        $qerPath = $this->_ci->config->item('pathToQER');
        if ($qerPath == '') {
            return array();
        }
		if($debugging == 1)
		{
			$this->_ci->benchmark->mark('qerStart');
			$mem1 = memory_get_usage();
		}
        $qerUrl = $qerPath . "?inkeyword=" . urlencode($keyword) . "&output=" . $this->_ci->config->item('qerOutputFormat') . "&action=Submit";
        $customCurlObject = $curlObject->curl(sanitizeUrl($qerUrl));
        if (($qerResultString = $customCurlObject->getResult())) {
            $qerXMLResult = json_decode(json_encode(simplexml_load_string($qerResultString, null, LIBXML_NOCDATA)), true);
            $qerSanatizedResult=$this->_extractAndSanitizeQERData($qerXMLResult);
            $qerSanatizedResult=$this->_checkForTooManyEntities($qerSanatizedResult,$keyword);
        } else {
			if(ENVIRONMENT == "production") // send mails only from live environment
			{
				$this->_sendMailForQERError($keyword,$customCurlObject);
			}
            return -1;
        }
		if($debugging == 1)
		{
			$timeTaken = $this->_ci->benchmark->elapsed_time('qerStart', 'qerEnd');
			$mem2 = memory_get_usage();
			$qerExecutionLogArr = array('component'=>'QER',
														'keyword'=>$keyword,
														'url'=>$qerUrl,
														'timeTaken'=>$timeTaken,
														'memoryUsed'=>($mem2-$mem1),
														'response'=>json_encode($qerXMLResult)
														);
			$qerSanatizedResult = array(
											'qerSanatizedResult' => $qerSanatizedResult,
											'qerExecutionLogArr'=>$qerExecutionLogArr
									   );
			return $qerSanatizedResult;
		}
        return $qerSanatizedResult;
    }
    
    
    private function _sendMailForQERError($keyword,$customCurlObject){
        $mailContent=$this->_getMailContentForQERError($keyword, $customCurlObject);
        $mailData['sender'] = SA_ADMIN_EMAIL; 
        $mailData['subject'] = 'Search : QER Error';
        $mailData['mailContent'] = $mailContent;
        $mailData['recipients'] = array(
                       'to' => array('satech@shiksha.com')
                    
                        );
        sendMails($mailData);
    }

    private function _getMailContentForQERError($keyword,$customCurlObject){
        $errorMailContent="<p>
			Hi&nbsp;</p>
		<p><u><strong>QER curl failure&nbsp;</strong></u></p>
		<p><strong>Curl Error Code : ".$customCurlObject->getCurlErrorCode()."</strong> :- 201</p>
		<p><strong>Curl Error Message</strong> :-".$customCurlObject->getCurlErrorMessage()."</p>
		<p><strong>Date Time Reported</strong> :-".date('l jS \of F Y h:i:s A')."</p>
		<p><strong>Time Taken :-".(($customCurlObject->getCurlEndTime()-$customCurlObject->getCurlStartTime())*1000)."</p>
		<p><strong>Keyword Searched </strong>:-$keyword</p>
		<p>Regards</p>
		<p>SA Team&nbsp;</p>
		<p>&nbsp;</p>";
        return $errorMailContent;
    }
    
    
    /**
     * This function converts the result of qer into 
     * required form . It makes an array having entity names and their respective
     * ids . 
     * @param type $qerXMLResult
     * @return array
     */
    private function _extractAndSanitizeQERData($qerXMLResult) {
        $qerFields = $this->_ci->config->item('qerFields');
        $sanatizedQERList = array();
        foreach ($qerFields as $qerField) {
            if (isset($qerXMLResult[$qerField])) {
                $qerValues = $qerXMLResult[$qerField];
                $qerValuesArray = explode(',', $qerValues);
                $sanatizedQERList[$qerField] = array();
                foreach ($qerValuesArray as $qerValue) {
                    if (trim($qerValue) == '') {
                        continue;
                    }
                    $qerValueIDAndName = explode("::", $qerValue);
                    $qerValuesList=array();
                    $qerValuesList['id']=$qerValueIDAndName[0];
                    $qerValuesList['name']=$qerValueIDAndName[1];
                    array_push($sanatizedQERList[$qerField], $qerValuesList);
                }
            }
        }
        return $sanatizedQERList;
    }


    private function _checkForTooManyEntities($qerResult,$keyword) {
        $resultLimit =20;
        if(isset($qerResult['universities']) && count($qerResult['universities']) >$resultLimit)
        {
            /*$qerResult['universities'] = array_slice($qerResult['universities'],0, $resultLimit);

            $scoreArray = array_map(function($a){ return $a['score'];}, $qerResult['universities']);
            $scoreArray = array_unique($scoreArray);
            if(count($scoreArray)<2){*/
                unset($qerResult['universities']);
            //}
            $qerResult['textSearchFlag'] = true;
        }

        if(isset($qerResult['institute']) && count($qerResult['institute']) >$resultLimit)
        {
            /*$qerResult['institute'] = array_slice($qerResult['institute'], 0,$resultLimit);

            $scoreArray = array_map(function($a){ return $a['score'];}, $qerResult['institute']);
            $scoreArray = array_unique($scoreArray);
            if(count($scoreArray)<2){*/
                unset($qerResult['institute']);
            //}
            $qerResult['textSearchFlag'] = true;
        }

        if($qerResult['textSearchFlag']==true)
        {
            foreach ($qerResult as $key => $entityArray) 
            {

              if($key !='universities' && $key !='institute')
              {
                foreach ($entityArray as $entityKey => $entityValue) 
                {
                   $keyword = str_replace($entityValue['name'], '', $keyword);
                }
              }
            }
            $qerResult['remainingKeyword'] = $keyword;
        }

        return $qerResult;
    }

}
