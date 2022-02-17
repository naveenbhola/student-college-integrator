<select name="ManagerId" id="ManagerId">
<option value="">Select Manager</option>
<?php for($i=0;$i<count($managerList);$i++) { ?>
<option value="<?php echo $managerList[$i]['userId'];?>"><?php echo $managerList[$i]['ManagerName'];?></option>
<?php } ?>
</select>
