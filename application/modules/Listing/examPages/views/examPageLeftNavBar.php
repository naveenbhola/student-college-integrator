<?php
$sectionNamesMapping = $this->config->item("sectionNamesMapping");

$sectionIconMapping  = array('home' 		=> 'abt-icn',
							'syllabus'           => 'syllabus-icn',
							'imp_dates'          => 'imp-date-icn',
							'colleges'           => 'college-icn',
							'results'            => 'result-icn',
						     'article'			=> 'news-article-icn',
							'preptips'		=> 'prep-tips-icn',
							'discussion' 	=> 'discussion-icn'
                            );
?>
<div class="exam-left-col">
	<ul>
		<?php
		
			foreach($activeTileDetails as $activeTileRow)
			{
				// don't show the nav link if its flag is off
				if($activeTileRow['show_link_in_menu'] == 0 )
					continue;
				if($sectionNamesMapping[$activeTileRow['name']] == 'Top Colleges') {
	                $sectionName = 'Colleges Accepting '.$examPageData->getExamName();
	                $spanClass	= 'class="top-collegeLeft-nav"';
	            } else {
	                $sectionName = $sectionNamesMapping[$activeTileRow['name']];
	            }
		?>
		<li>
			<h2>
				<a onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'left_navigation_bar', '<?=$sectionNamesMapping[$activeTileRow['name']]?>');" href="<?=($pageType == $activeTileRow['name'] || empty($sectionUrl[$activeTileRow['name']]['url'])) ? 'javascript:;' :html_escape($sectionUrl[$activeTileRow['name']]['url'])?>" class="<?=($pageType == $activeTileRow['name']) ? 'active' : ''?> <?=($pageType == $activeTileRow['name']) ? '_exam-hm-scroll' : '' ?>" <?=($activeTileRow['name'] == 'colleges' && !empty($sectionUrl[$activeTileRow['name']]['url'])) ? 'target="_blank"' : ''?>>
					<i class="exam-sprite <?=$sectionIconMapping[$activeTileRow['name']]?>"></i>
					<span <?=$spanClass?> ><?=$sectionName == "Preparation Tips" ? "Prep Tips" : $sectionName?></span>
					<?php if($activeTileRow['name'] == 'home' && $pageType == 'home' || $activeTileRow['name'] == 'results' && $pageType == 'results') { ?>
						<i class="exam-sprite dwn-arrw"></i>
					<?php } ?>
				</a>
			</h2>
		<?php
			if($activeTileRow['name'] == 'home' && $pageType == 'home')
			{
				echo '<ul class="exam-sub-list">';
				$homePageData 	= $examPageData->getHompageData();
					$wikiCount 	= 0;
					foreach($homePageData as $homePageWiki)
					{
						// to check if wiki description is empty or not
						$wikiDescription = trim(strip_tags(html_entity_decode(str_replace("&nbsp;"," ",$homePageWiki->getDescription()))));
						
						// remove the wiki that need not to be shown as wiki sections
						if(!in_array($homePageWiki->getLabel(), array('Exam Title', 'Official Website')) && !empty($wikiDescription))
						{
							//<i class="exam-sprite exam-pointer">
							echo '<li><a href="javascript:void(0);" scrollto="wiki-sec-'.$wikiCount.'" class="active"><i class="exam-sprite exam-pointer" style=""></i><span>'.$homePageWiki->getLabel().'</span></a></li>';
							$wikiCount++;
						}
					}
				echo '</ul>';
			}elseif($activeTileRow['name'] == 'results' && $pageType == 'results'){
				echo '<ul class="exam-sub-list">';
				$resultPageData 	= $examPageData->getResultsData();
					$wikiCount 	= 0;
					foreach($resultPageData as $key=>$resultPageWiki)
					{
						if($key == 'Declaration Date'){
							$resultStartDate = $resultPageWiki->getStartDate();
							$resultEndDate = $resultPageWiki->getEndDate();

							if(!empty($resultStartDate) && !empty($resultEndDate)){
								echo '<li><a href="javascript:void(0);" scrollto="wiki-sec-'.$wikiCount.'" class="active"><i class="exam-sprite exam-pointer" style=""></i><span> Result Date </span></a></li>';
								$wikiCount++;
							}
						}elseif($key =='Topper interview'){
							$interviewLabel = $resultPageWiki[0]->getLabel();
							if(!empty($interviewLabel)){
								echo '<li><a href="javascript:void(0);" scrollto="wiki-sec-'.$wikiCount.'" class="active"><i class="exam-sprite exam-pointer" style=""></i><span> Topper Interviews </span></a></li>';
								$wikiCount++;
							}
						}else{

								// to check if wiki description is empty or not
							$wikiDescription = trim(strip_tags(html_entity_decode(str_replace("&nbsp;"," ",$resultPageWiki->getDescription()))));
						
							// remove the wiki that need not to be shown as wiki sections
							if(!empty($wikiDescription))
							{	
								$resultSectionLabel = $resultPageWiki->getLabel();
								$resultSectionLabel = ($resultSectionLabel == "Student Reaction" ? "Students' Reaction": $resultSectionLabel);
								//<i class="exam-sprite exam-pointer">
								echo '<li><a href="javascript:void(0);" scrollto="wiki-sec-'.$wikiCount.'" class="active"><i class="exam-sprite exam-pointer" style=""></i><span>'.$resultSectionLabel.'</span></a></li>';
								$wikiCount++;
							}

						}
						
					}
				echo '</ul>';
			}
		?>
		   </li>
		<?php
			}
		?>
	</ul>
</div>
