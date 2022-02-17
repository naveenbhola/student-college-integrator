<div class="location-layer locationDropdownLayer" style="display: none;top:36px;">
	<div class="loc-col flLt">
		<div class="scrollbar1 filtersOverlay">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb"></div>
				</div>
			</div>
			<div class="viewport" style="height: 230px; overflow: hidden;">
				<div class="overview">
					<div class="exam-list">
                            <?php if(!empty($cityFilters)){ ?>
                                <ul>
                                <?php
                                    foreach($cityFilters as $filter){
                                        $title      = $filter->getName();
                                        $url        = $filter->getURL();
                                        $isSelected = $filter->isSelected();
                                        if($isSelected == true && $useCityFilter){
                                            $citySelected = true;
                                            } else { ?>
                                            <li>
												<a href='<?php echo $url; ?>' label='filter-location' title='<?=$title?>'> <?php  if($title=='All'){ echo 'All (Locations)'; }else{ echo $title; } ?> </a>
											</li>
                                        <?php }
                                    }?>    
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </div>
    </div>
    <div class="loc-col flRt">
        <div class="scrollbar1 filtersOverlay">	
                    <div class="scrollbar">
                        <div class="track" >
                            <div class="thumb"></div>
                        </div>
                    </div>
                    <div class="viewport" style="height:230px;overflow:hidden">
                        <div class="overview">
                             <div class="exam-list">
                             <?php if(!empty($stateFilters)){ ?>
                                <ul>
                              <?php
                                    foreach($stateFilters as $filter){
                                        
                                        if(in_array($filter->getId(),array(134))) {
                                            continue;   //Skip chandigarh from state filters 
                                        }

                                        $title      = $filter->getName();
                                        $url        = $filter->getURL();
                                        $isSelected = $filter->isSelected();
                                        if($isSelected == true && $citySelected != true){
											
                                        } else { ?>
                                            <li>
												<a href='<?php echo $url; ?>' label='filter-location' title='<?=$title?>'><?php echo $title; ?></a>
											</li>
                                        <?php }
                                    }?>
                                </ul>
                                <?php } ?>
                    </div>
                        </div>
                    </div>
                </div>
    </div>
   </div>