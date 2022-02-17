<div id="main-wrapper">
   <div class="content-wrap clearfix">
      <div class="main">
         <section class="counselr-page clear_max">
           <div class="profile_left">
             <?php $this->load->view("applyHome/widgets/counselorProfile");?>
             <?php $this->load->view("applyHome/widgets/counselorReviewWidget");?>
           </div>
           <?php $this->load->view("applyHome/widgets/counsellorReviewPageRightWidget");?>
         </section>
             <?php $this->load->view('widgets/reviewErrorPopUp'); ?>
      </div>
   </div>
</div>
