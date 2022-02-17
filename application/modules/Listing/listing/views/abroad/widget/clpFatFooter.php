<?php 
if(count($fatFooterData['widgetData']) > 0){
?>
<div class="SubcatLinking">
   <div class="subcatWidget">
     <h3 class="boldtitl"><?php echo $fatFooterData['heading']; ?></h3>
      <div class="innerWidget clearfix">
         <?php 
          foreach ($fatFooterData['widgetData'] as $value) {
          ?>
            <div class="setSide">
             <a href="<?php echo $value['url'];?>"><?php echo $value['label'];?></a>
           </div>
          <?php 
        }
          ?>
      </div>
   </div>
</div>
<?php 
}
?>