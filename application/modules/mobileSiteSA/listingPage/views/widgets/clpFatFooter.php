<?php 
if(count($fatFooterData['widgetData']) > 0){
?>
<section class="detail-widget fat-footer">
<div class="subcatWidget">
  <div class="innerWidget">
    <p class="subTitl"><?php echo $fatFooterData['heading']; ?></p>
    <ul>
    <?php 
    foreach ($fatFooterData['widgetData'] as $value) {
    ?>
      <li><a href="<?php echo $value['url'];?>"><?php echo $value['label'];?></a></li>
    <?php 
	}
    ?>
    </ul>
  </div>
</div>
</section>
<?php 
}
?>