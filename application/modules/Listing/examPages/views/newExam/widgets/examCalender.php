<?php
if(empty($calendarWidgetData)){
    $message = 'No Upcoming Events';
    }
else{
    $message = "Upcoming dates related to ".$categoryName." exams";
}?>
<div class="card__right ps__rl global-box-shadow">
     <div class="new__shadow"><h3 class="f16__clr3 fnt__sb"><?=$categoryName?> Exams Calendar</h3><p class="f12__clr6 mt_6"><?=$message?></p> <a href="<?=$calendarWidgetLink?>" target="_blank" class="ps__abs sm__btn" ga-attr="VIEW_ALL_EXAM_CALENDER">View All</a></div>
     <div class="fixed__height">
          <ul class="exam__cal">
            <?php  foreach ($calendarWidgetData as $key => $value) { ?>
            <li>
              <?php $keyWithDates = explode('-',$key);
                    if($keyWithDates[0] == $keyWithDates[1]){
                      $dateRange = $keyWithDates[0];
                    }else{
                      $dateRange = $key;
                    }?>
                 <p class="f14__clr3 fnt__sb"><?=$dateRange?></p>
                 <?php foreach ($value as $key1 => $val1) { 
			$year = (!empty($val1['year']))?' '.$val1['year']:'';
		 ?>
                 <p class="f14__clr6"><a href="<?=$val1['url']?>" target="_blank"><?php echo $val1['title'].$year;?></a><?php echo ' : '; echo $val1['description'];?></p>
            <?php } ?>
              </li>
              <?php } ?>
          </ul>
     </div>
</div>
