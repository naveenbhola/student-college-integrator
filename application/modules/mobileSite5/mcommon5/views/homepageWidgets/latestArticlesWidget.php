<?php if(!empty($latestArticles)){?>
<section class="artBnr">
    <div class="_container">
        <h2>Latest Updates</h2>
        <div class="_ask">
            <div class="LUpdate">
                <ul>
                <?php
                foreach ($latestArticles as $key => $value) {
                ?>
                    <li>
                        <a class="gala" href = "<?php echo SHIKSHA_HOME.$value['url']; ?>">
                            <?php echo htmlentities($value['blogTitle']); ?>
                        </a>
                    </li>
                <?php $i++;} ?>
                </ul>
            </div>
            <div class="button-container">
                <a id="AllUpd" href="<?php echo SHIKSHA_HOME; ?>/articles-all" class="trnBtn  noLink">View All Updates</a>
            </div>
        </div>
    </div>
</section>
<?php } ?>
