<!DOCTYPE html>
	<head>
		<title>Ki Points</title>
		<link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("kiPoints",'nationalMIS');?>" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0-beta.1/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />

	</head>
	<body>
		<div class="header">
			<div class="headerText">Ki Points</div>
			<div class="headerSubtext">Manage those pesky keys without any hassles</div>
		</div>
		<div class="actionButtonsOpen" >
			<!-- <div class="buttonHolder" style="width:70% !important">
				<button class="addButton cssButton" onclick="resetPage();addButtonClick();"><i class="fa fa-plus"></i> Add New Key</button>
				<span class="explanationText">
					Use this option to add new keys into the tracking_pagekey table.
				</span>
			</div> -->
			<div class="buttonHolder" style="width:100% !important">
				<a class="backButton cssButton" href="<?php SHIKSHA_HOME?>/trackingMIS/Dashboard/overview" > Back To MIS</a>
			</div>
			<!--<div class="buttonHolder">
				<button class="editButton cssButton" onclick="resetPage();editButtonClick();"><i class="fa fa-pencil-square-o"></i> Edit Existing Key</button>
				<span class="explanationText">
					Use this option to edit an existing key in the tracking_pagekey table.
				</span>
			</div>
			<div class="buttonHolder">
				<button class="deleteButton cssButton" onclick="resetPage();deleteButtonClick();"><i class="fa fa-times"></i> Delete Existing Key</button>
				<span class="explanationText">
					Use this option to delete an existing key in the tracking_pagekey table.
				</span>
			</div>-->
		</div>
		<div id="formContainer" class="formBlock" style="display:none;">
			<div id="editDeleteForm" style="display:none;">
				Please enter the Key Id to <span id="addEditOperation"></span> : <input type="text" id="editDeleteKeyId" class="inputClass"/>
				<br/>
				<div id="editDeleteFormButtons" class="buttonHolder buffer">
					<button class="cancelButton cssButton" onclick="resetPage();">Cancel <i class="fa fa-times"></i></button>
					<button class="submitButton cssButton" onclick="editDeleteNext();">Next <i class="fa fa-arrow-circle-right"></i></button>
				</div>
			</div>
			<div id="addEditForm" style="display:none;" class="addEditFormFields">
				<div>
					<span>keyName</span>
					<div><input type="text" id="keyName" class="inputClass"></div>
				</div>
				<div>
					<span>page</span>
					<div><input type="text" id="page" class="inputClass"></div>
				</div>
				<div>
					<span>widget</span>
					<div><input type="text" id="widget" class="inputClass"></div>
				</div>
				<div>
					<span>conversionType</span>
					<div><input type="text" id="conversionType" class="inputClass"></div>
				</div>
				<div>
					<span>site</span>
					<div>
						<select id="site" class="dropdownInputClass">
							<option value="">Select</option>
							<option value="Study Abroad">Study Abroad</option>
							<option value="Domestic">Domestic</option>
							<option value="Domestic">Test Prep</option>
							<!-- <?php foreach ($site as $key => $value) { ?>
								<option value="<?php echo $value;?>"><?php echo $value;?></option>			
							<?php	} ?> -->
						</select>
						<!-- <input type="text" id="site" class="inputClass"> selected="selected" -->
					</div>
				</div>
				<div>
					<span>siteSource</span>
					<div>
						<select id="siteSource" class="dropdownInputClass">
							<option value="">Select</option>
							<option value="Desktop">Desktop</option>
							<option value="Mobile">Mobile</option>
							<option value="Mobile App">Mobile App</option>
							<!-- <?php foreach ($siteSource as $key => $value) { ?>
								<option value="<?php echo $value;?>"><?php echo $value;?></option>			
							<?php	} ?> -->
						</select>
						<!-- <input type="text" id="siteSource" class="inputClass"> -->
					</div>
				</div>
				<div class="contract">
					<span class="contractText">
						I hereby declare upon this date of <?=$date?> that the entries provided above are accurate to the best of my knowledge and will not cause harm to a fellow programmer's code.
					</span>
					<span class="contractSignature">
						<p>Signed,</p>
						<p><i class="fa fa-times"></i><input id="userName" type="text" class="signatureInput" placeholder="Full Name" /><i class="fa fa-times"></i></p>
					</span>
				</div>
				<div class="addEditFormButtons">
					<div class="buttonHolder">
						<button class="cancelButton cssButton" onclick="resetPage();">Cancel <i class="fa fa-times"></i></button>
						<button class="submitButton cssButton" onclick="addEditSubmit();">Next <i class="fa fa-arrow-circle-right"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div id="TableData" class="datatableContainer" style="">
			<span style="color:#9f6000;font-size:20px;font-weight:bold;">Current State of tracking_pagekey:</span><br/><br/>
			<table id="stuff" class="display cell-border hover order-column  jambo_table">
				<thead>
					<tr>
						<th>id</th>
						<th>keyName</th>
						<th>page</th>
						<th>widget</th>
						<th>conversionType</th>
						<th>site</th>
						<th>siteSource</th>
						<th>pageGroup</th>
						<th>siteSourceType</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($trackingIdsArray as $trackingId => $value){ ?>
						<tr>
							<td><?=$value['key']?></td>
							<td><?=$value['keyName']?></td>
							<td><?=$value['page']?></td>
							<td><?=$value['widget']?></td>
							<td><?=$value['conversionType']?></td>
							<td><?=$value['site']?></td>
							<td><?=$value['siteSource']?></td>
							<td><?=$value['pageGroup']?></td>
							<td><?=$value['siteSourceType']?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div id="loader" class="loader_overlay">
			<img src="//<?php echo JSURL; ?>/public/images/trackingMIS/kiPointLoader.gif"/>
		</div>
		<div id="dialogbox" title="Hi" style="display:none;">
			<p id="dialogText">Bye</p>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0-beta.1/jquery-ui.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
		<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("kiPoints","nationalMIS")?>"> </script>
		<script>
			$(document).ready(function(){
				
				tableData = JSON.parse('<?php echo json_encode($trackingIdsArray); ?>');
				keyIds = JSON.parse('<?php echo json_encode($id); ?>');
				keyIds = Object.keys(keyIds).map(function (key) {return keyIds[key].toString();});
				keyNames = JSON.parse('<?php echo json_encode($keyName); ?>');
				keyNames = Object.keys(keyNames).map(function (key) {return keyNames[key].toString();});
				pages = JSON.parse('<?php echo json_encode($page); ?>');
				pages = Object.keys(pages).map(function (key) {return pages[key].toString();});
				widgets = JSON.parse('<?php echo json_encode($widget); ?>');
				widgets = Object.keys(widgets).map(function (key) {return widgets[key].toString();});
				conversionTypes = JSON.parse('<?php echo json_encode($conversionType); ?>');
				sites = JSON.parse('<?php echo json_encode($site); ?>');
				sites = Object.keys(sites).map(function (key) {return sites[key].toString();});
				siteSources = JSON.parse('<?php echo json_encode($siteSource); ?>');
				siteSources = Object.keys(siteSources).map(function (key) {return siteSources[key].toString();});
				initializePage();
			});
		</script>
	</body>
</html>