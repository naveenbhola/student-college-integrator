<div id="breadcrumb" class="breadcrumb clear-width" style="width: 600px">                    
                    <?php
                    $totalBreadCrumbs = count($breadCrumb);
                    for($i = 0; $i < $totalBreadCrumbs; $i++) {?>
			<span itemscope itemtype="https://data-vocabulary.org/Breadcrumb" class="breadcrumb-link">
                         <?php if($breadCrumb[$i]['url'] != ""){?>
                         <a href="<?php echo $breadCrumb[$i]['url']?>" itemprop="url"><span itemprop="title"><?php echo $breadCrumb[$i]['name'];?></span></a>
                         <?php } else {?>
                         <?php echo $breadCrumb[$i]['name'];?>
                         <?php } ?>
			</span>
                     <?php if($i != ($totalBreadCrumbs -1)) {?>
                     <span class="breadcrumb-arrow">&rsaquo;</span>
                     <?php
                          }                     
                     } ?>
</div>