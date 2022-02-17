<?php
$page = $beaconTrackData['pageIdentifier'];
?>
<div class="browse-section clearwidth">
    <div class="browse-title">
        <i class="article-sprite browse-icon2"></i>
        <?php if($browsewidget['widgetHeading']!='')
        {  echo "Browse colleges offering ". $browsewidget['widgetHeading'];   }
        else
        {  echo "Browse colleges in abroad"; }
        ?>
    </div>

    <div class="browse-content clearwidth">
        <ul>
            <?php
            for($i = 0; $i < 6; $i++)
            {
                ?>
                <li>
                    <div class="abroad-flag-box flLt"><span class="flags <?php echo str_replace(" ",'',(strtolower($browsewidget['finalArray']['location'][$i])));?>"></span></div>
                    <div class="abroad-univ-detail">
                        <a href="<?php echo $browsewidget['finalArray']['url'][$i];?>" class="gaTrack" gaparams="<?php echo $page; ?>,browseWidget,course"><?php echo $browsewidget['finalArray']['title'][$i];?></a>
                        <p><?php echo  $browsewidget['finalArray']['college_count'][$i] .(($browsewidget['finalArray']['college_count'][$i]>1 && ($browsewidget['finalArray']['college_count'][$i]!='') )? " Colleges in ":" College in ").$browsewidget['finalArray']['location'][$i];?></p>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>