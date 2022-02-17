<?php
$max = 0;
$j = 0;
$ck = 0;
$data = $compareData['alumniSalary'];
foreach($data as $key => $val)
{  	
	$courseId[] = $key;
    if($max < $val['AvgCTC'])
        $max = $val['AvgCTC'];
}
$courseId = array_unique($courseId);
if(empty($data[$courseId[0]]['AvgCTC']) && empty($data[$courseId[1]]['AvgCTC'])){?>
<script>
	var _aluminiSectionFlag = 1;
</script>
<?php }

foreach($courseIdArr as $courseId)
{
    $j++;
    if(!is_array($compareData['alumniSalary'][$courseId])){
    	?>
    	<td class="verticalalign <?php echo ($j<$compare_count_max)?'border-right':'';?>">--</td>
    	<?php
    	continue;
    }
    $percent = $data[$courseId]['AvgCTC']/$max*100;
    if($percent > 90 && $max!=$data[$courseId]['AvgCTC'])
	$percent = 90;
    ?>
    <td class="<?php echo ($j<$compare_count_max)?'border-right':'';?>" <?php echo (($data[$courseId]['AvgCTC']=='0')?'style="vertical-align:middle;"':'')?>>
	<?php
	if($data[$courseId]['AvgCTC']=='0')
	{
	    $ck++;
	?>
	    <div class="college-rank"><p class="recognition">--</p></div>
	<?php
	}else{
	?>
	<p class = "alignText"><?php echo round($data[$courseId]['AvgCTC'],2); ?><span> lacs</span></p>
	<div class="graph-bar">
		<div class="graph-percent" style="width:<?php echo $percent; ?>%"></div>
	</div>
	<p class="exp-row">2-5 years work experience</p>
	<?php
	}
	?>
    </td>
    <?php
}
if($j < $compare_count_max){
    for ($x = $j+1; $x <=$compare_count_max; $x++){
	?>
		<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
	<?php
    }
}
?>