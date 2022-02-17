<div class="breadcrumb2">
    <?php   $count = count($breadCrumbData);
            $breadCrumbClass = "homeType2";
            if ($typeOfPredictor && $typeOfPredictor == 'rank'){
                $breadCrumbClass = "homeType1";
            }
            foreach ($breadCrumbData as $key => $data){
    ?>
                <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                    <?php   if(!empty($data['url'])){?>
                                <a itemprop="url" href="<?=$data['url']?>">
                                    <span itemprop="title">
                                        <?php 
                                            if($data['name'] == 'Home'){
                                        ?>
                                                <i class='icons ic_brdcrm <?=$breadCrumbClass?>'></i>
                                        <?php
                                            }else{
                                                echo $data['name'];
                                            }
                                        ?>
                                    </span>
                                </a>
                    <?php   }else{?>
                                <span itemprop="title"><?=$data['name']?></span>
                    <?php   }?>
                    <?php
                        if (!($key == ($count - 1))) {
                    ?>
                            <span class="breadcrumb-arrow">&rsaquo;</span>
                    <?php
                        }
                    ?>
                </span>
    <?php   }
    ?>
</div>