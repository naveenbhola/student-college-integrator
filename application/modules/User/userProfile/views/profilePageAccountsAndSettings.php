<?php if(!empty($userData)) { ?>
 	<div class="prf-tabpane" id="tab_1">
                      
        <?php $this->load->view('profilePageChangePassword');?> 

        <?php $this->load->view('profilePageCommPreference');?>
            
    </div>
<?php } ?>