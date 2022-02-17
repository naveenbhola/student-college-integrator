<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_title">
  		<h2>Advanced Filters :</h2>
		<div class="clearfix"></div>
	</div>
	<div>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Request Id Filter -->
			<div class="col-md-2 col-sm-2 col-xs-12">
				<input type="text" class="form-control" placeholder="Request Id" validationType='numeric' minlength="0" required="true" id='advancedFilter_requestId' caption='Request Id' maxLength="10"  onblur="formValidation.showErrorMessage($(this).attr('id'))">
				<div id="advancedFilter_requestId_error" class="errorMsg" style="display: none"></div>
			</div>

			<?php if($requestTypeForViewDetails == 'task'){ ?>
				<!-- Task Id Filter -->
				<div class="col-md-2 col-sm-2 col-xs-12">
					<input type="text" class="form-control" placeholder="Task Id" validationType='numeric' minlength="0" required="true" id='advancedFilter_taskId' caption='Task Id' maxLength="10"  onblur="formValidation.showErrorMessage($(this).attr('id'))">
					<div id="advancedFilter_taskId_error" class="errorMsg" style="display: none"></div>
				</div>
			<?php	} ?>

			<!-- Sales Order No. Filter -->
			<div class="col-md-2 col-sm-2 col-xs-12">
				<input type="text" class="form-control" placeholder="Sales Order No." validationType='str' required="true"  id='advancedFilter_salesOrderNo' caption='Sales Order No.' maxLength='20' minLength = '0' onblur="formValidation.showErrorMessage($(this).attr('id'))">			
				<div id="advancedFilter_salesOrderNo_error" class="errorMsg" style="display: none"></div>
			</div>

			<!-- Last Updated On Filter -->
			<div class="col-md-3 col-sm-3 col-xs-12">
	              <div class="dropdown col-md-3 col-sm-3 col-xs-12">
	                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="advancedFilter_lastUpdatedOn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:200px !important"  
	                   caption="Request Type" required="true" validationType="select">
	                  All Last Updated On
	                    <span class="caret"></span>
	                </button>                
	                <ul class="dropdown-menu" aria-labelledby="advancedFilter_lastUpdatedOn">
	                  <li data-dropdown="0">
	                    <a href="javascript:void(0)" title="Client">All Last Updated On</a>
	                  </li>
	                  <li data-dropdown="1">
	                    <a href="javascript:void(0)" title="Client">Last 1 Day</a>
	                  </li>
	                  <li data-dropdown="2">
	                    <a href="javascript:void(0)" title="Client">Last 2 Days</a>
	                  </li>
	                  <li data-dropdown="3">
	                    <a href="javascript:void(0)" title="Client">Last 3 Days</a>
	                  </li>
	                  <li data-dropdown="7">
	                    <a href="javascript:void(0)" title="Client">Last 7 Days</a>
	                  </li>
	                  <li data-dropdown="15">
	                    <a href="javascript:void(0)" title="Client">Last 15 Days</a>
	                  </li>
	                  <li data-dropdown="30">
	                    <a href="javascript:void(0)" title="Client">Last 30 Days</a>
	                  </li>
	                </ul>
	                <div id="advancedFilter_lastUpdatedOn_error" class="errorMsg" style="display: none"></div>
	              </div>
	              <input type="hidden" name="advancedFilter_lastUpdatedOn" value="all" id="hidden_advancedFilter_lastUpdatedOn"    />
			</div>	

			<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap-multiselect",'nationalMIS'); ?>" type="text/css">
	    	<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap-multiselect","nationalMIS"); ?>"></script>

	    	<!-- Task Category Filter -->
	        <div class="dropdown col-md-2 col-sm-3 col-xs-12">
	            <div class="dropdown">
	                <select id="advancedFilter_taskCategory" multiple="multiple" style="overflow:auto;height:300px">
	                    <option value="all" selected>All Task Category</option>
	                    <option value="Mailer" >Mailer</option>
	                    <option value="Listing" >Listing Design</option>
	                    <option value="Banner" >Banner Design</option>
	                    <option value="Shoshkele" >Shoshkele Design</option>
	                    <option value="Campaign Activation" >Campaign Activation</option>                            
	                </select>
	            </div>	            
			</div>

			<!-- Pending For Camapign Activation -->
			<?php if($requestTypeForViewDetails == 'request'){ ?>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="checkbox" style="padding:0px !important;margin:0px !important">
				    <label class="" style="padding:0px !important">
				      <div class="icheckbox_flat-green checked advancedFilter_pendingForCampaignActivation"  style="position: relative;">
				      	<input type="checkbox" class="flat "   style="position: absolute; opacity: 0;">
				      		<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
				      </div> 	
				      <span style="font-size: 18px;color: inherit;position: relative; top: 4px;" ><b class='requestTypeAddClass'> Pending For Campaign Activation</b></span>
				    </label>	    
			  	</div>
			</div>
			<?php } ?>

		</div>

		<div class="x_title">
			<div class="clearfix"></div>
		</div>

		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Date Range Filter Filter -->
			<div class="col-md-5 col-sm-6 col-xs-12">
				<div class="checkbox" style="padding:0px !important;margin:0px !important">
				    <label class="" style="padding:0px !important">
				      <div class="icheckbox_flat-green checked requestCheckBox advancedFilter_isDateRangeSelected"  style="position: relative;">
				      	<input type="checkbox" class="flat "   style="position: absolute; opacity: 0;">
				      		<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
				      </div> 	
				      <span style="font-size: 18px;color: inherit;position: relative; top: 4px;" ><b class='requestTypeAddClass'> Select Date Range (Date Created)</b></span>
				    </label>	    
			  	</div>

				<div> &nbsp</div>
				<div style="float:left; margin-top:0px;">
					<div style="float:left; margin-left:15px; padding-top:3px;">From Date: </div>
					<div style="float:left; margin-left:10px; padding-top:1px;">
						<input type="text" id="advancedFilter_fromDatePicker" readonly="readonly" value="" style="width:100px; cursor: text" class="hasDatepicker">
					</div>

					<div style="float:left; margin-left:30px; padding-top:3px;">To Date : </div>
					<div style="float:left; margin-left:10px; padding-top:1px;">
						<input type="text" id="advancedFilter_toDatePicker" readonly="readonly" value="" style="width:100px; cursor: text" class="hasDatepicker">
					</div>
				</div>
			</div>

			<!-- Course Filter -->
			<?php
				$allowableGroupIds = array(1,2,3,7,8,9,15,16);
			  if(in_array($userDataArray['groupId'],$allowableGroupIds)){  ?>
	        <div class="dropdown col-md-2 col-sm-3 col-xs-12">
	            <div class="dropdown">
	                <select id="advancedFilter_course" multiple="multiple" >
	                    <option value="all">All Courses</option>
	                    <?php foreach ($courseListForMailerRequest as $key => $value) { ?>
	                    	<option value="<?php echo $value;?>" ><?php echo $value;?></option>		
	                    <?php } ?>
	                </select>
	            </div>	            
			</div>
			<?php } ?>
			<!-- Branch Filter -->
	        <div class="dropdown col-md-2 col-sm-3 col-xs-12">
	            <div class="dropdown">
	                <select id="advancedFilter_branch" multiple="multiple">
	                    <option value="all" selected>All Branch</option>
	                    <?php foreach ($salesBranches as $key => $value) { ?>
	                    	<option value="<?php echo $key;?>" ><?php echo $value;?></option>		
	                    <?php } ?>
	                </select>
	            </div>	            
			</div>

			<!-- Current Status Filter -->
	        <div class="dropdown col-md-2 col-sm-3 col-xs-12">
	            <div class="dropdown">
	                <select id="advancedFilter_currentStatus" multiple="multiple">
	                    <option value="all" selected>All</option>
	                    <?php foreach ($currentStatusFilter as $key => $value) { ?>
	                    	<option value="<?php echo $key;?>" ><?php echo $value;?></option>		
	                    <?php } ?>
	                </select>
	            </div>	            
			</div>

			<!-- Apply Filter -->
			<div class=" col-md-2 col-sm-3 col-xs-12" style="float:right;margin-top:2px !important">
				<button type="button" id="advancedFilter_submit" onclick="viewRequest.applyAdvancedFilter()" class="btn btn-primary <?php echo $width;?>" style= "padding-top:10px !important">Apply Filter</button>
			</div>
		</div>	
	</div>
</div>
<div class="x_title">
	<div class="clearfix"></div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
<br>
</div>

  