<div>
    <?php foreach($arrayOfCourseObj as $key=>$courseObj){ ?>
        <input <?php if(in_array($courseObj->getId(),$allCourseIds)){  echo "checked";}?> id="otherCourseId<?php echo $courseObj->getId();?>"  type="checkbox" value="<?php echo $courseObj->getId();?>" <?php if($selectedCourseId == $courseObj->getId()){ echo "disabled='disabled'"; echo "checked";}?>/> <?php echo $courseObj->getName();?>&nbsp;<?php if($selectedCourseId == $courseObj->getId()){ echo "<font color='grey'>(Applied Course)</font>";}?><br/>
    <?php } ?>
    <div class="clearFix spacer5"></div>
   
    <?php if(!empty($arrayOfCourseObj)){?>
    <input type="button" onclick="addOtherCourseWithCA('<?php echo $userId;?>','<?php echo $selectedCourseId;?>','<?php echo $badge;?>','<?php echo $uniqueId;?>');" class="orange-button" value="Done"/>
    <?php }else{?>
    <span style="color:red">No Course Available.</span>
    <?php }?>
    
    <input type="button" onclick="hideOverlayAnA();" class="orange-button" value="Cancel"/>
</div>
