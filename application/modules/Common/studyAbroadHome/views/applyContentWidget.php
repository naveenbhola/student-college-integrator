<div class="carausel-section study-plan-widget clearfix">
    <div class="clearfix">
        <h2 class="flLt">Learn more about application process</h2>
    </div>
    
    <div class="planning-info application-process-info clearwidth" style="display:block;" id="chooseRightSection">
        <ul class="apply-content-sec">
            <?php foreach ($applyContent as $key => $value) {
                $value['icon'] = str_replace('-icon','-home-icn',$value['icon']);
                ?>
            <li>
                <a href="<?php echo $value['contentURL'];?>">
                    <i class="home-sprite <?php echo $value['icon'];?>"></i>
                    <div class="app-PrcsDiv">
                        <strong><?php echo $value['heading'];?></strong>
                        <p><?php echo $value['name'];?></p>
                    </div>
                </a>
            </li>
            <?php
            } ?>
        </ul>
    </div>
</div>