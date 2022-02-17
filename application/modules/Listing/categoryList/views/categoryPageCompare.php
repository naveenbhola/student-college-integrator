<?php
global $pageHeading;
if($pageHeading==""){
	$pageHeading=($request->getSubCategoryId()<=1)?$categoryPage->getCategory()->getName():$categoryPage->getSubCategory()->getName();
}
?>
<div id="compareDiv">
	<div id="compare-cont">
		<div class="back-inst">
        		<a href="#" onclick="openNormalSlide(); return false;"> <span>&nbsp;</span>Back to <?=$pageHeading?> Institutes</a>
		</div>
		<div id="confirmation-box-wrapper"></div>
		<div id="mainCompareContent">
			<div class="loaderBg" style="font-size:20px;padding:150px;"><img src="/public/images/loader.gif" align="absmiddle">&nbsp;&nbsp;Loading...</div>
		</div>
        	<div class="back-inst">
        		<a href="#" onclick="openNormalSlide(); return false;"> <span>&nbsp;</span>Back to <?=$pageHeading?> Institutes</a>
		</div>
	</div>
</div>