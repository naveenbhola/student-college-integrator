    <?php $examFilterCount = count($filters['exam']); 

if($examFilterCount > 1) { ?>
    <section class="clear__float btm__10">
        <h2 class="f16__semi clr__1">Related Rankings</h2>
        <article class="top__10 top__clgs">
            <h3 class="f14__semi clr__1"><?=$interlinkingWidgetHeading['exam']?></h3>
            <ul class="rl__links clear__float top__5">
                <?php foreach($filters['exam'] as $key => $examFilter) {
                    if($key > 6) break; ?>
                    <li><a href="<?=$examFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$examFilter->getName();?></a></li>
                <?php } ?>
                <li>
                    <?php if($examFilterCount > 7) { ?>
                        <a href="javascript:void();" class="ranking__btns __transbtn top__5 examViewAll">View All</a>
                    <?php } ?>
                </li>
            </ul>
        </article>
    </section>
<?php } ?>