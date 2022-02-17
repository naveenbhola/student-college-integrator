<select name="Sales_Branch" id="Sales_Branch" size="1">
<?php $i = 1;
    foreach($branches as $key=>$val){ 
        if ($i==1){
    ?>
    <option value="<?php echo $val['BranchId']; ?>" selected><?php echo $val['BranchName']; ?></option>
    <?php }else{ ?>
    <option value="<?php echo $val['BranchId']; ?>" ><?php echo $val['BranchName']; ?></option>
    <?php } ?>
    <?php $i++; 
    } ?>

</select>
