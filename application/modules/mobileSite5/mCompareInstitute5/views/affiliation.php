<?php 
if($compare_count <= $compare_count_max)
{
?>
	<tr id="row7_H">
	<td colspan="2" class="compare-title"><h2>Course Status</h2></td>
	</tr>
	<tr id="row7_C" align="center">
	<?php 
			$k = $j = 0;
			foreach($courseIdArr as $courseId){
		    $k++;
		    if(is_array($compareData['courseStatus'][$courseId])){
		    	$courseSts = $compareData['courseStatus'][$courseId];
		    	$j++;
				?>
				<td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
				    <?php 
				    foreach ($courseSts as $courseStsVal) {
				    ?>
				    	<div class="college-rank">
				    		<?php if(count($courseSts) > 1) {?><i class="clg-rcgn"></i><?php } ?>
		                	<p class="recognition <?php if(count($courseSts) > 1) { ?>p-left<?php } ?>"><?php echo $courseStsVal?></p>
		                </div>
				    <?php 
				    }
				    ?>
				</td>
			<?php }else{
			?>
				<td class="verticalalign <?php echo ($k<$compare_count_max)?'border-right':'';?>">--</td>
			<?php
		    }
		}
	    ?>
	</tr>
<?php 
}
?>