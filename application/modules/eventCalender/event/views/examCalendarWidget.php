<div class="exam-right-wid" id="exam-rightwidget" style="margin-bottom: 10px;">
            <div class="exam-right-hd">Important Dates</div>
            <?php if(empty($calendarWidgetData)){
                        $message = 'No Upcoming exams';
                        }
            else {
                        $message = 'Upcoming exams';
            }?>
            <p class="exam-right-coming"><?=$message?></p>
            <ul class="exam-right-info">
            <?php
            if(count($calendarWidgetData) >= 3){
                  $calendarWidgetRandomData = array_rand($calendarWidgetData,3);
	    }
            else{
                  $calendarWidgetRandomData = array_rand($calendarWidgetData, count($calendarWidgetData));
	    }

            if(!is_array($calendarWidgetRandomData))
            {
                  $calendarWidgetRandomData = array($calendarWidgetRandomData); 
            }

            foreach($calendarWidgetRandomData as $key){ 
		$year = (!empty($calendarWidgetData[$key]['year']))?' '.$calendarWidgetData[$key]['year']:'';
		?>
            <li>
            <div class="exam-right-name"><a href="<?=$calendarWidgetData[$key]['url']?>" target="_blank"><?=$calendarWidgetData[$key]['title'].$year?></a></div>
            <?php
                        $dateFormatted = date('F d Y', strtotime($calendarWidgetData[$key]['date']));
                        ?>
            <p><span class="exam-right-wid-dt"><?=$dateFormatted?></span><span class="exam-right-st">: <?php if(strlen($calendarWidgetData[$key]['description'])>40) { echo substr($calendarWidgetData[$key]['description'],0,40).'...';}else{ echo $calendarWidgetData[$key]['description'];}?></span></p>
            </li>
            <?php }?>
            </ul>
            <div class="clearfix"></div>
            <?php
            $gaTracking = '';
            if($fromWhere == 'examPageRightSection')
            {
                        $gaTracking = "trackEventByGA('EXAM_CALENDAR_EXAM_PAGES_WIDGET_CLICK','EXAM_CALENDAR_WIDGET_EXAM_PAGES');";
            }
            else if($fromWhere == 'articlePageRight')
            {
                        $gaTracking = "trackEventByGA('EXAM_CALENDAR_ARTICLE_PAGES_WIDGET_CLICK','EXAM_CALENDAR_WIDGET_ARTICLE_PAGES');"; 
            }
            ?>
            <a href="<?=$calendarWidgetLink?>" onclick="<?=$gaTracking?>" target="_blank" class="exam-right-wid-bt">Check Exam Calendar</a>
            
</div>
