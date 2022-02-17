<div class="thnks-page">
         <div class="thanku-widget">
            <strong class="thnks-title"> Thank you!</strong>
            <p class="thnku-info">Thank you for sharing your details. We will share your information with a trusted loan provider who will get in touch with you.</p>
            <span>Please save your unique application id for all future reference: <?php echo $applicationNumber; ?></span>
         </div>
    <?php
    if(!empty($popularEducationLoanContents))
    {
        ?>
        <div class="art-suggestn">
            <p>Meanwhile, you can explore other articles related to your loan queries</p>
            <ul>
            <?php
            foreach ($popularEducationLoanContents as $popularEducationLoanContent)
            {
                ?>

                    <li>
                        <div class="art-info">
                            <p><a href="<?php echo $popularEducationLoanContent['contentUrl'];?>"><?php echo htmlspecialchars(truncate($popularEducationLoanContent['strip_title'],90,'...',false,false));?></a></p>
                            <span><?php echo $popularEducationLoanContent['viewCount'];?> views</span>
                        </div>
                    </li>
                <?php
            }
            ?>
            </ul>
        </div>
        <?php
    }
    ?>
      </div>
