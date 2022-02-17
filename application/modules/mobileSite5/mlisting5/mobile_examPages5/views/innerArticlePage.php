<?php if(is_array($examArticle) && $totalNumRowsArticle >0){ foreach($examArticle as $examArticle){
	
	$blogTitle = new tidy();
        $blogTitle->parseString($examArticle['blogTitle'],array('show-body-only'=>true),'utf8');
        $blogTitle->cleanRepair();
	
	$summary = new tidy();
        $summary->parseString($examArticle['summary'],array('show-body-only'=>true),'utf8');
        $summary->cleanRepair();
?>
<li style="cursor:pointer;" onClick="window.location = '<?=$examArticle['url']?>';trackEventByGAMobile('MOBILE_ARTICLE_EXAMPAGE');">
    <strong><?php echo html_entity_decode($blogTitle);?></strong>
    <span><?php echo date('M d, Y',strtotime($examArticle['lastModifiedDate']));?></span>
    <p><?php echo substr(html_entity_decode($summary),0,100);?>, <a href="javascript:void(0);">Read More...</a></p>
</li>
<?php }}else{?>
<li>
    <p style="font-size:14px; color: #666;">Nothing interesting here! <br> Go to <a style="cursor:pointer;" href="<?php echo $home['url'];?>"><?php echo $examName;?> homepage</a>.</p>
</li>
<?php }?>