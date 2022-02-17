<?php if(isset($instituteObjects) && $instituteObjects!=''){?>
<p class="recomnded-h1" id="addToCompareLayerRecoHeading">Select from recommended colleges</p>
       <div class="recmnded-colg-slist" id="addToCompareLayerRecoData">
        <ul>
        <?php
        foreach ($instituteObjects as $institute) {
                $cityLocation = $courseInfo[$institute->getId()]['cityName'];
                $courseId     = $courseInfo[$institute->getId()]['courseId'];
        ?>
           <li onclick="trackEventByGAMobile('MOBILE_COLLEGE_SELECTION_RECOMMENDATION_FROM_COMPARE');"><a href="javascript:void(0);" class="chooseFromRecoCollegeForCompare" flagShipCourse="<?php echo $courseId;?>" ><?php echo $institute->getName();?><span class="locality"><i class="msprite cc-locality"></i><?php echo $cityLocation;?></span></a></li>
        <?php
        }
        ?>
    </ul>
</div>
<?php }?>