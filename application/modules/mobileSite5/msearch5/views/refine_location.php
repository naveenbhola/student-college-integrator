<?php
$dataobject = $search_lib_object;
$locations_parent = $dataobject->getLocationFacets();
$temp_array = array();
$temp_array1 = array();
$metroCitiesArray = array();
foreach ($locations_parent as $key=>$value) {
	if($key > 1) {
		$element = $value['name']." <br />(".$value['value'].")";
		$temp_array[] = $element;
	} else {
		$element = $value['name'];
		$temp_array1[] = $element;
	}
}
$final_array = array_merge($temp_array1,$temp_array);
$showLocation = false;

if(!empty($searchurlparams['country_id']) || !empty($searchurlparams['locality_id']) || !empty($searchurlparams['city_id']) || !empty($searchurlparams['zone_id'])){
	$showLocation = true;
}
unset($locations_parent['1']);
?>
<script>prevId=0;cityListCount=new Array();</script>
<div id='preferredStudyLocationLayer_<?php echo $regFormId; ?>'>
<div id='preferredStudyLocationLayerInner_<?php echo $regFormId; ?>'>
<div>
	<header id="page-header" class="clearfix">
        <div class="head-group">
            <a  href="javascript:void(0);" onclick="closeSecondOverlay()" ><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
            <h3>Location(s)</h3>
        </div>
    </header>

    <section class="content-wrap2 of-hide" style="margin-bottom:0">
    	<div id="autoSuggestRefine" class="search-option2" style="padding-top:12px">
            <div id="searchbox2">
            <span class="icon-search" aria-hidden="true"></span>
            <input id="search" type="text" placeholder="Enter location name" onkeyup="locationAutoSuggestSRF(this.value);" autocomplete="off">
            <i class="icon-cl" onclick="clearAutoSuggestor();">&times;</i>
            </div>
		</div>
        
        <div class="content-child2 clearfix" style="padding:0 0.7em;">
		<section id="loc-section">
		
		<!-- Display the Side Nav. Show the Country List -->
		<nav id="side-nav" class="loc-nav">	
		    <ul style="margin-bottom: 45px;">
			<?php if(count($temp_array)>0) :
				$i=0;
				foreach ($final_array as $country){ if($country=='All') continue;?>
				<li style="cursor:pointer;" <?php if($i==0){echo "class='active'";} ?> onClick="showHide('<?=$i?>');" id="<?=$i?>Menu"><span><?=$country?></span></li>
				<?php $i++;
				}
			endif;?>
			<li style="cursor:pointer;display:none;" onClick="showHide('1000');" id="1000Menu"><span>ALL</span></li>
		    </ul>
		</nav>
			
		<?php $k=0;
		$countryListArray= array();
		foreach($locations_parent as $parent):$m=0;?>
		<ul  class="location-list location-list2" id="<?php echo 'cityList'.$k; ?>" style="<?php if($k>0){ echo "display:none;"; } ?>">
			<!-- Display the Country Link at the top -->
			<?php if($parent['value']>0) { ?>
				<li id="SRO_SRF_<?php echo $m.'_'.$k;?>"  onClick='loadFilterLocation("<?=$parent['url']?>")'><label><strong><span id="SRF_<?php echo $m.'_'.$k;?>"><?=$parent['name']?> (<?=$parent['value']?>)</span> </strong></label></li>
			<?php      $cityListArray['cityNames'][$m]['Name'] = $parent['name'];
				   $cityListArray['cityNames'][$m]['Id'] = $m;
				   $cityListArray['cityNames'][$m]['Url'] = $parent['url'];
				   $metroCitiesArray[] =$m;
				   $m++;
				   $countryListArray[]= $parent['name']; $c++;
				  
			} else{
				    $cityListArray['cityNames'][$m]['Name'] = $parent['name'];
				    $cityListArray['cityNames'][$m]['Id'] = $m;
				    $cityListArray['cityNames'][$m]['Url'] =$parent['url'];
				    $metroCitiesArray[] =$m;
				    $m++;
				    $countryListArray[]= $parent['name'];
				   ?>
				<li id="SRO_SRF_<?php echo $m.'_'.$k;?>" onClick='loadFilterLocation("<?=$parent['url']?>")'><label><strong><span id="SRF_<?php echo $m.'_'.$k;?>"><?=$parent['name']?></span></strong></label></li>
			<?php } ?>
			
			<!-- Display the Complete City list -->
			<?php
				$i=0;
				$cityList = $parent['cities'];
			?>	
				<script> 
				   var index = "<?php echo $k;?>";
				   cityListCount[index]= "<?php echo count($cityList); ?>";
				  </script>
			 <?php
				foreach($cityList as $city) {
					$cityListArray['cityNames'][$m]['Name'] = $city['name'];
					$cityListArray['cityNames'][$m]['Id'] = $m;
					 $cityListArray['cityNames'][$m]['Url'] =$city['url'];
					 $metroCitiesArray[] =$m;
					$m++;
					?>
					<li id="SRO_SRF_<?php echo $m.'_'.$k;?>" 
						<?php if(isset($city['zone']) && count($city['zone'])>0 && empty($searchurlparams['zone_id']) ){ ?>
						onClick='showZones("<?=$m?>")' 
						<?php }else{ ?>
						onClick='loadFilterLocation("<?=$city['url']?>")'							
						<?php } ?>
					><label>
					<span id ="SRF_<?php echo $m.'_'.$k;?>">
					<?=$city['name']?>
					<?php	if($city['value']>0){
						       echo " (".$city['value'].")";
						}
						echo "</label></span></li>";
						$i++;
						
					//Write code for Zones
					$displayZones = "style='display:none;'";
					if(isset($searchurlparams['city_id']) && $searchurlparams['city_id']>0){
						$displayZones = "style='display:block;'";
					}
					else{ ?>
						<li style='display:none;' id="SRO_SRF_zone_<?=$m?>_-1" onClick='loadFilterLocation("<?=$city['url']?>")'><label style="padding-left: 16px;"><span><?=$city['name']?> All <?php if($city['value']>0){ echo " (".$city['value'].")"; } ?></span></label></li>
					<?php
					}
					$zoneList = $city['zone'];
					$x = 0;
					
					foreach ($zoneList as $zone){
						?>
						<li <?=$displayZones?> id="SRO_SRF_zone_<?=$m?>_<?=$x?>" onClick='loadFilterLocation("<?=$zone['url']?>")'>
						<label style="padding-left: 16px;">
						<span>
						<?=$zone['name']?>
						<?php	if($zone['value']>0){
							       echo " (".$zone['value'].")";
							}
							echo "</label></span></li>";
							$x++;
							
							
						//Write code for Localities
						$displayLocality = "style='display:none;'";
						if(isset($searchurlparams['zone_id']) && $searchurlparams['zone_id']>0){
							$displayLocality = "style='display:block;'";
						}
						$localityList = $zone['locality'];
						$y = 0;
						foreach ($localityList as $locality){
							?>
							<li <?=$displayLocality?> id="SRO_SRF_locality_<?=$m?>_<?=$y?>" onClick='loadFilterLocation("<?=$locality['url']?>")'>
							<label style="padding-left: 26px;">
							<span>
							<?=$locality['name']?>
							<?php	if($locality['value']>0){
								       echo " (".$locality['value'].")";
								}
								echo "</label></span></li>";
								$y++;
						}
							
					}
				}
			?>
			<!-- Display 1 Extra li for Fixed Footer -->
			<li><label for=""> </label></li>
                </ul>
                <?php $k++;
                endforeach;
			
		?>

		<ul class="location-list location-list2" id="cityList1000" style="display:none;">
			<li id="1000Link"><label>All Locations</label></li>
			<li><label for=""></label></li>
		</ul>
        </section>     
        
	</div>
    
</section>
	<a id="doneButton" class="cancel-btn" href="javascript:void(0);" onclick="closeSecondOverlay();">
	Cancel</a>
</div>
</div>
</div>
<script>

jQuery(document).ready(function(){
    /*window.onscroll = function() { 
            jQuery('#doneButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
    }*/
});

function showHide(id){
	if(id=='1000'){
		$("#cityList1000").show();
		$("#cityList"+prevId).hide();
		$("#autoSuggestRefine").hide();	
	}
	else{
	        if(cityListCount[id]==0){
                    $("#autoSuggestRefine").hide();
                }
		else
		     $("#autoSuggestRefine").show();
		$("#cityList1000").hide();
		$("#cityList"+prevId).show();
	}
	$("#"+id+'Menu').addClass('active').siblings().removeClass('active');
	$("ul[id*='cityList']").hide();
	window.jQuery("#search").val('');
	window.scrollTo(0, 1);
	$("#cityList"+id).show();
	clearAutoSuggestor(id+'Menu');
	prevId=id;
}

function showZones(valM){
	$("li[id*='zone_"+valM+"_']").show();
}
var showLocalitiesInFilters = false;
var showZonesInFilters = false;
</script>

<?php
	if(isset($searchurlparams['zone_id']) && $searchurlparams['zone_id']>0){
		echo "<script>showLocalitiesInFilters = true;</script>";
	}
	if(isset($searchurlparams['city_id']) && $searchurlparams['city_id']>0){
		echo "<script>showZonesInFilters = true;</script>";						
	}
 
	//Check of the user has filtered using Location. If yes, we will show an Extra option to choose "All Locations"
	if($showLocation){
		$searchurlparams['city_id'] = '';
		$searchurlparams['country_id'] = '';
		$searchurlparams['zone_id'] = '';
		$searchurlparams['locality_id'] = '';
		$url = urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));
		echo "<script>";
		echo "$('#1000Menu').show();";
		echo "$('#1000Link').click(function(){loadFilterLocation('".$url."')});";
		echo "</script>";
	}
?>

