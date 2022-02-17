<?php
function makeSpaceSeparated1($name)
{
	$nameArray = explode("-",$name);
	$name = implode(" ",$nameArray);
	return ucwords($name);
}

?>
	
<div class="float_L bgsearchResultBorderDotted bgsearchHeight" style="width:<?php echo $cType?>%" id="courseTypeDiv">
				<div class="OrgangeFont bld pd_left_10p">Mode of Learning</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="mar_full_10p borderSearchResult" style="height:97px; overflow-y:auto">
					<div style="width:85%; line-height:15px" class="pd_left_5p">
<?php if(!(isset($_REQUEST['cType']))||($_REQUEST['cType']==-1))
	{
		 ?>	
					<span class="disBlock bld blackFont" style="font-size:11px" title="All"> <span class="redcolor">&raquo;</span> All</span>
<?php 
$courseType=$cluster['courseType'];
$cTypePref= array('full-time'=>10,'part-time'=>9,'correspondence-course'=>8,'e-learning'=>7,'others'=>1);
if(is_array($courseType))
{
	foreach($courseType as $typeName=>$typeCount)
	{
		if(isset($cTypePref[$typeName]))
		{
			$sortArray[$typeName] = $cTypePref[$typeName]; 
		}
		else
		{
			$sortArray[$typeName] = 1;
		}
	}
	array_multisort($sortArray, SORT_DESC, $courseType);
	$Id=0;
	foreach($courseType as $key=>$value)
	{
	$Id++;
	$Onclick="return showResultsForCourseType('".$key."','".$Id."')";
?>
	<?php if((isset($_REQUEST['cType']))&&($_REQUEST['cType']==$key))
	{
		 ?>	
	
						<a href="" onClick="<?php echo $Onclick?>" class="disBlock bld blackFont" style="font-size:11px" title="<?php echo makeSpaceSeparated1($key);?>"> <span class="redcolor">&raquo;</span> <?php echo makeSpaceSeparated1($key)." (".$value.")" ?></a>
	<?php } else {?>

						<a href="" onClick="<?php echo $Onclick?>" class="disBlock" style="font-size:11px"  title="<?php echo makeSpaceSeparated1($key); ?>"> <?php echo makeSpaceSeparated1($key)." (".$value.")"; ?></a>
<?php 
		}
	}
}
?>	
<?php } else { ?>
					<a href="" onClick="return showResultsForCourseType('All','0')" class="disBlock" style="font-size:11px" title="All">All</a>
					<span onClick="return false;" class="disBlock bld blackFont" style="font-size:11px" title="<?php echo makeSpaceSeparated1($_REQUEST['cType']); ?>"> <span class="redcolor">&raquo;</span> <?php echo makeSpaceSeparated1($_REQUEST['cType']); ?></span>
<?php } ?>

					</div>
				</div>
			</div>
