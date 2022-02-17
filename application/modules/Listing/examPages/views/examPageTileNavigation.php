<?php

$sectionNamesMapping = $this->config->item("sectionNamesMapping");

$gridClassCombinations  = array( 4 => array("grid-rows-2", "grid-rows-5", "grid-rows-6", "grid-rows-7"),
				 5 => array("grid-rows-2", "grid-rows-6", "grid-rows-5", "grid-rows-9", "grid-rows-7"),
				 6 => array("grid-rows-2", "grid-rows-1", "grid-rows-5", "grid-rows-4", "grid-rows-9", "grid-rows-7"),
				 7 => array("grid-rows-2", "grid-rows-1", "grid-rows-5", "grid-rows-6", "grid-rows-4", "grid-rows-7", "grid-rows-9"),
				 8 => array("grid-rows-1", "grid-rows-2", "grid-rows-4", "grid-rows-5", "grid-rows-6", "grid-rows-7", "grid-rows-8", "grid-rows-9"),
				 9 => array("grid-rows-1", "grid-rows-2", "grid-rows-3", "grid-rows-4", "grid-rows-5", "grid-rows-6", "grid-rows-7", "grid-rows-8", "grid-rows-9"));

$gridTilesCombination 	= array( 4 => array(array(2),array(1,1),array(2)),
				 5 => array(array(2),array(1,1),array(1,1)),
				 6 => array(array(1,1),array(1,1),array(1,1)),
				 7 => array(array(2,1),array(1,1,1),array(1,2)),
				 8 => array(array(2,1),array(1,1,1),array(1,1,1)),
				 9 => array(array(1,1,1),array(1,1,1),array(1,1,1)));

$sectionIconMapping  = array('home' 		=> 'abt-icon-large',
			     'syllabus' 	=> 'syllabus-icon-large',
			     'imp_dates' 	=> 'date-icon-large',
			     'colleges' 	=> 'college-icon-large',
			     'colleges'		=> 'collge-icon-large',
			     'article'		=> 'newsArticle-icon-large',
			     'preptips'		=> 'prep-tip-large',
			     'results'		=> 'result-icon-large',
			     'discussion'	=> 'discussion-icon-large'
			    );

$sectionUrlForTiles 			= $sectionUrl;
$sectionUrlForTiles['home']['url'] 	= "javascript:;";
$onclickEvent 				= array("home" => "scrollToSection('wiki-sec-0');");
$numberOfTile 				= count($activeTileDetails);

if($numberOfTile < 4 || $numberOfTile > 9)
	return;
?>
<div class="exam-grid-box clearfix">
<?php 
	$index = 0;
	foreach($gridTilesCombination[$numberOfTile] as $key=>$column)
	{
		echo "<div class='exam-grid-".($key+1)."'>";
		foreach($column as $tile)
		{
			$extraClass = ($tile == 2) ? 'grid-merged' : '';
			if($sectionNamesMapping[$activeTileDetails[$index]['name']] == 'Top Colleges') {
                $sectionName = 'Colleges Accepting '.$examPageData->getExamName();
            } else {
                $sectionName = $sectionNamesMapping[$activeTileDetails[$index]['name']];
            } ?>
			<a class="<?=$onclickEvent[$activeTileDetails[$index]['name']] ? '_exam-hm-scroll' : ''?> <?=$gridClassCombinations[$numberOfTile][$index]?> <?=$extraClass?>" onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'tile_navigation', '<?=$sectionNamesMapping[$activeTileDetails[$index]['name']]?>');" href="<?=trim($sectionUrlForTiles[$activeTileDetails[$index]['name']]['url']) ? html_escape($sectionUrlForTiles[$activeTileDetails[$index]['name']]['url']) : "javascript:void(0);"?>" <?=($activeTileDetails[$index]['name'] == 'colleges' && $sectionUrlForTiles[$activeTileDetails[$index]['name']]['url']) ? 'target="_blank"' : ''?>>
				<strong><?=$sectionName == "Preparation Tips" ? "Prep Tips" : html_escape($sectionName)?></strong>
				<p><?=nl2br(html_escape($activeTileDetails[$index]['description']))?>

				<?php if($sectionName=='About Exam' && $trackMainExamName=='SNAP'){?><br/><br/><span style="font-size:14px;">Registrations open for SNAP 2017 - </span><span onclick="window.open('http://www.snaptest.org/?utm_source=shiksha&utm_medium=exampageone&utm_campaign=shikshaep');" style="color:#0038a2;font-size:14px;">Apply Now</span><?php } ?>
                                <?php if($sectionName=='About Exam' && $trackMainExamName=='IBSAT'){?><br/><br/><span style="font-size:14px;">Registrations open for IBSAT 2017 - </span><span onclick="window.open('https://bit.ly/IBSAT2k17');" style="color:#0038a2;font-size:14px;">Apply Now</span><?php } ?>
                                <?php if($sectionName=='About Exam' && $trackMainExamName=='XAT'){?><br/><br/><span style="font-size:14px;">XAT 2018 registrations open - </span><span onclick="window.open('http://bit.ly/2wW04JC');" style="color:#0038a2;font-size:14px;">Apply Now</span><?php } ?>

				</p>
				<i class="exam-sprite <?=$sectionIconMapping[$activeTileDetails[$index]['name']]?>"></i>
			</a>
		<?php
			$index++;
		}
		echo "</div>";
	}

?>
</div>
