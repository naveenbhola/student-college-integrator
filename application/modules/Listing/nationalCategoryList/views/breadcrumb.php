<div class="breadcrumb2">
  <?php
    $totalItemsInbreabcrumb = count($breadcrumb);
    $counter = 0;
    foreach ($breadcrumb as $breadcrumbObj) {
        $counter++;
        if(!empty($breadcrumbObj->url)) { ?>
            <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">                     
                <a itemprop="url" href="<?=$breadcrumbObj->url;?>">
                <?php //if(strtolower($breadcrumbObj->name) == "home" || isset($breadcrumbObj->useActual)) { ?>
                    <!--a itemprop="url" href="<?=$breadcrumbObj->url;?>"-->
                <?php //} else { ?>
                    <!--a itemprop="url" href="<?=base_url().$breadcrumbObj->url;?>"-->
                <?php //} ?>
                <span itemprop="title"><?=$breadcrumbObj->name;?></span>
              </a>
            </span>
        <?php } else { ?>
            <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">                     
                <?=$breadcrumbObj->name;?>
            </span>
        <?php } ?>
        
        <?php if($counter != $totalItemsInbreabcrumb) { ?>
            <span class="breadcrumb-arrow">›</span>
        <?php }
    } ?>
</div>