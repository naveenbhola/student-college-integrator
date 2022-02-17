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
	<div class="mar_full_10p" style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		<form id="formForEdit_Var_Template" action="/mailer/Mailer/saveConfigureNewsletter" method="POST">
		    <input type="hidden" name="temp_op_mode" id="temp_op_mode" value="<?php echo $temp_op_mode; ?>">
		    <input type="hidden" name="temp_id" id="temp_id" value="<?php echo $temp_id; ?>">
		    <input type="hidden" name="templateType"  value="<?php echo $templateType; ?>">
		
		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;"><strong>Configure Newsletter</strong></div>
		
		<div>
			<div style="width:15px" class="float_L">&nbsp;</div>
				<div style="margin-left:20px">

					<div style="margin-bottom: 10px;">
						<input type="hidden" name="templateId" id="templateId" value="<?php echo $newsletterParams['templateId']; ?>" />
						<div style="float:left; width:170px;">
							<span style='font-weight:bold; font-size: 13px;'>Article IDs:</span>
						</div>
						<div style="float:left; width:500px;">
						<input type="text" name="articleIDs" id="articleIDs" value="<?php echo $newsletterParams['articleIds']; ?>" style="width:400px; border:1px solid #ccc; padding:2px; margin-left: 10px;" />
						</div>
						<div style="clear: both"></div>
						<div id='Testbutton_mail' style="color:red; margin-left:180px;padding-top:5px;"><?php if($newsletterParams['templateId'] == 3148) { ?>(Enter 1, 3 or 5 articles. If you enter 2, 4 or 6, your last article will not be sent.) <?php } ?></div>
						<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "articleIDs_error"></div>
							<div class="clear_L"></div>
						</div>
					</div>	
				
					<div style="margin-bottom: 15px;">
						<div style="float:left; width:170px;">
						<span style='font-weight:bold; font-size: 13px;'>Question/Discussion IDs:</span>
						</div>
						<div style="float:left; width:500px;">
						<input type="text" name="discussionIDs" id="discussionIDs" value="<?php echo $newsletterParams['discussionIds']; ?>" style="width:400px; border:1px solid #ccc; padding:2px; margin-left: 10px;" />
						</div>
						<div style="clear: both"></div>
						<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "discussionIDs_error"></div>
							<div class="clear_L"></div>
						</div>
					</div>

					<div style="margin-bottom: 10px;">
						<div style="float:left; width:170px;">
						<span style='font-weight:bold; font-size: 13px;'>This mailer includes client article (No MPT):</span>
						</div>
						<div style="float:left; width:500px;">
						<input type="checkbox" name="include_MPT_tuple" id="include_MPT_tuple" style="padding:2px; margin-left: 10px;"/>
						</div>
						<div style="clear: both"></div>
						<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "include_MPT_tuple_error"></div>
							<div class="clear_L"></div>
						</div>
					</div>
					
					<?php /*	
					<div style="margin-bottom: 10px;">
						<div style="float:left; width:170px;">
						<span style='font-weight:bold; font-size: 13px;'>Event IDs:</span>
						</div>
						<div style="float:left; width:500px;">
						<input type="text" name="eventIDs" id="eventIDs" value="<?php echo $newsletterParams['eventIds']; ?>" style="width:400px; border:1px solid #ccc; padding:2px; margin-left: 10px;" />
						</div>
						<div style="clear: both"></div>
						<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="r2_2 errorMsg" id= "eventIDs_error"></div>
							<div class="clear_L"></div>
						</div>
					</div>
					*/
					?>
				</div>
			</div>
			<div class="clear_L"></div>
			
			<div class="clearFix"></div>
			<div style="background:#f0f0f0; text-align: center; padding:5px 0; margin-top:10px;">
				<button class="btn-submit19" type="button" onclick="return validationArticleIds();" value="" style="width:100px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
				</button>
			</div>
			
		</div>

		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		</form>
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
	<script>
   	addOnFocusToopTip(document.getElementById('formForEdit_Var_Template'));
   	addOnBlurValidate(document.getElementById('formForEdit_Var_Template'));
   	function validationArticleIds(){
   		if(document.getElementById('articleIDs').value.trim()=='' || document.getElementById('articleIDs').value.trim().length==0){
   			alert("Article IDs cannot be empty");
   			return;
   		}
   		document.getElementById('formForEdit_Var_Template').submit();
   	}
   	</script>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
