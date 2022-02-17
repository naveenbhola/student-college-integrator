<?php 
if($compare_count <= $compare_count_max)
{
?>
    <tr id="row8_H">
		<td colspan="2" class="compare-title"><h2>Recognition</h2></td>
	</tr>
	<tr id="row8_C" align="center">
    	<?php
        $k=1;
    	foreach($compareData['recognition'] as $courseId => $recoData){?>
    	<td class="<?php echo ($k<$compare_count_max)?'border-right ':''; if(empty($recoData)){ echo "verticalalign";}?>">
    	<?php if(!empty($recoData)){
    			foreach ($recoData as $key1 => $value) {?>
    				<div class="college-rank">
                       <p class="recognition"><?php echo $value; ?></p>
                     </div>
    			<?php } 
            }else{ ?>
    			<div class="college-rank"><p class="recognition">--</p></div>
            <?php 
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