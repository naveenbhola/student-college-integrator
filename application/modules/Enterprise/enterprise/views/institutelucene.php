<?php

$normalInstitutes = $data['normal_institutes'];
$sponsoredInstitutes = $data['sponsored_institutes'];
$featuredInstitutes = $data['featured_institutes'];

$singleResult = $data['solr_institute_data']['single_result'];
$singleResultType = $data['solr_institute_data']['single_result_type'];

$totalResultOnCurrentPage = count(array_keys($normalInstitutes)) + count(array_keys($sponsoredInstitutes)) + count(array_keys($featuredInstitutes));

$currentPagestart = $data['solr_institute_data']['start'];
$resultPerPage = $data['general']['rows_count']['institute_rows'];

$normalInstitutesOnPage = count(array_keys($normalInstitutes));
$nextPageStart = $currentPagestart + $normalInstitutesOnPage;
$totalResultForThisSearch = $data['solr_institute_data']['total_institute_groups'];

//if($nextPageStart == $resultPerPage){
//	$nextPageStart = 0;
//} else {
//	$nextPageStart = $nextPageStart - $normalInstitutesOnPage;
//}
?>
<!--Pagination Related hidden fields Starts-->
<input type="hidden" autocomplete="off" id="cmsSearchTotal" value="<?php echo $totalResultForThisSearch;?>"/>
<input type="hidden" autocomplete="off" id="cmsSearchstart" value="<?php echo (int)$nextPageStart?>"/>
<input type="hidden" autocomplete="off" id="cmsSearchCount" value="<?php echo $resultPerPage;?>"/>
<input type="hidden" autocomplete="off" id="cmsSearchMethodName" value="searchLuceneCMS"/>
<!--Pagination Related hidden fields Ends  -->
<?php
$count = 1;
if((int)$singleResult == 1){
	$breakFlag = false;
	foreach($normalInstitutes as $categoryId => $data){
		if(!$breakFlag){
			foreach($data as $courseId => $courseDocument){
				?>
				<div id="collegeTable_<?php echo $count;?>" name="collegeTable[]" class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer;line-height:25px;" onClick="selectFieldsCollege(this.id,'<?php echo $courseDocument->getInstitute()->getId();?>','');">
					<div class="float_L" style="width:50%;">
						<div class="mar_full_10p">
							<span class="bld">
								<a href="javascript:void(0);"><?php echo $courseDocument->getInstitute()->getName();?></a>
							</span>
						</div>
					</div>
					<div class="float_L" style="width:20%;">
						<div class="mar_full_10p">&nbsp;</div>
					</div>
					<div class="float_L" style="width:20%;">
						<div class="mar_full_10p"><?php echo $courseDocument->getInstitute()->getInstituteType();?></div>
					</div>
					<div class="clear_L"></div>
				</div>
				<?php
				$count++;
				$breakFlag = true;
				break;
			}	
		} else {
			break;
		}
	}
} else {
	// Show sponsored institutes
	foreach($sponsoredInstitutes as $instituteId => $courses){
		foreach($courses as $courseDocument){
		?>
			<div id="collegeTable_<?php echo $count;?>" name="collegeTable[]" class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer;line-height:25px;" onClick="selectFieldsCollege(this.id,'<?php echo $courseDocument->getInstitute()->getId();?>','');">
				<div class="float_L" style="width:50%;">
					<div class="mar_full_10p">
						<?php
						if($usergroup == "cms") {
							$imgS = "<img src='/public/images/sponsored.gif' alt='Sponsored' title='Sponsored' align='absmiddle'>";
							echo $imgS;
						}
						?>
						<span class="bld">
							<a href="javascript:void(0);"><?php echo $courseDocument->getInstitute()->getName();?></a>
						</span>
					</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p">&nbsp;</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p"><?php echo $courseDocument->getInstitute()->getInstituteType();?></div>
				</div>
				<div class="clear_L"></div>
				<input type="hidden" id="ifCheckBoxOrLink_<?php echo $i; ?>" value="<?php echo !(isset($d['groupId']) && ($d['groupId'] != $d['typeId'].'00' ) && ($d['groupId'] != '')) ?>"/>
				<div class="clear_L"></div>
			</div>
			<?php
			$count++;
			break; //Break after every first course document
		}
	}
	
	// Show featured institutes institutes
	foreach($featuredInstitutes as $instituteId => $courses){
		foreach($courses as $courseDocument){
		?>
			<div id="collegeTable_<?php echo $count;?>" name="collegeTable[]" class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer;line-height:25px;" onClick="selectFieldsCollege(this.id,'<?php echo $courseDocument->getInstitute()->getId();?>','');">
				<div class="float_L" style="width:50%;">
					<div class="mar_full_10p">
						<?php
						if($usergroup == "cms") {
							$imgF = "<img src='/public/images/featured.gif' alt='Featured' title='Featured' align='absmiddle'>";
							echo $imgF;
						}
						?>
						<span class="bld">
							<a href="javascript:void(0);"><?php echo $courseDocument->getInstitute()->getName();?></a>
						</span>
					</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p">&nbsp;</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p"><?php echo $courseDocument->getInstitute()->getInstituteType();?></div>
				</div>
				<div class="clear_L"></div>
				<input type="hidden" id="ifCheckBoxOrLink_<?php echo $i; ?>" value="<?php echo !(isset($d['groupId']) && ($d['groupId'] != $d['typeId'].'00' ) && ($d['groupId'] != '')) ?>"/>
				<div class="clear_L"></div>
			</div>
			<?php
			$count++;
			break; //Break after every first course document
		}
	}
	
	// Show normal institutes
	foreach($normalInstitutes as $instituteId => $courses){
		foreach($courses as $courseDocument){
		?>
			<div id="collegeTable_<?php echo $count;?>" name="collegeTable[]" class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer;line-height:25px;" onClick="selectFieldsCollege(this.id,'<?php echo $courseDocument->getInstitute()->getId();?>','');">
				<div class="float_L" style="width:50%;">
					<div class="mar_full_10p">
						<span class="bld">
							<a href="javascript:void(0);"><?php echo $courseDocument->getInstitute()->getName();?></a>
						</span>
					</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p">&nbsp;</div>
				</div>
				<div class="float_L" style="width:20%;">
					<div class="mar_full_10p"><?php echo $courseDocument->getInstitute()->getInstituteType();?></div>
				</div>
				<div class="clear_L"></div>
			</div>
			<?php
			$count++;
			break; //Break after every first course document
		}
	}
}
?>