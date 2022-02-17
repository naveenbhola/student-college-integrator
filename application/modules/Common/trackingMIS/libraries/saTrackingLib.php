<?php
class saTrackingLib {
    private $CI;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->CI->load->model('trackingMIS/samismodel');
        $this->trackingModel = new samismodel();
        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
    }

    function getPaidRegistration($pageName,$dateRange,$extraData,$userIds='',$colorCodes){
        //_p($dateRange);_p($extraData);die;
        $pageName = $this->getPageArray($pageName);
        $trackingIdsArray = $this->trackingModel->getTrackingIdsForSelectedPage($pageName,'registration');
        $trackingIds = array_map(function($a){
                return $a['id'];
        }, $trackingIdsArray);
        $sessionIdsArray = $this->trackingModel->getSessionIdsForRegistration($dateRange,$extraData,$trackingIds,$userIds);
        if(count($sessionIdsArray)>0){
            $sessionIds = array_map(function($a){
                return $a['visitorsessionid'];
            }, $sessionIdsArray);

            $paidRegistraton = $this->trackingModel->getPaidRegistrations($sessionIds,$dateRange);
            foreach ($paidRegistraton as $key => $value) {
                
                $paidRegistratonArray[$value['utm_campaign']] =  $value['count'];
                $total += $value['count'];
            }
            $paidRegistraton = $this->prepareDataForDonutChart($paidRegistratonArray,$colorCodes,$total);
        }else{
            $paidRegistraton = $this->prepareDataForDonutChart('',$colorCodes,0);
        }
        return $paidRegistraton;
    }

    function getCompareDataForSelectedDuration($pageName,$filterArray,$filter,$isComparision,$metric,$colorCodes)
    {
        if($filterArray['courseComparedFilter'] == 0){
            return $this->_preparedDataForFinallyCourseCompared($pageName,$filterArray,$filter,$isComparision,$metric,$colorCodes);
        }else{
            $pageName = $this->getPageArray($pageName);
            $trackingIds = $this->trackingModel->getTrackingIdsForSelectedPage($pageName,$filter);
            if(!$pageName){
                foreach ($trackingIds as $key => $value) {
                    if($value['conversionType'] &&  $value['conversionType'] == 'response'){
                        $trackingIdsForResponses[] = $value['id'];                    
                    }
                }
            }

            $trackingIdsArray = array_map(function($a){
                    return $a['id'];
            }, $trackingIds);

            $i=0;
            foreach ($trackingIds as $key => $value) {
                        $trackingArray[$value['id']] = array(
                                                            'widget'=> $value['widget'],
                                                            'siteSource'=>$value['siteSource']
                                                            );
                        if(!$pageName){
                            $trackingArray[$value['id']]['page']=$value['page'];
                        }
            }
            $ComrepDataForSelectedDuration=0;
            if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
            {
                $filteredCourseIds = $this->trackingModel->getCourseIdsBasedOnDifferentFilter($filterArray);
                 $filteredCourseIdsArray = array_map(function($a){
                      return $a['course_id'];
                }, $filteredCourseIds); 
                 //_p($filteredCourseIdsArray);die;
                if(count($filteredCourseIdsArray) > 0)
                {
                    $ComrepDataForSelectedDuration = $this->trackingModel->getCompareDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray);
                    
                }
            }
            else
            {
                if($trackingIdsArray){
                    $ComrepDataForSelectedDuration = $this->trackingModel->getCompareDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray);
                }
            }
            $i=0;
            $removedCourses =0;
            $nonLoggedin =0;
            $uniqueCourses  = array();
            $uniqueUsers = array();
        
            foreach ($ComrepDataForSelectedDuration as $key => $value) {
                $compareCount[$i]  = array(
                    'responseDate' => $value['responseDate'],
                    'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                    'reponsesCount'=>1
                );
                if($filterArray['view'] == 2){
                    $compareCount[$i]['weekNo'] = $value['week'];
                }else if($filterArray['view'] == 3){
                    $compareCount[$i]['monthNo'] = $value['month'];
                }
                $uniqueCourses[$value['courseId']] =0;
                if($value['userId'] > 0){
                    $uniqueUsers[$value['userId']] =0;
                }
                if(!$pageName){
                    $compareCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                }else{
                    $compareCount[$i]['widget']= $trackingArray[$value['tracking_keyid']]['widget'];
                }
                if($value['status'] == 'deleted')
                {
                    $removedCourses ++;
                }
                if($value['userId'] == -1)
                {
                    $nonLoggedin ++;
                }
                $i++;
            }
            $dataForDifferentCharts = $this->prepareDataForDifferentCharts($compareCount, $pageName,$filter,$filterArray,$isComparision,$metric,$colorCodes);

            $totalCompareCourses = $dataForDifferentCharts['donutChartData'][0][0][0]['value'] + $dataForDifferentCharts['donutChartData'][0][0][1]['value'];

            $loggedin = $totalCompareCourses - $nonLoggedin;
            $userWise['Loggedin'] = $loggedin;
            $userWise['Non Loggedin'] = $nonLoggedin;

            $userwise = $this->prepareDataForDonutChart($userWise,$colorCodes,$totalCompareCourses);
        
            if($pageName && !$isComparision){
                $temp = $dataForDifferentCharts['donutChartData'][1];
                $dataForDifferentCharts['donutChartData'][1] = $userwise;
                $dataForDifferentCharts['donutChartData'][2] = $temp;
            }else{
                $dataForDifferentCharts['donutChartData'][1] = $userwise;
            }
            $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;

            foreach ($uniqueUsers as $key => $value) {
                $distinctUserArray[$i++] = $key;
            }   

            $repeatUsers = $this->trackingModel->getResponsesByFirstTimeUser($distinctUserArray,$filterArray,'compare');
            //_p($repeatUsers);die;
            $distinctSessionIdForUserCount = $this->trackingModel->getDistinctSessionIdForSessionCountForComapre($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray);
            $totalUsers = count($distinctUserArray) + $distinctSessionIdForUserCount;
            $firstTimeUsers = $totalUsers - count($repeatUsers);
            
            if($pageName){
                $pageData['topTiles'] = array($totalCompareCourses,count($uniqueCourses),$removedCourses,$totalUsers,$firstTimeUsers);
            }else{
                if($trackingIdsForResponses){
                    $responses = $this->trackingModel->getResponsesDataForSelectedDuration('',$trackingIdsForResponses,$filterArray,'',$filteredCourseIdsArray);
                    $totalResponses = 0;
                    foreach ($responses as $key => $value) {
                        $totalResponses += $value['reponsesCount'];
                    }
                }else{
                    $totalResponses =0;
                }
             
                $pageData['topTiles'] = array($totalCompareCourses,count($uniqueCourses),$removedCourses,$totalResponses,$totalUsers,$firstTimeUsers);
            }
            //_p($pageData);die;
            return $pageData;
        }
    }

    private function _preparedDataForFinallyCourseCompared($pageName,$filterArray,$filter,$isComparision,$metric,$colorCodes){
        $pageName = $this->getPageArray($pageName);
        $trackingIds = $this->trackingModel->getTrackingIdsForSelectedPage($pageName,'courseCompared');
        //_p($trackingIds);die;        

        $trackingIdsArray = array_map(function($a){
                return $a['id'];
        }, $trackingIds);

        $i=0;
        foreach ($trackingIds as $key => $value) {
                    $trackingArray[$value['id']] = array(
                                                        'widget'=> $value['widget'],
                                                        'siteSource'=>$value['siteSource']
                                                        );
                    if(!$pageName){
                        $trackingArray[$value['id']]['page']=$value['page'];
                    }
        }

        $ComrepDataForSelectedDuration=0;
        if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
        {
            $filteredCourseIds = $this->trackingModel->getCourseIdsBasedOnDifferentFilter($filterArray);
             $filteredCourseIdsArray = array_map(function($a){
                  return $a['course_id'];
            }, $filteredCourseIds); 
             //_p($filteredCourseIdsArray);die;
            if(count($filteredCourseIdsArray) > 0)
            {
                $ComrepDataForSelectedDuration = $this->trackingModel->getFinallyCompareDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray);                
            }
        }else{
            if($trackingIdsArray){
                $ComrepDataForSelectedDuration = $this->trackingModel->getFinallyCompareDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray);
            }
        }
        //_p($ComrepDataForSelectedDuration);die;
        $i=0;
        //$removedCourses =0;
        $nonLoggedin =0;
        $uniqueCourses  = array();
        $uniqueUsers = array();
        $totalCompareCourses = 0;
        foreach ($ComrepDataForSelectedDuration as $key => $value) {
            $totalCompareCourses++;
            $compareCount[$i]  = array(
                'responseDate' => $value['responseDate'],
                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                'reponsesCount'=>1
            );
            if($filterArray['view'] == 2){
                $compareCount[$i]['weekNo'] = $value['week'];
            }else if($filterArray['view'] == 3){
                $compareCount[$i]['monthNo'] = $value['month'];
            }   
            if($value['userId'] > 0){
                $uniqueUsers[$value['userId']] =0;
            }
            $uniqueCourses[$value['courseId']] =0;
            if(!$pageName){
                $compareCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
            }else{
                $compareCount[$i]['widget']= $trackingArray[$value['tracking_keyid']]['widget'];
            }                
            if($value['userId'] == -1)
            {
                $nonLoggedin ++;
            }
            $i++;
        }
        //_p($nonLoggedin);_p($compareCount);_p($uniqueCourses);die;
        $dataForDifferentCharts = $this->prepareDataForDifferentCharts($compareCount, $pageName,$filter,$filterArray,$isComparision,$metric,$colorCodes);
        //_p($dataForDifferentCharts);die;

        $loggedin = $totalCompareCourses - $nonLoggedin;
        $userWise['Loggedin'] = $loggedin;
        $userWise['Non Loggedin'] = $nonLoggedin;

        $userwise = $this->prepareDataForDonutChart($userWise,$colorCodes,$totalCompareCourses);
        //_p($userwise);die;
        
        if($pageName && !$isComparision){
            $temp = $dataForDifferentCharts['donutChartData'][1];
            $dataForDifferentCharts['donutChartData'][1] = $userwise;
            $dataForDifferentCharts['donutChartData'][2] = $temp;
        }else{
            $dataForDifferentCharts['donutChartData'][1] = $userwise;
        }
        $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;

        foreach ($uniqueUsers as $key => $value) {
            $distinctUserArray[$i++] = $key;
        }
        $distinctSessionIdForUserCount = $this->trackingModel->getDistinctSessionIdForSessionCount($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray);

        $totalUsers = count($distinctUserArray) + $distinctSessionIdForUserCount;

        $distinctSessionId = $this->trackingModel->getAvgCompareCoursesINSession($pageName,$trackingIdsArray,$filterArray,$filteredCourseIdsArray);
        $avgCompareCoursesINSession = number_format(($totalCompareCourses/$distinctSessionId),2,'.','');       
        
        $pageData['topTiles'] = array($totalCompareCourses,count($uniqueCourses),$avgCompareCoursesINSession,$totalUsers);
        //_p($pageData);die;
        return $pageData;
    }

    function getCPEnquiryDataForSelectedDuration($filterArray,$filter,$isComparision,$metric,$colorCodes)
    {
        $trackingIdsForSelectedPage = $this->trackingModel->getTrackingIdsForSelectedPage('',$filter);
        
        $trackingIdsArray = array_map(function($a){
                return $a['id'];
        }, $trackingIdsForSelectedPage);
        $i=0;
        foreach ($trackingIdsForSelectedPage as $key => $value) {
                    $trackingArray[$value['id']] = array(
                                                        'widget'=> $value['widget'],
                                                        'siteSource'=>$value['siteSource'],
                                                        'page' => $value['page']
                                                        );
        }
        
        $CPEnquiryDataForSelectedDuration = $this->trackingModel->CPEnquiryDataForSelectedDuration($trackingIdsArray,$filterArray);
            
        $i=0;
        if($filterArray['view'] == 1){
            foreach ($CPEnquiryDataForSelectedDuration as $key => $value) {
                if($value['source'] == 'vendorInfo'){
                    $enquirySource = 'studentCall';
                }else{
                    $enquirySource = $value['source'];
                }

                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>1,
                                                'page' => $trackingArray[$value['tracking_keyid']]['page'],
                                                'source' => $enquirySource,
                                                'consultantId' => $value['consultantId'],
                                                'tempLmsId' => $value['tempLmsId']
                                             );
                    $i++;
            }
        }else if($filterArray['view'] == 2){
            foreach ($CPEnquiryDataForSelectedDuration as $key => $value) {
                if($value['source'] == 'vendorInfo'){
                    $enquirySource = 'studentCall';
                }else{
                    $enquirySource = $value['source'];
                }
                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>1,
                                                'weekNo' => $value['week'],
                                                'page' => $trackingArray[$value['tracking_keyid']]['page'],
                                                'source' => $enquirySource,
                                                'consultantId' => $value['consultantId'],
                                                'tempLmsId' => $value['tempLmsId']
                                             );
                $i++;
            }
        }else if($filterArray['view'] == 3){
            foreach ($CPEnquiryDataForSelectedDuration as $key => $value) {
                if($value['source'] == 'vendorInfo'){
                    $enquirySource = 'studentCall';
                }else{
                    $enquirySource = $value['source'];
                }
                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>1,
                                                'monthNo' => $value['month'],
                                                'page' => $trackingArray[$value['tracking_keyid']]['page'],
                                                'source' => $enquirySource,
                                                'consultantId' => $value['consultantId'],
                                                'tempLmsId' => $value['tempLmsId']
                                             );    
                $i++;
            }
        }
        
        $dataForDifferentCharts = $this->prepareDataForDifferentCharts($responseCount, '',$filter,$filterArray,$isComparision,$metric,$colorCodes);
        $totalCPEnquiry = $dataForDifferentCharts['donutChartData'][0][0][0]['value'] + $dataForDifferentCharts['donutChartData'][0][0][1]['value'];

        $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;
        $i=0;
        foreach ($responseCount as $key => $value) {
            if($value['tempLmsId']){
                $tempLmsIds[$i++] =$value['tempLmsId'];                
            }
        }
        $pageData['topTiles'] =$this->getTopTilesDataForCPEnquiry($filterArray,$totalCPEnquiry,$tempLmsIds);    
        //_p($pageData);die;
        return $pageData;
    }

    function getCommentReplyDataForSelectedDuration($pageName,$filterArray,$filter,$isComparision,$metric,$colorCodes)
    {
        $trackingIdsForSelectedPage = $this->trackingModel->getTrackingIdsForSelectedPage($pageName,$filter);

        $trackingIdsArray = array_map(function($a){
                return $a['id'];
        }, $trackingIdsForSelectedPage);
        $i=0;
        foreach ($trackingIdsForSelectedPage as $key => $value) {
                    $trackingArray[$value['id']] = array(
                                                        'conversionType'=> $value['conversionType'],
                                                        'siteSource'=>$value['siteSource']
                                                        );
                    if(!$pageName){
                        $trackingArray[$value['id']]['page']=$value['page'];
                    }
        }
        $commentReplyDataForSelectedDuration=0;
        if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
        {
            $filteredCourseIds = $this->trackingModel->getCourseIdsForContent($filterArray);
             $filteredCourseIdsArray = array_map(function($a){
                return $a['content_id'];
            }, $filteredCourseIds); 

            if(count($filteredCourseIdsArray) > 0)
            {
                $commentReplyDataForSelectedDuration = $this->trackingModel->getCommentReplyDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray,$filter,$filteredCourseIdsArray);                
            }
        }
        else
        {
            $commentReplyDataForSelectedDuration = $this->trackingModel->getCommentReplyDataForSelectedDuration($pageName,$trackingIdsArray,$filterArray,$filter);            
        }
        $i=0;
        $comments =0;
        $replies =0;
        if($filterArray['view'] == 1){
            foreach ($commentReplyDataForSelectedDuration as $key => $value) {
                $commentReplyCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'conversionType'=>$trackingArray[$value['tracking_keyid']]['conversionType'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount']
                                             );
                    if(!$pageName){
                        $commentReplyCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                    }else{
                        $commentReplyCount[$i]['contentId']  =$value['contentId'];
                    }
                    if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'commentPost')
                    {
                        $comments += $value['reponsesCount'];
                    }else if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'replyPost')
                    {
                        $replies += $value['reponsesCount'];
                    }
                    $i++;

            }
        }else if($filterArray['view'] == 2){
            foreach ($commentReplyDataForSelectedDuration as $key => $value) {
                $commentReplyCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'conversionType'=>$trackingArray[$value['tracking_keyid']]['conversionType'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'weekNo' => $value['week']
                                             );
                if(!$pageName){
                    $commentReplyCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                }else{
                    $commentReplyCount[$i]['contentId']  =$value['contentId'];
                }
                if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'commentPost')
                {
                    $comments += $value['reponsesCount'];
                }else if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'replyPost')
                {
                    $replies += $value['reponsesCount'];
                }
                $i++;
            }
        }else if($filterArray['view'] == 3){
            foreach ($commentReplyDataForSelectedDuration as $key => $value) {
                $commentReplyCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'conversionType'=>$trackingArray[$value['tracking_keyid']]['conversionType'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'monthNo' => $value['month']
                                             );
                if(!$pageName){
                    $commentReplyCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                }else{
                   $commentReplyCount[$i]['contentId']  =$value['contentId'];
                }

                if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'commentPost')
                {
                    $comments += $value['reponsesCount'];
                }else if($trackingArray[$value['tracking_keyid']]['conversionType']  == 'replyPost')
                {
                    $replies += $value['reponsesCount'];
                }    
                $i++;
            }
        }
        
        $dataForDifferentCharts = $this->prepareDataForDifferentCharts($commentReplyCount, $pageName,$filter,$filterArray,$isComparision,$metric,$colorCodes);
        
        $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;
        $avg = number_format(($replies/$comments),2,'.','');
        $pageData['topTiles'] = array($comments,$replies,$avg);
        //_p($pageData);die;
        return $pageData;
    }

    function getDownloadsDataForSelectedDuration($pageName,$filterArray,$filter,$isComparision,$metric,$colorCodes)
    {
        $trackingIdsForSelectedPage = $this->trackingModel->getTrackingIdsForSelectedPage($pageName,$filter);
        $i = 0;
        $j = 0;
        $trackingIdsArray = array_map(function($a){
            return $a['id'];
        }, $trackingIdsForSelectedPage);

        if($pageName == '')
        {
            foreach ($trackingIdsForSelectedPage as $key => $value) {
                if($value['page'] == 'applyContentPage')
                {
                    $trackingIdsArrayForApplyContent[$i++] = $value['id'];
                }else{
                    $trackingIdsArrayForGuide[$j++] = $value['id'];
                }
            }
        }else if($pageName == 'applyContentPage')
        {
            $trackingIdsArrayForApplyContent = array_map(function($a){
                    return $a['id'];
            }, $trackingIdsForSelectedPage);
        }else{
            $trackingIdsArrayForGuide = array_map(function($a){
                    return $a['id'];
            }, $trackingIdsForSelectedPage);
        }
        
        $i=0;
        foreach ($trackingIdsForSelectedPage as $key => $value) {
                    $trackingArray[$value['id']] = array(
                                                        'widget'=> $value['widget'],
                                                        'siteSource'=>$value['siteSource']
                                                        );
                    if(!$pageName){
                        $trackingArray[$value['id']]['page']=$value['page'];
                    }
        }
        if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
        {
            $filteredCourseIds = $this->trackingModel->getCourseIdsForContent($filterArray);
             $filteredCourseIdsArray = array_map(function($a){
                return $a['content_id'];
            }, $filteredCourseIds); 

            if(count($filteredCourseIdsArray) > 0)
            {
                if($pageName == ''){
                    $downloadsDataForApplyContent = $this->trackingModel->getDownloadsDataForApplyContent($trackingIdsArrayForApplyContent,$filterArray,$filteredCourseIdsArray);
                    $downloadsDataForGuide = $this->trackingModel->getDownloadsDataForGuide($trackingIdsArrayForGuide,$filterArray,$filteredCourseIdsArray);

                    $downloadsDataForSelectedDuration = array_merge($downloadsDataForApplyContent,$downloadsDataForGuide);
                }else if($pageName == 'applyContentPage'){
                    $downloadsDataForSelectedDuration = $this->trackingModel->getDownloadsDataForApplyContent($trackingIdsArrayForApplyContent,$filterArray,$filteredCourseIdsArray);                
                }else{
                    $downloadsDataForSelectedDuration = $this->trackingModel->getDownloadsDataForGuide($trackingIdsArrayForGuide,$filterArray,$filteredCourseIdsArray);
                } 
            }
        }
        else
        {
            if($pageName == ''){
                $downloadsDataForApplyContent = $this->trackingModel->getDownloadsDataForApplyContent($trackingIdsArrayForApplyContent,$filterArray);

                $downloadsDataForGuide = $this->trackingModel->getDownloadsDataForGuide($trackingIdsArrayForGuide,$filterArray);
                
                $downloadsDataForSelectedDuration = array_merge($downloadsDataForApplyContent,$downloadsDataForGuide);
                usort($downloadsDataForSelectedDuration, function($a,$b){
                    return(strtotime($a['responseDate']) <strtotime($b['responseDate'])?-1:1);
                });
            }else if($pageName == 'applyContentPage'){
                $downloadsDataForSelectedDuration = $this->trackingModel->getDownloadsDataForApplyContent($trackingIdsArrayForApplyContent,$filterArray);                
            }else{
                $downloadsDataForSelectedDuration = $this->trackingModel->getDownloadsDataForGuide($trackingIdsArrayForGuide,$filterArray);
            }
        }
        $i=0;
        if($filterArray['view'] == 1){
            foreach ($downloadsDataForSelectedDuration as $key => $value) {
                $downloadsCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount']
                                             );
                    if(!$pageName){
                        $downloadsCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                    }
                    $i++;

            }
        }else if($filterArray['view'] == 2){
            foreach ($downloadsDataForSelectedDuration as $key => $value) {
                $downloadsCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'weekNo' => $value['week']
                                             );
                if(!$pageName){
                        $downloadsCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                    }
                $i++;
            }
        }else if($filterArray['view'] == 3){
            foreach ($downloadsDataForSelectedDuration as $key => $value) {
                $downloadsCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'monthNo' => $value['month']
                                             );
                if(!$pageName){
                        $downloadsCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                    }    
                $i++;
            }
        }
        $dataForDifferentCharts = $this->prepareDataForDifferentCharts($downloadsCount, $pageName,$filter,$filterArray,$isComparision,$metric,$colorCodes);

        $totalResponses = $dataForDifferentCharts['donutChartData'][0][0][0]['value'] + $dataForDifferentCharts['donutChartData'][0][0][1]['value'];
        $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;
    
        $pageData['topTiles'] =$this->getTopTilesDataForDownloads($pageName,$filterArray,$filter,$totalResponses,$trackingIdsArrayForApplyContent,$trackingIdsArrayForGuide,$filteredCourseIdsArray,$downloadsCount,$trackingIdsArray);    
        //_p($pageData);die;
        return $pageData;
    }

    function getTopTilesDataForDownloads($pageName,$filterArray,$filter,$totalDownloads,$trackingIdsArrayForApplyContent,$trackingIdsArrayForGuide,$filteredCourseIdsArray,$downloadsCount,$trackingIdsArray)
    {

        if($totalDownloads !=0){
            if($pageName == ''){
                $distinctUserCountForApplyContent = $this->trackingModel->getDistinctUserCountForSelectedDuration($trackingIdsArrayForApplyContent,$filterArray,$filter,$filteredCourseIdsArray,'applyContent');
                $distinctUserArrayForApplyContent = array_map(function($a){
                        return $a['userId'];
                    }, $distinctUserCountForApplyContent);
                $distinctUserCountForGuide = $this->trackingModel->getDistinctUserCountForSelectedDuration($trackingIdsArrayForGuide,$filterArray,$filter,$filteredCourseIdsArray,'guide');

                $distinctUserArrayForGuide = array_map(function($a){
                        return $a['userId'];
                    }, $distinctUserCountForGuide);

            }else if($pageName == 'applyContentPage'){
                $distinctUserCountForApplyContent = $this->trackingModel->getDistinctUserCountForSelectedDuration($trackingIdsArrayForApplyContent,$filterArray,$filter,$filteredCourseIdsArray,'applyContent');
                $distinctUserArrayForApplyContent = array_map(function($a){
                        return $a['userId'];
                    }, $distinctUserCountForApplyContent);

            }else{
                $distinctUserCountForGuide = $this->trackingModel->getDistinctUserCountForSelectedDuration($trackingIdsArrayForGuide,$filterArray,$filter,$filteredCourseIdsArray,'guide');
                $distinctUserArrayForGuide = array_map(function($a){
                        return $a['userId'];
                    }, $distinctUserCountForGuide);
            }

            if($distinctUserArrayForApplyContent && $distinctUserArrayForGuide){
                $totalUsersArray= array_unique(array_merge($distinctUserArrayForApplyContent,$distinctUserArrayForGuide));
                $totalUsers = count($totalUsersArray);
            }else if($distinctUserArrayForApplyContent){
                $totalUsersArray= $distinctUserArrayForApplyContent;
                $totalUsers = count($totalUsersArray);
            }else if($distinctUserArrayForGuide){
                $totalUsersArray= $distinctUserArrayForGuide;
                $totalUsers = count($totalUsersArray);
            }else{
                $totalUsers = 0;
            }

            if($distinctUserArrayForApplyContent){
                $downloadsByFirstTimeUsersForApplyContent = $this->trackingModel->getResponsesByFirstTimeUser($totalUsersArray,$filterArray,'applyContent');
                $distinctidsForApplyContent = array_map(function($a){
                    return $a['userId'];
                }, $downloadsByFirstTimeUsersForApplyContent);
                //$distinctUseridsForApplyContent=array_diff($totalUsersArray,$distinctidsForApplyContent);
            }
            if($distinctUserArrayForGuide){
                $downloadsByFirstTimeUsersForGuide = $this->trackingModel->getResponsesByFirstTimeUser($totalUsersArray,$filterArray,'guide');
                $distinctidsForGuide = array_map(function($a){
                        return $a['userId'];
                    }, $downloadsByFirstTimeUsersForGuide);
                //$distinctUseridsForGuide=array_diff($totalUsersArray,$distinctidsForGuide);
            }

            if($distinctidsForApplyContent && $distinctidsForGuide){
                $repeatUsersArray = array_unique(array_merge($distinctidsForApplyContent,$distinctidsForGuide));
            }else if($distinctidsForApplyContent){
                $repeatUsersArray = $distinctidsForApplyContent;
            }else if($distinctidsForGuide){
                $repeatUsersArray = $distinctidsForGuide;
            }else{
                $repeatUsersArray = 0;
            }
            if($repeatUsersArray ==0){
                $downloadsByFirstTimeUsers = count($totalUsersArray);
            }else{
                $downloadsByFirstTimeUsers = count(array_diff($totalUsersArray,$repeatUsersArray));    
            }
            
        }else{
            $totalUsers =0;
            $downloadsByFirstTimeUsers =0;
        }
    
        // get registration by downloads
        if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0'){
            $registrationsByDownloads =0;
        }else{
            $registrationsByDownloads = $this->trackingModel->getRegistrationsByDownloads($trackingIdsArray,$filterArray);
            $registrationsByDownloads =$registrationsByDownloads[0]['count'];    
        }
        $topTiles = array($totalDownloads,$registrationsByDownloads,$totalUsers,$downloadsByFirstTimeUsers);
        
        //_p($topTiles);die;
        return $topTiles;
    }

    function getResponsesDataForSelectedDuration($pageName,$filterArray,$responseType,$isComparision,$metric,$colorCodes)
    {
        $topTiles =array();
        $productIdsArray =GOLD_SL_LISTINGS_BASE_PRODUCT_ID;

        if($pageName){
            $pageArray = $this->getPageArray($pageName,$filterArray);    
        }
        if(!$pageName){
            $filter = 'paid';
            if($responseType=='rmc'){
                $filter = $responseType;
            }
            $trackingIdsForSelectedPage = $this->trackingModel->getTrackingIdsForSelectedPage($pageArray,$filter);
        }else{
            $trackingIdsForSelectedPage = $this->trackingModel->getTrackingIdsForSelectedPage($pageArray,$responseType);
        }
        
        
        $trackingIdsArray = array_map(function($a){
            return $a['id'];
        }, $trackingIdsForSelectedPage);
    
        $i=0;
        foreach ($trackingIdsForSelectedPage as $key => $value) {
            $trackingArray[$value['id']] = array(
                                                'type' => $value['type'],
                                                'widget'=> $value['widget'],
                                                'siteSource'=>$value['siteSource']
                                                );
            if(!$pageName){
                $trackingArray[$value['id']]['page']=$value['page'];
            }
        }

        $filteredCourseIdsArray=0;
        if($filterArray['category'] != 0 ||  $filterArray['country'] != 0 || $filterArray['courseLevel'] != '0')
        {
            $filteredCourseIds = $this->trackingModel->getCourseIdsBasedOnDifferentFilter($filterArray,$responseType,$productIdsArray);
             $filteredCourseIdsArray = array_map(function($a){
                return $a['course_id'];
            }, $filteredCourseIds); 

            if(count($filteredCourseIdsArray) > 0)
            {
                if($trackingIdsArray){
                    $responsesDataForSelectedDuration = $this->trackingModel->getResponsesDataForSelectedDuration($pageArray,$trackingIdsArray,$filterArray,$responseType,$filteredCourseIdsArray);  
                }
            }else{
                $responsesDataForSelectedDuration =0;
            }
        }
        else
        {
            if($trackingIdsArray){
                $responsesDataForSelectedDuration = $this->trackingModel->getResponsesDataForSelectedDuration($pageArray,$trackingIdsArray,$filterArray,$responseType,$filteredCourseIdsArray);
            }
        }
        $i=0;
        if($filterArray['view'] == 1){
            foreach ($responsesDataForSelectedDuration as $key => $value) {
                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'type' =>$trackingArray[$value['tracking_keyid']]['type'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount']
                                             );
                if(!$pageName){
                    $responseCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                    if($responseType != 'rmc'){
                        $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];      
                    }
                    if($value['listing_subscription_type'] == 'paid'){
                        $responseTypeData['paid'] += $value['reponsesCount'];
                    }else{
                        $responseTypeData['free'] += $value['reponsesCount'];
                    }
                }
                if($responseType == 'rmc'){
                    $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];
                }
                $i++;
            }
        }else if($filterArray['view'] == 2){
            foreach ($responsesDataForSelectedDuration as $key => $value) {
                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'type' =>$trackingArray[$value['tracking_keyid']]['type'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'weekNo' => $value['week']
                                             );
                if(!$pageName){
                        $responseCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                        if($responseType != 'rmc'){
                            $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];      
                        }
                        if($value['listing_subscription_type'] == 'paid'){
                            $responseTypeData['paid'] += $value['reponsesCount'];
                        }else{
                            $responseTypeData['free'] += $value['reponsesCount'];
                        }
                }
                if($responseType == 'rmc'){
                    $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];
                }

                $i++;
            }
        }else if($filterArray['view'] == 3){
            foreach ($responsesDataForSelectedDuration as $key => $value) {
                $responseCount[$i]  = array(
                                                'responseDate' => $value['responseDate'],
                                                'type' =>$trackingArray[$value['tracking_keyid']]['type'],
                                                'widget'=>$trackingArray[$value['tracking_keyid']]['widget'],
                                                'siteSource'=>$trackingArray[$value['tracking_keyid']]['siteSource'],
                                                'reponsesCount'=>$value['reponsesCount'],
                                                'monthNo' => $value['month']
                                             );
                    if(!$pageName){
                        $responseCount[$i]['page']  =$trackingArray[$value['tracking_keyid']]['page'];
                        if($responseType != 'rmc'){
                            $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];      
                        }
                        if($value['listing_subscription_type'] == 'paid'){
                            $responseTypeData['paid'] += $value['reponsesCount'];
                        }else{
                            $responseTypeData['free'] += $value['reponsesCount'];
                        }
                    }
                    if($responseType == 'rmc'){
                        $responseCount[$i]['listing_subscription_type'] = $value['listing_subscription_type'];
                    }    
                $i++;
            }
        }
        $dataForDifferentCharts = $this->prepareDataForDifferentCharts($responseCount, $pageArray,$responseType,$filterArray,$isComparision,$metric,$colorCodes);
        //_p($dataForDifferentCharts);die;
        $totalResponses = $dataForDifferentCharts['donutChartData'][0][0][0]['value'] + $dataForDifferentCharts['donutChartData'][0][0][1]['value'];

        //-------------------------------for traffic source and utm source--------------
            if($responseType == 'rmc'){
                $freeRMC =0;
                $paidRMC =0;
                foreach ($responseCount as $key => $value) {

                    if($value['listing_subscription_type'] =='free'){
                        $rmcType['Free RMC'] += $value['reponsesCount'];
                    }else{
                        $rmcType['Paid RMC'] += $value['reponsesCount'];
                    }
                }
                //_p($rmcType);_p($rmcType['Free RMC']+$rmcType['Paid RMC']);die;
                $donutChartForRMCType = $this->prepareDataForDonutChart($rmcType,$colorCodes,($rmcType['Free RMC']+$rmcType['Paid RMC']));
                //_p($donutChartForRMCType);die;
                if($isComparision){
                    $dataForDifferentCharts['donutChartData'][1] = '';
                    $dataForDifferentCharts['donutChartData'][2] = $donutChartForRMCType; 
                }else{
                    if($pageName){
                        $dataForDifferentCharts['donutChartData'][1] = '';
                        $dataForDifferentCharts['donutChartData'][2] = $donutChartForRMCType; 
                    }else{
                        $dataForDifferentCharts['donutChartData'][2] = $donutChartForRMCType; 
                        $dataForDifferentCharts['donutChartData'][3] = $dataForDifferentCharts['donutChartData'][1];
                        $dataForDifferentCharts['donutChartData'][1] = '';

                    }      
                }
            }else{
                if($isComparision){
                    $temp = $dataForDifferentCharts['donutChartData'][1];
                    $dataForDifferentCharts['donutChartData'][1] = '';
                    $dataForDifferentCharts['donutChartData'][2] = $temp;
                    $responseTypeWise = $this->prepareDataForDonutChart($responseTypeData,$colorCodes,($responseTypeData['paid']+$responseTypeData['free']));
                    if(!$pageName){
                        $dataForDifferentCharts['donutChartData'][3] = $responseTypeWise;
                    }
                }else{
                    $responseTypeWise = $this->prepareDataForDonutChart($responseTypeData,$colorCodes,($responseTypeData['paid']+$responseTypeData['free']));
                    if(!$pageName){
                        $dataForDifferentCharts['donutChartData'][3] = $responseTypeWise;
                        $dataForDifferentCharts['donutChartData'][4] = $dataForDifferentCharts['donutChartData'][2];
                    }
                    $dataForDifferentCharts['donutChartData'][2] = $dataForDifferentCharts['donutChartData'][1];
                    $dataForDifferentCharts['donutChartData'][1] = '';
                }
            }
        
        //_p($dataForDifferentCharts['donutChartData']);die;
        //arsort($dataForDifferentCharts['donutChartData']);
        //_p($dataForDifferentCharts['donutChartData']);die;
        //_p($getDataFormSessionIds);die;
        //-------------------------------for traffic source and utm source end here------------------------------------------------
                
        $pageData['dataForDifferentCharts'] = $dataForDifferentCharts;
        //_p($pageData['dataForDifferentCharts']);die;
        $pageData['topTiles'] =$this->getTopTilesDataForResponses($pageName,$filterArray,$responseType,$productIdsArray,$totalResponses,$trackingIdsArray,$filteredCourseIdsArray,$responseCount);    
        return $pageData;
    }

    function prepareDataForTrafficSourceForAjaxCall($filter){
        //_p($filter);die;
        $flagValueForFilter =0;
        $filteredCourseIdsArray=0;
        if($filter['filters']['category'] != 0 ||  $filter['filters']['country'] != 0 || $filter['filters']['courseLevel'] != '0')
        {
            $filteredCourseIds = $this->trackingModel->getCourseIdsBasedOnDifferentFilter($filter['filters']);
            $filteredCourseIdsArray = array_map(function($a){
                return $a['course_id'];
            }, $filteredCourseIds); 
            $flagValueForFilter =1;
        }

        if($filter['dateRange']['startDateToCompare']){
                    if($flagValueForFilter!=1){
                            $trackingIdsForSessions = $this->trackingModel->getTrackingIds($filter['page'],$filter['filterAjax']);
                            
                            // For initial Date
                            $getSessionIds = $this->trackingModel->getSessionIds($trackingIdsForSessions,$filter['dateRange'],'responses',$filter['responseTypeFilter'],$filteredCourseIdsArray);
                            //_p($getSessionIds);die;
                            $sessionId = array();
                            $i = 0;
                            $sessionResultArray = array();
                            foreach ($getSessionIds as $key => $value) {
                                $sessionId[] = $value['visitorsessionid'];
                                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
                            }
                            
                            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                            //_p($sourceResult);die;
                            $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                            foreach ($sourceData as $key => $value) {
                                $sourceDataCount += $value;
                            }
                            $resultData['source'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);

                            //For Comparision

                            $filter['dateRange']['startDate'] = $filter['dateRange']['startDateToCompare'];
                            $filter['dateRange']['endDate'] = $filter['dateRange']['endDateToCompare'];
                            $getSessionIds = $this->trackingModel->getSessionIds($trackingIdsForSessions,$filter['dateRange'],'responses',$filter['responseTypeFilter'],$filteredCourseIdsArray);
                            //_p($getSessionIds);die;
                            $sessionId = array();
                            $i = 0;
                            $sessionResultArray = array();
                            foreach ($getSessionIds as $key => $value) {
                                $sessionId[] = $value['visitorsessionid'];
                                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
                            }
                            
                            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                            //_p($sourceResult);die;
                            $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                            $sourceDataCount =0;
                            foreach ($sourceData as $key => $value) {
                                $sourceDataCount += $value;
                            }
                            $resultData['sourceToCompare'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);
                            return $resultData;
                    }else{
                        if(count($filteredCourseIdsArray)>0){
                            $trackingIdsForSessions = $this->trackingModel->getTrackingIds($filter['page'],$filter['filterAjax']);
                            
                            // For initial Date
                            $getSessionIds = $this->trackingModel->getSessionIds($trackingIdsForSessions,$filter['dateRange'],'responses',$filter['responseTypeFilter'],$filteredCourseIdsArray);
                            //_p($getSessionIds);die;
                            $sessionId = array();
                            $i = 0;
                            $sessionResultArray = array();
                            foreach ($getSessionIds as $key => $value) {
                                $sessionId[] = $value['visitorsessionid'];
                                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
                            }
                            
                            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                            //_p($sourceResult);die;
                            $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                            foreach ($sourceData as $key => $value) {
                                $sourceDataCount += $value;
                            }
                            $resultData['source'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);

                            //For Comparision

                            $filter['dateRange']['startDate'] = $filter['dateRange']['startDateToCompare'];
                            $filter['dateRange']['endDate'] = $filter['dateRange']['endDateToCompare'];
                            $getSessionIds = $this->trackingModel->getSessionIds($trackingIdsForSessions,$filter['dateRange'],'responses',$filter['responseTypeFilter'],$filteredCourseIdsArray);
                            //_p($getSessionIds);die;
                            $sessionId = array();
                            $i = 0;
                            $sessionResultArray = array();
                            foreach ($getSessionIds as $key => $value) {
                                $sessionId[] = $value['visitorsessionid'];
                                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
                            }
                            
                            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                            //_p($sourceResult);die;
                            $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                            $sourceDataCount =0;
                            foreach ($sourceData as $key => $value) {
                                $sourceDataCount += $value;
                            }
                            $resultData['sourceToCompare'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);
                            return $resultData;
                        }else{
                            $resultData['source']=0;
                            $resultData['sourceToCompare'] =0;    
                        }
                    }
        }else{
            $result = $this->getSessionBasedData($filter,$filteredCourseIdsArray);
            //_p($result);die;
            //$result = array($result['barGraph']['utmSource'],$result['barGraph']['utmCampaign'],$result['barGraph']['utmMedium']);
            return $result;            
        }
    }

    function prepareDataForTrafficSourceForAjaxCallForRegistration($filter){
        //_p($filter);die;
        $this->CI->load->model('trackingMIS/cdmismodel');
        $this->cdMISModel = new cdmismodel();
        //$this->saRegistrationLib = $this->load->library('trackingMIS/cdMISlib');
        $saFilterArray = array(
                                'country' => $filter['filters']['country'],
                                'categoryId' => $filter['filters']['categoryId'],
                                'courseLevel' => $filter['filters']['courseLevel'],
                                'pageName' => $filter['page'],
                                'pageType' => $filter['pageType'],
                                );

        // get sessionId and count
        if($filter['dateRange']['startDateToCompare']){
            $sessionIds = $this->cdMISModel->getStudyAbroadRegistrationSessionData($filter['dateRange'],$saFilterArray);
            //_p($sessionIds);die;
            $sessionId = array();
            $sessionResultArray = array();
            foreach ($sessionIds as $key => $value) {
                $sessionId[] = $value['visitorsessionid'];
                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
            }
            if(count($sessionId)){
                $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
        
                $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                $sourceDataCount =0;
                foreach ($sourceData as $key => $value) {
                    $sourceDataCount += $value;
                }
            }else{
                $sourceData =0;   
                $sourceDataCount =0;
            }
            $resultData['source'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);
            //-================
            //For Comparision

            $filter['dateRange']['startDate'] = $filter['dateRange']['startDateToCompare'];
            $filter['dateRange']['endDate'] = $filter['dateRange']['endDateToCompare'];
            $sessionIds = $this->cdMISModel->getStudyAbroadRegistrationSessionData($filter['dateRange'],$saFilterArray);
            
            $sessionId = array();
            $sessionResultArray = array();
            foreach ($sessionIds as $key => $value) {
                $sessionId[] = $value['visitorsessionid'];
                $sessionResultArray[$value['visitorsessionid']] = $value['count'];
            }
            if(count($sessionId)){
                $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                //_p($sourceResult);die;
                $sourceData = $this->getDataForSourceFilter($sourceResult,$sessionResultArray,0);
                $sourceDataCount =0;
                foreach ($sourceData as $key => $value) {
                    $sourceDataCount += $value;
                }
            }else{
                $sourceData =0;   
                $sourceDataCount =0;
            }
            $resultData['sourceToCompare'] = $this->prepareDataForDonutChart($sourceData,$filter['colorCodes'],$sourceDataCount);
            return $resultData;
            //=-=-=-=================        
        }else{
            $registrationSessionIds = $this->cdMISModel->getStudyAbroadRegistrationSessionData($filter['dateRange'],$saFilterArray);
            $result = $this->getSessionBasedDataForRegistration($filter,$registrationSessionIds);    
        }
        
        //$result = array($result['barGraph']['utmSource'],$result['barGraph']['utmCampaign'],$result['barGraph']['utmMedium']);
        //_p($result);die;
        return $result;
    }

    function getSessionBasedData($filter,$filteredCourseIdsArray){
        //$pageArray,$responseType,$filter,$filteredCourseIdsArray='',$filterArray,$tableFilter='responses',$sourceFlag=0,$defaultView='',$count='',$colorCodes
        //_p($filter);die;
        //_p($pageArray);_p($responseType);_p($filter);_p($filteredCourseIds);_p($filterArray);_p($tableFilter);_p($sourceFlag);_p($defaultView);_p($count);die;
        $trackingIdsForSessions = $this->trackingModel->getTrackingIds($filter['page'],$filter['filterAjax']);
        //_p($trackingIdsForSessions);die;
        $getSessionIds = $this->trackingModel->getSessionIds($trackingIdsForSessions,$filter['dateRange'],'responses',$filter['responseTypeFilter'],$filteredCourseIdsArray);
        //_p($getSessionIds);die;
        $result = $this->_getDataFormSessionIds($getSessionIds,$filter['count'],$filter['defaultView']);
        //--------------------------------------------------------------------------------
        if(!($filter['sourceFlag']==1)){
            $trafficSourceCount = 0;
            foreach ($result['source'] as $key => $value) {
                $trafficSourceCount +=$value;
                if($key == 'Other'){
                    continue;
                }
                $trafficSourceArray[$i++] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.
                            '<input id="hidden_'.$key.'" type="hidden" value="'.$value.'" >'.
                        '</li>';
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $result['defaultView'];
            $countValue = $result['source'][$result['defaultView']];

            $barGraph['donutChartDataForTrafficSource'] = $this->prepareDataForDonutChart($result['source'],$filter['colorCodes'],$trafficSourceCount);
        }else{
            $countValue = $filter['count'];
        }

        $barGraph['utmSource'] = $this->prepareDataForTrafficSourceForResponse($result['utmSource'],$countValue,0);
        $barGraph['utmCampaign'] = $this->prepareDataForTrafficSourceForResponse($result['utmCampaign'],$countValue,1);
        $barGraph['utmMedium'] = $this->prepareDataForTrafficSourceForResponse($result['utmMedium'],$countValue,0);
        //_p($barGraph);die;
        $inputArray = array(
                            'utmSource' =>$barGraph['utmSource'],
                            'utmCampaign' => $barGraph['utmCampaign'],
                            'utmMedium' => $barGraph['utmMedium']
                            );
        $barGraphData = $this->prepareTrafficSourceBarGraph($inputArray);
        $data['barGraph'] = $barGraph;
        $data['barGraph']['barGraphData'] = $barGraphData;
        $data['source'] = $result['source'];
        return $data;
    }

    function getSessionBasedDataForRegistration($filter,$registrationSessionIds){
        //_p($filter);_p($registrationSessionIds);die;
        $result = $this->_getDataFormSessionIds($registrationSessionIds,$filter['count'],$filter['defaultView']);
        //_p('--');_p($result);die;
        arsort($result['source']);
        //_p($result['source']);die;
        if(!($filter['sourceFlag']==1)){
            $trafficSourceCount = 0;
            foreach ($result['source'] as $key => $value) {
                $trafficSourceCount +=$value;
                if($key =='Other'){
                    continue;
                }
                $trafficSourceArray[$i++] = $key;
                $lis = $lis . 
                        '<li role="presentation"  >'.
                            '<a href="javascript:void(0)" id="'.$key.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst($key).
                            '</a>'.
                            '<input id="hidden_'.$key.'" type="hidden" value="'.$value.'" >'.
                        '</li>';
            }
            $barGraph['lis'] = $lis;
            $barGraph['defaultView'] = $result['defaultView'];
            $countValue = $result['source'][$result['defaultView']];
            $barGraph['donutChartDataForTrafficSource'] = $this->prepareDataForDonutChart($result['source'],$filter['colorCodes'],$trafficSourceCount);
        }else{
            $countValue = $filter['count'];
        }
        arsort($result['source']);
        //_p($trafficSourceArray);die;        //_p($defaultView);die;        // 2. data in form of bar graph        //$defaultView = 'mailer';
        //_p($countValue);die;
        $barGraph['utmSource'] = $this->prepareDataForTrafficSourceForResponse($result['utmSource'],$countValue,0);
        $barGraph['utmCampaign'] = $this->prepareDataForTrafficSourceForResponse($result['utmCampaign'],$countValue,1);
        $barGraph['utmMedium'] = $this->prepareDataForTrafficSourceForResponse($result['utmMedium'],$countValue,0);
        //_p($barGraph);die;
        $inputArray = array(
                            'utmSource' =>$barGraph['utmSource'],
                            'utmCampaign' => $barGraph['utmCampaign'],
                            'utmMedium' => $barGraph['utmMedium']
                            );
        $barGraphData = $this->prepareTrafficSourceBarGraph($inputArray);
        
        $data['barGraph'] = $barGraph;
        $data['barGraph']['barGraphData'] = $barGraphData;
        $data['source'] = $result['source'];
        return $data;
    }

    function prepareTrafficSourceBarGraph($barGraph){
        //_p($barGraph);die;
        $utmSource = $this->prepareBarGraphForUTMData(1,'UTM Source',$barGraph['utmSource']);
        $utmCampaign = $this->prepareBarGraphForUTMData(2,'UTM Campaign',$barGraph['utmCampaign']);
        $utmMedium = $this->prepareBarGraphForUTMData(3,'UTM Medium',$barGraph['utmMedium']);
        $l1 = $barGraph['utmSource']['count'];
        $l3 = $barGraph['utmCampaign']['count'];
        $l2 = $barGraph['utmMedium']['count'];

        if($l1 > $l2){
            $maxL = 1;
        }else{
            $maxL = 2;
        }
        $dataValue =    '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmSource.'</div>'.
                        '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmMedium.'</div>'.
                        '<div class="col-md-12 col-sm-12 col-xs-12">'.$utmCampaign.'</div>';
        //_p($l1);_p($l2);_p($maxL);die;
        /*switch($maxL) {
            case 1:
                $dataValue = '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmSource.'</div>'.
                            '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmMedium.$utmCampaign.'</div>';
                break;
            
            case 2:
                $dataValue = '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmSource.$utmCampaign.'</div>'.
                            '<div class="col-md-6 col-sm-6 col-xs-12">'.$utmMedium.'</div>';
                break;
        }*/
        //_p($dataValue);die;
        return $dataValue;

    }

    function prepareBarGraphForUTMData($number,$heading,$data){
        if($data['count'] >=10){
            $height = 320;    
        }else{
            $height = $data['count']*36;
        }
        $class = "col-md-6 col-sm-6 col-xs-12";
        $result = '<div   id="BGraph'.$number.'" >'.
            '<div class="x_panel tile " style="padding-right:5px !important">'.
                '<div class="x_title " >'.
                    '<div  class= "pieHeadingSmallSA" style="width: 100%">'.
                        '<table style="width:100%">'.
                                    '<tr>'.
                                        '<td colspan=2>'.$heading.'</td>'.
                                        '<td style="text-align: left; width: 70px">'.'     '.'</td>'.
                                        '<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">'.'MOM'.'</td>'.
                                        '<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">'.'YOY'.'</td>'.
                                    '</tr>'.
                                '</table>'.
                    '</div>'.
                    '<div class="clearfix"></div>'.
                '</div>'.
                '<div class="loader_small_overlay" id="barGraphHorizental_'.$number.'" style="display:none"><img src="'.SHIKSHA_HOME.'/public/images/trackingMIS/mis-loading-small.gif"></div>'.
                '<div class="x_content overflow_BR" id="barGraph'.$number.'" style="height: '.$height.'px;padding-right:0px !important;padding-left:0px !important">'.$data['barGraph'].
                '</div>'.
            '</div>'.
        '</div>';

        return $result;
    }

    function _getDataFormSessionIds($sessionIds,$count,$defaultView=''){
        $sessionId = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($sessionIds as $key => $value) {
            $sessionId[] = $value['visitorsessionid'];
            $sessionResultArray[$value['visitorsessionid']] = $value['count'];
        }
        $result = $this->getDataSourceWise($sessionId,$sessionResultArray,$count,$defaultView);
        //_p($result);die;
        return $result;
    }

    public function prepareDataForTrafficSourceForResponse($UTMArray,$UTMDataCount,$sizeFlag=0)
    {  
        arsort($UTMArray);
        $maxValue = 0;
        $i=0;
        foreach ($UTMArray as $key => $value) { 
            if($i==0){
                $maxValue = $value;
            }else{
                break;   
            }
            $i++;
        }
        $avg = number_format((($maxValue)/100), 2,'.','');
        //_p($avg);die;
        if($sizeFlag==0){
            $leftWidth = 40;
            $centerWidth  = 30;
            $countWidth =   30;
            $pageNameWidth = 26;
        }else{
            $leftWidth = 55;
            $centerWidth  = 30;
            $countWidth =   15;
            $pageNameWidth = 70;
        }
            
        $barGraph = '<table style="width: 100%;">';
        foreach ($UTMArray as $key => $value) {   
            $normalizeValue = number_format(($value/$avg), 0, '.', '');
            $actualValue = number_format(($value));
            $title = ucfirst($key);
            $fieldName = limitTextLength($title,$pageNameWidth);
            $span = '<span title="'.htmlentities($title).'">'.htmlentities($fieldName).'</span>';
            $percantageValue = number_format((($value*100)/$UTMDataCount), 2,'.','');
            if($percantageValue > 100){
                $percantageValue =100;
            }
            $field ='<td class="BGHeading_fontSize" style="width:'.$countWidth.'% !important">&nbsp&nbsp'.$actualValue.' ( '.$percantageValue.'%)'.
                    '</td>';    
            

            $barGraph = $barGraph.
            '<tr class="widget_summary">'.
                '<td class="w_left" style="width:'.$leftWidth.'% !important">'.
                   $span.
                '</td>'.
                '<td class="w_center " style="width:'.$centerWidth.'% !important">'.
                    '<div class="progress" style="margin-bottom:10px !important" >'.
                        '<div  title = "'.$actualValue.'" class="progress-bar bg-green" role="progressbar" style="width:'.$normalizeValue.'%'.'" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">'.
                            '<span class="sr-only"  ></span>'.
                        '</div>'.
                    '</div>'.
                '</td>'.$field.
                '<div class="clearfix"></div>';   
        }
        
        $barGraph = $barGraph.'</table>';
        //die;_p($barGraph);die;
        return  array('barGraph'=>$barGraph,
                    'count' => count($UTMArray));   
    }

    function getDataSourceWise($sessionId,$sessionResult,$count,$defaultView='')
    {
        if( ! empty($sessionId))
        {
            // for traffic source
            if($defaultView==''){
                $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'source');
                $result['source'] = $this->getDataForSourceFilter($sourceResult,$sessionResult,0);

                $prioritySourceArray= array('paid','mailer','social','direct','seo');
                foreach ($result['source'] as $key => $value) {
                    $trafficSourceArray[] = $key;
                }
                foreach ($prioritySourceArray as $key => $value) {
                    if(in_array($value, $trafficSourceArray)){
                        $defaultView = $value;
                        break;
                    }
                }
                $count = $result['source'][$defaultView];
                $result['count'] = $count;
            }
            //$result['count'] = $result['source'][$defaultView];
            //$result['defaultView'] = $defaultView; 
            //$result['sessionId'] = $sessionId;

            // for utm source
            $sourceResult = array();
            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'utmSource',$defaultView);
            $result['utmSource'] = $this->getDataForSourceFilter($sourceResult,$sessionResult,1,$count);

            // for utm campaign
            $sourceResult = array();
            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'utmCampaign',$defaultView);
            $result['utmCampaign'] = $this->getDataForSourceFilter($sourceResult,$sessionResult,1,$count);

            // for utm medium
            $sourceResult = array();
            $sourceResult =$this->trackingModel->getSourceForSessionId($sessionId,'utmMedium',$defaultView);
            $result['utmMedium'] = $this->getDataForSourceFilter($sourceResult,$sessionResult,1,$count);
            //die;
            $result['defaultView'] = $defaultView;
        }
        //_p($defaultView);
        //_p($result);die;
        return $result;    
    }

    function getDataForSourceFilter($sourceResult,$sessionResult,$flag,$count=''){
        $sourceSessionMapping = array();
        foreach ($sourceResult as $key => $value) {
            $sourceSessionMapping[$value['sessionId']] = $value['value'];
        }

        $sourceWiseResult = array();
        $i = 0;
        foreach ($sessionResult as $key => $value) {
            
                if(empty($sourceSessionMapping[$key])){
                    if($flag != 1){
                        $sourceWiseResult['Other'] += $value;
                    }
                    continue;
                }
            
            
            $sourceWiseResult[$sourceSessionMapping[$key]] += $value;
            if($flag == 1){
                $UTMCount += $value;    
            }
        }
        
        foreach ($sourceWiseResult as $key => $value) {
            $sourceWiseSingleSplit[$key] = $value; 
        }
        //_p($flag);_p($count);_p($UTMCount);die;
        if($flag == 1){
            if($count > $UTMCount){
                $diff = $count - $UTMCount;
                $sourceWiseSingleSplit['Other'] = $diff;    
            }
        }
        arsort($sourceWiseSingleSplit);
        //_p($sourceWiseSingleSplit);die;
        return $sourceWiseSingleSplit;
    }

    function getTopTilesDataForResponses($pageName,$filterArray,$responseType,$productIdsArray,$totalResponses,$trackingIdsArray,$filteredCourseIdsArray,$responseCount)
    {
        if(!$responseCount){
            $avgResponsesByRespondent =0;
            $responsesByFirstTimeUsers =0;
            
        }else{
            $distinctUserCountForSelectedDuration = $this->trackingModel->getDistinctUserCountForSelectedDuration($trackingIdsArray,$filterArray,$responseType,$filteredCourseIdsArray,'response');
            $distinctUserArray = array_map(function($a){
                    return $a['userId'];
                }, $distinctUserCountForSelectedDuration); 

            $avgResponsesByRespondent = number_format((($totalResponses)/count($distinctUserArray)), 2, '.', '');

            $responsesByFirstTimeUsers = $this->trackingModel->getResponsesByFirstTimeUser($distinctUserArray,$filterArray);
            $bftu = count($distinctUserArray)-count($responsesByFirstTimeUsers);
            if($bftu){
                if($bftu <0){
                    $bftu= -$bftu;
                }
                $responsesByFirstTimeUsers = $bftu;
            }else{
                $responsesByFirstTimeUsers =0;
            }
        }

        if($responseType == 'rmc')
        {
            $universityIdsForSelectedDuration =  $this->trackingModel->getRMCUniversityIdsForSelectedDuration($filterArray);
            $universityIdsArray = array_map(function($a){
                return $a['universityId'];
            }, $universityIdsForSelectedDuration);
            if(count($universityIdsArray)){
                $courseIdsForSelectedDuration = $this->trackingModel->getRMCCourseIds($universityIdsArray,$filterArray);    
            }else{
                $courseIdsForSelectedDuration = 0;
            }     
            $universityIdsCount = count($universityIdsForSelectedDuration);   
            $courseIdsCount = count($courseIdsForSelectedDuration);
        }
        else if($responseType == 'paid' || $responseType == 'free')
        {
            $filterUserIdsArray = array(0,3284455,5194989);   
            $courseIdsForSelectedDuration=$this->trackingModel->getCourseIdsForSelectedDuration($productIdsArray,$filterArray,$responseType,$filterUserIdsArray);  
            $courseIdsArray = array_map(function($a){
                return $a['courseId'];
            }, $courseIdsForSelectedDuration);
            $courseIdsCount= count($courseIdsArray);
            if($courseIdsArray)
            {
                $universityIds = $this->trackingModel->getUniversityIds($courseIdsArray); 
                $universityIdsCount = count($universityIds);     
            }
            else
            {
                $universityIdsCount =0;
            }     
        }else{
            $filterUserIdsArray = array(0,3284455,5194989);   
            $courseIdsForSelectedDuration=$this->trackingModel->getCourseIdsForSelectedDuration($productIdsArray,$filterArray,'paid',$filterUserIdsArray);
            $paidResponses = 0;
            $shikshaApplyResponses=0;
            foreach ($responseCount as $key => $value) 
            {
                //if($value['listing_subscription_type'] == 'paid' && $value['type'] != 'rateMyChance'){
                if($value['listing_subscription_type'] == 'paid'){
                    $paidResponses += $value['reponsesCount'];
                }
                if($value['type'] == 'rateMyChance'){
                   $shikshaApplyResponses += $value['reponsesCount'];
                }    
            }
        }
        if($responseType && $responseType != 'rmc'){
            $topTiles = array($totalResponses,$avgResponsesByRespondent,$universityIdsCount,$courseIdsCount,$responsesByFirstTimeUsers);    
        }else if(!$responseType){
            $topTiles = array($totalResponses,$avgResponsesByRespondent,$paidResponses,$shikshaApplyResponses,count($courseIdsForSelectedDuration),$responsesByFirstTimeUsers);
        }else if($responseType == 'rmc'){
            $topTiles = array($totalResponses,$avgResponsesByRespondent,$universityIdsCount,$courseIdsCount,count($distinctUserArray),$responsesByFirstTimeUsers); 
        }
        
        return $topTiles;
    }

    function prepareDataForDifferentCharts($responsesData,$pageName,$responseType,$filterArray,$isComparision,$metric,$colorCodes)
    { 
        $startYear = date('Y', strtotime($filterArray['startDate']));
        $endYear = date('Y', strtotime($filterArray['endDate']));
        $gendate = new DateTime();

        if($filterArray['view'] == 1)
        {
            $sDate=date_create($filterArray['startDate']);
            $eDate=date_create($filterArray['endDate']);
            $diff = date_diff($sDate,$eDate);
            $dateDiff = $diff->format("%a");
            $lineArray=array();
            $tempDate =$filterArray['startDate'];
            for($i=0;$i<=$dateDiff;$i++){
                $lineArray[$tempDate] =0;
                $tempDate = date('Y-m-d', strtotime($tempDate . ' +1 day'));
            }                
            foreach ($responsesData as  $value) {
                    $lineArray[$value['responseDate']] += $value['reponsesCount'];    
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];   
                    
                    switch ($metric) {
                        case 'RESPONSE':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            break;

                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataFour[$value['paidFree']]+= $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $page = $value['page'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['reponsesCount'];
                        //$pieChartDataThree[$value['page']]+=$value['reponsesCount'];
                    }
            }
        }else if($filterArray['view'] == 2){   
            if($startYear == $endYear)
            {
                // creating week array
                $swn = intval(date('W', strtotime($filterArray['startDate'])));
                $ewn = intval(date('W', strtotime($filterArray['endDate'])));
                //_p($swn);_p($ewn);
                $lineArray = array();
                foreach ($responsesData as  $value) {
                    $lineChartData[$value['weekNo']] += $value['reponsesCount'];
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    switch ($metric) {
                        case 'RESPONSE':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            break;

                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataFour[$value['paidFree']]+= $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $page = $value['page'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['reponsesCount'];
                        //$pieChartDataThree[$value['page']]+=$value['reponsesCount'];
                    }                
                }
                //_p($lineChartData);
                if($swn > $ewn){
                    $swn = 0;
                }
                $lineArray[$filterArray['startDate']] = $lineChartData[$swn]?$lineChartData[$swn]:0;
                for ($i=$swn+1; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = 0;   
                }
                
                foreach ($lineChartData as $key => $value) {
                    if($key == $swn)
                    {
                        continue;
                    }         
                    $gendate->setISODate($startYear,$key,1); //year , week num , day
                    $lineArray[$gendate->format('Y-m-d')] = $value;   
                }
                //_p($lineArray);die;
            }
            else
            {
                $swn = date('W', strtotime($filterArray['startDate']));
                $ewn =date('W', strtotime($startYear."-12-31"));
                if($ewn == 1){
                    $ewn = date('W', strtotime($startYear."-12-24"));
                }
                $swn1 = 1;
                $ewn1 =date('W', strtotime($filterArray['endDate']));
                $gendate->setISODate($startYear,$ewn,7); //year , week num , day
                $tempDate = $gendate->format('Y-m-d');
                if($tempDate >= $filterArray['endDate'])
                {
                    $swn1 =0;
                    $ewn1 =-1;
                }
               $lineArray = array();
               foreach ($responsesData as  $value) {
                    if(($value['weekNo']) > $ewn)
                    {
                        $lineChartData[1] += $value['reponsesCount'];
                    }else{
                        $lineChartData[($value['weekNo'])] += $value['reponsesCount'];
                    }
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    switch ($metric) {
                        case 'RESPONSE':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            break;

                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataFour[$value['paidFree']]+= $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $page = $value['page'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['reponsesCount'];
                        //$pieChartDataThree[$value['page']]+=$value['reponsesCount'];
                    }
                }
               $lineArray[$filterArray['startDate']] = $lineChartData[$swn]?$lineChartData[$swn]:0;
               for ($i=$swn+1; $i <= $ewn ; $i++) { 
                    $gendate->setISODate($startYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;
                }
                for ($i=$swn1; $i <= $ewn1 ; $i++) { 
                    $gendate->setISODate($endYear,$i,1); //year , week num , day
                    $df = $gendate->format('Y-m-d');
                    $lineArray[$df] = $lineChartData[$i]?$lineChartData[$i]:0;   
                }
            }    
        }else if($filterArray['view'] == 3){   
            if($startYear == $endYear)
            {
                $smn = date('m', strtotime($filterArray['startDate']));
                $emn = date('m', strtotime($filterArray['endDate']));
                $lineArray = array();
                foreach ($responsesData as  $value) {
                    if($value['monthNo'] <=9)
                    {
                        $lineChartData['0'.$value['monthNo']] += $value['reponsesCount'];
                    }else{
                        $lineChartData[$value['monthNo']] += $value['reponsesCount'];    
                    }  
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    switch ($metric) {
                        case 'RESPONSE':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            break;

                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataFour[$value['paidFree']]+= $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $page = $value['page'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['reponsesCount'];
                        //$pieChartDataThree[$value['page']]+=$value['reponsesCount'];
                    }    
                }
                if($lineChartData[$smn])
                {
                    $lineArray[$filterArray['startDate']] = $lineChartData[$smn];    
                }else{
                    $lineArray[$filterArray['startDate']] = 0;    
                }
                
                for ($i=$smn+1; $i <= $emn ; $i++) {
                    if($i <= 9){
                        $i =intval($i);
                        $i = '0'.$i;
                        $df = $startYear.'-'.$i.'-01';
                    }else{
                        $df = $startYear.'-'.$i.'-01';    
                    }
                    if($lineChartData[$i]){
                        $lineArray[$df] = $lineChartData[$i];    
                    }else{
                        $lineArray[$df] = 0;   
                    }    
                }
            }
            else{
                $smn = intval(date('m', strtotime($filterArray['startDate'])));
                $emn = intval(12);
                $smn1 = intval(1);
                $emn1 =intval(date('m', strtotime($filterArray['endDate'])));
               //_p($smn);_p($emn);_p($smn1);_p($emn1);die;
               $lineArray = array();
               $lineArray[$filterArray['startDate']] = 0;
               $daten = $filterArray['startDate'];
                $mnp =0;
                $mnn =0;
                $y = date('Y', strtotime($responsesData[0]['responseDate']));
                $flag = 0;
                $sd='';
                for($i=$startYear; $i<=$endYear;$i++)
                {
                    
                    if($i == $startYear){
                        $sm =$smn;    
                    }else{
                        $sm =1;
                    }

                    if($i == $endYear){
                        $em = $emn1;
                    }else{
                        $em =12;
                    }
                    
                    for($j=$sm;$j<=$em;$j++)
                    {
                        if($j <= 9)
                        {
                            $j = intval($j);
                            if($flag == 0){
                                $daten = $i.'-0'.$j.'-01';
                            }else{
                                $daten = $i.'-0'.$j.'-01';    
                            }
                            
                        }else{
                            $daten = $i.'-'.$j.'-01';
                        }  
                        if($flag == 0)
                        {
                            $sd=$daten;
                            $flag=1;

                        }
                        $lineArray[$daten] = 0;
                    }
                }
                //_p($lineArray);die;

                foreach ($responsesData as  $value) {
                    $mnn = $value['monthNo'];
                    if($mnn > $mnp)
                    {
                        if($value['monthNo'] <= 9)
                        {
                            $daten = $y.'-0'.$value['monthNo'].'-01';
                        }else{
                            $daten = $y.'-'.$value['monthNo'].'-01';
                        }  
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }else if($mnn == $mnp)
                    {
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }
                    else{
                        $y++;
                        if($value['monthNo'] <= 9)
                        {
                            $daten = $y.'-0'.$value['monthNo'].'-01';
                        }else{
                            $daten = $y.'-'.$value['monthNo'].'-01';
                        }  
                        $lineArray[$daten] += $value['reponsesCount'];
                        $mnp = $mnn;    
                    }
                    $pieChartDataOne[$value['siteSource']]+= $value['reponsesCount'];
                    switch ($metric) {
                        case 'RESPONSE':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            break;

                        case 'REGISTRATION':
                            $pieChartDataTwo[$value['type']]+= $value['reponsesCount'];
                            $pieChartDataFour[$value['paidFree']]+= $value['reponsesCount'];
                            break;

                        case 'CPENQUIRY':
                            $pieChartDataTwo[$value['source']]+= $value['reponsesCount'];
                            break;

                        case 'DOWNLOAD':
                            if(!$isComparision && $pageName){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];    
                            }
                            break;;

                        case 'COMPARE':
                            if($pageName && !$isComparision){
                                $pieChartDataTwo[$value['widget']]+= $value['reponsesCount'];
                            }
                            break;
                    }
                    
                    if(!$pageName && !$isComparision){
                        $page = $value['page'];
                        $page = $this->MISCommonLib->getPageName($page);
                        $pieChartDataThree[$page]+=$value['reponsesCount'];
                        //$pieChartDataThree[$value['page']]+=$value['reponsesCount'];
                    }
                }
                $val = $lineArray[$sd];
                //_p($sd);
                //_p($lineArray);die;
                $date1=date_create($filterArray['startDate']);
                $date2=date_create($sd);
                $diff = date_diff($date1,$date2);
                $dateDiff = $diff->format("%a");
                //echo $diff->format("%a");die;
                if($diff->format("%a") !=0){
                    unset($lineArray[$sd]);    
                }
                $lineArray[$filterArray['startDate']] = $val;
                //_p($lineArray);die;
            }
        }
        //_p($lineArray);die;
        /*
           _p($lineArray);
            _p('----------------pie-1-----------');
            _p($pieChartDataOne);
            _p('-----------------pie-2----------');
            _p($pieChartDataTwo);
            _p('------------------pie-3---------');
            _p($pieChartDataThree);
            _p('---------------------------');
            die;
            _p('---------------------------');
            _p($pieChartDataThree);die;
        */
        
        $totalResponses = $pieChartDataOne['Desktop']+$pieChartDataOne['Mobile'];

        $lineChartData      = $this->prepareDataForLineChart($lineArray,$pageName);
        $pieChartOneData    = $this->prepareDataForDonutChart($pieChartDataOne,$colorCodes,$totalResponses);

        switch ($metric) {
            case 'RESPONSE':
            case  'CPENQUIRY':
                $pieChartTwoData = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$totalResponses);
                break;

            case 'REGISTRATION':
                $pieChartTwoData = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$totalResponses);
                $pieChartFourData = $this->prepareDataForDonutChart($pieChartDataFour,$colorCodes,$totalResponses);
                break;
            
            case 'COMPARE':
                if($pageName && !$isComparision){
                    $pieChartTwoData = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$totalResponses);        
                }
                break;
        }
                
        if($pageName=='' && !$isComparision && $metric != 'DOWNLOAD' ){
            $pieChartDataThree    = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$totalResponses);
        }
        if($pageName=='' && $metric == 'DOWNLOAD' &&  $isComparision ==0){
            $pieChartDataThree    = $this->prepareDataForDonutChart($pieChartDataThree,$colorCodes,$totalResponses);
        }   
        if($pageName && $metric == 'DOWNLOAD' &&  $isComparision ==0){
            $pieChartDataTwo    = $this->prepareDataForDonutChart($pieChartDataTwo,$colorCodes,$totalResponses);
        }
        
        if($pageName && $metric != 'DOWNLOAD' && $metric != 'COMMENT_REPLY' && $metric != 'COMPARE'){
            $dataForDataTable   = $this->prepareDataForDataTableForResponses($responsesData,$totalResponses,$metric);
            $pageData['dataForDataTable'] = $dataForDataTable;    
        }else if($pageName && $metric == 'DOWNLOAD'){
            $dataForDataTable   = $this->prepareDataForDataTableForDownloads($responsesData,$totalResponses,$metric);
            $pageData['dataForDataTable'] = $dataForDataTable;
        }else if($pageName &&  $metric == 'COMMENT_REPLY' && $iscomparision ){
                $dataForDataTable   = $this->prepareDataForDataTableForComrep($responsesData,$totalResponses,$metric,$pageName);
                $pageData['dataForDataTable'] = $dataForDataTable;
        }else if($pageName && $metric == 'COMPARE' && !$isComparision){
            $dataForDataTable   = $this->prepareDataForDataTableForCompare($responsesData,$totalResponses,$metric,$pageName);
        }else if($isComparision)
        {
            if($metric == 'CPENQUIRY'){
                $dataForDataTable = $this->prepareDataForDataTableForCPEnquiry($responsesData,$filterArray);
            }else if($metric == 'COMMENT_REPLY'){
                $dataForDataTable   = $this->prepareDataForDataTableForComrep($responsesData,$totalResponses,$metric,$pageName);
            }else if($metric == 'COMPARE'){
                $dataForDataTable   = $this->prepareDataForDataTableForCompare($responsesData,$totalResponses,$metric,$pageName);
            }else {
                $dataForDataTable   = $this->prepareDataForDataTableForOverview($responsesData,$totalResponses,$metric);
            }
            $pageData['dataForDataTable'] = $dataForDataTable;
        }
        
        $pageData['lineChartData'] = $lineChartData;
        if($metric == 'REGISTRATION'){
            if($pageName){
                    $pageData['donutChartData'] = array($pieChartOneData,0,$pieChartTwoData,$pieChartFourData);
            }else{
                if($isComparision==1){
                    $pageData['donutChartData'] = array($pieChartOneData,0,$pieChartTwoData,$pieChartFourData);    
                }else{
                    $pageData['donutChartData'] = array($pieChartOneData,0,$pieChartTwoData,$pieChartFourData,$pieChartDataThree);
                }
            }
        }else{
            if($pageName && $metric != 'DOWNLOAD'  && $metric  != 'COMMENT_REPLY' && $responseType != 'rmc'){
                $pageData['donutChartData'] = array($pieChartOneData,$pieChartTwoData);
            }else if($pageName && $metric == 'DOWNLOAD'  && !$isComparision){
                $pageData['donutChartData'] = array($pieChartOneData,$pieChartDataTwo);
            }else if($pageName && (($metric == 'DOWNLOAD' && $isComparision) || $metric == 'COMMENT_REPLY' || $responseType == 'rmc')){
                $pageData['donutChartData'] = array($pieChartOneData);
            }else if($pageName =='' && !$isComparision && $metric != 'DOWNLOAD' && $metric != 'COMMENT_REPLY' && $responseType != 'rmc' ){
                $pageData['donutChartData'] = array($pieChartOneData,$pieChartTwoData,$pieChartDataThree);
            }else if($pageName =='' && !$isComparision && ($metric == 'DOWNLOAD'  || $metric == 'COMMENT_REPLY' || $responseType == 'rmc') ){
                $pageData['donutChartData'] = array($pieChartOneData,$pieChartDataThree);
            }else if($metric == 'DOWNLOAD' || $metric == 'COMMENT_REPLY'  || $metric == 'COMPARE'  || $responseType == 'rmc'){
                $pageData['donutChartData'] = array($pieChartOneData);
            }else{
                $pageData['donutChartData'] = array($pieChartOneData,$pieChartTwoData);
            }
        }
            
        
        //_p($pageData['donutChartData']);die;
        //_p($pageData);die;
        return $pageData;
    }

    function prepareDataForLineChart($lineChartData,$pageName)
    {
        $i=0;
        foreach ($lineChartData as $date => $count) {
            $lineChartArray[$i++] = array($date,$count);   
        }
        $lineChartData = array('lineChartArray', $lineChartArray);
        return $lineChartData;
    }

    function prepareDataForDonutChart($donutChartData,$colorArray,$total)
    {   
        arsort($donutChartData);
        $i = 0; 
        foreach ($donutChartData as $key => $value) {
            $value = intval($value);
            $donutChartArray[$i]['value'] = $value;
            $donutChartArray[$i]['label'] = $key;
            $donutChartArray[$i]['color'] = $colorArray[$i];
            $splitName = strlen($key) > 18 ? substr($key, 0, 14) . ' ...' : $key;
            $donutChartIndexData=$donutChartIndexData. 
                            '<tr>'.
                                '<td class="width_60_percent_important" >'.
                                    '<p style="font-size:15px" title="'.$key.' : '.number_format($value).'"><i class="fa fa-square " style="color: '.$donutChartArray[$i]['color'].'">'.'</i>'.'&nbsp'.$splitName.''.'</p>'.
                                '</td>'.
                                '<td style="width:15%;text-align:center">'.number_format((($value*100)/$total), 2, '.', '').'</td>'.
                                '<td style="width:25%;text-align:center">'.$value.'</td>'.
                            '</tr>';                            
            $i++;
        }
        $donutChart = array($donutChartArray,$donutChartIndexData,$total);
        return $donutChart;
    }

    function prepareDataForDataTableForCompare($responsesData,$totalResponses,$metric,$pageName)
    {
        if($pageName){
            foreach ($responsesData as  $value) {
                $prepareTableData[$value['widget']][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
            }
            //_p($prepareTableData);die;
            $dataTableHeading = " (Widget-Source Application wise)";
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">widget </th>'.
                                '<th style="padding-left:20px">Source Application</th>'.
                                '<th style="padding-left:20px;width:100px"> Count </th>'.
                                '<th style="padding-left:20px"> (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('widget','Site Source','Compare Courses Count','Compare Courses (%)');
            $i=1;
            foreach ($prepareTableData as $widget => $widgetArray) {
                foreach ($widgetArray as $siteSource => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$widget.'</td>'.
                                '<td class=" ">'.$siteSource.'</td>'.
                                '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                                '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($widget,$siteSource,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
                    
                }    
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        }else{
            foreach ($responsesData as  $value) {
                $page = $value['page'];
                $page = $this->MISCommonLib->getPageName($page);
                $prepareTableData[$page][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
            }
            //_p($prepareTableData);die;
            $dataTableHeading = " (Page-Source Application wise)";
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">Page </th>'.
                                '<th style="padding-left:20px">Source Application</th>'.
                                '<th style="padding-left:20px;width:100px">Count </th>'.
                                '<th style="padding-left:20px"> (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Page','Site Source','Added Courses Count','Added Courses (%)');
            $i=1;
            foreach ($prepareTableData as $page => $pageArray) {
                foreach ($pageArray as $siteSource => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$page.'</td>'.
                                '<td class=" ">'.$siteSource.'</td>'.
                                '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                                '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($page,$siteSource,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
                    
                }    
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);    
        }
        return $DataForDataTable;   
    }

    function prepareDataForDataTableForComrep($responsesData,$totalResponses,$metric,$pageName)
    {
        //_p('responseDate');_p($responsesData);_p('totalResponses');_p($totalResponses);_p('metric');_p($metric);die;
        //page-device-conversion
        if($pageName){
            foreach ($responsesData as  $value) {
                $prepareTableData[$value['contentId']][$value['siteSource']][$value['conversionType']]['responsesCount']+=$value['reponsesCount'];
            }
            //_p($prepareTableData);die;
            $dataTableHeading = " (Content Id-Source Application-Conversion Type wise)";
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">Content Id </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px">Conversion Type </th>'.
                                '<th style="padding-left:20px;width:100px">Comments - Replies Count </th>'.
                                '<th style="padding-left:20px">Comments - Replies (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Content Id','Site Source','Conversion Type','Comments - Replies Count','Comments - Replies (%)');
            $i=1;
            foreach ($prepareTableData as $contentId => $contentArray) {
                foreach ($contentArray as $siteSource => $deviceArray) {
                    foreach ($deviceArray as $conversionType => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$contentId.'</td>'.
                                '<td class=" ">'.$siteSource.'</td>'.
                                '<td class=" ">'.$conversionType.'</td>'.
                                '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                                '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($contentId,$siteSource,$conversionType,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
                    }
                }    
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        }else{
            foreach ($responsesData as  $value) {
                $page = $value['page'];
                $page = $this->MISCommonLib->getPageName($page);
                $prepareTableData[$page][$value['siteSource']][$value['conversionType']]['responsesCount']+=$value['reponsesCount'];
            }
            //_p($prepareTableData);die;
            $dataTableHeading = " (Page-Source Application-Conversion Type wise)";
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">Page </th>'.
                                '<th style="padding-left:20px">Source Application </th>'.
                                '<th style="padding-left:20px">Conversion Type </th>'.
                                '<th style="padding-left:20px;width:100px">Comments - Replies Count </th>'.
                                '<th style="padding-left:20px">Comments - Replies (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Page','Site Source','Conversion Type','Comments - Replies Count','Comments - Replies (%)');
            $i=1;
            foreach ($prepareTableData as $page => $pageArray) {
                foreach ($pageArray as $siteSource => $deviceArray) {
                    foreach ($deviceArray as $conversionType => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$page.'</td>'.
                                '<td class=" ">'.$siteSource.'</td>'.
                                '<td class=" ">'.$conversionType.'</td>'.
                                '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                                '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($page,$siteSource,$conversionType,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
                    }
                }    
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);    
        }
        
        //_p($DataForDataTable);die;
        return $DataForDataTable;
    }

    function prepareDataForDataTableForDownloads($responsesData,$totalResponses,$metric)
    {
        foreach ($responsesData as  $value) {
            $prepareTableData[$value['widget']][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
        }
        $metric = ucwords(strtolower($metric)).'s';
        $dataTableHeading = " (Widget-Source Application wise)";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">Widget </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">'.$metric.' Count </th>'.
                            '<th style="padding-left:20px">'.$metric.' (%) </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Type','Widget','Site Source',$metric.' Count',$metric.' (%)');
        $i=1;
        foreach ($prepareTableData as $widget => $widgetArray) {
            foreach ($widgetArray as $siteSource => $value) {
                $dataTable = $dataTable.
                    '<tr class="even pointer">'.
                        '<td class="a-center ">'.
                            '<input type="checkbox" class="tableflat">'.
                        '</td>'.
                        '<td class=" ">'.$widget.'</td>'.
                        '<td class=" ">'.$siteSource.'</td>'.
                        '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                        '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                    '</tr>';
                $prepareDataForCSV[$i++] = array($widget,$siteSource,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
            }    
        }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        //_p($dataTable);die;
        return $DataForDataTable;
    }

    function prepareDataForDataTableForResponses($responsesData,$totalResponses,$metric)
    {
        foreach ($responsesData as  $value) {
            $prepareTableData[$value['type']][$value['widget']][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
        }
        $metric = ucwords(strtolower($metric)).'s';
        $dataTableHeading = " (Type-Widget-Source Application wise)";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">Type </th>'.
                            '<th style="padding-left:20px">Widget </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">'.$metric.' Count </th>'.
                            '<th style="padding-left:20px">'.$metric.' (%) </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Type','Widget','Site Source',$metric.' Count',$metric.' (%)');
        $i=1;
        foreach ($prepareTableData as $type => $typeArray) {
            foreach ($typeArray as $widget => $widgetArray) {
                foreach ($widgetArray as $siteSource => $value) {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$type.'</td>'.
                            '<td class=" ">'.$widget.'</td>'.
                            '<td class=" ">'.$siteSource.'</td>'.
                            '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                            '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                        '</tr>';
                    $prepareDataForCSV[$i++] = array($type,$widget,$siteSource,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));
                }    
            }
        }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        return $DataForDataTable;
    }

    function prepareDataForDataTableForOverview($responsesData,$totalResponses,$metric)
    {
        //_p('responseDate');_p($responsesData);_p('totalResponses');_p($totalResponses);_p('metric');_p($metric);die;
        foreach ($responsesData as  $value) {
            $page = $value['page'];
            $page = $this->MISCommonLib->getPageName($page);
            $prepareTableData[$page][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
        }

        $metric = ucwords(strtolower($metric)).'s';
        $dataTableHeading = " (Page-Source Application wise)";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th style="padding-left:20px">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th style="padding-left:20px">Page </th>'.
                            '<th style="padding-left:20px">Source Application </th>'.
                            '<th style="padding-left:20px;width:100px">'.$metric.' Count </th>'.
                            '<th style="padding-left:20px">'.$metric.' (%) </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
        $prepareDataForCSV[0]  = array('Page','Site Source',$metric.' Count',$metric.' (%)');
        $i=1;
        foreach ($prepareTableData as $page => $pageArray) {
            foreach ($pageArray as $siteSource => $value) {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$page.'</td>'.
                            '<td class=" ">'.$siteSource.'</td>'.
                            '<td class=" ">'.number_format($value['responsesCount']).'</td>'.
                            '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'</td>'.
                        '</tr>';
                    $prepareDataForCSV[$i++] = array($page,$siteSource,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', ''));   
            }
        }
        $dataTable = $dataTable.'</tbody>';
        $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
        return $DataForDataTable;
    }   

    function getPageArray($pageName,$filterArray)
    {
        switch ($pageName) {
            case 'categoryPage' :
                $page = array('categoryPage','savedCoursesTab');
                break;
            case 'rankingPage':
                switch ($filterArray['pageType']) {
                    case 0:
                        $page = array('courseRankingPage', 'universityRankingPage');
                        break;
                    case 1:
                        $page = 'universityRankingPage';
                        break;
                    case 2:
                        $page = 'courseRankingPage';
                        break;
                    default:
                        $page = array('courseRankingPage', 'universityRankingPage');
                        break;
                } 
                break;
            default :
                $page = $pageName;
                break;
        }
        return $page;
    } 

    function getTopTilesDataForCPEnquiry($filterArray,$totalCPEnquiry,$tempLmsIds)
    {
        if($totalCPEnquiry)
        {
            $userIds = $this->trackingModel->getUserIds($filterArray); 
            $userIdsArray = array_map(function($a){
                    return $a['mobile'];
                }, $userIds);
            $repeatUsers = $this->trackingModel->getResponsesByFirstTimeUserForConsultant($userIdsArray,$filterArray,'response');
            $totalUsers = count($userIdsArray);
            $firstTimeUsers = $totalUsers - count($repeatUsers); 

            if($firstTimeUsers < 0){
                $firstTimeUsers = -$firstTimeUsers;
            }
        }else
        {       $totalUsers = 0;
                $firstTimeUsers =0;
        }

        //Get Paid Consultant,university,region count

        $data = $this->trackingModel->getClientConsultantSubscription($filterArray);
        foreach ($data as $key => $value) {
            $result[$value['subscriptionId']] = $value['consultantId'];
        }
        //_p($subscriptionIds);die;
        $subscriptionIds = array_keys($result);
        $this->CI->load->library('subscription_client');
        $objSumsProduct              =  new Subscription_client();
        $resultSet                        = $objSumsProduct->getMultipleSubscriptionDetails(CONSULTANT_CLIENT_APP_ID,$subscriptionIds,true);
        //_p($resultSet);
        foreach ($resultSet as $key => $value) {
            if($value['BaseProdRemainingQuantity'] < 300){
                unset($result[intval($value['SubscriptionId'])]);
            }
        }

        $consultantIds = array_values($result);
        $paidConsultantArray = $this->trackingModel->getPaidConsultant($consultantIds);
        $paidConsultant = count($consultantIds);
        $universities = $paidConsultantArray['univCount'];
        $regions = $paidConsultantArray['regionCount'];
        $topTiles = array($totalCPEnquiry,$paidConsultant,$universities,$regions,$totalUsers,$firstTimeUsers);
        return $topTiles;
    }

    function prepareDataForDataTableForCPEnquiry($CPEnquiryData,$filterArray)
    {
        //_p('responseData');_p($CPEnquiryData);_p('filter array');_p($filterArray);die;
        //_p($CPEnquiryData);die;
        if($CPEnquiryData)
        {
            $totalCPEnquiry = sizeof($CPEnquiryData);
            $trackingModel = $this->CI->load->model('trackingMIS/samismodel');
            $consultantList = $trackingModel->getConsultant();
            //_p($consultantList);
            foreach ($consultantList as $key => $value) {
                $consultantArray[$value['consultantId']] = $value['name'];
            }
            foreach ($CPEnquiryData as $key => $value) {
                $CPEnquiryData[$key]['consultantName'] =$consultantArray[$value['consultantId']];
                if($value['tempLmsId']){
                    $tempLmsIds[$value['tempLmsId']] =0;                
                }
            }
            
            $i=0;
            foreach ($tempLmsIds as $key => $value) {
                $tempLmsId[$i++] =$key;
            }
            if($tempLmsId){
                $courseIds = $trackingModel->getCourseIdsFromTempLMSTable($tempLmsId);    
            }
            
            $courseIdsArray = array_map(function($a){
                    return $a['listing_type_id'];
            }, $courseIds);
            array_unique($courseIdsArray);
            $universityArray = $trackingModel->getUniversityArray($courseIdsArray);
            foreach ($universityArray as $key => $value) {
                $courseToUniName[$value['course_id']] = $value['name'];
            }

            foreach ($courseIds as $key => $value) {
                $tempIdToUniName[$value['id']] = $courseToUniName[$value['listing_type_id']];
            }

            foreach ($CPEnquiryData as $key => $value) {
                if($value['source'] == 'studentCall' || $value['tempLmsId'] ==0 || $value['tempLmsId'] =='' || $value['tempLmsId'] =='null') {
                    $CPEnquiryData[$key]['UniName'] = 'NA';
                }else
                {
                    $CPEnquiryData[$key]['UniName'] = $tempIdToUniName[$value['tempLmsId']];
                }
        
                if($CPEnquiryData[$key]['weekNo'])
                {
                    unset($CPEnquiryData[$key]['weekNo']);    
                }else if($CPEnquiryData[$key]['monthNo']){
                    unset($CPEnquiryData[$key]['monthNo']);    
                }    
            }
            //_p($CPEnquiryData);_p($totalCPEnquiry);die;
            foreach ($CPEnquiryData as  $value) {
                $prepareTableData[$value['consultantName']][$value['UniName']][$value['source']]['responsesCount']+=$value['reponsesCount'];
            }
            //_p($prepareTableData);die;
            $dataTableHeading = " (Consultant-University-Widget wise)";
            $dataTable = '<thead>'.
                            '<tr class="headings">'.
                                '<th style="padding-left:20px">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</th>'.
                                '<th style="padding-left:20px">Consultant Name </th>'.
                                '<th style="padding-left:20px">University Name </th>'.
                                '<th style="padding-left:20px">Widget </th>'.
                                '<th style="padding-left:20px;width:100px">'.'Enquiries Count </th>'.
                                '<th style="padding-left:20px">'.'Enquiries (%) </th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';
            $prepareDataForCSV[0]  = array('Consultant Name','University Name','Widget','Enquiries Count','Enquiries (%)');
            $i=1;
            foreach ($prepareTableData as $client => $clientArray) {
                foreach ($clientArray as $university => $universityArray) {
                    foreach ($universityArray as $key => $value) {
                        $dataTable = $dataTable.
                            '<tr class="even pointer">'.
                                '<td class="a-center ">'.
                                    '<input type="checkbox" class="tableflat">'.
                                '</td>'.
                                '<td class=" ">'.$client.'</td>'.
                                '<td class=" ">'.$university.'</td>'.
                                '<td class=" ">'.$key.'</td>'.
                                '<td class=" ">'.$value['responsesCount'].'</td>'.
                                '<td>'.number_format((($value['responsesCount']*100)/$totalCPEnquiry), 2, '.', '').'</td>'.
                            '</tr>';
                        $prepareDataForCSV[$i++] = array($client,$university,$key,$value['responsesCount'],number_format((($value['responsesCount']*100)/$totalCPEnquiry), 2, '.', ''));   
                    }
                }
            }
            $dataTable = $dataTable.'</tbody>';
            $DataForDataTable = array($dataTableHeading,$dataTable,$prepareDataForCSV);
            return $DataForDataTable;
        }
        return null;
    }   
}
?>
