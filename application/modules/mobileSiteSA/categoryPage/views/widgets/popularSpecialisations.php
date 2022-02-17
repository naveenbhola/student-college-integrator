<?php if(count($popularSubcats['linkData'])>0){ ?>
<div class="subcatWidget">
    <div class="innerWidget">
        <p class="subTitl"> Popular specialisations for <?php echo str_ireplace(' Colleges','',str_replace("in All Countries"," Abroad",$popularSubcats['widgetTitle'])); ?></p>
        <ul>
        <?php foreach($popularSubcats['linkData'] as $popularSubcatLink){ ?>
            <li><a onclick="gaTrackEventCustom('ABROAD_CAT_PAGE','popularSpecialisations');" href="<?php echo $popularSubcatLink['url']; ?>"><?php echo str_replace("in All Countries"," Abroad",$popularSubcatLink['title']); ?></a></li>
        <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>