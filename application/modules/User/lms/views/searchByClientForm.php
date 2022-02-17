		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">
			Search Client Profile &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			<span><a href="javascript:void(0);" onclick="showDiv('showHideByClientForm')">Show</a></span> <span style="color:#000000">/</span> <span><a href="javascript:void(0);" onclick="hideDiv('showHideByClientForm')">Hide</a></span>
		</div>
		<div class="grayLine"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div id="showHideByClientForm">
				<form id="formForQuoteUsers" action="" method="POST" style="margin:0; padding:0">
						<div class="row">
							<div style="width:49%; float:left">
									<div class="row">
										<div style="display:inline;float:left;width:100%">
											<div class="r1_1 bld">LOGIN EMAIL ID:&nbsp;&nbsp;</div>
											<div class="r2_2"><input type="text" name="email" id="email" size="30" maxlength="125" minlength="5" caption="Email Id"/></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "email_error"></div>
												<div class="clear_L"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>	
									<div class="row">
										<div style="display: inline; float:left; width:100%">
											<div class="r1_1 bld">DISPLAY NAME:&nbsp;&nbsp;</div>
											<div class="r2_2"><input name="displayname" id="displayname" type="text" size="30" maxlength="25" minlength="3" caption="Display Name" /></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "displayname_error"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="row">
										<div style="display: inline; float:left; width:100%">
											<div class="r1_1 bld">COLLEGE / INSTITUTE / UNIVERSITY NAME:&nbsp;&nbsp;</div>
											<div class="r2_2"><input name="collegename" id="collegename" type="text" size="30" maxlength="100" minlength="3" caption="College Name" /></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "collegename_error"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>	
							</div>
							<div style="width:49%; float:left">
									<div class="row">
										<div style="display: inline; float:left; width:100%">
											<div class="r1_1 bld">CONTACT NAME:&nbsp;&nbsp;</div>
											<div class="r2_2"><input name="contactName" id="contactName" type="text" size="30" maxlength="25" minlength="3" caption="Contact Name" /></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "contactName_error"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>	
									<div class="row">
										<div style="display: inline; float:left; width:100%">
											<div class="r1_1 bld">CONTACT PHONE NO.:&nbsp;&nbsp;</div>
											<div class="r2_2"><input name="contactNumber" id="contactNumber" type="text" size="30" maxlength="25" minlength="3" caption="Contact Number" /></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "contactNumber_error"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>	
									<div class="row">
										<div style="display: inline; float:left; width:100%">
											<div class="r1_1 bld">CLIENT ID:&nbsp;&nbsp;</div>
											<div class="r2_2"><input name="clientId" id="clientId" type="text" size="30" maxlength="25" minlength="3" caption="Client Id" /></div>
											<div class="clear_L"></div>
											<div class="row errorPlace" style="margin-top:2px;">
												<div class="r1_1">&nbsp;</div>
												<div class="r2_2 errorMsg" id= "clientId_error"></div>
											</div>
										</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
							</div>
						</div>
						<div class="row">
							<div class="r1_1 bld">&nbsp;</div>
							<div class="r2_2">
								<button class="btn-submit19" onclick="validateQuoteUsers();" type="button" value="" style="width:100px">
									<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Search</p></div>
								</button>
							</div>		
							<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
				</form>	
		</div>
		<!--<form method="POST" id="frmSelectUser" action="">-->
		<div id="userresults"></div>
		<div id="showClientListingDetail"></div>
		<!--</form>-->