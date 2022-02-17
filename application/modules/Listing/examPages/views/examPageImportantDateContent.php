<?php 
    $sectionInfo = $examPageData->getSectionInfo();
    $sectionNameMapping = $this->config->item('sectionNamesMapping');
    $importantDates = $examPageData->getImportantDates();
    $class = 'clearfix';
?>
<?php if(count($importantDates) == 1 && strtotime($importantDates[0]->getStartDate()) == 0) {
    $class = '';
} ?>
<div class="content-tupple <?php echo $class;?>" id="wiki-sec-0">
    <div class="tupple-title"><?php echo $sectionNameMapping[$pageType]; ?></div>
    <?php if($sectionInfo[$pageType]['description'] == '' && count($importantDates) == 1 && strtotime($importantDates[0]->getStartDate()) == 0) { ?>
	<p>
	Nothing interesting here!
	<br/>
	Go to <a href="<?php echo $sectionUrl['home']['url']?>"> <?php echo $examName;?> homepage </a>
	</p>
<?php } else { ?>
    <p><?php echo $sectionInfo[$pageType]['description'];?></p>
    <div class="calender-blocks">
        <ul>
<!-- Start : Calender Widget  -->
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
                    $this->load->view('examPages/widgets/examPageCalenderWidget',$calendarData); 
            }
        ?>
        </ul>
    </div>
    <?php } ?>
</div>
<!-- End : Calender Widget  -->

<?php $this->load->view("widgets/newsArticleSliderWidget"); ?>

<!-- Start : Registration  -->
<?php 
$tracking_keyid = DESKTOP_NL_EXAM_PAGE_IMP_DATES_TOP_REG;
?>
<?php 
$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 
?>
<!-- End : Registration  -->
<!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->