<?php  
	foreach($coursesData as $resultdata) {
	    $result=$resultdata['result'];
            $course=$resultdata['course']; 
	    $institute=$resultdata['institute']; 
	    $instituteRep=$resultdata['result']['instituteRep'];
            $url=$resultdata['url']; 
		 
                        $displayString = '';
                        if(count($result['data']) > 0){
                                $image = getSmallImage($result['data'][0]['imageURL']);
                                $displayname = $result['data'][0]['displayName'];
                                if($result['data'][0]['badge']=='CurrentStudent'){
                                        $badge = 'Current Student';
                                        $displayString .= "Hi, I am <a href='#'>$displayname</a>. I am a Current student of ".$course->getName()." course.";
                                }
                                else if($result['data'][0]['badge']=='Alumni'){
                                        $badge = 'Alumni';
                                        $displayString .= "Hi, I am <a href='#'>$displayname</a>. I am an Alumni of ".$course->getName()." course.";
                                }
                                else{
                                        $badge = 'Official';
                                }

                                if($image==''){
                                        $image = getSmallImage('/public/images/photoNotAvailable.gif');
                                }

                        }
                        else if($instituteRep == 'true'){
					$instInfo = getInstituteOfficial($course,$institute,$currentLocationId,$result);
					$displayname = $instInfo['displayname'];
					$badge = $instInfo['badge'];
					$image = $instInfo['image'];
                        }
                        else{
                               $image = $institute->getMainHeaderImage()->getThumbURL();
					$displayname = 'Academic Counsellor';
					$badge = 'Official';	
	                       if($image==''){
                                        $image = '/public/images/avatar.gif';
                               }

                        }

                ?>
                           <li onclick="window.location.href='<?php echo $url."#connect-wrapp";?>'" >
                                <h3 class="course-title">Course: <span class="link-color"><?=$course->getName()?></span></h3>
                                <?php if(count($result['data']) > 1 || ($instituteRep == 'true' && $displayname!='Academic Counsellor')){ ?><div class="flLt student-cols"><?php } ?>
                                <?php if($displayname=='Academic Counsellor'): ?>
                                     <div class="clear-width" style="color: #787878">
                                        Have a question about <?=$course->getName(); ?> , <?=$institute->getName(); ?>?<br/>Ask our Career Counselors.
                                     </div>
                                <?php endif;?>
                                <?php if($displayname!='Academic Counsellor') {?>
                                <div class="clear-width">
                                <div class="student-figure"><img src="<?=$image;?>" alt=""  /></div>
                                <div class="discussion-details">
                                    <div class="student-name">
					<strong><?=$displayname?></strong>
					<span class="blue-btn"><?=$badge?></span>
				   </div>
                                <?php //if($education) { ?>
				    <p class="student-details">
                                        <!--<strong>Course:</strong>-->
                                        <span style="margin-left: 0px;"><?=$course->getName()?></span>
                                    </p>
				<?php //} ?>
                                </div>
                                </div>
                                <?php }?>
                                <a class="white-btn" href="javascript:void(0);" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }">Ask your Question</a>
                                 <?php if(count($result['data']) > 1 || ($instituteRep == 'true' && $displayname!='Academic Counsellor')){ ?></div><?php } ?>
                          <?php if(count($result['data']) < 1) { ?></li> <?php } ?>
                       

                <?php
                        if(count($result['data']) > 1){
                                $image = getSmallImage($result['data'][1]['imageURL']);
                                $displayname = $result['data'][1]['displayName'];
                                if($result['data'][1]['badge']=='CurrentStudent')
                                        $badge = 'Current Student';
                                else if($result['data'][1]['badge']=='Alumni')
                                        $badge = 'Alumni';
                                else
                                        $badge = 'Official';
                                $displayString .= " I am joined by <a href='#'>$displayname</a>";
                                if($image==''){
                                        $image = getSmallImage('/public/images/photoNotAvailable.gif');
                                }

                                ?>
                                    <div class="flLt student-cols">
                                    <div class="student-figure"><img src="<?=$image?>" alt=""  /></div>
                                    <div class="discussion-details">
                                        <div class="student-name">
					<strong>
						<?=$displayname?>
					</strong>
						<span class="blue-btn"><?=$badge?></span>
					</div>
                      		<?php //if($educationDetails)  { ?>               
                                     <p class="student-details">
                                            <!--<strong>Course:</strong>-->
                                            <span style="margin-left: 0px;"><?=$course->getName()?></span>
                                        </p>
				<?php //} ?>
                                    </div>
                                    <a class="white-btn" href="javascript:void(0)" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }">Ask your Question</a>
                                    </div>
                         <?php   if(count($result['data']) < 2){ ?></li><?php } ?>
                                <?php
                        }
                ?>
			<?php
                                if((count($result['data']) ==1 || count($result['data']) ==  2) && $instituteRep == 'true' && $displayname!='Academic Counsellor'){
                                        $instInfo = getInstituteOfficial($course,$institute,$currentLocationId,$result);
                                        $displayname = $instInfo['displayname'];
                                        $badge = $instInfo['badge'];
					$image = $instInfo['image'];
				?>
				<div class="flLt student-cols <?php if(count($result['data'])==2){ ?> mt30 <?php }?>">
                                    <div class="student-figure"><img src="<?=$image?>" alt=""  /></div>
                                    <div class="discussion-details">
                                        <div class="student-name">
                                        <strong>
                                                <?=$displayname?>
                                        </strong>
                                                <span class="blue-btn"><?=$badge?></span>
                                        </div>
                                <?php //if($educationDetails)  { ?>
                                     <p class="student-details">
                                            <!--<strong>Course:</strong>-->
                                            <span style="margin-left: 0px;"><?=$course->getName()?></span>
                                        </p>
                                <?php //} ?>
                                    </div>
                                    <a class="white-btn" href="javascript:void(0)" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }">Ask your Question</a>
                                    </div>
				</li>
                                <?php
                        }
                ?>

                <?php
                        if(count($result['data']) > 2){
                                $image = getSmallImage($result['data'][2]['imageURL']);
                                $displayname = $result['data'][2]['displayName'];
                                if($result['data'][2]['badge']=='CurrentStudent')
                                        $badge = 'Current Student';
                                else if($result['data'][2]['badge']=='Alumni')
                                        $badge = 'Alumni';
                                else
                                        $badge = 'Official';
                                $displayString .= " and <a href='#'>$displayname</a>";
                                if($image==''){
                                        $image = getSmallImage('/public/images/photoNotAvailable.gif');
                                }

                                ?>
                                <div class="clearFix"></div>
                               <div class="flLt student-cols mt30">
                                <div class="student-figure"><img src="<?=$image?>" alt="" /></div>
                                <div class="discussion-details">
                                    <div class="student-name">
					<strong><?=$displayname?> </strong>
					<span class="blue-btn"><?=$badge?></span>
				   </div>
                                    <p class="student-details">
                                        <!--<strong>Course:</strong>-->
                                        <span style="margin-left: 0px;"><?=$course->getName()?></span>
                                    </p>
                                </div>
                                <a class="white-btn" href="javascript:void(0);" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }">Ask your Question</a>
                               </div>
                            </li>
			<?php } ?>
                               
        <?php } ?> 



<?php 
function getInstituteOfficial($course,$institute,$currentLocationId,$result){
				$image = $institute->getMainHeaderImage()->getThumbURL();
                                $displayname = $result['official'][0]['displayName'];

                                $locations = $course->getLocations();
                                $location = $locations[$currentLocationId];
                                $getInstituteLocation = false;
                                if(!$location){
                                        $getInstituteLocation = true;
                                }
                                else{
                                        $contactDetail = $location->getContactDetail();
                                        if($contactDetail->getContactPerson()){
                                                 $displayname = $contactDetail->getContactPerson();
                                        }
                                        else{
                                                $getInstituteLocation = true;
                                        }
                                }
                                if($getInstituteLocation){      //Check for Institute contact person
                                                        $locations = $institute->getLocations();
                                                        $location = $locations[$currentLocationId];
                                                        $contactDetail = $location->getContactDetail();
                                                        if($contactDetail->getContactPerson()){
                                                                $displayname = $contactDetail->getContactPerson();
                                                        }
                                                        else{
                                                                $displayname = "Academic Counsellor";
                                                        }
                                        }
                                $badge = 'Official';
                                if($image==''){
                                        $image = '/public/images/avatar.gif';
                                }
				$res['badge'] = $badge;$res['displayname'] = $displayname;$res['image']=$image;
				return $res;				
}
?>
