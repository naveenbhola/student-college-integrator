<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog'),
	'jsFooter'         => array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
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
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="margin-top:15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
	<?php if ($countresult == NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px;"><strong>Mailer Details</strong></div>
        <div>
	<?php 
        $from_date = "";
        $to_data = "";
        if($startTime) {
	   $from_date = $startTime;	
           $from_date = date("Y-m-d", strtotime($from_date));
        }
 
        if($endTime){
           $to_date = $endTime; 
           $to_date  = date("Y-m-d", strtotime($to_date)); 
        } 
        ?>	
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
        <form id="formForUpdateList_Template" action="/mailer/Mailer/MailerTrackingUrlsformsubmit" method="POST">
                <tr>
                    <td  valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 5px 10px"><input type="text" readonly id="subs_start_date" name="trans_start_date" value="<?php echo  $from_date;//date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   (date("Y")-1)));?>" onclick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
					</td>
                    <td  valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><input type="text" readonly id="subs_end_date" name="trans_end_date" value="<?php echo  $to_date;//date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));?>" onclick="calMain.select($('subs_end_date'),'ed','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="calMain.select($('subs_end_date'),'ed','yyyy-MM-dd');" />
					</td>
                    <td  valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><button onclick="" id="submitbutton" type="Submit" value="" class="btn-submit7" style="width:100px">
					<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
					</button></td>
                </tr>
        <input type="hidden" name="id" value="<?php echo $id; ?>" />        
        </form>        
        </table>
        </div>
		<div class="lineSpace_10">&nbsp;</div>
        <div>
			<!-- <div class="OrgangeFont fontSize_16p bld" style="padding-bottom:10px;">Stats</div>
			table width="740" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="550" bgcolor="#EEEEEE" style="padding:5px 10px 5px 10px; font-size: 13px;"><strong>URL</strong></td>
                    <td width="130" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>Mails Sent</strong></td>
                </tr>
            </table

            <div style="width:755px;">
                <table width="740" cellspacing="0" cellpadding="0" style="border-left:1px solid #ccc; border-top:1px solid #ccc;"> -->
                    <?php
						$hasLinks = FALSE;
						foreach ($resultSet as $val) {
							if($val['id'] < 0) {
					?>
					<!-- <tr>
                        <td valign="top" width="150" style="padding:5px 10px 5px 10px; font-size: 13px; border-right:1px solid #ccc; border-bottom:1px solid #ccc;"><?php if (!empty($val['trackerId'])) { echo $val['trackerId']; } else {echo "Open Rate";}; ?></td>
                        <td valign="top" width="530" style="padding:5px 10px 5px 10px; font-size: 13px; border-right:1px solid #ccc; border-bottom:1px solid #ccc;"><?php echo $val['count']; ?></td>
                    </tr> -->
                    <?php
							}
						if($val['id'] > 1) {
							$hasLinks = TRUE;
						}
					}
                    ?>
                </table>
            </div>
			
			<?php
			if($hasLinks) {
			?>
				<div class="OrgangeFont fontSize_16p bld" style="padding-bottom:10px; margin-top: 20px;">URL Report</div>
				<table width="740" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="550" bgcolor="#EEEEEE" style="padding:5px 10px 5px 10px; font-size: 13px;"><strong>URL</strong></td>
                    <td width="130" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>No. of clicks</strong></td>
                </tr>
				</table>
				
				<div style="overflow: auto; height:200px; width:755px;">
                <table width="740" cellspacing="3" cellpadding="0">
                    <?php
						$hasLinks = FALSE;
						foreach ($resultSet as $val) {
							if($val['id'] > 1) {
					?>
					
					<tr>
                        <td valign="top" width="570" style="padding:5px 10px 0px 10px; font-size: 13px;"><div style="word-wrap:break-word; width:550px;"><p><?php echo $val['trackerId']; ?></p></div></td>
                        <td valign="top" width="130" style="padding:5px 10px 0px 10px; font-size: 13px;"><?php echo $val['count']; ?></td>
                    </tr>
                    <?php
						}
					}
                    ?>
                </table>
            </div>
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
				
			<?php
			}
			?>
			
			
        
        <?php } ?>
<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
