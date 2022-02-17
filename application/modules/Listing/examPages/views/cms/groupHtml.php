<table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0">
<?php if(is_array($examList) && count($examList) > 0){?>
<tr>
    <th>S.No.</th>
    <th>Course Group Name</th>
    <th>Year</th>
    <th>Action</th>
    <th>Manage Content</th>
</tr>
<?php 
$num = 0;
foreach ($examList as $key => $value) {
    $num++;
?>
<tr>
    <td><?php echo $num;?></td>
    <td><?php echo htmlentities($value['groupName']); if($value['isPrimary'] == 1){?> <span style="color: blue;margin-left: 10px;"> ( Primary )</span></span><?php }?></td>
    <td><?php echo ($value['year']) ? $value['year'] : '- -';?></td>
    <td><a href="/examPages/ExamMainCMS/manageGroups/<?php echo $value['groupId']?>">Edit </a>
    <?php if($value['isPrimary'] == 0){?>
     | <a onclick="return confirm('Are you sure you want to remove this record ?');" href="/examPages/ExamMainCMS/removeGroups/<?php echo $value['groupId']?>/<?php echo $value['examId'];?>">Remove</a>
     <?php }?>
    </td>
    <td>
       <?php if(!array_key_exists($value['groupId'], $groupPageIdList)) { ?> 
            <a href="/examPages/ExamPagesCMS/addEditExamContent/<?php echo $value['examId']?>/<?php echo $value['groupId']?>">Add Content</a>
       <?php }elseif(array_key_exists($value['groupId'], $groupPageIdList)) { ?>
            <a href="/examPages/ExamPagesCMS/addEditExamContent/<?php echo $value['examId']?>/<?php echo $value['groupId']?>">Edit Content</a>
       <?php } ?>
    </td>
</tr>
<?php 
    }
}else if(count($examList) < 1){
    echo '<tr>';
    echo '<td style="color:red;font-size:12px;" colspan="10">No data found.</td>';
    echo '</tr>';
}
?>
</table>