<title> SMS Campaign</title>
<?php
$headerComponents = array(
    'js' => array('mailer'),
	'css'	=> array('raised_all','mainStyle'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'noextra_js'=>true
	//'tabName'	=>	'Mass Mailer',
	//'taburl' => site_url('mailer/Mailer')
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    //$this->load->view('common/calendardiv');
?>

</head>
<body>
    <div class="mar_full_10p" style="margin-top: 15px;">
    <div id="leftPanelHolder">
    	<?php $this->load->view('mailer/left-panel-top'); ?>
    </div>

    <div id="smsResponseHolder" style="display:block;margin-left:210px;">
        <div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;">
                <strong> Response SMS Interface </strong>
        </div>

        <form method="post" id="saveCSV" action="/mailer/Mailer/saveSMSList" enctype="multipart/form-data">
            <table style="font-family:arial;font-size:12px;">
                <tr>
                    <td valign="top">
                        <label>Enter Client Id : </label>
                    </td>
                    <td>
                        <input type="text" value="" style="width:324px !important;"  id="clientId" class="input" name="clientId" onblur="validateField(this.value,'clientErrorMsg')"/>
                        <span id="clientErrorMsg" style="color:red; display:block;"> </span> 
                    </td>
                </tr>

                <tr>
                    <td valign="top">
                        <label>Campaign Name : </label>
                    </td>
                    <td>
                        <input type="text" value="" style="width:324px !important;"  id="campaignName" class="input" name="campaignName" onblur="validateField(this.value,'campaignErrorMsg')"/>
                        <span id="campaignErrorMsg" style="color:red; display:block;"> </span> 
                    </td>
                </tr>

                <tr>
                    <td valign="top"> 
                        <label>Message : </label>
                    </td>
                    <td>
                        <textarea id="message" class="" name="message" rows="4" cols="50" onblur="validateField(this.value,'ErrorMsg')"></textarea>
                       <span id="ErrorMsg" style="color:red;display:block;"> </span>
                        <br/style="height:35px;"><label><b>Note: </b> Use '@landingURL' in place of response URL.</label>
                     </td>
                </tr>

                <tr style="height:40px;">
                    <td valign="top">
                        <label> Upload File : </label>
                    </td>
                    <td>
                        <input type="file" name="userSetFile" id="userSetFile" size="10" style="" onblur="validateField(this.value,'fileErrorMessage')" />
                        <span id="fileErrorMessage" style="display:block; color:red;"><?php echo $errorMessage; ?></span>
                        <br/style="height:35px;"><label><b>Note: </b> 1. Upload Excel Sheet in a format that first column contains Email id and second Course id. <br> <span style="margin-left: 36px;">2. First row contains Email Id and Course Id as heading then followed by data from second row</span> <br><span style="margin-left: 36px;">3. Before uploading excel sheet please remove hyperlink from email column<span></label>
                     </td>
                </tr>

                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" class="submit_Button" value="save" />
                    </td>
                </tr>
            </table>

        </form>
    </div> <!-- end of smsResponseHolder -->
</div>
<hr style="margin-left:210px;margin-right:10px;"/>