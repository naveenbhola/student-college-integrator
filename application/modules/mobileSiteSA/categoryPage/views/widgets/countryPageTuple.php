<li>
    <a href="<?php echo $url?>"><strong><?php echo htmlentities($universityName)?></strong></a>
    <span><?php echo empty($city)?"":$city?><?php echo (!empty($city) && !empty($state))?',':''?> <?php echo $state?></span>
    <p class="offered-course-info">
        <?php if ($instituteType1 != '' && $instituteType2 != '') {
            if($instituteType1=='not_for_profit'){
                $instituteType1='Not for Profit';
            }
            echo ucwords($instituteType1)." ".ucwords($instituteType2).", ";
        }
        if ($establishYear != '') {
            echo "Estd. ".$establishYear.", ";
        }
        ?>
        Offering <?php echo $courseCount?> course<?php echo ($courseCount==1)?'':'s'?>
    </p>
    <div class="vwal-crsSec">
    <a id="viewAllCourses" class="btn btn-primary vl-crsBtn" href="<?php echo $url?>" data-rel="dialog" data-transition="slide">View All Courses</a>
    <a id= "downloadBrochure" class="btn btn-primary btn-full mb15 dnd-brchr" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObj))?>',this);" ><span class="vam">Email Brochure</span></a>
    </div>
</li>
