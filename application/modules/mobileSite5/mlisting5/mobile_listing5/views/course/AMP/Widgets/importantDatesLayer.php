    <?php 
    $upcomingShown = false;
    $id = 'all';
    if(!empty($examName) && is_array($examName))
    {
        $id = $examName['exam_id'];
        $labelName = $examName['exam_name'];
        $checkedValue = '';
    }
    elseif($examName == 'All')
    {
        $id = 'All';
        $checkedValue = 'checked=true';
    }
    ?>
    <input type="radio" name="types" value="imp_dates_<?=$id?>" id="imp_dates_<?=$id?>" class="hide st" <?=$checkedValue;?>>
    <div  class="table tob1 margin-20">
         <?php if(!empty($labelName)) { ?>
            <p class="color-3 f14 font-w6"><?php echo $labelName;?> Dates</p>
        <?php } ?>

      <div class="bar-line pos-rl margin-20">
                    <?php 
                      foreach($importantDatesData['importantDatesLayer'][$id] as $index => $date) {
                          $upComingCurrent = '';
                          $upcomingClass = '';
                          $disabledBG = '';
                            if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                                $upcomingClass = 'current';
                                $upComingCurrent = 'color-r';
                            }
                             if(empty($date['showUpcoming']) && !$upcomingShown){ 
                                $upcomingClass = 'disable-color';
                                $disabledBG = 'bg-clr-f1';
                            } 
                       ?>
                         <div class="crc-blck">
                             <div class="crc <?=$upcomingClass;?> <?=$disabledBG;?>"></div>
                             <div class="l-cnt i-block v-top"><p class="f12 color-6 font-w6 <?=$upcomingClass;?>">
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
                             </p></div>
                             <div class="r-cnt i-block v-top"><p class="f13 color-6 <?=$upcomingClass;?>"><?=$date['event_name'];?></p>
                              <?php 
                                        if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                                            $upcomingShown = true; ?>   
                                            <span class="f11 color-6 <?=$upComingCurrent;?>">Upcoming</span>
                                            <?php }
                                            ?>
                             </div>
                         </div>
                     <?php } ?>
        </div>
    </div>