
<div class="col-md-12 col-sm-12 col-xs-12" style="text-align:center">
	<b><h2><?php echo ucfirst($appName); ?> Request Task</h2></b>
	<div class="x_title">
		<div class="clearfix"></div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
		<b>Request Id :</b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['requestId']?>
		</div>
	</div>
	<?php if($requestTaskDetails['clientName']){ ?>
	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
		<b>Client Name :</b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<b><?php echo $requestTaskDetails['clientName'];?></b>
		</div>
	</div>
	<?php }?>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Task Id : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['taskId']?>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Sales Order No. : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['salesOrderNumber']?>
		</div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Task Category : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<b><?php echo $requestDisplayName[$requestTaskDetails['taskCategory']] ?></b>
		</div>
	</div>		

	<div class="col-md-6 col-sm-6 col-xs-12 " >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Campaign Date : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['campaignDate']?>
		</div>
	</div>		
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-2 col-sm-3 col-xs-3"  style="margin-right: 25px">
		<div class="col-md-12 col-sm-12 col-xs-12"  >
			<b>Task Title : </b>
		</div>
	</div>
	<div class="col-md-9 col-sm-9 col-xs-9" >
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 2px !important">
		<?php echo $requestTaskDetails['taskTitle']?>
		</div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Site :</b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['site']?>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Task Type :</b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['taskType']?>
		</div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Created By : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['assignedBy']?>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Assigned Date : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['assignedDate']?>
		</div>
	</div>		
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Assigned To : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['assignedTo']?>
		</div>
	</div>		

	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Status : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['statusToShow']?>
		</div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>Last Updated On :</b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<?php echo $requestTaskDetails['lastUpdatedOn']?>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12" >
		<div class="col-md-4 col-sm-4 col-xs-12" >
			<b>TAT Date : </b>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12" >
			<b><?php echo $requestTaskDetails['TATDate']?></b>
		</div>
	</div>		
</div>

<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<?php if($requestTaskDetails['changeType']){?>
			<div class="col-md-6 col-sm-6 col-xs-12" >
				<div class="col-md-4 col-sm-4 col-xs-12" >
					<b>Change Type :</b>
				</div>
				<div class="col-md-7 col-sm-7 col-xs-12" >
					<?php echo ucfirst($requestTaskDetails['changeType'])?>
				</div>
			</div>
	<?php }?>
	<?php if($requestTaskDetails['taskRequestedBy']){?>
		<div class="col-md-6 col-sm-6 col-xs-12" >
		
			<div class="col-md-4 col-sm-4 col-xs-12" >
				<b>Requested By :</b>
			</div>
			<div class="col-md-7 col-sm-7 col-xs-12" >
				<?php echo $requestTaskDetails['taskRequestedBy']?>
			</div>
		</div>
	<?php } ?>
</div>

<?php if($requestTaskDetails['landingPageURL']){?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
			<div class="col-md-2 col-sm-3 col-xs-3"  style="margin-right: 25px">
				<div class="col-md-12 col-sm-12 col-xs-12"  >
					<b>Landing Page URL : </b>
				</div>
			</div>
			<div class="col-md-9 col-sm-9 col-xs-9" >
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 2px !important">
					<?php echo $requestTaskDetails['landingPageURL']?>
				</div>
			</div>
	</div>
<?php }?>

<?php if($requestTaskDetails['taskCategory'] == 'Mailer'){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
		<?php if($requestTaskDetails['mailerType']){?>
			<div class="col-md-6 col-sm-6 col-xs-12" >
				<div class="col-md-4 col-sm-4 col-xs-12" >
					<b>Mailer Type :</b>
				</div>
				<div class="col-md-7 col-sm-7 col-xs-12" >
					<?php echo $requestTaskDetails['mailerType']?>
				</div>
			</div>
		<?php }?>
		<div class="col-md-6 col-sm-6 col-xs-12" >
			<div class="col-md-4 col-sm-4 col-xs-12" >
				<b>Course :</b>
			</div>
			<div class="col-md-7 col-sm-7 col-xs-12" >
				<?php echo $requestTaskDetails['course']?>
			</div>
		</div>
	</div>
<?php }else if($requestTaskDetails['taskCategory'] == 'Listing'){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
		<?php if($requestTaskDetails['listingIdForListing']){?>
			<div class="col-md-6 col-sm-6 col-xs-12" >
				<div class="col-md-4 col-sm-4 col-xs-12" >
					<b><?php echo $requestTaskDetails['listingTypeForListing'].' Id';?> :</b>
				</div>
				<div class="col-md-7 col-sm-7 col-xs-12" >
					<?php echo $requestTaskDetails['listingIdForListing']?>
				</div>
			</div>
		
			<div class="col-md-6 col-sm-6 col-xs-12" >
				<div class="col-md-4 col-sm-4 col-xs-12" >
					<b><?php echo $requestTaskDetails['listingTypeForListing'].' Name';?> :</b>
				</div>
				<div class="col-md-7 col-sm-7 col-xs-12" >
					<?php echo $requestTaskDetails['listingNameForListing']?>
				</div>
			</div>
		<?php }?>
	</div>
<?php }else if($requestTaskDetails['taskCategory'] == 'Shoshkele'){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
		<div class="col-md-6 col-sm-6 col-xs-12" >
			<div class="col-md-4 col-sm-4 col-xs-12" >
				<b>Category Sponsor Type :</b>
			</div>
			<div class="col-md-7 col-sm-7 col-xs-12" >
				<?php echo $requestTaskDetails['categorySponsorType']?>
			</div>
		</div>
	</div>
<?php }else if($requestTaskDetails['taskCategory'] == 'Campaign Activation'){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
		<div class="col-md-12 col-sm-6 col-xs-12" >
			<div class="col-md-2 col-sm-4 col-xs-12" style="margin-right: 20px;padding-right: 0px !important">
				<b>Campaign Type :</b>
			</div>
			<div class="col-md-8 col-sm-7 col-xs-12" >
				<?php echo $requestTaskDetails['diffCampaignTypes']?>
			</div>
		</div>
	</div>
<?php }else if($requestTaskDetails['taskCategory'] == 'Banner'){ ?>
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
		<div class="col-md-12 col-sm-6 col-xs-12" >
			<div class="col-md-2 col-sm-4 col-xs-12" style="margin-right: 20px">
				<b>Banner Size :</b>
			</div>
			<div class="col-md-9 col-sm-7 col-xs-12" >
				<?php echo $requestTaskDetails['diffBannerSize']?>
			</div>
		</div>
	</div>
<?php } ?>



<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
	<div class="col-md-12 col-sm-12 col-xs-12" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<b>Description : </b>
		</div>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-12 col-sm-12 col-xs-12 taskActionDetails">
				<?php echo $requestTaskDetails['description']?>
			</div>
		</div>
	</div>
</div>

<?php if($attachmentDetails){?>
<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading " style="margin-top:5px !important;margin-bottom:5px !important;">
	<div class="col-md-12 col-sm-12 col-xs-12" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<b>Attachment(s) : </b>
		</div>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-12 col-sm-12 col-xs-12 taskActionDetails" style="padding-left:0px !important;" >
				<?php
					$i =1;
					foreach ($attachmentDetails as $key => $value) { ?>
						<div class="col-md-12 col-sm-12 col-xs-12 " style="margin-top:5px !important;padding-left:0px !important;">
							<div class="col-md-6 col-sm-12 col-xs-12 ">
								<?php echo $i++.'. ';?><a href="<?php echo MEDIAHOSTURL.$value['attachmentURL']?>" target="_blank"><?php echo MEDIAHOSTURL.$value['attachmentURL']?></a>
							</div>
							<div class="col-md-3 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
								<div class="col-md-2 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
									<b>By :</b>
								</div>
								<div class="col-md-9 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
									<?php echo $value['addedBy']?>
								</div>
								
							</div>
							<div class="col-md-3 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
								<div class="col-md-2 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
									<b>On :</b>
								</div>
								<div class="col-md-9 col-sm-12 col-xs-12 " style="margin:0px !important;padding:0px !important;">
									<?php echo $value['addedOn']?>
								</div>
							</div>
						</div>
				<?php	} ?>
				
			</div>
		</div>
	</div>
</div>
<?php }?>
	