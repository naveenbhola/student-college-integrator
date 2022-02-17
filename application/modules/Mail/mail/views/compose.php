<?php 
if(isset($mail['receivers_ids'])) {
foreach($mail['receivers_ids'] as $ids)
	$str .= $receiverDetails[$ids]['displayname'].",";
$str = substr($str,0,strlen($str)-1);
}
?>	
	<div id="mid_Panel_n">
	<form id="composeMail" action="save" onsubmit="new Ajax.Request('save',{ onComplete: function(transport) { parseSaveMail((eval(transport.responseText)));}, parameters:Form.serialize(this)}); return false;" method="post" >
	<input type="hidden" value="<?php if(isset($mail['content_id'])) { echo $mail['content_id'];}?>" name="contentId" id="contentId" autocomplete="off">
	<input type="hidden" value="" name="mailtype" id="mailtype_for_inbox" autocomplete="off">
		<div style="display:inline; float:left; width:100%">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="normaltxt_11p_blk_arial OrgangeFont fontSize_16p bld">Compose</div>				
			<div class="lineSpace_5">&nbsp;</div>
			<div class="grayLine"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">				
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return sendMail();">
						<div class="btn-submit5"><p class="btn-submit6">Send</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return saveMail();">
						<div class="btn-submit5"><p class="btn-submit6">Save</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" value="" type="button" onClick="cancelCompose();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
					</button>
				</div>
				<div id="status"></div>	
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
				
			<div class="brd_full bg_compose" id="to" >
				<div class="row" id="receivers">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial bld fontSize_13p txt_align_r float_L" style="width:70px">To:&nbsp;</div>
					<div><input id="names" type="text" size="50%" style="height:15px" name="names" autocomplete="off" value="<?php if(isset($str)) {echo $str;}?>"/><div style="padding-left:70px;padding-top:3px">Enter only Shiksha displaynames. For eg. ThisIsManish.</div></div>
					<div id="addressbook" class="autocomplete"></div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_1">&nbsp;</div>
				<div class="row">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial bld fontSize_13p txt_align_r float_L" style="width:70px">Subject:&nbsp;</div>
					<div><input type="text" size="50%" style="height:15px" name="subject" value="<?php if(isset($mail['subject'])) { echo $mail['subject']; }?>"/></div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
			</div>
			<div class="bgcolor_div_sky">
				<div class="pd_5">
					<textarea style="width:99%; height:200px" name="body"><?php if(isset($mail['body'])) { echo $mail['body']; }?></textarea>
				</div>
			</div>
										
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">				
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return sendMail();">
						<div class="btn-submit5"><p class="btn-submit6">Send</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return saveMail();">
						<div class="btn-submit5"><p class="btn-submit6">Save</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" value="" type="button" onClick="cancelCompose();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
					</button>
				</div>
				<div id="status"></div>	
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>
				<div class="clear_L"></div>
	</form>			
	</div>
