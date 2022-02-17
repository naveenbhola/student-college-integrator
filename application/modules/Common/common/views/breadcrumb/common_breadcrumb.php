<div class="breadcrumb2">
<?php 
$totalBreadCrumbs = count($BreadCrumbs);
$i = 0;
foreach($BreadCrumbs as $SingleBreadcrumb) {
    if($SingleBreadcrumb->getUrl() != "") { ?>
    	<span itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
        <a href="<?=$SingleBreadcrumb->getUrl();?>" itemprop="url">
        <span itemprop="title">
            <?=$SingleBreadcrumb->getText();?>
        </span>
        </a>
    	</span>
    <?php } else { ?>
        <span>
            <?=$SingleBreadcrumb->getText();?>
        </span>
    <?php } 
    if($i != ($totalBreadCrumbs -1)) { ?>
        <span class="breadcrumb-arrow">&rsaquo;</span>
    <?php }
    $i++;                 
} ?>
</div>