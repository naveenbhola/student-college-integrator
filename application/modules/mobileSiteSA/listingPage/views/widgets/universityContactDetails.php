<section class="detail-widget">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>Contact details</strong>
            <ul class="contact-detail-list" style="line-height:22px;">
                <?php
                    if(!empty($universityAddress)){
                ?>
                <li>
                    <i class="sprite contact-loc-icon"></i>
                    <div class="campus-details"><?=$universityAddress?></div>
                </li>
                <?php }
                    if(!empty($universityEmail)){
                ?>
                <li>
                    <i class="sprite contact-mail-icon"></i>
                    <div class="campus-details"><?=$universityEmail?></div>
                </li>
                <?php }
                    if(!empty($universityWebsite)){
                        if(strlen($universityWebsite) > 30){
                            $website = substr($universityWebsite,0,30).'...';
                        }else{
                            $website = $universityWebsite;
                        }
                ?>
                <li>
                    <i class="sprite contact-web-icon"></i>
                    <div class="campus-details">
                        <a href="<?=$universityWebsite?>" rel="nofollow" target="_blank" style="color: #333"><?=$website?></a>
                    </div>
                </li>
                <?php }?>
            </ul>
            <div class="clearfix"></div>
        </div>

    </div>
</section>