<?php 
if($compare_count <= $compare_count_max)
{
?>
    <tr id="row8_H">
		<td colspan="2" class="compare-title"><h2>Accreditation</h2></td>
	</tr>
	<tr id="row8_C" align="center">
    	<?php
        $k=1;
    	foreach($courseIdArr as $courseId){
            $accrData = $compareData['accreditation'][$courseId];
            ?>
    	<td class="<?php echo ($k<$compare_count_max)?'border-right ':''; if(empty($accrData)){ echo "verticalalign";}?>">
    	<?php if(!empty($accrData)){
    			foreach ($accrData as $key1 => $value) {?>
    				<div class="college-rank">
                       <p class="recognition"><?php echo $value; ?></p>
                     </div>
    			<?php } 
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