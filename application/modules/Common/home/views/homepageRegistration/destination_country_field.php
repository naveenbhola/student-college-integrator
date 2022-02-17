<style>.quesAnsBullets{background-image: none;background: none}</style>
<style>
.quesAnsBullets{background-image: none;background: none} 
.ctryContent li {float: left;list-style: none outside none;width: 100%;}
ul.ctryContent {
padding-left: 0px!important;
margin-left: -2px!important;
}  
</style>
<!--[if lte IE 6]>
    <style>
* html ul.ctryContent {
margin-left: -2px!important;
width: 22%!important;
} 
</style>
<![endif]-->
<div class="find-field-row">
<div id = "destinationlocationID" style="width:175px;line-height:18px; float:left; display:none">
    <label>:</label>
</div>
<div onblur="validateDestinationCountry();" class="selectStyleDiv" onclick="showDestinationCountry(this, true);">
	<span class="selectStyleArrow"></span>
	<div id="studyPreferedCountry">Destination Country(s)</div>
    
    <div class="clearFix"></div>
    <script>document.getElementById("studyPreferedCountry").innerHTML= "Destination Country(s)";document.getElementById("mCountryList").value = "";</script>
</div>
<div class="clearFix"></div>
<div>
	<div class="errorPlace" style="display:none;">
    	<div class="errorMsg" id= "preferedCountry_error"></div>
    </div>
</div>
<div class="clearFix"></div>
<?php  $this->load->view('home/homepageRegistration/studyCountryOverlay'); ?>
</div>