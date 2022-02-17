                <?php
                if(!empty($URLWidgetData)) {
		?>
                <div class="other-details-wrap clear-width">
                    <div class="choice-list">
                            <h2><?php echo $URLWidgetData['URLWidgetHeading']; ?></h2>
                            <?php
                            $viewNum = 0;
                            $categoryPageURLs = $URLWidgetData['categoryPageURLs'];
                            foreach($categoryPageURLs as $view => $links) {
                                    $viewNum++;
                                    $URLs = $links['URL'];
                                    $text = $links['text'];
                                    $tracking = $links['tracking'];
                                    $count = count($URLs) > 2 ? 2 : count($URLs);
                                    
                                    if($count > 0) {
                                            echo '<div class="flLt choice-box">';
                                            echo '<strong>'.$view.'</strong>';
                                            echo '<ul class="choice-course">';
                                            
                                            for($index = 0; $index < $count; $index++) {
                                                    echo '<li><a href="'.$URLs[$index].'" onclick="processActivityTrack('.$course->getId().', 0, '.$institute->getId().', \'LP_CatPageLinks_Viewed\', \'LP_CatPageLinks\', \''.$tracking[$index].'\', \''.$URLs[$index].'\', event);">'.$text[$index].'</a></li>';
                                            }
                                            
                                            echo '</ul>';
                                            echo '</div>';
                                            
                                            if($viewNum % 2 == 0) {
                                                    echo '<div class="clearFix"></div>';
                                            }
                                    }
                            }
                            ?>
                    </div>
                </div>
		<?php
		}
		?>
