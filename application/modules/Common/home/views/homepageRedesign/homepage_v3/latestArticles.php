<div class="fltryt articleLatest">
    <div>
        <h2>Latest Updates</h2>
        <ul class="latestUpdateList">
        <?php
        $i=0;
        foreach ($latestArticles as $key => $value) {
            $class='';
            if($i == 0){
                $class= ' class="first"';
            }
            elseif($i == $numberOfArticlesDisplayed-1){
                $class= ' class="last"';
            }
            $url = SHIKSHA_HOME.$value['url'];
        ?>
            <li<?php echo $class;?>>
                <a href = <?php echo $url; ?>><?php echo strlen($value['blogTitle'])>73 ? substr($value['blogTitle'],0,70).'...' : $value['blogTitle']; ?></a>
                <?php if($i != $numberOfArticlesDisplayed-1){ ?>
                <p class="smal-sep-line">
                    <span></span>
                </p>
                <?php } ?>
            </li>
        <?php $i++;} ?>
        </ul>
    </div>
</div>