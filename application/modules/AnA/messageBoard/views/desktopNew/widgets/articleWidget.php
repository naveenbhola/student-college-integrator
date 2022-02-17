<div class="carousel-wrap latest-ArtDiv">
   <p class="latest-ArtHead">Latest Articles</p>
   <div class="carousel-container ana-slider latest-SLdrDiv">
      <a href="javascript:void(0);" class="ana-prev shikshaSliderPrev sldrDisableBtn" style="display:none;"><i class=""></i></a>
      <div class="art-caraousal">
         <ul>
               <?php
                    foreach ($articles as $value) {
               ?>
               <li>
                     <a href="<?=$value['url']?>" >
                         <div class="art-Latestcard">
                             <p> <?php echo strlen($value['blogTitle'])>45 ? substr($value['blogTitle'], 0, 45)."..." : $value['blogTitle'];?> </p>
                             <span class="Art-link-blue">Read More</span>
                         </div>
                     </a>
               </li>
               <?php
                  }
               ?>

               <li>
			<a href="<?=SHIKSHA_HOME?>/articles-all">
                         <div class="art-Latestcard tac">
                             <p> View All </p>
                         </div>
			</a>
               </li>

               <p class="clr"></p>
         </ul>
      </div>
      <a href="javascript:void(0);" class="ana-next shikshaSliderNext"><i class=""></i></a>
   </div>
   <p class="clr"></p>
</div>

