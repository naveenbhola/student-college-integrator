 <div class="main">
             <section class="counselr-page clear_max">
               <div class="profile_left">
                 <?php $this->load->view('widgets/counselorInfo'); ?>
                    
                 <div class="_card">
                     <?php $this->load->view('widgets/counselorReviewsHead'); ?>
                     <?php $this->load->view('widgets/counselorReviews'); ?>
                 </div>
                    
               </div>
               <?php $this->load->view('widgets/reviewErrorPopUp'); ?>
             </section>
</div>
