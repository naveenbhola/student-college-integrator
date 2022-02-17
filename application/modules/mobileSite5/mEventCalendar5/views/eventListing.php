<section class="cale-outer">
    <div class="cale-inner">
        <!--month start-->
        <?php
        $eventMonths = array();$eventDate = array();$currentMonth = '';
        $showSocialShare = false;
        foreach($eventListData as $index=>$eventList) {
            $eventStartDate = $eventList['start'];
            $yearOfEvent = date('Y',strtotime($eventStartDate));
            $monthOfEvent = date('m',strtotime($eventStartDate));
            $dayOfEvent = date('d',strtotime($eventStartDate));
            $shortMonthOfEvent = date('M',strtotime($eventStartDate));
            $nameOfDay = date('D',strtotime($eventStartDate));
            if($currentMonth!= $monthOfEvent && $currentMonth!=''){ ?>
                </div>
                <?php } ?>
            <?php
            if(!in_array($monthOfEvent,$eventMonths)){
                $currentMonth = $monthOfEvent;
                $eventMonths[] = $monthOfEvent;
            ?>

                <div class="cale-mnlst">
                <?php
                if($showSocialShare)
                {
                ?>
                    <div class="dwnShreBx">
                        <div class="cale-widg cale-widg1" onclick="toggleShareBoxAndOverlay('show');">
                            <span>
                                <i class="cale-sprite cl-nShare1"></i>
                                <p>Share this<br>calendar with friends</p>
                            </span>
                            <a class="widgBtns1"><i class="cale-sprite cl-nShare2"></i>Share</a>
                            <b class="clr"></b>
                        </div>
                        <!-- Will be uncommented when download functionality comes.  -->
                        <!--div class="cale-widg cale-widg2">
                            <span>
                                <i class="cale-sprite cl-nDown1"></i>
                                <p>Get this calendar<br>on your mobile</p>
                            </span>
                            <a class="widgBtns1"><i class="cale-sprite cl-nDown2"></i>Download</a>
                            <b class="clr"></b>
                        </div-->
                    </div>
                <?php
                }
                $showSocialShare = true;
                ?>
                <h2 class="cale-mName"><?php echo date('F', strtotime("2000-$monthOfEvent-1"))." ".$yearOfEvent;?></h2>
            <?php
            }
            ?>
           <!--no event-->
            <!--<div class="cale-mDay cale-noEvents"><p>Sunday, 8 Mar<b>No events</b></p><a>+  Add Event</a></div>-->
            <!--no event -->
            <!--event date-->
            <?php
            if(!in_array($eventStartDate,$eventDate)){
                $eventDate[] = $eventStartDate;    
            ?>
                <div class="cale-mDay" id="<?php echo $eventStartDate;?>"><p><strong><?php echo $nameOfDay.', '.$dayOfEvent.' '.$shortMonthOfEvent; ?></strong>  <b>|</b> <i><?php echo $eventCount[$eventStartDate];?> Event<?php if($eventCount[$eventStartDate]>1){ echo 's';} ?></i></p><!--<a>+  Add Event</a>--></div>
            <?php } ?>
            <ul class="cale-mEvent">
                <li class="<?php if($eventList['eventType']=='examPageEvent'){ echo "eVntTyp1";}else{ echo "eVntTyp2";};?>"><a href="javascript:void(0);" onclick="toggleEventTuple('<?php echo $index;?>')">
                        <h3><?php echo (strlen($eventList['fullTitle'])>47)? substr($eventList['fullTitle'],0,44).'...':$eventList['fullTitle'];?></h3>
                            <p id="descHalf-<?php echo $index;?>"><?php echo (strlen($eventList['fullDescription'])>65)? substr($eventList['fullDescription'],0,62).'...':$eventList['fullDescription'];?></p>
                            <p id="descFull-<?php echo $index;?>" class="eventDetailTuple eventDetailTuple-<?php echo $index;?>"><?php echo $eventList['fullDescription'];?></p>
                    </a>
                    <?php
                    $cookieValue  = $_COOKIE['eventReminderSuccess'];
                    $cookieValArr = explode('@#@', $cookieValue);
                    $showSuccessMsg = '';
                    if(!empty($cookieValArr) && $eventList['start'] == $cookieValArr[0] && $eventList['fullTitle'] == $cookieValArr[1])
                    {
                        $showSuccessMsg = 'style="display:block !important"';
                        setcookie('eventReminderSuccess','',time()-3600,'/',COOKIEDOMAIN);
                    }
                    ?>
                    <p id="sucessRemindr-<?php echo $index;?>" <?php echo $showSuccessMsg;?> class="sucessRemindr">Your reminder has been saved.</p>
                    <?php
                    $borderCss = 'border-right: none !important;';
                    if($eventList['eventType'] == 'customEvent' && $eventList['ownerId'] == $userId)
                    {
                        $borderCss = '';
                    }
                    if($eventList['start'] > date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))))
                    {
                    ?>
                    <div class="eventDetailTuple eventDetailTuple-<?php echo $index;?> cale-toglBx">
                            <?php
                            if($eventList['start'] == $eventList['originalStart'])
                            {
                            ?>
                            <a href="#smsReminderLayer" style="padding: 0px; display: inline-block;" data-transition="slide" data-rel="dialog" data-inline="true" onclick="prepareSetReminderUserInterface('<?php echo $eventList['originalStart'];?>', '<?php echo $eventList['fullTitle'];?>', '<?php echo $eventList['fullDescription'];?>', '<?php echo $eventList['eventType'];?>', '<?php echo $index;?>', '<?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'yes':'no');?>');"><span class="goglBxRemindr" style="<?php echo $borderCss;?>">
                            <i id="bellReminderIcon-<?php echo $index;?>" class="cale-sprite <?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'cl-alarm1':'cl-alarm2');?>"></i>
                            <span id="bellReminderTxt-<?php echo $index;?>" style="border-right: none !important;"><?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'Reminder is set.':'Set SMS Reminder');?></span>
                            </span>
                            </a>
                            <?php
                            }
                            else
                            {
                            ?>
                            <a href="#smsReminderLayer" style="padding: 0px; display: inline-block;" data-transition="slide" data-rel="dialog" data-inline="true" onclick="prepareSetReminderUserInterface('<?php echo $eventList['originalStart'];?>', '<?php echo $eventList['fullTitle'];?>', '<?php echo $eventList['fullDescription'];?>', '<?php echo $eventList['eventType'];?>', '<?php echo $index;?>', '<?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'yes':'no');?>');"><span class="goglBxRemindr" style="<?php echo $borderCss;?>">
                            <i id="bellReminderIcon-<?php echo $index;?>" class="cale-sprite <?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'cl-alarm1':'cl-alarm2');?>"></i>
                            <span id="bellReminderTxt-<?php echo $index?>" style="border-right: none !important;"><?php echo (isset($userSetReminders[$eventList['originalStart']][$eventList['fullTitle']])?'Reminder is set.':'Set SMS Reminder');?></span>
                            </span>
                            </a>
                            <?php
                            }
                            ?>
                            <?php
                            if($eventList['eventType'] == 'customEvent' && $eventList['ownerId'] == $userId && $eventList['start'] == $eventList['originalStart'])
                            {
                            ?>
                                <a href="#userAddEvent_layerContainer" onclick="editCustomEvent('<?php echo$eventList['eventId']?>', '<?php echo $eventList['fullTitle']?>', '<?php echo $eventList['fullDescription']?>', '<?php echo $eventList['start']?>', '<?php echo $eventList['eventEndDate']?>');" data-transition="slide" data-rel="dialog" data-inline="true" style="padding: 0px; display: inline-block;"><span class="goglBxEdit">Edit</span></a>
                                <a href="javascript:void(0);" onclick="deleteCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $examFilter['canonicalUrl']?>','<?php echo $eventList['fullTitle']?>');" style="padding: 0px; display: inline-block;"><span class="goglBxDelete">Delete</span></a>
                            <?php
                            }
                            else if($eventList['eventType'] == 'customEvent' && $eventList['ownerId'] == $userId)
                            {
                            ?>
                                <a href="#userAddEvent_layerContainer" onclick="editCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $eventList['fullTitle']?>', '<?php echo $eventList['fullDescription']?>', '<?php echo $eventList['originalStart']?>', '<?php echo $eventList['eventEndDate']?>');" data-transition="slide" data-rel="dialog" data-inline="true" style="padding: 0px; display: inline-block;"><span class="goglBxEdit">Edit</span></a>
                                <a href="javascript:void(0);" onclick="deleteCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $examFilter['canonicalUrl'];?>','<?php echo $eventList['fullTitle']?>');" style="padding: 0px; display: inline-block;"><span class="goglBxDelete">Delete</span></a>
                            <?php
                            }
                            ?>
                    </div>
                    <?php }else{ ?>
                     <div class="eventDetailTuple eventDetailTuple-<?php echo $index?> cale-toglBx">
                            <a  style="cursor: default;padding: 0px;display: inline-block;" href="javascript:void(0);" data-transition="slide" data-rel="dialog" data-inline="true" ><span class="goglBxRemindr disbSetRemdr" style="<?php echo $borderCss?>">
                            <i id="bellReminderIcon-<?php echo $index?>" class="cale-sprite cl-alarm2"></i>Set SMS Reminder<br>
<b class="evntExpird">(Event Expired)</b></span>
                            </a>
                            <?php
                            if($eventList['eventType'] == 'customEvent' && $eventList['ownerId'] == $userId && $eventList['start'] == $eventList['originalStart'])
                            {
                            ?>
                                <a href="#userAddEvent_layerContainer" onclick="editCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $eventList['fullTitle']?>', '<?php echo $eventList['fullDescription']?>', '<?php echo $eventList['start']?>', '<?php echo $eventList['eventEndDate']?>');" data-transition="slide" data-rel="dialog" data-inline="true" style="padding: 0px; display: inline-block;"><span class="goglBxEdit">Edit</span></a>
                                <a href="javascript:void(0);" onclick="deleteCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $examFilter['canonicalUrl'];?>','<?php echo $eventList['fullTitle']?>');" style="padding: 0px; display: inline-block;"><span class="goglBxDelete">Delete</span></a>
                            <?php
                            }
                            else if($eventList['eventType'] == 'customEvent' && $eventList['ownerId'] == $userId)
                            {
                            ?>
                                <a href="#userAddEvent_layerContainer" onclick="editCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $eventList['fullTitle']?>', '<?php echo $eventList['fullDescription']?>', '<?php echo $eventList['originalStart']?>', '<?php echo $eventList['eventEndDate']?>');" data-transition="slide" data-rel="dialog" data-inline="true" style="padding: 0px; display: inline-block;"><span class="goglBxEdit">Edit</span></a>
                                <a href="javascript:void(0);" onclick="deleteCustomEvent('<?php echo $eventList['eventId']?>', '<?php echo $examFilter['canonicalUrl'];?>','<?php echo $eventList['fullTitle']?>');" style="padding: 0px; display: inline-block;"><span class="goglBxDelete">Delete</span></a>
                            <?php
                            }
                            ?>
                        </div>
                    <?php }?>
                        </li>
            </ul>         
            
            <?php } ?>
            <div class="dwnShreBx" style="margin-top:12px !important; margin-bottom:1px !important;">
                <div class="cale-widg cale-widg1" style="margin-bottom:1px !important;" onclick="toggleShareBoxAndOverlay('show');">
                    <span>
                        <i class="cale-sprite cl-nShare1"></i>
                        <p>Share this<br>calendar with friends</p>
                    </span>
                    <a class="widgBtns1"><i class="cale-sprite cl-nShare2"></i>Share</a>
                    <b class="clr"></b>
                </div>
                <!-- Will be uncommented when download functionality comes.  -->
                <!--div class="cale-widg cale-widg2">
                    <span>
                        <i class="cale-sprite cl-nDown1"></i>
                        <p>Get this calendar<br>on your mobile</p>
                    </span>
                    <a class="widgBtns1"><i class="cale-sprite cl-nDown2"></i>Download</a>
                    <b class="clr"></b>
                </div-->
            </div>
                    </div> 
            <!--event date ends-->

        <!--month ends-->
    </div>
</section>
<?php $closestDate = find_closest($eventDate, date("Y-m-d"));?>
<script>
var allEventDates = $.parseJSON('<?php echo json_encode($eventDate); ?>');
var closestDate = '<?php echo $closestDate;?>';
</script>
