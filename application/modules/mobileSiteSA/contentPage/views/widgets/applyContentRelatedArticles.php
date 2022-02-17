<?php  if(!empty($popularArticlesData)){ ?>
<section  class="detail-widget articlWidget"  data-enhance="false">
    <div class="detail-widegt-sec clearfix">
        <div class="detail-info-sec clearfix">
            <div class="more-app-process-sec" style="margin:0px;">
                	<strong style="width:100%; float:left;margin-bottom:7px;">Article<?php if(count($popularArticlesData)>1)echo 's'; ?> related to <?php echo htmlentities($contentDisplayValue);?></strong>
                    <ul class="apply-cont-related-articles-widget">
                        <?php foreach ($popularArticlesData as $value) { ?>
                   		 <li>
                        	<a href="<?php echo $value['contentUrl']; ?>"><?php echo htmlentities($value['strip_title']); ?></a>
                            <?php if($value['viewCount']!="0" || $value['commentCount']!="0" ){ ?>
                                <p class="comment-view-nfo">
                                <?php if($value['commentCount']!="0" || !empty($value['commentCount'])) {?>
                                    <i class="mobile-sop-sprite sop-comment-icon"></i>
                                    <?php echo $value['commentCount'];
                                          echo ($value['commentCount']=="1")?" comment":" comments"; } ?>
                                <?php if($value['viewCount']!="0" && $value['commentCount']!="0" ){echo  '|';  }?>
                                <?php if($value['viewCount']!="0" || !empty($value['viewCount'])){
                                        echo $value['viewCount']; 
                                        echo ($value['viewCount']=="1")?" view":" views";  } ?>
                                </p>
                            <?php } ?>
                        </li>
                        <?php } ?>
                    </ul>
            </div>
        </div>
    </div>
</section>
<?php }?>