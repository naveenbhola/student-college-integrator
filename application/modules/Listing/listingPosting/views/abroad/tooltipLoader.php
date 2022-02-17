<?php
  
   $globalTipArr = $this->config->item('helptext');
   
    switch ($formName)
    {
        case ENT_SA_FORM_ADD_UNIVERSITY:                  
        case ENT_SA_FORM_EDIT_UNIVERSITY:
            $tooltipArray=$globalTipArr['university_tooltips'];
            break;
        
        case ENT_SA_FORM_ADD_DEPARTMENT:
        case ENT_SA_FORM_EDIT_DEPARTMENT:
             $tooltipArray=$globalTipArr['department_tooltips'];
            break;
        
        case ENT_SA_FORM_ADD_COURSE:
        case ENT_SA_FORM_EDIT_COURSE:
            $tooltipArray=$globalTipArr['course_tooltips'];
            break;
        
        case ENT_SA_FORM_ADD_CONSULTANT:
        case ENT_SA_FORM_EDIT_CONSULTANT:
            $tooltipArray=$globalTipArr['consultant_tooltips'];
            break;
        
        case ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING:
        case ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING:
             $tooltipArray=$globalTipArr['consultant_univ_mapping_tooltips'];
            break;
        
        case ENT_SA_FORM_EDIT_STUDENT_PROFILE:      
        case ENT_SA_FORM_ADD_STUDENT_PROFILE:
            $tooltipArray=$globalTipArr['consultant_studentprofiles_tooltips'];
            break;
        
        case ENT_SA_FORM_ASSIGN_REGION:
        case ENT_SA_FORM_EDIT_ASSIGNED_REGION:
            $tooltipArray=$globalTipArr['consultant_assign_city_tooltips'];
            break;
        
        case ENT_SA_FORM_ADD_CONSULTANT_LOCATION:
        case ENT_SA_FORM_EDIT_CONSULTANT_LOCATION:
            $tooltipArray=$globalTipArr['consultant_branches/loc_tooltips'];
            break;
    }
    
     unset($globalTipArr);
?>

<script>
var tipArray = <?php echo json_encode($tooltipArray);?> ;
</script>
