<?php 
//_p($allModerators);
?>
<div class="row" id="mainForm">
	<div class="col-md-6 style-form col-md-offset-3">
		<div class="clearfix"></div>
		<form class="form-horizontal" action="/messageBoard/MessageBoardInternal/MISForListingANA" onsubmit="" method="post" autocomplete="off" id="listingMISForm">
			<div class="form-group">
				<label class="col-md-4 control-label">Start Date :</label>
				<div class="col-md-6">
				<input type="text" name="startDate" id="date" value="<?=date('Y-m-d', strtotime('-1 day'))?>" />
				<span class="errorBox text-danger" id="lockEntForMod_error">Please choose a moderator.</span>
				<span class="warningBox text-warning" id="lockEntForMod_warning">No entity locked for this moderator.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">End Date :</label>
				<div class="col-md-6">
				<input type="text" name="endDate" id="dateTo" value="<?=date('Y-m-d', strtotime('-1 day'))?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Question Type :</label>
				<div class="col-md-6">
				<select class="form-control" name="quesType" id="quesType">
					<option value="allListingQues">All Listing Questions</option>
					<option value="courseQues">Course Only Questions</option>
					<option value="instituteQues">Institute Only Questions</option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">CR Availability</label>
				<div class="col-md-6">
				<select class="form-control" name="crAvailability" id="quesType">
					<option value="allCases">Indifferent (All)</option>
					<option value="courseCR">Course CR Available</option>
					<option value="collegeOnlyCR">Institute Only CR Available</option>
					<option value="noCR">No CR Available</option>
				</select>
				</div>
			</div>
		  	<div class="form-group">
			    <div class="col-sm-offset-4 col-sm-2">
			      <button type="button" class="btn btn-default" id="listingMISSubmit">Download Excel</button>
			    </div>
			    <!--div class="col-sm-2">
			      <button type="button" class="btn btn-default" id="reset">Reset</button>
			    </div-->
			    <div class="col-md-1 text-center" id="ldr" style="display:none;padding:0px;">
					<img src="<?=SHIKSHA_HOME?>/public/images/loader_hpg.gif">
				</div>
		  	</div>
		</form>
	</div>
</div>
<div class="row" id="moderatorEntityLockedList" style="display:none;"></div>