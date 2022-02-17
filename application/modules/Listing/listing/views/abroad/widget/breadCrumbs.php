<div id="breadcrumb"><?php
foreach($breadcrumbData as $key => $breadCrumb) {
?>
     <span itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><?php
		if($breadCrumb['url'] != "") {
		?>
		  <a href="<?=$breadCrumb['url']?>" itemprop="url"><span itemprop="title"><?=htmlentities(str_replace("in abroad","abroad",$breadCrumb['title']))?></span></a>
		  <span class="breadcrumb-arr">&rsaquo;</span>
		  <?php
		} else {  ?>
		   <span itemprop="title"><?=htmlentities($breadCrumb['title'])?></span>
		<?php } ?>
     </span>
<?php
}
?>
</div>
