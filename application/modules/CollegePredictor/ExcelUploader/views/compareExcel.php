<div style="width:500px;margin-left:400px;">
<h4 style="display:block;text-align:center">Verify College Predictor data</h4>
<div id="mainContainer" style="background-color:#eee;padding:10px 0px">
	<form id="nud" method="post" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="" style="padding:15px 30px">
	<label for="file">Select Exam :</label>
	<select name="examName" id="examName" style="margin-bottom: 20px;">
			<option value="">--Select--</option>
			<?php
				foreach ($examList as $key => $value) {?>
					<option value="<?php echo $value['examName']?>"><?php echo $value['examName']?></option>	
				<?php }?>
	</select></br>

	<label for="file">File :</label>
	<input type="file" name="datafile" id="file"><br><br>

	<input type="submit" name="submit" value="Verify" style="margin-bottom:10px;padding:0px 10px;cursor:pointer;" onclick="return validateExam(this);"> <a style="margin-left: 10px;font-size: 13px;text-decoration: none;" title="Go to Upload Form" href="/ExcelUploader/ExcelUploader/loadExcelUploader">Upload Data</a>
	<br>
	<span id="error" <?php if($this->session->flashdata('error')==1){?> style="color:red;position: absolute;font-size: 14px;"  <?php }else{?> style="color:green;position: absolute;font-size: 14px;" <?php }?>>
		<?php if($this->session->flashdata('msg')){ echo $this->session->flashdata('msg');}?>
	</span>
		<?php if(count($finalReport)>0 && isset($finalReport)){?>
			<span id="dberror" style="color:red;position: absolute;font-size: 14px;">Error: There are few differences between excel and database.</span>
		<?php }?>	

	</form>
	<span style="font-size: 12px;margin-left: 30px;">NOTE : Round Diff will have count of rows.</span>
</div>
</div><br>

<table width="1080px" style=" border-collapse: collapse;font-size: 14px">

	<?php $i=0;foreach($finalReport as $index=>$value1){?>
			<tr>
				<td colspan="20" style="padding: 5px 12px 5px 12px;font-size: 14px;color:#323232;font-weight: 600;"><?php echo 'College predictor - '.$value1['examName'].(($value1['state']) ? ', State = '.$value1['state'] : "").(($value1['city']) ? ', City = '.$value1['city'] : "");?></td>
			</tr>
			
			<?php foreach ($value1['excel'] as $college => $value2) {?>
			<tr class="excel<?php echo $i;?>"><td colspan="20" bgcolor="#e8e8e8" style="padding: 5px 12px 5px 12px;">Excel</td></tr>
				<tr class="colspan">
					<?php
						 foreach($value2 as $columnName=>$v){?>
							<th class="thLn" style="border: 1px solid #dddddd;font-weight:600;color:#323232;font-size:14px;padding: 5px 12px 5px 12px;"><?php echo $columnName;?></th>	

					<?php }?>
				</tr>
				<tr>
					<?php
						 foreach($value2 as $columnName=>$v){?>
							<td align="center" class="thLn" style="border: 1px solid #dddddd;font-weight:normal;font-size:14px;padding: 5px 12px 5px 12px;">
							<?php if($columnName =='roundInfo'){?>
									<table style="font-size: 14px;border-collapse: collapse;" class="round">
							      <?php foreach ($v as $branchName => $roundType) {
										 	foreach ($roundType as $type => $value) {?>
										 	
										 	<tr>
										 		<td colspan="20" style="background: #eee;padding: 5px 10px 5px 10px;">
										 			<span style="font-weight: bold;">Branch - </span><?php echo $branchName;?>
										 		</td>
										 	</tr>
										 	<tr>
										 		<td colspan="20" style="padding: 5px 10px 5px 10px;">
										 			<span style="font-weight: bold;">RankType - </span><?php echo $type;?>
										 		</td>
										 	</tr>

											<?php foreach ($value as $round => $category) {?>
													<tr> 
												    <?php foreach($category as $name => $value){?>
														<td style="border: 1px solid #dddddd;padding: 5px 10px 5px 10px;"><?php echo 'R'.$round.'_'.$name;?></td>
											        <?php }?>
											        </tr>
											        <tr>
											        	<?php foreach($category as $name => $value){?>
														<td align="center" style="border: 1px solid #dddddd;padding: 5px 10px 5px 10px;"><?php echo $value;?></td>
											        <?php }?>
											        </tr>
											<?php }?>
											
										<?php }
									}
							?>
									</table>

							<?php }else{echo $v;}?>
								
							</td>	

					<?php }?>
				</tr>
				<tr class="excel<?php echo $i;?>"><td colspan="20" bgcolor="#e8e8e8" style="padding: 5px 12px 5px 12px;">Database</td></tr>
					<?php
						foreach($finalReport[$index]['db'][$college] as $columnName=>$v){?>
							<td align="center" class="thLn" style="border: 1px solid #dddddd;font-weight:normal;font-size:14px;padding: 5px 12px 5px 12px;">
									<?php if(is_array($v) && $columnName !='roundInfo'){?>
										<table>
											<tr style="font-size: 14px;">
												<td width="60px;">Count - </td>
												<td style="color: red;"><?php echo $v['count']; ?></td>
											</tr>
											<tr style="font-size: 14px;"><td>Diff  - </td>
												<td  style="color: red;">
												<?php foreach ($v['diff'] as $key => $dif) {?>
														<span><?php echo $dif;?></span><br><br>
												<?php }?>
												</td>
											</tr>
											
											<?php if($v['isLive']){?>
											<tr style="font-size: 14px;">
												<td width="60px;">Course - </td>
												<td style="color: red;">Deleted</td>
											</tr>
											<?php }?>

										</table>

								<?php }else if(is_array($v) && $columnName =='roundInfo'){?>
									<table style="font-size: 14px;border-collapse: collapse;" class="round">
							      	<?php foreach ($v as $branchName => $roundType) {
										 	foreach ($roundType as $type => $value) {?>
										 	
										 	<tr>
										 		<td colspan="20" style="background: #eee;padding: 5px 10px 5px 10px;">
										 			<span style="font-weight: bold;">Branch - </span><?php echo $branchName;?>
										 		</td>
										 	</tr>
										 	<tr>
										 		<td colspan="20" style="padding: 5px 10px 5px 10px;">
										 			<span style="font-weight: bold;">RankType - </span><?php echo $type;?>
										 		</td>
										 	</tr>

											<?php foreach ($value as $round => $category) {?>
													<tr> 
												    <?php foreach($category as $name => $value){?>
														<td style="border: 1px solid #dddddd;padding: 5px 10px 5px 10px;"><?php echo 'R'.$round.'_'.$name;?></td>
											        <?php }?>
											        </tr>
											        <tr>
											        	<?php foreach($category as $name => $value){?>
														<td align="center" style="color:red;border: 1px solid #dddddd;padding: 5px 10px 5px 10px;"><?php echo $value;?></td>
											        <?php }?>
											        </tr>
											<?php }?>
											
										<?php }
									}
							?>
									</table>

							<?php }else{ echo $v;}?>
								
							</td>	

					<?php }?>

				<tr><td colspan="20">&nbsp;</td></tr><tr><td colspan="20">&nbsp;</td></tr>
			<?php $i++;}?>
			
	<?php   }?>
</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
function validateExam(thisObj){
	$('#dberror').hide();
	if($('#examName').val()==''){	
		$('#error').text('Please select your exam.').css({'color':'red'});
		return false;
	}
	$('#error').text('');
	$(thisObj).val('Wait...').css({'pointer-events':'none'});
   	readData(thisObj);
	return true;
}

function readData(obj) {
	var txt = $(obj).val();
    setInterval(function(){
	txt = $(obj).val();
    if(txt == 'Wait...'){
    	$(obj).val('Reading...');
    }else if(txt == 'Reading...'){
    	$(obj).val('Wait...');
	}	
}, 3000);
}

$(document).ready(function(){
	$('#nud')[0].reset();
	clearInterval();
	$('.colspan').each(function(i){
 		var totalColspan = $(this).find('th').length;
  		$(".excel"+i).find('td').attr('colspan',totalColspan);
  	});
});
</script
</script>