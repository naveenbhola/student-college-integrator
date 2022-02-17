<?php 
$offset = $page*$batchSize;
$i =1;
    foreach ($notes as $notesRow) {

        $showReadMore = false;
        if(strlen($notesRow['body']) > 120){
            $shortenedtxt = substr($notesRow['body'], 0, 120);
            $showReadMore = true;
        }
?>
    
<li noteId='<?=$notesRow['note_id']?>' style="margin-bottom:10px;">
    <div>
        <div style="margin-bottom:8px;">
            <label style="color:#1C1D1D;width:auto !important;"><?php echo ($offset+($i++)).". ".html_escape($notesRow['title'])?></label>
        </div>
        <div style="float:left;width:100%;margin-bottom:5px; position:relative;"><span style="float:left;position: relative; padding-left: 13px;vertical-align:top;"><i style="left: 0px; top: 1px; position: absolute;" class="msprite notfy-watch"></i><?php echo html_escape($notesRow['last_updated_time_text'])?></span><div class="mysSetngSmlIcn"><i class="msprite setting-icon flRt"></i></div></div>
        <div class="clearfix"></div>
    </div>
    <?php 
        if($showReadMore){
    ?>
            <div class="shortenedtxt notes-detail"><?php echo html_escape($shortenedtxt)?>..<a href="javascript:void(0);" onclick="expandNotesTxt(this);">[read more]</a></div>
            <div class="fulltxt notes-detail" style="display:none;"><?php echo html_escape($notesRow['body'])?></div>
    <?php
        }else{
    ?>
            <div class="notes-detail"><?php echo html_escape($notesRow['body'])?></div>
    <?php
        }
    ?>
  <div class="setRemndrBox notes-detail" style="display:<?= !empty($notesRow['reminder_date_time_text']) ? 'block' : 'none' ?>;"><i class="msprite mys-setRemndIcn"></i><span id="reminderTxt_<?=$notesRow['note_id']?>">Reminder is set on <?=$notesRow['reminder_date_time_text'];?></span></div>  
</li>
<?php
    }
?>