<div class="tab-content-section">
	<div class="notes-block">
		<div class="notes-inner clearfix">
			<input type="text" id="noteTitle" maxlength="100" value="Untitled note" default="Untitled note" onfocus="checkTextElementOnTransition(this,'focus'); this.style.color = 'black';" onblur="checkTextElementOnTransition(this,'blur');" />
			<textarea id="noteBody" maxlength="1000" value="Write your note..." default="Write your note..." onfocus="checkTextElementOnTransition(this,'focus'); this.style.color = 'black';" onblur="checkTextElementOnTransition(this,'blur');">Write your note...</textarea>
		</div>
		<div class="errorMsg" id="noteError"></div>
					
					<div class="clearfix">
					<a class="brochure-button apply-btn flRt" style="padding:6px 15px" href="javascript:void(0);" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_notes', 'add'); addEditRemoveNote('add', <?php echo $courseId; ?>);">Add</a>
					<div class="remind-sec flRt">
						<i class="shortlist-sprite remind-icn flLt"></i>
						<input class="datepicker_0" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_notes', 'reminder');" type="text" value="Remind me" style="border:0 none; font-size:13px; background:#fff; width:100px; color:#00a5b5; font-weight:bold" />
						<a style="visibility:hidden;top: 2px;position:relative" onclick="removeReminder(0);" href="javascript:void(0);" class="remind-remove-icn">&times;</a>
					</div>
					</div>
	</div>
	
	<?php
		if($totalNotesCount < 1) {
			$style = 'display:none';
		}
		$currentPage = $page + 1;
		$totalPages = ceil($totalNotesCount/$batchSize);
	?>
	<div class="existing-notes" style="<?php echo $style; ?>">
		<?php if($totalNotesCount == 1) { ?>
			<div class="notes-title">Previously Added Note</div>
		<?php } else { ?>
			<div class="notes-title">Previously Added Notes</div>
		<?php }?>
		<ul>
			<?php $i=1;
				foreach($notes as $key => $note) { ?>
					<li <?php if($i == $batchSize || ($currentPage == $totalPages && $totalNotesCount % 2 != 0)) { ?> style="border-bottom:0px;" <?php } ?>>
						<input type="hidden" id="submitDate_<?php echo $note['note_id']; ?>" value="<?php echo $note['submit_date'] ?>"/>
						<div class="notes-header clearfix">
							<strong class="flLt" id="noteTitleParent_<?php echo $note['note_id']; ?>"><?php echo $note['title']; ?></strong>
							<div class="flRt">
								<span class="review-duration flLt"><i class="shortlist-sprite duration-icn"></i><?php echo $note['last_updated_time_text']; ?></span>
								<div class="flLt note-settings">
									<a href="javascript:void(0);" id="settingsIcon_<?php echo $note['note_id']; ?>" onclick="showHideNoteSettings(<?php echo $note['note_id']; ?>);"><i class="shortlist-sprite setting-icn"></i> <span class="caret"></span></a>
									<div class="settings-layer" id="settingsLayer_<?php echo $note['note_id']; ?>" style="display:none">
										<a href="javascript:void(0);" onclick="showNoteInEditMode(<?php echo $note['note_id']; ?>);">
											<span><i class="shortlist-sprite edit-icn"></i></span><p>Edit Note</p>
										</a>
										<a href="javascript:void(0);" onclick="addEditRemoveNote('remove', <?php echo $courseId; ?>, <?php echo $note['note_id']; ?>);">
											<span><i class="shortlist-sprite del-icn"></i></span><p>Delete Note</p>
										</a>



										<a class = "reminderEdit" noteId="<?=$note['note_id']?>" href="javascript:void(0);" style="-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box; float:left; width:100%;" >
											<span  class="flLt"><i style="margin-top: 3px" class="shortlist-sprite reminder-icn"></i></span>
											<div class="flLt" style="width:115px">
													<span  style="visibility:<?=!empty($note['reminder_date_time_text']) ? 'visible' : 'hidden' ?>;font-size:10px;line-height:9px;margin-left:7px;text-align:left; text-align:center;">Reminder</span>										
													<input onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_notes', 'reminder');" class="datepicker_<?=$note['note_id']?>" type="text" style="margin-left:5px;width: 100px; border: 0px none; color: rgb(102, 102, 102);" value="<?=!empty($note['reminder_date_time_text']) ? $note['reminder_date_time_text']: 'Remind me' ?>">
											</div>
												
											<span onclick="removeReminder(<?=$note['note_id']?>);" style="visibility:<?=!empty($note['reminder_date_time_text']) ? 'visible' : 'hidden' ?>;" class="remind-cross-btn">&times;</span>
									
										</a>
									</div>
								</div>
							</div>
						</div>
						
						<div class="added-notes clearfix">
							<div id="saveButton_<?php echo $note['note_id']; ?>" class="flRt save-note">
								<a href="javascript:void(0);" onclick="addEditRemoveNote('edit', <?php echo $courseId; ?>, <?php echo $note['note_id']; ?>);" class="save-btn">Save</a>
								<a href="javascript:void(0);" onclick="showNoteInNormalMode(<?php echo $note['note_id']; ?>);" style="margin-left: 2px;">Cancel</a>
							</div>
							<div id="editableArea_<?php echo $note['note_id']; ?>" class="editable-area"><?php echo $note['body'];?></div>
							<div id="reminderText_<?php echo $note['note_id']; ?>" style="display:<?=!empty($note['reminder_date_time_text']) ? 'block' : 'none' ?>;"><p style="font-size: 12px; margin-top: 10px; font-weight: inherit; color: rgb(102, 102, 102);"><i class="shortlist-sprite remind-icn" style="margin-right:5px;"></i>Reminder is set on <span id="reminderTextDate_<?php echo $note['note_id']; ?>"><?=$note['reminder_date_time_text']?></span></p></div>
						</div>
						<div class="errorMsg" id="noteError_<?php echo $note['note_id']; ?>"></div>
					</li>
			<?php $i++;
			} ?>
		</ul>
	</div>
	
	<?php  // make pagination view
		$data['paginateData']= array('totalResult'=>$totalNotesCount, 'perPage'=>$batchSize, 'pageNo'=>$page, 'fromPage'=>'myShortlistNotes', 'courseId'=>$courseId, 'instituteId'=>0);
		echo $this->load->view('CA/myshortlist_ana_pagination',$data);
    ?>
</div>

<script>
	addEditRemoveInProgress = 0;
</script>