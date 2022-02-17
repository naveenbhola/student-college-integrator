<?php
$pgFlag = false;
if(!empty($courseLevel1))
{
    if($courseLevel1 == 'PhD' || $courseLevel1 == 'PG')
    {
        $pgFlag = true;
    }
}
$classValues = array(
    "11"=>"11<sup>th</sup> or earlier",
    "12"=>"12<sup>th</sup>",
    "12 done"=>"12<sup>th</sup> completed"
);
?>
<div class="flot_col clear_max">
    <h3 class="title_of">Educational Information</h3>
    <div class="split-divs">
    <?php
    if($pgFlag)
    {
    ?>    
        <div class="divide_col">
            <div class="edu_info">Graduation Stream</div>
            <div class="edu_name"><?php echo empty($educationInfoProfile['PG']['stream'])? '-':ucwords(strtolower($educationInfoProfile['PG']['stream']));?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Graduation Percentage</div>
            <div class="edu_name"><?php echo empty($educationInfoProfile['PG']['marks'])? '-':$educationInfoProfile['PG']['marks']."%";?></div>
        </div>
    <?php 
    }else{
    ?>
        <div class="divide_col">
            <div class="edu_info">Current School Name</div>
            <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['CurrentSchoolName'])? '-':ucwords(strtolower($educationInfoProfile['UG']['CurrentSchoolName']));?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Current Class</div>
            <div class="edu_name"><?php echo $classValues[$educationInfoProfile['UG']['CurrentClass']];?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Class Xth Board</div>
            <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['tenthBoard'])? '-':$educationInfoProfile['UG']['tenthBoard'];?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Class Xth <?php echo empty($educationInfoProfile['UG']['marksHeading'])? 'CGPA':$educationInfoProfile['UG']['marksHeading'];?></div>
            <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['tenthMarks'])? '-':$educationInfoProfile['UG']['tenthMarks'];?></div>
        </div>
    <?php 
    }
    ?>
        <div class="divide_col">
            <div class="edu_info">SA Exam Score</div>
            <div class="edu_name">
                <ul class="exam_list">
                    <?php
               if(empty($exams))
               {
                   ?>
                   <li><a>-</a></li>
                   <?php
               }
               else
               {
                   $examCount = count($exams);
                   $countField = 0;
                   foreach($exams as $exam)
                   {
                       $examsStr = $exam['name'].': '.$exam['marks'];
                       $countField++;
                       if($countField < $examCount)
                       {
                           $examsStr .= ',';
                       }
                       ?>
                       <li><?php echo $examsStr;?></li>
                       <?php
                   }
               }
               ?>
                </ul>
            </div>
        </div>
    </div>
</div>