<?php
$GA_Tap_On_View_All = 'VIEW_ALL_HIGHLIGHTS';
if(is_array($highlights) && count($highlights)>0){
?>
<section>
           <div class="data-card m-5btm" id="high">
               <h2 class="color-3 f16 heading-gap font-w6">Highlights</h2>
               <div class="card-cmn color-w">
                   <input type="checkbox" class="read-more-state hide" id="post-hglt" />
                   <ol class="highlights-ol read-more-wrap">
		      <?php for ($i=0; $i<count($highlights); $i++){ ?>
                       <li class="<?php if($i>=4){echo 'read-more-target color-6 f13';}?>color-6 f13"><p class="color-3"><?php echo makeURLAsHyperlink(htmlentities($highlights[$i]->getDescription()),true);?></p></li>
		     <?php } ?>
                   </ol>
		   <?php
                if(count($highlights) > 4){ ?>
                   <label for="post-hglt" class="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_All;?>">View all</label>
		<?php } ?>
               </div>
           </div>
</section>
<?php
}
?>

