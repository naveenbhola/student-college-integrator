               
<section class="content-wrap2  clearfix" style="margin-top:20px; margin-bottom:0 !important;">
	<h2 class="ques-title">Popular <?=$baseEntityName?> college comparisons on Shiksha</h2>
	<ul class="layer-list">
		<?php	
			foreach( $popularList as $popular)
			{   
				$c1Location =  ($popular['course1_location']) ? ','.' '.$popular['course1_location']:'';
				$c2Location =  ($popular['course2_location']) ? ','.' '.$popular['course2_location']:'';
				$displayText = $popular['course1_name'].$c1Location.' '.'vs'.' '.$popular['course2_name'].$c2Location;
				?>
				<li><a style="color:#006FA2" href="<?php echo SHIKSHA_HOME.'/resources/college-comparison'.'-'.str_replace(' ','-',strtolower($popular['course1_name'])).'-'.str_replace(' ','-',strtolower($popular['course1_location'])).'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course2_name'])).'-'.str_replace(' ','-',strtolower($popular['course2_location'])).'-'.$popular['course1_id'].'-'.$popular['course2_id'];?>"> 
					<?php echo $displayText;?>
				</a></li>
			<?php }		
		?>
	</ul>
</section>
            