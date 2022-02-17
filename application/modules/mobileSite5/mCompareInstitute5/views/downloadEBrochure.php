<?php
$disableBrochureButton = false;
 $courseId = $courseObj->getId();
if(isset($_COOKIE['comparedCourses']) && $_COOKIE['comparedCourses']!=''){
        $comparedCourses = json_decode($_COOKIE['comparedCourses']);
        if(isset($comparedCourses) && in_array($courseId,$comparedCourses)){
            $disableBrochureButton = true;
        }
}
if($disableBrochureButton){
?>
<a class= "applynow-txt" style = "display: block !important">
	<i class="readonly" aria-hidden="true"></i>Request Brochure
</a>

<?php
}else{
?>
<div id="reb_button_<?php echo $courseId;?>">
<a style="font-size: 0.7em;" class="button new-orng small" href="javascript:void(0);" id="request_e_brochure<?php echo $courseId;?>" onClick="trackReqEbrochureClick('<?php echo $courseId;?>');responseForm.showResponseForm('<?php echo $courseId;?>','MOB_COMPARE_EBrochure','course',{'cta':'download_brochure','trackingKeyId': '308','callbackObj':'comparePageObj','callbackFunction': 'downloadBrochureComparePage','callbackFunctionParams': {'courseId':'<?php echo $courseId; ?>'}},{});">
<i class="icon-pencil" aria-hidden="true" id = "icon-brochure<?php echo $courseId;?>"></i>Request Brochure</a>
</div>
<?php
 }
?>
