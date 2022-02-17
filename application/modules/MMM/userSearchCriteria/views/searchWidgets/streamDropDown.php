<tr>
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Stream*:</label>
        </div>
    </td>
    <td class="">
        <div class="radio-l">
            <select class="dbselect stream clone" criteriaNo="<?php echo $criteriaNo;?>" name="stream_<?php echo $criteriaNo;?>" id="stream_<?php echo $criteriaNo;?>">
                <option value="0">Select Stream</option>
                <?php foreach($streams as $stream) { ?>
                <option value="<?php echo $stream['id'];?>"><?php echo $stream['name'];?></option>
                <?php } ?>
            </select>
        </div>
    </td>
</tr>
