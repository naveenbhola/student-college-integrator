<div>
	<div class="x_panel">
		<div class="x_content">
			<!-- Request Details -->
			<div class="col-md-12 col-sm-12 col-xs-12" role="row">
				<div class="col-md-12 col-sm-12 col-xs-12 x_title" style="font-size: 18px;font-weight: 400;"><h2>Request Id : <?php echo $requestData[0]['id'];?></h2>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-6 col-sm-6 col-xs-12" style="font-size: 14px;font-weight: 400;">Sales Order No. : <?php echo $requestData[0]['salesOrderNumber'];?></div>
					<div class="col-md-6 col-sm-6 col-xs-12" style="font-size: 14px;font-weight: 400;">Creation Date : <?php echo $requestData[0]['campaignDate'];?></div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-6 col-sm-6 col-xs-12" style="font-size: 14px;font-weight: 400;">Creation Date : <?php echo $requestData[0]['requestedOn'];?></div>
					<div class="col-md-6 col-sm-6 col-xs-12" style="font-size: 14px;font-weight: 400;">Created By : <?php echo $requestData[0]['requestedBy'];?></div>
				</div>
			</div>

			<!--  task Details -->
			<div class="col-md-12 col-sm-12 col-xs-12" style="width:100%">
					<table class="table table-striped table-bordered dataTable no-footer dtr-inline" id="datatable-buttons" role="grid"  style="width: 100% !important;">
						<thead>
							<tr>
								<?php
									foreach ($taskHeader as $heading) { ?>
										<th><?php echo $heading;?></th>
								<?php } ?>				
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($taskDetails as $key => $task) { ?>
									<tr>
										<?php
											foreach ($task as $taskDetails) { ?>
												<td><?echo $taskDetails;?></td>	
										<?php	}
										?>
									</tr>
									
							<?php	} ?>
						</tbody>
					</table>
			</div>
		</div>	
	</div>
</div>                 
