<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
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
		<?php
		$this->load->view('mailer/list_grid');
		?>
		<form id="formForUpdateList_Template" action="/mailer/Mailer/UpdateUserList" method="POST">
		<input type="hidden" name="mode" value="<?php echo $edit_form_mode; ?>" />
        	<input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
        	<input type="hidden" name="sumsData" value="<?php echo $sumsData; ?>" />
		<input type="hidden" name="list_id" value="<?php echo $selectedListId; ?>" />
			<div class="lineSpace_10">&nbsp;</div>
              			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Negate List: &nbsp;</div>
              			<div class="float_L"  >
              				<select id="all_Lists" multiple size="5" name="all_Lists[]" style="width:200px">
              				<?php if(is_array($all_Lists)){ for($i=0;$i<count($all_Lists);$i++) {
              				if ($selectedListId !=$all_Lists[$i]['id'] ) {
              				?>
						<option value="<?php echo $all_Lists[$i]['id'];?>" ><?php echo $all_Lists[$i]['name'];?></option>
					<?php
					}
					}
					}
					?>
              				</select>
              			</div>
              		<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">No. of Mails/SMS: &nbsp;</div>
			<div class="float_L"  >
				<input type="radio"  name="mails_limit" id="mails_limit_no" value="mails_limit_no" />
			</div>
			<div class="float_L"  >
				<input type="text" id = "mails_limit_text" name="mails_limit_text" style="cursor:pointer" align="absmiddle" >
			</div>
			<div class="float_L"  >
			&nbsp;&nbsp;All
				<input type="radio" id="mails_limit_all" name="mails_limit" value="-1" />
			</div>
		<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
            <!--<div class="float_L"  >
            <b>
			&nbsp;&nbsp;Export LIST TO CSV: &nbsp;&nbsp;</b>
				<input type="checkbox" id="export_csv" name="export_csv"  />
			</div>
		    <div class="clear_L"></div>

		    <div class="lineSpace_10">&nbsp;</div>-->
           	<div class="r1_1 bld">&nbsp;</div>
            	<div class="r2_2">
                <button class="btn-submit19" type="button" onclick="$('formForUpdateList_Template').action='/index.php/mailer/Mailer/UpdateUserList';return validate_update_listing_form();" value="" style="width:100px">
                <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
                </button>
            	</div>
            	<div class="clear_L"></div>
            	<div class="lineSpace_10">&nbsp;</div>
			<div id="radio_unselect_error1" style="display:none;color:red;"></div>
        	</form>
		<?php
		$this->load->view('mailer/left-panel-bottom');
		?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
