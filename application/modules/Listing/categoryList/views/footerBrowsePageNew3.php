<?php
$product = 'browseSeo';
$affiliationName = '';
$examName = '';
$feeString = '';
if($affiliationFromURL != 'none') { 
	$affiliationName = strtoupper($affiliationFromURL)." ".ucfirst($affiliationSuffix[strtolower($affiliationFromURL)])." ";
}
if($examFromURL != 'none') { 
	$examName = " accepting ".$examFromURL." ";
}

if($feeFromURL != 'none') {
	$feeString = "with fees ".str_replace("Maximum","less than",$GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$feeFromURL]);

}

$headerComponents = array(
	'js' => array(),
	'css'=>array('common','category-styles','browse_seo'),                                                
	'jsFooter' =>array('common','lazyload'),
	'product'=> $product,
	'taburl' =>  site_url(),
	'bannerProperties' => array(
				'pageId'=>'CATEGORY',
				'pageZone'=>'TOP',
				'shikshaCriteria' => $criteriaArray
				),
	'title'	=>	'View '.$affiliationName.$courseName.' courses in locations starting with '.$alfa.$examName.$feeString,
	'searchEnable' => true,
	'canonicalURL' => $canonicalurl,
	'metaDescription' => 'View '.$affiliationName.$courseName.' courses in locations starting with '.$alfa.$examName.$feeString,
	'metaKeywords'	=> ''
	);
$this->load->view('common/header', $headerComponents);
// we can create a common app for doing following things :)

if(count($StatesWithKey)>1) {
$count = count($StatesWithKey);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$statesnew = array_chunk($StatesWithKey,$length);
$statesnew1 = $statesnew[0];
$statesnew2 = $statesnew[1];
} else {
$statesnew1 = $StatesWithKey;
$statesnew2 = array();
}
if(count($countries)>1) {
$count = count($countries);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$countriesnew = array_chunk($countries,$length);
$countriesnew1 = $countriesnew[0];
$countriesnew2 = $countriesnew[1];
} else {
$countriesnew1 = $countries;
$countriesnew2 = array();
}
if(count($CityWithKey)>1) {
$count = count($CityWithKey);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$citiesnew = array_chunk($CityWithKey,$length);
$citiesnew1 = $citiesnew[0];
$citiesnew2 = $citiesnew[1];
} else {
$citiesnew1 = $CityWithKey;
$citiesnew2 = array();
}
if(count($regions)>1) {
$count = count($regions);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$regionsnew = array_chunk($regions,$length);
$regionsnew1 = $regionsnew[0];
$regionsnew2 = $regionsnew[1];
} else {
$regionsnew1 = $regions;
$regionsnew2 = array();
}
?>
    <!--Browse HTML STARTS HERE-->
    <div id="browse-contents">
<h2 class="bot-padd">View <?php if($affiliationFromURL != 'none') { echo strtoupper($affiliationFromURL)." ".ucfirst($affiliationSuffix[strtolower($affiliationFromURL)])." " ;}?><?php echo $courseName;?> courses in locations starting with <?php echo $alfa; if($examFromURL != 'none') { echo " accepting ".$examFromURL;}?>
<?php if($feeFromURL != 'none') {?> with fees <?php echo str_replace("Maximum","less than",$GLOBALS['CP_FEES_RANGE']['RS_RANGE_IN_LACS'][$feeFromURL]); }?>
</h2>
		<?php if(!empty($examList)) {  ?>
		<p class="exam-aff-fee" >  Exam Accepted -
		<?php foreach ($examList as $exam) { ?>
		  <a href="/categoryList/Browse/page3/<?php echo $type."/".$typeId."/".$alphabet."/".$scope."/".$exam?>"><?php echo $exam?></a>
		  <?php if($exam != end($examList)) {?>
		 <?php echo '|';}}?>
		 </p>
		 <?php }?>
		 <?php if(!empty($affliationList)) {  ?>
		<p class="exam-aff-fee" >  Affiliation -
		<?php foreach ($affliationList as $affliation) { ?>
		  <a href="/categoryList/Browse/page3/<?php echo $type."/".$typeId."/".$alphabet."/".$scope."/".$examFromURL."/".$feeFromURL."/".$affliation?>"><?php echo strtoupper($affliation)." ".ucfirst($affiliationSuffix[strtolower($affliation)]);?></a>
		  <?php if($affliation != end($affliationList)) {?>
		 <?php echo '|';}}?>
		 </p>
		 <?php }?>
		 <?php if(!empty($feeList)) {  ?>
		<p class="exam-aff-fee" >  Fees -
		<?php foreach ($feeList as $feeVal => $feeAcrnym) { ?>
		  <a href="/categoryList/Browse/page3/<?php echo $type."/".$typeId."/".$alphabet."/".$scope."/".$examFromURL."/".$feeVal?>"><?php echo str_replace("Maximum","Less than",$feeAcrnym);?></a>
		 <?php if(!($feeAcrnym == end($feeList))) {?>
		 <?php echo '|';}}?>
		 </p>
		 <?php }?>

			
		 
    	<div id="browse-left-col">
        	<div class="box-shadow">
            	<div class="contents2" id="pag3_left">
                    <ul>
                    <?php if($scope == 'India'):?>
                    <?php if(count($statesnew1)>0):?>
                    <?php $k=0;foreach($statesnew1 as $state): $categoryPageRequest->setDataByPageKey($state['page_key']); $categoryPageRequest->setData(array('examName' => $state['exam']));?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $affiliationName.$courseName." courses in ".$state['state_name'].$examName.$feeString?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php if(count($citiesnew1)>0):?>
                    <?php $k=0;foreach($citiesnew1 as $city): $categoryPageRequest->setDataByPageKey($city['page_key']); $categoryPageRequest->setData(array('examName' => $city['exam']));?>
                    	<li <?php if(((count($statesnew1)>1 || count($statesnew1)==0) && $k % 2 !=0) || (count($statesnew1)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $affiliationName.$courseName." courses in ".$city['city_name'].$examName.$feeString;?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php else:?>
                    <?php if(count($countriesnew1)>0):?>
                     <?php $k=0;foreach($countriesnew1 as $country): $categoryPageRequest->setData(array('countryId' => $country->getId(),'regionId' => NULL));?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $courseName." courses in ".$country->getName();?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php if(count($regionsnew1)>0):?>
                     <?php $k=0;foreach($regionsnew1 as $region): $categoryPageRequest->setData(array('regionId' => $region->getId(),'countryId' =>0));?>
                    	<li <?php if(((count($countriesnew1)>1 || count($countriesnew1)==0) && $k % 2 !=0) || (count($countriesnew1)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $courseName." courses in ".$region->getName();?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php endif;?>
                    </ul>
                    <div class="clearFix"></div>
                </div>
            </div>
        </div>
        <?php if(count($statesnew2)>0 || count($citiesnew2)>0 || count($countriesnew2)>0 || count($regionsnew2)>0):?>
        <div id="browse-right-col">
        	<div class="box-shadow">
            	<div class="contents2" id="pag3_leftr">
                    <ul>
                    <?php if($scope == 'India'):?>
                    <?php if(count($statesnew2)>0):?>
                    <?php $k=0;foreach($statesnew2 as $state): $categoryPageRequest->setDataByPageKey($state['page_key']); $categoryPageRequest->setData(array('examName' => $state['exam']));?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $affiliationName.$courseName." courses in ".$state['state_name'].$examName.$feeString?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php if(count($citiesnew2)>0):?>
                    <?php $k=0;foreach($citiesnew2 as $city): $categoryPageRequest->setDataByPageKey($city['page_key']); $categoryPageRequest->setData(array('examName' => $city['exam']));?>
                    	<li <?php if(((count($statesnew2)>1 || count($statesnew2)==0) && $k % 2 !=0) || (count($statesnew2)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $affiliationName.$courseName." courses in ".$city['city_name'].$examName.$feeString;?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php else:?>
                    <?php if(count($countriesnew2)>0):?>
                     <?php $k=0;foreach($countriesnew2 as $country): $categoryPageRequest->setData(array('countryId' => $country->getId(),'regionId' => NULL));?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $courseName." courses in ".$country->getName();?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php if(count($regionsnew2)>0):?>
                     <?php $k=0;foreach($regionsnew2 as $region): $categoryPageRequest->setData(array('regionId' => $region->getId(),'countryId' =>0));?>
                    	<li <?php if(((count($countriesnew2)>1 || count($countriesnew2)==0) && $k % 2 !=0) || (count($countriesnew2)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="<?php echo $categoryPageRequest->getURL();?>"><?php echo $courseName." courses in ".$region->getName();?></a></li>
                    <?php $k++;endforeach;endif;?>
                    <?php endif;?>
                    </ul>
                    <div class="clearFix"></div>
                </div>
            </div>
        </div>
        <?php endif;?>
        <div class="clearFix"></div>
    </div>
    <!--Browse HTML ENDS HERE-->
<div class="spacer20 clearFix"></div>	
<?php 
$this->load->view('common/footerNew');
?>
<script>
$j(document).ready(function($) {
if($('#pag3_left').height() >= $('#pag3_leftr').height()) {
	$('#pag3_leftr').height($('#pag3_left').height());	
} else {
        $('#pag3_left').height($('#pag3_leftr').height());  
} 
});
</script>
