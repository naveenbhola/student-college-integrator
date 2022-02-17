<?php 
			$listing = $institute;
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
            <h2>
				 <a class="view-link" style="cursor: pointer" id = 'showHideIcn' onclick = show_hide('location_select_box') >See all branches of this institute</a>
			</h2>
        </div>
        <div id = 'location_select_box' <?php if(isset($_REQUEST['city']) && is_numeric($_REQUEST['city'])){?>style = 'display:block' <?php }else {?>style = 'display:none' <?php } ?>>
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
		<?php echo "</p>";}}?>
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
    </div>
</div>
<?php } ?>
<script>
	function redirect_location(url){
		window.location = url;
	}
	function show_hide(id){
		if(document.getElementById(id).style.display=='block'){
			document.getElementById('showHideIcn').style.backgroundImage = 'url(/public/mobile/images/view-icn.jpg)';
			document.getElementById(id).style.display = 'none';
		}
		else {
			document.getElementById(id).style.display = 'block';
			document.getElementById('showHideIcn').style.backgroundImage = 'url(/public/mobile/images/minus-icn.jpg)';
		}
		}
	</script>
<?php if(isset($_REQUEST['city']) && is_numeric($_REQUEST['city'])){ ?>
	<script> 
		document.getElementById('showHideIcn').style.backgroundImage = 'url(/public/mobile/images/minus-icn.jpg)';
	</script>
 <?php }?>
