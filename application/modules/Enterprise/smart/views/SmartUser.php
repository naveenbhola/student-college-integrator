<html>
<head>
<style>
body {
    line-height: 200%;
}
.user-selectfield{width:236px;}
@media screen and (-webkit-min-device-pixel-ratio:0) {
.user-selectfield{width:174px !important;}
}
</style>
</head>
<body>
							
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

<?php
$attributes = array('onsubmit' => 'return submitCall()', 'id' => 'myform');
echo form_open('smart/addSmartUser/useradd',$attributes);
?>
<div style="color:red">
<?php echo validation_errors(); ?>								
</div>
<center>
<div style="font-size: medium; color :red">
<?php
if($msg)
   echo $msg.": ".$messagetext;
?>
</div>
<p>

<div class="bld fontSize_14p OrgangeFont" style="margin-bottom:15px;">Add New User</div>
<div class="form-group" style="margin-bottom:7px;">
<label for="user_email" id="labelEmail" style="width: 100px; display: inline-block;">User Email:</label>
<input type="email" class="form-control" name="user_email" id="user_email" placeholder="Enter User Email" onblur="validateFields(this)" style="width: 252px">
<div class="errorDiv" style="margin-left:54px; color:red;"></div>								
</div>

<div class="form-group" style="margin-bottom:7px;">
<label for="emp_id" id="labelEmpId" style="width: 100px; display: inline-block;">Employee ID:</label>
<input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Enter Employee ID" onblur="validateFields(this); validateEmpID()" style="width: 252px">
<div class="errorDiv" style="margin-left:54px; color:red;"></div>								
</div>

<div class="form-group" style="margin-bottom:7px;">
  <label for="role" id="labelRole" style="width: 100px; display: inline-block;">User Role:</label>
  <select id="role" name="role" onblur="validateFields(this)" onchange="validateFields(this)" class="user-selectfield" style="width: 252px !important">
   <option value=-1>Select</option>								
<?php foreach($role as $k=>$v){
?>
   <option value="<?php echo $k;?>"><?php echo $v; ?></option>
<?php								
}
?>
</select>
<div class="errorDiv" style="margin-left:54px; color:red;"></div>								
</div>

<div class="form-group" style="margin-bottom:7px;">
<label for="mgr_email" id="labelMgr" style="width: 100px; display: inline-block;">Manager Email:</label>
<input type="email" class="form-control" name="mgr_email" id="mgr_email" placeholder="Enter Manager's Email" onblur="validateFields(this)" style="width: 252px">
<div class="errorDiv" style="margin-left:54px; color:red;"></div>								
</div>

<div class="form-group" style="margin-bottom:7px;">
<label for="branch" id="labelBranch" style="width: 100px; display: inline-block; ">Branch:</label>
<select id="branch" name="branch" onblur="validateFields(this)" onchange="validateFields(this)" class="user-selectfield" style="width: 252px !important">
<option value=-1>Select</option>								
<?php foreach($branch as $k=>$v){
?>
<option value="<?php echo $k; ?>"> <?php echo $v; ?></option>
<?php								
}
?>
</select>
<div class="errorDiv" style="margin-left:54px; color:red;"></div>								
</div>

<?php
echo form_submit('submit','Submit');
?>
</p>
</center>
<?php $this->load->view('enterprise/footer'); ?>
<script>

function validateFields(field){
     var x=field.value;
     console.log(x);
     if (x=="" || x==null || x==-1) {
          $j(field)(".errorDiv").show();
	  $j(field)(".errorDiv").html("*This is a required field");
     }
     else{
	  $j(field)(".errorDiv").hide();
	  $j(field)(".errorDiv").html("");
     }
     
}

function validateEmpID(){
			var empId=$('emp_id').value;					
			if(isNaN(empId) || empId <= 0)  
			{
				alert("Employee ID has to be a positive integer.");
				return false;
			}else{  
				return true;
			}
}

function submitCall(){
								
		if((($('user_email').value) == "") || ($('mgr_email').value == null)){
			alert("Fields cannot be empty");
			return false;										
		}
		if(($('user_email').value) == $('mgr_email').value){
								
			alert("Please enter different ids for user and manager");
			return false;
		}
		if ($('branch').value == -1) {
			alert("Please select a branch");
			return false;
		}
		if(!(validateEmpID())){
			return false;					
		}
		if ($('role').value == -1) {
			alert("Please select a role");
			return false;
		}
	return true;
}
</script>
</body>
</html>
