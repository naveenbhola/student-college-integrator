<?php
if(empty($calendarWidgetData)){
    $message = 'No Upcoming Events';
    }
else{
    $message = "Upcoming dates related to ".$categoryName." exams";
}?>
<section>
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6"><?=$categoryName?> Exams Calendar <span class="f12 block font-w4 color-6 "><?=$message?></span></h2>
                <div class="card-cmn color-w f14 color-3">
                    <ul class="cls-ul ins-acc-ul f13">
                        <?php  foreach ($calendarWidgetData as $key => $value) { ?>
                            <li>
                            <?php $keyWithDates = explode('-',$key);
                            if($keyWithDates[0] == $keyWithDates[1]){
                                $dateRange = $keyWithDates[0];
                            }else{
                                $dateRange = $key;
                            }?>
                            <p class="color-3 font-w6 f14"><?=$dateRange?></p>
                            <?php foreach ($value as $key1 => $val1) { 
				$year = (!empty($val1['year']))?' '.$val1['year']:'';
				?>
                            <p><a href="<?=$val1['url']?>"><?php echo $val1['title'].$year;?></a><?php echo ' : ';echo $val1['description'];?></p>
                            <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="btn-sec">
                        <a href="<?=$calendarWidgetLink?>" data-vars-event-name="EVENT_CALENDAR_VIEW_ALL" class="btn btn-secondary color-w color-b f14 font-w6 m-15top  ga-analytic">View All</a>
                    </div>
                </div>
              </div>
          </section>
