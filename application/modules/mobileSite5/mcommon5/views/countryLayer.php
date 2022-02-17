<?php
    global $countriesForStudyAbroad;
    global $countries;
?>
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="countryOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3>Choose Country/Region</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="stream-list" id="layer-list-ul">
    
	<?php
	$key = 3;
	foreach($countriesForStudyAbroad as $countryArray) {
	    if(strtolower($countryArray['countryName']) == 'india') continue;
	    $countryname = isset($countryArray['name']) ? $countryArray['name'] : '';
	    $countryId = (is_numeric($countryArray['id'])) ? $countryArray['id'] : 0;
	    
	    $imgname = 'management-icn.png';
	    $rgn_id = 0;
	    switch ( strtolower($countryArray['name']) ):
	    case "south east asia":
		    $imgname = 'south-east-asia.png';
		    $rgn_id = 1;
		    $displayedString = " Malaysia | Singapore | Thailand";
		    break;
	    case "europe":
		    $imgname = 'europe.png';
		    $rgn_id = 2;
		    $displayedString = " France | Germany | Holland | Spain | Poland";
		    break;
	    case "middle east":
		    $imgname = 'middle-east.png';
   		    $rgn_id = 3;
		    $displayedString = " Qatar | Saudi Arabia | UAE";
		    break;
	    case "uk-ireland":
		    $imgname = 'uk.png';
   		    $rgn_id = 4;
		    break;
	    case "new zealand & fiji":
		    $imgname = 'newzealand&fiji.png';
   		    $rgn_id = 5;
		    $displayedString = "NewZealand | Fiji";
		    break;
	    case "far east":
		    $imgname = 'fareast-icn.png';
		    $displayedString = "Japan | North Korea | South Korea";
   		    $rgn_id = 6;
		    break;
	    case "china-hk, taiwan":
		    $imgname = 'china.gif';
   		    $rgn_id = 7;
		    $displayedString = "China | Hong Kong | Taiwan";
		    break;
	    case "africa":
		    $imgname = 'africa.gif';
   		    $rgn_id = 8;
		    $displayedString = "Mauritius";
		    break;
	    case "united states":
		    $imgname = 'united-states.png';
		    $displayedString = "Boston | New York | Chicago | Texas";
		    break;
	    case "australia":
		    $imgname = 'aus.png';
		    $displayedString = "Sydney | Melbourne | Brisbane | Perth";
		    break;
	    case "canada":
		    $imgname = 'canada.png';
		    $displayedString = "Toronto | Edmonton | Ottawa | Vancouver";
		    break;
	    default:
		    $imgname = 'management-icn.png';
	    endswitch;
	    
	?>
	
	
	<li onClick="countrySelected('<?=$key;?>');" style="cursor:pointer;" countryId = "<?=$countryId?>" regionId = "<?=$rgn_id?>" id = "countryMain<?=$key;?>" >
        	<!--<figure>
			<img class="lazy" src="/public/mobile/images/management-icn.png"  data-original="<?php echo base64_encode_image('/public/mobile/images/'.$imgname)?>" />
		</figure>-->
		<div class="details" style="margin-left:0px;">
		    <h2 id="countryName<?=$key?>"><?php echo $countryname;?></h2>

		    <?php if(strtolower($countryArray['name']) == 'uk-ireland'){ ?>
		    <span><?php echo $countries4[0]->getName();?> <?php if(array_key_exists(1,$countries4)):?>|<?php echo
			" ".$countries4[1]->getName();?><?php endif;?><?php if(array_key_exists(2,$countries4)):?>|<?php echo
			" ".$countries4[2]->getName();?><?php endif;?><?php if(array_key_exists(3,$countries4)):?>|<?php echo
			" ".$countries4[3]->getName();?><?php endif;?></span>
		    <?php }else{ ?>
		    <span><?=$displayedString?></span>
		    <?php } ?>

		</div>
	</li>

        <?php
	    $key++;
	}
	?>

    </ul>      
</section>
