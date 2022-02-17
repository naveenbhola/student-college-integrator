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
<div class="flot_col auto_clear">
   <table class="edu">
     <tr>
       <td colspan="2"><h3 class="title_of">Educational Information</h3></td>
     </tr>
       <?php
        if($pgFlag)
        {
            ?>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Graduation Stream</div>
                </td>
                <td>
                    <div class="edu_name"><?php echo empty($educationInfoProfile['PG']['stream'])? '-':ucwords(strtolower($educationInfoProfile['PG']['stream']));?></div>
                </td>
            </tr>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Graduation Percentage</div>
                </td>
                <td>
                    <div class="edu_name"><?php echo empty($educationInfoProfile['PG']['marks'])? '-':$educationInfoProfile['PG']['marks']."%";?></div>
                </td>
            </tr>
            <?php
        }
        else
        {
            ?>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Current School Name</div>
                </td>
                <td>
                    <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['CurrentSchoolName'])? '-':ucwords(strtolower($educationInfoProfile['UG']['CurrentSchoolName']));?></div>
                </td>
            </tr>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Current Class</div>
                </td>
                <td>
                    <div class="edu_name"><?php echo $classValues[$educationInfoProfile['UG']['CurrentClass']];?></div>
                </td>
            </tr>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Class Xth Board</div>
                </td>
                <td>
                    <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['tenthBoard'])? '-':$educationInfoProfile['UG']['tenthBoard'];?></div>
                </td>
            </tr>
            <tr class="tab_format">
                <td>
                    <div class="edu_info">Class Xth <?php echo empty($educationInfoProfile['UG']['marksHeading'])? 'CGPA':$educationInfoProfile['UG']['marksHeading'];?></div>
                </td>
                <td>
                    <div class="edu_name"><?php echo empty($educationInfoProfile['UG']['tenthMarks'])? '-':$educationInfoProfile['UG']['tenthMarks'];?></div>
                </td>
            </tr>
            <?php
        }
       ?>
     <tr class="tab_format">
       <td>
         <div class="edu_info">SA Exam Score</div>
       </td>
       <td>
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
                       <li><a><?php echo $examsStr;?></a></li>
                       <?php
                   }
               }

               ?>
           </ul>
         </div>
       </td>
     </tr>
   </table>

 </div>