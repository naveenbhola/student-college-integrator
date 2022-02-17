<div>
    <div class="float_L" style="width:35%;line-height:18px">
        <div class="txt_align_r" style="padding-right:5px">Desired Graduation Level<b class="redcolor">*</b>:</div>
    </div>
    <div style="float:right;width:63%;text-align:left;">
        <div style="float:left;">
            <input style="margin-left:0px;height:auto;" blurMethod="desiredCourseLevelOnBlur();" type="radio" id="desiredCourseLevel_ug" name="desiredCourseLevel" value="ug" onclick="toggleFormForDesiredCourseLevel('ug');"/>
        </div>
        <div style="float:left;margin-top:3px;">
            Graduation 
        </div>
        <div style="float:left;">
            <input style="height:auto;" blurMethod="desiredCourseLevelOnBlur();"  type="radio" id="desiredCourseLevel_pg" name="desiredCourseLevel" value="pg" onclick="toggleFormForDesiredCourseLevel('pg');"/>
        </div>
        <div style="float:left;margin-top:3px;">
            Post Graduation &nbsp; &nbsp;
        </div>
        <div style="clear:both"></div>
        <div style="float:left;">
            <div class="errorMsg" id="desiredCourseLevel_error" style="*padding-left:4px"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
<div class="lineSpace_10" style="clear:both;">&nbsp;</div>

<script style="text/javascript">
 function desiredCourseLevelOnBlur() {
    var pg_ele = document.getElementById("desiredCourseLevel_pg");
    var ug_ele = document.getElementById("desiredCourseLevel_ug");
    if(pg_ele != undefined && ug_ele != undefined) {
        var error_ele = document.getElementById("desiredCourseLevel_error");
        if(pg_ele.checked || ug_ele.checked) {
            if(error_ele != undefined){
                error_ele.style.display = "none";
            }
        } else {
            if(error_ele != undefined){
                error_ele.style.display = "block";
                error_ele.innerHTML = "Please select the desired course level";
            }
        }
    }
 }
</script>