<?php
	$iconArray = array(
		"0" => "abt-icon",
		"1" => "pattern-icon",
		"2" => "score-icon",
		"3" => "date-icon",
		"4" => "prep-tip-icon",
		"5" => "paper-icon",
		"6" => "syllabus-icon",
		"7" => "college-icon",
		"8" => "article-icon"
	);
?>
<div class="exam-left-col" id="leftNavBar">
	<ul>
		<?php
			foreach($leftNavData as $key => $leftNavSection){
				$myIcon = $iconArray[$key];
		?>
				<li>
					<h2>
						<a <?=(($leftNavSection['active']==1)?' onclick="navigate(\'examPageHeadingTitle\')" class="active0 active" href="javascript:void(0)"':(' href="'.$leftNavSection['url'].($key==1?'?sectionAbout=1':'').'"'))?>>
							<i class="abroad-exam-sprite <?=$myIcon?>"></i>
							<span><?=htmlentities($leftNavSection['label'])?></span>
						</a>
						<?php 
						if(!empty($leftNavSection['subsections']) && false){
							echo "<ul class='exam-sub-menu'>";
							$sectionCount = 1;
							foreach($leftNavSection['subsections'] as $subkey=>$subsection){
						?>
								<li>
									<a <?=(($sectionCount==1)?"class='active'":"")?> href="javascript:void(0)" onclick="navigate('section<?=$sectionCount?>')" id="leftNavBarSubsection<?=$sectionCount?>">
										<i class="abroad-exam-sprite menu-arr"></i>
										<span><?=htmlentities($subsection['heading'])?></span>
									</a>
								</li>
						<?php
								$sectionCount++;
							}
							echo "</ul>";
						}
						?>
					</h2>
				</li>
		<?php
			}
		?>
		<?php if($examPageObj->getDownloadLink()){ ?>
			<li>
				<a style="margin:9px 18px 5px; vertical-align:middle; border-radius: 2px;padding: 8px;" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="directDownloadORShowOneStepLayer('<?=base64_encode($examPageObj->getDownloadLink())?>)','<?=$examPageObj->getExamPageId()?>','<?=$loggedInUserData['isLDBUser']?>',492);">
					<span class="font-12" style="font-weight:bold;text-align:middle;color:#fff;">Download Exam Guide</span>
				</a>
				<span id="downloadGuideLeftNavBar" style="margin: 1px 18px;font-size: 11px;<?php if(!is_numeric($guideDownloadCount) || $guideDownloadCount < 50){ ?>display:none;<?php } ?>"><?=$guideDownloadCount?> people downloaded this</span>
				<?php if(is_numeric($guideDownloadCount) && $guideDownloadCount >= 50){ ?><br/><?php } ?>
				<span style="height:5px;">&nbsp;</span>
			</li>
		<?php } ?>
	</ul>
</div>