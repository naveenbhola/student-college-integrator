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
		<form id="formForSummaryList_Template" action="/mailer/Mailer/Summary_List_template" method="POST">
		<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
        	<input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
		<input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
		<input type="hidden" name="sumsData" value="<?php echo $sumsData; ?>" />
		<?php
		$this->load->view('mailer/left-panel-top');

		if (empty($result[0]['id']) || ($empty_list_error == 'TRUE') ||($error_empty_array == 'TRUE')||($email_validation_error =='TRUE_EMAIL')) {
		?>
		<div style="color:red;">
		<?php
			if ($empty_list_error == 'TRUE') {
			echo "Your CSV file is not matched with selected Template variables. Please go back and choose/correct CSV.";
			} elseif($error_empty_array == 'TRUE') {
			echo "Your Search Criteria return zero result.Please go back and search again.";
			} elseif($email_validation_error =='TRUE_EMAIL') {
			echo "Your CSV file contains invalid email id.Please go back and choose/correct CSV.";
			} elseif($email_validation_error =='WRONG_CSV') {
			echo "Your CSV file is not valid content type.Please go back and choose/correct CSV.";
			} else {
			echo "You have selected empty criteria. Please go back and choose different list combinations.";
			}
		?>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
            	<div class="r1_1 bld">&nbsp;</div>
            	<div class="r2_2">
                <button class="btn-submit19" type="button" onclick="history.go(-1);return false;" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Back</p></div>
                </button>
            	</div>
		<?php
		} else {
		echo "Your List has been saved by name <b>".$result[0]['name'] . "</b>.<br><br>" ;
		$num =  (int)$result[0]['numUsers'];
		echo " The number of members in list is <b>". $num . "</b>.";
		?>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Send Date: &nbsp;</div>
				<input type="text" readonly id="subs_start_date" name="trans_start_date" value="<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));?>" onclick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
			</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">Mailer Or SMS Name:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input required="true" profanity="true" validate="validateStr" type="text" name="temp1_name"  id="temp1_name" size="30" maxlength="125" minlength="2" caption="Mailer Name"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp1_name_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">Sender Email id:&nbsp;&nbsp;</div>
                    <div class="r2_2">
			<?php $this->load->view('mailer/senderEmailSelect'); ?>
		    </div>
		    <div class="clear_L"></div>
		    <div class="row errorPlace" style="margin-top:2px;">
			<div class="r1_1">&nbsp;</div>
			<div class="r2_2 errorMsg" id= "userFeedbackEmail_error"></div>
			<div class="clear_L"></div>
		    </div>
		</div>
        	<div class="clear_L"></div>
            	<div class="r1_1 bld">&nbsp;</div>
            	<div class="r2_2">
                <button class="btn-submit19" type="button" onclick="return formForSummaryList_Template();" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:10px" class="btn-submit20">Save</p></div>
                </button>
            	</div>
		<?php
		}
		?>
		</form>
		<?php
		$this->load->view('mailer/left-panel-bottom');
		?>
		<script>

		addOnBlurValidate(document.getElementById('formForSummaryList_Template'));
		</script>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
