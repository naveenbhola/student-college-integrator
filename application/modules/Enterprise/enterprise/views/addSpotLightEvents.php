<div class="lineSpace_20">&nbsp;</div>
<form id="eventForm" enctype="multipart/form-data" method="post" action="/enterprise/Enterprise/addSpotlightEvent">
<?php
                        if($refererAddEvent != '' && strtolower($refererAddEvent) == 'updated'){
                                        $showMessage = 'Event Successfully Updated.';
                                }else if($refererAddEvent != ''){
                                        $showMessage = 'Event Id not found in Database '.$refererAddEvent;
                                }
                ?>
<?php if($refererAddEvent != '' && strtolower($refererAddEvent) == 'updated'){ ?>
<div class="fontSize_11p" align="center"> <i><?php echo $showMessage; ?></i> </div>
<?php } ?>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="row">
		<div class="r1 bld"><b>Event 1:</b></div>
		<div class="r2">
			<?php foreach($spotlightEvents as $temp){
			$eventId1=$temp['eventId1'];
			$eventId2=$temp['eventId2'];
			$paidEventId=$temp['paidEventId'];
			$uploadedImage=$temp['uploadImage'];
			$tillDate=$temp['tillDate'];
			} ?>
			<div><input type="text" name="event_id_1" id="event_id_1" validate="validateStr" profanity="true" required="true" value="<?php echo $eventId1;?>" maxlength="8" class="w10_per" tip="event_id_1" caption="Event 1"/></div>
		</div>
		<div class="clear_L"></div>
	</div>
<?php if(is_numeric(strpos($refererAddEvent,"Event 1: "))){ ?>
                                                <div class="r1 bld">&nbsp;</div>
						<div class="r2">
						<i class="redcolor"><?php echo $showMessage; ?></i>
						</div>
						<div class="lineSpace_5">&nbsp;</div>
<?php } ?>

	<div class="lineSpace_10">&nbsp;</div>
	
	<div class="row">
		<div class="r1 bld"><b>Event 2:</b></div>
		<div class="r2"><div><input type="text" name="event_id_2" id="event_id_2" validate="validateStr" profanity="true" required="true" value="<?php echo $eventId2; ?>" maxlength="8" class="w10_per" tip="event_id_2" caption="Event 2"/></div></div>
		<div class="clear_L">&nbsp;</div>
	</div>
<?php if(is_numeric(strpos($refererAddEvent,"Event 2: "))){ ?>
<div class="r1 bld">&nbsp;</div>
                                                <div class="r2">
                                                <i class="redcolor"><?php echo $showMessage; ?></i></div>
                                                <div class="lineSpace_5">&nbsp;</div>
<?php } ?>
	<div class="lineSpace_10">&nbsp;</div>	
	<div class="row">
		<div class="r1 bld"><b>Paid Event:</b></div>
		<div class="r2">
			<div><input type="text" name="paidEventId" id="paidEventId" validate="validateStr" profanity="true" required="true" value="<?php echo $paidEventId; ?>" maxlength="8" class="w10_per" tip="paidEventId" caption="Paid Event"/></div>
        </div>
		<div class="clear_L"></div>
	</div>
<?php if(is_numeric(strpos($refererAddEvent,"Paid Event: "))){ ?>
<div class="r1 bld">&nbsp;</div>
                                                <div class="r2">
                                                <i class="redcolor"><?php echo $showMessage; ?></i>
                                                </div>
                                                <div class="lineSpace_5">&nbsp;</div>
<?php } ?>
	<div class="lineSpace_10">&nbsp;</div>
	
	<div class="row">
		<div class="r1 bld"><b>Upload Image:</b></div>
		<div class="r2">
			<div><input name="uploadedImage[]" value="" type="file" id="uploadedImage"/></div>
        </div>
		<div class="clear_L"></div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	
	<div class="row">
		<div class="r1 bld"><b>Till Date</b></div>
		<div class="r2">
			<div>
				<?php if($tillDate==0000-00-00){ $tillDate='';}?>
				<input style="width:75px;" type="text" required="true" required="true" validate="validateStartDate" name="till_date" id="till_date" value="<?php echo $tillDate; ?>" readonly maxlength="10" size="15" class="" onClick="cal.select($('till_date'),'td','yyyy-MM-dd');" caption="Till Date"/>&nbsp;<img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="td" onClick="cal.select($('till_date'),'td','yyyy-MM-dd');" />
			</div>
        </div>
		<div class="clear_L"></div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>

	<div class="row">
		<div class="r1 bld">&nbsp;</div>
		<div class="r2">
			<span>
				<button class="btn-submit13" onClick="return validateSpotlightEvents();"type="submit" style="width:100px" >
				<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Submit</p></div>
				</button>
			</span>&nbsp;&nbsp;
			<span>
				<button class="btn-submit5 w12" value="" onClick="location.replace('/enterprise/Enterprise/index/45')" type="button">
				<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
				</button>
			</span>
		</div>
		<div class="clear_L"></div>
	</div>
</form>
<script>
var cal = new CalendarPopup("calendardiv");
function validateSpotlightEvents(){
	if(document.getElementById('event_id_1').value==null || document.getElementById('event_id_1').value==''){
		alert("Please enter first spotlight event.");
		$('event_id_1').focus();
		return false;
	}if (!document.getElementById("event_id_1").value.match(/^\d+$/)) {
                alert("Event Id of first event should be numeric");
		document.getElementById('event_id_1').value='';
                $('event_id_1').focus();
                return false;   
        }if(document.getElementById('event_id_2').value==null || document.getElementById('event_id_2').value==''){
		alert("Please enter second spotlight event.");
		 $('event_id_2').focus();
		 return false;
	}if (!document.getElementById("event_id_2").value.match(/^\d+$/)) {
                alert("Event Id of second event should be numeric");
		document.getElementById('event_id_2').value='';
                $('event_id_2').focus();
                return false;   
        }
	if(document.getElementById('paidEventId').value!=null && document.getElementById('paidEventId').value!=''){
		if (!document.getElementById("paidEventId").value.match(/^\d+$/)) {
                alert("Event Id of paid event should be numeric");
                document.getElementById('paidEventId').value='';
                $('paidEventId').focus();
                return false;   
        	}
		if( document.getElementById('uploadedImage').value==null || document.getElementById('uploadedImage').value==''){
			alert("Please upload image with paid event.");	
			 $('uploadedImage').focus();
	                 return false;
		}
		if( document.getElementById('till_date').value==null || document.getElementById('till_date').value==''){
                        alert("Please enter till date with paid event.");  
                         $('till_date').focus();
                         return false;
                }
	}else{
		if( document.getElementById('uploadedImage').value!=null && document.getElementById('uploadedImage').value!=''){
                        alert("Please enter paid event id before uploading image.");  
                         $('paidEventId').focus();
                         return false;
                }
                if( document.getElementById('till_date').value!=null && document.getElementById('till_date').value!=''){
                        alert("Please enter paid event id before entering till date.");  
                         $('paidEventId').focus();
                         return false;
                }
	}
	 var tillDate=document.getElementById('till_date').value;
	 if(tillDate!=''){
	 var dateArray = tillDate.split("-");
	 if(dateArray.length < 3 ) {
	 alert("Please enter date in correct format of yyyy-mm-dd");
	 document.getElementById('till_date').value='';
	 $('till_date').focus();
	 return false;
	 }
	 var enteredYear = dateArray[0];
	 var enteredMonth = dateArray[1]-1;
	 var enteredDay = dateArray[2];
	 if((validateInteger(enteredYear,4) != true) ||( validateInteger(enteredMonth,2) != true) || ( validateInteger(enteredDay,2) != true)) {
         alert("Please enter till date in correct format of yyyy-mm-dd with all the values as numbers");
	 document.getElementById('till_date').value='';
	 $('till_date').focus();
	 return false;
 	 }
    	if(!(validateInteger(enteredYear,4) && validateInteger(enteredMonth,2) && validateInteger(enteredDay,2))) {
		alert("Please fill the field with correct date in format of yyyy-mm-dd with all the values as numbers");
		document.getElementById('till_date').value='';
		$('till_date').focus();
		return false;
	}
	var today = new Date();
	var enteredDate = new Date();
	enteredDate.setDate(enteredDay);
	enteredDate.setMonth(enteredMonth);
	enteredDate.setYear(enteredYear);
	if(enteredDate < today) {
		alert('Till date must be greater than the current date.');
		document.getElementById('till_date').value='';
		$('till_date').focus();
		return false;
	}}
}
</script>
<style>
.bgsplit{background:none}
</style>
