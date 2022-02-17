<?php
	if($empty_compares != $compare_count_max)
	{
	?>
        <tr>
        	<td colspan="2" class="compare-title"><h2>Interested in this course?</h2></td>
        </tr>
        <tr>
		
		<?php
	    if($compare_count <= $compare_count_max)
	    {
		$j = 0;
		$shortlistedCoursesOfUser = array();
		if(isset($validateuser[0]['userid'])) {
		$shortlistedCoursesOfUser =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$validateuser[0]['userid']); 
		}
		foreach($courseIdArr as $key => $courseId)
		{
		    $j++;
		    $course = $courseObjs[$courseId];
		    ?>
			<td class="<?php echo (($j<$compare_count_max)?'border-right':'');?>" style="padding:25px 10px;">
				<!----shortlist-course---->
				
				<?php 
				if(in_array($courseId, $shortlistedCoursesOfUser)){
					$class = 'sprite shortlisted-star';
					$Shortlist = 'Shortlisted';
				}else{
					$class = 'sprite shortlist-star';
					$Shortlist = 'Shortlist';
				}
				?>
				<div class="side-col short-list-box" id="shortlistDiv<?php echo $courseId;?>" onclick="var customParam = {'shortlistCallback':'shortlistCallbackComparePage', 'shortlistCallbackParam':{}, 'trackingKeyId':'<?php echo $shortlistTrackingPageKeyId;?>', 'pageType':'mobileComparePage'}; myShortlistObj.checkCourseForShortlist('<?php echo $courseId;?>', customParam); event.preventDefault(); event.stopPropagation();">
			        <span class="<?php echo $class;?> <?php echo 'allChkShortlisted'.$courseId;?>" id="shortlistedStar<?php echo $courseId;?>"></span>
			        <span id="shortlistedText<?php echo $courseId;?>" class="<?php echo 'allChkShortlistedText'.$courseId;?>"><?php echo $Shortlist;?></span>
			    </div>
				
				<!-----end-shortlist------>	
			</td>
	<?php   }
	
		if($j < $compare_count_max)
		{
		    for ($x = $j+1; $x <=$compare_count_max; $x++)
		    {
			?>
			<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
			<?php
		    }
		}
	    }?>
        	
        </tr>
	<?php
	}
	?>