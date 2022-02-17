<div class="container">
	<div style="font-weight:bold; margin-bottom:10px">Myshortlist Notification Center</div>
	<div class="cms-shortlist-cont clear-width">
		<div class="cms-shortlist-tab clear-width">
			<ul>
				<li class="active"><a href="javascript:void(0);" style="text-decoration:none;cursor: default;">Generic / Course specific</a></li>
				<li style="display: none;"><a href="#">Course Specific</a></li>
			</ul>
		</div>
		<!-- Generic Form Starts -->
		<?php
		?>
		<div class="cms-review-form">
			<div class="cms-shortlist-grey-cont" style="display:block;">
				<div class="floatL" id="ranking-grey-value-cont">
					<h4>Variable usage:</h4><br/>
					1. $CR$ - for campus rep: replaced with comma separated campus reps. <br/>
					2. $C$ - for courses: replaced with comma separated courses.<br/>
					3. $I$ - for institutes: replaced with comma separated institutes.<br/>
					4. $LT$....$.....$.....$LT$ - for link text: replaced with comma separated links.<br/>
				</div>
			</div>
			
			<form id="form_<?php echo $formName?>" name="form_<?php echo $formName?>" enctype="multipart/form-data">
			<ul>
				<li>
					<label>No.of Shortlist <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<select name="maxCourseNum" id="maxCourseNum" style="width:50px">
							<?php
							for($i=0; $i < 5; $i++){
								?>
								<option value=<?php echo $i+1;?>><?php echo $i+1;?></option>
								<?php
							}
							?>
						</select>
					</div>
				</li>
				<li>
					<label>Subject <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<input id="subject" name="subject" type="text" class="cms-form-field" caption="subject" onblur="showErrorMessage(this,'<?php echo $formName?>');" validationtype="str" required="true" maxlength="100"/>
						<div id="subject_error" class="errorMsg" style="display: none;"></div>
					</div>
				</li>
				<li>
					<label>From email<span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<input id="from" name="from" type="text" class="cms-form-field" caption="email" onblur="showErrorMessage(this,'<?php echo $formName?>');" validationtype="email" required="true" maxlength="100"/>
						<div id="from_error" class="errorMsg" style="display: none;"></div>
					</div>
				</li>
				
				<li>
					<label>Body <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<textarea style="height:60px" id="body" name="body" class="cms-form-field tinymce-textarea" maxlength="1000" caption="body" validationtype="html" required="true"></textarea>
						<div id="body_error" class="errorMsg" style="display:none;"></div>
					</div>
				</li>
				<li>
					<label>Notification Text <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<textarea style="height:60px" id="notification" name="notification" class="cms-form-field tinymce-textarea" maxlength="1000" caption="notification" validationtype="html" required="true"></textarea>
						<div id="notification_error" class="errorMsg" style="display:none;"></div>
					</div>
				</li>
				<li>
					<label>Mobile Notification Text <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<textarea style="height:60px" id="mobileNotification" name="mobileNotification" class="cms-form-field tinymce-textarea" maxlength="1000" caption="mobile notification" validationtype="html" required="true"></textarea>
						<div id="mobileNotification_error" class="errorMsg" style="display:none;"></div>
						<div>In mobile, link text is not needed. Tap on tuple will take to the link</div>
					</div>
				</li>
				
				<li>
					<label>Link out type <span class="errorMsg">*</span>:</label>
					<div class="cms-filds">
						<div class="clear-width" style="margin-bottom:12px">
							<div class="link-types"><input checked type="radio" name="linktype" value="generic"/> Generic</div>
							<div class="link-types"><input type="radio" name="linktype" value="ask"/> Campus Rep</div>
							<div class="link-types"><input type="radio" name="linktype" value="placement"/> Placement</div>
						</div>
						<div class="clear-width">
							<!--<div class="link-types"><input type="radio" name="linktype" value="note"/> Note</div>-->
							<div class="link-types"><input type="radio" name="linktype" value="reviews"/> Reviews</div>
						</div>
					</div>
				</li>
				<li style="text-align:center; margin-top:40px">
					<input type="button" value="Preview" onclick="submitTemplate('<?php echo $formName;?>');" />
					<span id="previewloader" style="display: none;"><img src="/public/images/loader_small_size.gif"></span>
				</li>
			</ul>
		</form>
		</div>
		<?php $this->load->view('cms/previewLayer'); ?>
		<!-- Generic Form Ends -->
	</div>
</div>