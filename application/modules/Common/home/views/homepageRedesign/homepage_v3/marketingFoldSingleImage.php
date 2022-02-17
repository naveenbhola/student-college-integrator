<div class="singleImage">
    <img class="lazy" data-original="<?php echo MEDIA_SERVER.$data[0]['imgURL']; ?>" alt="" title="" width="512" height="288" />
    <div class="img-caption">
        <p>
            <?php echo $data[0]['header'];?>
        </p>
        <?php if($data[0]['targetURL'] != ''){ ?>
        <a href="<?php echo $data[0]['targetURL'];?>" target="_blank" class="tertiaryButton readmore">READ MORE</a>
        <?php } ?>
    </div>
</div>