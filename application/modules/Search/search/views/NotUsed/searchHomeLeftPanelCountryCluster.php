<!--div class="raised_blue_L"> 
	<b class="b2"></b>
		<div class="boxcontent_blue" style="background:none">
			<div class="row_blue" style="padding:5px 0px;">
			<span style="margin:5px;">Refine by Location</span>
		</div>
		<div class="lineSpace_11">&nbsp;</div-->
	<div class="float_L bgsearchResultBorderDotted bgsearchHeight" style="width:<?php echo $Country?>%">
				<div class="OrgangeFont bld pd_left_10p">Location</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="mar_full_10p borderSearchResult" style="height:97px; overflow-y:auto">
                <div style="width:85%; line-height:15px" class="pd_left_5p">
			
<?php	

if($_REQUEST['subLocation']==-1)
{
    $selectedCountryName="India";
}
else
{
    foreach($countryList as $country) {
        $countryId   = $country['countryID'];
        $countryName = $country['countryName'];
        if($countryId==1)
            continue;
        if($countryId==$_REQUEST['subLocation'])
        {
            $selectedCountryName=$countryName;
        }
    }
}

$clusterCountry = $cluster['country'];
$clusterCity=$cluster['city'];

foreach($clusterCity as $key=>$value)
{
	$countryCity=$cityCountryNameMap[$key];
	$cityArray=explode("-",$countryCity);
	$elementCount=count($cityArray);
	$country=$cityArray[$elementCount-1];
	if($elementCount>2)
	{
		$city=$cityArray[0]." - ".$cityArray[1];
	}
	else
	{
		$city=$cityArray[0];
	}
    if($country==$selectedCountryName)
    {
	$cityCountryMap[$country][]=array("city"=>$city,"count"=>$value,"cityId"=>$key);
    }
}
$count = count($clusterCountry);	
if(is_array($clusterCountry)): 
	?>
	<!--<div class="openArrow" id="1_leftCountryArrow" name="Parent" style="cursor:pointer;" onClick="openCountries(1,'<?php echo $count; ?>');">
	</div>
	-->

	<?php endif; ?>
		<div id="1_country">
			<div>
<?php

if(!(isset($_REQUEST['subLocation']))||($_REQUEST['subLocation']==-1))
{
	?>
	
				<div id="0_leftSubCountry" class="">
					<span class="disBlock bld blackFont" style="font-size:11px" title="All"> <span class="redcolor">&raquo;</span> All <span id="allCountryCountPlace"></span></span>
				</div>
<?php
		$selectedLocation="All";
 } else { ?>
	
				<div id="0_leftSubCountry" onClick="showResultsForLocation(-1,'<?php echo $count; ?>', 0);" class="">
					<a href="#" class="disBlock" style="font-size:11px" title="All">All <span id="allCountryCountPlace"></span></a>
				</div>

<?php } ?>
	<?php   $Id = 0;
	$totalResultCount = 0;
	$countryId  = 1;
if(is_array($clusterCountry))
	foreach($countryList as $country) {
		$countryId   = $country['countryID'];
		$countryName = $country['countryName'];
        if($countryId == '1') {
			continue;
		}
		if(! array_key_exists($countryId, $clusterCountry)) { 
			continue; 
		} else {
			$clusterCountryCount = $clusterCountry[$countryId];
		}
		$Id++;
		$totalResultCount += $clusterCountryCount;
		?>
<?php 
if((isset($_REQUEST['subLocation']))&&($_REQUEST['subLocation']!=-1) && ((!isset($_REQUEST['cityId'])) || ($_REQUEST['cityId']==-1)) && ($_REQUEST['subLocation']==$countryId))
{
?>
				<div id="<?php echo $Id; ?>_leftSubCountry">
					<span class="disBlock bld blackFont" style="font-size:11px" title="<?php echo $countryName;?>" ><span class="redcolor">&raquo;</span> <?php echo $countryName; ?> <span id="countryCount_<?php echo $countryId; ?>"></span></span>
				</div>
<?php 
		$selectedLocation=$countryName;
} else {?>
				<div id="<?php echo $Id; ?>_leftSubCountry">
					<a href="#" onClick="return showResultsForLocation('<?php echo $countryId; ?>','<?php echo $count ?>','<?php echo $Id; ?>');" class="disBlock" style="font-size:11px" title="<?php echo $countryName;?>" ><?php echo $countryName; ?> <span id="countryCount_<?php echo $countryId; ?>">(<?php echo $clusterCountryCount; ?>)</span></a>
				</div>

<?php 
	}
?>
			<?php
			$IdCity=0;
		if((isset($_REQUEST['cityId'])) && ($_REQUEST['cityId']!=-1))
		{
			?>
				<script>
				document.getElementById('countryCount_<?php echo $countryId?>').innerHTML='';
			</script>	
				<?php
		}
		$cityClusterCount=10000;
		foreach($cityCountryMap[$countryName] as $city)
		{
			if(($IdCity%$cityClusterCount)==0)
			{
				if($IdCity==0)
				{	
					?>
				<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_cityDisplayDiv" style="display:''">
				<?php
				}
				else
				{
					?>
				<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_cityDisplayDiv" style="display:none">
				<?php 
				}
			}
			?>
<?php

	if((isset($_REQUEST['cityId']))&&($_REQUEST['cityId']==$city['cityId']))
	{
?>
					<div style="margin-left:10px;" id="<?php echo $IdCity; ?>_leftSubCity"><!--img align="absmiddle" src="/public/images/grayBullet.gif" id="catBullet_146"/--><span class="disBlock bld blackFont" style="font-size:11px" title="<?php echo $city['city']?>" style="cursor: pointer; margin-left: 5px;"><span class="redcolor">&raquo;</span> <?php echo $city['city']." "; ?><label id="countryCount_<?php echo $city['cityId']; ?>"></label></span>
					</div>
<?php
		$selectedLocation=$city['city'];
	}
	else
	{
?>
					<div style="margin-left:10px;" id="<?php echo $IdCity; ?>_leftSubCity"><!--img align="absmiddle" src="/public/images/grayBullet.gif" id="catBullet_146"/--><a class="disBlock" style="font-size:11px" title="<?php echo $city['city']?>" onclick="return showResultsForCity('<?php echo $city['cityId']; ?>','<?php echo $city['count'] ?>','<?php echo $IdCity; ?>');" style="cursor: pointer; margin-left: 5px;" href="#"><?php echo $city['city']." "; ?><label id="countryCount_<?php echo $city['cityId']; ?>">(<?php echo $city['count']; ?>)</label></a>
					</div>

<?php 
	}
?>	
				<?php
				if($IdCity%$cityClusterCount==($cityClusterCount-1))
				{
					if($IdCity>=$cityClusterCount)
					{
					?>
					<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_prevdiv" class="float_L" style="line-height:20px">
						<a href="#<?php echo $Id?>_leftSubCountry" onClick="return toggleDisplayForCity(<?php echo ((($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount)-1)?>,1);" class="disBlock" style="font-size:11px" ><img src="/public/images/preDefault.gif" border="0" id="img1<?php echo $IdCity?>" onmouseover="document.getElementById('img1<?php echo $IdCity?>').src='/public/images/preOver.gif'" onmouseout="document.getElementById('img1<?php echo $IdCity?>').src='/public/images/preDefault.gif'" /></a>
					</div>
					<?php
					}
					else
					{
					?>
					<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_prevdiv" class="float_L" style="line-height:20px">
						<a href="#<?php echo $Id?>_leftSubCountry" onClick="return toggleDisplayForCity(<?php echo ((($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount)-1)?>,1);" class="disBlock" style="font-size:11px" >&nbsp;</a>
					</div>
					
					<?php
					}
					?>
					<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_nextdiv" class="float_R" style="line-height:20px">
						<a href="#<?php echo $Id?>_leftSubCountry" onClick="return toggleDisplayForCity(<?php echo ((($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount)+1)?>,-1);" class="disBlock" style="font-size:11px" ><img src="/public/images/nextDefault.gif" border="0" id="img2<?php echo $IdCity?>" onmouseover="document.getElementById('img2<?php echo $IdCity?>').src='/public/images/nextOver.gif'" onmouseout="document.getElementById('img2<?php echo $IdCity?>').src='/public/images/nextDefault.gif'"/></a>
					</div>
					<div class="clear_L"></div>
				</div>
						<?php
				}
			$IdCity++;
		}
	if($IdCity%$cityClusterCount!=0)
	{
	if($IdCity>=$cityClusterCount)
	{
?>
					<div id="<?php echo ($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount ?>_prevdiv" class="float_L" style="line-height:20px">
						<a href="#<?php echo $Id?>_leftSubCountry" onClick="return toggleDisplayForCity(<?php echo ((($IdCity-$IdCity%$cityClusterCount)/$cityClusterCount)-1)?>,1);" class="disBlock" style="font-size:11px" ><img src="/public/images/preDefault.gif" border="0" id="img1<?php echo $IdCity?>" onmouseover="document.getElementById('img1<?php echo $IdCity?>').src='/public/images/preOver.gif'" onmouseout="document.getElementById('img1<?php echo $IdCity?>').src='/public/images/preDefault.gif'" /></a>
					</div>
					<div class="clear_L"></div>
	<?php
	}
	?>
				</div>
	<?php
	}}
	?>
<script>
/*if(document.getElementById('allCountryCountPlace')) {
  document.getElementById('allCountryCountPlace').innerHTML = ' (<?php echo $totalResultCount;?>)'
  }*/
var totalCountries = '<?php echo $countryId; ?>';
<?php

if(!(isset($_REQUEST['subLocation']))||($_REQUEST['subLocation']==-1))
{
	?>
		//document.getElementById('0_leftSubCountry').className='activeselectCategory5';
	<?php
}
else
{

	if(!(isset($_REQUEST['cityId']))||($_REQUEST['cityId']==-1))
	{
		?>
			//document.getElementById('1_leftSubCountry').className='activeselectCategory5';
		//document.getElementById('searchAtBlock').innerHTML=(document.getElementById('1_leftSubCountry').getElementsByTagName("a")[0].innerHTML.split("<")[0]);
		<?php
	}
	else
	{
		?>
		//	document.getElementById('0_leftSubCity').className='activeselectCategory5';
		//document.getElementById('searchAtBlock').innerHTML=(document.getElementById('0_leftSubCity').getElementsByTagName("a")[0].innerHTML.split("<")[0]);
		<?php
	}
}
?>
</script>
<input type="hidden" name="country" id="country" value="<?php echo $selectedLocation?>" autocomplete="off"/>
</div>
</div>
</div>
</div>
</div>
<!--div class="lineSpace_11">&nbsp;</div>

</div-->
<!--b class="b4b" style="background:#ffffff;"></b><b class="b3b" style="background:#ffffff;"></b><b class="b2b" style="background:#ffffff;"></b><b class="b1b"></b>		
</div>		
<div class="lineSpace_11">&nbsp;</div--> 
