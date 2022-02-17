<div class="drip--form-data">
  <form class="">
    <div class="drip--from">
      
      <div class="drip--form_data">
        <label>Resend Date:</label>
        <div class="drip--form-fileds noLmrgn">
          <?php $this->load->view('mailer/MailSchedulerWidgets/scheduleDateSelector',$data); ?>
        </div>
      </div>
     
      <?php $this->load->view('mailer/MailSchedulerWidgets/chooseSubscription',$data); ?>

      <?php $this->load->view('mailer/MailSchedulerWidgets/emailSetup',$data); ?>


      <div class="drip_footer">
        <p>Resend this mail in future based on user activity for above email <span>(Optional)</span></p>
      </div>
    </div>
  </form>
</div>