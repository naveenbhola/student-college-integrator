<div class="ss bar-line-col" style="position: relative; <?=(!empty($marginTopStyle)) ? $marginTopStyle : '';?>"> 
    <div class="bar-line">
        <?php 
        $importantDates = $importantDatesData['importantDates'];
        $source = $importantDatesData['source'];
        $monthGroups = array();
        $upcomingShown = false;
        foreach ($importantDates as $index => $date) { 
            $upcomingClass = '';
            if(empty($date['showUpcoming']) && !$upcomingShown){ 
                $upcomingClass = 'disabled-date';
            } 
            ?>
            <div class="circle-block">
            <?php 
                $backgroundColor = '';
                if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                    $backgroundColor = 'style="background:#ef5552;"';
                }
            ?>
                <div <?=$backgroundColor;?> class="circ <?=$upcomingClass;?>"></div>
                <div class="l-cnt">
                    <p class="<?=$upcomingClass;?>">
                        <?php 
                        $formattedDate = getFormattedDate($date);
                        $dateComp = explode(',',$formattedDate);
                        if(count($dateComp) <= 2){
                            echo $dateComp[0].'<span> ,'.$dateComp[1].'</span>';
                        }
                        else{
                            $middleArr = explode('-',$dateComp[1]);
                            echo $dateComp[0].'<span> ,'.$middleArr[0].'</span> - ';
                            echo $middleArr[1];
                            echo '<span> ,'.$dateComp[2].'</span>';
                        }
                        ?>
                    </p>
                </div>
                <div class="r-cnt">
                    <p class="<?=$upcomingClass;?>"><?=$date['event_name'];?></p>
                    <?php 
                    if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                        $upcomingShown = true; ?>   
                        <span class="upcoming-tap">Upcoming</span>
                        <?php }
                        ?>
                    </div>
                </div>
                <?php }
                ?>
    </div>
    <?php
    if($showImportantViewMore) { 
        if($pageName == 'Admission')
        {
            $gaAttr = "ga-attr= 'VIEW_ALL_IMP_DATES'";
        }
		else
		{
			$gaAttr = "ga-attr='IMPDATES_VAD_TOP_COURSEDETAIL_MOBILE'";
		}
        ?>
        <a class="link-blue-medium  v-more" id="importantDatesViewAll" <?=$gaAttr;?>>View all dates</a>
    <?php } ?>
</div> 
