<?php
$courseCategoryString = '';
$courseString = '';
$classValues = array(
    "11"=>"11<sup>th</sup> or earlier",
    "12"=>"12<sup>th</sup>",
    "12 done"=>"12<sup>th</sup> completed"
);
if($masterCourse)
{
    $courseCategoryString .= $catName;
}
else
{
    if(!empty($courseLevel))
    {
        $courseCategoryString .= $courseLevel;
    }

    if(!empty($catName))
    {
        if(!empty($courseCategoryString))
        {
            $courseCategoryString .= ' of '.$catName;
        }
        else
        {
            $courseCategoryString .= $catName;
        }
    }
}
if(!empty($courseCategoryString) && !empty($specializationName))
{
    $courseString .= '<strong>'.$courseCategoryString.' <span>in</span></strong> '.$specializationName;
}
elseif(empty($courseCategoryString) && !empty($specializationName))
{
    $courseString .= $specializationName;
}
elseif (!empty($courseCategoryString) && empty($specializationName))
{
    $courseString .= '<strong>'.$courseCategoryString.'</strong>';
}
else
{
    $courseString .='-';
}
if(empty($destinationCountry))
{
    $destinationCountry = array('-');
}
$expOrCurrentClassHeading = '';
$expOrCurrentClassContent = '';
if(!empty($courseLevel1))
{
    if($courseLevel1 == 'PhD' || $courseLevel1 == 'PG')
    {
        $expOrCurrentClassHeading='Work Experience';
        if(!empty($educationInfoProfile['PG']['workExperience']))
        {
            $expOrCurrentClassContent = '<strong>'.$educationInfoProfile['PG']['workExperience'].'</strong>';
        }
    }
    else
    {
        $expOrCurrentClassHeading='Currently Studying in';
        if(!empty($educationInfoProfile['UG']['CurrentClass']))
        {
            $expOrCurrentClassContent = '<strong>'.$classValues[$educationInfoProfile['UG']['CurrentClass']].'</strong>';
        }
        if(!empty($educationInfoProfile['UG']['CurrentSchoolName']))
        {
            $expOrCurrentClassContent .= ucwords(strtolower($educationInfoProfile['UG']['CurrentSchoolName']));
        }
    }
}
if(empty($expOrCurrentClassContent))
{
    $expOrCurrentClassContent = '-';
}

$examOrCollegeHeading = '';
if(!empty($admittedUniversityCountyStr))
{
    $examOrCollegeHeading = $admittedUniversityCountyStr;
}
else
{
    if(!empty($exams))
    {
        $examOrCollegeHeading = $exams[0]['name'].': '.$exams[0]['marks'];
    }
}
?>
<div class="profile_lft">
    <div class="profile_intro clear_max">
        <div class="profile_slct">
            <?php 
            if($selfProfile === true){
            ?>
                <p class="clear_max edit_col"> <a href="<?php echo $editProfilePageUrl; ?>"> <i class="edit_ic"></i> Edit Profile</a> </p>
            <?php 
            }
            ?>
            <div class="user_wrap clear_max">
                <!--pic placeholder-->
                <div class="pic_around clearfix">
                    <div class="pic_col" style="background: url(<?php echo $userAvtar;?>);"></div>
                </div>
                <!--end of pic placeholder-->
                <div class="txt_wrap">
                    <h1 class="user_alias"><?php echo $userName; ?></h1>
                    <p class="rank_state"><span class="<?php echo empty($admittedUniversityCountyStr)? 'aspirent':'admitted'?>"><?php echo empty($admittedUniversityCountyStr) ? $courseLevel1.' ASPIRANT':'ADMITTED'; ?></span> <?php echo $examOrCollegeHeading;?></p>
                </div>
            </div>
        </div>
        <!--profile details column-->
        <div class="alias_dtls clear_max">
            <div class="course_intrst">
                <p class="similar_p">Course Interested in</p>
                <p class="stream"><?php echo $courseString;?></p>
            </div>
            <div class="country_aspire">
                <p class="similar_p">Country Aspiring For</p>
                <div class="abroad_intrst">
                    <ul>
                       <?php
                       $destinationCount = count($destinationCountry);
                        for($i=0;$i< $destinationCount;$i++)
                        {
                            $countryName = $destinationCountry[$i];
                            if($i+1 < $destinationCount)
                            {
                                $countryName .= ',';
                            }
                            ?>
                            <li><?php echo $countryName;?></li>
                            <?php
                        }
                       ?>
                    </ul>
                </div>
            </div>
            <!--work experience-->
            <div class="work_expo">
                <p class="similar_p"><?php echo $expOrCurrentClassHeading; ?></p>
                <p class="schooling"><?php echo empty($expOrCurrentClassContent)?'-':$expOrCurrentClassContent; ?></p>
            </div>
            <!--end of work experience-->
            <!--planning info-->
            <div class="plan_info">
                <p class="similar_p">Planning to go in</p>
                <p class="schooling"><strong><?php echo empty($timeOfStart) ?'-':$timeOfStart; ?></strong></p>
            </div>
            <!--end of planning info-->
        </div>
        <!--end of profile details column-->

    </div>
</div>