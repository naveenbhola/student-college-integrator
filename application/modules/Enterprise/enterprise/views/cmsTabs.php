<?php
$headerTabs = Modules::run('enterprise/Enterprise/restrictTabs',$headerTabs,$validateuser);
?>
<div class="lineSpace_10">&nbsp;</div>
<div>
   <div id="header" class="rowWDFcms">
      <ul id="navigationUL">
	 <?php if(is_array($headerTabs) && is_array($headerTabs[0]) && is_array($headerTabs[0]['tabs'])) { ?>
	 <?php foreach($headerTabs[0]['tabs'] as $tab): $id = ($prodId==$tab['tabId'])? "selected" : ""; ?>
	<?php if($tab['tabId']=='777' && $validateuser[0]['usergroup']=='enterprise'): ?>
		<?php if($showOnlineFormEnterpriseTab['instituteName']!='None'):?>
			 <li id="<?php echo $id; ?>"><a href="<?php echo $tab['tabUrl']; ?>"><?php echo $tab['tabName']; ?></a></li>
		<?php endif;?>
	<?php else:?>
	<li id="<?php echo $id; ?>">
	<?php 
	$tabLink = SHIKSHA_HOME.$tab['tabUrl'];
	//changed to enterprise URL for listings CMS
	if(strpos($tab['tabUrl'], 'national') !== false) {
		$tabLink = ENTERPRISE_HOME.$tab['tabUrl'];;
	} ?>
		<a href="<?php echo $tabLink; ?>">
	<?php echo $tab['tabName']; ?></a></li>
	<?php endif;?>
	 <?php endforeach; ?>
	<?php } ?>
	 </ul>
      </div>
   <div style="clear:left"></div>
</div>
<div class="tabBorder" style="border:1px solid #7AA821; line-height:5px; background-image:url(/public/images/greenline.jpg); margin-top:0px">&nbsp;</div>
<?php 

            $this->load->view('common/changeOverlay');?> 
<style>
.showMessages{ background:#fffdd6;border:1px solid #facb9d;line-height:25px;padding:0 10px}
</style>
<div class="lineSpace_5">&nbsp;</div>
<div id = "loginCommunication" class = "showMessages mar_full_10p" style = "display:none">
<span class="cssSprite float_R" onClick="hidelogindiv()" style="cursor:pointer;padding-right:13px;position:relative;top:5px;background-position:right -72px">&nbsp;</span>
<span id = "logindiv" style = "font-size:11px"></span>
</div>
<div class="lineSpace_5">&nbsp;</div>
<script>
    <?php
        if(is_array($validateuser) && isset($validateuser[0])) {
            if($validateuser[0]['usergroup']=='enterprise'){
        ?>
var cookie = getCookie('user').split('|');var msg = cookie[2];
var comm = '';
if(trim(msg) == "hardbounce" || trim(msg) == "softbounce")
{
if(trim(msg) == "softbounce")
comm = "We experienced problem sending email to the address " + cookie[0] + " you provided. Please <a href = '#' onClick = 'showchangeEmailOverlay()'>click here</a> to change the email address or <a href = '#' onClick = 'showverificationMailOverlay()'>clickhere</a> to continue using Shiksha.com and avail its benefits."
if(trim(msg) == "hardbounce")
comm = "The email address - " + cookie[0] + " you provided appears to be invalid. <a href = '#' onClick = 'showchangeEmailOverlay();'>Click here</a> to provide the correct email address to continue using Shiksha.com and avail its benefits.";
//showMessagesInline1('logindiv',comm);
//document.getElementById('loginCommunication').style.display = '';
}
<?php
}
} ?>

function showMessagesInline1(confirmMsgPlace,msgToBeShown){
	document.getElementById(confirmMsgPlace).style.display = '';
	document.getElementById(confirmMsgPlace).innerHTML = msgToBeShown;
//	window.setTimeout(function(){ document.getElementById(confirmMsgPlace).style.display = 'none'; }, 5000);
}
</script>
