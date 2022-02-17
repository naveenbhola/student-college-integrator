<?php
$this->load->view('/mcommon/header');
$locations_parent = $dataobject->getLocationFacets();
$temp_array = array();
$temp_array1 = array();
foreach ($locations_parent as $key=>$value) {
	if($key > 1) {
		$element = "<a href='".$value['url']."'>".$value['name']." (".$value['value'].")</a>";
		$temp_array[] = $element;
	} else {
		$element = "<a href='".$value['url']."'>".$value['name']."</a>";
		$temp_array1[] = $element;
	}
}
$final_array = array_merge($temp_array1,$temp_array);
unset($locations_parent['1']);
?>
<div id="head-sep"></div>
<?php if(count($temp_array)>0) :?>
<div id="head-title">
	<h4>Select Location</h4>
    <p class="loc-list">
        <?php echo implode("<strong>|</strong>", $final_array)?>
    </p>
    <span>&nbsp;</span>
</div>
<?php endif;?>

<div id="content-wrap">
	<?php $k=0;foreach($locations_parent as $parent):?>
	<div id="contents">	
		<div class="loc-heading"><?php echo $parent['name']." "."(".$parent['value'].")";?></div>
		<?php 
			$total_count = count($parent['cities']);
			if($total_count%2 == 0) {
				$i = intval($total_count/2);
			} else {
				$i = intval($total_count/2) + 1;
			}
			$chunk_array = array_chunk($parent['cities'],$i,true);
			$location_part1 = $chunk_array[0];
			$location_part2 = $chunk_array[1];
		?>
		<ul id="location-cont" style="float:left;width:36%;">
		<?php foreach ($location_part1 as $city_id=>$city):?>
			<li><a href="<?php echo $city['url'];?>"><?php echo $city['name']." "."(".$city['value'].")";?></a></li>
			<?php $k++;endforeach;?>
		</ul>
		<ul id="location-cont" style="float:left;width:50%;">
		<?php foreach ($location_part2 as $city_id=>$city):?>
			<li><a href="<?php echo $city['url'];?>"><?php echo $city['name']." "."(".$city['value'].")";?></a></li>
			<?php $k++;endforeach;?>
		</ul>
	</div>
	<?php endforeach;?>
<?php $this->load->view('/mcommon/footer',array('total_results'=>$k,"device_type"=>$device_type));?>    
