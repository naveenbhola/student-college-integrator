<?php
$headerComponents = array(
        //'css'   =>  array('header','raised_all','header','mainStyle','footer'),
		'css' => array('user'),
        'js'    =>  array('common'),
        'jsFooter'    => array('prototype','scriptaculous','effects','controls','mail'),
        'title' =>  'Shiksha::Messages',
        'tabName'   =>  'Mail',
        'taburl' =>  site_url('mail/Mail/mailbox'),
        'bannerProperties' => array('pageId'=>'MESSAGE_DETAIL', 'pageZone'=>'HEADER'),
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => 'inbox',
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'callShiksha'=>1
        );
//$this->load->view('common/header', $headerComponents);
$this->load->view('common/homepage', $headerComponents);
?>
<script>
var siteURL = '<?php echo site_url();?>';
var autoCompleteFlag = true;
</script>
<style>
.bluefont {color:#000;}
.raised_sky_comp {background: transparent; } 
.raised_sky_comp .b1, .raised_sky_comp .b2, .raised_sky_comp .b3, .raised_sky_comp .b4, .raised_sky_comp .b1b, .raised_sky_comp .b2b, .raised_sky_comp .b3b, .raised_sky_comp .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_sky_comp .b1, .raised_sky_comp .b2, .raised_sky_comp .b3, .raised_sky_comp .b1b, .raised_sky_comp .b2b, .raised_sky_comp .b3b {height:1px;} 
.raised_sky_comp .b2 {background:#C8ECFC; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b3 {background:#ffffff; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b4 {background:#ffffff; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b4b {background:#ffffff; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b3b {background:#ffffff; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b2b {background:#ffffff; border-left:1px solid #C8ECFC; border-right:1px solid #C8ECFC;} 
.raised_sky_comp .b1b {margin:0 5px; background:#C8ECFC;} 
.raised_sky_comp .b1 {margin:0 5px; background:#ffffff;} 
.raised_sky_comp .b2 {margin:0 0px; border-width:0 2px;} 
.raised_sky_comp .b2b {margin:0 3px; border-width:0 2px;} 
.raised_sky_comp .b3, .raised_sky_comp .b3b {margin:0 2px;} 
.raised_sky_comp .b4, .raised_sky_comp .b4b {height:2px; margin:0 1px;} 
.raised_sky_comp .boxcontent_sky_comp {display:block; background-color:#ffffff; background-position:bottom; background-repeat:repeat-x; border-left:1px solid #C8ECFC; border-right:1px solid #FFFFFF;} 
</style>
<!--Start_Mid_Container-->
<!--Start_Center-->
<!--StartTopeaderWithNavigation-->
<div class="mar_full_10p">
	<!--End_Right_Panel-->
	<!--id="right_Panelnetwork"-->
	<div id="">
	</div>
	<!--End_Right_Panel-->


	<!--Start_Left_Panel-->
	<div id="left_Panel_n">
		<div class="drop_dwn_bg_comp" style="height:28px">
		<div style="margin-left:30px">
			<div class="buttr2" style="float:none">
				<button class="btn-submit5 w6" value="" type="button" onClick="compose();">
					<div class="btn-submit5"><p class="btn-submit6">Compose</p></div>
				</button>
			</div>
		</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="skyLine"></div>
		<div class="raised_sky_comp">
			<b class="b2"></b>
            <div class="boxcontent_sky_comp">
				<div class="row deactiveselectyear" style="width:100%">
                  	<div class="lineSpace_10">&nbsp;</div>
				</div>
				<div class="row" id="left_mail_panel">
					<div class="normaltxt_11p_blk lineSpace_20 w9 mar_left_11px">
						<div class="activeselectyear">
							&nbsp; <img src="/public/images/inbox.gif" align="absmiddle" />&nbsp; <a href="#inbox" class="OrgangeFont fontSize_14p bld" onClick="getMails(this);" id="inbox_link">Inbox (<?php echo $noOfUnread;?>)</a>
						</div>
						<div class="lineSpace_10 deactiveselectyear">&nbsp;</div>
						<div class="deactiveselectyear">
							&nbsp; <img src="/public/images/send.gif" align="absmiddle" />&nbsp; <a href="#sent" class="fontSize_14p bld" onClick="getMails(this);" id="sent_link">Sent</a>
						</div>
						<div class="lineSpace_10 deactiveselectyear">&nbsp;</div>
						<div class="deactiveselectyear">
							&nbsp; <img src="/public/images/draft.gif" align="absmiddle" />&nbsp; <a href="#drafts" class="fontSize_14p bld" onClick="getMails(this);" id="drafts_link">Drafts</a>
						</div>
						<div class="lineSpace_10 deactiveselectyear">&nbsp;</div>
						<div class="deactiveselectyear">
							&nbsp; <img src="/public/images/trash.gif" align="absmiddle" />&nbsp; <a href="#trash" class="fontSize_14p bld" onClick="getMails(this);" id="trash_link">Trash</a>
						</div>
						<div class="lineSpace_10 deactiveselectyear" style="height:100px">&nbsp;</div>
					</div>
				</div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>

	</div>
	<!--End_Left_Panel-->

	<!--Start_Mid_Panel-->
	<div id="mailBoxMessage" style="margin-left:164px;padding-left:5px;line-height:25px;background-color:#ccc;color:#fff;font-weight:bold;display:none">
	</div>
	<div id="mails">
	<?php $this->load->view('mail/inbox'); ?>
	</div>
	<!--End_Mid_Panel-->
<br clear="all" />
</div>
<div style="margin-left:164px;margin-right:10px;">
	<?php
		$bannerProperties = array('pageId'=>'MESSAGE_DETAIL', 'pageZone'=>'FOOTER');
		$this->load->view('common/banner',$bannerProperties);
	?>
</div>
<!--End_Center-->
<!--End_Mid_Container-->
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view("common/footer",$bannerProperties);
?>
