<?php if(is_array($examPrepTip) && $totalNumRowsPrep >0){ foreach($examPrepTip as $examPrepTip){
	
	$blogTitle = new tidy();
        $blogTitle->parseString($examPrepTip['blogTitle'],array('show-body-only'=>true),'utf8');
        $blogTitle->cleanRepair();
	
	$summary = new tidy();
        $summary->parseString($examPrepTip['summary'],array('show-body-only'=>true),'utf8');
        $summary->cleanRepair();

	$titleCharLimit = 16;
	$testPrepTitle = html_escape($examPrepTip['blogTitle']);
	$testPrepTitle = (strlen($testPrepTitle) > $titleCharLimit ?  substr($testPrepTitle,0,$titleCharLimit)."..." : $testPrepTitle);

?>
<li style="cursor:pointer;" onClick="window.location = '<?=$examPrepTip['url']?>';trackEventByGAMobile('MOBILE_PREPARATION_TIPS_EXAMPAGE');">
      <div class="clearfix" style="margin-bottom:8px;">
		 <img src="<?php echo ($examPrepTip['blogImageURL'] !='') ? MEDIA_SERVER.$examPrepTip['blogImageURL'] : '/public/mobile5/images/defaut-mobile-image-prep-tip.jpg';?>" width="87" height="55" alt="<?=substr($testPrepTitle,0,20)?>">
		 <div class="result-info-title">
		    <strong><?=$testPrepTitle ?></strong>
		    <span><?php echo date('M d, Y',strtotime($examPrepTip['lastModifiedDate']));?></span>
	     </div>
	  </div>
	 <div class="clearfix"><?php echo substr(html_entity_decode($summary),0,100);?>, <a href="javascript:void(0);">Read More...</a></div>
      </li>
<?php }}else{?>
<li>
    <p style="font-size:14px; color: #666;">Nothing interesting here! <br> Go to <a style="cursor:pointer;" href="<?php echo $home['url'];?>"><?php echo $examName;?> homepage</a>.</p>
</li>
<?php }?>
