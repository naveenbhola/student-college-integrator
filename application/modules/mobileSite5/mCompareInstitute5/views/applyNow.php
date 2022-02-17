<?php 
$trackingPageKeyId = '651';
foreach($courseIdArr as $key => $courseId){
	$hasForm[$key] = Modules::run('mOnlineForms5/OnlineFormsMobile/applyNowButton',$courseId,$trackingPageKeyId,'COMPARE_PAGE_BOTTOM');
}
if(!(empty($hasForm[0]) && empty($hasForm[1])))
{?>
<tr id="row9_H">
	<td colspan="2" class="compare-title"><p class="new-msg">Sure about this college?</p>
	<p class="new-msg1">Start the Application Process</p</td>
</tr>
<tr id="row9_C" align="center">
<?php $z=1; foreach($courseIdArr as $key => $courseId){
	if(!empty($hasForm[$key])){
		$instId = $instIdArr[$courseId];
?>
<td class="<?php echo ($z<$compare_count_max)?'border-right':'';?>">
<a id="startApp<?php echo $hasForm[$key]['courseId'];?>" style="width:97%" class="button new-blue small flLt" href="javascript: void(0)" onClick="emailResults('<?php echo $hasForm[$key]['courseId'];?>', '<?php echo base64_encode($instituteObjs[$instId]->getName());?>', '<?php echo $hasForm[$key]['isInternal'];?>','<?php echo $trackingPageKeyId;?>');trackEventByGAMobile('Start_Application_<?php echo $courseId; ?>_<?= date('Y-m-d H:i:s');?>');"><span>Apply Now</span></a>
</td>
<?php $z++;}
else{?>
<td>
	<a class= "apply-btn-noui">--</a>
</td>
<?php }
}?>
</tr>
<?php } ?>