<div id = "destinationlocationID" class="float_L" style="width:175px;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">Destination Country(s):<b class="redcolor">*</b> </div>
</div>
<div style="margin-left:175px">
    <div class="float_L" style="width:150px;background:url(/public/images/hpBtn.png) no-repeat left -196px;height:19px" onclick="showDestinationCountry(this, true);">
        <div id="studyPreferedCountry" style="position:relative;top:2px;font-size:11px">&nbsp;Select</div>
        <div class="clear_L withClear">&nbsp;</div>
        <script>document.getElementById("studyPreferedCountry").innerHTML= "&nbsp;Select";document.getElementById("mCountryList").value = "";</script>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
            <div class="errorMsg" id= "preferedCountry_error"></div>
        </div>
    </div>
    <div style="clear:left;font-size:1px">&nbsp;</div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<?php  $this->load->view('marketing/studyCountryOverlay'); ?>
