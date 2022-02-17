<?php 
//_p($allModerators);
?>
<div class="row" id="mainForm">
	<div class="col-md-6 style-form col-md-offset-3">
		<div class="clearfix"></div>
		<form class="form-horizontal" action="" onsubmit="return false;" method="post" autocomplete="off">
			<div class="form-group">
				<label class="col-md-4 control-label">Moderator :</label>
				<div class="col-md-6">
				<select class="form-control" id="lockEntForMod">
					<option value="">Select</option>
					<?php 
					foreach ($allModerators as $value) {
					?>
						<option value="<?php echo $value['userid']?>"><?php echo $value['email']?></option>
					<?php 	
					}
					?>
				</select>
				<span class="errorBox text-danger" id="lockEntForMod_error">Please choose a moderator.</span>
				<span class="warningBox text-warning" id="lockEntForMod_warning">No entity locked for this moderator.</span>
				</div>
			</div>
		  	<div class="form-group">
			    <div class="col-sm-offset-4 col-sm-2">
			      <button type="button" class="btn btn-default" id="showLockedEntity">Submit</button>
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
<div class="row" id="moderatorEntityLockedList" style="display:none;"></div>