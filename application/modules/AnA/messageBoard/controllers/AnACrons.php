<?php

class AnACrons extends MX_Controller
{
    private $daysToLookFor = 365;
    private $dateToCheckFor;
    private $QnAModel;
    private $articlemodel;
    
    function __construct() {
        // ini_set("memory_limit", '2000M');
        $this->QnAModel = $this->load->model('QnAModel');
    }    

    private function _getSubCatIdsOfAvailableCoursePages() {
        global $COURSE_PAGES_SUB_CAT_ARRAY;
        return array_keys($COURSE_PAGES_SUB_CAT_ARRAY);
    }
    
    /*
     *  Function that fetches "Questions" from the DB for Course Pages subcategories and calculate their popularity based on an algo of the
     *  factors viewCount, msgCount and creationDate, and update the popularity count for the questions.     
     */
    function updatePopularityForQuestions() {
	$this->validateCron();
        $this->dateToCheckFor = $this->_getDate(-($this->daysToLookFor));
        $subCatIdsArray = $this->_getSubCatIdsOfAvailableCoursePages();
        foreach($subCatIdsArray as $key => $subCatId)
        {
            $questionsList = $this->_getQuestionsListForSubCategory($subCatId);
            if(!count($questionsList)) {
                continue;
            }
            $this->getAndUpdateQuestionPopularity($questionsList);        
        }
    }
    
    /*
     *  Function that fetches "Discussions" from the DB for Course Pages subcategories and calculate their popularity based on an algo of the
     *  factors viewCount, Comments and creationDate, and update the popularity count for them.     
     */
    function updatePopularityForDiscussions() {
	$this->validateCron();
        $this->dateToCheckFor = $this->_getDate(-($this->daysToLookFor));
        $subCatIdsArray = $this->_getSubCatIdsOfAvailableCoursePages();
        foreach($subCatIdsArray as $key => $subCatId)
        {
            $discussionsList = $this->_getDiscussionsList($subCatId);
            if(!count($discussionsList)) {
                continue;
            }            
            $this->getAndUpdateDiscussionPopularity($discussionsList);            
        }
    }
    
    /*
     *  Function that fetches "Articles" from the DB for Course Pages subcategories and calculate their popularity based on an algo of the
     *  factors viewCount, Comments and creationDate, and update the popularity count for the questions.     
     */
    function updatePopularityForArticles($limit = '') {
	$this->validateCron();
        $this->dateToCheckFor = $this->_getDate(-($this->daysToLookFor));
        $minLimit = 1000;
        if($limit =='' || !is_numeric($limit)){
            $limit = $minLimit;
        }
        $this->articlemodel = $this->load->model('blogs/articlemodel');
        $start = 0;
        while(1){
            $articlesList = $this->_getArticlesList($start,$limit);
            if(!count($articlesList)) {
                break;
            }
            $this->getAndUpdateArticlePopularity($articlesList);
            $start += $limit;
        }
    }    
        
    function getAndUpdateArticlePopularity($articlesList) {
        foreach($articlesList as $key => $articleInfo) {
            $popularityCount = $this->_calculateArticlePopularity($articleInfo);
            $this->_updatePopularity($articleInfo['articleId'], $popularityCount, $entityType = "articles");
            // echo "<br>$key) Popularity updated for articleId ".$articleInfo['articleId']." = ".$popularityCount;
        }
    }
    
    function getAndUpdateQuestionPopularity($questionsList) {
        
        foreach($questionsList as $key => $questionInfo) {
            $popularityCount = $this->_calculateQuestionPopularity($questionInfo);
            $this->_updatePopularity($questionInfo['msgId'], $popularityCount);
            // echo "<br>$key) Popularity updated for msgId ".$questionInfo['msgId']." = ".$popularityCount;
        }        
    }
    
    
    function getAndUpdateDiscussionPopularity($discussionList) {
        foreach($discussionList as $key => $discussionInfo) {
            $popularityCount = $this->_calculateDiscussionPopularity($discussionInfo);
            $this->_updatePopularity($discussionInfo['msgId'], $popularityCount);
            //echo "<br>$key) Popularity updated for msgId ".$discussionInfo['msgId']." = ".$popularityCount;
        }        
    }    

    private function _calculateArticlePopularity($articleInfo) {
        /*
         * Algo is: 500*(5+(viewCount-20)*4+(msgCount-1))*7/POWER(2+TODAY()-creationDate,4)
         */ 
        $popularityCount = 500 * (5 + ($articleInfo['viewCount'] - 20) * 4 + ($articleInfo['commentCount'] - 1) * 7);                                    
        
        $dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($articleInfo['creationDate'])))/3600/24);
        // echo "<br>Date diff for = ".$discussionInfo['creationDate']." is ".$dateDifference. " days";// die;
        $powerNumber = pow((2 + $dateDifference), 4);
        // echo "<br> Popularity Count $popularityCount for quest id ".$discussionInfo['discussionId']." of $num days = ".$powerNumber;
        $popularityCount = $popularityCount / $powerNumber;
        // echo "<br> Popularity Count for discussion id ".$discussionInfo['discussionId']." = ".$popularityCount;
        return $popularityCount;        
    }
    
    private function _calculateDiscussionPopularity($discussionInfo) {
        /*
         * Algo is: [5000*(5+(viewCount-10)*3+(commentCount-3)*1.5)/POWER(2+TODAY()-creationDate,3.2)]
         */ 
        $popularityCount = 5000 * (5 + ($discussionInfo['viewCount'] - 10) * 3 + ($discussionInfo['commentCount'] - 3) * 1.5);                                    
        
        $dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($discussionInfo['creationDate'])))/3600/24);
        // echo "<br>Date diff for = ".$discussionInfo['creationDate']." is ".$dateDifference. " days";// die;
        $powerNumber = pow((2 + $dateDifference), 3.2);
        // echo "<br> Popularity Count $popularityCount for quest id ".$discussionInfo['discussionId']." of $num days = ".$powerNumber;
        $popularityCount = $popularityCount / $powerNumber;
        // echo "<br> Popularity Count for discussion id ".$discussionInfo['discussionId']." = ".$popularityCount;
        return $popularityCount;
    }    
    
    private function _calculateQuestionPopularity($questionInfo) {
        /*
         * Algo is: [(5+(msgCount-1)*10+(viewCount-5)*1.5)/POWER(2+(TODAY()-creationDate),1.8]
         */ 
        $popularityCount = (5 + ($questionInfo['msgCount'] - 1) * 10 + ($questionInfo['viewCount'] - 5) * 1.5 );        
        
        $dateDifference = floor((strtotime(date("M d Y ")) - (strtotime($questionInfo['creationDate'])))/3600/24);
        // echo "<br>Date diff for = ".$questionInfo['creationDate']." is ".$dateDifference. " days";// die;        
        $powerNumber = pow((2 + $dateDifference), 1.8);
        // echo "<br> Popularity Count $popularityCount for quest id ".$questionInfo['msgId']." of $num days = ".$powerNumber;
        $popularityCount = $popularityCount / $powerNumber;
        // echo "<br> Popularity Count for quest id ".$questionInfo['msgId']." = ".$popularityCount;
        return $popularityCount;
    }
    
    private function _updatePopularity($msgId, $popularityCount, $entityType = "messages") {
        switch($entityType) {
            case 'messages' :
                    $this->QnAModel->updatePopularity($msgId, $popularityCount);
                break;
            
            case 'articles' :
                    $this->articlemodel->updateArticlePopularity($msgId, $popularityCount);
                break;
            
            default:
                    $this->QnAModel->updatePopularity($msgId, $popularityCount);
                break;
        }
    }

    private function _getDiscussionsList($subCatId) {
        $discussionsInfo = $this->QnAModel->getDiscussionsList($subCatId, $this->dateToCheckFor);
        return $discussionsInfo;
    }

    private function _getArticlesList($start,$count) {
        $articleInfo = $this->articlemodel->getArticlesList($start,$count, $this->dateToCheckFor);
        return $articleInfo;
    }

    private function _getQuestionsListForSubCategory($subCatId) {
        $questionInfo = $this->QnAModel->getQuestionsListForSubCategory($subCatId, $this->dateToCheckFor);
        return $questionInfo;
    }
    
    private function _getDate($dayValue){
        return (date("Y-m-d", strtotime("+".$dayValue." days")));
    }

    public function weeklyShikshaCafeAnaMetricsCategorywise(){
	$this->validateCron();
        //build a config for internal email IDs
        $this->load->helper('messageBoard/ana');
        $this->config->load('messageBoard/AnAMetricsConfig');
        $internaluserEmailIds = $this->config->item('internalUserEmailIds');

        //get user IDs of internal user
        $internaluserUserIds = $this->QnAModel->getUserIdsByEmails($internaluserEmailIds);
        $internaluserUserIdStr = implode(',', $internaluserUserIds);

        $finalData = array();
        //external member category wise data
        $finalData['external']['externalUserQuestions'] = $this->QnAModel->getQuestionMetricForExternalUsers($internaluserUserIdStr);
        $temp = array();
        foreach ($finalData['external']['externalUserQuestions']['miscellaneous'] as $key => $value) {
            $temp[$value['categoryId']] = $value; 
        }
        foreach ($finalData['external']['externalUserQuestions']['category'] as $key => $value) {
            $temp[$value['categoryId']] = $value; 
        }
        $finalData['external']['externalUserQuestions'] = $temp;

        //average TAT for these external questions
        $finalData['external']['tatDataForExternalUserQuestions'] = $this->QnAModel->getTatDataForQuestionsByExternalUsers($internaluserUserIdStr);
        //formatting TAT data
        $finalData['external']['tatDataForExternalUserQuestions'] = calculateTatDataForQuestionsUsers($finalData['external']['tatDataForExternalUserQuestions']);
        
        $categoryTAT_externalUser = array();
        foreach ($finalData['external']['tatDataForExternalUserQuestions'] as $catId => $value) {
            $sum = array_sum($value);
            $count = count($value);
            $categoryTAT_externalUser[$catId] = round(($sum / $count)/60, 2);
        }
        $finalData['external']['tatDataForExternalUserQuestions'] = $categoryTAT_externalUser;

        //internal member category wise data
        $finalData['internal']['internalUserQuestions'] = $this->QnAModel->getQuestionMetricForInternalUsers($internaluserUserIdStr);
        $temp = array();
        foreach ($finalData['internal']['internalUserQuestions']['miscellaneous'] as $key => $value) {
            $temp[$value['categoryId']] = $value; 
        }
        foreach ($finalData['internal']['internalUserQuestions']['category'] as $key => $value) {
            $temp[$value['categoryId']] = $value; 
        }
        $finalData['internal']['internalUserQuestions'] = $temp;
        
        //average TAT for internal questions
        $finalData['internal']['tatDataForInternalUserQuestions'] = $this->QnAModel->getTatDataForQuestionsByInternalUsers($internaluserUserIdStr);
        //formatting TAT data
        $finalData['internal']['tatDataForInternalUserQuestions'] = calculateTatDataForQuestionsUsers($finalData['internal']['tatDataForInternalUserQuestions']);

        $categoryTAT_internalUser = array();
        foreach ($finalData['internal']['tatDataForInternalUserQuestions'] as $catId => $value) {
            $sum = array_sum($value);
            $count = count($value);
            $categoryTAT_internalUser[$catId] = round(($sum / $count)/60, 2);
        }
        $finalData['internal']['tatDataForInternalUserQuestions'] = $categoryTAT_internalUser;

        //number of discussions posted
        $finalData['others']['discussionsPostedOnCategories'] = $this->QnAModel->getCategoryWiseDiscussionPosted();
        $finalData['others']['discussionsPostedOnCategories'] = formatDiscussionDataByCategory($finalData['others']['discussionsPostedOnCategories']);

        //get number of unique users who are posting answers and commenting on discussions
        $finalData['external']['usersPostingAnswersAndDscnComments'] = $this->QnAModel->getUsersPostingAnswersAndDscnComments($internaluserUserIdStr);
        //formatting data
        $finalData['external']['usersPostingAnswersAndDscnComments']['category'] = formatUserPostingDataByExternalUsers($finalData['external']['usersPostingAnswersAndDscnComments']['category']);
        $finalData['external']['usersPostingAnswersAndDscnComments']['miscellaneous'] = formatUserPostingDataByExternalUsers($finalData['external']['usersPostingAnswersAndDscnComments']['miscellaneous']);
        //merging miscellaneous with other categories
        $finalData['external']['usersPostingAnswersAndDscnComments'] = $finalData['external']['usersPostingAnswersAndDscnComments']['category'] + $finalData['external']['usersPostingAnswersAndDscnComments']['miscellaneous'];
        
        //get number of questions answered by external members
        $finalData['external']['numOfQuesAnsweredByExternalUsers'] = $this->QnAModel->getNumOfQuestionsAnsweredByExternalUsers($internaluserUserIdStr);
        //formatting data
        $finalData['external']['numOfQuesAnsweredByExternalUsers']['category'] = formatNumOfQuesAnsweredByExternalUsers($finalData['external']['numOfQuesAnsweredByExternalUsers']['category']);
        $finalData['external']['numOfQuesAnsweredByExternalUsers']['miscellaneous'] = formatNumOfQuesAnsweredByExternalUsers($finalData['external']['numOfQuesAnsweredByExternalUsers']['miscellaneous']);
        //merging miscellaneous with other categories
        $finalData['external']['numOfQuesAnsweredByExternalUsers'] = $finalData['external']['numOfQuesAnsweredByExternalUsers']['category'] + $finalData['external']['numOfQuesAnsweredByExternalUsers']['miscellaneous'];
        //_p($finalData);die;
        //generate excel file and email the excel file
        $this->generateExcelAndEmailAnaReport($finalData);
    }

    function generateExcelAndEmailAnaReport($anaMetric){
        //get applicable categories IDs
        $categoryIds = $this->QnAModel->getCategoryIdsForAnaMetric();
        
        //generate excel file
        $this->load->library('common/PHPExcel');
        $objPHPExcel = new PHPExcel();

        $sheetNum = 0;
        $objWorkSheet = $objPHPExcel->createSheet($sheetNum);
        $objPHPExcel->setActiveSheetIndex($sheetNum);

        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Category wise data');
        $col = 'B';
        foreach ($categoryIds as $id => $name) {
            $objPHPExcel->getActiveSheet()->setCellValue($col.'2', $name);
            $col++;
        }
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Number of questions posted by external members');
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number of questions posted by external members with at least 1 answer');
        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Average TAT for these external questions');
        $objPHPExcel->getActiveSheet()->setCellValue('A6', '');
        $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Number of questions posted by internal members');
        $objPHPExcel->getActiveSheet()->setCellValue('A8', 'Number of questions posted by internal members with at least 1 answer');
        $objPHPExcel->getActiveSheet()->setCellValue('A9', 'Average TAT for these seeded questions');
        $objPHPExcel->getActiveSheet()->setCellValue('A10', '');
        $objPHPExcel->getActiveSheet()->setCellValue('A11', 'Number of new discussions posted');
        $objPHPExcel->getActiveSheet()->setCellValue('A12', '');
        $objPHPExcel->getActiveSheet()->setCellValue('B13', 'Category wise Expert Engagement');
        $objPHPExcel->getActiveSheet()->setCellValue('A14', 'Number of unique external users who are answering the questions or commenting on discussions');
        $objPHPExcel->getActiveSheet()->setCellValue('A15', 'Number of questions answered by external members');

        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('B1:N1');
        $objPHPExcel->getActiveSheet()->getStyle('B13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B13:N13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('B13:N13');

        $col = 'B';
        foreach ($categoryIds as $id => $name) {
            $columnValue1 = isset($anaMetric['external']['externalUserQuestions'][$id]['zeroAnsCount'])?$anaMetric['external']['externalUserQuestions'][$id]['zeroAnsCount']:0;
            $columnValue2 = isset($anaMetric['external']['externalUserQuestions'][$id]['moreThan1AnsCount'])?$anaMetric['external']['externalUserQuestions'][$id]['moreThan1AnsCount']:0;
            $objPHPExcel->getActiveSheet()->setCellValue($col.'3', $columnValue1+$columnValue2);
            $objPHPExcel->getActiveSheet()->setCellValue($col.'4', $columnValue2);
            $columnValue3 = isset($anaMetric['external']['tatDataForExternalUserQuestions'][$id])?$anaMetric['external']['tatDataForExternalUserQuestions'][$id]:'-';
            $objPHPExcel->getActiveSheet()->setCellValue($col.'5', $columnValue3);

            $columnValue4 = isset($anaMetric['internal']['internalUserQuestions'][$id]['zeroAnsCount'])?$anaMetric['internal']['internalUserQuestions'][$id]['zeroAnsCount']:0;
            $columnValue5 = isset($anaMetric['internal']['internalUserQuestions'][$id]['moreThan1AnsCount'])?$anaMetric['internal']['internalUserQuestions'][$id]['moreThan1AnsCount']:0;
            $objPHPExcel->getActiveSheet()->setCellValue($col.'7', $columnValue4+$columnValue5);
            $objPHPExcel->getActiveSheet()->setCellValue($col.'8', $columnValue5);
            $columnValue6 = isset($anaMetric['internal']['tatDataForInternalUserQuestions'][$id])?$anaMetric['internal']['tatDataForInternalUserQuestions'][$id]:'-';
            $objPHPExcel->getActiveSheet()->setCellValue($col.'9', $columnValue6);

            $columnValue7 = isset($anaMetric['others']['discussionsPostedOnCategories'][$id])?$anaMetric['others']['discussionsPostedOnCategories'][$id]:'-';
            $objPHPExcel->getActiveSheet()->setCellValue($col.'11', $columnValue7);

            $columnValue8 = isset($anaMetric['external']['usersPostingAnswersAndDscnComments'][$id])?$anaMetric['external']['usersPostingAnswersAndDscnComments'][$id]:'-';
            $objPHPExcel->getActiveSheet()->setCellValue($col.'14', $columnValue8);
            $columnValue9 = isset($anaMetric['external']['numOfQuesAnsweredByExternalUsers'][$id])?$anaMetric['external']['numOfQuesAnsweredByExternalUsers'][$id]:'-';
            $objPHPExcel->getActiveSheet()->setCellValue($col.'15', $columnValue9);
            $col++;
        }

        $documentName = "weeklyCategorywiseShikshaCafeAnaMetrics".date('Y_m_d').'.xlsx';
                $documentURL = "/tmp/".$documentName;
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($documentURL);
        $this->load->library('alerts_client');
        $alertClient = new Alerts_client();
        $documentContent = base64_encode(file_get_contents($documentURL));
        $this->load->library('Ldbmis_client');
        $misObj = new Ldbmis_client();
        $type_id = $misObj->updateAttachment(1);
        $attachmentId = $alertClient->createAttachment("12",$type_id,'COURSE','ANAMetricReport',$documentContent,'weeklyCategorywiseShikshaCafeAnaMetrics'.date('Y_m_d').'.xlsx','doc','true');
        $attachment = array($attachmentId);
        $content = "<p>Hi,</p> <p>Please find the attached Cafe ANA Metric for this week. </p><p>- Shiksha Tech.</p>";
        $emailIdarray = array('mudit.pandey@shiksha.com', 'megha.gupta@shiksha.com', 'renuka.rana@shiksha.com', 'anil.narayanan@shiksha.com', 'virender.singh@shiksha.com');
        foreach($emailIdarray as $key=>$emailId){
            $alertClient->externalQueueAdd("12",ADMIN_EMAIL,$emailId,'weeklyCategorywiseShikshaCafeAnaMetrics'.date('Y-m-d'),$content,"html",'','y',$attachment);
        }
        /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
        header('Content-Disposition: attachment; filename=weeklyCategorywiseShikshaCafeAnaMetrics'.date('d_m_Y').'.xlsx');
        echo file_get_contents($documentURL);*/
    }

	 /*
     *  Function that fetches "Discussions" from the DB in which atleast one comment has posted in last 24 hours and send mailers regarding new comments in a discussion to all users who had followed these dicussions. 
    */
    function commentPostToDiscussionsFollowedInLast1Day(){
	$this->validateCron();
        $results = $this->QnAModel->getUserDetailFollowedDiscsussion();

        foreach($results as $result){
            $userEmail = $result['userDetails']['email'];
            $contentArr['receiverId'] = $result['userDetails']['userId'];
            $contentArr['followedUserName'] = trim($result['userDetails']['firstname']);
            $contentArr['result'] = $result;
            $fromAddress="noreply@shiksha.com";

            Modules::run('systemMailer/SystemMailer/commentPostToDiscussionFollowedMailer', $userEmail, $contentArr);

        }

    }


    /**
        * @desc Upload excel for auto moderation
        * @date 2016-05-12
        * @author Yamini Bisht
    */

    function uploadAutomoderatedKeywords(){
        ini_set('memory_limit',-1);
        //$file_name = "/var/www/html/shiksha/public/automoderationkeywordsheet.xlsx";
        $file_name = "/var/www/html/shiksha/public/automoderate_keyword_dec2017.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel=$objReader->load($file_name);
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);

        for ($i=2;$i<=255;$i++) {
            $lingo = utf8_encode($objWorksheet->getCellByColumnAndRow(0,$i)->getValue());
            $actual_word = utf8_encode($objWorksheet->getCellByColumnAndRow(1,$i)->getValue());
            $type = utf8_encode($objWorksheet->getCellByColumnAndRow(2,$i)->getValue());

            if(empty($lingo) && empty($actual_word)){
                break;
            }
        
            $data[]=array(
                    'Lingo'=>trim($lingo),
                    'Actual_words'=>$actual_word,
                    'Type'=>$type,
                    'Status'=>'live',
                    );


        }
        $result = $this->QnAModel->automodrationUploader('autoModerationKeywordsMapping',$data);
        echo $result;
    }

    /**
        * @desc Cron to auto moderate an entity
        * @author akhter  
    */
    function reviewAutoModerationCron(){
        $this->validateCron();   
        $this->reviewModel  = $this->load->model('CollegeReviewForm/CollegeReviewModel');
        $results = $this->reviewModel->getReviewForAutoModeration();
        if(empty($results) || count($results)<=0){
            return;
        }
        
        $this->load->library(array('v1/AnACommonLib','CAEnterprise/CAUtilityLib'));
        $this->anaCommonLib = new AnACommonLib();
        $this->caUtilityLib = new CAUtilityLib();
        $this->anamodel     = $this->load->model('messageBoard/AnAModel');
        $keyWord_Data       = $this->anamodel->getAutoModerationKeywordData();
        $this->config->load('messageBoard/SuperlativeConfig');
        $superlativeList    = $this->config->item('superlativeList');
        $cachedData = $this->anaCommonLib->getMappingFromCache();

        foreach($results as $result){

            $autoModerationFlag = false;
            $data     = array();
            $reviewId = $result['reviewId'];

            foreach ($result as $columnName => $text) {

                    if(in_array($columnName, array('reviewId'))){
                        continue;
                    }

                    $maxLength  = 2500;
                    if($columnName == 'reviewTitle'){
                        $maxLength  = 100;
                    }
            
                    if(!empty($text)){

                        //Step 1 - remove impurities or unwanted elements from (a substance) string.
                        $updatedText = $this->anaCommonLib->refineElementFromString($text,true);

                        //Step 2 - check the sms lingo in the text If find then replace with actual keyword.
                        $updatedText = $this->anaCommonLib->autoModerationKeywordReplace($updatedText, $keyWord_Data);

                        //Step 3 - check superlative words from string if found then Add "the" before superlative
                        $updatedText = $this->anaCommonLib->findSuperlative($updatedText, $superlativeList);

			            //Step 4 - Python script to find basecourse, exam and institute (acronyms and synonyms) in sentence(s) and replace it with shiksha's basecourse, exama and institue (acronyms and synonyms)
                        $finalText = $this->anaCommonLib->runCleaningProcess($updatedText, $cachedData);

                        if(empty($finalText)){
                            error_log('==ANA==Crons==reviewAutoModerationCron=='.$updatedText);
                        }
                        $updatedText = empty($finalText) ? $updatedText : $finalText;
                        //Step 5 - spellchecker
                        $updatedText = $this->anaCommonLib->spellCheckString($updatedText);

                        if(!empty($updatedText) && $updatedText != $text && strlen($updatedText)<=$maxLength){
                            $data[$columnName] = $updatedText;
                            $autoModerationFlag = true;
                        }
                    }
            }
            if($autoModerationFlag){
                // track the automoderated data
                $this->caUtilityLib->trackCollegeReview($reviewId, 'autoModerated' , $data, 0);
                // update the original data with automoderated data
                $this->reviewModel->updateDataByAutoModeration($reviewId, $data);
            }

            Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);

            //calculate auto moderation review quality score
            $this->autoModerateCollegeReview($reviewId);


        }

    }

    /**
        * @desc Cron to auto moderate an entity
        * @date 2016-05-12
        * @author Yamini Bisht
    */

    function messageAutoModerationCron(){
     	$this->validateCron();   
        $this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();  
        $this->anamodel = $this->load->model('messageBoard/AnAModel');
        $userId = '0';

        $results = $this->anamodel->getEntityDataForAutomoderation();
        $keyWord_Data = $this->anamodel->getAutoModerationKeywordData();
        $this->config->load('messageBoard/SuperlativeConfig');
        $superlativeList    = $this->config->item('superlativeList');
        $cachedData = $this->anaCommonLib->getMappingFromCache();
        foreach($results as $result){

            if($result['fromOthers'] == 'user' && $result['parentId'] == 0){
                $entityType = 'question';
                $maxLength = '140';
                $maxLengthDesc = '300';
            }else if($result['fromOthers'] == 'user' && $result['parentId'] == $result['threadId']){
                $entityType = 'answer';
                $maxLength = '2500';
            }else if($result['fromOthers'] == 'discussion' && $result['parentId'] == $result['threadId']){
                $entityType = 'discussion';
                $maxLengthMsg = '100';
                $maxLengthDesc = '2500';
            }else if($result['fromOthers'] == 'discussion' && $result['parentId'] != $result['mainAnswerId'] && $result['mainAnswerId']>0){
                $entityType = 'reply';
                $maxLength = '500';
            }else{
                $entityType = 'comment'; 
                if($result['fromOthers'] == 'discussion'){
                    $maxLength = '2500';
                }else{
                    $maxLength = '500';
                }
                
            }
            
            $descriptionUpdateNeeded = false;
            $titleUpdateNeeded = false;
            $automoderationFlag = 0;

            //detect email and phone no in a message
            $contentFlag = $this->anaCommonLib->emailAndPhonenoDetectionInString($result['msgTxt']);
            
            if(!empty($contentFlag)){

              $email_flag = (isset($contentFlag['emailContentFlag']) && $contentFlag['emailContentFlag'] != '')?$contentFlag['emailContentFlag']:0;
              $phoneNo_flag = (isset($contentFlag['phoneNoContentFlag']) && $contentFlag['phoneNoContentFlag'] !='')?$contentFlag['phoneNoContentFlag']:0;
              $url_flag = (isset($contentFlag['urlContentFlag']) && $contentFlag['urlContentFlag'] !='')?$contentFlag['urlContentFlag']:0;
                        
              $this->anamodel->insertContentFlagInDB($result['msgId'], $entityType, $email_flag, $phoneNo_flag,$url_flag);

            }

            //check entity description for automoderation
            if($result['description'] !=''){
                $updatedDesc = $this->anaCommonLib->refineElementFromString($result['description'],true);
                $updatedDesc = $this->anaCommonLib->autoModerationKeywordReplace($updatedDesc, $keyWord_Data);
                $updatedDesc = $this->anaCommonLib->findSuperlative($updatedDesc, $superlativeList);
                $finalUpdatedDesc = $this->anaCommonLib->runCleaningProcess($updatedDesc, $cachedData);
                if(empty($finalUpdatedDesc)){
                    error_log('==ANA==Crons==messageAutoModerationCron==description=='.$updatedDesc);
                }
                $updatedDesc = empty($finalUpdatedDesc) ? $updatedDesc : $finalUpdatedDesc;
                //Step 5 - spellchecker
                $updatedDesc = $this->anaCommonLib->spellCheckString($updatedDesc);

                if(!empty($updatedDesc) && $result['description'] != $updatedDesc && strlen($updatedDesc)<=$maxLengthDesc){
                    $descriptionUpdateNeeded = true;
                }

            }

            //check entity title for automoderation
            $updatedMsgTxt = $this->anaCommonLib->refineElementFromString($result['msgTxt'],true);
            $updatedMsgTxt = $this->anaCommonLib->autoModerationKeywordReplace($updatedMsgTxt, $keyWord_Data);
            $updatedMsgTxt = $this->anaCommonLib->findSuperlative($updatedMsgTxt, $superlativeList);
            $finalUpdatedMsgTxt = $this->anaCommonLib->runCleaningProcess($updatedMsgTxt, $cachedData);
            if(empty($finalUpdatedMsgTxt)){
                error_log('==ANA==Crons==messageAutoModerationCron==msgTxt=='.$updatedMsgTxt);
            }
            $updatedMsgTxt = empty($finalUpdatedMsgTxt) ? $updatedMsgTxt : $finalUpdatedMsgTxt;
            //Step 5 - spellchecker
            $updatedMsgTxt = $this->anaCommonLib->spellCheckString($updatedMsgTxt);

            if(!empty($updatedMsgTxt) && $updatedMsgTxt != $result['msgTxt'] && strlen($updatedMsgTxt)<=$maxLength){
                $titleUpdateNeeded = true;
            }
           
            if($descriptionUpdateNeeded && $titleUpdateNeeded){
                $automoderationFlag = 3;
            }else if(!$titleUpdateNeeded && $descriptionUpdateNeeded){
                $automoderationFlag = 2;
            }else if($titleUpdateNeeded && !$descriptionUpdateNeeded){
                $automoderationFlag = 1;
            }

            // track edit of title or/and description
            if($descriptionUpdateNeeded || $titleUpdateNeeded){             
                $this->anaCommonLib->trackEditOperation($result['msgId'], $entityType, $userId, $automoderationFlag);
            }

            // update the description
            if($descriptionUpdateNeeded){
                $this->anamodel->updateEntityDescription($updatedDesc, $result['threadId']);
            }

            if($titleUpdateNeeded){
                $this->anamodel->updateMsgTextAutoModerate($updatedMsgTxt, $result['msgId']);

                //Edit tags incase of question/discussion
                if($entityType == 'question' || $entityType == 'discussion'){
                        $this->load->library('Tagging/TaggingLib'); 
                        $taggingLib = new TaggingLib(); 
                        $this->load->model('Tagging/taggingmodel');
                        $taggingModel = new TaggingModel(); 
                        $tagsToInsert = array();

                        $tags = $taggingLib->showTagSuggestions(array($updatedMsgTxt));
                        $finalTagsArray = $taggingLib->attachTagsWithParent($tags);
                        
                        $manualTags = $taggingModel->fetchManualTags($result['threadId']);
                        
                        foreach ($manualTags as $key => $value) {
                            $tagType = $value['tag_type'];
                            $tagId = $value['tag_id'];
                            if(array_key_exists($tagType, $finalTagsArray)){
                                $finalTagsArray[$tagType][] = $tagId;
                            } else {
                                $finalTagsArray[$tagType] = array();
                                $finalTagsArray[$tagType][] = $tagId;
                            }
                        }
                
                        $i = 0;
                        foreach ($finalTagsArray as $key => $value) {
                                if(!empty($value)){
                                    
                                    foreach ($value as $key1 => $value1) {
                                        if($value1 != ''){
                                            $tagsToInsert[$i]['tagId'] = $value1;
                                            $tagsToInsert[$i]['classification'] = $key;
                                            $i++;
                                        }
                                    }


                                }
                        }

                        $taggingModel->deleteTagsWithContentToDB($result['threadId']);
                        $taggingLib->insertTagsWithContentToDB($tagsToInsert,$result['threadId'],$entityType,'updatetag');  
                    
                 }
                
            }
        }
        
    }

    ////
    //Desc - Tag all listing questions with the primary institute tag
    //JIRA - MAB-2917
    //Script Type - One time
    //author - akhter
    ////
    function tagListingQuestion(){
        //step-1 Get all institute/university  
        $this->intitutedetaillib  = $this->load->library("nationalInstitute/InstituteDetailLib");
        $this->AnAPreprocess      = $this->load->library('ContentRecommendation/AnAPreprocess');   
        $this->anamodel           = $this->load->model('messageBoard/AnAModel');    
        $result                   = $this->anamodel->getAllInstUniversity();

        $totalResult = count($result);
        if($totalResult <= 100){
            $this->prepareMappingData($result);
        }else{
            //step-2 Split into chunks
            $num = $totalResult;
            $divideby = 100;
            $remainder=$num % $divideby;
            $number=explode('.',($num / $divideby));
            $loop=$number[0];
            $pages = ceil($num/$divideby);
            $pages = $pages - 1;

            for($i=0; $i <= $loop ; $i++){
                $start = $i*$divideby;
                if($i == $pages){
                    $limit =  " limit $start , $remainder";
                    echo $limit.'<br>';
                    $resArr = array_slice($result, $start, $remainder);
                    $this->prepareMappingData($resArr);
                }else{
                    $limit = " limit $start , 100";
                    echo $limit.'<br>';
                    $resArr = array_slice($result, $start, 100);
                    $this->prepareMappingData($resArr);
                }
            }
        }
        echo 'Script has been successfully executed.';
    }

    function prepareMappingData($resArr){

        //step-3 Get all courses which is directly mapped to institute/university
        foreach ($resArr as $key => $value) {
            $listingId   = $value['entity_id'];
            $listingType = ($value['entity_type'] == 'National-University')  ? 'univerity' : 'institute';
            $courses     = $this->intitutedetaillib->getInstituteCourseIds($listingId, $listingType, 'direct');
            $courseArr   = array_merge((array)$courseArr, (array)$courses['courseIds']);

            if(count($courses['instituteWiseCourses'][$listingId])>0){
                $instData[$listingId]['course'] = $courses['instituteWiseCourses'][$listingId];
                $instData[$listingId]['tag_id'] = $value['tag_id'];
            }
        }
        
        //step-4 Get all questions mapped on courses
        $courseList = $this->anamodel->getAllQuestionByCourse($courseArr);
        foreach ($instData as $instituteId => $value) {
            foreach ($value['course'] as $key => $courseId) {
                $courseTag[$courseId] = $value['tag_id']; // primary institute tag_id
            }
        }
        unset($courseArr);

        //step-5 Prepare final data for mapping
        foreach ($courseList as $key => $value) {
            $tag_id  = $courseTag[$value['courseId']];
            if(!empty($tag_id)){
                $rowData['tag_id']           = $tag_id;
                $rowData['content_id']       = $value['messageId'];
                $rowData['content_type']     = 'question';
                $rowData['tag_type']         = 'objective';
                $rowData['status']           = 'live';
                $rowData['modificationTime'] = date('Y-m-d H:i:s');

                $isExist = $this->anamodel->getExistTagsMapping($tag_id, $value['messageId']);
                if(!$isExist){
                    $msgIdList[]  = $value['messageId'];
                    //step-6 Insert data into table
                    $insertId = $this->anamodel->pushData($rowData);          
                    //step-7 Create Index
                    if($insertId){
                        modules::run('search/Indexer/addToQueue', $value['messageId'], 'question'); 
                    }
                }
            }
            unset($rowData);
        }
        //step-8 Run Ana Reco API
        $res = $this->AnAPreprocess->insertCustomTaggedANA(array_unique($msgIdList));
        if($res){
            echo 'Ana reco api has been successfully executed.<br>';
        }
        unset($msgIdList, $courseList, $courseTag, $instData);
    }

	function updateHQCountCron(){
        ini_set('memory_limit', '200M');

        $this->benchmark->mark('code_start');

        $this->load->model('UserPointSystemModel');      
        $userDigUpScore = $this->UserPointSystemModel->getUserDigUpScore();
        $updateStatus = $this->UserPointSystemModel->updateUserDataInTable($userDigUpScore);
        if($updateStatus){
            echo 'Script successfully executed';    
        }else{
            echo 'Script Failed';
        }
        $this->benchmark->mark('code_end');
        echo $this->benchmark->elapsed_time('code_start', 'code_end');

    }

    function updateUserPointForMoreThan5Ques(){
        $this->benchmark->mark('code_start');
        $this->load->model('UserPointSystemModel');      
        $usersWithQues = $this->UserPointSystemModel->getUserswithMoreThan5Ques();
        $userIdsWithMoreQues = array();
        foreach ($usersWithQues as $key => $value) {
            $userIdsWithMoreQues[] = $value['userId'];
            $usersAndCountArr[$value['userId']] = $value['cc']; 
        }
        $userPoints = $this->UserPointSystemModel->getPointsOfUserswithMoreThan5Ques($userIdsWithMoreQues);
        foreach ($userPoints as $userKey => &$userVal) {
        $userVal['userpointvaluebymodule'] = $userVal['userpointvaluebymodule'] - (($usersAndCountArr[$userVal['userId']] - 5) * 5);
        error_log("User Point Deducation Data: User Id = ".$userVal['userId'].' | '."Points Deducted =".(($usersAndCountArr[$userVal['userId']] - 5) * 5)."\n", 3, LOG_User_Point_Deducation);
        }  
        $updateStatus =  $this->UserPointSystemModel->updateUserDataInTable($userPoints);
        if($updateStatus){
            $this->updateLevelOfEligibleUsers($userIdsWithMoreQues);   
        }else{
            echo 'Script 1 Failed';
        }
        $this->benchmark->mark('code_end');
        echo $this->benchmark->elapsed_time('code_start', 'code_end');
    }

    function updateLevelOfEligibleUsers(){
        $this->load->model('UserPointSystemModel'); 
        $userIds =  $this->UserPointSystemModel->getUsersOfHigherLevel();
        $highLevelUserIds = array();
        foreach ($userIds as $key => $value) {
            $highLevelUserIds[] = $value['userId'];
        }     
     
        $pointsArr =  $this->UserPointSystemModel->getCurrentPointsAndHQ($highLevelUserIds);
        foreach ($pointsArr as $pointsKey => &$pointsValue) {
            $pointsValue['levelId'] =  $this->UserPointSystemModel->getLevelFromPoints($pointsValue['userpointvaluebymodule'], $pointsValue['HQContentCount']);
            if($pointsValue['levelId'] != $pointsValue['prevLevelId']){
                $pointsValue['levelName'] =  $this->UserPointSystemModel->getLevelNameFromLevelId($pointsValue['levelId']);
                error_log("User Point Deducation Data: User Id = ".$pointsValue['userId'].' | '."Previous Level Name =".$pointsValue['prevLevel'].' | '."New Level Name =".$pointsValue['levelName']."\n", 3, LOG_User_Level_Updation);
                unset($pointsValue['userpointvaluebymodule']);
                unset($pointsValue['prevLevel']);
                unset($pointsValue['prevLevelId']);
                unset($pointsValue['HQContentCount']);
            }else{
                unset($pointsArr[$pointsKey]);
            }
        }

        $updateStatus =  $this->UserPointSystemModel->updateUserDataInTable($pointsArr);
        if($updateStatus){
            echo 'Script successfully executed'; 
        }else{
            echo 'Script  Failed';
        }
    }

    function detectEmailPhoneNumberInMsgTxt(){
        ini_set('memory_limit',-1);
        $this->anamodel = $this->load->model('messageBoard/AnAModel');
        $this->load->library(array('v1/AnACommonLib','CAEnterprise/CAUtilityLib'));        
        $this->anaCommonLib = new AnACommonLib();
        $results = $this->anamodel->getEntityForAutomoderationWithTimeRange();
        foreach ($results as $key => $value) {
            if($value['fromOthers'] == 'user' && $value['parentId'] == $value['threadId']){
                $entityType = 'answer';
            }else{
                $entityType = 'comment'; 
            }
            $contentFlag = $this->anaCommonLib->emailAndPhonenoDetectionInString($value['msgTxt']);

            if(!empty($contentFlag)){
              $email_flag = (isset($contentFlag['emailContentFlag']) && $contentFlag['emailContentFlag'] != '')?$contentFlag['emailContentFlag']:0;
              $phoneNo_flag = (isset($contentFlag['phoneNoContentFlag']) && $contentFlag['phoneNoContentFlag'] !='')?$contentFlag['phoneNoContentFlag']:0;
              $url_flag = (isset($contentFlag['urlContentFlag']) && $contentFlag['urlContentFlag'] !='')?$contentFlag['urlContentFlag']:0;
                        
              $this->anamodel->insertContentFlagInDB($value['msgId'], $entityType, $email_flag, $phoneNo_flag,$url_flag);

            }
         }
    }

    function test(){
        
        $this->load->library(array('v1/AnACommonLib','CAEnterprise/CAUtilityLib'));
        $this->anaCommonLib = new AnACommonLib();
        $this->caUtilityLib = new CAUtilityLib();
        $this->anamodel     = $this->load->model('messageBoard/AnAModel');
        $keyWord_Data       = $this->anamodel->getAutoModerationKeywordData();
        $this->config->load('messageBoard/SuperlativeConfig');
        $superlativeList    = $this->config->item('superlativeList');
        //$results = $this->anamodel->getEntityDataForAutomoderation();
        
        $text = "I AM biggest politest DOASF b.tech AS F ASDFDASF12A \n , ? \1 @ 3  12121 http://shiksha.com/messageBoard/AnACrons/test1 asf as fasdf asdfdas http://www.shiksha.com/CA/CRDashboard/getCRUnansweredTab2 and http://shiksha.com/CA/CRDashboard/getCRUnansweredTab3";
        
        //Step 1 - remove impurities or unwanted elements from (a substance) string.
        $updatedText = $this->anaCommonLib->refineElementFromString($text,true);
        //Step 2 - check superlative words from string if found then Add "the" before superlative
        $updatedText = $this->anaCommonLib->findSuperlative($updatedText, $superlativeList);

        //Step 3 - check the sms lingo in the text If find then replace with actual keyword.
        $updatedText = $this->anaCommonLib->autoModerationKeywordReplace($updatedText, $keyWord_Data);

        //Step 4 - Python script to find basecourse, exam and institute (acronyms and synonyms) in sentence(s) and replace it with shiksha's basecourse, exama and institue (acronyms and synonyms)
        $finalText = $this->anaCommonLib->runCleaningProcess($updatedText);
        $updatedText = empty($finalText) ? $updatedText : $finalText;
        //Step 5 - spellchecker
        $updatedText = $this->anaCommonLib->spellCheckString($updatedText);

        echo $updatedText;
    }

    
    function sendEditMailToUserAndDeleteAnswerIfReq(){
       $this->validateCron();
            $deleteStatus = $this->deleteNotEditedAnswers();
            $this->load->library('mailerClient');
            $MailerClient = new MailerClient();

            $editAnswerIdsData = $this->QnAModel->getEditRequestedAnswerIds('secondMail');
            if(empty($editAnswerIdsData)){
                return;
            }
            foreach ($editAnswerIdsData as $key => $value) {
                $secondMailAnswerIds[] = $value['entityId'];
            }
            if(empty($secondMailAnswerIds)){
                return;
            }
            $answerData = $this->QnAModel->getQuestionIdsAndUserIdOfAnswers($secondMailAnswerIds);
            foreach ($answerData as $key => $value) {
                $questionIds[] = $value['parentId'];
                $userIds[] = $value['userId'];
                $answerFormatData[$value['msgId']]['parentId'] = $value['parentId'];
                $answerFormatData[$value['msgId']]['userId'] = $value['userId']; 
            }
            if(empty($questionIds)){
                return;
            }
            $questionText = $this->QnAModel->getQuestionText($questionIds);
            foreach ($questionText as $k1 => $ques) {
                $questionFormatArr[$ques['msgId']] = $ques['msgTxt'];
            }
            $userDetails = $this->QnAModel->getUserNameAndEmail($userIds);
            foreach ($userDetails as $k2 => $name) {
                $userFormatArr[$name['userid']]['email'] = $name['email'];
                $userFormatArr[$name['userid']]['name'] = $name['firstname'];
            }

            foreach ($editAnswerIdsData as $i => $result) {
                $contentArr = array();
                $questionId = $answerFormatData[$result['entityId']]['parentId'];
                if(empty($questionFormatArr[$questionId])){
                    continue;
                }
                $userId = $answerFormatData[$result['entityId']]['userId'];
                $urlOfLandingPage = getSeoUrl($questionId, 'question').'?answerId='.$result['entityId'];
                $userEmailId = $userFormatArr[$userId]['email'];
                $contentArr['userEmailId'] = $userEmailId ;
                $contentArr['userName'] = $userFormatArr[$userId]['name'];
                $contentArr['userProfileUrl'] = SHIKSHA_HOME.'/userprofile/'.$userId;
                $contentArr['subject'] = 'Shiksha - Final request to Improve your Answer';
                $contentArr['questionText'] = $questionFormatArr[$questionId];
                $contentArr['reasonToEdit'] = $result['reasonToEdit'];
                $contentArr['userId'] = $userId;
                $contentArr['timeLimit'] = 24;
                $contentArr['commentText'] = $result['comment'];
                $contentArr['seoURL'] = $urlOfLandingPage;
                $mailStatus = Modules::run('systemMailer/SystemMailer/sendRequestToEditAnswerMailer',$contentArr);
                if($mailStatus == 'Inserted Successfully'){
                    $answerIdsToBeUpdated[] = $result['entityId'];
                }
            }
            $updateStatus = $this->QnAModel->updateAnswerMailStatus($answerIdsToBeUpdated);
        if($updateStatus){
            echo 'Cron run successfully';
        }else{
            echo 'Cron Failed';
        }
    }

    function deleteNotEditedAnswers(){
        $reasonToDelete = array(3, 4);
        $toBeDeletedAnswerIds = array();
        $toBeDeletedAnswerIdsData = $this->QnAModel->getEditRequestedAnswerIds('deleteAnswers');
        foreach ($toBeDeletedAnswerIdsData as $key => $value) {
            $toBeDeletedAnswerIds[] = $value['entityId'];
            $formatDeleteArr[$value['entityId']] = $value['reasonToEdit'];
        }
        foreach ($formatDeleteArr as $msgId => $reasonStr) {
            $deleteFlag = 0;
            $reasonArr = explode(',', $reasonStr);
            foreach ($reasonArr as $i => $reasonV) {
                if(in_array($reasonV, $reasonToDelete)){
                    $deleteFlag = 1;
                }
            }
            if($deleteFlag == 1){
                $finalAnswerIdsToDelete[] = $msgId;
            }
        }
        if(empty($finalAnswerIdsToDelete)){
            return;
        }
        $deleteStatus = $this->QnAModel->deleteAnswersFromMessageTable($finalAnswerIdsToDelete);
        if($deleteStatus){
          $finalStatus =  $this->QnAModel->deleteAnswersFromModerationEdit($finalAnswerIdsToDelete); 
        }
    }


    private function autoModerateCollegeReview($reviewId){
             

        $caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
        $this->reviewModel  = $this->load->model('CollegeReviewForm/CollegeReviewModel');
        $this->load->config('CollegeReviewForm/collegeReviewConfig');
        $rejectionScore = $this->config->item('rejectionScore');

        $results = $this->reviewModel->getCollegeReviewData($reviewId);
        
        $results = $results[0];
        if($results['reviewId'] <1){
           // mail('ajay.sharma@shiksha.com','CR Auto Moderation not working', "Review data - ".$results);
            return;
        }

        $results['isShikshaInstitute'] = 'YES';
        
        $cr_API_data = json_encode([$results]) ;
        

        $this->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        $response = $this->solrServer->MMMSearchCurl(CR_ML_URL, $cr_API_data);


        $response  = json_decode($response,true);
        $response = $response['response'][$reviewId];
        $score = $response['score'];
        $status = '';
        $sendRejetionMail = false;

        if(!isset($response)){
            //track this data
            mail('ajay.sharma@shiksha.com, mohammad.hammad@99acres.com','CR Auto Moderation not working', "Review data - ".$cr_API_data);
            return;
        }

        unset($cr_API_data);
   
        if($score<=$rejectionScore){
            $status = 'rejected';
            $sendRejetionMail = true;
        }

        if($score >= 0){
            $this->reviewModel->updateReviewScoreAndStatus($reviewId, $score, $status);
        }

        //send rejection mail
        if($sendRejetionMail){
            $time =  date('Y-m-d H:i:s', strtotime('+1 hour'));
            Modules::run('CAEnterprise/CampusAmbassadorEnterprise/processBulkCRMailers', $results, true,$time);
        }


        //Track Review Data
        $tracking_data = array('qualityScore'=>$score);
        $tracking_data = json_encode($tracking_data);
        $caUtilityLib->trackCollegeReview($reviewId, 'qualityScoreAdded', $tracking_data, 11);
    

        //Add to Moderation Table and tracking table
        if($score<=$rejectionScore){
            $tracking_data = array("status"=>"rejected","reason"=>"5");
            $caUtilityLib->trackCollegeReview($reviewId, 'statusUpdated', $tracking_data, 11);
        
            $this->load->model('CAEnterprise/reviewenterprisemodel');
            $this->reviewenterprisemodel->storeModerationDetails($reviewId, 'cmsadmin@shiksha.com','rejected');
        }

        unset($tracking_data);

        return;
    }

    function sendContributionMailerToNonCampusReps($type=3){
        $this->validateCron();
	ini_set('memory_limit', '400M');
        $totalMailUsers = array();
        $crUserData = $this->QnAModel->getAllCampusReps();
        foreach ($crUserData as $key => $value) {
            $crUserIds[] = $value['userId'];
        }
        $firstMailUsers = $this->QnAModel->getContributionMailerUsers('first', $crUserIds);
        if($type == 6){
           $secondMailUsers = $this->QnAModel->getContributionMailerUsers('second', $crUserIds);
        }
        foreach ($firstMailUsers as $key => $value) {
           $firstMailUserIds[] = $key; 
           $firstMailAnswerIds[] = $value;
        }
        foreach ($secondMailUsers as $key1 => $value1) {
           $secondMailUserIds[] = $key1; 
           $secondMailAnswerIds[] = $value1;
        }

        if($type == 6){
            foreach ($secondMailUserIds as $seckey => $secVal) {
                if(in_array($secVal, $firstMailUserIds)){
                    unset($secondMailUserIds[$seckey]);
                }
            }
                if(!empty($secondMailUserIds)){
                    $totalMailUsers = $secondMailUserIds;
                }
        }

        if(!empty($firstMailUserIds) && $type == 3){
            $totalMailUsers = $firstMailUserIds;
        }

        $totalAnswerData = $this->QnAModel->getContributionMailerUsers('noCheck', $totalMailUsers);
        $userDetails = $this->QnAModel->getUserNameAndEmail($totalMailUsers);
        foreach ($userDetails as $k2 => $name) {
                $usersFirstNames[$name['userid']]['email'] = $name['email'];
                $usersFirstNames[$name['userid']]['name'] = $name['firstname'];
        }
        foreach ($totalAnswerData as $userId => $msgId) {
            $tempArr = array();
            $tempArr['userId'] = $userId;
            $tempArr['answerCount'] = count($msgId['answerIds']);
            $questionIds = $msgId['questionIds'];
            $answerIds = $msgId['answerIds'];
            $totalQuesViewCount = $this->QnAModel->getQuestionViewCount($questionIds);
            $totalAnsUpVotes = $this->QnAModel->getAnswerUpvotesCount($answerIds);
            if(empty($totalQuesViewCount['total'])){
                $totalQuesViewCount['total'] = 0;
            }
            if(empty($totalAnsUpVotes['totalUpVotes'])){
                $totalAnsUpVotes['totalUpVotes'] = 0;
            }
            $tempArr['totalViewCount'] = $totalQuesViewCount['total'];
            $tempArr['totalUpvotesCount'] = $totalAnsUpVotes['totalUpVotes'];
            $lastAnsweredArr[$userId] = reset($msgId['questionIds']);
            $mailerData[$userId] = $tempArr;
        }
        foreach ($lastAnsweredArr as $userId => $lastMsgId) {
            $lastAnsweredData[$userId] = $this->QnAModel->getLastAnsweredQuestionOfUser($lastMsgId);
        }
        $topicsFollowedByUsers = $this->QnAModel->getTopicsFollowed($totalMailUsers);
        foreach ($topicsFollowedByUsers as $tkey => $tvalue) {
            $unansweredQuestionsFromTopic[$tkey] = $this->QnAModel->getTagsEntityFollowed($tvalue);
            
            $countUnansweredQuestionBasedOnTopic[$tkey] = $this->QnAModel->getCountOfUnansQuestions($tvalue);
        }
        $allTagIds  = array();
        foreach ($countUnansweredQuestionBasedOnTopic as $key1 => $tagArr) {
            foreach ($tagArr as $tagId => $msgId) {
                $allTagIds[] = $tagId;
            }
        }
        if(!empty($allTagIds)){
            $tagNames = $this->QnAModel->getTagNames($allTagIds);
        }
        $this->load->library('search/Solr/SolrClient');
        $this->solrClient = new SolrClient;
        $solrDataArr['pageLimit'] = 5;
        $solrDataArr['pageNum'] = 1;
        foreach ($lastAnsweredData as $userId => $msgTxt) {
            $solrDataArr['keyword'] = $msgTxt;
            if(!empty($msgTxt)){
                $solrResults[$userId] = $this->solrClient->getUnansweredQuestions($solrDataArr);
            }
        }

        $todaysDay = date('N');
        if($todaysDay == 1 || $todaysDay == 2){
            $subject = 'with great knowledge comes great responsibility- Be responsible, Answer now!';
        }else if($todaysDay == 3){
            $subject = 'here are few questions that need your guidance';
        }else if($todaysDay == 5 || $todaysDay = 6){
            $subject = 'a glance at your numbers till now ! Your Shiksha Popularity Report';
        }else {
            $subject = 'with great knowledge comes great responsibility- Be responsible, Answer now!';            
        }
        foreach ($totalMailUsers as $key => $userId) {
            if(empty($mailerData[$userId])){
                continue;
            }
            $contentArr['mailerData'] = $mailerData[$userId];
            $contentArr['userName'] = $usersFirstNames[$userId]['name'];
            $contentArr['userEmailId'] = $usersFirstNames[$userId]['email'];
            $contentArr['unansweredQuestionsFromTopic'] = $unansweredQuestionsFromTopic[$userId];
            $contentArr['solrResults'] = $solrResults[$userId];
            $contentArr['countUnansweredQuestion'] = $countUnansweredQuestionBasedOnTopic[$userId];
            $contentArr['tagNames'] = $tagNames;
            $contentArr['type'] = $type;
            $contentArr['userId'] = $userId;
            $contentArr['utmTerm']['topic'] = 'Known_Topics'; 
            $contentArr['utmTerm']['similar'] = 'Similar_Ques'; 
            $contentArr['subject'] = $usersFirstNames[$userId]['name'].', '.$subject;
            $mailStatus = Modules::run('systemMailer/SystemMailer/sendContributionMailerToNonCampusRep',$contentArr);
        }

    }


    function sendContributionMailerToCampusReps(){
     	$this->validateCron();
        ini_set('memory_limit', '400M');

        $totalMailUsers = array();
        $crUserData = $this->QnAModel->getAllCampusReps();

        $crListingMapping = array();
        $userInstMapping = array();
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository(); 
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();  
  

        foreach ($crUserData as $key => $value) {
            $crUserIds[] = $value['userId'];
            $crListingMapping[$value['userId']]['courseId'] = $value['courseId'];
            $crListingMapping[$value['userId']]['instituteId'] = $value['instituteId'];
            $allCourseIds[] = $value['courseId'];
            $allInstIds[] = $value['instituteId'];
        }
        $totalMailUsers = $crUserIds;
        $totalAnswerData = $this->QnAModel->getContributionMailerUsers('noCheck', $totalMailUsers);
        $userDetails = $this->QnAModel->getUserNameAndEmail($totalMailUsers);
        foreach ($userDetails as $k2 => $name) {
            $usersFirstNames[$name['userid']]['email'] = $name['email'];
            $usersFirstNames[$name['userid']]['name'] = $name['firstname'];
        }
        foreach ($totalAnswerData as $userId => $msgId) {
            $tempArr = array();
            $tempArr['userId'] = $userId;
            $tempArr['answerCount'] = count($msgId['answerIds']);
            $questionIds = $msgId['questionIds'];
            $answerIds = $msgId['answerIds'];
            $totalQuesViewCount = $this->QnAModel->getQuestionViewCount($questionIds);
            $totalAnsUpVotes = $this->QnAModel->getAnswerUpvotesCount($answerIds);
            if(empty($totalQuesViewCount['total'])){
                $totalQuesViewCount['total'] = 0;
            }
            if(empty($totalAnsUpVotes['totalUpVotes'])){
                $totalAnsUpVotes['totalUpVotes'] = 0;
            }
            $tempArr['totalViewCount'] = $totalQuesViewCount['total'];
            $tempArr['totalUpvotesCount'] = $totalAnsUpVotes['totalUpVotes'];
            $mailerData[$userId] = $tempArr;
        }
        $instTagsMapping = $this->QnAModel->getTagsMappedToInst($allInstIds);

        
             
        $todaysDay = date('N');
        if($todaysDay == 2){
            $subject = 'with great knowledge comes great responsibility- Be responsible, Answer now!';
        }
        else if($todaysDay == 4){
            $subject = 'an answer a day keeps the unanswered away. Your guidance needed';
        }
        else if($todaysDay == 6){
            $subject = 'a glance at your numbers till now! Your Shiksha Popularity Report';
        }else {
            $subject = 'with great knowledge comes great responsibility- Be responsible, Answer now!';     
        }

        $allCourseObj = $this->courseRepo->findMultiple($allCourseIds, array('course_type_information'));
        $allInstObj = $this->instituteRepo->findMultiple($allInstIds, array('basic'));
        foreach ($allInstObj as $instId => $instObj) {
           $instUrls[$instId] = $instObj->getURL(); 
        }
        foreach ($allCourseObj as $courseId => $crsObj) {
            $courseTypeInfo[$courseId] = $crsObj->getPrimaryHierarchy();
            $instId = $crsObj->getInstituteId();
            $courseToInstUrl[$courseId] = $instUrls[$instId];
        }

        foreach ($courseTypeInfo as $crsId => $mappingArr) {
            if(!empty($mappingArr['stream_id']) && $mappingArr['stream_id'] > 0){
                $streamArr[] = $mappingArr['stream_id'];   
            }            
            if(!empty($mappingArr['substream_id']) && $mappingArr['substream_id'] > 0){
                $subStreamArr[] = $mappingArr['substream_id'];   
            }
            if(!empty($mappingArr['specialization_id']) && $mappingArr['specialization_id'] > 0){
                $specArr[] = $mappingArr['specialization_id'];   
            }
        }
        $streamToTagsMapping = $this->QnAModel->getTagsMappedToCourseType($streamArr, 'stream');
        $subStreamToTagsMapping = $this->QnAModel->getTagsMappedToCourseType($subStreamArr, 'substream');
        $specToTagsMapping = $this->QnAModel->getTagsMappedToCourseType($specArr, 'spec');
        
        foreach($courseTypeInfo as $course=>$map){
            if(!empty($map['stream_id']) && $map['stream_id'] > 0){
                $courseToTagsMapping[$course][] = $streamToTagsMapping[$map['stream_id']];
            }
            if(!empty($map['substream_id']) && $map['substream_id'] > 0){
                $courseToTagsMapping[$course][] = $subStreamToTagsMapping[$map['substream_id']];
            }
            if(!empty($map['specialization_id']) && $map['specialization_id'] > 0){
                $courseToTagsMapping[$course][] = $specToTagsMapping[$map['specialization_id']];
            }
        }
        
        foreach ($crListingMapping as $userId => $mapping) {
            $questionsOnCourse[$userId] = $this->QnAModel->getQuestionsBasedOnCourse($mapping['courseId']);
            $excludeMsgIds = array();
            foreach ($questionsOnCourse[$userId] as $key => $value) {
                $excludeMsgIdsFirstLevel[] = $value['msgId'];
            }
            $tagId = $instTagsMapping[$mapping['instituteId']];
            $questionsOnInst[$userId] = $this->QnAModel->getQuestionsBasedOnInst($tagId, $excludeMsgIdsFirstLevel, 'inst');
            foreach ($questionsOnInst[$userId] as $key1 => $value1) {
                $excludeMsgIdsSecondLevel[] = $value1['msgId'];
            }
            $totalExcludeIds = array();
            if(!empty($excludeMsgIdsFirstLevel) && !empty($excludeMsgIdsSecondLevel)){
                $totalExcludeIds = array_merge($excludeMsgIdsFirstLevel, $excludeMsgIdsSecondLevel);
            }
            else if(!empty($excludeMsgIdsFirstLevel) && empty($excludeMsgIdsSecondLevel)){
                $totalExcludeIds = $excludeMsgIdsFirstLevel;
            }else if(empty($excludeMsgIdsFirstLevel) && !empty($excludeMsgIdsSecondLevel)){
                $totalExcludeIds = $excludeMsgIdsSecondLevel;
            }
            $otherQuestionOnCourseHier[$userId] =  $this->QnAModel->getQuestionsBasedOnInst($courseToTagsMapping[$mapping['courseId']], $totalExcludeIds, 'tag');
            if(count($otherQuestionOnCourseHier[$userId]) > 5){
                $randomKeys[$userId] =  array_rand($otherQuestionOnCourseHier[$userId], 5);
                foreach ($randomKeys[$userId] as $key => $val) {
                    $fiveOtherQuestionOnCourse[$userId][$key] = $otherQuestionOnCourseHier[$userId][$val];
                }
            }else {
                $fiveOtherQuestionOnCourse[$userId] = $otherQuestionOnCourseHier[$userId];
            }
        }

        foreach ($totalMailUsers as $key => $userId) {
            if(empty($mailerData[$userId])){
                continue;
            }
            if(empty($questionsOnCourse[$userId]) && empty($questionsOnInst[$userId]) && empty($fiveOtherQuestionOnCourse[$userId])){
                continue;
            }
            $contentArr['mailerData'] = $mailerData[$userId];
            $contentArr['userName'] = $usersFirstNames[$userId]['name'];
            $contentArr['userEmailId'] = $usersFirstNames[$userId]['email'];
            $contentArr['questionsOnCourse'] = $questionsOnCourse[$userId];
            $contentArr['questionsOnInst'] = $questionsOnInst[$userId];
            if(!empty($questionsOnCourse[$userId]) || !empty($questionsOnInst[$userId])){
                $contentArr['thirdSectionHeading'] = 'Other unanswered questions that you might like to answer';
            }else {
                $contentArr['thirdSectionHeading'] = 'Unanswered questions that you might like to answer';                
            }
            $courseId = $crListingMapping[$userId]['courseId'];
            $instUrl = $courseToInstUrl[$courseId];
            $viewAllQuesOnCourse = '';
            $viewAllQuesOnInst = '';
            if(!empty($instUrl)){
                $viewAllQuesOnCourse = $instUrl."/questions?course=".$courseId."&type=unanswered";
                $viewAllQuesOnInst = $instUrl."/questions?&type=unanswered";
            }
            $contentArr['viewAllQuesOnCourse'] = $viewAllQuesOnCourse;
            $contentArr['viewAllQuesOnInst'] = $viewAllQuesOnInst;
            $contentArr['otherQuestionOnCourseHier'] = $fiveOtherQuestionOnCourse[$userId];
            $contentArr['userId'] = $userId;
            $contentArr['subject'] = $usersFirstNames[$userId]['name'].', '.$subject;
            $contentArr['utmTerm']['course'] = 'Course'; 
            $contentArr['utmTerm']['institute'] = 'College'; 
            $contentArr['utmTerm']['other'] = 'Others'; 
            $mailStatus = Modules::run('systemMailer/SystemMailer/sendContributionMailerToCampusRep',$contentArr);
        }

    }    

}
