    <div class="Reglayer-bg"></div>

    <?php if(!empty($extraDivParam)) { ?>
    	<div class="extraDiv" style="display: none;">
    <?php } ?>

        <div class="Reg-layer">
        	<div class="db-broucher ">
                <div class="db-sucess" style="" >
                    <?php if($action == 'shortlist') { ?>
                   	    <h1 style="">Shortlist Successful</h1>
                    <?php } else { ?>
                        <h1 style="">Download Successful</h1>
                        <p style="">Thank you for your request. The brochure for <strong style=""><?=$courseName;?></strong> has been successfully mailed to your registered email.
                        </p> 
                    <?php } ?>
                    <?php 
                        if(!empty($customMsgText)){
                            ?>
                            <div class="higlightedBox">
                                <?php echo $customMsgText; ?>
                            </div>   
                            <?php
                        }
                    ?>
                </div>
                <?php if(!$zeroRecommendations){ ?>
             	    <p class="text-clear">Students who showed interest in this course also looked for</p>
             	<?php }
                $this->load->view('tupleList'); ?>
            </div>
        </div>

        <a class="regClose" href="javascript:void(0);" onclick="eBrochureCloseButtonClick();">&times;</a>

    <?php if(!empty($extraDivParam)) { ?>
    	</div>
    <?php } ?>

</div><!--DONT REMOVE THIS COMMENT. This is the div close for TUPLE LIST. -->