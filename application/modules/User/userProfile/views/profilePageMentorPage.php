<div class="prf-tabpane" id="tab_2">
                       
                       <?php $this->load->view('profilePageMentorDetails');?>
                       
                       <?php $this->load->view('profilePageMentorChat');?>
                   </div>

<script type="text/javascript">
	menteeId = '<?php echo $userId; ?>';
    //alert(slotData);
    mentorId = '<?php echo $mentorId;?>';
    scheduleId = '<?php echo $slotData['id'];?>';
    slotId = '<?php echo $slotData['slotId'];?>';
    userType = '<?php echo $slotData['userType'];?>';
</script>                