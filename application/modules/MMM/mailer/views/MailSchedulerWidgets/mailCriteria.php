<div id  = "mailCriteria_<?php echo $mailCriteria ?>" class="setup_email">
	<div class="mailer_criteria">Mail Criteria <?php echo $mailCriteria ?></div>
	<div class="setup_email-block">
		<?php $data['widgetNumber'] = 1;?>
	 	<?php $this->load->view('mailer/MailSchedulerWidgets/emailSetup',$data); ?>
	 	<?php $this->load->view('mailer/MailSchedulerWidgets/emailSchedule',$data); ?>
	 	<?php $this->load->view('mailer/MailSchedulerWidgets/userSetSelection'); ?>
	 	<?php $this->load->view('mailer/MailSchedulerWidgets/dripCampaign'); ?>
	</div>
</div>
<div class="clearFix"></div>
<div class="lineSpace_35">&nbsp;</div>
