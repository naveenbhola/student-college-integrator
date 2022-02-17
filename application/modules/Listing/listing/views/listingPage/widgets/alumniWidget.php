<div class="section-cont">
<?php 
$ratings = $institute->getRatingsJson();
if(count($ratings) == 0){
?>
<script>
	$('alumniWidget').style.display = 'none';
</script>
<?php
}else{
?>
<script>
	// $j('#alumniWidget').css('background','#FFFFFF');
	$('alumniWidget').style.background = '#FFFFFF';
</script>
<h4 class="section-cont-title">Alumni Speak</h4>
<ul class="alumini-ratings">
<?php
	$types = array('Placements' => 3,'Infrastructure / Teaching facilities' => 1,'Faculty' => 2,'Overall Feedback' => 4);
        $typewise_total = 0; 
	foreach($types as $type=>$typeId){
        if(!isset($ratings[$typeId]))continue;
		$finalFeedback = $ratings[$typeId]["r"];
		$totalFeedbackCount = $ratings[$typeId]["n"];
                if($typeId == 4){
                    $li_str = ' itemscope itemtype="http://data-vocabulary.org/Review-aggregate""';
                    $count_str = ' itemprop="count"';
                    $avg_str = ' itemprop="average"';
                    $best_str = ' itemprop="best"';
                    $rating_str = 'itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"';
                }
		if($finalFeedback > 0){
?>
<li <?=$li_str?>>    
    <label><a href="<?=$alumniTabUrl?>#alumnai-<?=($typeId)?>"><?=preg_replace('/\W.*/','',$type)?></a> <?=($totalFeedbackCount>0)?"<span style='color:#626262;font-size:11px;'$count_str>($totalFeedbackCount votes)</span>":""?></label>
    <div <?=$rating_str?>>	
    <?php
            for($i=0;$i<5;$i++){
                    if($i<$finalFeedback){
                            echo '<img src=/public/images/nlt_str_full.gif />';
                    }else{
                            echo '<img src=/public/images/nlt_str_blk.gif />';
                    }

            }
    ?> <span<?=$avg_str?>><?=$finalFeedback?></span>/<span<?=$best_str?>>5.0</span>
    </div>
</li>
<?php
		}
        $typewise_total +=$finalFeedback;
	}
?>
</ul>
<?php
}
?>
<?php if($typewise_total == 0):?> 
<script> 
$('alumniWidget').style.display = 'none';
</script> 
<?php endif;?> 
</div>
