<?php
$cityFilters 	= $filters['city'];
$stateFilters 	= $filters['state'];
?>

<div id="overlayLocationHolder" class="change-loc-layer" onmouseover="this.style.display='';" onmouseout="this.style.display='none';" style="display:none;">
	<div class="heading">Institutes available in these locations.</div>
	<div class="city-box">
		<div class="city-cols">
			<strong>Cities in India</strong>
			<ul class="scroll-content" style="height:240px">
                <?php
                $allFilter = NULL;
                if(!empty($cityFilters)){
                    foreach($cityFilters as $filter){
                        if($filter->getName() == 'All'){
                            $allFilter = $filter;
                        }
                    ?>
                        <li><a uniqueattr="RankingPage/locationlayercityclick" href="<?php echo $filter->getURL();?>"><?php echo $filter->getName();?></a></li>
                    <?php
                    }			  
                } else {
                ?>
                    <li>No city information available.</li>
                <?php
                }
                ?>
			</ul>
		</div>
		<div class="city-cols last-cols">
			<strong>States in India</strong>
			<ul class="scroll-content" style="height:240px;">
			<?php
			if(!empty($stateFilters)){
				if(!empty($allFilter)){
				?>
					<li><a uniqueattr="RankingPage/locationlayerstateclick" href="<?php echo $allFilter->getURL();?>"><?php echo $allFilter->getName();?></a></li>	
				<?php
                }
				foreach($stateFilters as $filter){
				?>
					<li><a uniqueattr="RankingPage/locationlayerstateclick" href="<?php echo $filter->getURL();?>"><?php echo $filter->getName();?></a></li>
				<?php
				}			  
			} else {
			?>
			    <li>No state information available.</li>
			<?php
			}
			?>
			</ul>
		</div>
		<div class="clearFix"></div>
	</div>
</div>