<?php
$bestSortClass = "active";
$popularSortClass = "";
$bestOnclickStatement = 'onclick="refreshSearchPageWithSortType(\'best\');"';
$popularOnclickStatement = 'onclick="refreshSearchPageWithSortType(\'popular\');"';

$sortTypeURLParam = $_REQUEST['sort_type'];
if($sortTypeURLParam == "best"){
	$bestSortClass = "active";
	$popularSortClass = "";
	$bestOnclickStatement = "";
	
}
if($sortTypeURLParam == "popular"){
	$bestSortClass = "";
	$popularSortClass = "active";
	$popularOnclickStatement = "";
}
?>
<div class="Sorting-cont">
	<ul>
		<li>Sort by:</li>
		<li>
			<a href="javascript:void(0);" class = "<?php echo $bestSortClass;?>" <?php echo $bestOnclickStatement;?> >Best Match</a>
		</li>	
		<li> | </li>
		<li>
			<a href="javascript:void(0);" class = "<?php echo $popularSortClass;?>" <?php echo $popularOnclickStatement;?> >Popular</a>
		</li>
	</ul>
</div>
<div class="spacer10 clearFix"></div>
