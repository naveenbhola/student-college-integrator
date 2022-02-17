<?php
    $studyAbroadLeftNavArr = $this->config->item('ENT_SA_LEFT_NAVIGATION_DETAILED_ARR');
    
    $onclickAction = "";
    
    // show confirmation dialog whenever the current page is of add/edit and user clicks on left navigation bar
    if(in_array($formName, array(ENT_SA_FORM_ADD_UNIVERSITY, ENT_SA_FORM_EDIT_UNIVERSITY,
				 ENT_SA_FORM_ADD_DEPARTMENT, ENT_SA_FORM_EDIT_DEPARTMENT,
				 ENT_SA_FORM_ADD_COURSE, ENT_SA_FORM_EDIT_COURSE,
				 ENT_SA_FORM_ADD_SNAPSHOT_COURSE, ENT_SA_FORM_EDIT_SNAPSHOT_COURSE,
				 ENT_SA_FORM_ADD_RANKING, ENT_SA_FORM_EDIT_RANKING,
				 ENT_SA_FORM_ADD_CONTENT, ENT_SA_FORM_EDIT_CONTENT,
				 ENT_SA_FORM_ADD_CITY, ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES,
				 ENT_SA_FORM_ADD_PAID_CLIENT,
				 ENT_SA_FORM_ADD_ADMISSION_GUIDE,ENT_SA_FORM_EDIT_ADMISSION_GUIDE,
				 ENT_SA_FORM_ADD_RMS_COUNSELLOR,ENT_SA_FORM_EDIT_RMS_COUNSELLOR,ENT_SA_FORM_DELETE_RMS_COUNSELLOR,
				 ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING,ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING,
				 ENT_SA_FORM_ADD_CONSULTANT,ENT_SA_FORM_EDIT_CONSULTANT,
				 ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING,ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING,
				 ENT_SA_FORM_ADD_STUDENT_PROFILE,ENT_SA_FORM_EDIT_STUDENT_PROFILE,
				 ENT_SA_FORM_ASSIGN_CITY,ENT_SA_FORM_EDIT_ASSIGNED_CITY,
                 ENT_SA_FORM_EDIT_CLIENT_ACTIVATION,ENT_SA_FORM_ADD_CLIENT_ACTIVATION
				 )))
    {
        $onclickAction = "onclick=\" if(confirm('All data changes will be lost.')){ window.onbeforeunload = null; } else {return false;}\"";
    }
?>
<div class="abroad-cms-nav flLt">
        	<ul>
                    <?php
                        foreach($studyAbroadLeftNavArr as $key=>$value)
                        {
                            // hide the section from unauthorized users
                            if(isset($value['USER_GROUP_HIDE']) && in_array($usergroup,$value['USER_GROUP_HIDE']))
                                continue;
                            
                            ?>
                                <li class="nav-heading"><?= $value['DISPLAY_TEXT'];?></li>
                            <?php
                            foreach($value['CHILDREN'] as $key1=>$value1)
                            {
                                // hide the section from unauthorized users
                                if(isset($value1['USER_GROUP_HIDE']) && in_array($usergroup,$value1['USER_GROUP_HIDE']))
                                    continue;
                                
                                if(isset($selectLeftNav))
                                {
                                    $isActive =  ($selectLeftNav == $key1) ? 1 : 0;
                                }

                                ?>
                                    <li class=<?=$isActive?'active':'';?>><a href="<?=$value1['URL']?>" <?=$onclickAction?>><?=$value1['DISPLAY_TEXT'];?></a><i class="abroad-cms-sprite arrow-box"></i></li>
                                <?php
                            }
                        }
                    ?>
            </ul>
        </div>
