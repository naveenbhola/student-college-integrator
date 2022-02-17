<?php  // check compare btn is added or not

$cookieCmp = 'compare-mobile-global-categoryPage';
$courseCmpTot = array();
$checkCmpIdVal = array();
if($_COOKIE[$cookieCmp] !=''){
	$courseCmpTot = explode('|||',$_COOKIE[$cookieCmp]);
	foreach($courseCmpTot as $courseCmpTot){
		$expVal = explode('::',$courseCmpTot);
		array_push($checkCmpIdVal,$expVal[1]);
	}
}

if(count($checkCmpIdVal)>0 && $checkCmpIdVal !='' && in_array($course->getId(),$checkCmpIdVal)){
    $cmBtn = 'style="display: none;"';
    $addBtn = 'style="display: inline-block;"';
}
else{
    $cmBtn = 'style="display: inline-block;"';
    $addBtn = 'style="display: none;"';
}
?>

<input type="hidden" name="compare" id="compare<?php echo $institute->getId();?>-<?php echo $course->getId();?>" value="<?php echo $institute->getId().'::'.$course->getId();?>"/>

<a href="javascript:void(0);" track="on" action='add' trackid='<?php echo $comparetrackingPageKeyId;?>' instid='<?php echo $institute->getId();?>' courseid='<?php echo $course->getId();?>' class="srp-clg-compare btnCmpGlobal<?php echo $course->getId();?>"  id="compare<?php echo $institute->getId();?>-<?=$course->getId();?>lable" <?php echo $cmBtn;?> >
<strong id="plus-icon<?=$courseId?>" style="visibility:hidden" class="plus-icon"></strong><i class="compare-Icn"></i>compare</a>

<a href="javascript:void(0);" track="on" action='remove' instid='<?php echo $institute->getId();?>' courseid='<?php echo $course->getId();?>' id="compare<?php echo $institute->getId();?>-<?=$course->getId();?>added" class="srp-clg-compare btnCmpGlobalAdded<?php echo $course->getId();?>" <?php echo $addBtn;?>>
<i class="sprite added-icn" style="display:none"></i><i class="compare-Icn"></i>added<i class="added-mark"></i></a>