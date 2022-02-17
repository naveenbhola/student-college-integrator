
<?php
  if($isEdit == 1 || $isReplicate == 1){
    $headerComponents = array(
        'css'   => array('headerCms','raised_all','mainStyle','footer','mailScheduler'),
        'js'    => array('mailer','mailCampaign','CalendarPopup')
    );
        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('common/calendardiv');
  }
?>

<div id='clientPage' class="mar_full_10p">
  <?php if($isEdit == 1 || $isReplicate == 1) { ?>
       <div style="margin-left:40px">
      <?php $this->load->view('mailer/left-panel-top'); ?>

  <?php  }?>
        
    <div>
        
        <div id="dataLoaderPanel" style="position:absolute;display:none">
            <img src="/public/images/loader.gif"/>
        </div>
        
        <div id ='mailCriteriaWrapper' class="drip_compaign">
           <div class="drip_data">
             <h2>Setup Email Campaign</h2>
             <p>All fields on this page are mandatory to schedule mailer unless stated otherwise.</p>
          </div>
             
         <?php 

          $this->load->view('mailer/MailSchedulerWidgets/clientDetails');
          $this->load->view('mailer/MailSchedulerWidgets/mailCriteria');
          ?>
        </div>
        <?php  if($isEdit != 1) {?>

          <div id ="add-more-btn" class="midle_align">
              <p>
                 <a href = 'javascript:void(0);' onclick = 'addNewMailerCriteria()'><strong>+Add More</strong></a>
              </p>
              <p>Click to schedule more emails for this campaign</p>
            </div>
            <div class="clearFix"></div>
            <div class="lineSpace_35">&nbsp;</div>


            <div class="clearFix"></div>
            <br />
            <br />
          </div>
        <?php }   ?>
  <?php if($isEdit == 1 || $isReplicate == 1) { ?>
       </div>
  <?php  } ?>

</div>
  <script type="text/javascript">

   var existingPageData = {};
   existingPageData.templateData= <?php echo json_encode($templateData) ?>;
   existingPageData.senderEmailIds= <?php echo json_encode($senderEmailIds); ?>;
   existingPageData.usersets = <?php echo json_encode($usersets); ?>;
   existingPageData.clientDetails = <?php echo json_encode($clientDetails); ?>;
</script>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php if($isEdit || $isReplicate){ ?>
<script>$j = $.noConflict();</script>
<?php } ?>
<script type="text/javascript">
   var savedMailerData = {};
   var isEdit = <?php echo ($isEdit) ?>;
   var isReplicate = <?php echo ($isReplicate) ?>;
   if(isReplicate == true) {
     var savedMailerData = <?php echo json_encode($savedMailerData) ?>;
     replicateMailer();
   } else if(isEdit == true){
     var savedMailerData = <?php echo json_encode($savedMailerData) ?>;
     populateSavedMailerData();
   } 
  
</script>

