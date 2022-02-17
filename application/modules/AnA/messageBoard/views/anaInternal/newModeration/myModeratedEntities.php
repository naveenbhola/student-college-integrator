<?php
//_p($myModeratedData);die;
foreach ($myModeratedData as $val) {
	$type = '';
	if($val['fromOthers']=='user' && $val['parentId']==0) $type='Question';
	if($val['fromOthers']=='user' && $val['parentId']==$val['threadId'])$type='Answer';
	if($val['fromOthers']=='user' && $val['mainAnswerId']==$val['parentId'] && $val['mainAnswerId']>0) $type='Answer Comment';
	if($val['fromOthers']=='user' && $val['mainAnswerId']!=$val['parentId'] && $val['mainAnswerId']>0) $type='Comment Reply';
	if($val['fromOthers']=='discussion' && $val['mainAnswerId']==0) $type='Discussion';
	if($val['fromOthers']=='discussion' && $val['mainAnswerId']==$val['parentId']) $type='Discussion Comment';
	if($val['fromOthers']=='discussion' && $val['mainAnswerId']>0 && $val['mainAnswerId']!=$val['parentId'] ) $type='Discussion Reply';
?>
	<tr>
		<td><?=$val['entityId']?></td>
		<?php
		if($val['fromOthers']=='discussion'){
      		$entityType = 'discussion';
         }else{
      		$entityType = 'question';
         }
		$entityUrl = getSeoUrl($val['threadId'], $entityType, $val['questionTxt']).'?referenceEntityId='.$val['msgId'];
		?>
		<td><?=$type?> <a href="<?=$entityUrl?>" target="_blank"><span class="glyphicon glyphicon-export"></span></a></td>
		<td><?=$val['msgTxt']?></td>
		<td><?=date("j M, Y g:i A", strtotime($val['modifiedDate']))?></td>
	</tr>
<?php
}
?>