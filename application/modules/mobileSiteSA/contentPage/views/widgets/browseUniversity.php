<?php
$page = $beaconTrackData['pageIdentifier'];
?>
<article class="content-inner2">
    <div class="article-guide-content">
        <strong class="article-info-title" style="padding:10px 5px;">Browse Universities Abroad</strong>
        <ul class="university-list">
            <?php   for ($i = 0; $i < 6; $i++) {  ?>
                <li onclick="loadCountryCategoryPage(this);"<?php if($i==5){?> class="last-child" <?php } ?>>
                    <div class="univ-list-detail">
                        <span class="flags-sprite <?php echo str_replace(" ",'',(strtolower($browsewidget['finalArray']['location'][$i]))); ?> flLt"></span>
                        <div class="univ-info" style="margin-left:55px;">
                            <a href="<?php echo $browsewidget['finalArray']['url'][$i];?>" class="font-14" onclick="gaTrackEventCustom('<?php echo $page; ?>','browseWidget','university')">
                                <?php echo "Universities in ".$browsewidget['finalArray']['location'][$i];?>
                            </a>
                            <p><?php echo $browsewidget['finalArray']['univ_count'][$i].(($browsewidget['finalArray']['univ_count'][$i]>1 && $browsewidget['finalArray']['univ_count'][$i] !='')? " Universities":" University");?>
                            </p>
                        </div>
                    </div>
                </li>
            <?php }   ?>
        </ul>
    </div>
</article>