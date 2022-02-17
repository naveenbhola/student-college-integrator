<div id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList"><?php
foreach($breadCrumbData as $key => $breadCrumb) {
?>
     <span itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><?php
		if($breadCrumb['url'] != "") {
		?>
		  <a href="<?=$breadCrumb['url']?>" itemtype="http://schema.org/Thing"
       itemprop="item">
                  <span itemprop="name"><?php echo $breadCrumb['title']?></span></a>
		  <span class="breadcrumb-arr">&rsaquo;</span>
		  <?php
		} else {  ?>
		   <span itemprop="name"><?php echo $breadCrumb['title']?></span>
		<?php } ?>
                     <meta itemprop="position" content="<?php echo $key+1; ?>" />
     </span>
<?php
}
?>
</div>
