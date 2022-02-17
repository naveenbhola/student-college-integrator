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
								'isManualLDBAccessByStream' => true
								);
		$this->load->view('examResponseAccess/manualLDBAccessHeader',$manualLDBAccessHeader);
	?>
	<div style="float:left;width:100%">
	    <form autocomplete="off" action="saveManualLDBAccessDataByStream" id="ldbAccessForm" method="POST">        
			<div id="lms-port-content" style="padding-bottom:15px;">
				<div class="form-section">					

					<div style="margin:4px 0 4px 45px;">
						<label>Enter Client ID: </label>
						<input type="text" id="client_id" name="client_id" style="width:150px;margin-left:22px;" value="" placeholder="Enter Client Id"/>
					</div>
					
					<?php if($_GET['error'] == 1) { ?>
					<div style="float:left;color:red;margin-left:155px">Client Id does not exist</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<?php } /*else if($_GET['error'] == 2) { ?>
					<div style="float:left;color:red;margin-left:155px">Client Id already exist</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<div class="lineSpace_10">&nbsp;</div>
    				<?php }*/ ?>

                    <?php if(count($stream_list)>0):?>
					<div style="margin:4px 0 4px 45px;">
						<label style="width:40px;">Select Stream(s) To Restrict: </label>
						 <ul style="position:relative;left:120px;" id="parent_ul">
							 <li>
								<div class="Customcheckbox nav-checkBx">
									<input onclick="selectAllStreams(this);" id="stream_parent" class="unmp cLevel sSS_E59jib" name="streams[]" value="-1" classholder="sSS_E59jib" match="spec_10_E59jib" norest="yes" type="checkbox">
									<label for="stream_parent">All</label>
								</div>
							</li> 
						 <?php $i = 0;foreach($stream_list as $key=>$value):?> 						
							<li>
								<div class="Customcheckbox nav-checkBx">
									<input id="stream_<?php echo $i;?>" class="unmp cLevel sSS_E59jib" name="streams[]" value="<?php echo $key;?>" classholder="sSS_E59jib" match="spec_10_E59jib" norest="yes" type="checkbox">
									<label for="stream_<?php echo $i;?>"><?php echo $value;?></label>
								</div>
							</li>
						 <?php $i++; endforeach;?>
						 </ul>
					</div>
                    <?php endif;?>
					<div style="margin-left:45px; margin-top:15px;">
						<a href="javascript:void(0);" onclick="validateLDBAccess();" class="orange-button" style=" color: #ffffff;">Restrict Now</a>
					</div>
				</div>				

				<div class="clearFix"></div>
			</div>
		</form>
	</div>
</div>

<?php if(is_array($ldbAccessData) && count($ldbAccessData)>0):?>
<div style="float:left;width:100%">
	<div class="select-courses-head"><strong style="text-align: left; margin-left:18px;width:100%;">LDB Access is restricted for:</strong></div>
	<table border="0" style="width: 510px; margin: 10px 0 20px 20px; border: 1px solid #ccc; font-size:12px;" cellspacing="0" cellpadding="0">
		<tr>		
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Client ID</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Client Name</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><strong>Stream(s) Blocked</strong></td>
			<td style="border-bottom: 1px solid #ccc; padding:4px"><strong>Action</strong></td>
		<tr>
    <?php
    foreach($ldbAccessData as $details): ?>
        <tr style="border: 1px solid #ccc;">
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['client_id'];?></label></td>
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['firstname'].' '.$details['lastname'];?></label></td>
        <td style="border-bottom: 1px solid #ccc; padding:4px;border-right: 1px solid #ccc;"><label><?php echo $details['stream_name'];?></label></td>                        
        <td style="border-bottom: 1px solid #ccc; padding:4px"><label><a href="javascript:void(0)" onclick="unblockStream('<?php echo '/enterprise/shikshaDB/deleteManualLDBAccessByStream/'.$details['id']?>')">Unblock</a></label></td> 
        </tr>
    <?php endforeach;?>
    </table>
</div>
<?php endif;?>

<script type="text/javascript">

    function selectAllStreams(div) {
	   var div_id = $j(div);
	   if(div_id.attr('checked') == 'checked') {		  
		   $j($j('#parent_ul input')).each(function( index ) {
				//console.log( index + ": " + $( this ).text() );
				$j(this).attr('checked','checked');
			});
	   } else  {
		   $j($j('#parent_ul input')).each(function( index ) {
				//console.log( index + ": " + $( this ).text() );
				$j(this).removeAttr('checked');
			});
	   }
	}
    function unblockStream(url_to_delete) {
		
		$j.ajax({url: url_to_delete, success: function(result){
			//alert(result);
			window.location.reload();
		}});
        
	}
	
	function validateLDBAccess() {
		if (validateLDBFields()) {
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
		
		var checked_stream = false;
		 $j($j('#parent_ul input')).each(function( index ) {
			if($j(this).attr('checked') == 'checked') {
				    checked_stream = true;
					return true;
			}
		});
		
		if(checked_stream == false) {
			    alert("Please select atleast one sream.");
				return false;
		}
		
		return true;
	}
</script>
<?php $this->load->view('enterprise/footer'); ?>
