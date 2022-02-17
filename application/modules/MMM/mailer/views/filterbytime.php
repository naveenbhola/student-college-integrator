<div class="clearFix spacer20"></div>
<div>
	<h4>Filter by time</h4>
	<div class="filter-cont">
		<!-- <input type="radio" name="timefilter[type]" value="all" onclick="timeTxtBoxChange(this)" checked="true" /> All users
		<div class="clearFix spacer5"></div> -->
		
		<input type="radio" name="timefilter[type]" value="range" onclick="timeTxtBoxChange(this)" /> Users in time range:
		
		From <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="dd/mm/yyyy" readonly="true" disabled="true" name="timefilter[from]" id="timerange_from" />
		<img src="/public/images/cal-icn.gif" id="timerange_from_img" onclick="timerangeFrom();" style="vertical-align: middle; cursor:pointer;" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		To <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="dd/mm/yyyy" readonly="true" disabled="true" name="timefilter[to]" id="timerange_to" />
		<img src="/public/images/cal-icn.gif" id="timerange_to_img" onclick="timerangeTo();" style="vertical-align: middle; cursor:pointer;" />
		&nbsp;&nbsp;
		
		<div class="clearFix spacer10"></div>
		
		<input type="radio" name="timefilter[type]" value="interval" onclick="timeTxtBoxChange(this)" /> Users in moving window: Last &nbsp;
		<input style="width: 50px; vertical-align: middle" type="text" value="" name="timefilter[interval]" id="timeinterval" disabled="true" onblur="timeIntervalTxt(this);" />
		&nbsp;days
		<?php if($userset_type == 'profile_india' || $userset_type == 'profile_abroad') { ?>
		<div class="clearFix spacer10"></div>
		<input type='checkbox' name="includeActiveUsers" checked="checked" /> Include active users in this time range
		<div class="clearFix spacer10"></div>
		<input type='checkbox' name="includeCounselingUsers" /> Include students in Shiksha counseling loop
		<?php } ?>
	</div>

	<ul class="profile-form">
		<li><label>Save Userset as:</label>
			<div class="flLt">
				<input type="text" name="usersetname" class="width-200" />
			</div>
		</li>
	</ul>

	<div class="clearFix spacer10"></div>
	<div class="button-aligner" style="float:left;">
		<input type="button" value="Save" class="orange-button" onClick="doSubmitUserset('<?php echo $userset_type; ?>');" />
	</div>
	
	<?php if($userset_type == 'profile_abroad' || $userset_type == 'profile_india'){?>
		<input type="text" name="countFlag" value ='true' style ='display:none'/>
	<?php } ?>

	<div style="margin-left: 20px;float:left; margin-top: 0px;">
		<input type="button" id="userCountInSearchCriteriaButton" value="Compute user count in this set" style="font-size:14px; padding:3px 8px;" onClick="getUserCountInSearchCriteria('<?php echo $userset_type; ?>');" />
		<span id="userCountInSearchCriteria" style="margin-left: 10px; font:bold 14px arial;"></span>
	</div>
</div>

<div class="clearFix"></div>
