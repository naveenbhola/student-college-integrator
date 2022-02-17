<title>Campaign Details</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer','naukriLearningCMSPanel'),
        'js'    =>  array('common','enterprise'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
$this->load->view('common/calendardiv');
?>

<div class="mar_full_10p">

	<?php
		$manualLDBAccessHeader = array(
			'isNaukriLearningLeadsAccess'  => true,
			'isActiveSubscription' => true
		);
		if($type == 'active'){
			$manualLDBAccessHeader['isActiveSubscription'] = true;
		}else{
			$manualLDBAccessHeader['isExpiredSubscription'] = true;
		}
		$this->load->view('examResponseAccess/manualLDBAccessHeader',$manualLDBAccessHeader);
	?>

	<div style="float:left;width:100%">
		<div style="padding-bottom:15px;">
			<?php if(count($subscriptionDetails) <=0){ ?>
				<p style="font-size: 16px;text-align: center"> <b>No Data Available</b></p><br>
			<?php }else{ ?>
				<?php if($type == 'active'){?>
					<p style="font-size: 16px"> <b>Exam Page Responses Access is currently given to:</b></p><br>
			<?php }else{?>
					<p style="font-size: 16px"> <b>Exam Page Responses Access is inactive / expired for:</b></p><br>
			<?php }?>
				<table class="subscriptionDetail-table">
					<thead>
						<tr>
							<td >Client Id</td>
							<td >Client Name</td>
							<td >Account Manager Name</td>
							<td >Location(s)</td>
							<td >Start Date</td>
							<td >End Date</td>
							<td >Total Responses</td>
							<td >Responses Sent</td>
							<?php if($type == 'active'){?>
								<td >Delete</td>
							<?php }?>
						</tr>
					</thead>
					<tbody >
					<?php
					foreach ($subscriptionDetails as $key => $subscriptionDetail) {?>
						<tr>
							<td ><?php echo $subscriptionDetail['client_id']?></td>
							<td ><?php echo $subscriptionDetail['client_name']?></td>
							<td ><?php echo (empty($subscriptionDetail['account_manager_name']) ?"--":$subscriptionDetail['account_manager_name']);?></td>
							<td title="<?php echo $subscriptionDetail['userLocationsToolTip']?>" class="userLocations"><?php echo $subscriptionDetail['userLocations']?></td>
							<td ><?php echo $subscriptionDetail['start_date']?></td>
							<td ><?php echo $subscriptionDetail['end_date']?></td>
							<td ><?php echo $subscriptionDetail['quantity_expected']?></td>
							<td ><?php echo $subscriptionDetail['quantity_delivered']?></td>
							<?php if($type == 'active'){?>
								<td ><button class="subscription btn-submit7 w9" type="button" subscriptionId="<?php echo $subscriptionDetail['id']?>" >
	                        		<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete</p></div>
									</button>
								</td>
							<?php }?>
						</tr>
					<?php }
					?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
<?php 
$this->load->view('enterprise/footer'); ?>

<script type="text/javascript"> 
	window.onload=function(){
		$j(".subscription").bind("click",function(e){
			deleteSubscription(this);
		});
	};

	function deleteSubscription(obj){
		var flag = confirm("are you sure, you want to delete");
		if(flag == false){
			return;
		}
		$j(obj).addClass("disabled");
		showEnterpriseLoader();
		$j.ajax({
                type: 'POST',
                url : '/enterprise/NaukriLearningLeadsAccess/deleteSubscription',
                data : {
                  'subscriptionId' : $j(obj).attr("subscriptionId")
                },
                success : function(response) {
                    if(response == ""){
                    	alert("An error occured while deleting subscription. Please try again.");
                    }else{
                    	response = JSON.parse(response);
                    	if(response == "FAIL"){
                    		alert("An error occured while deleting subscription. Please try again.");
                    	}else{
                    		$j(obj).parent().html("Deleted");
                    		alert("Subscription successfully deleted.");
                    	}
                    }

                    hideEnterpriseLoader();
                    $j(obj).removeClass("disabled");
                }
        });
	}
</script>


