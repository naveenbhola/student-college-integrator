<div id="addColumnOverlay" style="display:none;margin:20px;width:100%;text-align:center">
	<form id="addColumn" onsubmit="saveColumn(); return false;" novalidate>
		<lable>
			<h3 style="font-size: 16px">Name : </h3>
			<input type="text" name="columnName" profanity="true" minlength="3" maxlength="50" required="true" validate="validateStringColumnNames" caption="Column Name" tip="multipleapply_name" id="columnName">
			<div class="clearFix"></div>
			<div style="display: none;"><div style="padding-left:60px; clear:both; display:block" id="columnName_error" class="errorMsg">Please enter Column Name</div></div>
		</lable>
		<div style="margin-top:20px;width:100%;text-align:center">
			<input type="submit" class="orange-button" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-size: 16px" href="#" onclick="hideOverlayAnA();return false;">Cancel</a>
		</div>
	</form>
</div>