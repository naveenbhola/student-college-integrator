<ul id="_comparedList">
<?php 
$k = 1;
foreach ($courseBucket as $courseId => $value) {
        if(empty($courseObj[$courseId])){
            continue;
        }
        $course = $courseObj[$courseId];  
        $instituteName = $course->getInstituteName();
      
        if(is_object($course->getMainLocation())){
        	$location =  $course->getMainLocation()->getCityName();	
        }

        $instTitle = $instituteName;
        if(strlen($instituteName) > 100){
			$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($instituteName));
			$instStr .= "...";
		}else{
			$instStr = html_escape($instituteName);
		}
?>

        <li id="l<?php echo $k;?>"><a href="javascript:void(0);">
		<i <?php if(trim($boomr_pageid) == 'COMPAREPAGE'){?>  data-page="COMPAREPAGE" <?php }?> class="cls-clg" data-id="<?php echo $k;?>" data-courseId="<?php echo $courseId;?>" data-instituteId="<?php echo $value['instituteId'];?>">&times;</i><?php echo $instituteName; ?><span class="locality"><i class="msprite compare-locality"></i><?=$location;?></span></a></li>

<?php $k++;}?>


	<?php if($signedInUser !='false'){?>  
		<li><a href="javascript:void(0);"><input type="button" value="Compare" data-boomr_pageid="<?php echo $boomr_pageid;?>" data-type="loggedIn" 	class="compare-btn"/></a></li>
	<?php }else{?>
		<li><a href="javascript:void(0);" id="_rhlCmp"><input type="button" value="Compare" data-boomr_pageid="<?php echo $boomr_pageid;?>" data-type="login" class="compare-btn"/></a></li>
	<?php }?>
</ul>