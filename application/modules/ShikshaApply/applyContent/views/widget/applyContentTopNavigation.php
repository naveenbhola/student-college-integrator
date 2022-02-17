<?php
if(!empty($topNavData['content_type_title']) && !empty($topNavData['links_data']))
{
    ?>
    <section class="apl-contnt-sec clearfix">
        <div class="apl-contnt">
            <h1 class="newApply-heading" itemprop="headline name">
               <?php echo $H1Title;?>
               </h1>
             
            <div class="headNav-wrap">
                <ul class="apply-headNav">
                    <?php
                    foreach ($topNavData['links_data'] as $linkContentId => $linkData) {
                        ?>
                        <li><a <?php echo ($linkContentId==$contentId?'class="active"':''); ?> href="<?php echo $linkData['url']; ?>"><?php echo htmlentities($linkData['label']); ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </section>
    <?php
}
?>