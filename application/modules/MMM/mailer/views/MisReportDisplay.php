<?php

$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	//'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api'),
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
        <?php }?>
        <?php
            $this->load->view('common/calendardiv');
        ?>
        
        <form action='/mailer/Mailer/MisReportDisplay' method='post' id='mailer_form'>
            <?php 

            if($adminType == 'super_admin') { 
            ?>
            Select Group 
            <?php

                $attributes = "id = 'group_choose' style='width:125px;margin-left:10px;'";
                 $options = array();
                 $options["select"] = "Select";

                foreach ($group_list as $row)
                {
                    $options[$row['group_id']]= ucfirst($row['group_name']);
                }

                echo form_dropdown('group_choose',$options,set_value('group_choose',$groupFilter),$attributes);
              ?>
              <?php } ?>
            <label>From : </label>
            <input type="text" id="timerangeFrom" name="timerangeFrom" style="width:100px;margin-left:10px;" value="<?=$timeStart?>" placeholder="YYYY-MM-DD"/>
            <img id="timerangeFromImg" onclick="timerange('From');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
       
        
       
            <label style="width:40px;">To : </label>
            <input type="text" id="timerangeTill" name="timerangeTill" style="margin-left:10px;width: 100px;" value="<?=$timeEnd?>" placeholder="YYYY-MM-DD"/>
            <img id="timerangeTillImg" onclick="timerange('Till');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
            <input id = 'clickedOn' name = 'clickedOn' value = '' style="display: none;">
            <a href="javascript:void(0)" onclick="getMailerReport('submit');" class="orange-button" style=" color: #ffffff;">Submit</a>
            <a href="javascript:void(0)" onclick="getMailerReport('download');" class="orange-button" style=" color: #ffffff;">Download</a>
            
        </form>
        

		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px;"><strong>View MIS Report (showing result for last one week) <br/><!--a href='/mailer/Mailer/MisReportDisplay/12'>Click here for last one year data</a--></strong></div>
        <div style="overflow:scroll;max-height:400px;height:auto;">
		
			<style type="text/css">
       .misMailTable{
        border-collapse: collapse;
       }
       .misMailTable td{padding: 10px 8px;font-size: 13px;border:1px solid #d2d2d2;}         
            </style>
		
            <div style="overflow-:auto; height-:400px; width-:1085px;">
                <table class="misMailTable" width="1385" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                    <td width="70" bgcolor="#EEEEEE" height="15" ><strong>Client ID</strong> </td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Mailer Id </strong></td>

                    <td width="300" bgcolor="#EEEEEE"><strong>Name</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Scheduled Date</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Scheduled Time</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Total Mails</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Processed Mails</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Sent Mails</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Unique mails Opened  </strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Open Rate</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Unique mails Clicked</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Click Rate</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Campaign Name</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Campaign Id</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Parent Id</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>Campaign Activity</strong></td>

                    <td width="100" bgcolor="#EEEEEE"><strong>&nbsp;</strong></td>

                </tr>
                <!--  <tr>
                    <td width="70" height="15" style="padding:5px 10px 5px 10px; font-size: 13px;"><strong></strong> </td>
                    <td width="300" ><strong>Total</strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total?></strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_processed?></strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_sent?></strong></td>

                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_open?></strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_open_rate?>
                    <?php if($total_open_rate != "0") echo " %";?>
                    </strong></td>

                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_click?> </strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_click_rate?>
                    <?php if($total_click_rate != "0") echo " %";?>
                    </strong></td>

                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_spam?></strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_spam_rate?>
                    <?php if($total_spam_rate != "0") echo " %";?>
                    </strong></td>

                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_unsubscribe?></strong></td>
                    <td width="100"  style="padding:0px 10px 0px 10px; font-size: 13px;text-align: center;"><strong><?=$total_unsubscribe_rate?>
                     <?php if($total_unsubscribe_rate != "0") echo " %";?>
                    </strong></td>


                    <td width="100" ><strong></strong></td>
                    <td width="100" ><strong></strong></td>
                </tr> -->
					<?php $i=1;
					foreach ($resultSet as $val) {
                    // _p($val);die;
                    // var_dump(!isset($mailerChildCount[$val['parentMailerId']]));
                    // var_dump((($val['parentMailerId'] == '') && ( (!isset($mailerChildCount[$val['parentMailerId']])) || ($mailerChildCount[$val['parentMailerId']] < 4))));

					?>
                    <tr>
                        <td valign="top" width="70"  ><?php echo $val['clientId']; ?></td>

                         <td valign="top" width="100"  ><?php echo $val['mailerId']; ?></td>

                        <td valign="top" width="300" ><a href="/mailer/Mailer/getMailerTrackingUrls/<?php echo$val['mailerId']; ?>"><?php echo $val['mailerName']; ?></a></td>

                        <td valign="top" width="100"  ><?php echo $val['date']; ?></td>

                        <td valign="top" width="140"  ><?php echo $val['time']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['totalMails']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['processedMails']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['sentMails']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['uniqueMailsOpened']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['openRate']; ?>
                             <?php if($val['openRate'] != "0") echo " %";?>
                        </td>

                        <td valign="top" width="102"  ><?php echo $val['uniqueMailsClicked']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['clickRate']; ?>
                             <?php if($val['clickRate'] != "0") echo " %";?>
                        </td>

                        <td valign="top" width="102"  ><?php echo $val['campaignName']; ?></td>

                        <td valign="top" width="102"  ><?php echo $val['campaignId']; ?></td>


                        <td valign="top" width="102"  ><?php 
                            if ($val['parentMailerId']){

                                echo $val['parentMailerId']; 
                            }
                            else{
                                echo "-";
                            }
                            ?>
                                
                            </td>


                        <td valign="top" width="102"  ><?php echo $val['dripCampaignType']; ?></td>


                        <td valign="top" width="102"  >   

                            <a href="/mailer/MailScheduler/replicateMailer/<?php echo $val['mailerId']?>"  class="orange-button" style=" color: #ffffff;">Replicate</a>

                            <?php 
                            
                            if(($val['parentMailerId'] == '') && ( (!isset($mailerChildCount[$val['mailerId']])) || ($mailerChildCount[$val['mailerId']] < 4))) { ?>
                                <a href="/mailer/MailScheduler/resendMailer/<?php echo $val['mailerId']?>"  class="orange-button" style=" color: #ffffff;">Resend</a>
                            
                            <?php } ?>
                        </td>

                    </tr>
                	<?php
        	            $i++;
                        } 
                    ?>

                </table>
            </div>
        <input type="hidden" id="totalTempCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <!--table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">
                    </td>
                </tr>
            </table-->
        </div>
        <div class="clear_L"></div>
<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:16px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
