<?php $this->load->view('common/calendardiv'); ?>
<?php
//_p($arrayOfCourseObj);
$badgesCourse = array('None'=>'None','CurrentStudent'=>'Current Student','Alumni'=>'Alumni');
$badgesOfficial = array('None'=>'None','Official'=>'Official');
foreach($resultCA as $temp){
    if(is_array($temp['ca'])){
        $storeCourseIds = '';
        if (array_key_exists('mainEducationDetails', $temp['ca'])) { ?>
        <div style="float: left;" >
                        <?php
                        $idandtype = '';
                        foreach($temp['ca']['mainEducationDetails'] as $key=>$value){ ?>
                        <?php $idandtype .= 'main:'.$value['id'].'#';?>
                        <div style="float: left">
                             <div style="float: left"><select class="universal-select" id="<?php echo $temp['ca']['userId'];?>_<?php echo $value['id'];?>_badge"><?php foreach($badgesCourse as $bKey=>$bValue){?> <option value="<?php echo $bKey;?>" <?php if($bKey==$value['badge']){ echo "selected='selected'";} ?>><?php echo $bValue;?></option><?php } ?></select></div>
                             <div style="float: left;margin-left: 5px;">
                             <?php
                            foreach($arrayOfCourseObj as $mainCourseId=>$courseId){
                                    
                                    if(empty($courseId)){?>
                                            <select id="<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>_course">
                                                <option value="<?php echo $value['courseId'];?>">
                                                    <?php echo $value['courseName'];?>
                                                </option>
                                            </select>
                                    <?php }
                                    if($value['courseId']==$mainCourseId){
                                        ?>
                                        <select class="universal-select" id="<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>_course"> 
                                        <?php
                                        foreach($courseId as $cId=>$cObj){ ?>
                                            <option value="<?php echo $cId;?>" <?php if($cId==$mainCourseId){ echo 'selected="selected"';}?>><?php echo $cObj->getName();?></option>
                                        <?php }
                                        ?>
                                        </select>
                                        <?php
                                    }
                             }?>
                             </div>
                             <div style="float: left;">
                                <span class="calenderBox">
                                    <input type="text" value="<?php echo date('d/m/Y',strtotime($value['from']));?>" class="calenderFields"  name="from_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>" id="from_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>"  readonly  />
                                    <a href="#" class="pickDate" title="Calendar" name="from_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>" id="from_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>'),'from_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>','dd/mm/yyyy'); return false;">&nbsp;</a>
                                    <!--<img name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />-->
                                </span>
                               <span class="calenderBox">
                                    <input type="text" value="<?php echo date('d/m/Y',strtotime($value['to']));?>" class="calenderFields"  name="to_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>" id="to_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>"  readonly  />
                                    <a href="#" class="pickDate" title="Calendar" name="to_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>" id="to_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('to_date_main_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>'),'to_date_main_img_<?php echo $temp['ca']['userId'];?>_<?php echo $value['id']?>','dd/mm/yyyy'); return false;">&nbsp;</a>
                                    <!--<img name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />-->
                                </span>
                            </div>
                            
                        </div>
                        
                        <?php
                        } ?>
                        <?php
                        if($temp['ca']['isOfficial']=='Yes'){ ?>
                        <?php $idandtype .= 'official:'.$temp['ca']['id'];?>
                        <div style="float: left;" >
                                        <div style="float: left;">
                                             <div><select class="universal-select" id="<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>_officialbadge"><?php foreach($badgesOfficial as $key=>$value){?> <option value="<?php echo $key;?>" <?php if($key==$temp['ca']['officialBadge']){ echo "selected='selected'";} ?>><?php echo $value;?></option><?php } ?></select></div>
                                        </div>
                                        <div style="float: left;margin-left: 5px;">
                                        <?php
                                       foreach($arrayOfCourseObj as $mainCourseId=>$courseId){
                                               
                                               if(empty($courseId)){?>
                                                       <select class="universal-select" id="<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>_officialcourse">
                                                           <option value="<?php echo $value['courseId'];?>">
                                                               <?php echo $value['courseName'];?>
                                                           </option>
                                                       </select>
                                               <?php }
                                               if($temp['ca']['officialCourseId']==$mainCourseId){
                                                   ?>
                                                   <select class="universal-select" id="<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>_officialcourse">
                                                   <?php
                                                   foreach($courseId as $cId=>$cObj){ ?>
                                                       <option value="<?php echo $cId;?>" <?php if($cId==$mainCourseId){ echo 'selected="selected"';}?>><?php echo $cObj->getName();?></option>
                                                   <?php }
                                                   ?>
                                                   </select>
                                                   <?php
                                               }
                                        }?>
                                    </div>
                                    <div style="float: left;">
                                       <span class="calenderBox">
                                           <input type="text" value="<?php echo date('d/m/Y',strtotime($temp['ca']['officialDateFrom']));?>" class="calenderFields"  name="from_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>" id="from_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>"  readonly  />
                                           <a href="#" class="pickDate" title="Calendar" name="from_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>" id="from_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>'),'from_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>','dd/mm/yyyy'); return false;">&nbsp;</a>
                                           <!--<img name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />-->
                                       </span>
                                      <span class="calenderBox">
                                           <input type="text" value="<?php echo date('d/m/Y',strtotime($temp['ca']['officialDateTo']));?>" class="calenderFields"  name="to_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>" id="to_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>"  readonly  />
                                           <a href="#" class="pickDate" title="Calendar" name="to_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>" id="to_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="javascript:$('genOverlayAnA').style.zIndex='1000';calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('to_date_main_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>'),'to_date_main_img_official_<?php echo $temp['ca']['userId'];?>_<?php echo $temp['ca']['id'];?>','dd/mm/yyyy'); return false;">&nbsp;</a>
                                           <!--<img name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />-->
                                       </span>
                                   </div>
                        </div>
                        <?php
                        }
                        ?>
         </div>
        <?php }    
    }
} ?>
<div>
    <div>
        <textarea></textarea>
    </div>
    <div>
        <input type="text" value="<?php echo rtrim($idandtype,'#');?>" id="relationValues"/>
        <input type="button" value="Update" name="Update" class="orange-button" onclick="updateDataAndSendMailer('<?php echo $userId;?>');"/>
        <input type="button" value="Cancel" name="Cancel" class="orange-button" onclick="hideOverlayAnA();"/>
    </div>
</div>