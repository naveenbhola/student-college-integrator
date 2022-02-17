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
		<form id="update_New_List_template" action="/mailer/Mailer/update_New_List_template" method="POST">
            	<input type="hidden" name="new_list_id" value="<?php echo $new_list_id; ?>" />
            	<input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
            	<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
            	<input type="hidden" name="list_id" value="<?php echo $new_list_id; ?>" />
            	<input type="hidden" name="sumsData" value="<?php echo $sumsData; ?>" />
            	<div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">List Name:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input tip="mailer_list_name" required="true" profanity="true" validate="validateStr" type="text" name="temp1_name" value="<?php echo $temp_name; ?>" id="temp1_name" size="30" maxlength="125" minlength="2" caption="List Name"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp1_name_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">List Description:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <textarea tip="mailer_list_desc" type="text" name="temp_desc" id="temp_desc" maxlength="1000" minlength="2" required="true" profanity="true" validate="validateStr"  style="height:30px" caption="List Description" /><?php echo $temp_desc ; ?></textarea>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "temp_desc_error"></div>
                    </div>

                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div  id= "gen_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
		<div style="padding-left:180px;"> <?php $numcount = (int)$numUsers; if ($numcount > 1 ){ echo"The number of members in list is " . $numcount; ; }  ?></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="r1_1 bld">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" type="button" onclick="return update_New_List_template();" value="" style="width:100px">
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
	<script>

   	addOnFocusToopTip(document.getElementById('update_New_List_template'));
   	addOnBlurValidate(document.getElementById('update_New_List_template'));
   	</script>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
