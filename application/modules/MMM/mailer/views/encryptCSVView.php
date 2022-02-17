<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	//'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api'),
	'jsFooter'         => array(),
	//'jsFooter'         => array('scriptaculous','utils'),
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
	<div class="mar_full_10p" style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		
		<div class="bld fontSize_18p OrgangeFont" style="padding-left:10px;">Encrypt CSV</div>
		<div class="lineSpace_20">&nbsp;</div>
<form id="encryptCSVform" action="/mailer/Mailer/encryptCSV" method="POST" enctype="multipart/form-data" >
    <div id="upload_csv_select_user_tmp">
            
        <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld" style="width:250px; font-size:13px; padding-top:5px; color:#333;">Upload CSV:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="file" name="c_csv" id="c_csv" size="10" style="" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="r2_2 errorMsg" id= "temp1_name_error"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>

                        <div class="clear_L"></div>
                    </div>
                </div>
        </div>
    <div class="lineSpace_20">&nbsp;</div>
                        
        <div class="row">
            <div class="r1_1 bld" style="width:250px; font-size:13px; padding-top:5px; color:#333;">Encrypt FieldName:&nbsp;&nbsp;</div>
            <div class="r2_2">
                <input type="text" name="field" id="field" size="10"  style="width:300px; border:1px solid #ccc; font-size:13px; padding:5px 2px;" />
            </div>
            <div class="clear_L"></div>
            <div class="r2_2 errorMsg" id= "temp2_name_error"></div>
            <div class="row errorPlace" style="margin-top:2px;">
                <div class="r1_1">&nbsp;</div>
                <div class="clear_L"></div>
            </div>
       </div>
   <div class="lineSpace_10">&nbsp;</div>
       <div class="row">
            <div class="r1_1 bld" style="width:250px; font-size:13px; padding-top:5px; color:#333;">URl:&nbsp;&nbsp;</div>
            <div class="r2_2">
                <input type="text" name="url" id="url" size="20"  style="width:300px; border:1px solid #ccc; font-size:13px; padding:5px 2px;" />
            </div>
            <div class="clear_L"></div>
            <div class="r2_2 errorMsg" id= "temp4_name_error"></div>
            <div class="row errorPlace" style="margin-top:2px;">
                <div class="r1_1">&nbsp;</div>
                <div class="clear_L"></div>
            </div>
        </div>
    <div class="lineSpace_10">&nbsp;</div>

        <div class="row">
            <div class="r1_1 bld" style="width:250px; font-size:13px; padding-top:5px; color:#333;">Encrypred Url Name:&nbsp;&nbsp;</div>
            <div class="r2_2">
                <input type="text" name="eurl" id="eurl" size="10"  style="width:300px; border:1px solid #ccc; font-size:13px; padding:5px 2px;" />
            </div>
            <div class="clear_L"></div>
            <div class="r2_2 errorMsg" id= "temp5_name_error"></div>
            <div class="row errorPlace" style="margin-top:2px;">
                <div class="r1_1">&nbsp;</div>
                <div class="clear_L"></div>
            </div>
        </div>
    <div class="lineSpace_10">&nbsp;</div>
        <div class="row">
            <div class="r1_1 bld" style="width:250px; font-size:13px; padding-top:5px; color:#333;">Encoded Unsubscribe Url Name:&nbsp;&nbsp;</div>
            <div class="r2_2">
                <input type="text" name="unsuburl" id="unsuburl" size="10" style="width:300px; border:1px solid #ccc; font-size:13px; padding:5px 2px;" />
            </div>
            <div class="clear_L"></div>
            <div class="r2_2 errorMsg" id= "temp6_name_error"></div>
            <div class="row errorPlace" style="margin-top:2px;">
                <div class="r1_1">&nbsp;</div>
                <div class="clear_L"></div>
            </div>
        </div>

        </fieldset>
    </div>

    <div class="lineSpace_10">&nbsp;</div>
    <input type="hidden" name="submit" value="1"/>
    <div class="r1_1 bld" style="width:250px;">&nbsp;</div>
    <div class="r2_2">
        <button class="btn-submit19" type="submit" value="" style="width:100px">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Encrypt Csv</p></div>
        </button>
    </div>
    <div class="clear_L"></div>
    <div class="lineSpace_10">&nbsp;</div>
</form>
 		<div class="lineSpace_35">&nbsp;</div>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
