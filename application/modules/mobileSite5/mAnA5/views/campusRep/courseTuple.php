<?php
$inputId1 = '';
$inputId2 = '';
$inputId3 = '';
?>

	<?php
        $displayString = '';
        $displayCourse = true;
        if($mainCourse){ 
                $courseName = $mainCourse->getName();
        } 
        else{ 
                $courseName= $course->getName();
        }
        $courseName= $course->getName();
        if(count($result['data']) > 0){
                $image = getSmallImage($result['data'][0]['imageURL']);
                $displayname = $result['data'][0]['displayName'];
                if($result['data'][0]['badge']=='CurrentStudent'){
                        $badge = 'CURRENT STUDENT';
                        $displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am a Current student of ".$courseName." course.";
                }
                else if($result['data'][0]['badge']=='Alumni'){
                        $badge = 'ALUMNI';
                        $displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Alumni of ".$courseName." course.";
                }
                else{
                        $badge = 'OFFICIAL';
                        $displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";
                }
                if($image==''){
                        $image = getSmallImage('/public/images/photoNotAvailable.gif');
                }
                
                $inputId1 = $result['data'][0]['userId'].'_'.$badge;                    
        }
        else if($result['instituteRep'] == 'true'){
                $instInfo = getInstituteOfficial($course,$institute,$currentLocation,$courseName);
                $image = $instInfo['image'];
                $displayname = $instInfo['displayname'];
                $badge = $instInfo['badge'];
                $displayString = $instInfo['displayString'];					
        }
        
        ?>

<?php  if($result['totalReps'] >0 || ($result['instituteRep']=='true' && $displayname!='')){ ?>

<section class="content-wrap2 clearfix" >
        <h2 class="ques-title">
            <p id="campusRepTupleHeading">Ask your queries to current students</p>
        </h2>

	<article class="clearfix" style="padding: 1em 0.625em 0.625em;position: relative;" data-enhance="false">
    	
        <p id="campusRepTupleSubHeading">Current students of this college are available to answer your questions. You can view their details below.</p>
        <div class="stu-detail clearfix">
            <div class="stu-image"><img src="<?=$image?>" width="44" height="52" alt="stu-image"></div>
            <div class="stu-info">
                <p><strong><?=$displayname?> </strong><a href="javascript:void(0)" class="current-stu-btn" style="cursor:default"><?=$badge?></a></p>
                <p style="font-size:13px"><?=$courseName?></p>
            </div>
      	</div>

        
        
        <?php
                if(count($result['data']) > 1){
                        $image = getSmallImage($result['data'][1]['imageURL']);
                        $displayname = $result['data'][1]['displayName'];
                        if($result['data'][1]['badge']=='CurrentStudent')
                                $badge = 'CURRENT STUDENT';
                        else if($result['data'][1]['badge']=='Alumni')
                                $badge = 'Alumni';
                        else
                                $badge = 'Official';
                        $displayString .= " I am joined by <span style='color:#0065DE;'>$displayname</span>";
                        if($image==''){
                                $image = getSmallImage('/public/images/photoNotAvailable.gif');
                        }
                        $inputId2 = $result['data'][1]['userId'].'_'.$badge;                	                
        ?>
                <div class="stu-detail clearfix">
                    <div class="stu-image"><img src="<?=$image?>" width="44" height="52" alt="stu-image"></div>
                    <div class="stu-info">
                        <p><strong><?=$displayname?> </strong><a href="javascript:void(0)" class="current-stu-btn" style="cursor:default"><?=$badge?></a></p>
                        <p style="font-size:13px"><?=$courseName?></p>
                    </div>
                </div>        
        <?php
                }
        ?>
        
        
        <?php
                if(count($result['data']) > 2){
                        $image = getSmallImage($result['data'][2]['imageURL']);
                        $displayname = $result['data'][2]['displayName'];
                        if($result['data'][2]['badge']=='CurrentStudent')
                                $badge = 'CURRENT STUDENT';
                        else if($result['data'][2]['badge']=='Alumni')
                                $badge = 'Alumni';
                        else
                                $badge = 'Official';
                        $displayString .= " and <span style='color:#0065DE;'>$displayname</span>.";
                        if($image==''){
                                $image = getSmallImage('/public/images/photoNotAvailable.gif');
                        }
                        $inputId3 = $result['data'][2]['userId'].'_'.$badge;
        ?>
                <div class="stu-detail clearfix">
                    <div class="stu-image"><img src="<?=$image?>" width="44" height="52" alt="stu-image"></div>
                    <div class="stu-info">
                        <p><strong><?=$displayname?> </strong><a href="javascript:void(0)" class="current-stu-btn" style="cursor:default"><?=$badge?></a></p>
                        <p style="font-size:13px"><?=$courseName?></p>
                    </div>
                </div>        
        <?php
                }
        ?>

	<input id="input_1" value="<?php echo $inputId1;?>" style="display:none;" />
	<input id="input_2" value="<?php echo $inputId2;?>" style="display:none;" />
	<input id="input_3" value="<?php echo $inputId3;?>" style="display:none;" />
        <?php
        foreach ($result['data'] as $campusRepUser){
                $userIds .= ($userIds == "")?$campusRepUser['userId']:','.$campusRepUser['userId'];
        }
        ?>
        <input id="input_userList" value="<?php echo $userIds;?>" style="display:none;" />
        
<?php
}
?>




<?php

function getInstituteOfficial($course,$institute,$currentLocation,$courseName){
	$image = $institute->getMainHeaderImage()->getThumbURL();
	$locations = $course->getLocations();
	$location = $locations[$currentLocation->getLocationId()];
	$getInstituteLocation = false;
	if(!$location){
		$getInstituteLocation = true;
	}
	else{
		$contactDetail = $location->getContactDetail();
		if($contactDetail->getContactPerson()){
			$displayname = $contactDetail->getContactPerson();
			$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";
		}
		else{
			$getInstituteLocation = true;
		}
	}
	if($getInstituteLocation){	//Check for Institute contact person
			$locations = $institute->getLocations();
			$location = $locations[$currentLocation->getLocationId()];
			$contactDetail = $location->getContactDetail();
			if($contactDetail->getContactPerson()){
				$displayname = $contactDetail->getContactPerson();
				$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";
			}
			else{
				$displayname = "";
				$displayString .= "Hi, I am an Official of ".$courseName." course.";
			}
	}

	$badge = 'Official';				
	$displayCourse = false;
	if($image==''){
		$image = '/public/images/avatar.gif';
	}
	$res['badge'] = $badge;$res['displayname'] = $displayname;$res['displayString'] = $displayString;$res['image']=$image;
	return $res;
}

?>
