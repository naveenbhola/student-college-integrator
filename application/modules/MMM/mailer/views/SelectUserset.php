<div id="mmm-cont" style="margin:0 auto;">
	<div id="select_user_set_loader"></div>
	<div id="select_user_set">
		<?php if(!$compositeUserSet) { ?>
		<h2 class="flLt">Select Userset</h2>
		<div class="flRt"><input type="button" value=" " class="new-serset-btn" onclick="addNewUserset('profile_india');" /></div>
		<div class="clearFix"></div>
		<?php } ?>
		<div id="addUsersetMessage" style="display:none; background:#DCF2DD; border:1px solid #84CF87; padding:5px; margin:5px 0px 15px 0px; font-size:13px;">The userset has been successfully saved. You can now select it from Userset dropdowns below.</div>
		
		<ul style="display:none;" id="userset_template">
			<li>
				<label>Userset:</label>
				<div class="flLt">
					<select class="width-200 drip--fileds">
						<option value=0>Select</option>
						<?php
							foreach($usersets as $key=>$name){
                                                                if($key == 3685) continue;
								echo "<option value=".$key.">".$name."</option>";
							}
						?>
					</select>&nbsp;&nbsp;
					<input type="button" onclick="addNewOrset(this);" value="Or">
				</div>
			</li>
		</ul>
		<h4>Add Userset</h4>
		<div id="profile-form-0">
			<ul class="profile-form" id="profile-form-0-0">
				<li id="userset-0-0-0">
					<label>Userset:</label>
					<div class="flLt">
						<select class="width-200 drip--fileds" name="userset[0][0][]">
							<option value=0>Select</option>
							<?php
								foreach($usersets as $key=>$name){
                                                                        if($key == 3685) continue;
									echo "<option value=".$key.">".$name."</option>";
								}
							?>
						</select>&nbsp;&nbsp;
						<input type="button" id="or-0-0-0" onclick="addNewOrset(this);" value="Or">
					</div>
				</li>
			</ul>
		</div>
		<ul class="profile-form" id="profile-and-0" style="display: none;">
			<li>
				<label>&nbsp;</label>
				<div class="flLt">
					<input type="button" id="and-0-0-0" onclick="addNewAndset(this);" value="And">
				</div>
			</li>
		</ul>
		<div class="clearFix spacer10"></div>
		<h4>Exclude Userset</h4>
		<div id="profile-form-1">
			<ul class="profile-form" id="profile-form-1-0">
				<li id="userset-1-0-0">
					<label>Userset:</label>
					<div class="flLt">
						<select class="width-200 drip--fileds" name="userset[1][0][]">
							<option value=0>Select</option>
							<?php
								foreach($usersets as $key=>$name){
                                                                        if($key == 3685) continue;
									echo "<option value=".$key.">".$name."</option>";
								}
							?>
						</select>&nbsp;&nbsp;
						<input type="button" id="or-1-0-0" onclick="addNewOrset(this);" value="Or">
					</div>
				</li>
			</ul>
		</div>
		<ul class="profile-form" id="profile-and-1" style="display: none;">
			<li>
				<label>&nbsp;</label>
				<div class="flLt">
					<input type="button" id="and-1-0-0" onclick="addNewAndset(this);" value="And">
				</div>
			</li>
		</ul>
		<div class="clearFix"></div>
		<div class="button-aligner" style="border-top: 1px solid #e8e8e8; padding-top: 15px">
		<?php if($compositeUserSet) { ?>
			<input type="button" value="Count users" id="userCountInCompositeSearchCriteriaButton" class="orange-button" onclick="getUserCountInCompositeUserSet();" />
			<input type="button" value="Download" id="downloadUserInCompositeSearchCriteriaButton" class="orange-button" onclick="downloadUserInCompositeUserSet();" />			
			<span id="userCountInCompositeSearchCriteria" style="margin-left: 10px; font:bold 14px arial;"></span>
		<?php } else { ?>
			<input type="button" value="Proceed" class="orange-button" onclick="$('formForTestmail_Template').action='/index.php/mailer/Mailer/handle_userlist/';validate_user_listing();" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Count users in this set" id="userCountInCompositeSearchCriteriaButton" onclick="getUserCountInCompositeUserSet('yes');" style="font-size:14px; padding:3px 8px;" />
			<span id="userCountInCompositeSearchCriteria" style="margin-left: 10px; font:bold 14px arial;"></span>
		<?php } ?>
		</div>
		<div class="clearFix"></div>
	</div>
</div>
