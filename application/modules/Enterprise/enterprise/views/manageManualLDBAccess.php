<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','smart'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<style type="text/css">
	.date-range{font-size:12px; padding-left:2px; float:left}
	.date-range label{width:auto !important; text-align:left !important; font-size:12px !important; }
	.date-range input{width:80px; font:normal 11px Arial, Helvetica, sans-serif; color:#84888b}
	
	.icon-cal, .arrow-d, .drop-icon, .info-icon, .prev-item, .next-item, .prev-item-active, .next-item-active, .popup-layer2 .pointer, .popup-layer2 .pointer2{background:url(/public/images/smart-sprite.png) 0 0 no-repeat; position:relative; display:inline-block;}
	.info-icon{background-position:-50px 0; width:16px; height:16px; cursor:pointer}
	.icon-cal{background-position:0 0 ; width:15px; height:15px; vertical-align:middle; cursor:pointer}
</style>

<?php
	$this->load->view('common/calendardiv');
?>

<div class="mar_full_10p">


	<?php
		$manualLDBAccessHeader = array(
								'isManualLDBAccess' => true,
								'isManualLDBAccessByStream' => false
								);
		$this->load->view('examResponseAccess/manualLDBAccessHeader',$manualLDBAccessHeader);
	?>
	<div style="float:left;width:100%">
	    <form autocomplete="off" action="saveManualLDBAccessData" id="ldbAccessForm" method="POST">        
			<div id="lms-port-content">
				<div class="form-section">					

					<div style="margin:4px 0 4px 45px;">
						<label>Enter Client Id : </label>
						<input type="text" id="client_id" name="client_id" style="width:150px;margin-left:22px;" value="" placeholder="Enter Client Id"/>
					</div>
					
					<?php if($_GET['error'] == 1) { ?>
					<div style="float:left;color:red;margin-left:155px">Client Id does not exist</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<?php } else if($_GET['error'] == 2) { ?>
					<div style="float:left;color:red;margin-left:155px">Client Id already exist</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<?php } ?>

					<div style="margin:4px 0 4px 45px;">
						<label style="width:40px;">Enter End Date : </label>
						<input readonly type="text" id="timerangeTill" name="timerangeTill" style="margin-left:16px;width: 150px;" placeholder="YYYY-MM-DD"/>
						<img id="timerangeTillImg" onclick="timerange('Till');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
					</div>

					<div style="margin-left:45px; margin-top:15px;">
						<a href="javascript:void(0);" onclick="validateLDBAccess();" class="orange-button" style=" color: #ffffff;">Grant Access</a>
					</div>
				</div>				

				<div class="clearFix"></div>
			</div>
		</form>
	</div>
</div>


<div style="float:left;width:100%">
	<div class="select-courses-head"><strong style="text-align: left; margin-left:18px;width:100%;">Manual LDB Access is currently granted to:</strong></div>
	<table border="0" style="width: 510px; margin: 10px 0 20px 20px; border: 1px solid #ccc; font-size:12px;" cellspacing="0" cellpadding="0">
		<tr>
		
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Client Id</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Client Name</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>End Date</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Sales User Id</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Sales User Name</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px"><strong>Action</strong></td>
		<tr>
    <?php
    foreach($ldbAccessData as $details) { ?>
        <tr style="border: 1px solid #ccc;">
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['client_id'];?></label></td>
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['firstname'].' '.$details['lastname'];?></label></td>
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['ended_on'];?></label></td>            
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['salesUserId'];?></label></td>            
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['salesUserName'];?></label></td>            
        <td style="border-bottom: 1px solid #ccc; padding:4px"><label><a href="<?=SHIKSHA_HOME?>/enterprise/shikshaDB/deleteManualLDBAccess/<?=$details['id']?>">Remove</a></label></td> 
        </tr>
    <?php } ?>
    </table>
</div>

<script type="text/javascript">

	function validateLDBAccess() {
		if (validateLDBFields() && validatetimeRange()) {
			$("ldbAccessForm").submit();
			return;
		}
	}

	function validateLDBFields() {
		var clientid = $('client_id').value;

		if (!clientid) {
			alert("Please enter Client Id.");
			return false;
		}
		if (isNaN(clientid)) {
			alert("Please enter Client Id in numbers only.");
			return false;			
		}
		var regex=/^[0-9]+$/;
		if (!clientid.match(regex)){
			alert("Please enter Client Id in numbers only.");
			return false;	
			
		}
		if ((clientid.indexOf(".")) > -1) {
			alert("Decimal numbers in Client Id not allowed.");
			return false;		
		}
		return true;
	}

	function timerange(timeFlag) {
		var calMain = new CalendarPopup('calendardiv');
		calMain.select($('timerange'+timeFlag),'timerange'+timeFlag+'Img','yyyy-mm-dd');
		return false;
    }
	
	Date.prototype.yyyymmdd = function() {         
         
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
    };

    function getCurrentDate () {
    	var currentdate = new Date();
    	var yyyy = currentdate.getFullYear().toString();                                    
        var mm = (currentdate.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = currentdate.getDate().toString();             
            
        return (mm[1]?mm:"0"+mm[0]) + '/' +  (dd[1]?dd:"0"+dd[0]) + '/' + yyyy;
    }
	
	function validatetimeRange() {
        var endDate = $('timerangeTill').value;
        var todate = endDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
        var currentDate = getCurrentDate();

		if (todate !== '') {
            if (Date.parse(todate) < Date.parse(currentDate)) {
				alert("Please select End Date in future.");
				return false;
			}
        }
        else if (todate == '') {
            alert("Please select End Date.");
			return false;
        }
		
		return true;
    }	


</script>
<?php $this->load->view('enterprise/footer'); ?>