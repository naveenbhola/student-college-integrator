<div id="topBar" <?php echo ($request->isFilterAjaxCall() || $request->isAjaxCall()?'':'style="display:none"'); ?> >
<?php
	global $queryStringAliasToSolrFieldMapping;
	$queryStringAliasToSolrFieldMapping = array_flip($queryStringAliasToSolrFieldMapping);
	$appliedFilters = $request->getAppliedFilters();
	if(count($appliedFilters)>0) {
?>
    <p class="slct-opt">Your selection:
		<?php foreach($scholarshipData['appliedFilterData'] as $fieldName=>$rows){
			//$availableFilter = array_diff(array_keys($scholarshipData['filterData'][$fieldName."_parent"]),array_keys($scholarshipData['filterData'][$fieldName]));
			$availableFilter = $scholarshipData['filterData'][$fieldName];
			//var_dump($availableFilter);
				$label = $nMoreLabel = $flValue = "";
				switch($fieldName)
				{
					case 'saScholarshipCountryId':
						$label = "Destination:";
						$nMoreLabel = (count($rows)>1 ? ' <em class="more__flags">+'.(count($rows)-1).' more</em>':'');
						$mainLabel = reset($rows)['name'];
						$flValue = 'resetAll';
						break;
					case 'saScholarshipStudentNationality':
						$label = "Citizenship:";
						$nMoreLabel = (count($rows)>1 ? ' <em class="more__flags">+'.(count($rows)-1).' more</em>':'');
						$mainLabel = reset($rows)['name'];
						$flValue = 'resetAll';
						break;
					case 'saScholarshipAwardsCount':
						$label = "Awards:";
						$mainLabel = $rows['minLabel']." - ".$rows['maxLabel'];
						$flValue = 'resetSl';
						break;
					case 'saScholarshipAmount':
						//$label = "Amount";
						$mainLabel = $rows['minLabel']." - ".$rows['maxLabel'];
						$flValue = 'resetSl';
						break;
					case 'saScholarshipApplicationEndDate':
						//$label = "Deadline";
						$mainLabel = $rows['minLabel']." - ".$rows['maxLabel'];
						$flValue = 'resetSl';
						break;
				}
				$label = $label == ""?"":"<strong>".$label."</strong> ";
				// print slider labels directly & move on to next filter

				if(in_array($fieldName,array('saScholarshipAmount','saScholarshipApplicationEndDate','saScholarshipAwardsCount','saScholarshipCountryId','saScholarshipStudentNationality')))
				{
					echo '<span class="'.$disabledClass.'"> '.$label.$mainLabel.$nMoreLabel.' <i class="dis__slct" alias="'.$queryStringAliasToSolrFieldMapping[$fieldName].'" flvalue="'.$flValue.'"></i></span>';
					continue;
				}
				foreach($rows as $id => $row)
				{
					if(in_array(strtolower($id),$appliedFilters[$fieldName])){
						if(in_array($fieldName, array('saScholarshipCategory','saScholarshipType','saScholarshipCourseLevel','saScholarshipIntakeYear')))
						{
							$name = ($id);
							if($fieldName == 'saScholarshipCourseLevel')
							{
								$id = strtolower($id);
							}else if($fieldName == 'saScholarshipIntakeYear')
							{
								$id = $id;
								$name = $id;
							}
							else if($fieldName == 'saScholarshipCategory')
							{
								$id = strtolower($id);
								if($id == 'external'){
									$name = 'Non-college specific scholarships';
								}else{
									$name = 'College specific scholarships';
								}
							}
						}
						else{
							$name = $row['name'];
						}
						
						echo '<span class="'.$disabledClass.'"> '.$label.ucfirst($name).' <i class="dis__slct" alias="'.$queryStringAliasToSolrFieldMapping[$fieldName].'" flvalue="'.$id.'"></i></span>';
					}
				}
			}
		?>
	</p>
<?php } ?>
</div>