<?php
$urlIdentifier = $this->input->post('urlIdentifier');
$displayStyle = "none";
if(!empty($urlIdentifier)){
	$displayStyle = "block";
}
?>
<div style="width:360px;margin: 0pt auto;display:<?php echo $displayStyle;?>" id="rankingEmailThisHTMLDiv">
	<div class="blkRound">
		<div class="bluRound">
            	<span class="float_R"><img class="pointer" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();" src="/public/images/fbArw.gif" border="0"/></span>
                <span class="title" id="formTitle">Email this page</span>
                <div class="clear_B"></div>
        </div>
		<div class="whtRound" style="padding:10px">
            <form method="post" onsubmit="return false;" id="rankingEmailThisForm" name="rankingEmailThisForm">
			<ul>
				<li id="ranking_share_email_field_1" style="margin-bottom:5px;">
				  <div>
					  <input class="universal-txt-field" style="width:300px" caption="Enter Email" type="text" name="rankingEmailThisForm_email1" id="rankingEmailThisForm_email1"  value="Enter Email" default="Enter Email" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
					  <div class="errorMsg" id="rankingEmailThisForm_email1_error" style="padding-left:3px; clear:both; display:block"></div>
				  </div>
				</li>
				<li id="ranking_share_email_field_2" style="display:none;margin-bottom:5px;">
				  <div>
					  <input class="universal-txt-field" style="width:300px" caption="Enter Email" type="text" name="rankingEmailThisForm_email2" id="rankingEmailThisForm_email2"  value="Enter Email" default="Enter Email" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
					  <div class="errorMsg" id="rankingEmailThisForm_email2_error" style="padding-left:3px; clear:both; display:block"></div>
				  </div>
				</li>
				<li id="ranking_share_email_field_3" style="display:none;margin-bottom:5px;">
				  <div>
					  <input class="universal-txt-field" style="width:300px" caption="Enter Email" type="text" name="rankingEmailThisForm_email3" id="rankingEmailThisForm_email3"  value="Enter Email" default="Enter Email" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
					  <div class="errorMsg" id="rankingEmailThisForm_email3_error" style="padding-left:3px; clear:both; display:block"></div>
				  </div>
				</li>
				<li id="ranking_share_email_field_4" style="display:none;margin-bottom:5px;">
				  <div>
					  <input class="universal-txt-field" style="width:300px" caption="Enter Email" type="text" name="rankingEmailThisForm_email4" id="rankingEmailThisForm_email4"  value="Enter Email" default="Enter Email" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
					  <div class="errorMsg" id="rankingEmailThisForm_email4_error" style="padding-left:3px; clear:both; display:block"></div>
				  </div>
				</li>
				<li id="ranking_share_email_field_5" style="display:none;margin-bottom:5px;">
				  <div>
					  <input class="universal-txt-field" style="width:300px" caption="Enter Email" type="text" name="rankingEmailThisForm_email5" id="rankingEmailThisForm_email5"  value="Enter Email" default="Enter Email" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
					  <div class="errorMsg" id="rankingEmailThisForm_email5_error" style="padding-left:3px; clear:both; display:block"></div>
				  </div>
				</li>
				<li>
				<li id="ranking_add_more_link">
					<div class="spacer10"></div>
					<span><a href="javascript:void(0);" onclick="showShareEmailField();">+ Add more email ids</a></span>
				</li>
				<li id="ranking_global_error_field_cont" style="display:none;">
					<div class="spacer5"></div>
					<div class="errorMsg" id="ranking_global_error_field" style="padding-left:3px; clear:both; display:block"></div>
				</li>
				<div class="spacer10"></div>
				<li style="margin-bottom:15px">
					<button type="button" onclick="return submitRankingEmailThisForm();" class="orange-button">Submit</button>
					<a href="javascript:void(0);" style="margin-left:5px;font-weight:bold;" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none'; closeMessage();">Cancel</a>
					<div id="rankingEmailThisForm_loader" style="display:none;margin-top:5px;">
						<img id="rankingEmailThisForm_loader" src= "/public/images/loader_hpg.gif" align="absmiddle" id="loader"/>
					</div>
				</li>
			</ul>
			<input type="hidden" name="url_identifier" id="url_identifier" value="<?php echo $urlIdentifier;?>" />
			</form>
		</div>
	</div>
</div>
<style type="text/css">
.layer-hidden-fields div.flLt{width:330px !important;}
.layer-hidden-fields .universal-select{width:311px !important; padding:5px !important}
</style>