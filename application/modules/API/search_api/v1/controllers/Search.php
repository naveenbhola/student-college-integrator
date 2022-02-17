<?php
/**
 * Search Class
 * This is the class for all the APIs related to Search
 * @date    2015-09-01
 * @author  Romil Goel
 * @todo    none
*/

class Search extends APIParent {

        private $validationObj;
        private $anaCommonLib;

        function __construct() {
                parent::__construct();
                $this->load->library(array('SearchCommonLib','SearchValidationLib'));
                $this->searchCommonLib = new SearchCommonLib();
                $this->validationObj = new SearchValidationLib();
        }

        /**
         * API for searching the tag/discussion/question based on the given input
         * @param  POST text  = user typed text 
         * @param  POST start = starting offset of the results to be fetched 
         * @param  POST rows  = no. of rows to be fetched
         * @param  POST type  = tag/discussion/question
         * @date   2015-09-04
         * @author Romil Goel
         */
        function search(){
                
                $text  = $this->input->post('text');
                $start = $this->input->post('start');
                $rows  = $this->input->post('rows');
                $type  = $this->input->post('type');
                $type  = $type ? $type : '';

                //step 1:Fetch the Input from GET/POST
                $Validate              = $this->getUserDetails();
                $userId                = isset($Validate['userId'])?$Validate['userId']:0;
                $otherParams           = array();
                $data                  = array();
                $result                = array();
                $text                  = $text;
                $otherParams['start']  = $start;
                $otherParams['rows']   = $rows;
                $otherParams['userId'] = $userId;
                $type                  = $type;

                // specify the results order in which result tabs will be shown
                $resultsOrder         = array("question","discussion","tag");

                //step 2:validate all the fields
                if(! $this->validationObj->validateSearchParams($this->response, array('userId'=>$userId, 'text'=>$text, 'type'=>$type, 'rows'=>$otherParams['rows'], 'start' => $otherParams['start'] ))){
                    return;
                }

                //Step 3: Send the Input to Backend and get the output
                
                // if no type is specified explicitly then set it the first tab to be shown
                if(empty($type))
                    $type = $resultsOrder[0];

                if($type){
                        $data[$type] = $this->searchCommonLib->getSearchResults($type, $text, $otherParams);        

                        if(empty($data[$type])){
                            $noInfoAvailableText = $this->config->item("noInfoAvailableText");
                            $this->response->setResponseMsg($noInfoAvailableText['search']);
                        }
                }
                
                $result['resultOrder'] = $resultsOrder;
                $result['results']     = $data;
                $result['rowsCount']   = $rows;

                $this->response->setBody($result);

                //Step 4: Return the Response
                $this->response->output();
        }

		/**
         * API for searching the related tag/discussion/question
         * @param  POST id  = entity id
         * @param  POST type  = tag/question/discussion
         * @date   2015-09-04
         * @author Romil Goel
         */
        function getRelatedEntities(){
        
                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId   = isset($Validate['userId'])?$Validate['userId']:0;
                $id       = $this->input->post("id");
                $type     = $this->input->post("type");

                 //step 2:validate all the fields
                if(! $this->validationObj->validateRelatedEntityParams($this->response, array('userId'=>$userId, 'id'=>$id, 'type'=>$type))){
                    return;
                }

                //Step 3: Send the Input to Backend and get the output
                $this->load->library('v1/SearchRelatedEntities');
                $relatedEntities = new SearchRelatedEntities();
                $relatedEntities = $relatedEntities->getRelatedEntity($id, $type);

                $this->response->setBody(array("data" => $relatedEntities));

                //Step 4: Return the Response
                $this->response->output();
        }

        /**
         * API for searching the linked and related discussion/question
         * @param  POST id  = entity id
         * @param  POST type  = question/discussion
         * @date   2015-09-04
         * @author Romil Goel
         */
        function getLinkedAndRelatedThread($id, $type, $loadFirstAnswer="false"){

                //step 1:Fetch the Input from GET/POST
                $Validate = $this->getUserDetails();
                $userId   = isset($Validate['userId'])?$Validate['userId']:0;

                 //step 2:validate all the fields
                if(! $this->validationObj->validateRelatedEntityParams($this->response, array('userId'=>$userId, 'id'=>$id, 'type'=>$type))){
                    return;
                }

                //Step 3: Send the Input to Backend and get the output
                $this->load->library('v1/SearchRelatedEntities');
                $this->load->model("messageBoard/anamodel");
                $relatedEntities = new SearchRelatedEntities();
                $anamodel        = new anamodel();

                $s = microtime(true);
                // get linked threads
                //if(in_array($type, array("question", "discussion")))
                //    $linkedEntities  = $anamodel->getLinkedQuestionDiscussionDetails($id, $type, 5);
                if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Stage 1 :: ".$type."_".$id." = ".(microtime(true)-$s), 3, '/tmp/perfLogs.log');
                
                
                if(empty($linkedEntities)){
                        $linkedEntities = NULL;
                }
                
                // get linked thread-ids
                $linkedThreads = array();
                /*foreach($linkedEntities as $linkedThread){
                    $linkedThreads[] = $linkedThread['threadId'];
                }*/
                
                for($i=0; $i<count($linkedEntities); $i++)
                {
                        $linkedThreads[] = $linkedEntities[$i]['threadId'];
                        $linkedEntities[$i]['title'] = strip_tags(html_entity_decode($linkedEntities[$i]['title']));
                        $linkedEntities[$i]['creationDate'] = date("Y-m-d", strtotime($linkedEntities[$i]['creationDate']));
                }

                // determine the algorithm to serve
                $algorithm = 2;
                // if(rand(0, 10)%2 == 0)
                //     $algorithm = 1;
                
                // get related threads excluding linked threads
                $inputParams              = array();
                $inputParams['text']      = $this->input->post("threadTitle");
                $inputParams['algorithm'] = $algorithm;

                $relatedEntities = $relatedEntities->getRelatedEntity($id, $type, $linkedThreads, 5, $inputParams);
                if(empty($relatedEntities)){
                        $relatedEntities = NULL;
                } elseif ($loadFirstAnswer == "true" && $type == "question"){
                    foreach ($relatedEntities as $key => $value){
                        $responseAnswer = $anamodel->getTopAnswerDetails($value['threadId']);
                        if (is_array($responseAnswer) && count($responseAnswer) > 0){
                            $relatedEntities[$key]['answer']    =   array(  
                				"msgId"         =>  $responseAnswer[0]['msgId'],
                                "msgTxt"        =>  $responseAnswer[0]['msgTxt'],
                                "firstName"     =>  $responseAnswer[0]['firstname'],
                                "lastname"      =>  $responseAnswer[0]['lastname'],
                                "displayName"   =>  $responseAnswer[0]['displayName'],
                                "aboutMe"       =>  $responseAnswer[0]['aboutMe'],
                                "picUrl"        =>  $responseAnswer[0]['picUrl'],
                                "levelName"     =>  $responseAnswer[0]['levelName'],
                                "userLevelDesc" =>  $responseAnswer[0]['userLevelDesc']
                            );
                        }
                    }
                }

                $this->response->setBody(array("linkedEntities" => $linkedEntities, "related" => $relatedEntities, "algoType" => $algorithm));

                //Step 4: Return the Response
                $this->response->output();
        }
}
