<tr id="workExperienceBlock_<?php echo $criteriaNo;?>" class="clone">
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Work Experience:</label>
        </div>
    </td>
    
    <td class="">
        <div class="radio-l" style="width:150px">
              
	          <select class="dbselect clone workExperience" id="minExp_<?php echo $criteriaNo;?>" name="minExp_<?php echo $criteriaNo;?>" criteriaNo="<?php echo $criteriaNo;?>" style="width:130px">
                <option value="">Min</option>
                <?php foreach($workExperience as $expId=>$expValue) { ?>
                <option value="<?php echo $expId;?>"><?php echo $expValue;?></option>
                <?php } ?>
            </select>

        </div>
        
        <div class="radio-l" style="width:100px">
           
	          <select class="dbselect clone workExperience" id="maxExp_<?php echo $criteriaNo;?>" name="maxExp_<?php echo $criteriaNo;?>" criteriaNo="<?php echo $criteriaNo;?>" style="width:130px">
                <option value="">Max</option>
                <?php foreach($workExperience as $expId=>$expValue) { ?>
                <option value="<?php echo $expId;?>"><?php echo $expValue;?></option>
                <?php } ?>
            </select>

        </div>
    </td>
</tr>

<tr id="examsBlock_<?=$criteriaNo;?>" class="clone" style="display:none"></tr>