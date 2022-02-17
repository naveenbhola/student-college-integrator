<?php
$headerComponents = array(
		'css'	=>	array(
			'raised_all',
			'mainStyle',
			'header'
			),
		'js'	=>	array(
			'common',
			'user',
			'inviteFriends',
			'prototype',
			'scriptaculous',
			'utils'
			),
		'title'	=>	'Shiksha.com - Invite Friends And Share Information With Them',
		'tabName'	=>	'Invite Friends',
		'taburl' =>  '/index.php/events/Events/index',	
		'metaKeywords'	=>'Some Meta Keywords',
		'product' => 'College',
		'search'=> false,
        'bannerProperties'=>array('pageId'=>'', 'pageZone'=>''),
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
		'callShiksha'=>1
		);
$this->load->view('common/homepage', $headerComponents);

?>
<div id="globalContainer">
    <div id="wait" style="display:none" class="mar_full_10p">
        <div class="lineSpace_20">&nbsp;</div>
        <div class="OrgangeFont fontSize_16p bld" align="center">Uploading Webmail Contacts...... </div>
        <div class="lineSpace_5">&nbsp;</div>
        <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">Please wait while your upload process,</div>
        <div class="lineSpace_5">&nbsp;</div>
        <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">during that time this page will reload automatically.</div>
        <div class="lineSpace_10">&nbsp;</div>
        <div align="center"><img src="/public/images/ajax-loader.gif"  /></div>
        <div class="lineSpace_5">&nbsp;</div>
        <div class="fontSize_11p" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif">loading....</div>
        <div class="lineSpace_35">&nbsp;</div>
    </div>

    <div id="box" class="mar_full_10p">
        <div class="lineSpace_20">&nbsp;</div>
        <div id="responseTextPlace" class="OrgangeFont fontSize_13p bld" align="center" style="display:none">Your invites have been sent!! </div>
        <?php 
            if($showSent=="true") {
                if(trim($numSent) == "1") {
                    echo "<div id='responseTextFirst' class='OrgangeFont fontSize_13p bld' align='center' >".$numSent." invitation has been sent. </div>";
                } else {
                    echo "<div id='responseTextFirst' class='OrgangeFont fontSize_13p bld' align='center' >".$numSent." invitations have been sent. </div>";
                }
            }else {
                echo'
                    <div class="OrgangeFont fontSize_16p bld" align="center">Invite Friends.</div>
                    <div class="lineSpace_5">&nbsp;</div>
                    <div class="fontSize_12p" align="center">Send a <span class="OrgangeFont">Join Shiksha</span> invitation email to your friends either by specifying their email ids or by importing your address book from Yahoo! Mail, GMail and Orkut. <br/>We do not store your mail accounts details and address book data in our system.  <span class="OrgangeFont">Join Shiksha</span> invitation emails would only be send to the contacts you select.</div>
';
        }   
    ?>
        <div class="lineSpace_15">&nbsp;</div>
    
    <?php $this->load->view('inviteFriends/contentGrabber'); ?>
</div>
</div>

<div class="lineSpace_20">&nbsp;</div>
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
        $this->load->view('common/footer', $bannerProperties);
?>
