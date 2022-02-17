
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Exams Given:</label>
        </div>
    </td>
    <td class="">
        <div class="ul-block">
            <ul class="">

                <?php
                foreach($exams as $examId=>$examName) { ?>
                    <li>
                        <div class="Customcheckbox2">
                           <input type="checkbox" id="<?php echo $examId.'_'.$criteriaNo;?>" class="clone" value="<?php echo $examName;?>" name="exams_<?php echo $criteriaNo;?>[]"/>
                           <label for="<?php echo $examId.'_'.$criteriaNo;?>" class="clone"><?php echo $examName;?></label>
                       </div> 
                     </li>
                <?php } ?>

            </ul>
        </div>
    </td>
