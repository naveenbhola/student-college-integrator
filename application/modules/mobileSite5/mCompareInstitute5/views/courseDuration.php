<?php 
if($compare_count <= $compare_count_max)
{
?>
    <tr id="row8_H">
		<td colspan="2" class="compare-title"><h2>Course Duration</h2></td>
	</tr>
	<tr id="row8_C" align="center">
    	<?php
        $k=1;
    	foreach($courseIdArr as $courseId){
            $duration = $compareData['courseDuration'][$courseId];
            ?>
    	<td class="<?php echo ($k<$compare_count_max)?'border-right ':''; if(empty($duration)){ echo "verticalalign";}?>">
    	<?php if(!empty($duration)){
            ?>
    				<div class="college-rank">
                       <p class="recognition"><?php echo $duration['value'].' '.ucfirst($duration['unit']); ?></p>
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