<?php foreach($updateDetails as $key=>$detail){?>
	<tr class="updateRow" id = "uRow_<?php echo $detail['examId'];?>">
		<td><?php echo $detail['update_text'];?></td>
        <td><?php echo $detail['announce_url'];?></td>
        <td id="lessGroup_<?=$detail['u_id']?>"><?php echo $detail['examName'];?><br/>
            <?php echo $detail['fGroupName'];
                if($detail['groupCount']>1){?>                                
                    <a style="color:#0065de;" onclick="showAllGroups('<?=$detail['u_id']?>')">+<?=($detail['groupCount']-1)?>more</a>
            <?php } ?>
        </td>
        <td id="moreGroup_<?=$detail['u_id']?>" style="display:none;"><?php echo $detail['examName'];?><br/><?php echo $detail['groupsNameString'];?></td>
		<td><?php echo $detail['publishedDate'];?></td>
        <td><a style="color:#0065de;"onclick="deleteUpdate('<?=$detail['u_id']?>');" >Delete</a></td>
	</tr>
<?php }?>

