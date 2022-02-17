<?php
// $articleStreamUrl = ($blogInfo[0]['blogType'] == 'news') ? SHIKSHA_HOME.'/news' : $this->config->item('articleUrl'); 
if(count($relatedBlogs) > 0){
    $blogCount = 0;
    ?>
<section>
        <div class="color-1 no-pad">
            <h2 class="color-1 f16 heading-gap font-w6">Related Articles</h2>
            <div class="color-w art-crd">
                <?php foreach($relatedBlogs  as $key=>$blogData) {
                    $blogUrl = addingDomainNameToUrl(array('domainName' => SHIKSHA_HOME ,'url' => $blogData['url']));    
                    $blogCount++;
                    if($blogCount>4)
                    break;
                ?>
                    <div class="tupple-wrap">
                        <h3 class="font-w6 f16 "><a href="<?php echo getAmpPageUrl('blog',$blogUrl)?>" class="f16"><?=$blogData['blogTitle'];?></a></h3>
                        <?php if($blogData['viewCount'] > 0):?>
                        <div class="color-9 f14" >
                            <?=$blogData['viewCount'];?> view<?php if($blogData['viewCount']>1) echo s;?>
                        </div>
                        <?php endif;?>
                    </div>
                <?php } ?>
            </div>
        </div>
</section>
<?php
}
?>