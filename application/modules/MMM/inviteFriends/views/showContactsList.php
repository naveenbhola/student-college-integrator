<div class="mar_full_10p">
    <div class="OrgangeFont fontSize_13p bld">These are your newly added contact from <?php echo $type; ?> that are not yet connected to you on Shiksha. Invite them to connect!</div>
	<div class="lineSpace_20">&nbsp;</div>
	<div class="mar_full_10p">
			<div style="width:65%"; class="float_L">
				<div style="margin:0 20px">
					<!--Mid_RightArrow-->
					<div class="inviteRight">
						<div style="line-height:52px">&nbsp;</div>
						<div><img src="/public/images/inviteArrow.gif" class="positionLeft" /></div>
					</div>
					<!--End_Mid_RightArrow-->
					
					<!--Alphabets-->
					<div class="inviteLeft positionRight">
						<div style="line-height:30px">&nbsp;</div>
						<div><img src="/public/images/alphabetTopCorner.gif" /></div>						
						<div style="background-image:url(/public/images/alphabetMiddleBg.gif); background-repeat:repeat-y; height:670px">
							<div style="margin-left:4px">
								<a href="#All"><div class="alphabetsSelect" id="selector" onclick="doAlphabetSelection('');">All</div></a>
								<?php 
									for($alphabet = 65; $alphabet < 91; $alphabet++) { 
								?>
								<a href="#<?php echo chr($alphabet); ?>" onclick="return doAlphabetSelection('<?php echo chr($alphabet); ?>')">
									<div id="selector<?php echo chr($alphabet); ?>" class="alphabetsDisable"><?php echo chr($alphabet); ?></div>
								</a>
								<?php
									}
								?>
								<!--<div class="alphabetsHighLight">B</div>-->
							</div>
						</div>
						<div style="height:8px"><img src="/public/images/alphabetBottomCorner.gif" /></div>
								
					</div>
					<!--End_Alphabets-->

					<!--Contacts-->
					<div class="inviteMiddle">
						<div class="raised_lgraynoBG">
							<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_lgraynoBG">
								<div style="height:740px;padding-bottom:20px;">
									<div class="mar_full_10p">										
                                        <div class="bld fontSize_12p float_R" style="padding:5px 0">Showing <?php echo count($contactsList); ?> contacts</div>
                                        <div  class="bld fontSize_12p allUnChecked" id="selectAll" onclick="toggleAllContacts(this);">Select All</div>
                                        <div class="bld fontSize_12p">
                                        To add contacts, click on the checkbox next to the person's name/email.
                                        </div>
									</div>
									<div class="grayLine"></div>
									<div>
										<div style=" width:85%; margin-left:15px">
											<div class="lineSpace_15">&nbsp;</div>
											<div>											
												<div>
														<div style="font-size:18px; font-weight:bold" class="OrgangeFont" id="selectedAlphabet">A</div>
														<div style="border-bottom:3px solid #999999; margin-left:25px;"></div>
														<div class="lineSpace_10">&nbsp;</div>
												</div>
											</div>
										</div>
										<div style="overflow:auto; height:660px;">
											<div style=" width:85%; margin-left:15px">
												<div style="margin-bottom:10px" id="inviteLeftPanel">
													<a name="All" ></a>
                                                    <ul id="inviteLeftPanelList" class="unselectAll">
													<?php
														$startAlphabet = '';
														$flag = false;
														$jsMarkup = '';
														foreach($contactsList as $contact) {
															$flag = false;
															if($contact[1] == '') {
																$firstAlphabet = strtoupper($contact[3][0]);
															} else {
																$firstAlphabet = strtoupper($contact[1][0]);
															}
															if($firstAlphabet != $startAlphabet){
																$flag = true;
																$startAlphabet = $firstAlphabet;
																$jsMarkup .= $startAlphabet .',';
															}
															$genId = 'contact'. rand();
													?>
                                                       <li id="<?php echo $genId; ?>" onclick="checkContact(this);"> 
													<?php if($flag) { ?>
														<a name="<?php echo $startAlphabet; ?>" ></a>
													<?php } ?>
                                                    <?php
                                                    if(!((trim($contact[1])== "")&&(trim($contact[1])== ""))) {
													echo '<span id="'.$genId.'Name">'.$contact[1].' '.$contact[2].'</span>';
													echo '<div id="'.$genId.'Email">'.$contact[3].'</div>';
                                                    }else {
                                                        echo '<span id="'.$genId.'Name"></span>';
                                                        echo '<span id="'.$genId.'Email">'.$contact[3].'</span>';
                                                    }
                                                    ?>
													<div class="lineSpace_10">&nbsp;</div>
													<div class="grayLine" style="margin-left:25px;" ></div>
                                                    </li>
													<?php
														}
													?>
                                                    </ul>
												</div>														
											</div>
										</div>
									</div>
								</div>
							</div>
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
					<br clear="all" />				
					<!--End_Contacts-->
				</div>				
			</div>
			
			<div style="width:35%;" class="float_L">
				<div style="border:1px solid #FFC58B; padding:10px 10px">			
					<div class="bld fontSize_12p">
						To remove contacts, click on the cross next to the person's name/email.
					</div>
					<div class="lineSpace_15">&nbsp;</div>
					<div id="contactList" style="height:233px; overflow:auto;">
					</div>
							
				</div>
				<div id="contactInviteError" style="display:none;color:red;">Please select atleast one contact.</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div align="center">
					<button class="btn-submit7" value="" type="button" style="width:185px"  onclick="sendFriendInvites()"><div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Invite Selected Contacts</p></div></button>
					<button class="btn-submit5 w12" value="" type="button"  onclick="window.location.reload();"><div class="btn-submit5"><p class="btn-submit6">Cancel</p></div></button>
				</div>
			</div>
			<div class="clear_L"></div>						
	</div>
</div>
<input type="hidden" id="jsMarkup" value="<?php echo $jsMarkup;?>"/>
