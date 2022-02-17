<?php 
if(isset($courseAttributeData) && count($courseAttributeData) > 0){
  echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision', $courseAttributeData);
}else{ 
      echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision', $courseAttributeData_fullTimeMba);
      echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision', $courseAttributeData_beBtech);
    }
?>