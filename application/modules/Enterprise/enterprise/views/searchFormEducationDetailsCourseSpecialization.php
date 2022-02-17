<?php if($_REQUEST['course_name']=='Animation Courses' || $_REQUEST['course_name']=='Distance BCA/MCA' || $_REQUEST['course_name']=='IT Courses' || $_REQUEST['course_name']=='Test Prep' || $_REQUEST['course_name']=='Hospitality, Aviation & Tourism Courses' || $_REQUEST['course_name']=='Media, Films & Mass Communication Courses' || $_REQUEST['course_name']=='Test Prep (International Exams)' || $_REQUEST['course_name']=='Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name']=='Banking & Finance Courses' || $_REQUEST['course_name']=='Design Courses' || $_REQUEST['course_name']=='Distance B.A./M.A.' || $_REQUEST['course_name']=='Foreign Language Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses'){
	}else{ ?>
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Specialisation:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
					<div id="course_specialization" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
                        <div style="display:block;padding-left:5px"><input type="checkbox"
onClick="checkUncheckChilds3(this, 'course_specialization_holder')" id="all_specialization" name
="course_specialization[]" value="-1"/> All</div>
                        <div id="course_specialization_holder">
		        			<?php foreach($course_specialization_list as $list): ?>
				    		<div style="display:block;padding-left:5px"><input type="checkbox"
name="course_specialization[]" value="<?php echo $list['SpecializationId']; ?>"
onClick="uncheckElement3(this,'all_specialization');"> <?php echo $list['SpecializationName']; ?></div>
					    <?php endforeach; ?>
                        </div>
					</div>
                    <!--<select id="course_specialization" name="course_specialization[]" multiple="" >
                        <?php //foreach($course_specialization_list as $list): ?>
                             <option value="<?php //echo $list['SpecializationId']; ?>"><?php //echo $list['SpecializationName']; ?></option>
                        <?php //endforeach; ?>
                    </select>-->
                </div>
            </div>
        </div>

        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>
	
</div>
<?php } ?>
<?php
if($course_specialization_list[0]['CourseName']=='Distance B.Sc' || $course_specialization_list[0]['CourseName']=='Distance B.Tech' || $course_specialization_list[0]['CourseName']=='Distance M.Sc' || $course_specialization_list[0]['CourseName']=='Distance/Correspondence MBA' || $course_specialization_list[0]['CourseName']=='Management Certifications'
   || $course_specialization_list[0]['CourseName']=='Online MBA' || $course_specialization_list[0]['CourseName']=='Part-time MBA' || $course_specialization_list[0]['CourseName']=='Engineering Distance Diploma' || $_REQUEST['course_name']=='Animation Courses' || $_REQUEST['course_name']=='Distance BCA/MCA' || $_REQUEST['course_name']=='IT Courses' || $_REQUEST['course_name']=='Test Prep' || $_REQUEST['course_name']=='Hospitality, Aviation & Tourism Courses' || $_REQUEST['course_name']=='Media, Films & Mass Communication Courses' || $_REQUEST['course_name']=='Test Prep (International Exams)' || $_REQUEST['course_name']=='Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name']=='Banking & Finance Courses' || $_REQUEST['course_name']=='Design Courses' || $_REQUEST['course_name']=='Distance B.A./M.A.' || $_REQUEST['course_name']=='Foreign Language Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses' || $_REQUEST['course_name']=='Engineering Distance Diploma' || $_REQUEST['course_name']=='Management Certifications') { ?>

 <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	<?php } ?>
<div style="line-height:6px">&nbsp;</div>

<script>
function checkUncheckChilds3(obj, checkBoxesHolder)
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

function uncheckElement3(obj ,id)
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
    if (checks[i].checked == true )
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
<?php

if($course_name=='IT Courses' || $course_name=='Management Certifications' || $_REQUEST['course_name']=='Test Prep' || $_REQUEST['course_name']=='Hospitality, Aviation & Tourism Courses' || $_REQUEST['course_name']=='Media, Films & Mass Communication Courses' || $_REQUEST['course_name']=='Test Prep (International Exams)' || $_REQUEST['course_name']=='Arts, Law, Languages and Teaching Courses' || $_REQUEST['course_name']=='Banking & Finance Courses' || $_REQUEST['course_name']=='Design Courses' || $_REQUEST['course_name']=='Distance B.A./M.A.' || $_REQUEST['course_name']=='Foreign Language Courses' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Courses') {
	
	$this->load->view('enterprise/currentLocalityBlock');
}
?>