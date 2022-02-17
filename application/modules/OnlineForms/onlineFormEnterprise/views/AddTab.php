<div id="addTabOverlay" style="display:none;margin:20px;width:100%;text-align:center">
	<form id="addTab" onsubmit="saveTab(); return false;" novalidate>
		<lable>
			<h3 style="font-size: 16px">Name : </h3>
			<input type="text" name="tabName" profanity="true" minlength="3" maxlength="50" required="true" validate="validateStringColumnNames" caption="Tab Name" tip="multipleapply_name" id="tabName">
			<div class="clearFix"></div>
			<div style="display: none;"><div style="padding-left:60px; clear:both; display:block" id="tabName_error" class="errorMsg">Please enter Tab Name</div></div>
		</lable>
		<div style="margin-top:20px;width:100%;text-align:center">
			<input style="font-size: 16px" type="submit" class="orange-button" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-size: 16px" href="#" onclick="hideOverlayAnA()">Cancel</a>
		</div>
	</form>
</div>