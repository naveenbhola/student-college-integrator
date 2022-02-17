<div class="ImageWithCaption">
    <div class="secImg">
        <img class="lazy" data-original="<?php echo MEDIA_SERVER.$data[0]['imgURL']; ?>" alt="" title="" />
    </div>
    <div class="secDesc" lang="en">
        <h5>
        <?php if($data[0]['header'] != ''){?>
            <strong><?php echo $data[0]['header'];?> : </strong><?php } echo $data[0]['subHeader'];?>
        </h5>
        <p>
            <?php echo $data[0]['description'];?>
        </p>
        <?php if($data[0]['targetURL'] != ''){ ?>
        <div class="buttonContainer">
            <a href="<?php echo $data[0]['targetURL'];?>" target="_blank" class="primaryButton readmore">READ MORE</a>
        </div>
        <?php } ?>
    </div>

</div>