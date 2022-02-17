<!-- Specialisation Section Start -->
<?php if($_REQUEST['course_name']  == 'Medicine, Beauty & Health Care Degrees' || $_REQUEST['course_name']  == 'Marine Engineering'){ ?>
	<div class="txt_align_r" style="padding-right:680px">Desired Level:&nbsp;<input type="radio" value="UG" name="educationLevel" id="educationLevel1" onclick="filterCourseInLevel1()"  style="margin-left: 10px" checked>UG </input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="PG" name="educationLevel" id="educationLevel2" onclick="filterCourseInLevel2()">PG </input></div>
	<br>
<?php } ?>

<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Desired Courses:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
		    <div>
			    <?php
				$height = "125px";
				if (isset($_REQUEST['course_name']))
				{
					$height = "65px";
					$name_field = 'course_specialization[]';
				}

			    ?>
			    <div id="course_specialization" style="width:313px;border:1px solid #CCC;padding:5px
0;height:<?php echo $height; ?>;overflow:auto">
	<?php if($_REQUEST['course_name']  == 'Medicine, Beauty & Health Care Degrees' || $_REQUEST['course_name']  == 'Marine Engineering'){ }else{?>
			    <div style="display:block;padding-left:5px"><input type="checkbox"
onClick="checkUncheckChilds(this, 'course_specialization_holder')" id="all_specialization" name ="<?php echo
$name_field; ?>" value="-1"/> All</div>
			    <?php }?>
			    <div id="course_specialization_holder">
			    <?php
			    if (($_REQUEST['course_name'] == 'Integrated MBA Courses')&&($_REQUEST['categoryId'] ==
			      '2')) {
				foreach ($itcourseslist as $groupId => $value) {
				  foreach ($value as $finalArray) {
				    if ($finalArray['SpecializationId'] == '782') {
					echo '<div style="display:block;padding-left:5px"><div
					class="float_L" style="width:22px"><input type="checkbox"
					name="course_specialization[]" value="'.$finalArray['CourseName'].'"
					onClick="uncheckElement(this,\'all_specialization\');"></div><div
					class="float_L"
					style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div
					class="clear_B">&nbsp;</div></div>';
				      }
				  }
				}	
				
				
			      }
else if (($_REQUEST['course_name'] == 'Medicine, Beauty & Health Care Degrees')&&($_REQUEST['categoryId'] =='2')) {
				foreach ($itcourseslist as $groupId => $value) {
				  foreach ($value as $finalArray) {
				    if ($finalArray['SpecializationId'] == '353' || $finalArray['SpecializationId']
== '354') {
					echo '<div style="display:block;padding-left:5px"
					class="'.$finalArray['CourseLevel1'].'"><div
					class="float_L" style="width:22px"><input type="checkbox"
					name="course_specialization[]" value="'.$finalArray['CourseName'].'"
					onClick="uncheckElement(this,\'all_specialization\');"
					class="'.$finalArray['CourseLevel1'].'"></div><div
					class="float_L"
					style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div
					class="clear_B">&nbsp;</div></div>';
				      }
				  }
				}		
				
			      }
			      else if (($_REQUEST['course_name'] == 'Marine Engineering')&&($_REQUEST['categoryId'] ==
'2')) {

  foreach ($itcourseslist as $groupId => $value) {
    foreach ($value as $finalArray) {
      if ($finalArray['SpecializationId'] == '1376' || $finalArray['SpecializationId'] ==
	'1377') {
	echo '<div style="display:block;padding-left:5px" class="'.$finalArray['CourseLevel1'].'"><div
	class="float_L" style="width:22px"><input type="checkbox" class="'.$finalArray['CourseLevel1'].'"
	name="course_specialization[]" value="'.$finalArray['CourseName'].'"
	onClick="uncheckElement(this,\'all_specialization\');"></div><div
	class="float_L"
	style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div
	class="clear_B">&nbsp;</div></div>';
	}
  }
}

  
}			    //print_r($itcourseslist);
else if (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Science & Engineering Degrees'))
			      {
				foreach ($itcourseslist as $groupId => $value) {
				  foreach ($value as $finalArray) {
				    if ($finalArray['SpecializationId'] == '644' || $finalArray['SpecializationId'] ==
				      '356') {
				      echo '<div style="display:block;padding-left:5px"><div
				      class="float_L" style="width:22px"><input type="checkbox"
				      name="course_specialization[]" value="'.$finalArray['CourseName'].'"
				      onClick="uncheckElement(this,\'all_specialization\');"></div><div
				      class="float_L"
				      style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div
				      class="clear_B">&nbsp;</div></div>';
				      }
				  }
				}
			    }
elseif (isset($_REQUEST['course_name'])&& ($_REQUEST['course_name'] == 'Aircraft Maintenance Engineering'))
			      {
				foreach ($itcourseslist as $groupId => $value) {
				  foreach ($value as $finalArray) {
				    if ($finalArray['SpecializationId'] == '352') {
					echo '<div style="display:block;padding-left:5px"><div
					class="float_L" style="width:22px"><input type="checkbox"
					name="course_specialization[]" value="'.$finalArray['CourseName'].'"
					onClick="uncheckElement(this,\'all_specialization\');"></div><div
					class="float_L"
					style="width:260px;padding-top:3px">'.$finalArray['CourseName'].'</div><div
					class="clear_B">&nbsp;</div></div>';
				      }
				  }
				}
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
<div style="line-height:6px">&nbsp;</div>
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
