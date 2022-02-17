<?php
$this->load->view('/mcommon/header');
?>
<div id="head-sep"></div>
<div id="head-title">
	<h4>Select Location</h4>
    <span>&nbsp;</span>
</div>
<?php
$total_count = count($locations);
if($total_count%2 == 0) {
	$i = intval($total_count/2);
} else {
	$i = intval($total_count/2) + 1;
}
$chunk_array = array_chunk($locations,$i,true);
$location_part1 = $chunk_array[0];
$location_part2 = $chunk_array[1];
?>
<div id="content-wrap">
	<div id="contents" style="width:100%;display:block;">
    	<ul id="location-cont" style="float:left;width:36%;">
	    <?php foreach($location_part1 as $city_id=>$city_name):?>	
            <li><a href="/search/showSearchHome?<?php echo 'more_city_id='.$city_id.'&more_city_name='.base64_encode($city_name);?>"><?php echo $city_name;?></a></li>
	    <?php endforeach;?>	
        </ul>
	<ul id="location-cont" style="float:left;width:50%;">
	    <?php foreach($location_part2 as $city_id=>$city_name):?>	
            <li><a href="/search/showSearchHome?<?php echo 'more_city_id='.$city_id.'&more_city_name='.base64_encode($city_name);?>"><?php echo $city_name;?></a></li>
	    <?php endforeach;?>	
        </ul>
    </div>
<?php $this->load->view('/mcommon/footer');?>
