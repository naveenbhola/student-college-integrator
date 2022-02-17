<style>
	.sales-client-section{font-size:12px; color: #333; font-family: verdana; width;95%; float: left; margin-left:15px;}
	.sales-client-section h2{font-weight: normal; font-size: 20px;}
	.sales-client-section ul{margin:0 0 0 15px; padding: 0; font-size: 13px;}
	.sales-client-section ul li{list-style: disc; margin-bottom: 5px;}
	ul.sales-manager-info-list{margin-left:0 !important;}
	ul.sales-manager-info-list li{list-style: none !important; }
	ul.sales-manager-info-list li label{width:360px; display: inline-block; font-weight: bold;}
	.map-now-btn{color: #fff !important; background: #fc8104; padding: 5px; font-size: 12px; text-decoration: none !important; font-weight: bold;}
	.Or-box{font-size:20px; margin:30px 0; display: block;}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script type="text/javascript">
	$j = jQuery.noConflict();
</script>

<script type="text/javascript">

	function doSubmitMapping(formobj) {
		
		var formData = $j(formobj).serializeArray();
		var formStringData = $j(formobj).serialize();
		var formDataObj = {};

		$j.each(formData, function() {
			if (formDataObj[this.name] !== undefined) {
				if (!formDataObj[this.name].push) {
					formDataObj[this.name] = [formDataObj[this.name]];
				}
				formDataObj[this.name].push(this.value || '');
			} else {
				formDataObj[this.name] = this.value || '';
			}
		});

		if(!validateEmailId(formDataObj['SalesEmail'])) {
			alert('Please enter a Valid Email ID');
			$j("#SalesEmail").focus();
			return false;
		}

		if(!testPositiveInt(formDataObj['ClientId'])) {
			alert("Please enter a Valid Client ID.");
			$j("#ClientId").focus();
			return false;
		}

		var url = '/sums/Nav_Integreation/mapSalesToClient';

		new jQuery.ajax( url,
		{
			type:'post',
		    success:function(response){
		    	alert(response);
		    	$j("#SalesEmail").val('');
		    	$j("#ClientId").val('');
			},
			error:function(){
				alert("Something went wrong...");
			},
			evalScripts:true,
			data:formStringData
		});

		return true;
	}

	function validateEmailId(emailId) {
		if(emailId) {
			var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
			if(!filter.test(emailId)) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

	function testPositiveInt(number) {
		var intRegex = /^\d+$/;
		if(number > 0 && intRegex.test(number)) {
			return true;
		} else {
			return false;
		}
	}

	function doUploadCSV() {
		
		var formData = new FormData($j("#form_mapping_csv")[0]);
		var url = '/sums/Nav_Integreation/mapSalesToClientUsingCSV';
		
		new jQuery.ajax( url, 
		{
			type:'post',
			async: false,
		    success:function(response){
		    	alert(response);
		    	$j("#mappingCSV").val('');
			},
			error:function(){
				alert("Something went wrong...");
			},
			cache: false,
	        contentType: false,
	        processData: false,
			data:formData
		});

		return true;
	}

</script>

<?php $this->load->view('support/header'); ?>

<div id='left_panel'>
    <form method="post" onsubmit="return submitFindUserById();">
    	Search by user ID (CID)
    	<input type="text" name="suserId" id="suserId" class='inputbox'  />
    	<div class='input_error' id='error_suserId'>Please enter user id</div>
    	<input type="submit" value="Submit" class='inputbutton' />
    </form>
    <br />
    <form method="post" action='/support/Support/findUserByEmail' onsubmit="return submitFindUserByEmail();">
    	Search by email
    	<input type="text" name="semail" id="semail" class='inputbox' />
    	<div class='input_error' id='error_semail'>Please enter email id</div>
    	<input type="submit" value="Submit" class='inputbutton' />
    </form>
    <input type="hidden" id="logged_in_userid" name="logged_in_userid" value="<?php echo $loggedInUserInfo[0]['userid']; ?>" />
    <input type="hidden" id="logged_in_username" name="logged_in_username" value="<?php echo $loggedInUserInfo[0]['displayname']; ?>" />
    <div>
        <a href="#" id="mappingLink" onclick="doGetMappingInterface(); return false;" style="text-decoration:none;">
            Client Sales Mapping Interface
        </a>
    </div>
</div>

	<div id="main_panel" class="sales-client-section">
		<h2 style="font-size:16px;">
			SALES CLIENT MAPPING
		</h2>

		<form id="form_mapping" name="form_mapping"> 
			<div>
				<p><strong style="font-size:14px;">Notes:</strong></p>
				<ul>
					<li>
						The interface below is used for correcting the mapping between the Sales Account Manager and the Client.
					</li>
					<li>
						Use the interface wisely as the changes done here are irreversible. 
					</li>
				</ul>
				<ul class="sales-manager-info-list">
					<li>
						<label>Enter Sales Account Manager's Shiksha Email ID</label>
						<input id="SalesEmail" name="SalesEmail" type="text" />
					</li>

					<li>
						<label>Enter Client ID</label>
						<input id="ClientId" name="ClientId" type="text" />
					</li>
					<li>
						<a href="#" class="map-now-btn" onclick="doSubmitMapping($j('#form_mapping')); return false;">
							Map Now
						</a>
					</li>
				</ul>
			</div>
		</form>
		<div class="Or-box">
			OR
		</div>
		<form method="post" enctype="multipart/form-data" action="/sums/Nav_Integreation/mapSalesToClientUsingCSV" id="form_mapping_csv" name="form_mapping_csv">
			<div>
				<p><strong style="font-size:14px;">Map multiple people in one go : </strong></p>
				<p>
					<input id="mappingCSV" type="file" name="mappingCSV"/>
					<a href="#" class="map-now-btn" onclick="doUploadCSV();">Upload Mapping Data</a>
				</p>
				
				<p><strong style="font-size:14px;">Notes:</strong></p>
				<ul>
					<li>
						Only following formats of files will be accepted : '.xls', '.xlsx', '.csv'.
					</li>
					<li>
						1st column should be 'Sales Email' and 2nd column should be 'Client ID'.
					</li>
				</ul>
			</div>
		</form>
	</div>