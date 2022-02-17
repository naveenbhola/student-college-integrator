<div class="find-field-row">
	<input type="text" id = "homename" name = "homename" class="form-txt-field" onblur="validateDisplayNameAbroad($('homename').value,$('homename').getAttribute('caption'),$('homename').getAttribute('maxlength'),$('homename').getAttribute('minlength'),'');" validate = "validateDisplayName" maxlength = "25" minlength = "3" required = "true" caption = "name" value="Your Name" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Name"/>
    <div class="errorPlace" style="display:none;">
    	<div class="errorMsg" id= "homename_error"></div>
    </div>
</div>

