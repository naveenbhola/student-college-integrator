<div class="dhl-steps">
     	<h2 class="dhl-txt">Student testimonials </h2>
        <ul class="test-list">
            <?php
                foreach ($studentTestimonialsForShipment as $key => $studentTestimonial) { ?>
                    <li>
                        <div class="test-det">
                            <i class="dhl-sprite dhl-coma"></i>
                            <p><?php echo $studentTestimonial['description'];?></p>
                            
                        </div>
                        <div class="test-usr">
                                <div class="tst-img"><img src="<?php echo IMGURL_SECURE.$studentTestimonial['studentImage'];?>"></div>
                                <div class="tst-info">
                                    <strong><?php echo $studentTestimonial['studentName'];?></strong>
                                    <p><?php echo $studentTestimonial['currentStage'];?></p>
                                </div>
                            </div>
                    </li>
            <?php    
                } ?>
        </ul>
    </div>