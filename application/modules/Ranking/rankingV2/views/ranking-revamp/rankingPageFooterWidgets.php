<div class="ranking-wrapper clear-width">
	<div class="related-ranking-sec">
		<div class="related-rnk-widget">
            <?php 
                function sanitizeArticleTitle($content, $charToDisplay) {
                    if(strlen($content) <= $charToDisplay) {
                        return($content);
                    } else {
                        return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))) ;
                    }
                }
                if($subCategoryId == '23') {
                    $height = '137px';
                }else {
                    $height = '209px';
                }
                if(is_array($articleWidgetsData) && count($articleWidgetsData) > 0){
            ?>
			<div class="widget-col clear-width">
				<h2>
					Articles about Top <?php echo $rankingPage->getName();?> Colleges and Courses
				</h2>
				<div class="widget-box-2 clear-width"  style="height:auto;">
					<div class="widget-details">
						<ul>
                            <?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageArticleWidgetNew'); ?>
                        </ul>
					</div>
				</div>
			</div>
            <?php } ?>
            <?php if(is_array($examWidgetData) && count($examWidgetData) > 0){ ?>
                <div type='exams' class="widget-col clear-width">
				    <h2>Know more about <?php echo $rankingPage->getName();?> exams</h2>
				    <div class="widget-box-2 clear-width">
                        <div id="examScrollDiv" class="widget-details scrollbar1" style="min-height: <?php echo $height;?>; width:100%;">
                            <div class="scrollbar" style="margin-right: 6px; height: 100px;">
                                <div class="track" style="height: 100px;">
                                    <div class="thumb" style="top: 80px; height: 20px;"></div>
                                </div>
                            </div>
                            <div class="viewport newRecommendToCrwl" style="height: 105px;">
                                <div class="overview" style="width: 98%; top: -2386px;">
                                    <ul>
                                        <?php foreach ($examWidgetData as $id => $data){ ?>
                                            <li class="flLt">
                                                <a href="<?php echo $data['url']; ?>">
                                                    <span><?php echo $data['name'];?></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
				    </div>
                </div>
            <?php } ?>
            <div class="widget-col clear-width last">
                <div class="widget-details">
                    <?php 
                        $bannerProperties = array('pageId'=>'RANKING', 'pageZone'=>'BOTTOM_RIGHT');
                        $this->load->view('common/banner',$bannerProperties);
                    ?>
                </div>
            </div>
        </div>
	</div>
</div>
<div class="page-break clear-width"></div>
<div class="clearFix"></div>
