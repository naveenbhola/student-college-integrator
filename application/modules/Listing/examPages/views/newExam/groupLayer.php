<!--group list layer starts-->
<div class="layer-common" id="courselayer" style="display:none;">
   <div class="group-card pop-div">
      <a class="cls-head heplyr" data-layer="courselayer">&times;</a>
      <div>
         <h3 class="head-3"> Choose the course you're interested in:</h3>
         <div class="custom-dropdown mrg-btm-10 gen-cat">
            <div class="dropdown-primary" id="clist" defaulttext="Select Course">
                    <span class="option-slctd"></span>
                    <span class="icon"></span>
                    <ul class="dropdown-nav" style="display: none;">
                    <?php 
                        foreach ($groupList as $key=>$details) {                            
                     ?>
                        <li class="li-dropdown"><a class="li-dropdown-a" value="<?php echo $details['id'];?>" ga-attr="COURSE_SELECTION"><?php echo $details['name'];?></a></li>
                     <?php } ?>
                    </ul>
            </div>
         </div>
         <div class="algn-rt">
            <input type="button" value="Change Course" class="btn-primary" id="grpChange">
            <input type="hidden" value="" id="courseSelectedFromLayer" name="courseSelectedFromLayer">
            <input type="hidden" value="<?=$currentUrl;?>" id="currentUrl" name="currentUrl">
         </div>
      </div>
   </div>
</div>


<!--group list layer ends-->