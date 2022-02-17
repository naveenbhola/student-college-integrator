<?php

?>
<div class="main_stack">
  <div class="user_control">
      <?php
        if($selfProfile)
        {
            ?>
            <p class="edit_col auto_clear">
                <span><a href="<?php echo $userProfileUrl.'edit'; ?>"><i></i>Edit Profile</a></span>
            </p>
            <?php
        }
      ?>
    <div class="register_dtls auto_clear">
        <div class="img_wrap auto_clear">
           <p class="pic" style="background-image: url('<?php echo $userAvtar;?>')">
           </p>
        </div>
        <div class="content_right">
            <h1 class="user_alias"><?php echo $userName; ?></h1>
            <!-- <p class="rank_state">GMAT: 454, Applied to RMIT University Royal Melbourne Institu...</p> -->
        </div>
    </div>
    <div class="<?php echo empty($admittedUniversityCountyStr) ? 'stream_find': 'stream_pg'; ?>">
        <span><?php echo empty($admittedUniversityCountyStr) ? $courseLevel1.' ASPIRANT':'ADMITTED'; ?></span>
    </div>
  </div>
    <?php
        $examOrCollegeHeading = '';
        if(!empty($admittedUniversityCountyStr))
        {
            $examOrCollegeHeading = $admittedUniversityCountyStr;
        }
        else
        {
            if(!empty($exams))
            {
                $examOrCollegeHeading = $exams[0]['name'].' '.$exams[0]['marks'];
            }
        }
        ?>
    <div class="exam_fill">
        <?php
        if(!empty($examOrCollegeHeading))
        {
            ?>
                <div>
                    <?php echo $examOrCollegeHeading;?>
                </div>
            <?php
        }
        ?>
    </div>

</div>