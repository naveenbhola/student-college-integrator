<?php 
    $mentorDisplayPic = isset($caDetails[0]['ca']['avtarimageurl'])?getMediumImage($caDetails[0]['ca']['avtarimageurl']):'';
?>

    <section class="prf-box-grey" >
        <div class="prft-titl">
            <div class="caption">
                <p> YOUR MENTOR</p>
            </div>
        </div>
        
        <!--profile-tab content-->
        <div class="frm-bdy">
            <div class="prfb-img"> 
                <img id="mentorImage" name="mentorImage" src="<?php if($mentorDisplayPic!='') echo $mentorDisplayPic; else echo SHIKSHA_HOME.'/public/images/photoNotAvailable_m.gif'; ?>" style="height: 100%; width: 100%;"/>
            </div>
            <div class="prfb-dtls">
                <h1 class="prf-n">
                    <a class="pfn" href="<?php echo SHIKSHA_HOME.'/userProfile/UserProfileController/showUserPublicProfile/'.$caDetails[0]['ca']['userId'];?>" style="display:inline-block;text-decoration:none;color:#00a5b6;">
                        <?php echo $caDetails[0]['ca']['displayName'];?>
                    </a>
                    <span> | <?php if($caDetails[0]['ca']['mainEducationDetails'][0]['badge']=='CurrentStudent'){ echo 'Current Student';}?></span>
                </h1>
                <br/>
                <h4 class="pfi"><?php echo $caDetails[0]['ca']['aboutMe'];?></h4>
                
                    
                    <p class="mentor-p">
                        <i class="icons1 ic_clg"></i>
                        <a href="<?php echo $instObj->getUrl();?>" class="mentor-a"><?php echo $courseObj->getInstituteName();?></a>
                    </p>
                    
                
                <p class="mentor-p">
                    <i class="icons1 ic_edu"></i>
                    <a href="<?php echo $courseObj->getUrl();?>"  class="mentor-a"><?php echo $courseObj->getName();?></a></p>
                
               
                    
                    <p class="mentor-p"><i class="icons1 ic_loc"></i> <a href="<?php echo $courseObj->getUrl();?>"  class="mentor-a"><?php echo $courseObj->getMainLocation()->getCity()->getName();?></a></p>
                
            </div>
            <p class="clr"></p>
        </div>
    </section>