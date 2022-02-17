<div class="bld mb5">Board Name:</div>
<select name="boardName" id="boardName" caption="Board Type" class="textboxBorder" onchange="prepareBoardClass()">
	<option value="">Select board</option>
	<?php foreach($boardList as $board){?>
    		<option value="<?php echo $board['name']?>"><?php echo $board['name'].' ( '.$board['fullName'].' )'; ?></option>
    <?php }?>
</select>