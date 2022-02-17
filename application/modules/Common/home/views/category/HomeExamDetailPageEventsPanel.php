<?php
    if(is_array($examEvents) && count($examEvents) > 0){
        $examEventsCount = count($examEvents);
?>
<div class="raised_lgraynoBG">
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_lgraynoBG">
	    <div class="pd_lft_rgt">
		    <div>					
			    <div class="lineSpace_5">&nbsp;</div>
				<div class="OrgangeFont bld fontSize_13p">Important Dates</div>
				<div class="lineSpace_10">&nbsp;</div>
                <?php
                    $counter = 0;
                    foreach($examEvents as $event) {
                        $eventStartDate = $event['start_date'];
                        $eventEndDate = $event['end_date'];
                        $eventUrl = $event['url'];
                        $eventName = $event['title'];
                        $counter++;
                ?>
				<div class="bld fontSize_12p"><?php echo $eventStartDate; ?> - <?php echo $eventEndDate; ?></div>
				<div>
                    <a href="<?php echo $eventUrl; ?>" title="<?php echo $eventName; ?>"><?php echo $eventName; ?></a>
                </div>
				<div class="lineSpace_10">&nbsp;</div>
                <?php
                    if($counter < $examEventsCount){
                ?>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
                <?php
                    }
                }    
                ?>
			</div>
		</div>
	</div>
  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?php
}
?>
