<?php
$page = $beaconTrackData['pageIdentifier'];
?>
<div class="browse-section clearwidth">
    <div class="browse-title">
        <i class="article-sprite browse-icon2"></i>
        Browse universities abroad
    </div>

    <div class="browse-content">
        <ul>
            <?php
            for($i = 0; $i < 6; $i++)
            {
                ?>
                <li>
                    <div class="abroad-flag-box flLt"><span class="flags <?php echo str_replace(" ",'',(strtolower($browsewidget['finalArray']['location'][$i])));?>"></span></div>
                    <div class="abroad-univ-detail">
                        <a href="<?php echo $browsewidget['finalArray']['url'][$i];?>" class="gaTrack" gaparams="<?php echo $page; ?>,browseWidget,university">
                            Universities in <?php echo  $browsewidget['finalArray']['location'][$i];?></a>
                        <p><?php echo $browsewidget['finalArray']['univ_count'][$i].(($browsewidget['finalArray']['univ_count'][$i]>1 && $browsewidget['finalArray']['univ_count'][$i] !='')? " Universities":" University");?></p>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>