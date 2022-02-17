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
    $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
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
	<div class="mar_full_10p" style="height:100%">
    		<div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
		</div>
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		<form id="formForTestmail_Template" enctype="multipart/form-data" action="/mailer/Mailer/handle_userlist/" method="POST"  >
			<table>
				<tr>
				<td width="200"><input type="radio" name="user_list_template" value="search_new_criteria" id="search_new_criteria" onclick="view_user_list_template(this)">Search New Criteria </td>
				<td width="200"><input type="radio" id="use_old_list" name="user_list_template" value="use_old_list" onclick="view_user_list_template(this)">Use Old List </td>
				<td width="200"><input type="radio" name="user_list_template" id="upload_csv" value="upload_csv" onclick="view_user_list_template(this)"> Upload CSV </td>
				</tr>
			</table>
			<div class="r2_2 errorMsg" id= "clk_one_error"></div>
		<!-- Search Form Start -->
		<div id="search_form_select_user_tmp" style="display:none;" >
		<div class="row">
			<div style="display:inline;float:left;width:100%">
				<?php
				$this->load->view('mailer/getSearchFormData');
				?>
			</div>
		</div>
            	<div class="lineSpace_10">&nbsp;</div>
            	</div>
		<!-- Search form End -->
		<!-- LISTING Start -->
		<div id="old_listing_select_user_tmp" style="display:none;" >
		<div class="row">
			<?php
			$this->load->view('mailer/user_list');
			?>
		</div>
            	<div class="lineSpace_10">&nbsp;</div>
            	</div>
		<!-- LISTING End -->

		<!-- UPLOAD CSV Form Start -->
		<div id="upload_csv_select_user_tmp" style="display:none;">
		<div class="row">
		<fieldset><legend>Upload CSV</legend>
			<div style="display:inline;float:left;width:100%">
				<div class="r1_1 bld">Upload CSV:&nbsp;&nbsp;</div>
				<div class="r2_2">
					<input type="file" name="c_csv" id="c_csv" size="5" style="" />
				</div>
				<div class="clear_L"></div>
				<div class="r2_2 errorMsg" id= "temp1_name_error"></div>
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1">&nbsp;</div>
					<div class="clear_L"></div>
				</div>
			</div>
			</fieldset>
		</div>
            	<div class="lineSpace_10">&nbsp;</div>
            	</div>
		<!-- UPLOAD CSV Form End -->

	<input type="hidden" name="edit_form_mode" value="<?php echo $edit_form_mode; ?>" />
        <input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
        <input type="hidden" name="sums_data" value="<?php echo $sumsData; ?>" />

	<div class="lineSpace_10">&nbsp;</div>
            <div class="r1_1 bld">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" type="button" onclick="$('formForTestmail_Template').action='/index.php/mailer/Mailer/handle_userlist/';validate_user_listing();" value="" style="width:100px">
                <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
                </button>
            </div>
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
        </form>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
