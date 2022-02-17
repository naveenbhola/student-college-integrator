<?php if(count($secondaryTile)>0){
?>
<h2 class="pop-review-title">Review Collections</h2>
<?php
     foreach($secondaryTile as $stiles){
        $url = ($stiles['url'] !='') ? $stiles['url'] : $stiles['seoUrl']; 
?>
<section class="content-section top-gap-box">
     <a class="tileFinder" href="<?php echo $url;?>" style="display: block;" tileimg="<?php echo $stiles['mImage'];?>">
          <div class="txtWthImgsBx bordRdus3px adjustHeight" style="position: relative;">
               <span class="gradient-rv"></span>
               <h2 class="gradient-hd"><?php echo ucfirst($stiles['title']);?></h2>
          </div>
     </a>  
</section>
<?php }}?>
