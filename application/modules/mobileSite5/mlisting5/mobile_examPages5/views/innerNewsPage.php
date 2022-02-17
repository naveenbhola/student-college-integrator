<?php if(is_array($examNews) && $totalNumRowsNews >0){ $i=1;foreach($examNews as $examNews){
        $blogTitle = new tidy();
        $blogTitle->parseString($examNews['blogTitle'],array('show-body-only'=>true),'utf8');
        $blogTitle->cleanRepair();
	
	$summary = new tidy();
        $summary->parseString($examNews['summary'],array('show-body-only'=>true),'utf8');
        $summary->cleanRepair();
    ?>
<li style="cursor:pointer;" onClick="window.location = '<?=$examNews['url']?>';trackEventByGAMobile('MOBILE_NEWS_EXAMPAGE');">
    <strong><?php echo html_entity_decode($blogTitle);?></strong>
    <span><?php echo date('M d, Y',strtotime($examNews['lastModifiedDate']));?></span>
    <p><?php echo substr(html_entity_decode($summary),0,100);?>, <a href="javascript:void(0);">Read More...</a></p>
</li>
<?php }}else{?>
<li>
    <p style="font-size:14px; color: #666;">Nothing interesting here! <br> Go to <a style="cursor:pointer;" href="<?php echo $home['url'];?>"><?php echo $examName;?> homepage</a>.</p>
</li>
<?php }?>