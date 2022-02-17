<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer'),
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
			'isExamResponseAccess' => true
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
							<td >Exam</td>
							<td >Course Group(s)</td>
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
							<td ><?php echo $subscriptionDetail['clientId']?></td>
							<td ><?php echo $subscriptionDetail['clientName']?></td>
							<td ><?php echo (empty($subscriptionDetail['accountManagerName']) ?"--":$subscriptionDetail['accountManagerName']);?></td>
							<td ><?php echo $subscriptionDetail['examName']?></td>
							<td title="<?php echo $subscriptionDetail['groupNamesToolTip']?>"><?php echo $subscriptionDetail['groupNames']?></td>
							<td title="<?php echo $subscriptionDetail['userLocationsToolTip']?>" class="userLocations"><?php echo $subscriptionDetail['userLocations']?></td>
							<td ><?php echo $subscriptionDetail['startDate']?></td>
							<td ><?php echo $subscriptionDetail['endDate']?></td>
							<td ><?php echo $subscriptionDetail['quantityExpected']?></td>
							<td ><?php echo $subscriptionDetail['quantityDelivered']?></td>
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

<style type="text/css">
	
	.subscriptionDetail-table tr td {
	    vertical-align: top;
	    border-bottom: 1px solid #111;
	    padding-right: 0px;
	    word-wrap: break-word;
	    border-right: 1px solid #111;
	    padding: 6px;
	    text-align: center;
	}

	.subscriptionDetail-table{
		width: 100%;
	    table-layout: fixed;
	    border: 1px solid #111;
    	border-collapse: collapse;
	}

	.btn-submit7.w9{
		width: auto;
	}

	.subscriptionDetail-table thead{
	  background: #e6e5e5;
	  font-size: 14px;
	}

	.subscriptionDetail-table tbody{
		font-size: 12px;
	}

	.subscriptionDetail-table tbody tr:nth-child(odd){
		background: #f4f4f4;
	}

	.disabled{
		pointer-events : none;
	}
</style>

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
                url : '/enterprise/examResponseAccess/deleteSubscription',
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


