<form id ="form_addEditForm" name="addEditForm" action="/videoCMS/VideoCMS/saveData" method="POST" enctype="multipart/form-data">

<!-- <div class="abroad-cms-wrapper"> -->
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('manageTabs',array('activePage'=>'back'));?>
            <div>
                <h1 class="abroad-title">Add / Edit Video CMS</h1>
				<div class="cms-form-wrapper clear-width">
						<div class="clear-width" id="video_list_section">
							<div class="">
								<table class="top-section--data">
									<tr>
									 <td>
										<div>
										  <label class="label-width">Video Title<span style="color:#FF0000;">*</span></label>
										  <div class="findSrCnt customSelect multi-slct alignBox">
										  	<input type="text" class="uni-txtfld" name="title" placeholder="Enter Video Title" id="videoTitle" value="<?php echo $videoInfo['title'];?>" spellcheck="false" autocomplete="off" minlength="3" maxlength="100" required="true" validate="validateStr"
										  	caption="Video Title"/>
										  	 <div><div id="videoTitle_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>	
										  </div> 
										 
										</div>
											</td>
										</tr>
										<tr>
											<td>
												<div>
													<label class="label-width">Video YouTube URL<span style="color:#FF0000;">*</span></label>
													<div class="findSrCnt customSelect multi-slct alignBox">
														<input type="url" class="uni-txtfld" id="youtubeUrl" name="videoUrl" value="<?php echo $videoInfo['videoUrl'];?>"placeholder="Enter YouTube URL" required="true" validate="validateStr"
										  	caption="YouTube Url" minlength="20" maxlength="200" <?php echo ($videoInfo['videoUrl']!='')?'disabled':'';?>/>
										  	<input type="hidden" class="uni-txtfld" id="ytVideoId" name="ytVideoId"/>
														<div><div id="youtubeUrl_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>	
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div>
													<label class="label-width" style="position:relative;top:-70px">
														Video Description<span style="color:#FF0000;">*</span>
													</label>
													<div class="findSrCnt customSelect multi-slct alignBox">
														<textarea id="videoDescription" class="uni-txtarea" placeholder="Enter Video Description" minlength="50" maxlength="600" required="true" validate="validateStr" name="description" 
										  	caption="Video Description"><?php echo $videoInfo['description'];?></textarea>
														<div><div id="videoDescription_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div>
													<label class="label-width">Video Type<span style="color:#FF0000;">*</span></label>
												   <div class="findSrCnt customSelect multi-slct alignBox">
												   	 <select id="videoType" name="videoType" class="uni-select" onchange="videoCMSObj.showVideoSubType();return false;" required="true" validate="validateSelect"
										  	caption="Video Type">
												   	 	<option value="">Select</option>
												   	 	<option <?php echo ($videoInfo['videoType']=='Institute Video')?'selected=selected':''?> value="Institute Video">Institute Video</option>
												   	 	<option <?php echo ($videoInfo['videoType']=='Exam Video')?'selected=selected':''?>value="Exam Video">Exam Video</option>
												   	 	<option <?php echo ($videoInfo['videoType']=='careers_courses')?'selected=selected':''?> value="Careers and Courses Video">Careers and Courses Video</option>
												   	 	<option <?php echo ($videoInfo['videoType']=='other')?'selected=selected':''?> value="Other Video">Other Video</option>
												   	 </select>
												   	<div><div id="videoType_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
												   </div>
												</div>
											</td>
										</tr>
										<tr <?php echo ($videoInfo['videoType']!='Institute Video' && $videoInfo['videoType']!='Exam Video')?'class="disNone"':''?> id="videoSubType">
											<td>
												<div>
													<label class="label-width">Video Sub-Type<span style="color:#FF0000;">*</span></label>
												   <div class="findSrCnt customSelect multi-slct alignBox">
												   	 <select class="videoSubType uni-select <?php echo ($videoInfo['videoType']=='Institute Video') ? '': 'disNone'?>" id="instituteSubType"
										  	caption="Video SubType" name="videoInstituteSubType">
												   	 	<option value="">Select</option>
												   	 	<option <?php echo ($videoInfo['videoSubType']=='Infrastructure')?'selected=selected':''?> value="Infrastructure">Infrastructure</option>
												   	 	<option <?php echo ($videoInfo['videoSubType']=='College Reviews')?'selected=selected':''?> value="College Reviews">College Reviews</option>
												   	 	<option <?php echo ($videoInfo['videoSubType']=='College General')?'selected=selected':''?> value="College General">College General</option>
												   	 </select>
												   	 <div><div id="instituteSubType_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
												   	 
												   	<select class="videoSubType uni-select <?php echo ($videoInfo['videoType']=='Exam Video')?'':'disNone'?>" id="examSubType"
										  	caption="Video SubType" name="videoExamSubType">
												   	 	<option value="">Select</option>
												   	 	<option <?php echo ($videoInfo['videoSubType']=='Exam General')?'selected=selected':''?> value="Exam General">Exam General</option>
												   	 	<option <?php echo ($videoInfo['videoSubType']=='Student Reaction')?'selected=selected':''?> value="Student Reaction">Student Reaction</option>
												   	 </select>
												   	 <div><div id="examSubType_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
												   </div>
												   </div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
								<?php
								$prefilledData = Modules::run('common/commonHierarchyForm/getPrefilledData', 'videoCms', array('videoId' => $videoId,'videoMappings' => $videoInfo['videoMappings'],'videoLocations'=>$videoInfo['videoLocations'],'videoTags'=>$videoInfo['videoTags']));?>
								<?php echo Modules::run('common/commonHierarchyForm/getHierarchyMappingForm','videoCms',$prefilledData);?>
											</td>
										</tr>
										<tr>
											<td>
												<div>
													<label class="label-width">Mailer Title</label>
													<div class="findSrCnt customSelect multi-slct alignBox">
														<input id="mailerTitle" type="url" class="uni-txtfld" placeholder="Enter Mailer Title" minlength="3" maxlength="100" validate="validateStr" name="mailerTitle" value="<?php echo $videoInfo['mailerTitle'];?>"
										  	caption="Mailer Title"/>
														<div><div id="mailerTitle_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>	
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div>
													<label class="label-width" style="position:relative;top:-70px">
														Mailer Snippet
													</label>
													<div class="findSrCnt customSelect multi-slct alignBox">
														<textarea id="mailerSnippet" class="uni-txtarea" placeholder="Enter Mailer Description" name="mailerSnippet" rows="5" style="width:100%" minlength="200" maxlength="800" validate="validateStr" caption="Mailer Snippet"><?php echo $videoInfo['mailerSnippet'];?></textarea>
														<div><div id="mailerSnippet_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
													</div>
												</div>
											</td>
										</tr>
									</table>
								</div>
						</div>
						<div>			   
						<div class="button-wrap" style="margin-left:190px;">
							<a id="saveButton" href="JavaScript:void(0);" onclick="videoCMSObj.addEditVideo('addEditForm')" class="orange-btn _btnClick_">Save</a>
							<a href="/videoCMS/VideoCMS/getVideoList" class="cancel-btn">Cancel</a>
						</div>
						<div class="clearFix"></div>
					</div>
				</div>

            </div>
		</div>
    </div>
    <input type="hidden" name="videoId" value="<?php echo $videoId?>">
<!-- </div> -->
</form>
<?php $this->load->view('enterprise/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>
