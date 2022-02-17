<?php
	$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
	$criteriaArray = array(
		'category' => $categoryIdForBanner,
		'country' => '',
		'city' => '',
		'keyword'=>''
	);
    $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
    $headerComponents = array(
		'css'	=>	array('online-styles','header','raised_all','common'),
		'js' 	=>	array('header','common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','onlineForms'),
		'title'	=>	'Online College Admission form - MBA Application Form - Shiksha.com',
		'tabName' =>	'Discussion',
		'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
		'metaDescription'	=>'Apply to top MBA colleges of your choice online at Shiksha.com. Fill application form once & apply to multiple PGDM/MBA colleges. Get updates on your admission process status',	
		'metaKeywords'	=>'college admission, online MBA application, Online MBA application form, online application, apply online, mba admission, online admission form, list of colleges, engineering admission, college admission form, online admission process, Online college admission form, online application forms, Online college admission, list of institutes, online admission forms, list of online colleges, application form online',
		'product'	=>'online',
		'bannerProperties' => $bannerProperties,
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
		'callShiksha'=>1,
		'notShowSearch' => true,
			'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
			'showBottomMargin' => false,
		'showApplicationFormHomepage' => true
   );

   $this->load->view('common/header', $headerComponents);

?>
<script>
	var isUserLoggedInSystem = '<?php echo $userId;?>';
	var urlToRedirect = '';
	var is_processed1 = true;
	var is_processed2 = true;
	var is_processed = true;
</script>


	<div id="appsFormWrapper">
		<div class="payment-sys-cont">
			<h3 class="profileMsg">Search Payment Status</h3>
			<br/><br/>
			<h4>Search by:</h4>
			<div class="clearFix"></div>
			<form name="paymentform" method="post" action="/Online/PaymentController/searchpaymentdetails" >
				<input type="hidden" autocomplete="off" id="formStartFrom" value="<?php echo $startFrom;?>"/>
				<input type="hidden" autocomplete="off" id="formCountOffset" value="<?php echo $countOffset;?>"/>
				<input type="hidden" autocomplete="off" id="abuseFilter" value=""/>
				<input type="hidden" autocomplete="off" id="methodName" value="nextpage"/>
				<input type="hidden" autocomplete="off" id="moduleNameSel" value="<?php echo $moduleName;?>"/>
				<ul>
					<li>
						<div class="col-1" style="padding-top:17px;">
							<label>Order ID:</label>
							<div class="flLt">
								<input id="first" name="orderid" type="text" value = "<?php if (isset($orderid))  echo $orderid; ?>" style="width:180px" />
							</div>
						</div>
						<?php $this->load->view('common/calendardiv'); ?>
						<div class="col-2">
							<label style="padding-top:20px;">Receipt Date:</label>
							<div class="flLt">
								<p>From</p>
								<span class="calenderBox">
									  <input type="text" name="startDate" value="<?php if(isset($startDate) ) echo $startDate; else echo "dd/mm/yyyy" ?>" class="calenderFields"  id="from_date_main_first"  readonly  />
									  <a href="#" class="pickDate" title="Calendar" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_first'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a>
								</span>
								</div>
								<div class="flLt" style="margin-left:12px">
								<p>To</p>
								<span class="calenderBox">
									   <input type="text" name="endDate" value="<?php if(isset($endDate) ) echo $endDate; else echo "dd/mm/yyyy" ?>" class="calenderFields" id="from_date_main_second"  readonly  />
									   <a href="#" class="pickDate" title="Calendar"  id="from_date_main_second"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_second'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a>
								</span>
							</div>
						</div>
						
						<div class="col-3" style="padding-top:17px;">
							<label>Institute Name:</label>
							<div class="flLt">
									<input id="last" name="instituteName" "width:200px" type="text"  value = "<?php if (isset($instituteName)) { echo $instituteName; }?>" 
							</div>
						</div>
					</li>
					<li>
						<div class="col-1">
							<label>Transaction Mode:</label>
							<div class="flLt">
								<select name="mode" style="width:134px" value = "<?php if (isset($mode)) { echo $mode; }?>">
									<option value="Online" <?php if(isset($mode) && $mode=="Online") { ?> selected="selected" <?php } ?>>Online</option>
								</select>
							</div>
						</div>
						
						<div class="col-2">
							<label>Payment Gateway:</label>
							<div class="flLt">
							<select name="gateway" style="width:193px" value = "<?php if (isset($gateway)) { echo $gateway; }?>">
								
								<option value="CCavenue" <?php if(isset($gateway) && $gateway=="CCavenue") { ?> selected="selected" <?php } ?>>CCavenue</option>
							</select>
							</div>
						</div>
						
						<div class="col-3">
							<label>Payment Status:</label>
							<div class="flLt">
							 <select name="status" style="width:100px">
								 <option value="">Select</option>
								 <option value="Started" <?php if(isset($status) && $status=="Started") { ?> selected="selected" <?php } ?>>Started</option>
								 <option value="Failed"  <?php if(isset($status) && $status=="Failed") { ?> selected="selected" <?php } ?>>Failed</option>
								 <option value="Success" <?php if(isset($status) && $status=="Success") { ?> selected="selected" <?php } ?>>Success</option>
								 <option value="Cancelled" <?php if(isset($status) && $status=="Cancelled") { ?> selected="selected" <?php } ?>>Cancelled</option>
                                                                 <option value="Refunded" <?php if(isset($status) && $status=="Refunded") { ?> selected="selected" <?php } ?>>Refunded</option>
                                                                 <option value="Chargeback" <?php if(isset($status) && $status=="Chargeback") { ?> selected="selected" <?php } ?>>Chargeback</option>
							 </select>
							</div>
						</div>
					</li>
					
					<li>
						<div class="col-1" style="width:322px">
							<label>Candidate Email:</label>
							<div class="flLt">
								 <input id="candidateName" style="width:200px" type="textbox" size="10" name="candidateName"  value = "<?php if (isset($candidateName)) { echo $candidateName; }?>"/>
							</div>
						</div>
						<div class="col-2">
							<label>Order By:</label>
							<div class="flLt">
							 <select name="orderby" style="width:134px">
							    <option value="">Select</option>
								<option value="paymentId" <?php if(isset($orderby) && $orderby=="paymentId") { ?> selected="selected" <?php } ?>>Payment Id</option>
								<option value="receiptDate" <?php if(isset($orderby) && $orderby=="date") { ?> selected="selected" <?php } ?>>Receipt Date</option>
								<option value="orderId" <?php if(isset($orderby) && $orderby=="orderId") { ?> selected="selected" <?php } ?>>Transaction Id</option>
							 </select>
							</div>
						</div>
					</li>
					<li><input type="submit" name="search_form" class="orangeButton" value="Search" title="search"/></li>
				</ul>
			</form>
                <div class="clearFix spacer15"></div>
                  <?php $totalResults= $resultSet['totalResults'];?>
                <div>
                	<h4>
                        <?php if($totalResults<=0 && $message==''){echo " No result found";}else if($totalResults<=0 && $message!=''){echo $message;} elseif($totalResults==1){echo " $totalResults result found";} else {echo  " $totalResults results found";}?>
                    </h4>
                    <?php if ($totalResults>0){ ?>
                    <div class="pagingID" id="paginataionPlace1" align="right" style="line-height:23px;width:350px; float:right">
                        <?php
                            $url = SHIKSHA_HOME."/Online/PaymentController/searchpaymentdetails/@start@/@count@?orderid=".$_REQUEST['orderid'].
								"&startDate=".$_REQUEST['startDate']."&endDate=".$_REQUEST['endDate']."&instituteName=".
								$_REQUEST['instituteName']."&mode=".$_REQUEST['mode']."&gateway=".$_REQUEST['gateway'].
								"&status=".$_REQUEST['status']."&candidateName=".$_REQUEST['candidateName'].
								"&orderby=".$_REQUEST['orderby']."&submit=".$_REQUEST['search_form'];
                            echo doPagination($totalResults,$url,$start,$count,5);
						?>
                    </div>
                    <div class="spacer10 clearFix"></div>
                	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="payment-table">
                    	<tr>
                            <th width="15%">Payment Id</th>
                            <th width="15%">Transaction Id</th>
                            <th width="15%">Transaction Date</th>
			    <th width="20%">Receipt Date</th>
                            <th width="15%">Payment Status</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>                            
                        </tr>
					<div id="paymentInformationContainer">
						<?php echo $this->load->view('Online/PaymentTool/userPaymentRecords'); ?>
					</div>
				</table>
				<div class="spacer10 clearFix"></div>
				<div class="pagingID" id="paginataionPlace2" align="right" style="line-height:23px;width:350px; float:right">
					<?php 
						echo doPagination($totalResults,$url,$start,$count,5);
					?>
				</div>
				<div class="spacer10 clearFix"></div>                     
				<?php
					if($_REQUEST['startDate']) {
						$startdate_array = explode("/", $_REQUEST['startDate']);
						$_REQUEST['startDate'] = $startdate_array[2]."-".$startdate_array[1]."-".$startdate_array[0];
					}
					if($_REQUEST['endDate']) {
						$enddate_array = explode("/", $_REQUEST['endDate']);
						$_REQUEST['endDate'] = $enddate_array[2]."-".$enddate_array[1]."-".$enddate_array[0];
					}
					$params = $_REQUEST['orderid'].':'.$_REQUEST['startDate'].':'.$_REQUEST['endDate'].':'.$_REQUEST['instituteName'].':'.$_REQUEST['mode'].':'.$_REQUEST['gateway'].':'.$_REQUEST['status'].':'.$_REQUEST['candidateName'].':'.$_REQUEST['orderby'].':'.$_REQUEST['search_form']; 
					
				?>
				<div class="buttonBlock">
					<input type="button" class="orangeButton" value="Update" onclick="javascript:updatePaymentStatus(null);return false;"/>&nbsp;&nbsp;
					<a style="text-decoration:none" href="/Online/PaymentController/downloadPaymentLogInformation/<?php echo $params.":".$totalResults;?>"; return false;" target="_blank" name="download_form" class>
						<input type="button" class="orangeButton" value="Download" />
					</a>            
				</div>
			</div>
			<div class="clearFix"></div>
		</div>
	</div>
    <?php
		}
	    $bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
        $this->load->view('common/footer',$bannerProperties1);
    ?>
<script>
	var transactionObject = new Object;
	var paymentIdGroupsObject = new Object;
	
	var confirmMessage = new Object;
	var confirmMessageBegin = 'You have modified the following Orders:\r\n\r\n';
	var confirmMessageEnd = "\r\nDo you Confirm?";
	
	function setStatusOfOrder(element, orderId, paymentId, date, receiptDate) {
		receiptDate = $(paymentId+'--'+date).value;
		status = $(paymentId+'--'+date+'_status').value;

		if(receiptDate==''){
			alert("Receipt Date cannot be empty.");
			return false;
		}
		if(status=="null"){
			return false;
		}
		var dropdowns = document.getElementsByName(paymentId);
		var count = 0;
		key  = makeKeyFromData(orderId, paymentId, date);

		for (var i in dropdowns) {
			if (dropdowns[i].value == "Success") {			
				count++;
			}
			if (count >=2) {
				alert("Two or more transactions can't be Successful for the same Payment ID "+paymentId+" Change the success status to some other status for the other transaction of this Id. Only then you can change the status to success for this transaction.");
				element.selectedIndex = 0;
				if (typeof transactionObject[key] != undefined) {
					delete transactionObject[key];
				}
				if (typeof paymentIdGroupsObject[paymentId] != undefined) {
					for (var i in paymentIdGroupsObject[paymentId]) {
						if (paymentIdGroupsObject[paymentId][i] == key) {
							delete paymentIdGroupsObject[paymentId][i];
						}
					}
				}
				return false;
			}
		}
		
		var data_string = '{"orderId":"' + orderId +'","status":"' + status + '","paymentId":"' + paymentId + '","date":"' + date + '","receiptDate":"' + receiptDate +'"}';
		transactionObject[key] = data_string;
		confirmMessage[key] = "OrderId:"+orderId+" on Date:"+date+" has been changed as "+status+" with Receipt Date as "+receiptDate+".\r\n\n";
		if (typeof paymentIdGroupsObject[paymentId] != 'undefined') {
					var flag=0;
					for(var i in paymentIdGroupsObject[paymentId]){
						if (paymentIdGroupsObject[paymentId][i] == key) {
								flag=1;
						}
					}
					if(!flag){
						  paymentIdGroupsObject[paymentId].push(key);		
					}
		}
					 else {
			paymentIdGroupsObject[paymentId] = new Array;
			paymentIdGroupsObject[paymentId].push(key);
		}
		return true;
	}
	
	
	function makeKeyFromData(orderId, paymentId, date) {
	    orderId = orderId.replace(/-/g, '');
		paymentId = paymentId.replace(/-/g, '');
		date = date.replace(/\s/g, '');
		date = date.replace(/-/g, '');
		date = date.replace(/:/g, '');
		key = orderId+paymentId+date;
		return key;
	}
</script>
