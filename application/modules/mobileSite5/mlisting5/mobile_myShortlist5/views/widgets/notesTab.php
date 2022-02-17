<article class="lising-nav-details">
    <p class="title">Add a note for yourself</p>
    <div class="search-box" style="margin:0; display:block">
    <form id="postNote" novalidate="" autocomplete="off" onsubmit="return false;" method="post">
        <div class="serach-field" style="margin-right:0; border:1px solid #a5a6aa ">
            <input type="text" id="noteTitle" name="noteTitle" value="" style="text-transform:none;" placeholder="Write Note Title" required="true" minlength="2" maxlength="100" caption="Note Title" validate="validateStr"/>
            <div class="errorPlace Fnt11"><div id="noteTitle_error" class="errorMsg" style="display:none;"></div></div>
            <br/>
        </div>
        <div class="serach-field" style="margin-right:0; margin-top:15px; border:1px solid #a5a6aa ">
            <input type="text" name="noteBody" id="noteDesc" value="" style="text-transform:none;" placeholder="Write note description here..." required="true" maxlength="1000" caption="Note Description" validate="validateStr" />
            <div class="errorPlace Fnt11"><div id="noteDesc_error" class="errorMsg" style="display:none;"></div></div>
        </div>
        <input type="hidden" name="courseId" value="<?php echo $courseId;?>" />
    </form>
    </div>
    <div class="reminder-btn-area" style="margin-top:15px;">
        <a id ="reminderDt" href="javascript:void(0);" class="btn btn-default btn-med" style="width:auto; background:#9e9e9e"><i class="msprite reminder-bell-icon"></i>
        <input name="reminder_date" onclick="showDatePickerOnAddNote()" class="datepicker_0" value="Set Reminder"/>
        <b style="visibility:hidden">x</b>
        </a>
        <a href="javascript:void(0);" id="addNoteBtn" onclick="return validateNote(document.getElementById('postNote')); return false;" class="btn btn-default btn-med" style="width:auto">Add Note</a>
    </div>
    
    <?php 
    if($totalNotesCount){
    ?>
    <div class="prev-q"><strong>Previously added note<?php echo ($totalNotesCount > 1) ? "s" : ""; ?> (<?php echo $totalNotesCount;?>)</strong></div>
    <?php
    } ?>
    
    <ol class="notes-display" id="notesList" style="list-style-type:none;margin-left:5px;">
        <?php $this->load->view("widgets/notesTabDetails");?>
    </ol>
    <?php
        if($totalNotesCount > $batchSize){
    ?>
    <div class="load-more-block">
        <a href="javascript:void(0);" style="color:black;" onclick="getMoreNotes(<?php echo $course_id;?>);" class="btn-load-more">Load more Notes ...</a>
    </div>
    <?php
        }
    ?>
    <input type='hidden' id="totalNotesCount" value="<?php echo $totalNotesCount;?>" />
    <div id="loading_img" style="text-align:center;margin-top:20px;display:none;"><img src="/public/mobile5/images/ajax-loader.gif" border=0 /></div>
</article>