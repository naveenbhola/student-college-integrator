<form name="mail" action="delete/<?php echo $type; ?>" id="mainMailForm" onsubmit="return confirmAction();" method="post" >
	<div id="mid_Panel_inbox">
		<input type="hidden" value="" id="flag" name="flag">
		<input type="hidden" value="<?php echo $type; ?>" id="type">
		<input type="hidden" id="pageNo" value="<?php echo $pageNo; ?>">
		<div style="display:inline; float:left; width:100%">
			<div class="normaltxt_11p_blk_arial"><span class="fontSize_18p OrgangeFont bld">Drafts</span>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="grayLine"></div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<?php $this->load->view('mail/pagination'); ?>
				<div class="buttr2">
					<button class="btn-submit5 w6" type="button" onclick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<!--Draft_Header-->
			<div>			
				<div class="brdpink_box">
					<div class="normaltxt_11p_blk_arial">
						<div class="float_L brdright_b h28 bgpink_box" style="width:27px;"><span class="disBlock mar_top_4p mar_left_5p"><input type="checkbox" onclick="check(this,'mail_container');" value="yes" />&nbsp;</span></div>
						<div class="float_L brdright_b h28 bgpink_box" style="width:30%;"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">To</span></span></div>
						<div class="float_L brdright_b h28 bgpink_box" style="width:47%;"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">Subject</span></span></div>
						<div class="float_L h28" style="background-color:#FEFDF8"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">Date</span><!--<img src="/public/images/down_arrowdate.gif" align="absmiddle" />--></span></div>
						<div class="clear_L"></div>
					</div>								
				</div>
				<div class="lineSpace_10">&nbsp;</div>
			</div>
			<!--End_Draft_Header-->
			<!--Start_Draft_Mail_Container-->
			<div id="mail_container">
				<!--Mail_Row-->
				<?php if (count($mails)>0):?>
				<?php foreach($mails as $mail): $t = strtotime($mail['time_created']); ?>
				
				<div>
					<div class="">
						<div class="lineSpace_10">&nbsp;</div>
						<div class="normaltxt_11p_blk_arial">
							<div class="float_L" style="width:36px;"><span class="disBlock mar_top_4p mar_left_5p"><input type="checkbox" name="mailId[]" value="<?php echo $mail['mail_ids'][0];?>" />&nbsp;</span></div>
							<div class="float_L" style="width:29%;">
								<div>
								<?php $i=0;foreach ($mail['receivers_ids'] as $ids): if ($i==2) break; ?>
									<span class="mar_left_5p bld"><a href="<?php echo site_url()."getUserProfile/".$userDetails[$ids]['displayname']; ?>"><?php echo $userDetails[$ids]['displayname']; ?></a></span>
								<?php $i++;endforeach; ?>&nbsp;
								</div>
							</div>
							<div class="float_L" style="width:48%;">													
								<div class="disBlock mar_full_8p"><a href="#" class="bld" onClick="showDraft(<?php echo $mail['mail_ids'][0];?>);"><?php $s=($mail['subject']!="")? $mail['subject']: "(no subject)";echo $s; ?></a></div>
								<div class="lineSpace_5"></div>
								<div class="disBlock mar_full_8p"><span class="grayFont"><?php echo $mail['preview']; ?> </span></div>
							</div>
							<div class="float_L">&nbsp;<span class="bld"><?php echo date('D j M Y',$t); ?></span></div>
							<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>										
					<div class="grayLine"></div>													
				</div>
				<!--End_Mail_Row-->
				<?php endforeach; ?>
				<?php else: ?>
				<span class="normaltxt_11p_blk_arial fontSize_18p OrgangeFont bld" style="margin:10px 0px 10px 30px">You have no messages in the Drafts</span>
				<?php endif;?>
			</div>
			<div class="pinkLine"></div>
			<!--End_Draft_Mail_Container-->
			
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<?php $this->load->view('mail/pagination'); ?>
				<div class="buttr2">
					<button class="btn-submit5 w6" value="" type="button" onclick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>					
	</div>
				<div class="clear_L"></div>
	</div>
	<!--End_Mid_Panel-->
</form>
