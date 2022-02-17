<?php
$widget = "crInst";
$sliderArrMba = array();
$sliderArrEng = array();
for($i=0,$j=0; $i<count($repData['repInfo'])-2;$i=$i+2)
{
        $sliderArrMba[$j] = array($repData['repInfo'][$i], $repData['repInfo'][$i+1]);
        $j++;
}
?>
<div class="com-m-qry com-m-bdr">
        <!--manage tab---->	
        <div class="com-m-qry-tab" id="tabSection">
            <a href="javascript:void(0);" class="com-m-rt-bdr active">Ask Current Students</a>
        </div>
        <p class="com-m-hd">Ask your queries to current students of this college</p>
        <?php if($repData['repInfo']['totalRep'] >0){?>
        <div id="repInfo-section">
        
                <div class="com-m-stu-info flexslider-rep" id="mainList-mba">
                    <div class="com-m-stu-l slide-prev" <?php if($repData['repInfo']['totalRep'] <= 2){?> style="visibility: hidden;" <?php }?>><i class="msprite com-m-l-icon"></i></div><!--mba-prev-->  
                        
                        <?php if($repData['repInfo']['totalRep']==1){?> <!--view for single rep-->    
                        <div class="com-m-stu-lst">
                            <ul class="com-m-info">
                                <?php if($repData['repInfo'][0]['imageURL'] !='' && $repData['repInfo'][0]['displayName'] !='')
						{
						//handling size of the image
						$rData['imageURL'] = substr_replace($repData['repInfo'][0]['imageURL'],"_s.",strrpos($repData['repInfo'][0]['imageURL'],"." ),1);  ?>
                                <li style="width: 100% !important; text-align: center;">
                                        <div class="com-m-user" style=" margin: auto; float: none !important;"><img src="<?php echo $rData['imageURL'];?>" />
                                        <span class="com-m-name"><?php echo substr($repData['repInfo'][0]['displayName'],0,18);?></span></div>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                         <?php } if($repData['repInfo']['totalRep']>1){?> <!--more then one rep-->
                         <div class="com-m-stu-lst flex-viewport">
                            <ul class="com-m-info slides">
                                <?php foreach($sliderArrMba as $mbaData){?>
                                <li>    <?php foreach($mbaData as $rData){
                                        if($rData['imageURL'] !='' && $rData['displayName'] !='')
                                                {
                                                        //handling size of the image
                                                $rData['imageURL'] = substr_replace($rData['imageURL'],"_s.",strrpos($rData['imageURL'],"." ),1);
                                        ?>
                                        <div class="com-m-user"><img src="<?php echo $rData['imageURL'];?>" />
                                                <span class="com-m-name"><?php echo substr($rData['displayName'],0,18);?></span>
                                        </div>
                                        <?php }}?>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                         <?php }?>
                    <div class="com-m-stu-r slide-next" <?php if($repData['repInfo']['totalRep'] <= 2){?> style="visibility: hidden;" <?php }?>><i class="msprite com-m-r-icon"></i></div><!--mba-next-->  
                </div>
                
                <!---answer given count-->
		<?php if($repData['repInfo']['commentCount']>0){?>
		<p class="com-m-ans" id="comments-mba"><?=$repData['repInfo']['commentCount'];?><?php echo ($repData['repInfo']['commentCount'] == 1) ? " Answer" : " Answers"; ?> given</p>
		<?php }?>
                <?php if(count($repData['numberOfCACourses']['mbaCourse']) == 1) {//for mba single course, no dropdown ?>
                        <div class="com-m-selt-bt">
                                <strong class="com-mask-hd-txt">Answers queries for:</strong>
                                <p><?php echo $repData['campusConnectCourses']['mbaCourseObj'][0]->getName(); ?></p>
                                <div style="display: none"><?php echo Modules::run('CA/CADiscussions/getQuestionForm',$repData['campusConnectCourses']['mbaCourseObj'][0]->getId(),$institute_id,true,true,'instituteCampusRep-mba');?></div>
                                <a href="javascript:void(0)" onclick="$('#tracking_keyid').val('<?php echo $trackingPageKeyId?>');$('#form_askQuestion_mba').submit();" class="com-m-qry-bt com-m-bdr">Ask Now</a>
                        </div>
                <?php }else{?>
                        <div class="com-m-selt-bt">
                                <select class="com-m-qry-selt com-m-bdr" caption="course" id="campus_rep_course_<?=$widget?>" name="campus_rep_course_<?=$widget?>" required="true">
					<option selected value="">Select a course</option>
					<?php  foreach($repData['campusConnectCourses']['mbaCourseObj'] as $course)
					{
						echo '<option customurl = "'.$course->getURL().'" value="'.$course->getId().'">'.html_escape($course->getName()).'</option>';
					} ?>
				</select>
				<div class="clearFix"></div>
				
				<div class="errorMsg" id="campus_rep_course_error_mba" style="padding-left:3px; clear:both; display: none;"></div>
                                
                                <div id="mba-anapost-form" style="display: none"></div>
                                <a href="javascript:void(0)" onclick="getAnaFormOnCampusRep('<?php echo $institute_id;?>','campus_rep_course_<?=$widget?>','repInfo-section','mba',this,'<?php echo $trackingPageKeyId;?>');" class="com-m-qry-bt com-m-bdr">Ask Now</a>
                        </div>
                <?php }?>
        
        </div><!--end-mba--->
        <?php }?>
</div>