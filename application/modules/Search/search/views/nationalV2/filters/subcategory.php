<div class="left_nav">
    <label class="nav_main_head">Show results for</label>
    <div class="srp-cm-img"></div>
    <ul class="menu accordion">
        <?php 
        $flag = true;
        foreach($filters['subCat'] as $key=>$cat){
            ?>
            <li id='list<?php echo $key; ?>'><a <?php if($flag){echo 'class="activeLeftNav"';} ?>><?php echo $cat['name']; ?><i class="icons ic_down"></i></a>
                <div class="scrollbar1" <?php if(!$flag){echo "style = 'display:none'";} ?>>
                    <ul class="srpsubmenu subCatFilter">
                    <?php
                    $i=0;
                    foreach($cat['subCategory'] as $subCategory){
                        ?>
                        <li <?php echo ($i>3)? 'style="display:none"':''; ?>><a data-item="<?=$key?>:<?=$subCategory['id']?>"><?php echo $subCategory['name']; ?></a></li>
                        <?php
                        $i++;
                    }
                    if(count($cat['subCategory']) > 4){
                        ?>
                        <li><a class="morecourse">+ More courses</a></li>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
            </li>
                    <?php
            $flag = false;
        }
        ?>
    </ul>
</div>