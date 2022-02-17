<tr id="importantInfoDiv" class="flagClassForAjaxContent" <?php if(!($courseIdArr && count($courseIdArr)>0)){ echo "style='display:none;'";} ?>>
        	<td colspan="2" class="compare-title"><h2>Course Name</h2></td>
        </tr>

	<tr id="courseDisplayDiv" class="flagClassForAjaxContent" <?php if(!($courseIdArr && count($courseIdArr)>0)){ echo "style='display:none;'";} ?>>	
            <?php
		$j = 0;$k = 0;
		if($compare_count <= $compare_count_max)
		{
		    foreach($courseIdArr as $courseId) {
			$k++;
			$course      = $courseObjs[$courseId];
			//$instituteId = $instIdArr[$courseId];
			//$coursesForDropDown = $instituteWithCoursesData[$courseId][$instituteId]->getCourse();
			if($showAcademicUnitSection){
				$instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute']; 
				$coursesForDropDown = $instituteWithCoursesData[$courseId][$instIdArr[$courseId]]->getCourse(); 
			}else{ 
				$instituteId = $instIdArr[$courseId]; 
				$coursesForDropDown = $instituteWithCoursesData[$courseId][$instituteId]->getCourse(); 
			}
			if( isset( $coursesForDropDown ) && count($coursesForDropDown)>0 ){
				$j++;
		?>
		    <td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>" >

			<div class="custome-compare-dropdown" id="courseNameDropDownList<?=$k?>" style="z-index:8;">
                    	<a href="javascript:void(0)" onClick="toggleCourseNameDropdown('<?=$k?>');">
                        	<div class="arrow"><i class="caret"></i></div>
                            <span class="display-area" id="courseSelectCustom<?=$k?>"><?=$course->getName();?></span>
                        </a>
                        <div class="drop-layer" id="courseNameDropDown<?=$k?>" style="display:none;">
                        	<ul>
	                        <?php foreach ($coursesForDropDown as $courseD){
	                        	if(empty($courseD))
	                        	{
	                        		continue;
	                        	}
								$name = $courseD->getName().(($courseD->getOfferedById() != '' && $instituteId != $courseD->getOfferedById() && $courseD->getOfferedByShortName() != '') ? ', '.$courseD->getOfferedByShortName() : '');
	                        	$prepareCourse[$k][] = array('id'=>$courseD->getId().'_'.$k,'name'=>str_replace("'", "&apos;", $name),'url'=>'javascript:void(0);');
	                        	$firstCourseList  = json_encode(array('popularList'=>$prepareCourse[1],'otherList'=>array()));
                				$secondCourseList = json_encode(array('popularList'=>$prepareCourse[2],'otherList'=>array())); 
	                         ?>
        	                <li><a id="courseNameList<?php echo $courseD->getId()?>" href="javascript:void(0)" onClick="courseNameHTML('<?=$courseD->getName();?>','<?=$k;?>','<?=$courseD->getId()?>');"><?php echo $courseD->getName();if($courseD->getOfferedById() != '' && $instituteId != $courseD->getOfferedById() && $courseD->getOfferedByShortName() != ''){ echo ', '.$courseD->getOfferedByShortName();}?></a></li>
                	        <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" id= "courseSelect<?=$k?>" value = "<?=$courseId?>" />

		    </td>
		<?php }else{ ?>
		    <td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
			<strong style="font-size:22px; color:#828282">-</strong>
		    </td>
		<?php }
		    }
		    
			$showId = true;
			if($j<$compare_count_max){       //Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=$compare_count_max; $x++){
					echo '<td class="'.(($x<$compare_count_max)?'border-right':'').'" ';
					if($showId){ echo " id='newCourseSection' ";}
					echo '>&nbsp;</td>';
					$showId = false;
				}
			}
		    
		}
		
		?>
        </tr>
<script type="text/javascript">
var firstCourseList  = '<?php echo $firstCourseList;?>';
var secondCourseList = '<?php echo $secondCourseList;?>';
</script>