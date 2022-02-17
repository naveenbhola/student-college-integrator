<?php foreach($featuredData as $key=>$detail){
        ?>
	<tr class="updateRow">
	<td><?php echo $detail['examName'];?></td>
        <td id="lessGroup_<?php echo $detail['l_id'];?>"><?php echo $detail['fGroupName'];?>
            <?php
                if($detail['groupCount']>1){?>                                
                    <a style="color:#0065de;" onclick="showAllGroups('<?php echo $detail['l_id'];?>')">+<?=$detail['groupCount']-1?>more</a>
            <?php } ?>
        </td>
        <td id="moreGroup_<?php echo $detail['l_id'];?>" style="display:none;"><?php echo $detail['groupsNameString'];?></td>
	    <td><?php echo $detail['campaign_name'];?></td>
        <td><?php echo $detail['start_date'];?></td>
        <td><?php echo $detail['end_date'];?></td>
        <td><?php echo $detail['viewCount'];?></td>
        <td><?php echo $detail['clickCount'];?></td>
        <td><a style="color:#0065de;" href="/examPages/ExamMainCMS/manageExamPageFeaturedCDLinks/edit/<?=$detail['l_id']?>" >Edit</a></td>
        
        <td><a style="color:#0065de;" onclick="showConfirmLayer('CD_links','<?=$detail['l_id']?>');" >Delete</a></td>
	</tr>
<?php          
 }?>

