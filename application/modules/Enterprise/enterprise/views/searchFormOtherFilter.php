<!--Start_OtherFilter-->
<?php if($course_name == 'Study Abroad') { ?>
<div class="cmsSearch_SepratorLine">&nbsp;</div>
<div class="cmsSearch_contentBoxTitle"><a href="javascript:void(0);" style="padding-left:15px; text-decoration:none;color:#000;font-size:14px"><b>Exam Filter</b></a></div>
<div style="line-height:15px">&nbsp;</div>                       
<div id="otherFilterParent"> </div>
<?php } ?>

<?php
$message = ($boolen_flag_to_apply_hack_on_mba_courses == 'false') ? $actual_course_name : $course_name;
//if ((($course_name != 'IT Courses') && ($course_name != 'IT Degrees') && ($course_name != 'Study Abroad')) || ($message == 'Executive MBA')) {
if($examValues) {
?>
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Competitive Exam Scores:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<?php $this->load->view('enterprise/competitiveExams'); ?>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<?php } ?>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>

<?php if($course_name == 'Study Abroad') { ?>   
<div class="cmsSearch_SepratorLine">&nbsp;</div>
<?php } ?>

<script>

function validateMaxExp()
{
    if(document.getElementById("min_workex").value == "" || document.getElementById("max_workex").value == "")
    {
        return;
    }
    else
    {
       if(document.getElementById("max_workex").value > document.getElementById("min_workex").value)
       {
           return;
       }
       else
       {
           alert("Please select a value greater than minimum work-experience");
           selectComboBox(document.getElementById('max_workex'),"");
           return;
       }
    }
}
function addAnotherOtherExam()
{
   var par =document.getElementById('other_exam_holder');
   var childs=par.getElementsByTagName('div');
   var otherExamNumber = childs.length+1;
   var divElement = document.createElement('div');
   divElement.id = 'other_exam_'+otherExamNumber +'_holder';

    divElement.innerHTML = '<select id="other_exam_'+otherExamNumber+'" name="other_exam_'+otherExamNumber+'" onchange="scoreTxtBoxChange(this)"><option value="">Select Exam</option><option value="XAT">XAT</option><option value="UGAT">UGAT</option><option value="IITJEE">IITJEE</option><option value="GATE">GATE</option><option value="TOEFL">TOEFL</option><option value="IELTS">IELTS</option><option value="GRE">GRE</option><option value="GMAT">GMAT</option></select> &nbsp;Score:&nbsp;&nbsp;<input id="other_exam_'+otherExamNumber+'_min" name="other_exam_'+otherExamNumber+'_min" type="text" value="Min" style="width:55px" onblur="return Numbers(this)" onfocus="javascript:this.value=\'\';" disabled="true" />&nbsp;<input id="other_exam_'+otherExamNumber+'_max" name="other_exam_'+otherExamNumber+'_max" type="text" value="Max" style="width:55px" onblur="return Numbers(this)" onfocus="javascript:this.value=\'\';" disabled="true" />';
   document.getElementById('other_exam_holder').appendChild(divElement);
}

function competitiveExamScore(obj)
{
    var examName = obj.id.replace('exam_','');
    if(obj.checked==true){
        document.getElementById(examName +'_min_score').disabled = false;
        document.getElementById(examName +'_max_score').disabled = false;
    } else { 
        document.getElementById(examName +'_min_score').disabled = true;
        document.getElementById(examName +'_max_score').disabled = true;
    }
}

function scoreTxtBoxChange(obj){
    objMin = document.getElementById(obj.id+'_min');
    objMax = document.getElementById(obj.id+'_max');
    if(obj.value==''){
        objMin.disabled = true;
        objMax.disabled = true;
    } else {
        objMin.disabled = false;
        objMax.disabled = false;
    }
}
function timerangeFrom()
{
	var calMain = new CalendarPopup('calendardiv');	
	calMain.select($('timerange_from'),'timerange_from_img','yyyy-mm-dd');
	return false;
}

function timerangeTo()
{
	var calMain = new CalendarPopup('calendardiv');	
	calMain.select($('timerange_to'),'timerange_to_img','yyyy-mm-dd');
	return false;
}

function timeTxtBoxChange(obj)
{
	if (obj.value == 'range')
	{
		$('timerange_from').disabled = false;
		$('timerange_to').disabled = false;
		$('fixedduration').disabled = true;
		if ($('timerange_from').value == "dd/mm/yyyy") {
			$('timerange_from').value = "";
		}
		
		if ($('timerange_to').value == "dd/mm/yyyy") {
			$('timerange_to').value = "";
		}
	}
	else if (obj.value == 'fixed')
	{
		$('timerange_from').disabled = true;
		$('timerange_to').disabled = true;
		$('fixedduration').disabled = false;
		if ($('timerange_from').value == "") {
			$('timerange_from').value = "dd/mm/yyyy";
		}
		
		if ($('timerange_to').value == "") {
			$('timerange_to').value = "dd/mm/yyyy";
		}
	}
}

function validatetimeRange()
{
	var startDate = document.getElementById('timerange_from').value;
	var endDate = document.getElementById('timerange_to').value;
	if (startDate == '' || startDate == 'yyyy-mm-dd') {
		alert('Select a start date.');
		return false;
	}
	else if (endDate == '' || endDate == 'yyyy-mm-dd') {
		alert('Select an end date.');
		return false;
	}
	var fromdate = startDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
	var todate = endDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
	
	if (Date.parse(todate) >= Date.parse(fromdate)) {
		return true;
	}
	else {
		alert("Please select a 'TO' date greater than the 'FROM' date.");
		return false;
	}
	return true;
}

function validateYearRange(level)
{
    var formobj = $('studentSearchFormMain');
    if (formobj[level +'StartYear'].value == "" || formobj[level +'EndYear'].value == "") {
        return;
    }
    else {
	if(parseInt(formobj[level +'EndYear'].value) >= parseInt(formobj[level +'StartYear'].value)) {
	    return;
	}
	else {
	    alert("Please select an end year value greater than or equal to start year");
	    formobj[level +'EndYear'].focus();
	    selectComboBox(formobj[level +'EndYear'],"");
	    return;
	}
    }   
}
</script>
