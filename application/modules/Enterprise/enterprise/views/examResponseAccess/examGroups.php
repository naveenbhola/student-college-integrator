<td class="nxt-td">
    <div class="nxt">
        <label class="label">*Select Exam Course Group(s):</label>
    </div>
</td>

<td class="">
    <div class="ul-block">
        <ul>
            <?php
            if(!empty($examGroups) && count($examGroups)>0){ ?>
                <li>
                    <div class="Customcheckbox2">
                        <input type="checkbox" class="parentEntity" id="<?php echo 'group_all';?>" value="<?php echo "examGroups";?>" />
                        <label for="group_all" class=""><?php echo "All";?></label>
                    </div>
                    <?php foreach($examGroups as $key=>$examGroup) { ?>
                        <div class="l-col">
                            <ul class="">
                                <li>
                                    <div class="Customcheckbox2">
                                        <input type="checkbox" id="<?php echo $examGroup['id']?>" value="<?php echo $examGroup['id'];?>" name="selectedGroupIds[]" parentId="group_all" class="group_all childEntity" />
                                        <label for="<?php echo $examGroup['id']?>" class=""><?php echo $examGroup['name'];?></label>
                                    </div> 
                                </li>
                            </ul>
                        </div>
                    <?php }?> 
                </li>
            <?php } ?>
        </ul>
    </div>
    <div id="group_error" class="errorMsg" style="display:none;">Please select atleast one exam course group</div>
</td>
