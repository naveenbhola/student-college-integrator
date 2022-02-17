<?php if($counter==1){ ?>
<div class="exam-accrodian clearfix">
    <h1>Important Date</h1>
    <div class="calender-blocks">
    <ul>
<?php } ?>
        <li class="<?php echo $class; ?>" style="cursor:default;" >
                <div class="date clearfix">
                <big><?php echo date('d', $timestamp); ?></big>
                <small><?php echo date('M', $timestamp);?><br /><span><?php echo date('Y', $timestamp);?></span></small>
            </div>
            <div class="info">
                <strong><?php if ($eventName != '') echo $eventName; ?></strong>
                <?php if ($articleUrl != '') echo '<a style="color:#fff;" href="'.$articleUrl.'" onclick="trackEventByGAMobile(\'HTML5_IMPORTANT_DATE_ARTICLE_CLICKED\');">View More...</a>'; ?> 
            </div>
            <?php if($showUpcoming && $tagName == 'TODAY') { ?>
                <h2 class="badge2" style="background:#0065de;"><?php echo $tagName;?></h2>
            <?php } elseif($showUpcoming) { ?>
                <h2 class="badge2"><?php echo $tagName;?></h2>
            <?php }  ?>

        </li>
<?php if($counter==$maxCount){ ?>        
    </ul>
    </div>

</div>
<?php } ?>