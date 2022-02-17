<?php foreach($featuredData as $dest_groupId=>$value){
        foreach($value as $orig_examId=>$detail){
               
                foreach($detail['start'] as $key=>$val){
                        foreach($val as $key1=>$val1){
                            if(!empty($val1['selfIds'])){
                              $selfIds = implode(',', $val1['selfIds']);            
                            }
        ?>
        <tr class="updateRow">
        <td><?php echo $detail['orig_exam_name'];?></td>
        <td id="lessGroup_<?php echo $dest_groupId.$orig_examId.$key.$key1;?>"><?php echo $val1['firstGroup'];?>
            <?php
                if(count($val1['groups'])>1){?>                                
                    <a style="color:#0065de;" onclick="showAllGroups('<?php echo $dest_groupId.$orig_examId.$key.$key1;?>')">+<?=(count($val1['groups'])-1)?>more</a>
            <?php } ?>
        </td>
        <td id="moreGroup_<?php echo $dest_groupId.$orig_examId.$key.$key1;?>" style="display:none;"><?php echo $val1['groupString'];?></td>
        <td><?php echo $detail['dest_exam_name'];?></td>
        <td><?php echo $detail['dest_group_name'];?></td>
        <td><?php echo $val1['ctaText'];?></td>
        <td><?php echo $val1['redirectUrl'];?></td>
        <td><?php echo $key;?></td>
        <td><?php echo $key1;?></td>
        <td><a style="color:#0065de;" href="/examPages/ExamMainCMS/manageExamPageFeaturedContent/featuredExam/edit/<?=$orig_examId;?>?selfIds=<?=$selfIds?>" >Edit</a></td>
        
        <td><a style="color:#0065de;"onclick="showConfirmLayer('<?=$contentType;?>','<?=$dest_groupId?>','<?=$orig_examId?>','<?=$key?>','<?=$key1?>');" >Delete</a></td>
        </tr>
<?php          
                 }
              }
         }
 }?>

