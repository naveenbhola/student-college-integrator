<div class="content-wrap clearfix">
	<div id="left-col">
		<div class="account-setting-detail">
			<p class="account-setting-info-title">Account settings</p>
			<div class="account-detail-widget customInputs">
				<div class="account-detail-wideget-head">Personal information</div>
				<ul>
					<li>
						<div class="account-setting-fields flLt">
							<label>First name</label>
							<input type="text" class="universal-text" value="Ravi"/>
						</div>
						<div class="account-setting-fields flRt">
							<label>Last name</label>
							<input type="text" class="universal-text" value="Jain"/>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="account-setting-fields flLt">
							<label>Mobile no.</label>
							<input type="text" class="universal-text" value="987654321"/>
						</div>
						<div class="account-setting-fields flRt">
							<label>Email ID</label>
							<p class="accnt-setting-mail-info">ravi.jain@gmail.com</p>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="account-setting-fields flLt">
							<label>Current city</label>
							<div class="custom-dropdown">
								<select name="residenceCity" class="universal-select" <?=($disabled)?> id="residenceCity" onblur="">
									<?php //$this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
								</select>
						   </div>
						</div>
						
						<div class="clearfix"></div>
					</li>
				</ul>
			</div>
			
			<div class="account-detail-widget customInputs">
				<div class="account-detail-wideget-head">Education details</div>
				<div class="edu-detail-fields">
					<ul>
					<li>
						<div class="account-setting-fields flLt">
							<label>Primary course of interest</label>
							<div class="custom-dropdown">
								<select class="universal-select">
									<option>MBA</option>
								</select>
						   </div>
						</div>
						<div class="account-setting-fields flRt">
							<label>Course Specialization</label>
							<div class="custom-dropdown">
								<select class="universal-select">
									<option>Marketing</option>
								</select>
						   </div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul>
				</div>
				<ul>
					<li>
						<div class="account-setting-fields flLt">
							<label>Country of interest</label>
							<div class="custom-dropdown">
								<select class="universal-select">
									<option>Netherlands, New Zealand, Can..</option>
								</select>
						   </div>
						</div>
						<div class="account-setting-fields flRt">
							<label>Planing to study abroad in</label>
							<div class="custom-dropdown">
								<select class="universal-select">
									<option>2015</option>
								</select>
						   </div>
						</div>
						<div class="clearfix"></div>
					</li>
					<li>
						<div class="account-setting-fields setting-child-wrap flLt">
							<label style="margin-bottom:8px;">Do you have a passport?</label>
							   <div class="columns">
									<input type="radio" id="yes">
									<label for="yes">
									<span class="common-sprite"></span>
									<p style="margin-top:0"><strong style="font-size:12px !important;">Yes</strong></p>
									</label>
								</div>
								<div class="columns">
									<input type="radio" id="no">
									<label for="no">
									<span class="common-sprite"></span>
									<p style="margin-top:0"><strong style="font-size:12px !important;">No</strong></p>
									</label>
								</div>
						</div>
						<div class="account-setting-fields flRt">
							<label>Given any study abroad exam?</label>
								 
								 <div class="accnt-setting-exam-col">
								 <p class="accnt-setting-mail-info"><strong>GMAT: 310</strong> <a href="#" class="setting-edit-link">Edit</a></p>
									 <div class="custom-dropdown flLt" style="width:50%; margin-right:5px;">
										<select class="universal-select" style="padding:5px;">
											<option>GRE</option>
										</select>
									</div>
									<div class="flLt" style="width:38%;">
										<input type="text" class="universal-text" value="710"/>
									</div>
									<div class="remove-icon-2"><a href="#">&times;</a></div>
									<div class="clearfix"></div>
							</div>
							<div class="accnt-setting-exam-col">
								<p class="accnt-setting-mail-info"><strong>IELTS: 310</strong> <a href="#" class="setting-edit-link">Edit</a></p>
									 <div class="custom-dropdown flLt" style="width:50%; margin-right:5px; ">
										<select class="universal-select" style="padding:5px;">
											<option>Select Exam</option>
										</select>
									</div>
									<div class="flLt" style="width:38%;">
										<input type="text" class="universal-text" value="Exam Score"/>
									</div>
									<div class="remove-icon-2"><a href="#">&times;</a></div>
									<div class="clearfix"></div>
							</div>
							<a href="#" class="font-11 add-another-link">add another</a>
						</div>
						<div class="clearfix"></div>
					</li>
				 </ul>
			</div>
		</div>
		<a href="#" class="save-change-btn">Save Changes</a>
	</div>
	<div id="right-col"></div>
</div>