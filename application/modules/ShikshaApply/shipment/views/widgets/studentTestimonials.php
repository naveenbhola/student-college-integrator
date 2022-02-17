<div class="dhl-sec pd-20">
    <h2 class="stu-ts">Student testimonials</h2>
    
    <ul class="test-list">
        <?php
            foreach ($studentTestimonialsForShipment as $key => $studentTestimonial) { ?>
                <li>
                    <div class="test-det">
                        <i class="big-coma-icn"></i>
                        <p><?php echo $studentTestimonial['description'];?></p>

                    </div>
                    <div class="test-usr">
                            <div class="tst-img"><img src="<?php echo $studentTestimonial['studentImage'];?>"></div>
                            <div class="tst-info">
                                <span><?php echo $studentTestimonial['studentName'];?></span>
                                <p><?php echo $studentTestimonial['currentStage'];?></p>
                            </div>
                        </div>
                </li>
        <?php    
            } ?>
    </ul>
</div>
