<div data-enhance="false">
<section class="content-wrap clearfix">
        <article>
            <h2 class="wrap-title" style="padding:12px 8px 0;">Quick Links</h2>
            <ul class="quick-list">
                <?php foreach($quickLinksData as $key=>$url){?>
                <li><a href="<?= $url;?>"><?= $key;?></a></li>
                <?php }?>
            </ul>
        </article>
</section>
</div>