<div class="layer-outer">
    <div class="layer-title">
        <a class="close" href="javascript:void(0)" onclick="redirectOnClose();"></a>
        <div class="title">You had shown interest in pursuing <?php echo $oldCourseName; ?> earlier, which of these best represent your education interest</div>
    </div>
    <div class="layer-contents" style="padding-bottom: 10px;">
	<div id="user-pref-overlay">
	    <div id="user-pref-panel">
		<form id = "user-perf-form" method="post">
		<div>
		    <div style="padding:0 0 15px 0">
			<div class="float_L">
			    <ul>
				<li><input type="radio" name="userprefoptions" value="1"><label id="userprefoption1" style="margin-left: 4px;">I am still interested in <?php echo $oldCourseName; ?></label></li>
				<li><input type="radio" name="userprefoptions" value="2"><label id="userprefoption2" style="margin-left: 4px;">I am now interested in <?php echo $newCourseName; ?></label></li>
				<li><input type="radio" name="userprefoptions" value="3"><label id="userprefoption3" style="margin-left: 4px;">I am interested in both <?php echo $oldCourseName; ?> and <?php echo $newCourseName; ?></label></li>
			    </ul>
			</div>
			<div class="clearFix"></div>
		    </div>
		    <div style="margin:0px 0 0 8px;">
			<input type="button" id="userPrefSubmit" value="Submit" class="orange-button" onclick="shikshaUserRegistration.logExistingUserPreference(<?php echo $insertId; ?>, '<?php echo $redirectURL; ?>'); return false;">
		    </div>
		</div>	
		</form>
	    </div>
	</div>
    </div>
</div>

<script>
    function redirectOnClose() {
	window.location = '<?php echo $redirectURL; ?>';
    }
</script>