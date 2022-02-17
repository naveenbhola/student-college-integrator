<?php if(!empty($data)){?>
<div class="fltryt GRATITUDE">
    <div class="speaker-sec">
        <h2>WORDS OF GRATITUDE</h2>
        <?php $i=1; foreach ($data as $key => $testimonial) {?>
        <div class="speaker-con">
            <div class="fltlft avatar-figure">
                <img class="avatar lazy" data-original="<?php echo MEDIA_SERVER.$testimonial['image_url']; ?>" />
            </div>
            <div class="fltryt avatar-caption" lang="en">
                <p class="tesName">
                    <strong><?php echo $testimonial['name']; if($testimonial['designation'] != ''){ ?> <b>|</b><?php } ?></strong>
                    <span><?php echo $testimonial['designation'];?></span>
                </p>
                <p class="caption-main">
                    <?php echo $testimonial['testimonial'];?>
                </p>
            </div>
            <div class="clr"></div>
        </div>
        <?php if($i != $testimonial['totalTestimonials']){ ?>
        <div class="seprator"></div>
        <?php } $i++; } ?>
    </div>
</div>
<?php } ?>