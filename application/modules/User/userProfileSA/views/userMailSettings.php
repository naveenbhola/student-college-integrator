<div class="unsubDiv" <?php echo ($subscrSetting===""?'style="display:none;"':'') ?>>
  <div class="email_title">COMMUNICATION PREFERENCES <span>(Choose only the emails you'd like to receive on your registered email id)</span></div>
  <div class="email_row">
    <?php $this->load->view('userProfilePage/userMailSubscriptionSettings'); ?>
  </div>
</div>