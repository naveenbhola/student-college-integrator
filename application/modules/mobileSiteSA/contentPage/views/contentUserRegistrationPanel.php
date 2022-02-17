<div id="contentRegistration" class="wrapper" data-role="page" style = "width:100% !important;" data-enhance="false">
	<section class="layer-wrap clearfix">
		<div class="layer-header">
			<a id="contentUserRegistrationBackBox" href="javascript:void(0);" data-rel="back" data-transition="slide" class="back-box" onclick="resetContentUserRegistrationPanel();">
				<i class="sprite back-icn"></i>
			</a>
			<p style="text-align:center">Enter Your Details</p>
		</div>
		<article class="content-inner2">
			<div class="wrap-title" style="margin:5px 0 15px; font-weight:normal">
				Provide your details to submit your comment
			</div>
			<form>
				<ul class="form-display">
					<li>
						<input id="contentUserName" type="text" class="universal-txt" placeholder="Name" maxlength=50>
						<div style="display:none;" class="errorMsg error-msg" id="contentUserNameError"></div>
					</li>
					<li>
						<input id="contentUserEmail" type="text" class="universal-txt" placeholder="Email ID">
						<div style="display:none;" class="errorMsg error-msg" id="contentUserEmailError"></div>
					</li>
					<li style="margin:15px 0 15px">
						<a href="javascript:void(0)" onclick="validateContentUserForm();" class="btn btn-default btn-full">
							Submit
						</a>
					</li>
					<li class="tac" style="padding-bottom:10px;">
						<div style="text-align: center;">
							<a href="javascript:void(0);" data-rel="back" data-transition="slide" onclick="resetContentUserRegistrationPanel();">
								Cancel
							</a>
						</div>
					</li>
				</ul>
			</form>
		</article>
	</section>	
</div>
