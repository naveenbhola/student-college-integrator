<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles', 'searchCriteria'),
	//'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog','footer','ajax-api', 'recatSearchCriteria'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api', 'searchCriteria'),
	//'jsFooter'         => array('scriptaculous','utils'),
	'jsFooter'         => array(),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>'',
	'noextra_js'=>true
	
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
</SCRIPT>
</head>
<body>
	<script>
				var userSetPage = 1;
	</script>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="margin-top:15px; min-height:600px;">
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
	
		<div id='formForTestmail_Template'><div id='select_user_set_loader'></div></div>
		
		<div id='add_new_user_set'>
			
		</div>
		
		<div id="select_user_set">
		
		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px; float:left;"><strong>User Sets</strong></div>
		<div style="float:right;display:none" id="newUsersetButton">
			<input type="button" value=" " class="new-serset-btn" onclick="addNewUserset('profile_india');">
		</div>
		<div class="clearFix"></div>
		
		<?php if($successMsg) { ?>
			<div id='addUsersetMessage' style="background:#DCF2DD; border:1px solid #84CF87; padding:5px; margin:5px 0px 15px 0px; font-size:13px;"><?php echo $successMsg; ?></div>
		<?php } else { 	?>
			<div id='addUsersetMessage'></div>
		<?php } ?>
		
		
		<?php if (count($userSets) == 0) { ?>
		<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div id="userSetOuter">
			
			<div style="margin:10px;">
			<div id="helpText" style="display:none;position:absolute;left:800px;margin-top: -5px;">Download User Count may vary from User Count</div>
			</div>
			<table width="740" border="0" cellpadding="0" cellspacing="10" bordercolor="#EEEEEE">
                <tr>
                    <td width="206" bgcolor="#EEEEEE" height="15" style="padding:5px 10px 5px 10px; font-size: 13px; text-align:center;"><strong>Name</strong> </td>
                    <td width="103" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px; text-align:center;"><strong>Type</strong></td>
                    <td width="123" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px; text-align:center;"><strong>Created By</strong></td>
                    <td width="123" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px; text-align:center;"><strong>User count</strong></td>
                    <td width="123" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px; text-align:center;">
                    		<strong>Download <span onmouseover="$j('#helpText').show();" onmouseout="$j('#helpText').hide();" style="color:blue;">(?)</span></strong>
            		</td>
                    <td width="80" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px; text-align:center;"><strong>Delete</strong></td>
                </tr>
            </table>
		
            <div style="overflow:auto; height:400px;">
                <table width="740" border="0" cellspacing="10" cellpadding="0">
					<?php $i=1;
					foreach ($userSets as $userSet) {
                                        if($userSet['id'] == 3685) continue;
					?>
                    <tr id="usersetrow<?php echo $userSet['id']; ?>">
                        <td valign="top" width="120" style="font-size: 13px;display:inline-block; word-wrap:break-word; padding:5px 10px;"><?php echo $userSet['name']; ?></td>
                        <td valign="top" width="103"  style="font-size: 13px; word-wrap:break-word; text-align:center; padding:5px 10px;"><?php echo $userSet['criteriaType']; ?></td>
                        <td valign="top" width="123" style="font-size: 13px; word-wrap:break-word; text-align:center; padding:5px 10px;"><?php echo $allAdminData[$userSet['user_id']]['displayname']; ?></td>
                        <td valign="top" width="126" style=" font-size: 13px; word-wrap:break-word"><div id="userCount<?php echo $userSet['id']; ?>"><input type='button' value='Get user count' class="getUserCountButton" id="getUserCountButton<?php echo $userSet['id']; ?>" onclick="getUserCountInSet('<?php echo $userSet['id']; ?>' ,'true');" /></div></td>
                        <td valign="top" width="123" style="font-size: 13px; word-wrap:break-word"><div id="downloadUser<?php echo $userSet['id']; ?>"><input type='button' value='Download' class="downloadUserButton" id="downloadUserButton<?php echo $userSet['id']; ?>" onclick="window.open('/mailer/Mailer/downloadUserInUserSet/<?php echo $userSet['id']; ?>');" /></div></td>
                        <td valign="top" width="80" style="font-size: 13px; word-wrap:break-word"><div id="deleteUser<?php echo $userSet['id']; ?>"><input type='button' value='Delete' class="deleteUserButton" onclick="deleteUserSet('<?php echo $userSet['id']; ?>');" /></div></td>
                    </tr>
	<?php
	$i++;
        } ?>

                </table>
            </div>
        <input type="hidden" id="deleteCounter" value = "<?php echo $i-1; ?>" />
        <input type="hidden" id="totalTempCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>
		
		</div>
		
<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
	$j( document ).ready(function() {
		$j('#newUsersetButton').show();
	});
</script>
