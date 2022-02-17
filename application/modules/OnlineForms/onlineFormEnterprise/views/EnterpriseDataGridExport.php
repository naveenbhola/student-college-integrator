<?php
	$url = '/onlineFormEnterprise/EnterpriseDataGrid/downloadFile/'.base64_encode($fileName).'/'.$courseId.'/';
?>
	<div style="color:green"><br/>File is generated and your download will start automatically.</div>
	If not <a href="<?=$url?>" style="color:#0065DE;text-decoration:none">Click Here</a> to download.<br/>

<script>
parent.document.getElementById('exportOverlayDiv').style.height = '200px';
window.location = '<?=$url?>';
</script>