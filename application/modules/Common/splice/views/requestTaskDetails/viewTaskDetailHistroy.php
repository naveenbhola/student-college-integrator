<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" style="margin-top:25px !important" >
	<div class="col-md-12 col-sm-12 col-xs-12" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<h2><b>Status History :</b></h2>
			<div class="x_title">
		    	<div class="clearfix"></div>
		  	</div>
		</div>
	</div>
</div>
<?php foreach ($viewTaskDetailHistory as $key => $value) { //_p($value);die;?>	
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" style="margin-top:25px !important;margin-bottom:5px !important" >
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-1 col-sm-1 col-xs-12" >
				<b>Level <?php echo $key?> :</b>
			</div>
			<div class="col-md-11 col-sm-10 col-xs-12" >
			<div class="col-md-3 col-sm-1 col-xs-12" >
				<?php echo $value['assignee']?>
			</div>
			<div class="col-md-4 col-sm-1 col-xs-12" >
				<b>Status : </b><?php echo $value['status']?>
			</div>
			<div class="col-md-3 col-sm-1 col-xs-12" style="margin-right:0px !important;padding-right:0px !important">
				<b>On :  </b><?php echo $value['updatedOn']?>
			</div>				
			</div>
		</div>
	</div>

	<!-- Done By -->
	<?php if($value['doneBy']){?>
		<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" style="margin-bottom:5px !important" >
			<div class="col-md-12 col-sm-12 col-xs-12" >
				<div class="col-md-1 col-sm-1 col-xs-12" style="padding-right:0px !important">
					<b>Done By: </b>
				</div>
				<div class="col-md-11 col-sm-10 col-xs-12" >
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div>
							<?php echo $value['doneBy']?>
						</div>					
					</div>
				</div>
			</div>
		</div>
	<?php }  ?>
	<!-- Comment Data -->
	<div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" style="margin-bottom:20px !important">
		<div class="col-md-12 col-sm-12 col-xs-12" >
			<div class="col-md-1 col-sm-1 col-xs-12" style="padding-right:0px !important">
				<b>Comments: </b>
			</div>
			<div class="col-md-11 col-sm-10 col-xs-12" >
				<div class="col-md-12 col-sm-12 col-xs-12 taskActionDetails">
				<div class='commentDetails'>
					<div >
						<?php echo $value['statusCommentDetails']['comment']?$value['statusCommentDetails']['comment']:'<br><br>';?>					
					</div>
					<div >
						<?php if($value['statusCommentDetails']['attachmentURL']){?>
							<b>Attachment:</b> <a href="<?php echo MEDIAHOSTURL.$value['statusCommentDetails']['attachmentURL'];?>" target="_blank"><?php echo MEDIAHOSTURL.$value['statusCommentDetails']['attachmentURL']?></a>
						<?php }?>
					</div>
				</div>
				<div class="<?php if($value['commentDetails']['commentAndPushBackDetails']){echo 'pushedBackCommentDiv';} ?>" id ='pushedBackCommentDiv'>
					<?php 
					$i =1;
					foreach ($value['commentDetails']['commentAndPushBackDetails'] as $pushBackCommentNo => $pushBackCommentNoDetails) { ?>
						<div class=" <?php echo ($i++ !=count($value['commentDetails']['commentAndPushBackDetails']))?'x_title':'';?>  col-md-12 col-sm-12 col-xs-12" style="margin-top:5px !important;padding-left:0px !important;">
							<?php if($pushBackCommentNoDetails['pushBackAssignedTo']){?>
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
									<div class="col-md-3 col-sm-12 col-xs-12" style="padding-left:0px !important;">
										<b>By : </b><?php echo $pushBackCommentNoDetails['statusTakenBy'];?>
									</div>
									<div class="col-md-3 col-sm-12 col-xs-12" >
										<b>Action : </b><?php echo $pushBackCommentNoDetails['status'];?>
									</div>
									<div class="col-md-3 col-sm-12 col-xs-12" >
										<b>To : </b><?php echo $pushBackCommentNoDetails['pushBackAssignedTo'];?>
									</div>
									<div class="col-md-3 col-sm-12 col-xs-12" >
										<b>On : </b><?php echo $pushBackCommentNoDetails['statusTakenOn'];?>
									</div>
								</div>
							<?php }else{?>
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
									<div class="col-md-3 col-sm-12 col-xs-12" style="padding-left:0px !important;">
										<b>By : </b><?php echo $pushBackCommentNoDetails['statusTakenBy'];?>
									</div>
									<div class="col-md-6 col-sm-12 col-xs-12" >
										<b>Action : </b><?php echo $pushBackCommentNoDetails['status'];?>
									</div>

									<div class="col-md-3 col-sm-12 col-xs-12" >
										<b>On : </b><?php echo $pushBackCommentNoDetails['statusTakenOn'];?>
									</div>
								</div>
							<?php }?>

							<?php if($pushBackCommentNoDetails['actionTakenBy']){?>
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
									<div class="col-md-1 col-sm-1 col-xs-12" style="padding-right:0px !important;padding-left:0px !important;">
										<b>Done By: </b>
									</div>
									<div class="col-md-9 col-sm-10 col-xs-12" >
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div>
												<?php echo $pushBackCommentNoDetails['actionTakenBy']?>
											</div>					
										</div>
									</div>									
								</div>		
							<?php }?>
						
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px !important">
									<div class="col-md-1 col-sm-12 col-xs-12" style="padding-left:0px !important;">
										<b>Comment: </b>
									</div>
									<div class="col-md-9 col-sm-12 col-xs-12" >
										<?php echo $pushBackCommentNoDetails['comment'];?>									
									</div>
								</div>
							</div>
							<?php if($pushBackCommentNoDetails['attachmentURL']){?>
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px !important;">
									<b>Attachment:</b> <a href="<?php echo MEDIAHOSTURL.$pushBackCommentNoDetails['attachmentURL']?>" target="_blank"><?php echo MEDIAHOSTURL.$pushBackCommentNoDetails['attachmentURL']?></a>
								</div>
							</div>
							<?php }?>
						</div>
						
					    	<div class="clearfix"></div>		  	
					<?php } ?>
				</div>
			</div>
			</div>
		</div>
	</div>
	<?php }  ?>