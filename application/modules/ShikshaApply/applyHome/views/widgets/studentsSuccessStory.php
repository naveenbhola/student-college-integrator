<div class="apply_col">
   <section class="page_width">
      <h2 class="txt_cntr fnt_28">What students have to say about us</h2>
      <div class="video_block clearfix">
         <?php foreach($successVideoArray as $key=>$details){ ?>
         <div id="ankur" class="video_div">
           <a class="video_quote" videoid="<?php echo $details['videoId']; ?>">
             <img class="lazy" src="" alt="<?php echo $details['name']; ?>" title="<?php echo $details['name']; ?>" data-original="<?php echo IMGURL_SECURE; ?>/public/images/<?php echo $details['image']; ?>-desk.jpg">
             <strong class="play_back"> <i></i> </strong>
              <blockquote>
                <i class="lft_quote"></i>
                <?php echo $details['quotes']; ?>
               <i class="rght_quote"></i>
              </blockquote>
           </a>
            <div class="video_footer">
              <div class="video_cont">
                <p class="fnt_16"><?php echo $details['name']; ?></p>
                <p class="fnt_14" title="<?php echo $details['univName']; ?>"><?php echo $details['exam'][0]; ?>, Admitted to <?php echo formatArticleTitle($details['univName'],35); ?></p>
                <a href="<?php echo $details['articleURL']; ?>">Read My Success Story</a>
              </div>
            </div>
         </div>
         <?php } ?>
      </div>
   </section>
</div>
<?php $this->load->view('applyHome/widgets/studentsSuccessStoryLayer'); ?>
