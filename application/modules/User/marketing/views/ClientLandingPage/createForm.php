<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog','footer','ajax-api'),
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
</head>
<body>
    
<div class="mar_full_10p" style="margin-top:15px; min-height:600px;">
<?php
$this->load->view('mailer/left-panel-top');
?>    
    
<div style='padding:10px; padding-top:0;'>
    
    <div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px; float:left;"><strong>
        <?php echo $formId ? "Edit Form Mailer: <span style='color:#666;'>".$form['name']."</span>" : "Create New Form Mailer"; ?>
    </strong></div>
    
    <div style="clear:both;"></div>
    
    <div style="font-size:15px; margin-top: 10px;border-top:0px solid orange; background:#f6f6f6; padding:20px 20px 50px 20px;">
        <form name="marketingFormMailer" id="marketingFormMailer" action="/mailer/MarketingFormMailer/saveForm" method="POST">
        <div style="float:left; width:200px; text-align: right; padding-top:6px;">Name:</div>
        <div style="float:left; width:400px; margin-left: 20px;">
            <input type="text" id="formName" name="name" style="width:350px; border:1px solid #ccc; padding:5px; font-size:15px;" maxlength="100" value='<?php echo html_escape($form['name']); ?>' />
            <div id="formName_error" style='color:red; font-size:12px; display: none;'>Please enter form name</div>
        </div>
        <div style="clear:both; padding:10px;"></div>
    
        <div style="float:left; width:200px; text-align: right; padding-top:2px;"></div>
        <div style="float:left; width:400px; margin-left: 20px;">
            <input type="button" value="Submit" style="padding:5px; font-size:15px;" onclick="submitCreateMarketingFormMailerForm()" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href='/mailer/MarketingFormMailer/listForms' style='font-size:12px;'>Cancel</a>
        </div>
        <div style="clear:both;"></div>
        <input type='hidden' name='formId' value='<?php echo $form['id']; ?>' />
        </form>
    </div>
</div>

<script>
    function submitCreateMarketingFormMailerForm()
    {
        var error = false;
        var formName = trim($('formName').value);
        
        if (formName == '') {
            $('formName_error').style.display = '';
            $('formName').value = '';
            $('formName').focus();
            //error = true;
        }
        else {
            $('formName_error').style.display = 'none';
        }
        
        if (!error) {
            $('marketingFormMailer').submit();
        }
    }
</script>


