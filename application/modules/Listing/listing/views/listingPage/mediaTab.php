<?php
	$this->load->view('listing/listingPage/listingHead',array('tab' => 'media', 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount));
	foreach($mediaData as $key=>$media){
		$count = 0;
		foreach($media as $m){
			$finalData[$key][$count] =  array('name'=>$m->getName(),'url'=>$m->getURL(),'thumb'=>$m->getThumbURL());
			$count++;
		}
	}
	$videoCount = count($mediaData['videos']);
	$photoCount = count($mediaData['photos']);
	if($videoCount == 0){
		$photoPerPage = min(6,$photoCount);
	}else{
		$photoPerPage = min(3,$photoCount);
	}
	if($photoCount == 0){
		$videoPerPage = min(6,$videoCount);
	}else{
		$videoPerPage = min(3,$videoCount);
	}
	$videoPages = ceil($videoCount/$videoPerPage);
	$photoPages = ceil($photoCount/$photoPerPage);
	$mediaTabUrlComponents = explode("?",$mediaTabUrl);
	$mediaTabUrl = $mediaTabUrlComponents[0];
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_MEDIA_PAGE',1);
?>

<div id="page-contents">
	<?php
	if(($videoCount + $photoCount) != 0){
	?>
	<div id="vid-col">
		<?php if($showDropDowns){ ?>
		<div style="width:40%;float:left">
		<select id="city" onchange="changeCity();" class="universal-select">
			<option value="">All Cities</option>
			<?php
				foreach($cityLocalityArray as $key=>$city){
					echo '<option value="'.$key.'">'.$city['cityName'].'</option>';
				}
			?>
		</select>
		</div>
		<div style="width:40%;float:left">
		<span id="localityWrapper">
			<input id="locality" type="hidden" value="" />
		</span>
		</div>
		<div class="clearFix spacer15"></div>
		<?php } ?>
		<div class="section-cont-title" style="margin-bottom:1px">
			<span class="flLt" id="currentItemName"></span>
			<div class="next-prev">
				<a href="#" class="sprite-bg prev" onclick="selectItem(currentItem-1); return false;"></a>
				<a href="#" class="sprite-bg next" onclick="selectItem(currentItem+1); return false;"></a>
			</div>
		</div>
		<div class="vid-block" id="currentItem"></div>
	</div>
	
	<div id="photo-col">
		<div class="section-cont" id="video-wrapper">
			<div class="section-cont-title">
				<span class="flLt">More Videos (<?=$videoCount?>)</span>
				
				<div class="photo-paging" id="video-paging">
					<strong>&laquo;</strong> Prev
					&nbsp;|
					<span>1-<?=$videoPerPage?> of <?=$videoCount?></span>
					|&nbsp;
					<?php if($videoPages > 1) { ?>
						<a href="#">Next <strong>&raquo;</strong></a>
					<?php
					}else{
					?>
						Next <strong>&raquo;</strong>
					<?php
					}
					?>
				</div>
			</div>
			<?php
				$j = 0;
				$start = 0;
				for($i=0;$i<$videoPages;$i++){
					if($i != 0){
						$display = 'none';
					}else{
						$display = '';
					}
			?>
			<div class="pL-7" id="videoPage<?=$i?>" style="display:<?=$display?>">
				<ul class="photo-vid-lists">
					<?php
						for($j=$start;$j<($videoPerPage+$start);$j++){
							if(!$mediaData['videos'][$j]){
								break;
							}
							if($j == 0 && $i == 0){
								$class = "active";
							}else{
								$class = '';
							}
					?>
					<li class="<?=$class?>" id="videos-<?=$j?>">
						<?php
							$name = $mediaData['videos'][$j]->getName();
							if(strlen($name) > 20){
								$name = substr($name,0,17)."...";
							}
						?>
						<label><?=$name?></label>
						<div class="thumb-box">
							<a href="#" onclick="selectItem(<?=$j?>,'videos');return false;">
								<img src="<?=$mediaData['videos'][$j]->getThumbURL()?>" width="123" height="100" alt="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().', Video '.($j+1);?>" title="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().', Video '.($j+1);?>"/>
								<span class="sprite-bg play-icn"></span>
							</a>
						</div>
					</li>
					<?php
						}
						$start = $j;
					?>
				</ul>
			</div>
			<?php
				}
			?>
		</div>
		
		<div class="section-cont"  id="photo-wrapper">
			<div class="section-cont-title">
				<span class="flLt">More Photos (<?=$photoCount?>)</span>
				
				<div class="photo-paging" id="photo-paging">
					<strong>&laquo;</strong> Prev
					&nbsp;|
					<span>1-<?=$photoPerPage?> of <?=$photoCount?></span>
					|&nbsp;
					<?php if($photoPages > 1) { ?>
						<a href="#" onclick="selectItem(<?=$photoPerPage?>,'photos');return false;">Next <strong>&raquo;</strong></a>
					<?php
					}else{
					?>
						Next <strong>&raquo;</strong>
					<?php
					}
					?>
				</div>
			</div>
			<?php
				$start = 0;
				for($i=0;$i<$photoPages;$i++){
					if($i != 0){
						$display = 'none';
					}else{
						$display = '';
					}
			?>
			<div class="pL-7" id="photoPage<?=$i?>" style="display:<?=$display?>">
				<ul class="photo-vid-lists">
					<?php
						for($j=$start;$j<($photoPerPage+$start);$j++){
							if(!$mediaData['photos'][$j]){
								break;
							}
							if($j == 0 && $i == 0 && $videoCount == 0){
								$class = "active";
							}else{
								$class = '';
							}
					?>
					<li class="<?=$class?>" id="photos-<?=$j?>">
						<?php
							$name = $mediaData['photos'][$j]->getName();
							if(strlen($name) > 20){
								$name = substr($name,0,17)."...";
							}
						?>
						<label><?=$name?></label>
						<div class="thumb-box"><a href="#" onclick="selectItem(<?=$j?>,'photos');return false;"><img width="123" src="<?=$mediaData['photos'][$j]->getThumbURL()?>" height="100" alt="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().', '.$name;?>" title="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().', '.$name;?>"/></a></div>
					</li>
					<?php
						}
						$start = $j;
					?>
				</ul>
			</div>
			<?php
				}
			?>
		</div>
		</div>
	</div>
	<?php }else{ ?>
	<h1>The photos & videos for this listing are no longer available.</h1>
	<?php } ?>
	<div class="desc-details-wrap" id="bottomWidget" style="width:685px"></div>
	<div class="clearFix spacer20"></div>
</div>
<?php
$this->load->view('listing/listingPage/listingFoot');
?>
<script>
	var data = <?php echo json_encode($finalData); ?>;
	var videoCount = <?=$videoCount?>;
	var photoCount = <?=$photoCount?>;
	var photoPerPage = <?=$photoPerPage?>;
	var videoPerPage = <?=$videoPerPage?>;
	var videoPages = <?=$videoPages?>;
	var photoPages = <?=$photoPages?>;
	var selectedType = 'videos';
	var currentItem = 0;
	var currentPage = new Array();
	currentPage['photos'] = 0;
	currentPage['videos'] = 0;
	if(videoCount == 0){
		$j("#video-wrapper").hide();
		selectedType = 'photos'; 
	}
	if(photoCount == 0){
		$j("#photo-wrapper").hide();
	}
	selectItem(currentItem);
<?php if($showDropDowns){ ?>
	var cityLocalityArray = <?=json_encode($cityLocalityArray,true)?>;
	function changeCity(){
		var city = $j('#city').val();
		var locality = $j('#locality').val();
		$j('#localityWrapper').html("");
		if(cityLocalityArray[city]){
			updateLocalityDiv(city);
		}else{
			changeLocality();
		}
	}
	function updateLocalityDiv(city){
		var html = "";
		html += '<select id="locality" onchange="changeLocality();" class="universal-select">';
		html += '<option value="">Select Locality</option>';
		html += '<option value="All">All</option>';
		var flag = 1; 
		$j.each(cityLocalityArray[city]['locality'],function(index,item){
			if(item['name'] == "All"){
				flag = 0;
				return true;
			}
			html += '<option value="'+index+'">'+item['name']+'</option>';
		});
		html += '</select>';
		if(!flag){
			html = "";
			changeLocality();
		}
		$j('#localityWrapper').html(html);
	}
	mediaTabUrl = '<?=$mediaTabUrl?>';
	function changeLocality(){
		var locality = $j('#locality').val() != undefined?$j('#locality').val():"";
		var city = $j('#city').val();
		window.location = mediaTabUrl + "?city=" + city + "&locality=" + locality + "&custommedia=1";
	}
	(function($j) {
	<?php
	if($_REQUEST['custommedia']){
	?>
		$j('#city').val(<?=$_REQUEST['city']?>);
		<?php if($_REQUEST['locality']) { ?>
			changeCity();
			$j('#locality').val('<?=$_REQUEST['locality']?>');
		<?php } ?>
	<?php
	}else{
	?>
		$j('#city').val("");
	<?php
	}
	?>
	})($j);
<?php } ?>
</script>
