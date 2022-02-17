<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<!--Code Starts form here-->
<div id="lms-port-wrapper">
	<div class="page-title">
		<h2>Manage Portings</h2>
</div>
	<div style="float:right">
		<input type="button" value="Add New OAF Porting" class="new-serset-btn" onclick="window.location = '/oafPorting/OafPorting/addNewPorting';">
	</div>
	<!-- <div style="float:right;margin-right: 20px; ">
		<input type="button" value="Add New Porting" class="new-serset-btn" onclick="window.location = '/lms/Porting/addNewPorting';">
	</div> -->
	<?php
		if(count($portings)) {
	?>
	<!-- <div style="float:right;margin-right:25px;">
		<input type="button" value="Download" class="new-serset-btn" onclick="window.open('/lms/Porting/downloadPortings');">
	</div> -->
	<?php } ?>
	<div class="clearFix"></div>
	<div id="lms-port-content">
		<?php
			if(empty($portings)) {
				echo "No portings have been set up currently.";
			}
			else {
		?>
		<table cellpadding="6" cellspacing="0" border="0" width="100%" class="manage-table" style="table-layout:fixed;margin-top:10px;">
			<tr>
				<th>Porting ID</th>
				<th>Porting Name</th>
				<th>Client ID</th>
				<th>Client Name</th>
				<th>Porting Type</th>
				<th>Porting Method</th>
				<th>Current Status</th>
				<th>Actions</th>
				<th>Edit</th>
			</tr>
		<?php foreach ($portings as $key => $portingData) {
			if($key%2 == 0) {
				echo '<tr>';
			}
			else {
				echo '<tr class="alt-row">';
			}
				echo '<td>'.$portingData['id'].'</td>';
				echo '<td>'.$portingData['name'].'</td>';
				echo '<td>'.$portingData['client_id'].'</td>';
				echo '<td>'.$portingData['displayname'].'</td>';
				echo '<td>'.$portingData['type'].'</td>';
				echo '<td>'.$portingData['request_type'].'</td>';
				if($portingData['status'] == 'live') {
					echo '<td>Activated</td>';
					echo '<td><input type="button" value="Deactivate" class="new-serset-btn" onclick="updateOAFProtingStatus('.$portingData['id'].',\'stopped\',\''.$portingData['portingType'].'\');"></td>';
				}
				else if($portingData['status'] == 'stopped') {
					echo '<td>Deactivated</td>';
					echo '<td>';
					echo '<table>';
					if($portingData['request_type'] != 'EMAIL'){
						if($portingData['isrun_firsttime'] == 'no' && $portingData['last_ported_id'] == 0){
							echo '<tr><input type="button" value="Test" style="width: 80px;margin-bottom:10px;" class="new-serset-btn" onclick="updateOAFProtingStatus('.$portingData['id'].',\'intest\',\''.$portingData['portingType'].'\');"></tr>';
						}else {
							echo '<tr><input type="button" value="Test Again" style="width: 80px;margin-bottom:10px;" class="new-serset-btn" onclick="updateOAFProtingStatus('.$portingData['id'].',\'intest\',\''.$portingData['portingType'].'\');"></tr>';
						}
						echo'<tr><input type="button" value="Activate" style="width: 80px" class="new-serset-btn" onclick="updateOAFProtingStatus('.$portingData['id'].',\'live\',\''.$portingData['portingType'].'\');"></tr>';
					} else {
						echo'<tr><input type="button" value="Activate" style="width: 80px" class="new-serset-btn" onclick="updateOAFProtingStatus('.$portingData['id'].',\'live\',\''.$portingData['portingType'].'\');"></tr>';
					}
					echo '</table>';
					echo '</td>';
				}
				else if($portingData['status'] == 'expired') {
					echo '<td>Expired</td>';
					echo '<td>NA</td>';
				} else if($portingData['status'] == 'intest') {
					echo '<td>In Test</td>';
					echo '<td>NA</td>';
				}
				echo '<td><input type="button" value="Edit" class="new-serset-btn" onclick="window.location = \'/oafPorting/OafPorting/editOAFPorting/'.$portingData['id'].'\';"></td>';
				echo '</tr>';
		} ?>
		</table>
		<?php } ?>
		<div class="clearFix"></div>
	</div>
</div>
<!--Code Ends here-->
<script type="text/javascript">
function updateOAFProtingStatus(porting_id,status,portingType) {
	new Ajax.Request('/oafPorting/OafPorting/updateProtingStatus',
		{	method:'post',
			onSuccess:function(request){
				if(request.responseText == 'success') {
					window.location = '/oafPorting/OafPorting/manageOAFPortings';
				}
				else {
					alert('Something went wrong...');
				}
			},
			onFailure: function(){ alert('Something went wrong...'); },
			evalScripts:true,
			parameters:'porting_id='+porting_id+'&status='+status+'&portingType='+portingType
		}
	);
}

</script>
<?php $this->load->view('common/footer');?>
