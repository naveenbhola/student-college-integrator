<?php 
if(empty($noteData['title'])) {
	echo "No Data Found";
   return;
}

?>

<div style="" class="mys-foternav mys-course-settings">
	
    <ul>
		<li class="reminderButton remndBtnPrntCss" >
    <a href="javascript:void(0)">
    <i class="msprite f-remainder"></i>
    <p class ="reminderText_<?=$noteId?>" style="display:inline-block; margin-right:5px;">
    <?= empty($reminderDate) || $reminderDate == '0000-00-00 00:00:00' ? 'Set Reminder' : 'Reminder is set on'?>
    </p>
    <input class="datepicker_<?=$noteId?>" class="msprite f-remainder" type="text" value="<?= empty($reminderDate) || $reminderDate == '0000-00-00 00:00:00' ? '' : $reminderDate ?>" style="border:0px;display:inline-block;padding:10px 0px;  max-width: 90px;">
      <b style="visibility: <?= empty($reminderDate) || $reminderDate == '0000-00-00 00:00:00' ? 'hidden' : 'visible'?>;height:15px; width:15px; text-align:center; line-height:16px; border:1px solid #333; border-radius:50%; display:inline-block; color:#333; font-size:10px">x</b>
    </a></li>
		<li><a href="javascript:void(0)" onclick="showEditNoteLayer('<?=$noteId?>');"><i class="msprite f-edit"></i>Edit Note</a></li> 
		<li><a onclick="deleteNote('<?=$noteId?>','<?=$noteData['course_id']?>');" href="javascript:void(0)"><i class="msprite f-delete"></i>Delete Note</a></li>
	</ul>
</div>
<div class="mys-popup mys-logn mys-report" style="width: 280px;display:none" id="noteEdit_<?=$noteId?>">
    	<form enctype="multipart/form-data" method="POST" action="" id="editNoteForm_<?=$noteId?>" name="editNote" >
          <span class="mys-pop-title">
              <h1>Edit Note</h1>
              <a href="javascript:void(0)" onclick="hideEditNoteLayer('<?=$noteId?>');" class="mys-cross"><i class="msprite mys-close"></i></a>
          </span>
          <span class="mys-pop-mid">
          <div style="margin-bottom:8px;">
          	  <input id = "noteTitle<?=$noteId?>" type="text" validate="validateStr" caption="Note Title" maxlength="100" minlength="2" required="true" placeholder="Write Note Title" value = "<?=html_escape($noteData['title'])?>" class="note-txtfield"/>
          	  <div class="errorPlace"> <p id = "noteTitle<?=$noteId?>_error" style="display:none; " class="errorMsg"></p> </div>
          </div>
              <textarea id = "noteDesc<?=$noteId?>" style="color:#666 !important; font-size:12px !important;" validate="validateStr" caption="Note Description" maxlength="1000" required="true" placeholder="Write note description here..." class="txtAreamsg"><?=html_escape($noteData['body'])?></textarea>
              <div class="errorPlace"> <p id = "noteDesc<?=$noteId?>_error" style="display:none; " class="errorMsg"></p></div>
          </span>
          <input id="submitDate<?=$noteId?>" type="hidden" value ="<?=$noteData['submit_date']?>"/>
          <span class="mys-pop-botm">
              <a onclick="editNoteOfShortlistCourse('<?=$noteId?>','<?=$noteData['course_id']?>');" class="mys-blue-btn">SAVE</a>
          </span>
        </form>
</div>