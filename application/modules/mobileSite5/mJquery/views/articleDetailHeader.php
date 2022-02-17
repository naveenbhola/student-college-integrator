<header id="page-header2" class="clearfix" data-role="header">
    <div class="head-group" data-enhance="false">
        <div class="left-align" style="<?php  if($displayHamburger){echo 'margin: 10px 40px 4px 13px';}else{echo 'margin: 10px 40px 4px 13px';} ?>">
            <h1 style="text-align:left">
                <?=$blogObj->getTitle();?>
            </h1>
            <?php if($blogObj->getType() == 'kumkum'){ ?>
                <a href="<?php echo SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/kumkumProfile';?>">by Kumkum Tandon</a>
            <?php } ?>
        </div>
    </div>
</header>