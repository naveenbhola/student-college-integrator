<?php $this->load->view('header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<?php
$this->load->view('tabs');

/* Need to FIX following issues */
global $countriesForStudyAbroad;
global $countries;
$this->load->library('categoryList/CategoryPageRequest');
$this->load->library('category_list_client');

global $tabsContentByCategory;
if(!isset($tabsContentByCategory)){
    $tabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
} else {
    $tabsContentByCategory = $tabsContentByCategory;
}
$request_object = new CategoryPageRequest();
$this->load->builder('LocationBuilder','location');
$locationBuilder = new LocationBuilder;
$locationRepository = $locationBuilder->getLocationRepository();
$countries1 = $locationRepository->getCountriesByRegion(1);
$countries2 = $locationRepository->getCountriesByRegion(2);
$countries3 = $locationRepository->getCountriesByRegion(3);
$countries4 = $locationRepository->getCountriesByRegion(4);

?>
<?php
		
	if (getTempUserData('confirmation_message')){?>
		<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
		 <?php echo getTempUserData('confirmation_message'); ?>
		</div> 
	<?php } 
?>

<div id="content-wrap">
<div id="contents">
<ul>
<?php
foreach($countriesForStudyAbroad as $countryId => $country) {
    if(strtolower($countryId) == 'india') continue;
    $countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
    $countryname = isset($country['name']) ? $country['name'] : '';
    $countryId = str_replace('&','_',$countryId);
    $countryId = str_replace(',','_',$countryId);
    $linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
    $ulr_array[$countryId] = $linkUrl;
    $imgname = 'management-icn.png';
    
    switch ($countryId):
    case "southeastasia":
	    $imgname = 'south-east-asia.png';
	    break;
    case "europe":
	    $imgname = 'europe.png';
	    break;
    case "middleeast":
	    $imgname = 'middle-east.png';
	    break;
    case "uk-ireland":
	    $imgname = 'uk.png';
	    break;
    case "usa":
	    $imgname = 'united-states.png';
	    break;
    case "australia":
	    $imgname = 'aus.png';
	    break;
    case "newzealand&fiji":
	    $imgname = 'newzealand&fiji.png';
	    break;
    case "canada":
	    $imgname = 'canada.png';
	    break;
    case "china":
	    $imgname = 'china.gif';
	    break;
    case "fareast":
	    $imgname = 'fareast-icn.png';
	    break;

    default:
	    $imgname = 'management-icn.png';
	endswitch;
?>
  <li>
    <a href="<?php echo $linkUrl; ?>" />
        <div class="figure"><img class="lazy" src="/public/mobile/images/management-icn.png"  data-original="<?php echo base64_encode_image('/public/mobile/images/'.$imgname)?>" /></div>
  <div class="details">
  <strong><?php echo $countryname;?></strong>
  <?php if($countryId == 'southeastasia'):$rgn_id=1;?>
  <span> Malaysia | Singapore | Thailand</span>
  <?php elseif($countryId == 'europe'): $rgn_id=2;?>
  <span> France | Germany | Holland | Spain | Poland</span>
  <?php elseif($countryId == 'middleeast'): $rgn_id=3;?>
  <span> Qatar | Saudi Arabia | UAE </span>
  <?php elseif($countryId == 'uk-ireland'): $rgn_id=4;?>
    <span><?php echo $countries4[0]->getName();?> <?php if(array_key_exists(1,$countries4)):?>|<?php echo
        " ".$countries4[1]->getName();?><?php endif;?><?php if(array_key_exists(2,$countries4)):?>|<?php echo
        " ".$countries4[2]->getName();?><?php endif;?><?php if(array_key_exists(3,$countries4)):?>|<?php echo
        " ".$countries4[3]->getName();?><?php endif;?></span>
        <?php elseif($countryId == 'usa'):?>
        <span>Boston | New York | Chicago | Texas</span>
        <?php elseif($countryId == 'australia'):?>
        <span>Sydney | Melbourne | Brisbane | Perth</span>
        <?php elseif($countryId == 'newzealand_fiji'):?>
        <span>NewZealand | Fiji</span>
         <?php elseif($countryId == 'china-hk_taiwan'):?>
        <span>China | Hong Kong | Taiwan</span>
         <?php elseif($countryId == 'africa'):?>
        <span>Mauritius</span>
        <?php elseif($countryId == 'canada'):?>
        <span>Toronto | Edmonton | Ottawa | Vancouver</span>
        <?php elseif($countryId == 'fareast'):?>
        <span>Japan | North Korea | South Korea</span>
        <?php elseif($countryId == 'china'):?>
        <span>Beijing </span>
        
        <?php endif;?>
    </div>
        </a>
        </li>
        <?php } ?>

        </ul>
        </div>
    <?php 
    deleteTempUserData('confirmation_message');
    ?>
     <script>
    $("img.lazy").show().lazyload({ 
        effect : "fadeIn",
        failure_limit : 5  
    });
    </script>
    <?php $this->load->view('footer'); ?>
