<?php 
			$listing = $course;
            $locations = $locations?$locations:$listing->getLocations();
            if(count($locations) <= 1){
                        return "";
            }
            $loctionsWithLocality = array();
            $otherLocations = array();
            foreach($locations as $location){
                   if($location->getLocality() && $location->getLocality()->getName()){
                            $city = $location->getCity();
                            $loctionsWithLocality[$city->getId()][] = $location;
                   }else{
							$otherLocations[] = $location;
                   }
            }
?>
<?php if(count($loctionsWithLocality)!=0 || count($otherLocations)!=0){ ?>
<div class="round-box">
	<div class="course-details">
        <div class="inst-desc"> 
            <h2><?php 
					echo html_escape($listing->getName());?> is available at the following branches</h2>
        </div>
       <?php if(count($loctionsWithLocality) > 0){ ?>
		   <div id = 'select box'>
				
					<?php 
						foreach($loctionsWithLocality as $cityGroup){ 
						?>
						<select class = 'select-field' onchange="redirect_location(value)";>
							 <optgroup label="<?php echo $cityGroup[0]->getCity()->getName();?>">
				<?php	
					
					foreach($cityGroup as $key=>$location){
						$selected = '';
						$localityId = $location->getLocality()?$location->getLocality()->getId():0;
						if($_REQUEST['locality'] == $localityId){
							$selected = 'selected';
							}
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=".$location->getLocality()->getId();
					$listing->setAdditionalURLParams($additionalURLParams);?>
					<option value ="<?=$listing->getURL()?>" <?=' '.$selected ?>><?=$location->getLocality()->getName()?></option>;
				<?php }?>
				</optgroup>
				</select>
		<?php echo "</p>";}
		echo "</div>";}?>
       <?php if(count($otherLocations) > 0){ ?>
		   <p>
		   <div id = 'select box' >
				<select class = 'select-field' onchange="redirect_location(value)";>
					<optgroup label="Other Cities">
					<?php 	foreach($otherLocations as $key=>$location){
							$selected_2 = '';
							if($_REQUEST['city'] == $location->getCity()->getId())
							{
									$selected_2 = 'selected';
							}
						$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=";
						$listing->setAdditionalURLParams($additionalURLParams);?>
						<option value ="<?=$listing->getURL()?>" <?= ' '.$selected_2 ?> ><?=$location->getCity()->getName()?></option>
					<?php }?>
					</optgroup>
				</select>
			</div>
		</p>
		<?php }?>
        
    </div>
</div>
<?php } ?>
<script>
	function redirect_location(url){
		if(url)
			window.location = url;
	}
	</script>

