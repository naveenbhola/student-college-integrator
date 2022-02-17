<div class="row">
	<div class="col-md-offset-3 col-md-6 style-form">
		<div class="clearfix"></div>
		<form class="form-horizontal" name="myform" action="userPointHistory" method="post" onsubmit="if(validateForm() == false){return false;}" autocomplete="off">
			<div class="form-group">
				<label for="userName" class="col-md-4 control-label">Displayname</label>
			    <div class="col-md-6">
			    	<input type="text" name="userName" id="userName" minlength="1"  maxlength="50" caption="Name" value="<?php echo $_POST['userName']; ?>" class="form-control">
			    	<div class="text-danger error_div" id="error_displayName_div"></div>
			    </div>
			</div>
			<div class="form-group">
				<div class="col-md-4"></div>
			    <div class="col-md-6">OR</div>
			</div>
			<div class="form-group">
				<label for="userId" class="col-md-4 control-label">User ID</label>
			    <div class="col-md-6">
			    	<input type="text" name="userId" id="userId" onmouseout="validateUserId()" minlength="1" maxlength="50" caption="userId" value="<?php echo $_POST['userId']; ?>" class="form-control">
			    	<div class="text-danger error_div" id="error_userId_div"></div>
			    </div>
			</div>
			<div class="form-group">
			    <div class="col-md-offset-4 col-md-6">OR</div>
			</div>
			<div class="form-group">
				<label for="emailAddress" class="col-md-4 control-label">Email ID</label>
			    <div class="col-md-6">
			    	<input type="text" name="emailAddress" id="emailAddress" minlength="1" maxlength="50" caption="email Id" value="<?php echo $_POST['emailAddress']; ?>" class="form-control">
			    	<div class="text-danger error_div" style="margin-top:5px;" id="error_email_div"></div>
			    </div>
			</div>
			<hr/>
			<div class="form-group">
				<label for="startDate" class="col-md-4 control-label">Start Date</label>
			    <div class="col-md-6">
			    	<input readonly="readonly" type="text" id="startDate" class="span2 datepicker form-control" name="startDate" value="<?php echo $_POST['startDate']!=''?$_POST['startDate']:date('Y-m-d', strtotime('-7 days')); ?>">
			    	<div class="text-danger error_div" id="error_start_div"></div>
			    </div>
			</div>
			<div class="form-group">
				<label for="endDate" class="col-md-4 control-label">End Date</label>
			    <div class="col-md-6">
			    	<input readonly="readonly" type="text" id="endDate" class="span2 datepicker form-control" name="endDate"  value="<?php echo $_POST['endDate']!=''?$_POST['endDate']:date('Y-m-d'); ?>">
			    	<div class="text-danger error_div" id="error_endDate_div"></div>
			    </div>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-4 col-sm-2">
			    	<input type="submit" name="showData" class="btn btn-default" value="Get Data">
			    </div>
		  	</div>
		</form>
	</div>
</div>