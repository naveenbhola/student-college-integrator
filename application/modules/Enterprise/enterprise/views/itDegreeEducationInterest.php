<!-- Desired Courses Start -->
<?php if($_REQUEST['course_name']=='Animation Degrees' || $_REQUEST['course_name']=='IT Degrees' || $_REQUEST['course_name']=='Hospitality, Aviation & Tourism Degrees' || $_REQUEST['course_name']=='Media, Films & Mass Communication Degrees' || $_REQUEST['course_name']=='Arts, Law, Languages and Teaching Degrees' || $_REQUEST['course_name']=='Banking & Finance Degrees' || $_REQUEST['course_name']=='Design Degrees' || $_REQUEST['course_name']=='Medicine, Beauty & Health Care Degrees' || $_REQUEST['course_name']=='Retail Degrees' || $_REQUEST['course_name']=='Other Management Degrees'){ ?>
<div class="txt_align_r" style="padding-right:680px">Desired Level:&nbsp;<input type="radio" value="UG" name="educationLevel" id="educationLevel1" onclick="filterCourseInLevel1()" style="margin-left: 10px" checked>UG </input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="PG" name="educationLevel" id="educationLevel2" onclick="filterCourseInLevel2()">PG </input></div>
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
	<div>
	<?php //echo "<pre>";print_r($itcourseslist); echo "</pre>"; ?>
        	<div style="width:100%">
            	<div style="line-height:18px">
		<!--<select onChange= "load_specialisation(this.value,'course_specialization_holder');" style="font-size:11px;width:316px" name = 'desiredCourse[]' id='desiredCourse' style='width:170px;font-size:11px'>-->
		    <!--<option value="" ddtype="">Select</option>-->
			<div id="desiredCourse" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
			<?php if($_REQUEST['course_name']=='Animation Degrees' || $_REQUEST['course_name']=='IT Degrees'){ ?>
                        <div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkallfunction()" id="all_specialization" name ="desiredCourse[]" value="-1"/> All</div>
                        <?php }else{ ?>
			<div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds(this, 'course_specialization_holder');checkallfunction();" id="all_specialization" name ="desiredCourse[]" value="-1"/> All</div>
			<?php } ?>
			<div id="course_specialization_holder">
		    <?php
if ( ($_REQUEST['course_name'] == 'Integrated MBA Courses')&&($_REQUEST['categoryId'] == '3'))
		    {
			$selected_str = '';
			foreach ($itcourseslist as $groupId => $value) {
			    foreach ($value as $finalArray) {
			      if (($finalArray['CourseLevel'] == 'Degree') && ($finalArray['CourseReach'] != 'local'))
{
				  if ($finalArray['SpecializationId'] ==
				    '783'|| $finalArray['SpecializationId'] ==
				    '1375') {
                                        $spez = ($_REQUEST['course_name'] == 'Science & Engineering')?" - ".$finalArray['SpecializationName']:"";
			?>
				<div style="display:block;padding-left:5px"><input type="checkbox" name="desiredCourse[]" value="<?php echo $finalArray['CourseName']; ?>" onClick="uncheckElement(this,'all_specialization');"> <?php echo $finalArray['CourseName'].$spez; ?></div>
			<?php
			//		echo '<option ddtype="'.$finalArray['SpecializationId'].'"  ' . $selected_str . ' value="'.$finalArray['CourseName'].'">'.$finalArray['CourseName'].'</option>';
				    }
				}
			    }
			}
		    }
		    else if ( $_REQUEST['course_name'] == 'Other Management Degrees')
		    {
		      $selected_str = '';
		      foreach ($itcourseslist as $groupId => $value) {
			foreach ($value as $finalArray) {
			  if (($finalArray['CourseLevel'] == 'Degree') && ($finalArray['CourseReach'] != 'local')) {
			    if ($finalArray['SpecializationId'] == '1305' ||
			      $finalArray['SpecializationId'] == '1306' ||
			      $finalArray['SpecializationId'] == '1308' ||
			      $finalArray['SpecializationId'] == '1313' ||
			      $finalArray['SpecializationId'] == '1314')
{
			      $spez = ($_REQUEST['course_name'] == 'Science & Engineering')?" -
			      ".$finalArray['SpecializationName']:"";
			    ?>
			    <div style="display:block;padding-left:5px"class="<?php echo $finalArray['CourseLevel1']; ?>"><input type="checkbox" name="desiredCourse[]" value="<?php echo $finalArray['CourseName']; ?>" class="<?php echo $finalArray['CourseLevel1']; ?>" onClick="uncheckElement(this,'all_specialization');"> <?php echo
$finalArray['CourseName'].$spez; ?></div>
			    <?php
			    //		echo '<option ddtype="'.$finalArray['SpecializationId'].'"  ' . $selected_str .
			    ' value="'.$finalArray['CourseName'].'">'.$finalArray['CourseName'].'</option>';
				    }
				}
			    }
			}
		    }
		    else if ( $_REQUEST['course_name'] == 'BBA/BBM')
		    {
		      $selected_str = '';
		      foreach ($itcourseslist as $groupId => $value) {
			foreach ($value as $finalArray) {
			  if ($finalArray['SpecializationId'] == '781') {
			      $spez = ($_REQUEST['course_name'] == 'Science & Engineering')?" -
			      ".$finalArray['SpecializationName']:"";
			    ?>
			    <div style="display:block;padding-left:5px"><input type="checkbox"
			    name="desiredCourse[]" value="<?php echo $finalArray['CourseName']; ?>"
			    onClick="uncheckElement(this,'all_specialization');"> <?php echo
$finalArray['CourseName'].$spez; ?></div>
			    <?php
			    //		echo '<option ddtype="'.$finalArray['SpecializationId'].'"  ' . $selected_str .
			    ' value="'.$finalArray['CourseName'].'">'.$finalArray['CourseName'].'</option>';
				    }
			    }
			}
		    }
		    else
		    {
		      $selected_str = '';
		      foreach ($itcourseslist as $groupId => $value) {
			foreach ($value as $finalArray) {
			  // Apply check 2 filter result that comes from
			  // ldb courses and who are degree + local
			  if (($finalArray['CourseLevel'] == 'Degree') && ($finalArray['CourseReach'] != 'local')) {
			    
			    if ($finalArray['CourseName'] != 'Distance BCA' && $finalArray['CourseName'] !=
			      'Distance MCA') {
			      $spez = ($_REQUEST['course_name'] == 'Science & Engineering')?" -
			      ".$finalArray['SpecializationName']:"";
			    ?>
			    <div style="display:block;padding-left:5px" class="<?php echo $finalArray['CourseLevel1']; ?>"><input type="checkbox"
			    name="desiredCourse[]" value="<?php echo $finalArray['CourseName']; ?>"
			    onClick="uncheckElement(this,'all_specialization');" class="<?php echo $finalArray['CourseLevel1']; ?>"> <?php echo
$finalArray['CourseName'].$spez; ?></div>
			    <?php
			    //		echo '<option ddtype="'.$finalArray['SpecializationId'].'"  ' . $selected_str .
			    ' value="'.$finalArray['CourseName'].'">'.$finalArray['CourseName'].'</option>';
				    }
				}
			    }
			}
		    }
		    ?>
			</div>
			</div>
	</div>
		<!--</select>-->
		<span id="ajax-loader-display" style="display:none;"><img src="/public/images/working.gif" border="0" /></span>
		</div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<!-- Desired Courses End -->
<!-- Specialisation Section Start -->
<div id="specialisation_block" style="display:none;width:100%;">
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
                        <div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds(this, 'course_specialization_holder')" id="all_specialization" name ="course_specialization[]" value="-1"/> All</div>
                        <div id="course_specialization_holder">
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
function load_specialisation(id,divid)
{
    var courseOptions = document.getElementById('desiredCourse').options;
    var selectindex = courseOptions.selectedIndex;
    id = courseOptions[selectindex].getAttribute("ddtype");
    document.getElementById('all_specialization').checked = false;
    var xmlHttp = getXMLHTTPObject();
        xmlHttp.onreadystatechange=function()
        {
            if (xmlHttp.readyState !== 4) {
                 document.getElementById('ajax-loader-display').style.display = "";
            }
            if(xmlHttp.readyState==4)
            {
                if(trim(xmlHttp.responseText) != "")
                {
		    var response = eval("eval("+xmlHttp.responseText+")");
                    if (response.length > 0 ) {
			document.getElementById(divid).innerHTML = '';
			var response_str = '';
			if(response != 0)
			{
			    for(i = 0;i < response.length;i++)
			    {
				response_str += '<div style="display:block;padding-left:5px"><input type="checkbox" name="course_specialization[]" value="'+response[i].SpecializationId+'" onClick="uncheckElement(this,\'all_specialization\');">'+response[i].SpecializationName+'</div>';
			    }
			    
			}
			document.getElementById(divid).innerHTML = response_str;
			document.getElementById("specialisation_block").style.display = "";
		    } else {
			document.getElementById("specialisation_block").style.display = "none";
		    }
                }
                document.getElementById('ajax-loader-display').style.display = "none";
            }
        };
        url = '/enterprise/shikshaDB/callAjax' + '/1/' + id;
	xmlHttp.open("POST",url,true);
        xmlHttp.send(null);
}
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
