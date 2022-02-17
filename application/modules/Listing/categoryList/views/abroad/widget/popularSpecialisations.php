<?php if(count($popularSubcats['linkData'])>0){ ?>
<div class="SubcatLinking">
    <div class="subcatWidget">
        <p class="boldtitl"> Popular specialisations for <?php echo str_ireplace(' Colleges','',str_replace("in All Countries"," Abroad",$popularSubcats['widgetTitle'])); ?></p>
        <div class="innerWidget clearfix">
        <?php foreach($popularSubcats['linkData'] as $popularSubcatLink){ ?>
            <div class="setSide">
            <a class="gaTrack" gaParams="ABROAD_CAT_PAGE,popularSpecialisations" href="<?php echo $popularSubcatLink['url']; ?>"><?php echo str_replace("in All Countries"," Abroad",$popularSubcatLink['title']); ?></a>
            </div>
        <?php } ?>
        </div>
    </div>
</div>
<?php } ?>