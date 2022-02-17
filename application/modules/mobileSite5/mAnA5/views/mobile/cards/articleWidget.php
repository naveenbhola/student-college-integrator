<div class="latest-ArtDiv">
   <p class="latest-ArtHead">Latest Articles</p>
   <div class="latest-SLdrDiv">
      <div class="art-caraousal">
         <ul>
            <?php
                 foreach ($articles as $value) {
            ?>

               <li>
                  <a href="<?=SHIKSHA_HOME.$value['url']?>">
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

         </ul>
      </div>

   </div>
</div>

