<div class="popupovrlay eventSubscriptionLayer eventSubscriptionSuccessLayer eventReminderSuccessLayer" onclick="hideSubscriptionLayer();"></div>
<!--   google claneder popup -->
<div class="cal-popup <?=(is_array($validateuser[0]) && !empty($validateuser[0]) && $validateuser[0]['userid']>0)?'ptyp1':'ptyp2'?>  ptyp3 ptyp4 eventSubscriptionLayer">
   <h2><i class="calsprite ic-alarm"></i>Set Alerts<a class="closePopUpEv2" href="javascript:void(0);" onclick="hideSubscriptionLayer();"><i class="calsprite ic-close2x"></i></a></h2>
   <div class="cal-popup-cont">
       <p>Select Exams to get alerts of important dates in your inbox </p>
       <div class="cal-grp2L">
        <h3 class="txtTyp1">Select :  </h3>
        <!--div class="cal-dropdown1">
            <i class="calsprite ic-downarw easeall3s"></i>
            <span id="subscribe_stream">Stream</span>
            <ul id="subscribe_streams">
                <li><a>Engineering</a>
                </li>
                <li><a>MBA</a>
                </li>
            </ul>
        </div-->
        <div class="cal-dropdown2">
            <i class="calsprite ic-downarw easeall3s"></i>
            <span id="_subscribeSelectedExam">Select Exams</span>
            <ul class="xam" id="subscribe-exam-list" style="height:260px;overflow-y:auto;">
                <li>
                    <a href="javascript:void(0);">
                        <div class="cal-checkBx" refId="subscribe-all-exams">
                            <input type="checkbox" id="subscribe-all" value="subscribe-all" class="c-input" name="subscribe_allExams" />
                            <label for="subscribe-all" class="c-heck"><i class="calsprite ic-checkdisable"></i>All</label>
                        </div>
                    </a>
                </li>
                <?php if(count($examNameList)>0){foreach($examNameList as $key=>$examTitle){?>
                <li>
                    <a href="javascript:void(0);" title="<?php echo $examTitle;?>">
                        <div class="cal-checkBx" refId="<?=$key;?>">
                            <input type="checkbox" id="subscribe-<?=$key;?>" value="<?=$examTitle;?>" class="c-input subscribe_examList" name="subscribe_examList[]" <?=(in_array($examTitle, $userSubscribedExams))?'checked="checked"':''?>/>
                            <label for="subscribe-<?=$key;?>" class="c-heck"><i class="calsprite ic-checkdisable"></i><?=$examTitle;?></label>
                        </div>
                    </a>
                </li>
                <?php }}?>
            </ul>
        </div>
    </div>
      <p class="errormsgp" id="calMsgBox">Please select atleast 1 exam.</p>                        
   </div> 
   <?php
   if(is_array($validateuser[0]) && !empty($validateuser[0]) && $validateuser[0]['userid']>0)
   {
    ?>
        <div class="popup-fotr">
            <span><a class="cal-grybtnDrk" href="javascript:void(0);" onclick="hideSubscriptionLayer();">Cancel</a>
            <a class="cal-bluebtn" href="javascript:void(0);" id="getAlertButton" onclick="submitUserSubscription(190);">Save</a></span>
        </div>
    <?php
   }
   else
   {
    ?>
     <div class="popup-fotr">
        <a href="javascript:void(0);" onclick="submitUserSubscriptionAlreadyExist();">Existing users, Login</a>
        <span><a class="cal-grybtnDrk" href="javascript:void(0);" onclick="hideSubscriptionLayer();">Cancel</a>
        <a class="cal-bluebtn" href="javascript:void(0);" id="getAlertButton" onclick="submitUserSubscription(190);">Sign up to Save</a></span>
        <p class="clr"></p>
     </div>
    <?php
   }
   ?>
</div>                
<!--   google claneder popup ends -->
