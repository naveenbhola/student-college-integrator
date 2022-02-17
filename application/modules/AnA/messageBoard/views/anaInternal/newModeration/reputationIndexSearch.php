<div class="row" id="mainForm">
	<div class="col-md-6 style-form col-md-offset-3">
		<div class="clearfix"></div>
		<form class="form-horizontal" action="" onsubmit="return false;" method="post" autocomplete="off">
			<div class="form-group">
				<label class="col-md-4 control-label">Filter By:</label>
				<div class="col-md-6">
				<select class="form-control" id="filterBy">
					<option value="displayname" selected="selected">Display Name</option>
					<option value="userid">UserId</option>
					<option value="email">Email</option>
				</select>
				</div>
			</div>

			<div class="form-group">
		    <label for="userId" class="col-md-4 control-label">Search User:</label>
		    <div class="col-md-6">
		      <input type="text" id="searchkey" name="searchkey" class="form-control" />
		      <span id="error_key" style="top:7px;position:relative;"></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-4 col-sm-2">
		      <button type="button" class="btn btn-default" id="_submitBtn">Submit</button>
		    </div>
		    <div class="col-sm-2">
		      <button type="button" class="btn btn-default" id="reset">Reset</button>
		    </div>
		    <div class="col-md-1 text-center" id="ldr" style="display:none;padding:0px;">
					<img src="<?=SHIKSHA_HOME?>/public/images/loader_hpg.gif">
				</div>
		  </div>
		</form>
	</div>
</div>
<?php $this->load->view('anaInternal/newModeration/userReputationIndexList'); ?>