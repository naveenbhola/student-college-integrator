<?php
class ContentPageLib {

    private $CI = '';
    private $SAContentModel;

    function __construct(){
        $this->CI = &get_instance();
        $this->SAContentModel   = $this->CI->load->model('blogs/sacontentmodel');
        //$this->contentpagemodel = $this->CI->load->model('contentPage/contentpagemodel');
    }

    function getContentDetails($contentId){
        $contentDetails = $this->SAContentModel->getContentDetails($contentId);
        if(empty($contentDetails)) {
            $url = SHIKSHA_STUDYABROAD_HOME;
            header("Location:$url ",TRUE,301);
            exit;
        }
        if(isset($contentDetails['data']['contentURL']) && $contentDetails['data']['contentURL']!='' && $contentDetails['data']['contentURL']!=getCurrentPageURLWithoutQueryParams()){
            $url = $contentDetails['data']['contentURL'];
            header("Location: $url",TRUE,301);
            exit;
        }
        $contentDetails ['rating'] = $this->SAContentModel->getRating($contentId);
        $this->checkNGetScholarshipsMapped($contentDetails);
        return  $contentDetails;
    }

    function formatContentSectionDetails(&$contentDetails, $sourceApp){
        $imgClasses = 'content-img';
        if($sourceApp == 'desktop'){
            $imgClasses .= ' lazy';
        }
        if(is_array($contentDetails)){
            foreach ($contentDetails['data']['sections'] as &$section) {
                $section['details'] = str_replace('src="/mediadata', 'class="'.$imgClasses.'" src="'.IMGURL_SECURE.'/mediadata', $section['details']);
            }
        }else{
            $contentDetails = str_replace('src="/mediadata', 'class="content-img" src="'.IMGURL_SECURE.'/mediadata', $contentDetails);
        }
    }

    function getSeoDetailsForContent(& $displayData)
    {
        $content = $displayData['content'];
        if($content['data']['seo_title'] != '')
        {
            $seoTitle = $content['data']['seo_title'];
        }else
        {
            $seoTitle = $content['data']['strip_title'];
        }
        $displayData['seoTitle'] = $seoTitle;
        $displayData['canonicalURL'] = $content['data']['contentURL'];

        if($content['data']['seo_description'] == '') {
            $text = strip_tags($content['data']['summary']);
        }else {
            $text = strip_tags($content['data']['seo_description']);
        }

        $text = trim($text);
        $text = str_replace('&nbsp;',' ',$text);
        if(strlen($text) > 150) {
            $newText = substr($text,150,160);
            $spaceAfter150 = stripos($newText,' ');
            $text = substr($text,0,150+$spaceAfter150);
        }else {
            $text = substr($text, 0, 150);
        }

        $displayData['metaDescription'] = $text;
        $displayData['imageUrl'] = str_replace('_s','',$content['data']['contentImageURL']);
        $displayData['pgType'] = $content['data']['type'] == 'guide' ? 'guidePage' : 'articlePage';

        $robots = 'ALL';
        if($content['data']['content_id']==246){
            $robots = 'NOINDEX';
        }
        $displayData['robots'] = $robots;
    }

    public function getRemarketingDataForContent($contentId) {
        return $this->SAContentModel->getRemarketingDataForContent($contentId);
    }

    public function getCommentsForContent($contentId, $pageNo,$sectionId=0){
        $result = $this->SAContentModel->getComments($contentId,'comment',array(),($pageNo-1)*5,$sectionId,5);
        $commentCount = $result['total'];
        $comments = $result['data'];

        $commentIds = array_map(function($ele){return $ele['commentId'];},$comments);
        if(!empty($commentIds)) {
            // collect userIds of commentors
            $userIds = array_map(function($a){ return $a['userId']; },$comments);

            $result = $this->SAContentModel->getComments($contentId,'reply',$commentIds);
            $replies = $result['data'];
            if($result['total']>0)
            {
                // collect userIds of commentors
                $userIds = array_merge($userIds, array_map(function($a){ return $a['userId']; },$replies));

            }
        }
        // get user information:
        $userData = $this->getUserInfoForComments(array_unique($userIds));
        $this->_formatCommentsAndReplies($comments,$replies);
        return array('data' =>$comments,'total'=>$commentCount,'userData'=>$userData);
    }
    /*
     * get following user info:
     * 	- user profile picture url
     *  - user university admitted / aspirant str
     *  - user profile page url
     */
    public function getUserInfoForComments($userIds)
    {
        $userData = array();
        if(count($userIds)>0)
        {
            $this->userProfilePageLib = $this->CI->load->library('userProfilePage/userProfilePageLib');
            // get display name, profile image url
            $returnData = $this->SAContentModel->getUserInfoForCommentSection($userIds);
            // get user desiredCourseObj
            $this->CI->load->builder('LDBCourseBuilder','LDB');
            $ldbBuilder = new LDBCourseBuilder();
            $ldbRepo = $ldbBuilder->getLDBCourseRepository();
            $desiredCourses = array_filter(array_values($returnData['prefs']));
            if(count($desiredCourses)>0)
            {
                $ldbCourses = $ldbRepo->findMultiple(array_filter(array_values($returnData['prefs'])));
            }
            // get university admitted / aspirant string
            $userAdmittedStrs = $this->userProfilePageLib->userAdmittedInfo($userIds);
            // combine/process
            foreach($returnData['results'] as $row)
            {//_p($row);
                $urlData = $this->userProfilePageLib->getUserProfilePageURL(urlencode($row['displayname']),'viewPage',false, true);
                $userData[$row['userid']]['profilePageUrl'] = $urlData['url'];
                // except cousellor review desktop use mobile size
                if(is_null($row['avtarimageurl']) || $row['avtarimageurl'] == ''||$row['avtarimageurl'] == '/public/images/photoNotAvailable.gif')
                {
                    $avtarimage = '/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
                }else{
                    $avtarimage = $row['avtarimageurl'];
                }
                $userData[$row['userid']]['imageUrl'] = $this->userProfilePageLib->prependDomainToUserImageUrlInComments($avtarimage,false,true);
                // univ admitted / aspirant String
                $str = '';
                if(is_array($userAdmittedStrs) && $userAdmittedStrs[$row['userid']] != '')
                {
                    $str = $userAdmittedStrs[$row['userid']];
                }else if(count($userIds)==1 && !is_null($userAdmittedStrs)){
                    $str = $userAdmittedStrs; // returns string directly in this case
                }
                else if(!is_null($ldbCourses[$returnData['prefs'][$row['userid']]])){
                    $str = $ldbCourses[$returnData['prefs'][$row['userid']]]->getCourseLevel1()." Aspirant";
                }
                $userData[$row['userid']]['admittedStr'] = $str;
                $userData[$row['userid']]['userName'] = $row['firstname']." ".$row['lastname'];
            }
        }
        return $userData;
    }

    private function _formatCommentsAndReplies(& $comments,& $replies){
        //Since we only have 1 level depth in replies, we are not writing a generic function
        $tempArray = array();
        foreach($comments as $comment){
            $tempArray[$comment['commentId']] = $comment;
        }
        $comments = $tempArray;
        foreach($replies as $reply){
            $comments[$reply['parentId']]['replies'][$reply['commentId']] = $reply;
        }
    }

    public function submitComment($postData){
        unset($postData['authorId']);
        $postData['source'] = "mobile";
        $postData['visitorSessionid'] = getVisitorSessionId();
        $commentId = $this->SAContentModel->addComment($postData);
        return $commentId;
    }

    public function getBrowseWidgetData($contentId,$flagUnivOnly=false)
    {
        $this->categoryPageRequest 	= $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        $this->articlesAbroadWidgetsModel = $this->CI->load->model('studyAbroadArticleWidget/articlesabroadwidgetsmodel');
        $articleCountry 	= $this->articlesAbroadWidgetsModel->getArticleCountry($contentId);
        $result = $this->_LDBCourseCheckForBrowseWidget($contentId,$articleCountry,$flagUnivOnly);
        if($result === false)
        {
            $result = $this->_courseCheckForBrowseWidget($contentId,$articleCountry,$flagUnivOnly);
            if($result === false)
            {
                $params = array();
                $result['finalArray'] = $this->prepareUniversityWidget($params,$articleCountry,$this->categoryPageRequest);
                return $result;
            }
        }
        return $result;
    }

    private function _LDBCourseCheckForBrowseWidget($contentId,$articleCountry,$flagUnivOnly)
    {
        if(!$flagUnivOnly)
        {
            $articleDesiredCourseId = $this->articlesAbroadWidgetsModel->getArticleDesiredCourse($contentId);
        }
        if(!$flagUnivOnly && is_array($articleDesiredCourseId) && $articleDesiredCourseId[0]['ldb_course_id']!='' && $articleDesiredCourseId[0]['ldb_course_id']>0)
        {
            $ldbCourseId 	= $articleDesiredCourseId[0]['ldb_course_id'];
            $this->CI->load->builder('LDBCourseBuilder','LDB');
            $ldbCourseBuilder 	= new LDBCourseBuilder;
            $ldbRepository 	= $ldbCourseBuilder->getLDBCourseRepository();
            $ldbCourseObj 	= $ldbRepository->find($ldbCourseId);
            $ldbcourseName 	= $ldbCourseObj->getCourseName();
            $specialization 	= $ldbCourseObj->getSpecialization();
            $specialization 	= strtolower($specialization);
            if(!($specialization == 'all' || $specialization == ''))
            {
                $ldbcourseName = $ldbcourseName." ".$specialization;
            }
            $result['widgetHeading'] = $ldbcourseName;
            $params['LDBCourseId'] 	= $ldbCourseId;
            $params['courseLevel'] 	= "";
            $params['categoryId'] 	= 1;
            $params['subCategoryId'] 	= 1;
            $result['finalArray'] = $this->prepareCourseWidget($params ,$articleCountry,$this->categoryPageRequest,true);
            return $result;
        }
        return false;
    }

    private function _courseCheckForBrowseWidget($contentId,$articleCountry,$flagUnivOnly)
    {
        if(!$flagUnivOnly)
        {
            $articleArr 		= $this->articlesAbroadWidgetsModel->getArticleCourseMappingData($contentId);
            if(is_array($articleArr))
            {
                $articleCourseLevel	= $articleArr[0];
                $articleSubCat	= $articleArr[1];
                $articleCat		= $articleArr[2];
            }
        }
        if(!$flagUnivOnly && $articleSubCat>0 && $articleCat>0 && $articleCourseLevel!='')
        {
            $this->CI->load->builder('categoryList/CategoryBuilder');
            $categoryBuilder 	= new CategoryBuilder;
            $categoryRepository 	  = $categoryBuilder->getCategoryRepository();
            $categoryObject  		  = $categoryRepository->find($articleCat);
            $parentCatName 		  = $categoryObject->getName();
            $result['widgetHeading'] = $parentCatName;

            if($articleCourseLevel!='')
            {
                $params['courseLevel'] 		= strtolower($articleCourseLevel);
                $result['widgetHeading'] 	= $articleCourseLevel.' of '.$parentCatName;
            }
            else
            {
                $params['courseLevel']	= "";
            }
            $params['categoryId'] 	= $categoryObject->getParentId();
            $params['subCategoryId'] 	= $articleSubCat;
            $params['LDBCourseId'] 	= 1;

            $result['finalArray'] 	= $this->prepareCourseWidget($params ,$articleCountry,$this->categoryPageRequest,false);
            return $result;
        }
        return false;
    }

    function prepareUniversityWidget($params , $articleCountry ,$categoryPageRequest){
        $countryName =  array('3'=>'USA', '8'=>'Canada', '5'=>'Australia', '4'=>'UK', '7' =>'New Zealand','21'=>'Ireland');
        $allCountriesIds = array();
        $finalArray = array();
        if(is_array($articleCountry))
        {
            $allCountriesIds = $articleCountry;
        }
        $allCountriesIds = array_unique(array_merge($allCountriesIds,array_keys($countryName)));
        $finalArray['all_univ_count'] = $this->articlesAbroadWidgetsModel->getUniversityCountForCountry($allCountriesIds);
        if(is_array($articleCountry)){
            $finalArray['countryId'] = array();
            foreach ($articleCountry as $countryId){
                if(!isset($finalArray['all_univ_count'][$countryId]) || $finalArray['all_univ_count'][$countryId] == 0)
                {
                    continue;
                }
                if(array_key_exists($countryId,$countryName)){
                    unset($countryName[$countryId]);
                }else{
                    array_pop($countryName);
                }
                $finalArray['url'][] 	= $categoryPageRequest->getURLForCountryPage($countryId);
                $finalArray['countryId'][]	= $countryId;
            }
            $countries = $this->articlesAbroadWidgetsModel->getCountryNameByIds($finalArray['countryId']);
            foreach ($finalArray['countryId'] as $key => $value) {
                $finalArray['location'][] = $countries[$value];
            }
        }

        if(count($finalArray['url'])<6){
            foreach ($countryName as $countryId => $country){
                $finalArray['url'][]    = $categoryPageRequest->getURLForCountryPage($countryId);
                $finalArray['location'][]   = $country;
                $finalArray['countryId'][]  = $countryId;
            }
        }
        foreach ($finalArray['countryId'] as $key => $value) {
            $finalArray['univ_count'][] = $finalArray['all_univ_count'][$value];
        }
        unset($finalArray['all_univ_count']);
        unset($finalArray['countryId']);
        $finalArray['widgetType'] = 'university';
        return $finalArray;
    }


    function prepareCourseWidget($params ,$articleCountry,$categoryPageRequest,$isLDBCourse){
        global $certificateDiplomaLevels;
        $this->CI->load->builder('categoryList/AbroadCategoryPageBuilder');
        $countryName =  array('3'=>'USA', '8'=>'Canada', '5'=>'Australia', '4'=>'UK', '7' =>'New Zealand','21'=>'Ireland');
        $allCountriesIds = array();
        $finalArray = array();
        if(is_array($articleCountry))
        {
            $allCountriesIds = $articleCountry;
        }
        $allCountriesIds = array_unique(array_merge($allCountriesIds,array_keys($countryName)));
        $categoryPageRequest->setData($params);
        $this->_getUnivCountForCourseBrowseWidget($finalArray,$allCountriesIds,$categoryPageRequest);
        foreach($certificateDiplomaLevels as $level){
            if(strtolower($params['courseLevel']) == strtolower($level)){
                $params['courseLevel'] = 'certificate - diploma';
            }
        }
        if(is_array($articleCountry)){
            foreach ($articleCountry as $countryId){
                if(!isset($finalArray['all_college_count'][$countryId]) || $finalArray['all_college_count'][$countryId] == 0)
                {
                    continue;
                }
                $params['countryId'] = array($countryId);
                if(array_key_exists($countryId,$countryName)){
                    unset($countryName[$countryId]);
                }else{
                    array_pop($countryName);
                }
                $categoryPageRequest->setData($params);
                $seoData 	= $categoryPageRequest->getSeoInfo();
                $titlesArr          = explode('|',$seoData['title']);
                $titlesArr 		= explode('-',$titlesArr[0]);
                $finalStr = '';
                if($isLDBCourse){
                    $finalStr		=trim($titlesArr[0]);
                }else{
                    $count = count($titlesArr);
                    for ($i=0;$i<($count-1);$i++){
                        $finalStr .= ($finalStr=='')?$titlesArr[$i]:" ".$titlesArr[$i];
                    }
                    $finalStr = trim($finalStr);
                }
                $finalArray['title'][] 		= $finalStr;
                $finalArray['url'][] 		= $categoryPageRequest->getUrl();
                $finalArray['countryId'][]  = $countryId;
            }
            $countries = $this->articlesAbroadWidgetsModel->getCountryNameByIds($finalArray['countryId']);
            foreach ($finalArray['countryId'] as $key => $value) {
                $finalArray['location'][] = $countries[$value];
            }
        }
        if(count($finalArray['url'])<6){
            foreach ($countryName as $countryId => $country){
                $finalArray['countryId'][] = $countryId;
                $params['countryId'] 	= array($countryId);
                $categoryPageRequest->setData($params);
                $seoData 			= $categoryPageRequest->getSeoInfo();
                $titlesArr 			= explode('-',$seoData['title']);
                $finalStr 			= '';
                if($isLDBCourse){
                    $titlesArr 		= explode('|',$seoData['title']);
                    $titlesArr 		= explode('-',$titlesArr[0]);
                    $finalStr		=trim($titlesArr[0]);
                }else{
                    $count = count($titlesArr);
                    for ($i=0;$i<($count-1);$i++){
                        $finalStr .= ($finalStr=='')?$titlesArr[$i]:" ".$titlesArr[$i];
                    }
                    $finalStr = trim($finalStr);
                }
                $finalArray['title'][] 		= $finalStr;
                $finalArray['location'][] 		= $country;
                $finalArray['url'][] 		= $categoryPageRequest->getUrl();
            }
        }
        foreach ($finalArray['countryId'] as $key => $value) {
            $finalArray['college_count'][] = $finalArray['all_college_count'][$value];
        }
        unset($finalArray['countryId']);
        $finalArray['widgetType'] = 'course';
        return $finalArray;
    }

    private function _getUnivCountForCourseBrowseWidget(&$finalArray,$allCountriesIds,$categoryPageRequest)
    {
        $params['countryId'] 	=  $allCountriesIds;
        $categoryPageRequest->setData($params);
        $universityCountryData = $this->articlesAbroadWidgetsModel->getUniversityCountryListForCourseCountries($categoryPageRequest);
        $universityCountryData = $universityCountryData['mainResult'];
        $finalArray['all_college_count'] = array();
        foreach ($universityCountryData as &$value)
        {
            $finalArray['all_college_count'][$value['country_id']] = isset($finalArray['all_college_count'][$value['country_id']])?(++$finalArray['all_college_count'][$value['country_id']]):1;
            unset($value);
        }

    }

    public function emailGuideToUser($guideId,$userData,$trackingPageKeyId){
        $guideDetails = $this->SAContentModel->getContentDetails($guideId);
        $guideDetails = $guideDetails['data'];
        if($guideDetails['is_downloadable'] == 'no' && $guideDetails['type'] == 'examContent'){
            //check if parent has a guide
            $guideFromParent = $this->SAContentModel->getParentExamContentGuide($guideDetails['exam_type']);
            $guideDetails['download_link'] = $guideFromParent[0]['download_link'];
            if($guideDetails['download_link'] == ''){
                return -1;
            }else{
                $guideDetails['download_link'] = MEDIAHOSTURL.$guideFromParent[0]['download_link'];
            }
        }
        //_p($guideDetails);_p($userData);die;
        $guideSize = getRemoteFileSize($guideDetails['download_link'],FALSE);
        $sendGuideAsAttachment = FALSE;
        if($guideSize <= 5*1024*1024){
            $sendGuideAsAttachment = TRUE;
        }
        $guideSize = formatFileSize($guideSize);
        $alerts_client = $this->CI->load->library('alerts_client');
        if($sendGuideAsAttachment){
            $misObj = $this->CI->load->library('Ldbmis_client');
            $appId = 1;
            $type= 'guide';
            $contentURL = $guideDetails['download_link'];
            //echo 'content_url: '.$contentURL;die;
            $fileExtension = end(explode(".",$contentURL));
            $type_id = $misObj->updateAttachment($appId);
            $attachmentName = str_replace(" ",'_',$guideDetails['strip_title']);
            $attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
            $attachmentName = $attachmentName.".".$fileExtension;
            $attachmentId = $alerts_client->createAttachment("12",$type_id,$type,'Guide','',$attachmentName,$fileExtension,'false', $contentURL);
        }
        $mailContents = $this->_getEmailContent($guideDetails, $userData);
        $cookieStrArray = explode('|', $userData[0]['cookiestr']);
        //'toEmail'],$data['fromEmail'],$data['emailSubject'],$data['emailContent'
        $mailerData['toEmail'] = $cookieStrArray[0];
        $mailerData['fromEmail'] = SA_ADMIN_EMAIL;
        $mailerData['emailSubject'] = $mailContents['subject'];
        $mailerData['emailContent'] = $mailContents['body'];
        $mailerData['mailer_name'] = 'studyAbroadEmailGuide';
       if(isset($attachmentId) && $attachmentId > 0) {
           $mailerData['attachmentId'] = $attachmentId;
        }
        $mailerResponse = Modules::run('systemMailer/SystemMailer/emailSAGuideToUser',$mailerData);
       /* if(isset($attachmentId) && $attachmentId > 0){
            $insertMailQResponse    = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$cookieStrArray[0],$mailContents['subject'],$mailContents['body'],"html",'','y',array($attachmentId));
        }else{
            $insertMailQResponse    = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$cookieStrArray[0],$mailContents['subject'],$mailContents['body'],"html");
        }*/
        if($mailerResponse == "Inserted Successfully"){
            $params = array(
                'userId'    => $userData[0]['userid'],
                'contentId' => $guideId,
                'pageUrl'   => $_SERVER['HTTP_REFERER'],
                'downloadedFrom'    => 'mobile',
                'trackingPageKeyId' => $trackingPageKeyId,
                'visitorSessionid' => getVisitorSessionId()
            );
            $contentPageModel = $this->CI->load->model('contentPage/contentpagemodel');
            $contentPageModel->guideDownloadTracking($params);
            return 1;
        }else{
            return -1;
        }
    }

    private function _getEmailContent($guideDetails,$userData,$attachmentAvailable=TRUE){
        $mailContents = array();
        $mailContents['subject']    = $guideDetails['strip_title'];
        $params = array();
        $cookieStrArray = explode('|', $userData[0]['cookiestr']);
        $params['userEmailId']  = $cookieStrArray[0];
        $params['firstName']    = $userData[0]['firstname'];
        $params['guideSeoUrl']  = $guideDetails['contentURL'];
        $params['attachmentAvailable']  = $attachmentAvailable;
        $params['downloadLink'] = $guideDetails['download_link'];
        $params['guideName']    = $guideDetails['strip_title'];
        $mailContents['body']       = $this->CI->load->view('contentPage/guideMail',$params,TRUE);
        return $mailContents;
    }
    /*this function fetches details for the content page for mis tracking*/
    public function contentPageDetails($contentId)
    {
        $contentPageModel = $this->CI->load->model('contentPage/contentpagemodel');
        $data = $contentPageModel->contentPageDetails($contentId);
        return $data;
    }

    public function getContentDownloadCount($contentId){
        $count = $this->SAContentModel->totalGuideDownloded($contentId);
        if(!$count){
            return 0;
        }
        return $count;
    }

    public function checkIfRegistrationRequiredForComment($contentId){
        $commentCount = $this->SAContentModel->getCommentCount($contentId);
        if($commentCount>COMMENT_COUNT_BEFORE_REGISTRATION){
            return "true";
        }
        else{
            return "false";
        }
    }
    public function checkNGetScholarshipsMapped(&$contentDetails)
    {
        // check if this article page because only article pages have tags
        // also check if the scholarships & loans tag is present
        if($contentDetails['data']['type'] == 'article' && strpos($contentDetails['data']['tags'],'Scholarships & Loans')!== false)
        {
            // check if tagged to a country
            $countryIds = array_map(function($a){ return $a['country_id']; },$contentDetails['data']['mappedCountryResults']);
            if(count($countryIds)==1)
            {
                // set parameters for scholarship cat page request in that country
                $params = array(
                                'country'=> array($contentDetails['data']['countryId']),
                                'countryName'=>array($contentDetails['data']['countryName']),
                                'type'=>'country'
                );
                if(!is_null($params)){
                    $scholarshipCategoryPageLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
                    $tupleCount = 7;
                    $contentDetails['scholarshipCardData'] = $scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($params,$tupleCount,'ARTICLEPAGE');
                    $contentDetails['scholarshipSliderTitle'] = $contentDetails['data']['countryName'];
                }
            }else{
                // get popular scholarships globally
                $contentDetails['scholarshipCardData'] = $this->_getPopularScholarshipData($countryIds);
                $contentDetails['scholarshipSliderTitle'] = ' study abroad';
            }
        }
    }

    public function getLevelTwoNavBarLinksByContentId($contentId)
    {
        if(empty($contentId))
            return false;
        $navData = $this->SAContentModel->getLevelTwoNavBarIdByContentId($contentId);
        if(empty($navData))
            return false;
        $navId = $navData[0]['navbar_id'];
        $navLinkData = $this->SAContentModel->getLevelTwoNavBarLinksByNavBarId($navId);
        if(empty($navLinkData))
            return false;
        $allContentIds=[];
        foreach ($navLinkData as $navItemData)
        {
            $allContentIds[] = $navItemData['content_id'];
        }
        $examPageModel  = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
        $contentURLs = $examPageModel->getAllContentURLs($allContentIds);
        return array('title'=>$navData[0]['title'],'navData'=>$navLinkData,'navUrl'=>$contentURLs);
    }

    private function _getPopularScholarshipObjs($schIds = array())
    {
        if(count($schIds)== 0)
        {
            return false;
        }
        $sections = array('basic'=>array('scholarshipId','seoUrl'),
                          'amount'=>array('totalAmountPayout','amountCurrency','convertedTotalAmountPayout')
                    );
        $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $this->scholarshipBuilder        = new scholarshipBuilder();
        $this->scholarshipRepository     = $this->scholarshipBuilder->getScholarshipRepository();
        $scholarshipObjs = $this->scholarshipRepository->findMultiple($schIds,$sections);
        return $scholarshipObjs;
    }
    private function _getPopularScholarshipData($countryIds = array())
    {
        $additionalFilters = array();
        $scholarshipHomePageLib = $this->CI->load->library('scholarshipHomepage/scholarshipHomePageLib');
        if(count($countryIds)>0){
            $additionalFilters['saScholarshipCountryId'] = $countryIds; // keeping same name as field name in solr
        }
        $tupleCount = 8;
        $scholarshipData = $scholarshipHomePageLib->getPopularScholarshipFromSolr($tupleCount,$additionalFilters);
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/abroadCommonLib');
        $this->abroadCategories = $this->abroadCommonLib->getAbroadCategories();
        $scholarshipCardData = array();
        if($scholarshipData['status'] == 'success' && $scholarshipData['total'] > 0)
        {
            $scholarshipCardData['totalCount'] = $scholarshipData['total'];
            $scholarshipObjs = $this->_getPopularScholarshipObjs(array_filter(array_unique(array_map(function($a){ return $a['saScholarshipId']; },$scholarshipData['scholarships']))));
            $this->abroadCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
            foreach($scholarshipData['scholarships'] as &$schData){
                if(is_object($scholarshipObjs[$schData['saScholarshipId']])){
                    $amount = $scholarshipObjs[$schData['saScholarshipId']]->getAmount();
                    $amtStr = $this->_formatAmountString($amount);
                    $catStr = $this->_formatCategoryString($schData['saScholarshipCategoryId']);
                    $schData['seoUrl'] = $scholarshipObjs[$schData['saScholarshipId']]->getUrl();
                    $scholarshipCardData['scholarshipData'][$schData['saScholarshipId']] = 
                    $rowdata=array(
                            'name'=> $schData['saScholarshipName'],
                            'url'=> $scholarshipObjs[$schData['saScholarshipId']]->getUrl(),
                        );
                    if($schData['saScholarshipAwardsCount'] > 0){
                        $rowdata['awards'] = $schData['saScholarshipAwardsCount'];
                    }
                    if($amtStr['amtStr1']!=''){
                        $rowdata['amountStr1'] = $amtStr['amtStr1'];
                    }
                    if($amtStr['amtStr2']!=''){
                        $rowdata['amountStr2'] = $amtStr['amtStr2'];
                    }
                    if($catStr!=''){
                        $rowdata['category'] = $catStr;
                    }
                    $scholarshipCardData['scholarshipData'][$schData['saScholarshipId']] = $rowdata;
                }
            }
        }
        return $scholarshipCardData;
    }
    private function _formatCategoryString($categoryIds = array())
    {
        if(count($categoryIds)==0)
        {
            return false;
        }
        $strComponents = array();$str='';
        if($categoryIds[0]=='all')// all streams
        {
            $categoryIds = array_map(function($a){return $a['id'];},$this->abroadCategories);
        }
        foreach($categoryIds as $catId)
        {
            if(count($strComponents)>=3)
            {
                $str = " + ".(count($categoryIds)-3)." more";
                break;
            }else{
                $strComponents[] = $this->abroadCategories[$catId]['name'];
            }
        }
        return implode(', ',$strComponents).$str;
    }
    private function _formatAmountString($amountObj = null)
    {
        if(is_null($amountObj))
        {
            return false;
        }
        if($amountObj->getConvertedTotalAmountPayout()>0){
            $amtStr1 = "Rs ".moneyFormatIndia($amountObj->getConvertedTotalAmountPayout());
        }
        if($amountObj->getTotalAmountPayout() > 0 && $amountObj->getAmountCurrency() > 1){
            $currencyName = $this->abroadCommonLib->getCurrencyCodeById($amountObj->getAmountCurrency());
            $amtStr2 = "(".number_format($amountObj->getTotalAmountPayout())." ".$currencyName.")";
        } 
        return array('amtStr1' => $amtStr1, 'amtStr2' => $amtStr2);
    }
}

