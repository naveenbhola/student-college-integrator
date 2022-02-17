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
<div class="explore-guide-section clearwidth">
    <h2>Explore more section of this guide</h2>
    <div class="explore-grids">
        <ul>
            <li>
                <?php
                $count=1;
                foreach($leftNavData as $key=>$linkDetails){
					if($linkDetails['active'] != 1){
						$myIcon = $iconArray[$key];
				?>
						<a class="explore-grid-col <?= ($count%3==0)?"last-col":''?> <?= ($linkDetails['active']==1)?"active":"";?>"  <?=(($linkDetails['active']==1)?' onclick="navigate(\'examPageHeadingTitle\')" href="javascript:void(0)"':(' href="'.$linkDetails['url'].($key==1?'?sectionAbout=1':'').'"'))?>>
							<div>
								<i class="abroad-exam-sprite <?=$myIcon?> flLt"></i>
								<p class="exam-info"><?=htmlentities($linkDetails['label'])?></p>
							</div>
						</a>    
				<?php
						echo ($count%3==0)? "</li><li>":"";
						$count++;
					}
                }?>                
            </li>
        </ul>
    </div>
</div>