<?php if(is_array($examWidgetData) && count($examWidgetData) > 0){ ?>
    <div class="rnk_slider">
        <h2 class="f-20 fnt__sb">Popular <?php echo $rankingPage->getName();?> Exams</h2>
        <div class="rnk_bx ps__rl exm_bx" id="examInterlinkingWidget">
            <a id="navPhotoPrev" class="arrw-bx hid prv"><i class="arrows prev"></i></a>
            <div class="r-caraousal">
                <ul class="featuredSlider">
                <?php foreach ($examWidgetData as $id => $data){ 
			$year = ($data['year'] != '')?' '.$data['year']:'';
			?>
                    <li>
                        <a title="<?=$data['name'];?>" href="<?=$data['url']; ?>" target="_blank" ga-attr="EXAMINTERLINK">
                            <div class="art_card">
                                <p class="f-16 fnt__sb" title="<?=$data['name'];?>"><?=$data['name'];?><?=$year?></p>
				<!--<p class="fnt__sb"><?=$data['fullName'];?></p>-->
                                <span class="link_blue f-14 m_10top block">View Exam Details</span>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                </ul>
            </div>
            <a id="navPhotoNxt" class="arrw-bx nxt hid"><i class="arrows next"></i></a>
        </div>
    </div>
<?php } ?>
