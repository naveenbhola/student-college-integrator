<?php 
$ratings = $institute->getRatingsJson();
if(count($ratings) > 0){
?>
    <div class="clearfix" id="alumniWidget">
	<p class="rating-col" style="width:99%">
	    <span>Alumni Rating:</span>
	    <?php
		    $types = array('Placements' => 3,'Infrastructure / Teaching facilities' => 1,'Faculty' => 2,'Overall Feedback' => 4);
		    $typewise_total = 0; 
		    foreach($types as $type=>$typeId){
			    if(!isset($ratings[$typeId]))continue;
			    $finalFeedback = $ratings[$typeId]["r"];
			    if($finalFeedback > 0){
				$typewise_total +=$finalFeedback;
			    }
		    }
		    $typewise_total = round($typewise_total/4);
		    for($i=0;$i<$typewise_total;$i++){
			echo '<i class="star"></i>';
		    }
		    echo '<span class="rate-num" style="margin-left:3px;">'.$typewise_total.'/5</span>';
	    ?>
	</p>
    </div>
    <?php if($typewise_total == 0):?> 
	<script> 
	$('#alumniWidget').hide();
	</script> 
    <?php endif;?>

<?php
}
?>