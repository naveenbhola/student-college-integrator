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
	<div style="float:right;margin-right: 20px; ">
		<input type="button" value="Add New Porting" class="new-serset-btn" onclick="window.location = '/lms/Porting/addNewPorting';">
	</div>
	<?php
		if(count($portings)) {
	?>
	<div style="float:right;margin-right:25px;">
		<input type="button" value="Download" class="new-serset-btn" onclick="window.open('/lms/Porting/downloadPortings');">
	</div>
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
				<th>Current Status</th>
				<th>Actions</th>
				<th>Edit</th>
				<th>Clone Porting</th>
				<th style="width: 180px">Last Ported Lead Date</th>
			</tr>

		<?php
		foreach ($portings as $key => $portingData) {

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
				
				if($portingData['status'] == 'live') {
					echo '<td>Activated</td>';
					echo '<td><input type="button" value="Deactivate" class="new-serset-btn" onclick="updateProtingStatus('.$portingData['id'].',\'stopped\',\''.$portingData['portingType'].'\');"></td>';
				}
				else if($portingData['status'] == 'stopped') {
					echo '<td>Deactivated</td>';
					echo '<td>';
					echo '<table>';
					if($portingData['request_type'] != 'EMAIL'){
						if($portingData['isrun_firsttime'] == 'no' && $portingData['last_ported_id'] == 0){
							echo '<tr><input type="button" value="Test" style="width: 80px" class="new-serset-btn" onclick="updateProtingStatus('.$portingData['id'].',\'intest\',\''.$portingData['portingType'].'\');"></tr>';
						}else {
							echo '<tr><input type="button" value="Test Again" style="width: 80px;margin-bottom:10px;" class="new-serset-btn" onclick="updateProtingStatus('.$portingData['id'].',\'intest\',\''.$portingData['portingType'].'\');"></tr>';
							echo'<tr><input type="button" value="Activate" style="width: 80px" class="new-serset-btn" onclick="updateProtingStatus('.$portingData['id'].',\'live\',\''.$portingData['portingType'].'\');"></tr>';
						}
					} else {
						echo'<tr><input type="button" value="Activate" style="width: 80px" class="new-serset-btn" onclick="updateProtingStatus('.$portingData['id'].',\'live\',\''.$portingData['portingType'].'\');"></tr>';
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
				echo '<td><input type="button" value="Edit" class="new-serset-btn" onclick="window.location = \'/lms/Porting/editPorting/'.$portingData['id'].'\';"></td>';
				echo '<td><input type="button" value="Clone" class="new-serset-btn" onclick="window.location = \'/lms/Porting/clonePorting/'.$portingData['id'].'\';"></td>';;
				if($portingData['portingType'] != "examResponse"){
					echo "<td> 
							<input type='text' style='width:80px; color:#888a89' placeholder='yyyy-mm-dd'  readonly='true' name='timerange_from_".$portingData['id']." ' id='timerange_from_".$portingData['id']."'>&nbsp; 
							<img style='cursor:pointer;' class='caln-mid-txt' src='/public/images/calen-icn.gif' id='timerange_from_img_".$portingData['id']."' onclick='timerangeFrom(".$portingData['id'].");' style='position: relative;top:1px'>
							<span style='margin-left: 8px;'>
								<input type='button' onclick='updateDate(".$portingData['id'].", \"".$portingData['portingType']."\");' value='Update' class='new-serset-btn'>
							</span>
						 </td>";
				}
				echo '</tr>';
		} ?>
		</table>
		<?php } ?>
		<div class="clearFix"></div>
	</div>
</div>

<!--Code Ends here-->
<script type="text/javascript">
function updateProtingStatus(porting_id,status,portingType) {
	new Ajax.Request('/lms/Porting/updateProtingStatus',
		{	method:'post',
			onSuccess:function(request){
				if(request.responseText == 'success') {
					window.location = '/lms/Porting/managePortings';
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

function timerangeFrom(portingId)
{
    var calMain = new CalendarPopup('calendardiv');
    disDate = null;
    frmdisDate = new Date();
    isresponseViewer = 1;
    calMain.select($('timerange_from_'+portingId),'timerange_from_img_'+portingId,'yyyy-mm-dd');
    return false;
}

function updateDate(portingId,portingType){
	var date = document.getElementById('timerange_from_'+portingId).value;
	if(date != undefined && date != "") {
		new Ajax.Request('/lms/Porting/updateLastPortedId',
		{	method:'post',
			onSuccess:function(request){
				if(request.responseText == 'success') {
					window.location = '/lms/Porting/managePortings';
				} else if(request.responseText == 'accessDenied'){
					alert('You do not have access to update the Date!');
				} else if(request.responseText == 'dateLimitExceeded'){
					alert('Dates older than 3 months cant be selected !');
				}else {
					alert('Something went wrong...');
				}
			},
			onFailure: function(){ alert('Something went wrong...'); },
			evalScripts:true,
			parameters:'portingId='+portingId+'&date='+date+'&type='+portingType
		}
	);
	} else{
		alert("No Date Selected !")
	} 
}

</script>
<?php $this->load->view('common/footer');?>
