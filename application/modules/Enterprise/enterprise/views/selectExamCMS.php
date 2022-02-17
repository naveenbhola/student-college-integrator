<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','smart'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
<?php  // _P($itcourseslist); die; ?>
<div class="mar_full_10p">
	<?php echo form_open('enterprise/FilterExam/getSelectedExamCMS'); ?>
	<?php require APPPATH.'modules/User/registration/config/examConfig.php'; ?>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="fontSize_14p bld">Show Exams in Registration Form</div>
    <div class="lineSpace_10">&nbsp;</div>
	<div style="float:left;width:100%">
		<div id="lms-port-content">
			<div class="row">
			<div class="row1 Fnt13"><b>Select Course : </b></div>
		      <div class="row2">
                            <div>
                            	<select leftposition="250" style="width: 155px;" name="c_courseExam" id="c_courseExam" selected="Select" tip="entrance_exams_required" onChange="entranceExams();">
                            		<option value="-1">Select</option>
					<option value="2">MBA Exams</option>
                            		<option value="52">Engineering Exams</option>
                            	</select>&nbsp;&nbsp;
			    </div>
			    </div>
		    </div>
			<p id="hiddenError" class="row2" style="color: RED"><?php echo $errorMessage; ?></p>
			<div class="entrance_exam_list">
				<div class="examDrop">
                    <div class="row">
			
                    	<div class="row1 Fnt13"><b>Entrance Exam Required:<br/></b></div>
			
			</div>
                        <div class="row2">
			    <div>
				
				 <?php $rowHandler=0;?>
				<table border="0" style="border-collapse : collapse;" width="50%">
				 <?php foreach($allExam as $index=>$value){ ?>
				
				<div>
					<b style="font-size:13px;"> </b>
				</div>
				
			<?php	
					if($rowHandler % 2 == 0)
						echo "<tr><td>";
					else
						echo "<td>";
					?>
						<div style="margin-bottom:10px;" class="<?php echo $value->CourseId; ?>">
						<div style="font-size:12px !important;">
						<input type="checkbox" <?php foreach($selectedExam as $ch){ if($value->Id == $ch->Id){echo "checked"; break;} } ?> name="course[]" value="<?php echo $value->Id;?> " /> <?=$value->ExamName?>
						</div>
						</div>
					<?php
					if($rowHandler % 2 == 0)
						echo "</td>";
					else
						echo "</td></tr>";
						
					$rowHandler++;
					?>
						
						<?php } ?>		
				</table>		
	</div>
			<div class="clearFix"></div>
		</div>
	</div>
		</div>
			<input type="submit" value="Submit" class="row2" id="button">
				<p> <br><br></p><p> <br><br></p>
<?php $this->load->view('enterprise/footer'); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
	$j(".examDrop").hide();
	var x=document.getElementById("button");
	x.disabled = true
	
	function entranceExams(){
		var x=document.getElementById("c_courseExam").value;
		
		if (x==-1) {
		$j(".examDrop").hide();
		
		var x=document.getElementById("button");
		x.disabled = true;
		$j("#hiddenError").show();
		}else if(x==2){
		$j("#hiddenError").hide();
		$j(".examDrop").show();
		$j(".2").show();
		$j(".52").hide();
		var x=document.getElementById("button");
		x.disabled = false;
		}else if (x==52){
		$j(".examDrop").show();
		$j("#hiddenError").hide();
		$j(".2").hide();
		$j(".52").show();
		var x=document.getElementById("button");
		x.disabled = false;
		}
	}
</script>