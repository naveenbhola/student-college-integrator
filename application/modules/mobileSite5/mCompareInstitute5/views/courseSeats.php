<?php 
if($compare_count <= $compare_count_max)
{
?>
    <tr id="row8_H">
		<td colspan="2" class="compare-title"><h2>Total Seats</h2></td>
	</tr>
	<tr id="row8_C" align="center">
    	<?php
        $k=1;
    	foreach($courseIdArr as $courseId){
            $totalSeats = $compareData['courseSeats'][$courseId];
            ?>
    	<td class="<?php echo ($k<$compare_count_max)?'border-right ':''; if(empty($totalSeats)){ echo "verticalalign";}?>">
    	<?php if(!empty($totalSeats)){
            ?>
    				<div class="college-rank"><?php if(count($totalSeats) > 1) {?><i class="clg-rcgn"></i><?php } ?>
                       <p class="recognition <?php if(count($totalSeats) > 1) { ?>p-left<?php } ?>"><?php echo $totalSeats; ?></p>
                     </div>
    			<?php  
            }else{ 
                echo '--';
            } ?>
		</td>
    	<?php
    	$k++;
    }
    ?>
    </tr>
    <?php 
}
?>