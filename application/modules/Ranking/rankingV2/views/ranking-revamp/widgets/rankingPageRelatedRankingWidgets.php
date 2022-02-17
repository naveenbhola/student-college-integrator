<?php 
	$citySelected = false;
	if($widgetType  == 'location') { 
		$widgetPosition= 'last';
	}else{
		$widgetPosition= '';												
	}	
?>

<div class="widget-box clear-width <?php echo $widgetPosition ?>">
	<h3><?=$widgetHeadline?></h3>
	<div class="scrollbar1 relatedRanking" style="padding:0 0 8px 0;">
		<div class="scrollbar" style="margin-right: 6px;" >
			<div class="track">
				<div class="thumb"></div>
			</div>
		</div>
		<div class="viewport newRecommendToCrwl" style="height: 105px;">
			<div class="overview" style="width:98%">
				<?php if(isset($widgetType)  == 'location') { ?>
					<div class="city-state-col flLt">
						<strong>Cities</strong>
						<?php if(!empty($cityFilters)){ ?>
							<ul class="rnk-widget-list">
								<?php
									foreach($cityFilters as $filter){
										$title 		= $filter->getName();
										$url 		= $filter->getURL();
										$isSelected = $filter->isSelected();
										if($isSelected == true && $useCityFilter){
											$citySelected = true;
										} else { 
								?>
											<li>
												<a href='<?php echo $url; ?>'><span><?php echo $title; ?></span></a>
											</li>
								<?php 
										}
									}
								?>									
							</ul>
						<?php } ?>
					</div>
					<?php  if(count($stateFilters)>1){ ?>
						<div class="city-state-col flRt">
							<strong>States</strong>
							<ul class="rnk-widget-list">
								<?php
									foreach($stateFilters as $filter){
										$title 		= $filter->getName();
										$url   		= $filter->getURL();
										$isSelected = $filter->isSelected();
										if($isSelected != true){ 
								?>
											<li>
												<a href='<?php echo $url; ?>';><span><?php echo $title; ?></span></a>
											</li>
								<?php 
										}
									}
								?>									
							</ul>
						</div>
					<?php } ?>
				<?php } else {  ?>
					<?php if(!empty($filterResult) &&  $showFilters != false){ ?>
						<ul class="rnk-widget-list">
							<?php
								foreach($filterResult as $filter){
									if(is_object($filter)){
										$title 		= $filter->getName();
										$url   		= $filter->getURL();
										$isSelected = $filter->isSelected();
										if($isSelected != true){
							?>
											<li class='flLt'>
												<a href='<?php echo $url; ?>'><span><?php echo $title; ?></span></a>
											</li>
							<?php
										}
									}else{
										foreach($filter as $elem){
											$title 		= $elem['name'];
											$url 		= $elem['url'];
								?>
											<li class='flLt'>
												<a href='<?php echo $url; ?>'><span><?php echo $title; ?></span></a>
											</li>
								<?php
										}
									}
								}
							?>
						</ul>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>