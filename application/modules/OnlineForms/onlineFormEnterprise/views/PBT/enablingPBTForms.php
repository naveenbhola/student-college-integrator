<div style='padding:20px 15px 40px 15px;'>
	<div class="pbtAutomationCms">
		<div class="line">
			<div class="inline label">Enter college ID:</div>
			<div class="inline input"><input type="number" maxlength="10" id="collegeId"/></div>
			<div class="inline btn"><input type="button" id="getPBTData" value="OK"/></div>
			<div class="inline clgName" id="srchClgName"></div>
		</div>
		<div style="border:1px solid #7AA821; line-height:5px; background-image:url(/public/images/greenline.jpg); margin-top:20px; margin-bottom:10px; display:none;" class="tabBorder">&nbsp;</div>
		<div class="line" id="subCatSelect"></div>
		<div style="border:1px solid #7AA821; line-height:5px; background-image:url(/public/images/greenline.jpg); margin-top:10px; margin-bottom:20px; display:none;" class="tabBorder">&nbsp;</div>
		<form action="/onlineFormEnterprise/PBTFormsAutomation/addNewPBTForm" method="post" id="enablePBTForm">
			<div class="line" id="pbtFormAdd"></div>
		</form>
	</div>
	<?php $this->load->view('common/calendardiv'); ?>
</div>
<?php $this->load->view('common/leanFooter'); ?>
<script type="text/javascript">
	var pbtObj = new pbtAutomation();
	$j(document).ready(function(){
		pbtObj.DOMReadyCalls();
		$j(window).scrollTop(0);
	});
</script>
<script language="javascript" src="//<?php echo JSURL?>/public/js/CalendarPopup.js"></script>