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
    $courseString .= '<strong>'.$courseCategoryString.'</strong> in '.$specializationName;
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
?>
<div class="flot_col auto_clear">
 <table class="sa-ug">
   <tr>
     <td>
         <div class="">
             <p class="similar_p">Course Interested in</p>
             <p class="stream"><?php echo $courseString;?></p>
         </div>
     </td>
     <td>
       <div>
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
                    <li><a><?php echo $countryName;?></a></li>
                    <?php
                }
               ?>
           </ul>
         </div>
       </div>
     </td>
   </tr>
   <tr>
     <td>
       <div class="school_data">
         <p class="similar_p"><?php echo $expOrCurrentClassHeading; ?></p>
         <p class="schooling"><?php echo empty($expOrCurrentClassContent)?'-':$expOrCurrentClassContent; ?></p>
       </div>
     </td>
     <td>
       <div class="school_data">
         <p class="similar_p">Planning to go in</p>
         <p class="schooling"><strong><?php echo empty($timeOfStart) ?'-':$timeOfStart; ?></strong></p>
       </div>
     </td>
   </tr>
 </table>
</div>