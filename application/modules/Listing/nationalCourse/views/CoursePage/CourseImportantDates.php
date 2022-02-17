<?php 
    $importantDates = $importantDatesData['importantDates'];
    $source = $importantDatesData['source'];
    $monthGroups = array();
    $upcomingShown = false;
    foreach ($importantDates as $index => $date) {
        ?>
        <li>
            <?php 
                if($source == 'page'){
                    if(!in_array($date['start_month'].$date['start_year'],$monthGroups)){
                        $monthGroups[] = $date['start_month'].$date['start_year'];
                        ?>
                        <label><?php echo date('M Y',strtotime('1-'.$date['start_month'].'-'.$date['start_year'])); ?></label>
                        <?php
                    }
                }
            ?>
            <div class="<?php echo ($index == count($importantDates)-1) ? 'last-brd':''; ?> dat-info <?php echo $date['showUpcoming'] ? '':'inactive'; ?>">
                <strong>
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
                </strong>
                <p>
                    <?php 
                        echo $date['event_name'];
                        if(!empty($date['showUpcoming']) && !$upcomingShown){
                            $upcomingShown = true;
                            ?>
                            <span class="upc-tag">UPCOMING</span>
                            <?php
                        }
                    ?>
                </p>
            </div>
        </li>
        <?php
    }
?>

