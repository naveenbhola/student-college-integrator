<!-- Specialisation Section Start -->

<?php if($_REQUEST['course_name']  == 'Test Prep' || $_REQUEST['course_name']  == 'Test Prep (International Exams)'){ ?>
<div class="txt_align_r" style="padding-right:680px">Desired Level:&nbsp;<input type="radio" value="localUG" name="educationLevel" id="educationLevel1" onclick="filterCourseInLevel3()"  style="margin-left: 10px" checked>UG </input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="localPG" name="educationLevel" id="educationLevel2" onclick="filterCourseInLevel4()">PG </input></div>
<br>
<?php }else if($_REQUEST['course_name']  == 'Distance BCA/MCA' || $_REQUEST['course_name'] == 'Distance B.A./M.A.' || $_REQUEST['course_name']=='Design Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses' || $_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name'] == 'Banking & Finance Courses'){ ?>
<div class="txt_align_r" style="padding-right:680px">Desired Level:&nbsp;<input type="radio" value="UG" name="educationLevel" id="educationLevel1" onclick="filterCourseInLevel1()"  style="margin-left: 10px" checked>UG </input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="PG" name="educationLevel" id="educationLevel2" onclick="filterCourseInLevel2()">PG </input></div>
	<br>
<?php } ?>
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Desired Courses:&nbsp; </div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        
        	<div style="width:100%">
		    <div>
			    <?php
				$height = "125px";
				if (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Distance MCA/BCA')) {
					$height = "65px";
					$name_field = 'course_specialization[]';
				} else if ($_REQUEST['course_name']  == 'Test Prep') {
					$height = "125px";
					$name_field = 'testPrep_blogid[]';
				}

			    ?>
			    <div id="course_specialization" style="width:313px;border:1px solid #CCC;padding:5px 0;height:<?php echo $height; ?>;overflow:auto">
			    <?php if($_REQUEST['course_name']  == 'Distance BCA/MCA' || $_REQUEST['course_name'] == 'Distance B.A./M.A.'){ }else if($_REQUEST['course_name']  == 'Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name']  == 'Medicine, Beauty & Health Care Courses' || $_REQUEST['course_name']  == 'Design Courses' || $_REQUEST['course_name'] == 'Banking & Finance Courses' || $_REQUEST['course_name']  == 'Test Prep' || $_REQUEST['course_name']  == 'Test Prep (International Exams)') { ?>
			    <div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkallfunction()" id="all_specialization" name ="<?php echo $name_field; ?>" value="-1"/> All</div>
			    <?php }else { ?>
			    <div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds(this, 'course_specialization_holder')" id="all_specialization" name ="<?php echo $name_field; ?>" value="-1"/> All</div>
			    <?php } ?>
			    <div id="course_specialization_holder">
			    <?php
if (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Arts, Law, Languages and Teaching Courses'))
			      { 
				foreach ($itcourseslist as $groupId => $value) {
				  foreach ($value as $finalArray) {
				    if ($finalArray['SpecializationId'] != '496' && $finalArray['SpecializationId'] != '500' && $finalArray['SpecializationId'] != '503' && $finalArray['SpecializationId'] != '505' && $finalArray['SpecializationId'] != '504' && $finalArray['SpecializationId'] != '499' && $finalArray['SpecializationId'] !='497' && $finalArray['SpecializationId'] != '498' && $finalArray['SpecializationId'] != '499'&& $finalArray['SpecializationId'] != '494'&& $finalArray['SpecializationId'] != '495'&& $finalArray['SpecializationId'] != '456'&& $finalArray['SpecializationId'] != '457') {
					if ($finalArray['CourseReach'] == 'local') {
						echo '<div style="display:block;padding-left:5px" class="'.$finalArray['CourseLevel1'].'"><div class="float_L" style="width:22px"><input type="checkbox" name="course_specialization[]" class="'.$finalArray['CourseLevel1'].'" value="'.$finalArray['CourseName'].'"  onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div></div>';
					}
				      }
				  }
				}
			    }
	else if (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Foreign Language Courses'))
	{ 
			      foreach ($itcourseslist as $groupId => $value) {
				foreach ($value as $finalArray) {
					if ($finalArray['SpecializationId'] == '496' || $finalArray['SpecializationId'] == '500' || $finalArray['SpecializationId'] == '503' || $finalArray['SpecializationId'] == '505' || $finalArray['SpecializationId'] == '504' || $finalArray['SpecializationId'] == '499' || $finalArray['SpecializationId'] == '497' || $finalArray['SpecializationId'] == '498' || $finalArray['SpecializationId'] == '499'|| $finalArray['SpecializationId'] == '494'|| $finalArray['SpecializationId'] == '495') {
						echo '<div style="display:block;padding-left:5px"><div class="float_L" style="width:22px"><input type="checkbox" name="course_specialization[]" value="'.$finalArray['CourseName'].'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div></div>';
					  }
					}
				      }
				    }
					elseif (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Distance B.A./M.A.'))
					    { 
					      foreach ($itcourseslist as $groupId => $value) {
						foreach ($value as $finalArray) {
						  if ($finalArray['CourseReach'] == 'local') {
						    if ( ($finalArray['CourseName'] == 'Distance B.A.') || ($finalArray['CourseName'] == 'Distance M.A.')) {
							echo '<div style="display:block;padding-left:5px" class="'.$finalArray['CourseLevel1'].'"><div class="float_L" style="width:22px"><input type="checkbox" name="course_specialization[]" class="'.$finalArray['CourseLevel1'].'" value="'.$finalArray['CourseName'].'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div></div>';
							}
						    }
						}
					    }
					}
					else if (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Distance BCA/MCA'))
						{ 
							foreach ($itcourseslist as $groupId => $value) {
								foreach ($value as $finalArray) {
								    if ($finalArray['CourseReach'] == 'local') {
									if ( ($finalArray['CourseName'] == 'Distance BCA') || ($finalArray['CourseName'] == 'Distance MCA')) {
									    echo '<div style="display:block;padding-left:5px" class="'.$finalArray['CourseLevel1'].'"><div class="float_L" style="width:22px"><input type="checkbox" class="'.$finalArray['CourseLevel1'].'" name="course_specialization[]" value="'.$finalArray['CourseName'].'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div></div>';
									}
								}
							}
					}	
				} else {
				    if ( $_REQUEST['course_name']  != 'Test Prep' ) {
					
				    foreach ($itcourseslist as $groupId => $value) {
					foreach ($value as $finalArray) {
						
					  if ($finalArray['CourseReach'] == 'local') {
					
						if ( ($finalArray['CourseName'] != 'Distance BCA') && ($finalArray['CourseName'] != 'Distance MCA')) { 
							if($_REQUEST['course_name'] == 'Design Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses' || $_REQUEST['course_name']== 'Banking & Finance Courses'){ 
								echo '<div style="display:block;padding-left:5px" class="'.$finalArray['CourseLevel1'].'"><div class="float_L" style="width:22px"><input type="checkbox" name="course_specialization[]" class="'.$finalArray['CourseLevel1'].'" value="'.$finalArray['CourseName'].'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div> </div>';										
							}else{													
								 echo '<div style="display:block;padding-left:5px" ><div class="float_L" style="width:22px"><input type="checkbox" name="course_specialization[]" value="'.$finalArray['CourseName'].'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div class="clear_B">&nbsp;</div> </div>';
							}
						}
					    }
					}
				    }
				    }
				}
				if ($_REQUEST['course_name']  == 'Test Prep')
				{
					foreach ($itcourseslist as $key=>$value)
					{
						foreach($value as $index=>$main)
						{
						  if ($main['child']['blogId'] != '3300' && $main['child']['blogId'] != '310'&& $main['child']['blogId'] != '410' && $main['child']['blogId'] !='418'&& $main['child']['blogId'] != '2494') {
							echo '<div style="display:block;padding-left:5px" class="'.$registrationBuilder->getCourseGroupForTestPrep($main['child']['blogId']).'"><div class="float_L" style="width:22px"><input type="checkbox" name="testPrep_blogid[]" value="'.$main['child']['blogId'].'" onClick="uncheckElement(this,\'all_specialization\');" class="'.$registrationBuilder->getCourseGroupForTestPrep($main['child']['blogId']).'"></div><div class="float_L" style="width:260px;padding-top:3px">'.$main['child']['acronym'].'</div><div class="clear_B">&nbsp;</div> </div>';
							}
						}
					}
					?>
					<input type="hidden" id="flag_test_prep" name="flag_test_prep" value="testprep" />
					<?php
				}
				if ($_REQUEST['course_name']  == 'Test Prep (International Exams)')
				{
				  foreach ($itcourseslist as $key=>$value)
				  {
				    foreach($value as $index=>$main)
				    {
				      if ($main['child']['blogId'] == '3300' || $main['child']['blogId'] == '310'|| $main['child']['blogId'] == '410' || $main['child']['blogId'] == '418'|| $main['child']['blogId'] == '2494') { 
					echo '<div style="display:block;padding-left:5px"  class="'.$registrationBuilder->getCourseGroupForTestPrep($main['child']['blogId']).'"><div class="float_L" style="width:22px"><input type="checkbox" name="testPrep_blogid[]" value="'.$main['child']['blogId'].'"  class="'.$registrationBuilder->getCourseGroupForTestPrep($main['child']['blogId']).'" onClick="uncheckElement(this,\'all_specialization\');"></div><div class="float_L" style="width:260px;padding-top:3px">'.$main['child']['acronym'].'</div><div class="clear_B">&nbsp;</div> </div>';
				      }
				    }
				  }
				  ?>
			    <input type="hidden" id="flag_test_prep" name="flag_test_prep" value="testprep" />
			    <?php
				}
			    ?>
			    </div>
		    </div>
                </div>
            </div>
        </div>
	
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
	
    </div>
</div>
<?php if($_REQUEST['course_name']!='Animation Courses'){ ?>

<?php } ?>
<?php if($_REQUEST['course_name']=='Animation Courses' || $_REQUEST['course_name']=='Distance BCA/MCA' || $_REQUEST['course_name']=='IT Courses' || $_REQUEST['course_name']=='Test Prep' || $_REQUEST['course_name']=='Hospitality, Aviation & Tourism Courses' || $_REQUEST['course_name']=='Media, Films & Mass Communication Courses' || $_REQUEST['course_name']=='Test Prep (International Exams)' || $_REQUEST['course_name']=='Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name']=='Banking & Finance Courses' || $_REQUEST['course_name']=='Design Courses' || $_REQUEST['course_name']=='Distance B.A./M.A.' || $_REQUEST['course_name']=='Foreign Language Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses'){ 
	//$this->load->view('enterprise/searchFormEducationDetailsDesiredCourse'); //desired course
	  $this->load->view('enterprise/searchFormEducationDetailsCourseSpecialization');
  }else{ ?>
  <div class="cmsSearch_SepratorLine">&nbsp;</div>
<div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details : </b></div></div>
<?php } ?>

<script>
function checkUncheckChilds(obj, checkBoxesHolder)
{
    var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
    for(var i=0;i<checkBoxes.length;i++)
    {
        if(checkBoxes[i].checked!=obj.checked)
        {
            checkBoxes[i].checked = obj.checked;
        }
    }
}
function uncheckElement(obj ,id)
{
   var allChecked = false;
   if(!obj.checked)
   {
       if(document.getElementById(id).checked)
       {
        document.getElementById(id).checked = false;
       }
   }
    var checks =  document.getElementById('course_specialization_holder').getElementsByTagName("input");
    var boxLength = checks.length;
    var chkAll = document.getElementById(id);
    for ( i=0; i < boxLength; i++ )
    {
	    if ( checks[i].checked == true )
	    {
		allChecked = true;
		continue;
	    }
	    else
	    {
		allChecked = false;
		break;
	    }
    }
    if ( allChecked == true )
    chkAll.checked = true;
    else
    chkAll.checked = false;
}
</script>
<!-- Specialisation Section End -->
<?php if($_REQUEST['course_name']!='Animation Courses' && $_REQUEST['course_name']!='Distance BCA/MCA' && $_REQUEST['course_name']!='IT Courses' && $_REQUEST['course_name']!='Test Prep' && $_REQUEST['course_name']!='Hospitality, Aviation & Tourism Courses' && $_REQUEST['course_name']!='Media, Films & Mass Communication Courses' && $_REQUEST['course_name']!='Test Prep (International Exams)' && $_REQUEST['course_name']!='Arts, Law, Languages and Teaching Courses' && $_REQUEST['course_name']!='Banking & Finance Courses' && $_REQUEST['course_name']!='Design Courses' && $_REQUEST['course_name']!='Distance B.A./M.A.' && $_REQUEST['course_name']!='Foreign Language Courses' && $_REQUEST['course_name']!='Medicine, Beauty & Health Care Courses'){ echo $_REQUEST['course_name']; ?>

<?php $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation');
}//change?> 
