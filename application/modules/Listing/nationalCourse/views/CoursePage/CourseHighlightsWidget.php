<?php 
if(is_array($highlights) && count($highlights)>0){
?>
<!--highlights start-->
     <div class="new-row">
       <div class="group-card no__pad gap listingTuple" id="highlights">
       <h2 class="head-1 gap">Highlights</h2>
         <div class="new-row events highlights">
	  <?php if(count($highlights)<2){ ?>
            <ul class="cl-ul">
	       <?php foreach ($highlights as $usp){ ?>
               <li class="">
                 <p class="para-2"><?=nl2br(makeURLAsHyperlink(htmlentities($usp->getDescription())))?></p>
               </li>
	       <?php } ?>
             </ul>
	  <?php }else{ ?>
            <ul class="flex-ul">
	       <?php for ($i=0; $i<count($highlights); $i++){ ?>
               <li class="">
                 <p class="para-2"><?=nl2br(makeURLAsHyperlink(htmlentities($highlights[$i]->getDescription())))?></p>
               </li>
	       <?php } ?>
             </ul>
	  <?php } ?>
         </div>
     </div>
     </div>

<?php
}
?>
