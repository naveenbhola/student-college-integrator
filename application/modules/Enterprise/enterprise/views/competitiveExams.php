<div style="<?php if($course_name == 'Study Abroad') { ?> width:600px; overflow: auto; <?php } else { ?> width:300px; overflow-y: auto; overflow-x: hidden; <?php } ?> height:170px; background: #f8f8f8; border:1px solid #ccc; padding:8px;">

<?php

if($course_name == 'Study Abroad') {
	$this->load->view('enterprise/examListAbroad',array('examList' => $examValues));
}
else {
	$this->load->view('enterprise/examList',array('examList' => $examValues));
}

if(count($examValues['others'])) {
?>
<div style="line-height:6px">&nbsp;</div>
<div id='other_exams' style='display:none;'>
<?php $this->load->view('enterprise/examList',array('examList' => $examValues['others'])); ?>
<div style="line-height:6px">&nbsp;</div>
</div>
<div style='padding-bottom:10px;' id='show_more_exams'>
    &nbsp;<a href="javascript:void(0)" onclick="showOtherExams()" class="cmsSearch_plusImg">Show More Exams</a>
</div>
<?php } ?>
</div>
<script>
function showOtherExams()
{
   document.getElementById('other_exams').style.display = '';
   document.getElementById('show_more_exams').style.display = 'none';
}

function toggleExamDetails(obj,examId){
    if(obj.checked==true){
		if (document.getElementById('exam_'+examId+'_min_score')) {
			document.getElementById('exam_'+examId+'_min_score').disabled = false;
		}
        if (document.getElementById('exam_'+examId+'_max_score')) {
			document.getElementById('exam_'+examId+'_max_score').disabled = false;
		}
		if (document.getElementById('exam_'+examId+'_year')) {
			document.getElementById('exam_'+examId+'_year').disabled = false;
		}
    } else { 
        if (document.getElementById('exam_'+examId+'_min_score')) {
			document.getElementById('exam_'+examId+'_min_score').disabled = true;
		}
        if (document.getElementById('exam_'+examId+'_max_score')) {
			document.getElementById('exam_'+examId+'_max_score').disabled = true;
		}
		if (document.getElementById('exam_'+examId+'_year')) {
			document.getElementById('exam_'+examId+'_year').disabled = true;
		}
    }
}

function validateExamNational(obj,examId)
{
    var minScoreObj = document.getElementById('exam_'+examId+'_min_score');
    var maxScoreObj = document.getElementById('exam_'+examId+'_max_score');
    
    if (minScoreObj.value == "" || maxScoreObj.value == "") {
        return;
    }
    else if (minScoreObj.value == "Min" || maxScoreObj.value == "Max") {
        return;
    }
    else {
	if(parseInt(maxScoreObj.value) >= parseInt(minScoreObj.value)) {
	    return;
	}
	else {
	    alert("Please select a maximum value greater than or equal to minimum");
	    obj.value = "";
	    return;
	}
    }
}

</script>