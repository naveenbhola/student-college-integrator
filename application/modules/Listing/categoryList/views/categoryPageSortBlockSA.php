<?php
if((($institutes && (count($institutes) > 1))) || (isset($appliedFilters) && count($appliedFilters)>0)){
?>

	<div id="compareFiller" style="height:46px;display:none">
						&nbsp;
	</div>
	<div class="compareBlock" id = "compareBlock" style="position:relative;width:653px;">
	<div class="compareSection">
                <p class="compareTitle"><strong>Compare</strong><br><span>upto 4 institutes</span></p>
                <div id="compareList" class="compareListCol"><ul><li></li><li></li><li></li><li></li></ul><div class="compareButtonCol"><input type="button" onclick="compareColleges(); return false;" title="Compare" value="Compare" class="compareButton"></div></div>                
            </div>
            
<!--	<div class="sortByCol" style="float:right"> -->
		<div class="sortByCol" id="sortByCol">
		<strong>Sort by:</strong>
		<select onchange="sortInstitutes();" id="categorySorter">
			<option value="none">
						All Institutes
			</option>
			<option value="lowfees">
						Lowest Fees
			</option>
			<option value="highfees">
						Highest Fees
			</option>
			<option value="dateOfCommencement">
						Earliest start date ascending
			</option>
			<option value="reversedateOfCommencement">
						Earliest start date descending
			</option>
			<option value="viewCount">
						Popularity
			</option>
		</select>
		<script>
			$("categorySorter").value = "<?=$request->getSortOrder()?>";
		</script>
	</div>

	<div class="clearFix"></div>
</div>
<?php
}
?>
<script>
var compareBoxPostionT = obtainPostitionY($('compareBlock'));
var compareBoxPostionL = obtainPostitionX($('page-top'));
//alert(navigator.userAgent);
if (navigator.userAgent.indexOf("Windows")>=0){
			if(navigator.userAgent.indexOf("MSIE") >= 0){
						compareBoxPostionL -= 0;
			}else{
						compareBoxPostionL -= 9;
			}
}else if(navigator.userAgent.indexOf("Firefox")<0){
			compareBoxPostionL -= 9;
}
</script>
