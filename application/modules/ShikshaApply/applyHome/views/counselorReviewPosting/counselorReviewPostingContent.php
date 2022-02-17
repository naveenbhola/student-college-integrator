<div class="content-wrap clearfix">
    <div class="main" id="frm-counselorreview">
      <section class="review_page">
        <!--review head-->
        <div class="review_head">
           <h1 class="fnt24">Shiksha.com Counselor Review</h1>
           <p class="clr3 fnt_14">We thank you for taking out time to fill this survey.</p>
           <p class="head-msg">*All questions are mandatory</p>
        </div>
        <!--review body-->
        <?php $this->load->view('applyHome/counselorReviewPosting/counselorReviewPostingForm'); ?>
      </section>
      <?php 
      $this->load->view('applyHome/widgets/reviewErrorPopUp'); ?>
    </div>
</div>