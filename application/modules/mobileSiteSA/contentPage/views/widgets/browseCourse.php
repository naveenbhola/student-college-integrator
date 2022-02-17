<?php
$page = $beaconTrackData['pageIdentifier'];
?>
<article class="content-inner2">
<div class="article-guide-content">
        <strong class="article-info-title" style="padding:10px 5px;">
        <?php if($browsewidget['widgetHeading']!='')
              {  echo "Browse colleges offering ". $browsewidget['widgetHeading'];   }
              else
              {  echo "Browse colleges in abroad"; }
        ?>
        </strong>
    <ul class="university-list">
         <?php  for ($i = 0; $i < 6; $i++) {  ?>
        <li onclick="loadCountryCategoryPage(this);" <?php if($i==5){?> class="last-child" <?php } ?>>
            <div class="univ-list-detail">
              <span class="flags-sprite <?php echo str_replace(" ",'',(strtolower($browsewidget['finalArray']['location'][$i]))); ?> flLt"></span>
                <div class="univ-info" style="margin-left:55px;">
                        <a href="<?php echo $browsewidget['finalArray']['url'][$i];?>" class="font-14" onclick="gaTrackEventCustom('<?php echo $page; ?>','browseWidget','course')"><?php echo $browsewidget['finalArray']['title'][$i];?></a>
                    <p><?php echo  $browsewidget['finalArray']['college_count'][$i] .(($browsewidget['finalArray']['college_count'][$i]>1 && ($browsewidget['finalArray']['college_count'][$i]!='') )? " Colleges in ":" College in ").$browsewidget['finalArray']['location'][$i];?></p>
                </div>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>
</article>