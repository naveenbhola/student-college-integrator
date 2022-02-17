<div id="page-t1op">
	<div class="cateRefineBlock" style="padding-top:0px;">
		<div id="compareFiller" style="height:46px;display:none">
			&nbsp;
		</div>
		<div class="compareBlock" id = "compareBlock" style="position:relative;width:713px; font:normal 14px Tahoma, Geneva, sans-serif; padding:15px 5px 5px 10px; background:#f4f9ff; border:1px solid #ececec;">
			<div class="compareSection" style="border-right:0 none; width:650px">
				<p class="compareTitle"><strong>Compare</strong><br /><span style="font-size:13px">upto 4 institutes</span></p>
				<div class="compareListCol" id="compareList" style="width:350px"></div>
			</div>
			<div class="sortByCol" id="sortByCol"></div>
		</div>
    </div>
</div>
<script>
var compareBoxPostionT = obtainPostitionY($('compareBlock'));
var compareBoxPostionL = obtainPostitionX($('page-top'));
if (navigator.userAgent.indexOf("Windows")>=0){
	if(navigator.userAgent.indexOf("MSIE") >= 0){
		compareBoxPostionL -= 0;
	}else {
		compareBoxPostionL -= 9;
	}
} else if(navigator.userAgent.indexOf("Firefox")<0){
	compareBoxPostionL -= 9;
}
</script>