<?php
$disableBrochureButton = false;
if(isset($_COOKIE['comparedCourses']) && $_COOKIE['comparedCourses']!=''){
	$comparedCourses = json_decode($_COOKIE['comparedCourses']);
	if(isset($comparedCourses) && in_array($courseId,$comparedCourses)){
	    $disableBrochureButton = true;
	}
}
if($disableBrochureButton){
?>
<p class="eb-sent">E-brochure Sent</p>
<?php
}else{
?>
<div id="reb_button_<?php echo $courseId;?>">
<a href="javascript:void(0);" class="new-dwn-btn" value="Download E-brochure" onclick="responseForm.showResponseForm('<?php echo $courseId;?>','COMPARE_EBrochure','course',{'cta':'download_brochure','trackingKeyId': '<?php echo $trackingKeyId; ?>','callbackObj':'comparePageObj','callbackFunction': 'downloadBrochureComparePage','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});"><i class="cmpre-sprite ic-ebrocher"></i>Download E-Brochure</a>
</div>
<?php
 }
?>
