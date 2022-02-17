<title> Offline Responses </title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

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
<div style="margin-top:10px;">
<h1 style="margin:18px; font-size:18px !important;"> Offine Responses </h1>
</div>
<hr style="margin-left:15px; margin-right:15;"/>

<div id="offlineResponse" style="margin:18px;">
	
	<div id="userInfo" style="margin-top:30px;">
		<div id="userDetails" style="margin-left:45px;">
			<span style="font-size:15px;" >Enter User/Email Id: </span>
			<span> <input type="text" id="usersInfo"/ onblur="reportErrorMessage(userDetailsError, this.value)"></span>
			<span> <input type="submit" id="submitUserInfo" value="Submit" class="submitButton" onclick="getUserInfo()" /> </span>
			<div id="userDetailsError" style="color:red; font-size:12px;margin-top:5px;margin-left:142px;"> </div>
		</div> <!-- end of userDetails -->

		<div id="showUserInfo" style="display:none;  border: 1px solid #a1a1a1; margin:18px;">
			<table style="font-size:14px; margin-left:45px; margin:18px;">
			<tr>
				<td class="leftRow">Name:</td>      <td class="rigthRow"><span id="showName"></span></td>
			</tr>
			<tr>
				<td class="leftRow">Email:</td>      <td class="rigthRow"><span id="showEmail"></span></td>
			</tr>
			<tr>
				<td class="leftRow">Mobile:</td>      <td class="rigthRow"><span id="showMobile"></span></td>
			</tr>
			<tr>
				<td class="leftRow">Responses:</td>      <td class="rigthRow"><span id="showResponse"></span></td>
			</tr>
		</table>
		</div> <!-- end of showUserInfo -->
	</div> <!-- end of userInfo -->

	

	<div id="alreadyApplied" style="display:none; margin-top:20px;">
		<h1 style=" font-size:18px !important;"> Make Offline Response On </h1>
		<hr/>
		<div id="searchInst" style="margin-left:45px;">
			<span style="font-size:15px;" >Search Institute: </span>
			<span> <input type="text" id="instId" value='' /></span>
			<span> <input type="submit" id="submitInstId" class="submitButton" value="Submit"/ onclick="getAllCourses()"> </span>
		</div> <!-- end of searchInst -->
		<div id="courseErrorReport" style="color:red; margin-left:160px;">
		</div>
	<div id="offlineResponseArea" style="display:none; border: 1px solid #a1a1a1; margin:18px;">
	<div id="courseList" style="margin:20px;; font-size:13px;">
	</div> <!-- end of CourseList -->

	<div id="actionType" style="font-size: 15px; margin: 20px;">
		<label> Action Type : </label>
		<input type="radio" value="Applied" class="actionType" name="actionType">Applied</input>
		<input type="radio" value="Applying" class="actionType" name="actionType">Applying</input>
		<input type="radio" value="TakenAdmission" class="actionType" name="actionType">Taken Admission</input>
	</div><!-- end of actionType -->

	<div id="errorReport" style="font-size: 14px;  margin: 15px; color:red"> </div>
	<div id="courseSubmit" style="margin:20px;"> <input type="submit" id="courseSubmit" onclick="makeOfflineCourse()" value="submit" class="submitButton"></div>
	</div> <!-- end of courseSubmit -->
</div> <!-- end of offlineResponseArea -->
</div> <!-- end of offlineResponse  -->

<style type="text/css">

.leftRow{
	float:right;
	color:#808080;
}


tr{
	margin:30px;
}

p{
	margin-bottom: 7px;
}

td{
	margin:2px;
	vertical-align:top;
}

.submitButton {
	background-color: #F78640;
	border: solid 1px #D2D2D2;
	overflow: visible;
	padding: 4px 8px;
	font: bold 14px Tahoma, Geneva, sans-serif !important;
	color: #fff;
	line-height: normal;
	cursor: pointer;
	margin: 0;
}

input {
    line-height: 1.3em; 
    margin:5px;
}

.courseName{
	font-size:13px;
}
</style>
<?php $this->load->view('enterprise/footer'); ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("offlineResponses"); ?>"></script>
