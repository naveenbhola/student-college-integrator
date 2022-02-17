<?php if($userset_type == "activity"){
    $heading  = "<h3>Date Range <span>Select users based on when they created <strong>Responses</strong> on the above Colleges/Courses</span></h3>";
    $timeRangeAll = "<span>All users who created responses on the above Colleges/Courses.</span>";
    $timeRange = "Users who created responses for selected duration.";
    $nRange = "Users who created responses in the last n days selected.";
} else if($userset_type == "exam"){
    $heading = "<h3>Date Range <span>Select users based on when they created <strong>Exam Responses</strong> on the above exam(s)</span></h3>";
    $timeRangeAll = "<span> All users who created responses on the above exam(s).</span>";
    $timeRange = " Users who created exam responses for selected duration.";
    $nRange = " Users who created exam responses in the last n days selected.";
}
else{
    $heading = "<h3>Date Range <span>Select users based on when their <strong>Profile</strong> was created</span></h3>";
    $timeRangeAll = "<span>All users who have the above profile.</span>";
    $timeRange = "Users whose profile was created for selected duration.";
    $nRange = "Users whose profile was created in last n days selected.";
} 
?>


<div class="ext-div">
    <?php echo $heading; ?>
    <table class="user-table" cellpadding="0" cellspacing="0">

        <tr>
            <td>
                <div class="radio" style="display:inline-block">
                    <input id="timeRangeAll" class="timeRange" type="radio" name="timeRange" value="all" checked="checked" />
                    <label for="timeRangeAll" style="width:125px">All <span id="timeRangleAllText" >(Last 2 Years)</span></label>
                    <?php echo $timeRangeAll; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="radio" style="width:100px;display:inline-block">
		            <input id="timeRangeDuration" class="timeRange" type="radio" name="timeRange" value="duration" />
		            <label for="timeRangeDuration" style="width:150px; margin-top:7px;">Time Duration</label>
		 		</div>
                
                <div class="to-block">
                    <div class="to-col">
                        <label style="margin-left:22px;">From:</label>
                        <input type="text" class="txt-ip cal-in" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationFrom" name="timeRangeDurationFrom" disabled="disabled"/>
                        <img src="/public/images/cal-icn.gif" id="timeRangeFromImage"/>
                    </div>
                    <div class="to-col">
                        <label style="margin-left:22px;">To:</label>
                        <input type="text" class="txt-ip cal-in" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationTo" name="timeRangeDurationTo" disabled="disabled"/>
                        <img src="/public/images/cal-icn.gif" id="timeRangeToImage" />
                        <i class="pwaicono-exclamationCircle default-exclCircle">
                            <div class="help-text-popup">
                                <?php echo $timeRange; ?>
                            </div>
                        </i>
                    </div>
                    <label id="timeRangeDurationError" style="float:left;margin-left:56px;"></label>
                </div>

            </td>
        </tr>
        <tr>
            <td>
                <div class="radio" style="width:100px;display:inline-block">
		            <input id="timeRangeInterval" class="timeRange" type="radio" name="timeRange" value="interval" />
		            <label for="timeRangeInterval" style="width:150px; margin-top:7px;">Moving Window</label>
				</div>
        
                <div class="to-block">
                    <div class="to-col">
                        <div style="float:left;width:100%;">
                            <label style="margin-left:27px;">Last:</label>
                            <input type="text" class="txt-ip cal-in" placeholder="0" id="timeRangeIntervalDays" name="timeRangeIntervalDays" disabled="disabled"/> Days
                             <i class="pwaicono-exclamationCircle default-exclCircle">
                                <div class="help-text-popup">
                                    <?php echo $nRange; ?>
                                </div>
                            </i>
                        </div>
                        <span id="timeRangeIntervalError" style="float:left;margin-left:56px"></span>
                    </div>                 
                </div>
            </td>
        </tr>
        <?php if ($userset_type != "activity" && $userset_type != "exam" ) { ?>
        <tr>
            <td>
                <div class="Customcheckbox2">
                    <input type="checkbox" id="activeUsers" checked="checked" name="includeActiveUsers"/>
                    <label for="activeUsers">Include active	users in the selected time period</label>	
                </div>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>	
