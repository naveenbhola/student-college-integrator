<section class="college-head clearfix">
        	<a href="<?php echo SHIKSHA_HOME.'/mba/resources/ask-current-mba-students'; ?>" title="Back" class="msprite bck-icn"></a>
            <div class="college-title-box">
            	<p><?php echo $instituteName;?>, <span class="font-12"><?php echo $cityName;?></span></p>
                <div class="collge-title">
                        <a href="<?=$courseURL?>" class="flLt font-11" style="width:60%;"> <?php echo $courseName;?></a>
                    <?php if(is_array($courseReviews[$courseIdforHeader]) && !empty($courseReviews[$courseIdforHeader])) { ?>
                <div class="rating-sec flRt">
                    	Alumni Rating : <span><?php echo $courseReviews[$courseIdforHeader]['overallRating'] ?><strong>/5</strong></span>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                </div>
            </div>
        </section>
    	