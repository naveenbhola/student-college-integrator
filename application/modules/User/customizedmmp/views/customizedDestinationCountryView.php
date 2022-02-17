<div id = "destinationlocationID" class="float_L" style="width:35%;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">Destination Country(s)<b class="redcolor">*</b>:</div>
</div>
<div style="width:63%;float:right;text-align:left;">
    <div class="float_L" style="width:150px;background:url(/public/images/hpBtn.png) no-repeat left -196px;height:19px;margin-top:2px;" onclick="showDestinationCountryOverlay(this, true);">
        <div id="studyPreferedCountry" style="color:#000;position:relative;font-size:11px;top:2px;">&nbsp;Select</div>
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
<div class="lineSpace_10" style="clear:both;">&nbsp;</div>
<?php $this->load->view('customizedmmp/customizedStudyCountryOverlay');?>
