<!--Start_CourseLevel-->
<?php
function makeSpaceSeparated($name)
{
	$nameArray = explode("-",$name);
	$name = implode(" ",$nameArray);
	return ucwords($name);
}

?>
			<div class="float_L bgsearchHeight" style="width:<?php echo $CourseLevel?>%" id="courseLevelDiv">
				<div class="OrgangeFont bld pd_left_10p">Course Level</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="mar_full_10p borderSearchResult" style="height:97px; overflow-y:auto">
					<div style="width:85%; line-height:15px" class="pd_left_5p">
<?php if(!(isset($_REQUEST['courseLevel']))||($_REQUEST['courseLevel']==-1))
	{
		 ?>	
					<span class="disBlock bld blackFont" style="font-size:11px" title="All"> <span class="redcolor">&raquo;</span> All</span>
<?php 
$courseLevel=$cluster['courseLevel'];
$cLevelPref= array('under-graduate-degree'=>10,'post-graduate-degree'=>9,'diploma'=>8,'post-graduate-diploma'=>7,'certification'=>6,'vocational'=>5,'exam-preparation'=>4,'Others'=>1);

if(is_array($courseLevel))
{
	foreach($courseLevel as $typeName=>$typeCount)
	{
		if(isset($cLevelPref[$typeName]))
		{
			$sortArray[$typeName] = $cLevelPref[$typeName]; 
		}
		else
		{
			$sortArray[$typeName] = 1;
		}
	}
	array_multisort($sortArray, SORT_DESC, $courseLevel);

	$Id=0;
	foreach($courseLevel as $key=>$value)
	{
	$Id++;
	$Onclick="return showResultsForCourseLevel('".$key."','".$Id."')";
?>
	<?php if((isset($_REQUEST['courseLevel']))&&($_REQUEST['courseLevel']==$key))
	{
		 ?>	
	
						<a href="" onClick="<?php echo $Onclick?>" class="disBlock bld blackFont" style="font-size:11px" title="<?php echo makeSpaceSeparated($key); ?>"> <span class="redcolor">&raquo;</span> <?php echo makeSpaceSeparated($key)." (".$value.")" ?></a>
	<?php } else {?>
						<a href="" onClick="<?php echo $Onclick?>" class="disBlock" style="font-size:11px"  title="<?php echo makeSpaceSeparated($key); ?>"> <?php echo makeSpaceSeparated($key)." (".$value.")" ?></a>
<?php 
		}	
	}
}
?>
<?php } else { ?>
					<a href="" onClick="return showResultsForCourseLevel('All','0')" class="disBlock" style="font-size:11px" title="All">All</a>
					<span onClick="return false;" class="disBlock bld blackFont" style="font-size:11px" title="<?php echo  makeSpaceSeparated($_REQUEST['courseLevel']); ?>"> <span class="redcolor">&raquo;</span> <?php echo  makeSpaceSeparated($_REQUEST['courseLevel']); ?></span>
<?php } ?>
	
					</div>
				</div>
			</div>
			
