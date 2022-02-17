<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<style type="text/css">
	table.priTable { border-left:1px solid #ccc; border-top:1px solid #ccc; margin-bottom: 20px;}
	table.priTable th { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font:bold 13px arial; text-align: left; padding:4px; background:#f1f1f1;}
	table.priTable td { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font:normal 13px arial; text-align: left; padding:4px;}
	ul.primenu {margin-bottom: 20px; border-bottom: 1px solid #ccc;}
	ul.primenu li {float: left; background: #eee; font:normal 14px arial; margin-right: 5px; margin-left: 10px; border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc;}
	ul.primenu li a {display: block; padding:8px; font:normal 14px arial; text-decoration: none;}
	li#priactivemenu {background: #fff;}
	li#priactivemenu a {color:#333; font-weight:bold;}
</style>

<div style="width: 950px;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
	
<ul class='primenu'>
	<li id='priactivemenu'><a href='/enterprise/PRILine/index'>Map Numbers</a></li>
	<li><a href='/enterprise/PRILine/viewMapped'>View Mapped Numbers</a></li>
	<div style="clear:both;"></div>
</ul>	
	
	
<div class="orangeColor fontSize_14p bld" style="width: 950px;"><span><b>Map PRI Numbers</b></span><br/>
<div class="lineSpace_10">&nbsp;</div>
</div>

<div style='background:#f6f6f6; padding:10px;'>
<form action="/enterprise/PRILine/index" method="post" id="priForm">
   <div>
    <div style="width: 175px; line-height: 18px; margin-top: 6px;" class="float_L">
        <div style="padding-right: 5px; font:bold 13px arial; color:#444;" class="txt_align_r">Category : </div>
    </div>
    <div style="margin-left: 187px;padding-bottom:5px;">
        <div>
			<select name="category" id="priCategory" class="universal-select" style="width:300px;">
			<option value=''>Select</option>
			<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category['id']; ?>' <?php if($category['id'] == $selectedCategory) echo "selected='selected'"; ?>><?php echo $category['name']; ?></option>
			<?php } ?>
			</select>        
        </div>
    </div>
	<div class="lineSpace_10">&nbsp;</div>
	<div style="width: 175px; line-height: 18px; margin-top: 6px;" class="float_L">
        <div style="padding-right: 5px;font:bold 13px arial; color:#444;" class="txt_align_r">City : </div>
    </div>
    <div style="margin-left: 187px;padding-bottom:5px;">
        <div>
			<select name="city" id="priCity" class="universal-select" style="width:300px;">
			<option value=''>Select</option>
			<?php foreach($cities as $city) { ?>
				<option value='<?php echo $city['city_id']; ?>' <?php if($city['city_id'] == $selectedCity) echo "selected='selected'"; ?>><?php echo $city['city_name']; ?></option>
			<?php } ?>
			</select>        
        </div>
    </div>
</div>

<div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<input type="submit" class="orange-button" value="Search" onclick="return submitPRIForm()" style="margin-left: 185px;" />
</form>
</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<?php if($selectedCategory && $selectedCity) { ?>
<?php if(is_array($courses) && count($courses) > 0) { ?>
<table cellpadding='0' cellspacing='0' width='950' class="priTable">
	<tr>
		<th width="180">Client</th>
		<th width="200">Institute</th>
		<th>Course</th>
		<th width="80">Responses</th>
		<th width="200">PRI</th>
	</tr>
	<?php foreach($courses as $course) { ?>
	<tr>
		<td><?php echo $course['clientName']; ?></td>
		<td><?php echo $course['instituteName']; ?></td>
		<td><?php echo $course['courseName']; ?></td>
		<td><?php echo $course['responseCount']; ?></td>
		<td id='pri_block_<?php echo $course['courseId']; ?>'>
			<?php if($PRIAssignments[$course['courseId']]) { ?>
			<div style="background: #E3ECF3; padding:5px;">
			<div style='float:left;'>
				<span style='color:#205081; font:bold 14px arial;'><?php echo $PRIAssignments[$course['courseId']]['PRINumber']; ?></span><div style='margin-top:1px; color:#666; font-size: 12px;'><i>Set on <?php echo date('M j Y',strtotime($PRIAssignments[$course['courseId']]['setOn'])); ?></i></div>
			</div>
			<div style='float:right'>
				<input type='button' id="pri_reset_button_<?php echo $course['courseId']; ?>" value='Reset' onclick="resetPRI('<?php echo $course['courseId']; ?>');" />
			</div>
			<div style="clear:both;"></div>
			</div>
			<?php } else { ?>
			<input type='text' id="pri_<?php echo $course['courseId']; ?>" style='width:120px;' />
			<input type='button' id="pri_button_<?php echo $course['courseId']; ?>" value='Set' onclick="setPRI('<?php echo $course['courseId']; ?>');" />
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>
<?php } else { ?>
<h2 style="font:bold 13px arial; color:red; margin-bottom: 20px; display: block; background: #f8f8f8; padding:15px;">No courses found for selected criteria.</h2>
<?php } ?>
<?php } ?>
<script>
	function submitPRIForm()
	{
		var catId = $('priCategory').value;
		var cityId = $('priCity').value;
		if (!catId) {
			alert("Please select category");
			return false;
		}
		if (!cityId) {
			alert("Please select city");
			return false;
		}
		return true;
	}
	
	function setPRI(courseId)
	{
		var PRI = trim($('pri_'+courseId).value);
		if (PRI == '') {
			alert('Please enter PRI number to set');
		}
		else {
			$('pri_'+courseId).disabled = true;
			$('pri_button_'+courseId).disabled = true;
			
			var mysack = new sack();
			mysack.requestFile = '/enterprise/PRILine/set';
			mysack.method = 'POST';
			mysack.setVar('courseId',courseId);
			mysack.setVar('PRINumber',PRI);
			mysack.setVar('cityId','<?php echo $selectedCity; ?>');
			mysack.setVar('categoryId','<?php echo $selectedCategory; ?>');
			mysack.onLoading = function() { };
			mysack.onCompletion = function() {
				var response = eval("("+mysack.response+")");
				if (response['status'] == 'ALREADY_ASSIGNED') {
					alert("This number is already assigned.");
					$('pri_'+courseId).disabled = false;
					$('pri_button_'+courseId).disabled = false;
				}
				else {
					$('pri_block_'+courseId).innerHTML = "<div style='background: #E3ECF3; padding:5px;'><div style='float:left;'><span style='color:#205081; font:bold 14px arial;'>"+response['PRINumber']+"</span><div style='margin-top:1px; color:#666; font-size: 12px;'><i>Set on "+response['setOn']+"</i></div></div><div style='float:right'><input type='button' id='pri_reset_button_"+courseId+"' value='Reset' onclick=\"resetPRI('"+courseId+"');\" /></div><div style='clear:both;'></div></div>";
				}
			};
			mysack.runAJAX();
		}
	}
	
	function resetPRI(courseId)
	{
		if(confirm('Are you sure?')) {
			$('pri_reset_button_'+courseId).disabled = true;
			
			var mysack = new sack();
			mysack.requestFile = '/enterprise/PRILine/reset';
			mysack.method = 'POST';
			mysack.setVar('courseId',courseId);
			mysack.onLoading = function() { };
			mysack.onCompletion = function() {
				$('pri_block_'+courseId).innerHTML = "<input type='text' id='pri_"+courseId+"' style='width:120px;' /> <input type='button' id='pri_button_"+courseId+"' value='Set' onclick=\"setPRI('"+courseId+"');\" />";
			};
			mysack.runAJAX();
		}
	}
</script>

</div>
<?php $this->load->view('common/leanFooter');?>
