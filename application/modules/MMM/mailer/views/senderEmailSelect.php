<?php
$senderEmailIds = array('noreply@shiksha.com','no-reply@shiksha.com','marcomm@shiksha.com','marketing@shiksha.com', 'news@shiksha.com', 'collegealerts@shiksha.com', 'admissions@shiksha.com', 'counselling@shiksha.com', 'features@shiksha.com', 'research@shiksha.com', 'college.reviews@shiksha.com', 'studyabroad@shiksha.com', 'learn.intern@shiksha.com');
?>
<select class="normaltxt_11p_blk_arial fontSize_12p" required="true" name="userFeedbackEmail" id="userFeedbackEmail" style="border:1px solid #ccc; padding:3px 2px; font-size: 13px; ">
    <?php foreach($senderEmailIds as $emailId) { 
    		$emailSelected = '';
    		if($mailerDetails['senderMail'] == $emailId) {
    			$emailSelected = 'selected="selected"';
    		} ?>
        <option value='<?php echo $emailId; ?>' <?php echo $emailSelected;?>><?php echo $emailId; ?></option>
    <?php } ?>
</select>
