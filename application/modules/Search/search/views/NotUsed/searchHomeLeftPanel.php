<?php 
if(isset($_REQUEST['showCluster']))
{
   if($_REQUEST['showCluster'] == '-1')
   {
    $showCluster = 1;
   }
   else
   {
    $showCluster =0;
   }
}
else
{
    $showCluster = 1;
}
?>
<div class="mar_full_10p" id="leftPanelDiv">
	<div class="lineSpace_24 bgsearchResult">
		<?php if($showCluster) {?>
		<div class="float_L bld" id="ClusterDiv"style="width:30%"><img src="/public/images/searchRefineClose_n.gif" align="absmiddle" class="anchorClass" onclick="toggleClusterDiv(0);" />Refine your search results</div>
		<div class="clear_L"></div>
		<?php } else {?>
		<div class="float_L bld" id="ClusterDiv" style="width:30%"><img src="/public/images/searchRefineOpen_n.gif" align="absmiddle" class="anchorClass" onclick="toggleClusterDiv(1);" />Refine your search results</div>
		<div class="clear_L"></div>
		<?php } ?>
	</div>
	<div id="searchClusterDiv" class="bgsearchResultBorder">
		<div class="lineSpace_10">&nbsp;</div>
		<div>
		<?php
		$loadCluster=array();
		/*if(($cluster['type'] != "")&&($_REQUEST['searchType']=="" || $_REQUEST['searchType']=="Category" || $_REQUEST['searchType']=="foreign" || $_REQUEST['searchType']=="testprep"||$_REQUEST['searchType']=="groups"))	
{
	if($_REQUEST['subType']=="0"){
		if(count($cluster['type'])>1)
			$loadCluster['Type']='search/searchHomeLeftPanelTypeCluster';}
	else if (count($cluster['type'])>0){
			$loadCluster['Type']='search/searchHomeLeftPanelTypeCluster';}

}
if($cluster['country']!="")
{
	if($_REQUEST['cityId']!=-1 || $_REQUEST['subLocation']!=-1){
			$loadCluster['Country']='search/searchHomeLeftPanelCountryCluster';
	}
	else if ((count($cluster['country'])>1) || (count($cluster['city']))>1){
			$loadCluster['Country']='search/searchHomeLeftPanelCountryCluster';
	}
}          
if($cluster['courseType']!="")
{
	if($_REQUEST['cType']==-1){
		if(count($cluster['courseType'])>1)	
			$loadCluster['cType']='search/searchHomeLeftPanelCourseTypeCluster';
	}
	else if (count($cluster['courseType'])>0){
			$loadCluster['cType']='search/searchHomeLeftPanelCourseTypeCluster';}
}          
if($cluster['courseLevel']!="")
{
	if($_REQUEST['courseLevel']==-1){
		if(count($cluster['courseLevel'])>1)	
			$loadCluster['CourseLevel']='search/searchHomeLeftPanelCourseLevelCluster';
	}
	else if (count($cluster['courseLevel'])>0){
			$loadCluster['CourseLevel']='search/searchHomeLeftPanelCourseLevelCluster';}
}*/
	//		$loadCluster['Type']='search/searchHomeLeftPanelTypeCluster';
			if(!(isset($_REQUEST['location'])) || $_REQUEST['location'] =="")
			{
				$loadCluster['Country']='search/searchHomeLeftPanelCountryCluster';
			}
            if(isset($location) && ($location != "")) {
                unset($loadCluster['Country']);
            }
			$loadCluster['cType']='search/searchHomeLeftPanelCourseTypeCluster';
			$loadCluster['CourseLevel']='search/searchHomeLeftPanelCourseLevelCluster';
$widthArray=array(
		'Type'=>18,
		'Country'=>22,
		'cType'=>22,
		'CourseLevel'=>22
);
$count=count($loadCluster);
if($count<4)
{
	foreach($widthArray as $key=>$value)
	{
		if(!array_key_exists($key,$loadCluster))
		{
			foreach($widthArray as $key1=>$value1)
			{
				if(array_key_exists($key1,$loadCluster))
					$widthArray[$key1]=min(30,$widthArray[$key1]+$widthArray[$key]/$count);
			}
		}
	}
}

    foreach ($loadCluster as $value)
	{
		$this->load->view($value,$widthArray);
	}
if($durationData['minCount']!=$durationData['maxCount'])
    //$this->load->view('search/searchHomeLeftPanelDurationSlider');          
//$this->load->view('search/searchHomeLeftPanelTagCloud');
?> 
<!--End_Course_TYPE-->
			<!--div id="relatedSearch"></div-->
				<!--div class="float_L bgsearchHeight" style="width:20%" id="durationDiv"><div-->
				<div class="clear_L"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>
</div>
<script>
	<?php if(!$showCluster) {?>
	document.getElementById('searchClusterDiv').style.display='none';
	<?php }?>
</script>
