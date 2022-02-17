<div id="mailToAllOverlay" style="position:absolute;display:none;z-index:20500000;">
<?php 

echo $this->ajax->form_remote_tag( array(

                            'url' => '/search/sendMail',
			    			'before' => 'if(!'.$validateFunction.'){return false;}else{'.$beforeFunction.';}',  	
                            'success' => 'javascript:'.$callBackFunction.''
) 
                    ); 
		?>
<br /><br /><br />
<div style="width:400px">
	<div id="shadow-container">
		<div class="shadow1">
			<div class="shadow2">
				<div class="shadow3">
					<div class="container">	
						<div class="">
								<div class="h33 normaltxt_11p_blk" style="background:#6391cc">	
									<div class="row lineSpace_7">&nbsp;</div>
									<div class="float_R mar_right_10p"><img src="/public/images/crossImg.gif" onClick="javascript:hideMailToAllOverlay();" style="cursor:pointer;" /></div>
									<div class="OrgangeFont bld fontSize_14p mar_left_10p" style="color:#FFF">Send Message</div>
									<div class="clear_R"></div>
								</div>
								<div class="bgOverLay">
										<div class="lineSpace_10">&nbsp;</div>
										<div class="fontSize_12p row">
											<div class="float_L" style="width:75px; text-align:right">To:</div> 
											<div style="margin-left:80px"><input id="names1" type="text" size="45" style="height:18px;" name="searchEmailAddr" validate="validateEmail" caption="Email" maxlength="250" minlength="5" required="true" value="" class = "normaltxt_11p_blk_arial fontSize_12p"/></div>
											<div class="clear_L"></div>
										</div>
										<!-- <div id="addressbook1" class="autocomplete"></div> -->
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row errorPlace">
											<div id="names1_error" class="errorMsg" style="margin-left:80px"></div>
										</div>	
										<div class="lineSpace_10">&nbsp;</div>			
									
										<div class="fontSize_12p row">
											<div class="float_L" style="width:75px; text-align:right">Subject:</div> 
											<div style="margin-left:80px"><input type="text" name = "subject" id = "mailsubject" size="45" value="<?php echo $defaultSubject; ?>" style="height:18px;" required="false" class = "normaltxt_11p_blk_arial fontSize_12p" /></div> 
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>			

										<div class="fontSize_12p row">
											<div class="float_L" style="width:75px; text-align:right">Content:</div>
											<div style="margin-left:80px"><textarea name="body" id = "mailContent" class="textboxBorder" required="false" id="content" rows="15" style="width:280px;" ><?php echo $defaultEmailContent; ?></textarea></div>
											<div class="clear_L"></div>
										</div>	
										<input type="hidden" name="listingTypeForMail" value="<?php echo $type; ?>"/>			
										<input type="hidden" name="extraParams" value="<?php echo $extraParams; ?>" />
										<input type="hidden" name="fromAddress" value="<?php echo $defaultEmailId; ?>" />
										<div class="lineSpace_5">&nbsp;</div>
										<div style="margin-left:128px">
											<div>
												<button class="btn-submit13" type="Submit" style="width:70px;" id="questionExternalMailSendButton">
												<div class="btn-submit13"><p class="btn-submit14 whiteFont" name = "submit" id = "submit">Send</p></div>
												</button>
												 &nbsp;
												<button class="btn-submit5 w16" type="button" onClick="javascript:hideMailToAllOverlay();">
												<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
												</button>
											</div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</form>
<script>
	//var auto = new Ajax.Autocompleter('names1','addressbook1','/mail/Mail/getAddressbook');
</script>
