<?php if($timestamp != '') { ?>
<li class="<?php echo $class; ?>" style="cursor:default;">
    <?php if($showUpcoming) { ?>
        <span class="badge"><?php echo $tagName;?></span>
    <?php }  ?>
    <div class="date clearfix">
        <big><?php echo date('d', $timestamp); ?></big>
        <small><?php echo date('M', $timestamp);?><br /><span><?php echo date('Y', $timestamp);?></span></small>
    </div>
    <div class="info">
        <strong title="<?php echo $eventName;?>"><?php 
            if ($eventName != '') {
               $eventNameLength = strlen($eventName);
               if($eventNameLength > 38) {
                   echo substr($eventName,0,35).'...';
               }
               else {
                   echo $eventName;
               }
            } ?>
        </strong>
        <?php if ($articleUrl != '') { ?> 
            <a style="color:#fff;text-decoration: underline;" target="_blank" onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'calendar_article_clicked');" href="<?=$articleUrl?>">View More</a> 
        <?php } ?> 
    </div>
</li>
<?php } ?>