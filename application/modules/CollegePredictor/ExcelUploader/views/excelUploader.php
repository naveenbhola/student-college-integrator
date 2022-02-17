<div style="width:500px;margin-left:400px;">
	<h4 style="display:block;text-align:center">Upload College Predictor Excel data</h4>
		<div id="mainContainer" style="background-color:#eee;padding:14px 0 10px 35px;">
			<form action="/ExcelUploader/ExcelUploader/loadData" method="post" enctype="multipart/form-data">
			<label for="file">File:</label>
			<input type="file" name="datafile" id="file"><br><br>
			<label for="file">Exam:</label><input type="text" autocomplete="off" name="examname" value="<?php echo $_GET['examName']?>"><br><br>
			<input style="cursor: pointer;" type="submit" id="submit" name="submit" value="Upload" onclick="stopMultipleClick();">
<a style="margin-left: 10px;font-size: 13px;text-decoration: none;" title="Go to Verify Form" href="/ExcelUploader/ExcelUploader/compareExcelData">Verify Data</a>
			<br><br>
			<span id="error" <?php if($this->session->flashdata('error')==1){?> style="color:red;position: absolute;font-size: 14px;"  	<?php }else{?> style="color:green;position: absolute;font-size: 14px;" <?php }?>>
					<?php if($this->session->flashdata('msg')){ echo $this->session->flashdata('msg');}?>
			</span>
			</form>
	</div>
	<?php 
	if($this->session->flashdata('columnError')){?>
	<table>
		<tr><td colspan="5" style="color: red;font-size: 14px;">Error: This column can't be blank in excel.</td></tr>
		<tr>
		<?php $column = $this->session->flashdata('columnError');
			  foreach ($column as $key => $value) {?>
						<td style="background: yellow;padding: 10px 10px 10px;border:1px solid #000"><?php echo $value;?></td>
			  <?php }?>
		</tr>
	</table>
	<?php }?>
</div>
<script type="text/javascript">
function stopMultipleClick() {
	document.getElementById("submit").style.pointerEvents = "none";
}
</script>