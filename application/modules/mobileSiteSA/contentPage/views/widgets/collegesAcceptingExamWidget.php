</div><!--this closing div will close main content div and will start at the end again to maintain design-->
<div class="mid-exm-widget clearfix">
<div class="newExam-widget">
	<h2>
			<div class="widget-head"><p>Colleges accepting <?php echo $exam;?><i class="sprite blue-arrw"></i></p></div>
    </h2>
    <ul>
		<?php
			$count=1;
			foreach($widgetData[$exam] as $key=>$val)
			{
				if($count==count($widgetData[$exam])){
				   $class = "last";
				}
				$count++;
			?>	
    	<li class="<?php echo $class;?>">
        	<i class="sprite examClg-icon"></i>
            <div class="examClg-link"><a href="<?php echo $val['url'];?>"><?php echo htmlentities($val['title']);?></a><br><span><?php echo $val['totalCount'].(($val['totalCount']>1)? " colleges ":" college");?></span></div>
        </li>
		<?php } ?>
    </ul>
</div>
</div>
<!--we have closed div at beginning of the file so we are opening it again to maintain design-->
<div class="newExam-page">