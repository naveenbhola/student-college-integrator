<?php
$examId 	= $page_request->getExamId();
$cityId 	= $page_request->getCityId();
$stateId 	= $page_request->getStateId();
$countryId 	= $page_request->getCountryId();

if(array_key_exists('subcat_name', $ranking_page_cat_details)){
		$subcategoryName = $ranking_page_cat_details['subcat_name'];
}

$subcategoryName = str_replace("Full Time", "", $subcategoryName);
$subcategoryName = trim($subcategoryName);

if(!empty($seo_links)){
?>
		<div class="ranking-courses">
				<h2>Related results for <?php echo $subcategoryName;?> courses</h2>
				<table width="100%" border="0">
						<?php
						$mainSEOLinks 	= array_slice($seo_links, 0, 8);
						$extraSEOLinks 	= array_slice($seo_links, 8);
						$maxTrElements = ceil(count($mainSEOLinks) / 4);
						$maxTdInRow = 4;
						$count = 0;
						for($trCount = 0; $trCount < $maxTrElements; $trCount++){
						?>
								<tr>
								<?php
								$tdEncountered = 0;
								for(;$count < count($mainSEOLinks); $count++){
								?>
										<td valign="top">
												<div>
														<a href="<?php echo $mainSEOLinks[$count]->getURL();?>"><?php echo $mainSEOLinks[$count]->getName();?></a>
												</div>
										</td>
										<?php
										$tdEncountered++;
										if($tdEncountered % $maxTdInRow == 0){
												$count++;
												break;
										}
								}
								?>
								</tr>
								<?php
						}
						?>
				</table>
				<?php
				if(!empty($extraSEOLinks)){
				?>
						<div class="clearFix"></div>
						<div id="extra_seo_links" style="display:none;">
								<table width="100%" border="0">
								<?php
								$maxTrElements = ceil(count($extraSEOLinks) / 4);
								$maxTdInRow = 4;
								$count = 0;
								for($trCount = 0; $trCount < $maxTrElements; $trCount++){
								?>
										<tr>
										<?php
										$tdEncountered = 0;
										for(;$count < count($extraSEOLinks); $count++){
										?>
												<td valign="top">
														<div>
																<a uniqueattr="RankingPage/bttomseolinks" href="<?php echo $extraSEOLinks[$count]->getURL();?>"><?php echo $extraSEOLinks[$count]->getName();?></a>
														</div>
												</td>
												<?php
												$tdEncountered++;
												if($tdEncountered % $maxTdInRow == 0){
														$count++;
														break;
												}
										}
										if($maxTrElements == 1 && count($extraSEOLinks) < 4){
												$extraTDNeeded = $maxTdInRow - count($extraSEOLinks);
												for($c=0; $c < $extraTDNeeded; $c++){
												?>
														<td valign="top">&nbsp;</td>
												<?php
												}
                                        }
										?>
										</tr>
										<?php
								}
								?>
								</table>
						</div>
						<div style="text-align:right;"><a uniqueattr="RankingPage/viewmoreseolinks" id="view_more_seo_link" href="javascript:void(0);" onclick="showExtraSEOLinks();">+ View more</a></div>
				<?php
				}
				?>
				
		</div>
<?php
}

$sourceStyle = "margin:0px 0px 15px 0px";
if(!empty($seo_links)){
		$sourceStyle = "";
}
?>
<div class = "ranking-source" style="<?php echo $sourceStyle;?>">
</div>