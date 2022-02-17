<?php 
    $this->load->library('common/Seo_client');
    $Seo_client = new Seo_client();
    $dbURL = $Seo_client->getURLFromDB($articleId,'blog');

    
    $showUpcoming = 0;
    $calendarData = array();
    $sortedAndExpandedImportantDates = array();
    $count = 0;
    foreach($examPageData->getImportantDates() as $ExamPageDate) {
        $startDate = strtotime($ExamPageDate->getStartDate());
        $endDate   = strtotime($ExamPageDate->getEndDate());
        $articleId = $ExamPageDate->getArticleId();
        $eventName = $ExamPageDate->getEventName();
        $articleUrlArray = $Seo_client->getURLFromDB($articleId,'blog');
        if($ExamPageDate->getEndDate() != '0000-00-00') {
            $dateDiff = abs(($endDate - $startDate)/86400);
            for($i = 0;$i <= $dateDiff; $i++) {
                $sortedAndExpandedImportantDates[$count]['timestamp']      = strtotime("$i days",$startDate);
                $sortedAndExpandedImportantDates[$count]['articleId']      = $articleId;
                $sortedAndExpandedImportantDates[$count]['eventName']      = $eventName;
                $sortedAndExpandedImportantDates[$count]['articleUrl']     = $articleUrlArray['URL'];
                $count++;
            }
        }
        else {
            $sortedAndExpandedImportantDates[$count]['timestamp']      = $startDate;
            $sortedAndExpandedImportantDates[$count]['articleId']      = $articleId;
            $sortedAndExpandedImportantDates[$count]['eventName']      = $eventName;
            $sortedAndExpandedImportantDates[$count]['articleUrl']     = $articleUrlArray['URL'];
        }
        $count++;
    }
    
    usort($sortedAndExpandedImportantDates,function($a, $b) {
                                                if ($a['timestamp'] == $b['timestamp']) {
                                                    return 0;
                                                }
                                                return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
                                           }
        );
    $counter = 1;
    foreach($sortedAndExpandedImportantDates as $date) {
        $calendarData['class']              = 'date';
        $calendarData['timestamp']   = $date['timestamp'];
        $calendarData['articleId']   = $date['articleId'];
        $calendarData['eventName']   = $date['eventName'];
        $calendarData['articleUrl']  = $date['articleUrl'];
        $calendarData['tagName']  = 'UPCOMING';
        $calendarData['showUpcoming'] = false;
            if (strtotime(date('Y-m-d')) > $date['timestamp']) {
                $calendarData['class'] = 'exp-date';
            }
            else if (date('Y-m-d') == date('Y-m-d',$date['timestamp'])) {
                $calendarData['showUpcoming'] = true;
                $calendarData['tagName'] = 'TODAY';
            }
            else {
                if($showUpcoming == 0) {
                    $showUpcoming = 1;
                    $calendarData['showUpcoming'] = true;
                    $upcomingDate = $date['timestamp'];
                }
                if($upcomingDate == $date['timestamp']) {
                    $calendarData['showUpcoming'] = true;
                }
            }
            $calendarData['counter'] = $counter;
            $calendarData['maxCount'] = count($sortedAndExpandedImportantDates);
            $counter++;
            $this->load->view('mobile_examPages5/widgets/examPageCalenderWidget',$calendarData); 
    }
?>

